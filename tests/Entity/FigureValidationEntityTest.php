<?php

namespace App\Tests\Entity;

use App\Entity\Figure;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FigureValidationEntityTest extends KernelTestCase
{
    use ErrorValidationTestTrait;

    private const NOT_BLANK_TITLE_MESSAGE = 'Veuillez saisir une valeur';
    private const NOT_BLANK_DESCRIPTION_MESSAGE = 'Indiquez une déscription de la figure';
    private const TITLE_VALUE_VALIDE = 'Titre de la figure';

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
        $this->getErrorValidation($this->getEntity(), 0);
    }

    public function testTitleFigureIsEmpty() : void
    {
        $titleFigure = $this->getEntity()->setTitle('');
        $error = $this->getErrorValidation($titleFigure, 1);
        self::assertEquals(self::NOT_BLANK_TITLE_MESSAGE, $error[0]->getMessage());
    }

    public function testDescriptionFigureIsEmpty()
    {
        $descriptionFigure = $this->getEntity()
            ->setTitle(self::TITLE_VALUE_VALIDE)
            ->setDescription('');
        $error = $this->getErrorValidation($descriptionFigure, 1);
        self::assertEquals(self::NOT_BLANK_DESCRIPTION_MESSAGE, $error[0]->getMessage());
    }
}
