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
         $rows = $this->db->fetchAll('SELECT *, appointments.uuid AS uuid FROM appointments JOIN clients ON appointments.client_uuid = clients.uuid');
         $appointments = [];
         for($i = 0; $i < count($rows); $i++){
             $appointment = new Appointment($rows[$i]);
             $appointment->birth = $rows[$i]['birthday'];
             array_push($appointments, $appointment);
         };
         return $appointments;
    }

    function getAppointmentWithClientInfo($uuid){
        $rows = $this->db->fetchAll('SELECT *, clients.uuid as "clientUuid", appointments.uuid as "appointmentUuid" FROM appointments JOIN clients ON appointments.client_uuid=clients.uuid WHERE appointments.uuid = ?', [$uuid]);
        return json_encode($rows[0]);
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
        $query = $qb->update('appointments')
                ->set('client_uuid', $qb->expr()->literal($post['client_uuid']))
                ->set('product_uuid', $qb->expr()->literal($post['product_uuid']))
                ->set('client_name', $qb->expr()->literal($post['client_name']))
                ->set('product_name', $qb->expr()->literal($post['product_name']))
                ->set('product_duration', $post['product_duration'])
                ->set('date', $qb->expr()->literal($post['date']))
                ->where('uuid = :uuid')
                ->setParameter('uuid', $post['uuid']);
        try {$result = $query->execute();}catch(\Exception $e) {
            return $e->getMessage();
        }
        return ['uuid'=>$post['uuid']];
    }

    function delete($postUuid){
        try {
                $result = $this->db->executeQuery('DELETE FROM appointments
                    WHERE uuid = ?',
                    [$postUuid]
                );
        } catch(\Exception $e) {
            return trigger_error($e->getMessage(), E_USER_ERROR);
        }
        return ['uuid'=>$postUuid];
    }
}
