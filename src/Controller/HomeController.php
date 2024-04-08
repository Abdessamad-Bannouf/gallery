<?php

namespace App\Controller;

use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ImageRepository $imageRepository): Response
    {
        return $this->render('image/home/index.html.twig', [
            'images' => $imageRepository->findAll(),
        ]);
    }
}
