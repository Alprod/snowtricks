<?php

namespace App\Controller;

use App\Entity\Discussion;
use App\Form\DiscussionType;
use App\Repository\DiscussionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/discussion")
 */
class DiscussionController extends AbstractController
{
    /**
     * @Route("/", name="discussion_index", methods={"GET"})
     */
    public function index(DiscussionRepository $discussionRepository): Response
    {
        return $this->render('discussion/showDiscussion.html.twig', [
            'discussions' => $discussionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="discussion_delete")
     */
    public function deleteDiscussion(Request $request, Discussion $discussion): RedirectResponse
    {
        $submittedToken = $request->request->get('token');
        if ($this->isCsrfTokenValid('delete_discussion'.$discussion->getId(), $submittedToken)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($discussion);
            $em->flush();
        }

        return $this->redirectToRoute('figure');
    }
}
