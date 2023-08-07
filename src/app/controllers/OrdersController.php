<?php

use Phalcon\Mvc\Controller;
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Select;
use Phalcon\Http\Request;
use MongoDB\Client;
use MongoDB\Driver\ServerApi;
use MongoDB\BSON\ObjectId;


class OrdersController extends Controller
{
    private $collection;

    public function onConstruct()
    {
        $apiVersion = new ServerApi(ServerApi::V1);
        $uri = 'mongodb+srv://sachinkumar:fNhxINmDPtbVQSHZ@cluster0.ghmmlbu.mongodb.net/?retryWrites=true&w=majority';
        $client = new Client($uri, [], ['serverApi' => $apiVersion]);

        // Select database and collection
        $databaseName = 'sachin';
        $collectionName = 'products';
        $this->collection = $client->$databaseName->$collectionName;
    }

    public function addAction()
    {
        $products = $this->collection->find([]);
        $this->view->products = $products;
    }

    public function submitAction()
    {
        // Check if the request method is POST
        if ($this->request->isPost()) {
                // Get the form data
            $productData = [];
            $product = $this->request->getPost('product', 'string');
                // $variation = $this->request->getPost('variation', 'string'); // Optional, if applicable
            if (str_contains($product, '_')) {
                //VARIATION
                $product = explode('_', $product);
                $product = $filter = ['_id' => new MongoDB\BSON\ObjectID($product[0])];
                // Query the collection and find the product
                $product = $this->collection->findOne($filter);
                $product = (array) $product;
                $variation = $product['variation'][0]; 
                print_r($variation);die();
            
            }else{
                //SIMPLE
                $product = $filter = ['_id' => new MongoDB\BSON\ObjectID($product)];
                // Query the collection and find the product
                $product = $this->collection->findOne($filter);
            }
            $customerName = $this->request->getPost('customer_name', 'string');
            $quantity = $this->request->getPost('quantity', 'int');
            $status = $this->request->getPost('status', 'string');
            $orderDate = $this->request->getPost('order_date', 'string'); // Assuming it's a string (e.g., 'DD/MM/YYYY')
            
            // Redirect the user to a success page or any other desired action
            return $this->response->redirect('/orders/success');
        }

        // If the request is not POST, redirect the user to the form page or show an error message
        // return $this->response->redirect('/orders/form');
    }

}