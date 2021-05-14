<?php

namespace App\Tests\Entity;

use App\Entity\Figure;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FigureValidationEntityTest extends KernelTestCase
{
    use ErrorValidationTestTrait;

    private const NOT_BLANK_TITLE_MESSAGE = 'Veuillez saisir une valeur';
    private const TITLE_VALUE_VALIDE = 'Titre de la figure';
    private const LIMIT_DESCRIPTION_CONTENT = 50;
    private const VALIDE_LIMIT_CONTENT = 'Le contenu de la description de la figure est valider et tester';
    private const INVALIDE_LIMIT_CONTENT = 'Pas assez de contenu';
    private const INVALIDE_LIMIT_CONTENT_MESSAGE = 'Veuillez ecrire '.self::LIMIT_DESCRIPTION_CONTENT.' caractères minimum';

    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->validator = $kernel->getContainer()->get('validator');
    }

    public function getEntity(): Figure
    {
        return new Figure();
    }

    public function testFigureEntityIsValid()
    {
        $figure = $this->getEntity()
            ->setTitle(self::TITLE_VALUE_VALIDE)
            ->setDescription(self::VALIDE_LIMIT_CONTENT);

        $this->getErrorValidation($figure, 0);
    }

    public function testTitleFigureIsEmpty() : void
    {
        $titleFigure = $this->getEntity()
            ->setTitle('')
            ->setDescription(self::VALIDE_LIMIT_CONTENT)
        ;
        $error = $this->getErrorValidation($titleFigure, 1);
        self::assertEquals(self::NOT_BLANK_TITLE_MESSAGE, $error[0]->getMessage());
    }


    public function testNumberOfCharactersOfContentDescription()
    {
        $contentDescription = $this->getEntity()
            ->setTitle(self::TITLE_VALUE_VALIDE)
            ->setDescription(self::INVALIDE_LIMIT_CONTENT);
        $error = $this->getErrorValidation($contentDescription, 1);
        $count = strlen($contentDescription->getDescription());
        self::assertLessThan(self::LIMIT_DESCRIPTION_CONTENT,$count);
        self::assertEquals(self::INVALIDE_LIMIT_CONTENT_MESSAGE, $error[0]->getMessage());
    }

}
