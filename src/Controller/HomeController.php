<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Images;
use App\Form\UploadType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class HomeController extends AbstractController
{
    private $entityManager;

    // fait le lien avec la doctrine 
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request): Response
    {
        $image= new Images();
        $form= $this->createForm(UploadType::class,$image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file=$image->getName();
            $imags = $form->get('name')->getData();

            // On génère un nouveau nom de fichier
            $fileName=md5(uniqid()).'.'.$imags->guessExtension();
         
            // On copie le fichier dans le dossier uploads
            $imags->move($this->getParameter('path_image'),$fileName);

            $image->setName($fileName);
        
            // On sauvegarde dans de la base
            $this->entityManager->persist($image);
            $this->entityManager->flush();

            return $this->redirectToRoute('home');
        }
        
        $images= $this->entityManager->getRepository(Images::class)->findAll();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'form'=>$form->createView(),
            'images'=>$images
            
        ]);
    }

     /**
     * @Route("/show/{id}", name="show_image")
     */
    public function show_image(Images $image,Request $request )
    {

            // On récupère le nom de l'image
            $nom = $image->getName();
            
            //On récupère l'id
            $id = $image->getId();
            

         $images= $this->entityManager->getRepository(Images::class)->find($id);
            
           // return $this->redirectToRoute('home');
       

    }

      /**
     * @Route("/delete/{id}", name="delete_image")
     */
    public function delete(Images $image,Request $request )
    {

            // On récupère le nom de l'image
            $nom = $image->getName();
            
            // On supprime le fichier
            unlink($this->getParameter('path_image').'/'.$nom);

            // On supprime l'entrée de la base
            $this->entityManager->remove($image);
            $this->entityManager->flush();

            
            return $this->redirectToRoute('home');
       

    }
}
