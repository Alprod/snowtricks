<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => false,
                'first_options' => [
                    'constraints' => [
                    	new Regex([
                    		'pattern' => "/^\S*(?=\S*[\W])(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/",
		                    'message' => 'Il vous faut au moins 1 chiffre, 1 majuscule, 1 minuscule et 1 caractère spécial'
                        ]),
                        new NotBlank([
                            'message' => 'Veuillez entrer un mot de passe',
                        ]),
                        new Length([
                            'min' => 8,
                            'minMessage' => 'Votre mot de passe doit être composer au minimum de {{ limit }} caractères',
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'New password',
                ],
                'second_options' => [ 'label' => 'Repeat Password'],
                'invalid_message' => 'Les champs du mot de passe doivent correspondre.',
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
