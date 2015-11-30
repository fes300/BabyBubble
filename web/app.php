<?php

require_once __DIR__.'/../vendor/autoload.php';

//models
use Babybubble\Repository\UserModel as UserModel;

//libraries

//repositories
use Babybubble\Repository\UserRepository as UserRepo;

//controllers
use Babybubble\Controller\UserController;
use Babybubble\Controller\AppointmentController;

//Symfony components
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


//Extend Silex
use Silex\Application;
class MyApplication extends Application
{
    use Application\TwigTrait;
    use Application\SecurityTrait;
};

//Create own route
use Silex\Route;
class MyRoute extends Route
{
    use Route\SecurityTrait;
};

// setup
$app = new MyApplication();
$app['debug'] = true;
$app['route_class'] = 'MyRoute';

$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$defaultDbConfiguration = array(
    'driver'    => 'pdo_pgsql',
    'host'      => 'localhost',
    'dbname'    => 'babybubble',
    'user'      => 'babybubble',
    'password'  => '123',
    'charset'   => 'utf8',
);

$dbUrl = getenv("DATABASE_URL");
$isOnHeroku = !empty($dbUrl);
if ($isOnHeroku) {
    $app['debug'] = false;
    $dbUrl = parse_url($dbUrl);
    $defaultDbConfiguration['host'] = $dbUrl['host'];
    $defaultDbConfiguration['user'] = $dbUrl['user'];
    $defaultDbConfiguration['password'] = $dbUrl['pass'];
    $defaultDbConfiguration['dbname'] = substr($dbUrl['path'],1);
}

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => $defaultDbConfiguration,
));

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

//extend twig with global username and methods
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $username = empty($app['user']) ? "" : $app['user']->getUsername();
    $twig->addGlobal('userName', $username);

    $filter = new Twig_SimpleFilter('convertObjectToArray', function ($object) {
        if (gettype($object)!=='object') return $object;
        return (array) $object;
    });
    $twig->addFilter($filter);

    return $twig;
}));

// security
$userRepo = new UserRepo($app['db']);
$usersData = $userRepo->getUsersForSilex();

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
   'security.firewalls' =>  array(
       'admin' => array(
           'pattern' => '^(?!/login).*$',
           'http' => true,
           'form' => array(
               'login_path' => '/login',
               'check_path' => '/admin/login_check',
               'always_use_default_target_path' => true,
               'default_target_path' => '/'
           ),
           'logout' => array(
               'logout_path' => '/logout',
               'invalidate_session' => true
           ),
           'remember_me' => array(
                'key' => '44pinc3d229ebqbvqa6bpvlhp3'
            ),
            'users' => $usersData,
    )
)));


$app->register(new Silex\Provider\RememberMeServiceProvider());

$app->get('/login', function( Request $request) use ($app) {
    return $app->render('login.html', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
});

$app->get('/', function ()  use ($app) {
    $user = $app['user']->getUsername();
    return $app->render('home.twig', ['user'=>$user, 'page'=>'home']);
});

// build User controller
$admin = new UserController($app);
$app->mount('/users', $admin->build());

// build appointments controller
$admin = new AppointmentController($app);
$app->mount('/appointments', $admin->build());

return $app;
