<?php

require '../vendor/autoload.php';

use App\Controller\ContactController;
use App\Router;

$router = new Router();

$router->get('/welcome', function(){
    echo "Welcome page";
});

$router->get('/teste', function(array $params = []){

});

$router->post('/contact', ContactController::class.'@index');
$router->post('/contact', ContactController::class,'@index');


$router->addNotFoundPage(function(){
    $title = "Não encontrado";
    require __DIR__.'/../templates/error/error-page.php';
});


$router->run();
