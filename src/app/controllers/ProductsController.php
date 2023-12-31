<?php

use Phalcon\Mvc\Controller;
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Select;
use Phalcon\Http\Request;
use MongoDB\Client;
use MongoDB\Driver\ServerApi;


class ProductsController extends Controller
{
    public function indexAction()
    {   
        
    }

    public function addAction()
    {   

        if ($this->request->isPost()) {
            $apiVersion = new ServerApi(ServerApi::V1);
            $uri = 'mongodb+srv://sachinkumar:<@gtxrtx399#>@cluster0.ghmmlbu.mongodb.net/?retryWrites=true&w=majority';
            $mongo = new MongoDB\Client($uri, [], ['serverApi' => $apiVersion]);
            try{
            $collection = $mongo->selectDatabase('Cluster0')->command(['ping' => 1]);
                echo "Pinged your deployment. You successfully connected to MongoDB!\n";
            } catch (Exception $e) {
                printf($e->getMessage());
            }
            echo '<pre>';print_r($this->request->getPost());die(__METHOD__);
            $product->productname = $this->request->getPost("productname");
            $product->category = $this->request->getPost("category");
            $product->price = (float) $this->request->getPost("price");
            $product->stock = (int) $this->request->getPost("stock");

            $metaFields = [];
            $meta_label = $this->request->getPost("meta_label");
            $meta_value = $this->request->getPost("meta_value");

            $variations = [];
            $attributeNames = $this->request->getPost("attributename");
            $attributeValues = $this->request->getPost("attributevalue");
            $variationPrices = $this->request->getPost("variationprice");

            // Create variations array
            for ($i = 0; $i < count($attributeNames); $i++) {
                $variation = [
                    "attributename" => $attributeNames[$i],
                    "attributevalue" => $attributeValues[$i],
                    "variationprice" => (float) $variationPrices[$i]
                ];
                $variations[] = $variation;
            }

            $product->variations = $variations;

            if ($product->save()) {
                $this->flashSession->success("Product saved successfully!");
            } else {
                foreach ($product->getMessages() as $message) {
                    $this->flashSession->error($message);
                }
            }
        }

        $this->view->pick("products/add"); // Render the add view  
    }

}