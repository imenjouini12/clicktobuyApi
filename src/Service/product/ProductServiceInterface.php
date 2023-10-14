<?php


namespace App\Service\product;

use App\Entity\Products;

interface ProductServiceInterface
{
    public function addProduct(Products $product);
    public function updateProduct(Products $product);
    public function deleteProduct(Products $product);
    public function getProductByName(int $productId): ?Products;
    public function listAllProducts();
    // Ajoutez d'autres méthodes selon vos besoins
}





























?>