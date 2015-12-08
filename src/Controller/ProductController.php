<?php

namespace Babybubble\Controller;

use Symfony\Component\Security\Core\User\User;

//repositories
use Babybubble\Repository\ProductRepository as ProductRepo;

//models
use Babybubble\Model\ProductModel as Product;


class ProductController {

    function __construct($app) {
        $this->app = $app;
    }

    function build() {
        $app = $this->app;

        $product = $app['controllers_factory'];

        $product->get('/', function() use($app){
            $productRepo = new ProductRepo($app['db']);
            $products = $productRepo->getAll();
            return $app->render('products/home.twig', ['products'=>$products, 'page'=>'products']);
        })->secure('ROLE_ADMIN');

        $product->post('/product', function() use($app){
            $productRepo = new ProductRepo($app['db']);
            $product = new Product($_POST);
            return $app->json($productRepo->insert($product));
        })->secure('ROLE_ADMIN');

        $product->post('/delete', function() use($app){
            $productRepo = new ProductRepo($app['db']);
            return $app->json($productRepo->delete($_POST['uuid']));
        })->secure('ROLE_ADMIN');

        return $product;
    }
}
