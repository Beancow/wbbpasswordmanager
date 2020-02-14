<?php


namespace WBB;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class DataBaseController
{
    private $DBConfig;


    /**
     * DataBaseController constructor.
     */
    public function __construct()
    {
        $this->DBConfig = new WbbConfig();

        $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src"), $this->DBConfig->isDevMode,  $this->DBConfig->proxyDir,  $this->DBConfig->cache,  $this->DBConfig->useSimpleAnnotationReader);

    }
    public function Insert(){

    }
    public function Remove(){

    }
}