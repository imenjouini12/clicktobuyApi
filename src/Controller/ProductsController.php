<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductsRepository;
use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\product\ProductServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductsController extends AbstractController
{

private $manager;
private $productrep;
private $product;
private $productService;



public function __construct(EntityManagerInterface $manager ,ProductsRepository $productrep,ProductServiceInterface $productService  )
{
$this->manager = $manager;
$this->productrep = $productrep;
$this->productService = $productService;

}
    //Liste des produits
     /**
     * @Route("/allproducts", name="all_products", methods={"GET"})
     */
    public function getAllproducts(): Response
    {
        $products=$this->productService->listAllProducts();
        return $this->json($products,200);

    }
        //Ajouter un produit 
     /**
     * @Route("api/addProduct", name="add_products", methods={"POST"})
     */
    public function addproducts(Request $request)
    {
        $data= json_decode($request->getContent(),true);
        $name= $data['name'];
        $description= $data['description'];
        $price= $data['price'];
        $stock= $data['stock'];

        if (!isset($data['name']) || !isset($data['description']) || !isset($data['price']) || !isset($data['stock'])) {
            return new JsonResponse(['error' => 'Données JSON invalides'], Response::HTTP_BAD_REQUEST);
        }else{

         $produit= new Products();
         $produit->setName($name);
         $produit->setDescription($description);
         $produit->setPrice($price);
         $produit->setStock($stock);

         $this->productService->addProduct($produit);
         return new JsonResponse(['message' => 'un article a été ajouter avec succès'], Response::HTTP_CREATED);

        }
  


    }


}
