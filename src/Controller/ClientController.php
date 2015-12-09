<?php

namespace Babybubble\Controller;

use Symfony\Component\Security\Core\User\User;

//repositories
use Babybubble\Repository\ClientRepository as ClientRepo;

//models
use Babybubble\Model\ClientModel as Client;


class ClientController {

    function __construct($app) {
        $this->app = $app;
    }

    function build() {
        $app = $this->app;

        $client = $app['controllers_factory'];

        $client->get('/', function() use($app){
            $clientRepo = new ClientRepo($app['db']);
            $clients = $clientRepo->getAll();
            return $app->render('clients/home.twig', ['clients'=>$clients, 'page'=>'clients']);
        })->secure('IS_AUTHENTICATED_REMEMBERED');

        $client->post('/client', function() use($app){
            $clientRepo = new ClientRepo($app['db']);
            $_POST['tutors'] = json_encode(explode(',', $_POST['tutors']));
            $client = new Client($_POST);
            return $app->json($clientRepo->insert($client));
        })->secure('IS_AUTHENTICATED_REMEMBERED');

        $client->get('/manage/{clientUuid}', function($clientUuid) use($app){
            $clientRepo = new ClientRepo($app['db']);
            $client = $clientRepo->getbyUuid($clientUuid);
            $client->tutors = implode(",",$client->tutors);
            return $app->render('clients/manageClient.twig', ['client'=>$client, 'page'=>'clients']);
        })->secure('IS_AUTHENTICATED_REMEMBERED');

        $client->post('/manage/{clientUuid}', function($clientUuid) use($app){
            $clientRepo = new ClientRepo($app['db']);
            $_POST['uuid'] = $clientUuid;
            return $app->json(new Client($_POST));
            return $app->json($userRepo->update($_POST));
        })->secure('IS_AUTHENTICATED_REMEMBERED');

        return $client;
    }
}
