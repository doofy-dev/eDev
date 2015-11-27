<?php
/**
 * Created by PhpStorm.
 * User: Tibi
 * Date: 2015.11.20.
 * Time: 13:24
 */
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Yaml\Yaml;

require_once "vendor/autoload.php";

$settings = YAML::parse(file_get_contents('config/config.yml'));

$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src/"), $isDevMode, null, null, false);

// database configuration parameters
$conn = $settings['Database'];

// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);
return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);