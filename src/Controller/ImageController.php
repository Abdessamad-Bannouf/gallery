<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/image')]
class ImageController extends AbstractController
{
    #[Route('/new', name: 'app_image_new', methods: ['POST', 'GET'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $photo = new Image();
        $form = $this->createForm(ImageType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                    
            $image = new File($photo->getName());
                    
                    $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                    $image->move(
                        $this->getParameter('images_directory'),
                        $fichier);

            $date = new \DateTime();
            $photo->setDate($date);

            //$name = $form->get('name')->getData()->getClientOriginalName();
            $photo->setName($fichier);

            $entityManager->persist($photo);
            $entityManager->flush();

            return $this->redirectToRoute('app_single_image', ['page' => $photo->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('image/new.html.twig', [
            'image' => $photo,
            'form' => $form,
        ]);
    }

    #[ParamConverter('image', options: ['mapping' => ['page' => 'id']])]
    #[Route('/show/{page}', name: 'app_single_image', methods: ['POST','GET'])]
    public function showSingle($page, Image $image = null, Request $request, ImageRepository $imageRepository): Response
    {
        $count = $request->get('page');

        while($image === null) {

            $startTime = microtime(true);

            $image = $imageRepository->findOneBy(['id' => $count+1]);

            usleep(1000000);
            $executionTime = microtime(true) - $startTime;

            if ($executionTime > 1) {
                $image = $imageRepository->findOneBy(['id' => $count-1]);

                return $this->redirectToRoute('app_single_image', ['page' => $image->getId()], Response::HTTP_SEE_OTHER);
            } 
        }

        $session = $request->getSession();
        $session->set('page', $image->getId());
        $image = $imageRepository->findOneBy(['id' => $session->get('page')]);
        
        return $this->render('image/index.html.twig', [
            'image' => $image,
            'previous' => $image->getId()-1,
            'next' => $image->getId()+1
        ]);
    }

    #[Route('/home', name: 'app_image_index', methods: ['GET'])]
    public function showAll(Image $image = null, ImageRepository $imageRepository): Response
    {
        return $this->render('image/home/test.html.twig', [
            //'images' => $imageRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_image_edit', methods: ['POST'])]
    public function edit(Request $request, Image $image, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_image_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('image/edit.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_image_delete', methods: ['POST'])]
    public function delete(Request $request, Image $image, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->request->get('_token'))) {
            $entityManager->remove($image);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_image_index', [], Response::HTTP_SEE_OTHER);
    }
}
