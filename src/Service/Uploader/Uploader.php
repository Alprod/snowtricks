<?php


namespace App\Service\Uploader;


use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class Uploader
{
    private $targetDirectory;
    private SluggerInterface $slugger;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct($targetDirectory, SluggerInterface $slugger, UrlGeneratorInterface $urlGenerator)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param UploadedFile $file
     * @param string $render
     * @param array $params
     * @return string|RedirectResponse
     */
    public function upload(UploadedFile $file, string $render, array $params = [])
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            return new RedirectResponse($this->urlGenerator->generate($render, $params));
        }

        return $fileName;
    }

    /**
     * @return mixed
     */
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}