<?php

namespace Babybubble\Controller;

//repositories
use Babybubble\Repository\AppointmentRepository as AppointmentRepo;
use Babybubble\Repository\ProductRepository as ProductRepo;
use Babybubble\Repository\ClientRepository as ClientRepo;

//models
use Babybubble\Model\UserModel;
use Babybubble\Model\AppointmentModel as Appointment;


class AppointmentController {

    function __construct($app) {
        $this->app = $app;
    }

    function build() {
        $app = $this->app;

        $appointment = $app['controllers_factory'];

        $appointment->get('/', function() use($app){
            return $app['twig']->render('appointments/home.twig', ['page'=>'appointments']);
        });

        $appointment->post('/appointment', function() use($app){
            $appRepo = new AppointmentRepo($app['db']);
            $clientRepo = new ClientRepo($app['db']);
            $productRepo = new ProductRepo($app['db']);
            $client = $clientRepo->getByUuid($_POST['client']);
            $product = $productRepo->getByUuid($_POST['product']);
            $allDay = "";
            if(!empty($_POST['all_day']))$allDay = $_POST['all_day'];
            $newPost = ['client_uuid'=>$client->uuid,
                        'client_name'=>($client->first_name.' '.$client->last_name),
                        'product_uuid'=>$product->uuid,
                        'product_name'=>$product->name,
                        'product_duration'=>$product->duration,
                        'date'=>date('Y-m-d H:i:s', strtotime($_POST['date'].' '.$_POST['time'])),
                        'all_day'=>$allDay
                    ];
            $newPost = new Appointment($newPost);
            return $app->json($response = $appRepo->insert($newPost));
        });

        return $appointment;
    }
}
