<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\FigureRepository;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 *@Route("/video")
 */
class VideoController extends AbstractController
{
    /**
     * @Route("/", name="video")
     */
    public function show(VideoRepository $videoRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $data = $videoRepository->findBy([], ['id' => 'DESC']);
        $videos = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            8
        );
        return $this->render('video/showVideo.html.twig', [
            'videos' => $videos
        ]);
    }

    /**
     * @Route("/new", name="new_video", methods={"GET","POST"})
     * @noinspection PhpOptionalBeforeRequiredParametersInspection
     */
    public function formVideo(Video $video = null, Request $request, FigureRepository $figureRepository)
    {
        if(!$video){
            $video = new Video();
        }

        $formVideo = $this->createForm(VideoType::class, $video);
        $figureId = $figureRepository->find((int)$request->get('figure'));
        $formVideo->handleRequest($request);
        if($formVideo->isSubmitted() && $formVideo->isValid()) {
            $video->setFigure($figureId);

            $em = $this->getDoctrine()->getManager();
            $em->persist($video);
            $em->flush();

            return $this->redirectToRoute('detail_figure', [
                'id' => $video->getFigure()->getId()
            ]);
        }

        return $this->render('video/form/formVideo.html.twig', [
            'formVideo' => $formVideo->createView(),
            'videoMode' => $video->getId() !== null
        ]);
    }

    public function deletVideo(Request $request, Video $video)
    {
        $submitedToken = $request->request->get('token');
        if($this->isCsrfTokenValid('delete_video'.$video->getId(), $submitedToken)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($video);
            $em->flush();
        }

        return $this->redirectToRoute('detail_figure', [
            'id' => $video->getFigure()->getId()
        ]);
    }
}
