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

class RestControllerTest extends AbstractHttpControllerTestCase
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


    public function testUserCanLogin()
    {
        $this->login();
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Rest');
        $this->assertControllerName(UserController::class);
        $this->assertControllerClass('UserController');
        $this->assertMatchedRouteName('user.login');
    }

    public function testUserCanGetToken()
    {
        $this->login();
        $content = $this->getResponse()->getContent();
        $decodedContent = json_decode($content,true);
        $this->assertNotNull($decodedContent['api_content']['token']);

    }

    public function login(){
        $userLoginParams = ['email' => 'y.yilmaz@hotmail.com.tr', 'password' => '123456'];
        $this->dispatch('/login', 'POST', $userLoginParams);
    }
}