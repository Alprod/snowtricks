<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\FigureRepository;
use App\Repository\ImageRepository;
use App\Service\Uploader\ImageUploadFile;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/image")
 */
class ImageController extends AbstractController
{
    /**
     * @Route("/", name="image")
     * @param ImageRepository $imageRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function show(ImageRepository $imageRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $data = $imageRepository->findby([],['id' => 'DESC']);
        $images = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            8
        );


        return $this->render('image/showImage.html.twig', [
            'images' => $images
        ]);
    }

    /**
     * @Route("/new", name="new_image", methods={"GET","POST"})
     * @Route ("/{id<\d+>}/image_edit", name="image_edit", methods={"GET","POST"})
     * @param Image|null $image
     * @param Request $request
     * @param ImageUploadFile $imageUploadFile
     * @param FigureRepository $figureRepository
     * @return Response
     * @noinspection PhpOptionalBeforeRequiredParametersInspection
     */
    public function formImage(Image $image = null,Request $request, ImageUploadFile $imageUploadFile,FigureRepository $figureRepository): Response
    {
        if(!$image) {
            $image = new Image();
        }
        $formImage = $this->createForm(ImageType::class, $image);
        $figueId = $figureRepository->find((int)$request->get('figure'));
        $formImage->handleRequest($request);

        if ($formImage->isSubmitted() && $formImage->isValid()) {

            /** @var UploadedFile $linkFileImage */
            $linkFileImage = $formImage->get('link')->getData();

            if($linkFileImage) {
                $newFilenameImage = $imageUploadFile->upload($linkFileImage, "new_image");
                $image->setFigure($figueId);
                $image->setLink($newFilenameImage);

                $em = $this->getDoctrine()->getManager();
                $em->persist($image);
                $em->flush();
            }

            return $this->redirectToRoute('detail_figure', [
                'id' => $image->getFigure()->getId()
            ]);

        }

        return $this->render('image/newImage.html.twig', [
            'formImage' => $formImage->createView(),
            'images' => $image,
            'imageMode' => $image->getid() !== null,
            'error'=>null
        ]);
    }

    /**
     * @Route("/{id<\d+>}/delete_image", name="image_delete")
     * @ParamConverter ("id", class="App\Entity\Image", options={"id": "id"})
     * @param Request $request
     * @param Image $image
     * @return RedirectResponse
     */
    public function deleteImage(Request $request, Image $image): RedirectResponse
    {
        $submittedToken = $request->request->get('token');
        if ($this->isCsrfTokenValid('delete_image'.$image->getId(), $submittedToken)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();
        }

        return $this->redirectToRoute('detail_figure', ['id' => $image->getFigure()->getId()]);
    }
}
