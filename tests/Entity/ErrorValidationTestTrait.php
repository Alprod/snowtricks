<?php


namespace App\Tests\Entity;

use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait ErrorValidationTestTrait
{
    /**
     * @var object|ValidatorInterface|null
     */
    private ValidatorInterface $validator;

    /**
     * @param object $entity
     * @param int $numberError
     * @return ConstraintViolationList
     */
    private function getErrorValidation(object $entity, int $numberError) : ConstraintViolationList
    {
        $errors = $this->validator->validate($entity);
        $messages = [];
        foreach ($errors as $error) {
            $messages[] = 'Attention : '.ucfirst($error->getPropertyPath()).' => '. $error->getMessage()." | verifier vos constants \n";
        }
        self::assertCount($numberError, $errors, implode('', $messages));
        return $errors;
    }

}