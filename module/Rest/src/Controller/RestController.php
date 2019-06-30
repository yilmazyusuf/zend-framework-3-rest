<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Rest\Controller;


use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Mvc\MvcEvent;

class RestController extends AbstractRestfulController
{

    protected $errorMessages = [];

    /**
     * @return array
     * @internal param array $moduleConfiguration
     */
    public function getModuleConfiguration() : array
    {
        return $this->getEvent()->getApplication()->getServiceManager()->get('configuration');
    }

    /**
     * @return EntityManager
     * @internal param MvcEvent $mvcEvent
     */
    protected  function getEntityManager(): EntityManager
    {
        return $this->getEvent()->getApplication()->getServiceManager()->get('Doctrine\ORM\EntityManager');
    }


}