<?php

namespace Babybubble\Repository;

use Babybubble\Model\AppointmentModel as Appointment;

class AppointmentRepository extends Repository{

    function __construct($db) {
        $this->db = $db;
    }

    function insert($appointment) {
        $now = new \DateTime();
        $data = [
            'uuid' => $this->generateUuid(),
            'client_uuid' => $appointment->client_uuid,
            'client_name' => $appointment->client_name,
            'product_uuid' => $appointment->product_uuid,
            'product_name' => $appointment->product_name,
            'product_duration' => $appointment->product_duration,
            'date' => $appointment->date,
            'all_day' => $appointment->all_day,
            'created' => $now->format('c'),
            'updated' => $now->format('c'),
        ];

        try {$result = $this->db->insert('appointments', $data);} catch(\Exception $e) {
            // return $e->getMessage();
            return $e->getMessage();
        }
        return ['uuid'=>$data['uuid']];
    }

    function getbyUuid($uuid) {
        $rows = $this->db->fetchAll(
            'SELECT * FROM appointments WHERE uuid = ?',
            [$uuid]
        );
        return new Appointment($rows[0]);
    }

    function getAll(){
         $rows = $this->db->fetchAll('SELECT * FROM appointments');
         $appointments = [];
         for($i = 0; $i < count($rows); $i++){
             $appointment = new Appointment($rows[$i]);
             array_push($appointments, $appointment);
         };
         return $clients;
    }

    function getByClientUuid($clientUuid) {
        $rows = $this->db->fetchAll(
            'SELECT * FROM appointments WHERE client_uuid = ?',
            [$clientUuid]
        );
        return new Appointment($rows[0]);
    }

    function update($post){
        $qb = $this->db->createQueryBuilder();
        $query = $qb->update('clients')
                ->set('first_name', $qb->expr()->literal($post->first_name))
                ->set('last_name', $qb->expr()->literal($post->last_name))
                ->set('tutors', $qb->expr()->literal(json_encode($post->tutors)))
                ->set('address', $qb->expr()->literal($post->address))
                ->set('phone', $post->phone)
                ->set('email', $qb->expr()->literal($post->email))
                ->set('birthday', $qb->expr()->literal($post->birthday))
                ->set('medical_conditions', $qb->expr()->literal($post->medical_conditions))
                ->set('first_contact_info', $qb->expr()->literal($post->first_contact_info))
                ->where('uuid = :uuid')
                ->setParameter('uuid', $post->uuid);
        try {$result = $query->execute();}catch(\Exception $e) {
            return $e->getCode();
        }
        return ['uuid'=>$post->uuid];
    }
}
