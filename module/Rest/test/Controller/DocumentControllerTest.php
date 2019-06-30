<?php

/**
 * Created by PhpStorm.
 * User: yusuf
 * Date: 30.06.2019
 * Time: 15:55
 */

namespace Rest\Test\Controller;

use Rest\Controller\UserController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class DocumentControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = false;

    public function setUp()
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));
        parent::setUp();
    }


    public function testUserCanAccessDocument()
    {
        $userAccessToken = $this->getAccessToken();
        $userLoginParams = ['token' => $userAccessToken];
        $this->dispatch('/document/1', 'GET', $userLoginParams);
        $this->assertResponseStatusCode(200);
    }


    public function getAccessToken()
    {
        $userLoginParams = ['email' => 'y.yilmaz@hotmail.com.tr', 'password' => '123456'];
        $this->dispatch('/login', 'POST', $userLoginParams);
        $content = $this->getResponse()->getContent();
        $decodedContent = json_decode($content, true);
        return $decodedContent['api_content']['token'];
    }
}