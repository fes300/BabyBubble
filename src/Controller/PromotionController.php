<?php

namespace Babybubble\Controller;

//repositories
use Babybubble\Repository\UserRepository as UserRepo;

//models
use Babybubble\Model\UserModel;


class PromotionController {

    function __construct($app) {
        $this->app = $app;
    }

    function build() {
        $app = $this->app;

        $appointment = $app['controllers_factory'];

        $appointment->get('/', function() use($app){
            return $app['twig']->render('promotions/home.twig', ['page'=>'promotions']);
        });

        $appointment->post('/appointment', function() use($app){
            return $app->json($_POST);
        });

        return $appointment;
    }
}
