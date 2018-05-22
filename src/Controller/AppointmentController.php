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
            $appointmentRepo = new AppointmentRepo($app['db']);
            $appointments = $appointmentRepo->getAll();
            return $app['twig']->render('appointments/home.twig', ['appointments'=>$appointments, 'page'=>'appointments']);
        });

        $appointment->get('/{appointmentUuid}', function($appointmentUuid) use($app){
            $appointmentRepo = new AppointmentRepo($app['db']);
            $clientRepo = new ClientRepo($app['db']);
            $productRepo = new ProductRepo($app['db']);
            $clients = $clientRepo->getAll();
            $products = $productRepo->getAll();
            $extendedAppointment = json_decode($appointmentRepo->getAppointmentWithClientInfo($appointmentUuid));
            if ($extendedAppointment->tutors) $extendedAppointment->tutors = implode(",",json_decode($extendedAppointment->tutors));
            return $app['twig']->render('appointments/manageAppointment.twig', ['appointment'=>$extendedAppointment, 'clients'=>$clients, 'products'=>$products, 'page'=>'appointments']);
        });

        $appointment->post('/appointment', function() use($app){
            $appRepo = new AppointmentRepo($app['db']);
            $clientRepo = new ClientRepo($app['db']);
            $productRepo = new ProductRepo($app['db']);
            if ($_POST['client']) {
              $client = $clientRepo->getByUuid($_POST['client']);
            }
            $product = $productRepo->getByUuid($_POST['product']);
            $allDay = "";
            $name = $_POST['client'] ? null : $_POST['name'];
            if(!empty($_POST['all_day']))$allDay = $_POST['all_day'];
            $newAppointment = [
                        'client_uuid'=>$_POST['client'] ? $client->uuid : null,
                        'client_name'=>$_POST['client'] ? $client->first_name.' '.$client->last_name :$name,
                        'product_uuid'=>$product->uuid,
                        'product_name'=>$product->name,
                        'product_duration'=>$product->duration,
                        'notes'=>$_POST['notes'],
                        'date'=>date('Y-m-d H:i:s', strtotime($_POST['date'].' '.$_POST['time'])),
                        'all_day'=>$allDay
                    ];
            $newAppointment = new Appointment($newAppointment);
            return $app->json($response = $appRepo->insert($newAppointment));
        });

        $appointment->post('/manage/{appointmentUuid}', function($appointmentUuid) use($app){
            $appRepo = new AppointmentRepo($app['db']);
            $clientRepo = new ClientRepo($app['db']);
            $productRepo = new ProductRepo($app['db']);
            if ($_POST['client_uuid']) {
              $client = $clientRepo->getByUuid($_POST['client_uuid']);
              $_POST['client_name'] = $client->first_name.' '.$client->last_name;
            }
            $product = $productRepo->getByUuid($_POST['product_uuid']);
            $_POST['uuid'] = $appointmentUuid;
            $_POST['product_name'] = $product->name;
            $_POST['product_duration'] = $product->duration;
            $_POST['date'] = date('Y-m-d H:i:s', strtotime($_POST['date'].' '.$_POST['time']));
            return $app->json($appRepo->update($_POST));
        });

        $appointment->post('/delete', function() use($app){
            $appRepo = new AppointmentRepo($app['db']);
            return $app->json($appRepo->delete($_POST['uuid']));
        });

        return $appointment;
    }
}
