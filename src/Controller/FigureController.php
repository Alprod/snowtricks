<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FigureController extends AbstractController
{
    /**
     *@Route("/figure", name="figure")
     */
    public function show(): Response
    {
        return $this->render('figure/showFigure.html.twig', [
            'titre' => 'Vue de tout nos tricks'
        ]);
    }
}
