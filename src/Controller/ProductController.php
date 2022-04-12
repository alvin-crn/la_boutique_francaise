<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Product;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/nos-produits', name: 'products')]
    public function index(Request $request): Response
    {
        $search = New Search;
        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($search);
            $products = $this->em->getRepository(Product::class)->findWithSearch($search);
        } else {
            $products = $this->em->getRepository(Product::class)->findAll();
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/produit/{slug}', name: 'product')]
    public function show($slug): Response
    {
        $product = $this->em->getRepository(Product::class)->findOneBySlug($slug);

        if(!$product) {
            return $this->redirectToRoute('products');
        }

        $sizes = $product->getSize()->getValues();

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'sizes' => $sizes,
        ]);
    }
}
