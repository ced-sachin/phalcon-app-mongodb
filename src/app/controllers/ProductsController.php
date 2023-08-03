<?php

use Phalcon\Mvc\Controller;
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Select;
use Phalcon\Http\Request;
use MongoDB\Client;
use MongoDB\Driver\ServerApi;
use MongoDB\BSON\ObjectId;


class ProductsController extends Controller
{
    public function indexAction()
    {
        $apiVersion = new ServerApi(ServerApi::V1);
        $uri = 'mongodb+srv://sachinkumar:fNhxINmDPtbVQSHZ@cluster0.ghmmlbu.mongodb.net/?retryWrites=true&w=majority';
        $client = new MongoDB\Client($uri, [], ['serverApi' => $apiVersion]);

        // Select database and collection
        $databaseName = 'sachin';
        $collectionName = 'products';
        $collection = $client->$databaseName->$collectionName;

        // Query the collection and fetch all documents
        $products = $collection->find([]);

        $this->view->products = $products;
    }

    public function updateAction($id)
    {
        $apiVersion = new ServerApi(ServerApi::V1);
        $uri = 'mongodb+srv://sachinkumar:fNhxINmDPtbVQSHZ@cluster0.ghmmlbu.mongodb.net/?retryWrites=true&w=majority';
        $client = new MongoDB\Client($uri, [], ['serverApi' => $apiVersion]);

        // Select database and collection
        $databaseName = 'sachin';
        $collectionName = 'products';
        $collection = $client->$databaseName->$collectionName;

        // Convert the string $id to MongoDB ObjectId
        $objectId = new ObjectId($id);

        // Fetch the product by ID from the MongoDB collection
        $product = $collection->findOne(['_id' => $objectId]);

        if (!$product) {
            // Product not found, handle the error accordingly
            // For example, redirect to a 404 page or show an error message
            return;
        }

        // Check if the update form has been submitted
        if ($this->request->isPost()) {
            // Retrieve updated data from the form
            $updatedData = $this->request->getPost();

            // Update the product with the new data
            $result = $collection->updateOne(['_id' => $objectId], ['$set' => $updatedData]);

            if ($result->getModifiedCount() === 1) {
                // Product updated successfully, you can perform any additional actions or redirect
                // For example, redirect back to the product listing page
                $this->response->redirect('products/index');
            } else {
                // Update failed, handle the error accordingly
                // For example, show an error message to the user
            }
        }

        // Pass the product details to the view for editing
        $this->view->product = $product;
    }


    public function deleteAction($id)
    {
        $apiVersion = new ServerApi(ServerApi::V1);
        $uri = 'mongodb+srv://sachinkumar:fNhxINmDPtbVQSHZ@cluster0.ghmmlbu.mongodb.net/?retryWrites=true&w=majority';
        $client = new MongoDB\Client($uri, [], ['serverApi' => $apiVersion]);

        // Select database and collection
        $databaseName = 'sachin';
        $collectionName = 'products';
        $collection = $client->$databaseName->$collectionName;

        // Convert the string $id to MongoDB ObjectId
        $objectId = new ObjectId($id);

        // Delete the product by ID from the MongoDB collection
        $result = $collection->deleteOne(['_id' => $objectId]);

        if ($result->getDeletedCount() === 1) {
            // Product deleted successfully, you can perform any additional actions or redirect
            // For example, redirect back to the product listing page
            $this->response->redirect('products/index');
        } else {
            // Product not found or some other issue occurred during deletion
            // Handle the error accordingly
        }
    }

    public function searchAction()
    {
        $apiVersion = new ServerApi(ServerApi::V1);
        $uri = 'mongodb+srv://sachinkumar:fNhxINmDPtbVQSHZ@cluster0.ghmmlbu.mongodb.net/?retryWrites=true&w=majority';
        $client = new MongoDB\Client($uri, [], ['serverApi' => $apiVersion]);

        // Search products by product name using a MongoDB query
        $searchTerm = $this->request->get('search');
        $filter = ['productname' => ['$regex' => $searchTerm, '$options' => 'i']];
        $products = $client->selectCollection('sachin', 'products')->find($filter);

        $this->view->products = $products;
        $this->view->searchTerm = $searchTerm;

        // Render the index view to display search results
        $this->view->pick('products/index');
    }

    public function quickViewAction($id)
    {
        // Fetch the product by ID from the MongoDB collection
        $searchTerm = $this->request->get('search');
        $apiVersion = new ServerApi(ServerApi::V1);
        $uri = 'mongodb+srv://sachinkumar:fNhxINmDPtbVQSHZ@cluster0.ghmmlbu.mongodb.net/?retryWrites=true&w=majority';
        $client = new MongoDB\Client($uri, [], ['serverApi' => $apiVersion]);
        // Search products by product name using a MongoDB query
        $filter = ['productname' => ['$regex' => $searchTerm, '$options' => 'i']];
        $product = $client->selectCollection('sachin', 'products')->findOne(['_id' => new ObjectId($id)]);

        if (!$product) {
            // Handle not found error or redirect to a different page
            return;
        }

        // Pass the product details to the view for quick view
        $this->view->product = $product;
    }

    public function addAction()
    {   

        if ($this->request->isPost()) {
            $apiVersion = new ServerApi(ServerApi::V1);
            $uri = 'mongodb+srv://sachinkumar:fNhxINmDPtbVQSHZ@cluster0.ghmmlbu.mongodb.net/?retryWrites=true&w=majority';
            $client = new MongoDB\Client($uri, [], ['serverApi' => $apiVersion]);
            try {
                // Send a ping to confirm a successful connection
                $client->selectDatabase('Cluster0')->command(['ping' => 1]);
                echo "Pinged your deployment. You successfully connected to MongoDB!\n";
            } catch (Exception $e) {
                printf($e->getMessage());
            }
            // echo '<pre>';print_r($this->request->getPost());die(__METHOD__);
            $productname = $this->request->getPost('productname', 'string');
            $category = $this->request->getPost('category', 'string');
            $price = $this->request->getPost('price', 'double');

            // Additional Fields
            $meta_label = $this->request->getPost('meta_label', 'string');
            $meta_value = $this->request->getPost('meta_value', 'string');

            // Variations
            $variationname = $this->request->getPost('variationname', 'string');
            $variationvalue = $this->request->getPost('variationvalue', 'string');
            $variationprice = $this->request->getPost('variationprice', 'double');

            // Create MongoDB client

            // Select database and collection
            $databaseName = 'sachin';
            $collectionName = 'products';
            $collection = $client->$databaseName->$collectionName;

            // Prepare the document to be inserted
            $document = [
                'productname' => $productname,
                'category' => $category,
                'price' => $price,
                'additional_fields' => [],
                'variations' => []
            ];

            // Add additional fields to the document
            for ($i = 0; $i < count($meta_label); $i++) {
                $document['additional_fields'][] = [
                    'meta_label' => $meta_label[$i],
                    'meta_value' => $meta_value[$i]
                ];
            }

            // Add variations to the document
            for ($i = 0; $i < count($variationname); $i++) {
                $document['variations'][] = [
                    'variationname' => $variationname[$i],
                    'variationvalue' => $variationvalue[$i],
                    'variationprice' => $variationprice[$i]
                ];
            }

            // Insert the document into the collection
            $collection->insertOne($document);

            // Set success message and redirect to another page if needed
            $this->flashSession->success('Product added successfully!');
            $this->response->redirect('products/add'); // Render the add view  
        }

        // $this->view->pick("products/add"); // Render the add view  
    }

}