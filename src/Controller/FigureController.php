<?php

namespace App\Controller;

use App\Entity\Discussion;
use App\Entity\Figure;
use App\Form\DiscussionType;
use App\Form\FigureType;
use App\Repository\FigureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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
    public function show(FigureRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $data = $repo->findBy([], ['createdAt'=> 'DESC']);
        $figures = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            10
        );
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
            $dateParis = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
            if(!$figure->getId()) {
                $figure->setAuthor($this->getUser()->getfirstname())
                    ->setCreatedAt($dateParis);
            }
            if($figure->getId()) {
                $figure->setUpdatedAt($dateParis);
            }
            $manager->persist($figure);
            $manager->flush();

            if ($dateParis <= $figure->getUpdatedAt()) {
                $this->addFlash('update', 'Voilà c\'est fait !!! la figure est mise à jours' );
            }else {
                $this->addFlash('add', 'Félicitation vous venez d\'ajouter une vouvelle figure');
            }

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
     * @Route("/figure/{id}", name="detail_figure", methods={"GET","POST"})
     * @param Figure $figureId
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function detail(Figure $figureId, Request $request, EntityManagerInterface $manager): Response
    {
        $discussion = new Discussion();
        $formDiscussion = $this->createForm(DiscussionType::class, $discussion);
        $formDiscussion->handleRequest($request);

        if($formDiscussion->isSubmitted() && $formDiscussion->isValid()) {
            $discussion->setCreatedAt(new \DateTime('now'));
            $discussion->setAuthor($this->getUser()->getPseudo());
            $discussion->setFigure($figureId);

            $manager->persist($discussion);
            $manager->flush();

            return $this->redirectToRoute('detail_figure', ["id"=>$figureId->getId()]);

        }
        return $this->render('figure/detailFigure.html.twig', [
            'figuresId' => $figureId,
            'formDiscussion'=>$formDiscussion->createView()
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
