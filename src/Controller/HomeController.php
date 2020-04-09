<?php

namespace App\Controller;

use App\Repository\PropertyRepository;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param PropertyRepository $repository
     */
    public function index(PropertyRepository $repository)
    {

        $properties = $repository->findLatest();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'properties' => $properties
        ]);
    }

}
