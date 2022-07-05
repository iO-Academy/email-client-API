<?php
declare(strict_types=1);

use Slim\App;

return function (App $app) {
    // enable options requests
    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });

    // enable CORS
    $app->add(function ($request, $handler) {
        $response = $handler->handle($request);
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    });

    $app->get('/', function ($request, $response) {
        $html = '<h1>Email Client API</h1>
                    <p>To use this API please check the 
                        <a href="https://github.com/iO-Academy/email-client-API#api-documentation" target="_blank">documentation</a>.
                    </p>';
        $response->getBody()->write($html);
        return $response->withHeader('Content-type', 'text/html')->withStatus(200);
    });

    $app->get('/emails', '\Emails\Controllers\EmailController:getInboxEmails');
    $app->get('/emails/sent', '\Emails\Controllers\EmailController:getSentEmails');
    $app->get('/emails/deleted', '\Emails\Controllers\EmailController:getDeletedEmails');
    $app->get('/emails/{id}', '\Emails\Controllers\EmailController:getEmail');
    $app->put('/emails/{id}', '\Emails\Controllers\EmailController:readEmail');
    $app->post('/emails', '\Emails\Controllers\EmailController:sendEmail');
    $app->delete('/emails/{id}', '\Emails\Controllers\EmailController:deleteEmail');

};
