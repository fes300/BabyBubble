<?php

namespace Babybubble\Controller;

//repositories
use Babybubble\Repository\UserRepository as UserRepo;

//models
use Babybubble\Model\UserModel;


class AppointmentController {

    function __construct($app) {
        $this->app = $app;
    }

    function build() {
        $app = $this->app;

        $admin = $app['controllers_factory'];

        $admin->post('/appointment', function() use($app){
            return $app->json($_POST);
        });

        return $admin;
    }
}
