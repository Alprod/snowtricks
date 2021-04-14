<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Repository\FigureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FigureController extends AbstractController
{
    /**
     *@Route("/figure", name="figure")
     */
    public function show(FigureRepository $repo): Response
    {
        $figures = $repo->findAll();
        return $this->render('figure/showFigure.html.twig', [
            'titre' => 'Vue de tout nos tricks',
            'figures' => $figures
        ]);
    }


    /**
     * @Route("/figure/{id}", name="detail_figure")
     * @param Figure $figureId
     * @return Response
     */
    public function detail(Figure $figureId): Response
    {
        return $this->render('figure/detailFigure.html.twig', [
            'figuresId' => $figureId
        ]);
    }
}
