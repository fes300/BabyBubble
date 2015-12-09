<?php

namespace Babybubble\Repository;

use Babybubble\Model\ClientModel as Client;

class ClientRepository extends Repository{

    function __construct($db) {
        $this->db = $db;
    }

    function insert($client) {
        $now = new \DateTime();
        $data = [
            'uuid' => $this->generateUuid(),
            'first_name' => $client->first_name,
            'last_name' => $client->last_name,
            'tutors' => json_encode($client->tutors),
            'address' => $client->address,
            'phone' => $client->phone,
            'email' => $client->email,
            'birthday' => $client->birthday,
            'medical_conditions' => $client->medical_conditions,
            'first_contact_info' => $client->first_contact_info,
            'created' => $now->format('c'),
            'updated' => $now->format('c'),
        ];

        try {$result = $this->db->insert('clients', $data);} catch(\Exception $e) {
            // return $e->getMessage();
            return $e->getCode();
        }
        return ['uuid'=>$data['uuid']];
    }

    function getbyUuid($uuid) {
        $rows = $this->db->fetchAll(
            'SELECT * FROM clients WHERE uuid = ?',
            [$uuid]
        );
        return new Client($rows[0]);
    }

    function getAll(){
         $rows = $this->db->fetchAll('SELECT * FROM clients');
         $clients = [];
         for($i = 0; $i < count($rows); $i++){
             $client = new Client($rows[$i]);
             array_push($clients, $client);
         };
         return $clients;
    }

    function getByLastName($lastName) {
        $rows = $this->db->fetchAll(
            'SELECT * FROM clients WHERE username = ?',
            [$lastName]
        );
        return new Client($rows[0]);
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
