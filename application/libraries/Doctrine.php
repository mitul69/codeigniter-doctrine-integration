<?php
use Doctrine\Common\ClassLoader,
    Doctrine\ORM\Configuration,
    Doctrine\ORM\EntityManager,
    Doctrine\Common\Cache\ArrayCache,
    Doctrine\DBAL\Logging\EchoSQLLogger,
    Doctrine\ORM\Mapping\Driver\DatabaseDriver,
    Doctrine\ORM\Tools\DisconnectedClassMetadataFactory,
    Doctrine\ORM\Tools\EntityGenerator;
    
  class Doctrine {
 
  /**
   * @var EntityManager $em 
   */
    public $em = null;
 
  /**
   * constructor
   */
  public function __construct()
  {
    // load database configuration from CodeIgniter
    require APPPATH.'config/database.php';
     
    // Set up class loading. You could use different autoloaders, provided by your favorite framework,
    // if you want to.
    require_once APPPATH.'third_party/Doctrine/Common/ClassLoader.php';
 
    $doctrineClassLoader = new ClassLoader('Doctrine',  APPPATH.'third_party');
    $doctrineClassLoader->register();
    $entitiesClassLoader = new ClassLoader('models', rtrim(APPPATH, "/" ));
    $entitiesClassLoader->register();
    $proxiesClassLoader = new ClassLoader('proxies', APPPATH.'models');
    $proxiesClassLoader->register();
 
    // Set up caches
    $config = new Configuration;
    $cache = new ArrayCache;
    $config->setMetadataCacheImpl($cache);
    $driverImpl = $config->newDefaultAnnotationDriver(array(APPPATH.'models/Entities'));
    $config->setMetadataDriverImpl($driverImpl);
    $config->setQueryCacheImpl($cache);
 
    // Proxy configuration
    $config->setProxyDir(APPPATH.'models/proxies');
    $config->setProxyNamespace('Proxies');
 
    // Set up logger
    //$logger = new EchoSQLLogger;
    //$config->setSQLLogger($logger);
 
    $config->setAutoGenerateProxyClasses( TRUE );   
    // Database connection information
    $connectionOptions = array(
        'driver' => 'pdo_mysql',
        'user' =>     $db['default']['username'],
        'password' => $db['default']['password'],
        'host' =>     $db['default']['hostname'],
        'dbname' =>   $db['default']['database']
    );
 
    // Create EntityManager
    $this->em = EntityManager::create($connectionOptions, $config);   
   
     
  }
  
  /**
   * generate entity objects automatically from mysql db tables
   * @return none
   */
  function generate_classes(){     
       
    $this->em->getConfiguration()
             ->setMetadataDriverImpl(
                new DatabaseDriver(
                        $this->em->getConnection()->getSchemaManager()
                )
    );
 
    $cmf = new DisconnectedClassMetadataFactory();
    $cmf->setEntityManager($this->em);
    $metadata = $cmf->getAllMetadata();     
    $generator = new EntityGenerator();
     
    $generator->setUpdateEntityIfExists(true);
    $generator->setGenerateStubMethods(true);
    $generator->setGenerateAnnotations(true);
    $generator->generate($metadata, APPPATH."models/Entities");
     
  } 
}  
?>