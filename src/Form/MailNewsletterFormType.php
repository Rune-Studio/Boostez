<?php

namespace App\Form;

use App\Message\MailNewsletter;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\File;

class MailNewsletterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject', TextType::class)
            ->add('files', FileType::class, [
                'label' => 'Brochure (PDF file)',

                'required' => false,

                'multiple' => true,

                'constraints' => [
                    new Count(['max' => 7]),
                    new All([
                        new File([
                            'maxSize' => '5M',
                            'mimeTypes' => [
                                'application/pdf',
                                'application/x-pdf',
                                'image/jpeg',
                                'image/png'
                            ],
                        ])
                    ])
                ],
                'attr' => [
                    'accept' => '.jpg, .jpeg, .png, .pdf'
                ],

            ])
            ->add('content', CKEditorType::class, [
                'attr' => [
                    'class' => 'tinymce',
                    'data-theme' => 'bbcode' // Skip it if you want to use default theme
                ]
            ])
            ->add('envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MailNewsletter::class
        ]);
    }
}
