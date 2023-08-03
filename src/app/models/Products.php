<?php

use Phalcon\Mvc\Model;

class Products extends Model
{
    public $productname;
    public $category;
    public $price;
    public $stock;
    public $metaFields;
    public $variations;

    // Other model functions, if any...
    public function getSource()
    {
        return 'products';
    }
}