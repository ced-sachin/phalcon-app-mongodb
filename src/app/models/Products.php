<?php

use Phalcon\Mvc\Collection;

class Products extends Collection
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