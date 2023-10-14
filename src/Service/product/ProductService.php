<?php

namespace App\Service\product ;

use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;


class ProductService implements ProductServiceInterface
{

    public function __construct(EntityManagerInterface $entityManager)
    {
    $this->entityManager = $entityManager;
    
    }

    public function addProduct(Products $product)
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();

    }
    public function updateProduct(Products $product)
    {
        $this->entityManager->flush();
        
    }
    public function deleteProduct(Products $product)
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();
    
        
    }
    public function getProductByName(int $productId): ?Products
    {
        return $this->entityManager->getRepository(Products::class)->findOneById($productId);
        
    }
    public function listAllProducts()
    {
        return $this->entityManager->getRepository(Products::class)->findAll();
        
    }









}









?>