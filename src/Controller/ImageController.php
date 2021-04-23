<?php

namespace App\Controller;

use App\Entity\Image;
use App\Repository\ImageRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function show(ImageRepository $imageRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $data = $imageRepository->findAll();
        $images = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            8
        );


        return $this->render('image/showImage.html.twig', [
            'images' => $images
        ]);
    }
}
