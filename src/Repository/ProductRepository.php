<?php

namespace Babybubble\Repository;

use Babybubble\Model\UserModel as User;
use Babybubble\Model\ProductModel as Product;

class ProductRepository extends Repository{

    function __construct($db) {
        $this->db = $db;
    }

    function insert($product) {
        $now = new \DateTime();
        $data = [
            'uuid' => $this->generateUuid(),
            'name' => $product->name,
            'duration' => $product->duration,
            'description' => $product->description,
            'created' => $now->format('c'),
            'updated' => $now->format('c'),
            'active' => true
        ];

        try {$result = $this->db->insert('products', $data);} catch(\Exception $e) {
            // return $e->getMessage();
            return $e->getCode();
        }
        return ['uuid'=>$data['uuid']];
    }

    function getbyUuid($uuid) {
        $rows = $this->db->fetchAll(
            'SELECT * FROM products WHERE uuid = ?',
            [$uuid]
        );
        return new Product($rows[0]);
    }

    function getAll(){
         $rows = $this->db->fetchAll('SELECT * FROM products');
         $products = [];
         for($i = 0; $i < count($rows); $i++){
             $product = new Product($rows[$i]);
             array_push($products, $product);
         };
         return $products;
    }

    function delete($productUuid){
        try {$result = $this->db->executeQuery('DELETE FROM products WHERE uuid = ?', [$productUuid]);}catch(\Exception $e) {
            // return $e->getMessage();
            return $e->getCode();
        }
        return ['uuid'=>$productUuid];
    }

    function activate($post){
        $qb = $this->db->createQueryBuilder();
        $query = $qb->update('products')
                ->set('active', $post['state'])
                ->where('uuid = :uuid')
                ->setParameter('uuid', $post['uuid']);
        try {$result = $query->execute();}catch(\Exception $e) {
            return trigger_error($e->getMessage(), E_USER_ERROR);
        }
        return $post['uuid'];
    }
}
