<?php

declare(strict_types=1);

spl_autoload_register(function (string $classNamespace) {
    $path = str_replace(['\\', 'App/'], ['/', ''], $classNamespace);
    $path = "src/$path.php";
    require_once($path);
});

require_once("src/Utils/debug.php");
$configuration = require_once("config/config.php");

use App\Controller\AbstractController;
use App\Controller\NoteController;
use App\Request;
use App\Exception\AppException;
use App\Exception\ConfigurationException;
use App\Controller\AbstractLogController;
use App\Controller\LogController;

$request = new Request($_GET, $_POST, $_SERVER);
session_start();
try {
    if (!isset($_SESSION['start'])) {
        AbstractLogController::initConfiguration($configuration);
        $logController = new LogController($request);
        $logController->run();


    } else {
        AbstractController::initConfiguration($configuration);
        (new NoteController($request))->run();
    }
} catch (ConfigurationException $e) {
    echo '<h1>Wystąpił błąd w aplikacji</h1>';
    echo 'Problem z applikacją';
} catch (AppException $e) {
    echo '<h1>Wystąpił błąd w aplikacji</h1>';
    echo '<h3>' . $e->getMessage() . '</h3>';
} catch (\Throwable $e) {
    echo '<h1>Wystąpił błąd w aplikacji</h1>';
}