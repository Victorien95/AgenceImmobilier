<?php

namespace App\Controller;

use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository;

    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(PropertyRepository $propertyRepository, EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->repository = $propertyRepository;
    }

    /**
     * @Route("/biens", name="property.index")
     */
    public function index(PaginatorInterface $paginator, Request $request):Response
    {
        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);
        $properties = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
        12
        );
        return $this->render('property/index.html.twig', [
            'controller_name' => 'PropertyController',
            'current_menu' => 'properties',
            'properties' => $properties,
            'form' => $form->createView()
        ]);
    }

    /**
     * @return Response
     * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
     */
    public function show(string $slug, Property $property):Response
    {
        if ($property->getSlug() != $slug)
        {
            return $this->redirectToRoute('property.show',
                [
                   'id' => $property->getId(),
                   'slug' =>  $property->getSlug()
                ], 301);
        }

        return $this->render('property/show.html.twig',
            [
                'controller_name' => 'PropertyController',
                'current_menu' => 'properties',
                'property' => $property
            ]);
    }
}
