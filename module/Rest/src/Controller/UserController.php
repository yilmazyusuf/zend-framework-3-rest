<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Rest\Controller;

use Rest\Attributes\UserLoginAttributes;
use Rest\Service\Authenticate;
use Rest\Service\Encryption;
use Rest\Service\Response;
use Rest\Validation\ValidateEmail;
use Rest\Validation\ValidatePassword;
use Zend\Mvc\MvcEvent;

class UserController extends RestController
{

    use Encryption;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        //Override rest create method
        $this->customHttpMethodsMap = ['post' => [$this, 'login']];
    }

    public function login(MvcEvent $mvcEvent)
    {
        $config = $this->getModuleConfiguration();
        $secretKey = $config['app_secret'];

        $entityManager = $this->getEntityManager();
        $request = $mvcEvent->getRequest();
        $response = new Response($mvcEvent->getResponse());

        $email = $request->getPost('email');
        $password = $request->getPost('password');

        //Default Scheme
        $attributes = new UserLoginAttributes();
        $response->setEndpointAttributes($attributes);

        //Validate Request
        $isValid = $this->validateLogin($email, $password);
        if ($isValid === false) {

            return $response->setHttpStatus(400)
                ->setMessages($this->errorMessages)
                ->setResult(false)
                ->setResponse();
        }

        //Login Attempt
        $authService = new Authenticate($entityManager);
        $login = $authService->authenticate($email, $password);

        //Login Success
        if ($login->getCode() == 1) {

            $user = $login->getIdentity();

            //Create Token
            $encryptData = [
                'user' => [
                    'id' => $user->getId(),
                    'email' => $user->getEmail(),
                ],
                'token_expire_time' => time() + (300) //5 Minutes
            ];

            $token = Encryption::encrypt($secretKey, json_encode($encryptData));

            $attributes->setToken($token);
            $response->setEndpointAttributes($attributes);

            return $response->setResponse();
        }

        return $response->setHttpStatus(401)
            ->setMessages($login->getMessages())
            ->setResult(false)
            ->setResponse();

    }


    /**
     * @param $email
     * @param $password
     * @return bool
     */
    public function validateLogin($email, $password): bool
    {

        $validateEmail = new ValidateEmail();
        $isValidEmail = $validateEmail->isValid($email);

        if ($isValidEmail === false) {
            $this->errorMessages[] = $validateEmail->getMessages();
            return false;
        }

        $validatePassword = new ValidatePassword();
        $isValidPass = $validatePassword->isValid($password);
        if ($isValidPass === false) {
            $this->errorMessages[] = $validatePassword->getMessages();
            return false;
        }

        return true;

    }

}

/*
 * docker-compose up -d
docker-compose exec app composer update
docker-compose exec app ./vendor/bin/doctrine-module --no-interaction migrations:migrate
docker-compose exec app composer serve


docker stop $(docker ps -a -q) && docker rm $(docker ps -a -q) && docker-compose up -d
docker system prune -a



docker-compose up -d
docker-compose exec rest composer update

docker-compose exec rest ./vendor/bin/doctrine-module --no-interaction migrations:migrate

docker system prune -a


ps -A xa | grep php


 */