<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Rest\Controller;


use Rest\Attributes\DocumentGetAttributes;
use Rest\Entity\Post;
use Rest\Service\Encryption;
use Rest\Service\Response;
use Zend\Cache\StorageFactory;

/**
 * Class DocumentController
 * @package Rest\Controller
 */
class DocumentController extends RestController
{

    use Encryption;

    /**
     * @var Post
     */
    private $post;


    /**
     * @param $documentId
     * @return \Zend\View\Model\JsonModel
     */
    public function get($documentId)
    {

        $loginToken = $this->getRequest()->getHeader('token');
        $response = new  Response($this->getResponse());

        $config = $this->getModuleConfiguration();
        $redisConfig = $config['redis_cache'];
        $secretKey = $config['app_secret'];
        $cache = StorageFactory::factory($redisConfig);
        $cache->getOptions()->setTtl(3600);

        //Default Scheme
        $attributes = new DocumentGetAttributes();
        $response->setEndpointAttributes($attributes);

        //Missing Token
        if ($loginToken === false) {

            return $response->setHttpStatus(400)
                ->setMessages(['token' => 'Login Token Required'])
                ->setResult(false)
                ->setResponse();
        }

        $tokenString = $loginToken->getFieldValue();

        //Decryption Token
        $decrypedToken = Encryption::decrypt($secretKey, $tokenString);

        //Token Decryption Failed
        if ($decrypedToken === false || $decrypedToken =='') {

            return $response->setHttpStatus(401)
                ->setMessages(['token' => 'Invalid Token'])
                ->setResult(false)
                ->setResponse();
        }

        $encodeToken = json_decode($decrypedToken, true);
        $userBaseInfo = $encodeToken['user'];
        $tokenExpireTime = $encodeToken['token_expire_time'];

        //Token Time Expired (5 Minutes)
        if (time() > $tokenExpireTime) {
            return $response->setHttpStatus(401)
                ->setMessages(['token' => 'Token Expired'])
                ->setResult(false)
                ->setResponse();
        }

        //Cache Check
        $documentCacheKey = md5('document_' . $documentId);

        if ($cache->hasItem($documentCacheKey) === false) {

            //Get Document From Db
            $this->post = $this->getEntityManager()->getRepository(Post::class)
                ->findOneById($documentId);

            //Document not exist
            if (is_null($this->post)) {
                return $response->setHttpStatus(404)
                    ->setMessages(['token' => 'Document not found'])
                    ->setResult(false)
                    ->setResponse();
            }

            $postCacheData = [
                'title' => $this->post->getTitle(),
                'content' => $this->post->getContent(),
                'date' => $this->post->getDateCreated()->format('d.m.Y'),
            ];
            $cache->setItem($documentCacheKey, json_encode($postCacheData));
        }

        $getDocumentFromCache = json_decode($cache->getItem($documentCacheKey),true);

        //Success
        $attributes->setContent($getDocumentFromCache['content'])
            ->setTitle($getDocumentFromCache['title'])
            ->setDateCreated($getDocumentFromCache['date']);

        $response->setEndpointAttributes($attributes);

        return $response->setResponse();
    }


}
