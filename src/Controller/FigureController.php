<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Form\FigureType;
use App\Repository\FigureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        if(!$figures) {
            throw $this->createNotFoundException("Désolé mais cette figure n'éxiste pas");
        }
        return $this->render('figure/showFigure.html.twig', [
            'titre' => 'Vue de tout nos tricks',
            'figures' => $figures
        ]);
    }


    /**
     * @Route("/figure/{id}", name="detail_figure", requirements={"id"="\d+"})
     * @param Figure $figureId
     * @return Response
     */
    public function detail(Figure $figureId): Response
    {
        return $this->render('figure/detailFigure.html.twig', [
            'figuresId' => $figureId
        ]);
    }

    /**
     * @Route("figure/new", name="figure_create")
     * @Route("figure/{id}/edit", name="figure_edit")
     * @noinspection PhpOptionalBeforeRequiredParametersInspection
     */
    public function formFigure(Figure $figure = null ,Request $request,EntityManagerInterface $manager): Response
    {
        if(!$figure){
            $figure = new Figure();
        }
        $formFigure = $this->createForm(FigureType::class, $figure);
        $formFigure->handleRequest($request);

        if($formFigure->isSubmitted() && $formFigure->isValid()) {
            if(!$figure->getId()) {
                $figure->setAuthor($this->getUser()->getfirstname())
                    ->setCreatedAt(new \DateTime());
            }
            $manager->persist($figure);
            $manager->flush();
            return $this->redirectToRoute('detail_figure', [
                'id' => $figure->getId()
            ]);
        }

        return $this->render('figure/form/create.html.twig', [
            'formFigure' => $formFigure->createView(),
            'editMode' => $figure->getId() !== null
        ]);
    }
}
