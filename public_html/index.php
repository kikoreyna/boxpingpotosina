<?php
define('DS', DIRECTORY_SEPARATOR);
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/vendor/autoload.php');

use System\Core\Environment;
use System\Core\Route;
use System\Core\Xarvis;

Environment::prepare();
$xarvis = new Xarvis( new Route() );
$xarvis->layers();
$xarvis->prepare();
$xarvis->attend();
