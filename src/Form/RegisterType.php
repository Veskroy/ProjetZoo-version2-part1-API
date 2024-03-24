<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /*$builder
            ->add('email', EmailType::class)
            ->add('firstname', Tex)
            ->add('lastname')
            ->add('password')
        ;*/
        $builder
            ->add('firstName', null, ['label' => 'Prénom'])
            ->add('lastName', null, ['label' => 'Nom'])
            ->add('email', EmailType::class)
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Vous avez saisi deux mots de passe différents.',
                'required' => true,
                'first_options' => [
                    'attr' => ['placeholder' => 'Saisissez votre mot de passe'],
                ],
                'second_options' => [
                    'attr' => ['placeholder' => 'Confirmer votre mot de passe'],
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères.',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('termsAccepted', CheckboxType::class, [
                'label' => "J'accepte les conditions générales d'utilisation",
                'mapped' => false,
                'constraints' => new IsTrue(),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'data_class' => User::class,
        ]);
    }
}
