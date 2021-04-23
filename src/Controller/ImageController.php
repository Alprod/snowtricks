<?php

namespace App\Controller;

use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/image")
 */
class ImageController extends AbstractController
{
    /**
     * @Route("/", name="image")
     */
    public function show(ImageRepository $imageRepository): Response
    {
        $images = $imageRepository->findAll();

        return $this->render('image/showImage.html.twig', [
            'images' => $images,
        ]);
    }
}
