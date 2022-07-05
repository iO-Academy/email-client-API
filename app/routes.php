<?php
declare(strict_types=1);

use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/', function ($request, $response, $args) use ($container) {
        $renderer = $container->get('renderer');
        return $renderer->render($response, "index.php", $args);
    });

    $app->get('/emails', '\Emails\Controllers\EmailController:getInboxEmails');
    $app->get('/emails/{id}', '\Emails\Controllers\EmailController:getEmail');
    $app->put('/emails/{id}', '\Emails\Controllers\EmailController:readEmail');
    $app->post('/emails', '\Emails\Controllers\EmailController:sendEmail');
    $app->delete('/emails/{id}', '\Emails\Controllers\EmailController:deleteEmail');

};
