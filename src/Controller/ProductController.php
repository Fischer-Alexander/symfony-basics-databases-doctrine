<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @param ManagerRegistry $doctrine
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/product/edit/{id}")
     */
    public function update(ManagerRegistry $doctrine, int $id)
    {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' .$id
            );
        }

        $product->setName('New product name!');
        $entityManager->flush();

        return $this->redirectToRoute('product_show', [
            'id' => $product->getId()
        ]);
    }

    /**
     * @param ManagerRegistry $doctrine
     * @param int $id
     * @return Response
     * @Route("/product/{id}", name="product_show")
     */
    public function show(ManagerRegistry $doctrine, int $id)
    {
        $product = $doctrine->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '. $id
            );
        }

        return new Response('Check out this great product: '.$product->getName());
    }

    /**
     * @param ManagerRegistry $doctrine
     * @return Response
     * @Route("/product", name="create_product")
     */
    public function createProduct(ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();

        $product = new Product();
        $product->setName('Keyboard');
        $product->setPrice(1999);
        $product->setDescription('Ergonomic and stylish!');

        $entityManager->persist($product);

        $entityManager->flush();

        return new Response('Saved new product with id ' .$product->getId());
    }

    /**
     * @Route("/product", name="app_product")
     */
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }
}
