<?php

namespace App\Form;

use App\Entity\Newsletter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class NewsletterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 30
                    ]),
                    new NotBlank()
                ],
                'attr' => [
                    'placeholder' => 'nom'
                ]
            ])
            ->add('firstname', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 30
                    ]),
                    new NotBlank()
                ],
                'attr' => [
                    'placeholder' => 'prénom'
                ]
            ])
            ->add('mail', EmailType::class, [
                'label' => 'Votre email',
                'constraints' => [
                    new NotBlank()
                ],
                'attr' => [
                    'placeholder' => 'adresse email'
                ]
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Newsletter::class,
        ]);
    }
}
