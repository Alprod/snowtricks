<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Form\FigureType;
use App\Repository\FigureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FigureController extends AbstractController
{
    /**
     *@Route("/figure", name="figure", methods={"GET"})
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
     * @Route("figure/new", name="figure_create", methods={"GET","POST"})
     * @Route("figure/{id}/edit", name="figure_edit", methods={"GET","POST"})
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


    /**
     * @Route("/figure/{id}", name="detail_figure", methods={"GET"})
     * @param Figure $figureId
     * @return Response
     */
    public function detail(Figure $figureId): Response
    {
        if(!$figureId) {
             throw $this->createNotFoundException('Désolé mais la figure n\'éxiste plus');
        }
        return $this->render('figure/detailFigure.html.twig', [
            'figuresId' => $figureId
        ]);
    }



    /**
     *@Route("figure/{id<\d+>}/delete", name="figure_delete")
     *@ParamConverter("id", class="App\Entity\Figure", options={"id": "id"})
     */
    public function deleteFigure(Request $request, Figure $figure): Response
    {
        $submittedToken = $request->request->get('token');
        if($this->isCsrfTokenValid('delete_figure'.$figure->getId(), $submittedToken)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($figure);
            $em->flush();
        }

        return $this->redirectToRoute('figure');
    }
}
