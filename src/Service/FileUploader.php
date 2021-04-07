<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class FileUploader
{
    private $targetDirectory;
    private $slugger;
    private $urlGenerator;

    public function __construct($targetDirectory, SluggerInterface $slugger, UrlGeneratorInterface $urlGenerator)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
        $this->urlGenerator = $urlGenerator;
    }

    public function upload(UploadedFile $file, string $render)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
               return new RedirectResponse($this->urlGenerator->generate($render));
        }

        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
