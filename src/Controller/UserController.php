<?php

namespace Babybubble\Controller;

use Symfony\Component\Security\Core\User\User;

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

        $admin->get('/manage/{userUuid}', function($userUuid) use($app){
            $userRepo = new UserRepo($app['db']);
            $user = $userRepo->getbyUuid($userUuid);
            return $app->render('users/manageUser.twig', ['user'=>$user, 'page'=>'users']);
        })->secure('ROLE_ADMIN');

        $admin->post('/manage/{userUuid}', function($userUuid) use($app){
            $userRepo = new UserRepo($app['db']);
            $user = $userRepo->getbyUuid($userUuid);
            $_POST['uuid'] = $userUuid;

            if(!empty($_POST['password'])){
                $simphonyUser = new User($_POST['username'], $_POST['password'], [$_POST['role']], true, true, true, true);
                $encoder = $app['security.encoder_factory']->getEncoder($simphonyUser);
                $encodedPassword = $encoder->encodePassword($_POST['password'], $simphonyUser->getSalt());
                $_POST['password'] = $encodedPassword;
            }else{$_POST['password'] = $user->password;};

            return $app->json($userRepo->update($_POST));
        })->secure('ROLE_ADMIN');

        $admin->post('/user', function() use($app){
            $userRepo = new UserRepo($app['db']);
            $_POST['active'] = true;
            $user = new UserModel($_POST);
            $simphonyUser = new User($user->username, $user->password, [$user->role], true, true, true, true);
            $encoder = $app['security.encoder_factory']->getEncoder($simphonyUser);
            $encodedPassword = $encoder->encodePassword($user->password, $simphonyUser->getSalt());
            $user->password = $encodedPassword;
            return $app->json($userRepo->insert($user));
        })->secure('ROLE_ADMIN');

        return $admin;
    }
}
