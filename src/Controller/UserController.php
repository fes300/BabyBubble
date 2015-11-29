<?php

namespace Babybubble\Controller;

//repositories
use Babybubble\Repository\UserRepository as UserRepo;

//models
use Babybubble\Model\UserModel;


class UserController {

    function __construct($app) {
        $this->app = $app;
    }

    function build() {
        $app = $this->app;

        $admin = $app['controllers_factory'];

        $admin->get('/', function() use($app){
            $userRepo = new UserRepo($app['db']);
            $user = $userRepo->getByUsername($app['user']->getUsername());
            $users = $userRepo->getAll();
            return $app->render('users/home.twig', ['user'=>$user, 'users'=>$users, 'page'=>'users']);
        })->secure('ROLE_ADMIN');

        $admin->post('/user', function() use($app){
            $userRepo = new UserRepo($app['db']);
            $_POST['active'] = true;
            $user = new UserModel($_POST);
            return $app->json($userRepo->insert($user));
        })->secure('ROLE_ADMIN');

        return $admin;
    }
}
