<?php

namespace App\Form;

use App\Entity\Archmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ArchiveMailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sender', TextType::class, [
                'label' => 'Prénom et nom de l\'expéditeur ou personne morale',
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Entrez le nom de l\'expéditeur'
                ]
            ])
            ->add('object', TextType::class, [
                'label' => 'Objet du courrier',
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Entrez l\'objet du courrier'
                ]
            ])
            ->add('fileFile1', VichFileType::class, [
                'label' => 'Fichier du courrier',
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'download_label' => false,
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Choisissez un fichier'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-powerpoint',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/svg+xml',
                            'image/webp',
                            'image/tiff',
                            'image/bmp',
                            'image/vnd.microsoft.icon',
                            'image/vnd.wap.wbmp',
                            'image/x-xbitmap',
                            'image/x-jg',
                            'image/x-emf',
                            'image/x-wmf',
                            'image/x-xpixmap',
                            'image/x-xwindowdump',
                            'image/x-portable-pixmap',
                            'image/x-portable-greymap',
                            'image/x-portable-anymap',
                            'image/x-portable-bitmap',
                        ],
                        'mimeTypesMessage' => 'Veuillez choisir un fichier valide',
                    ])
                ]
            ])
            ->add('fileFile2', VichFileType::class, [
                'label' => 'Fichier du courrier',
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'download_label' => false,
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Choisissez un fichier'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-powerpoint',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/svg+xml',
                            'image/webp',
                            'image/tiff',
                            'image/bmp',
                            'image/vnd.microsoft.icon',
                            'image/vnd.wap.wbmp',
                            'image/x-xbitmap',
                            'image/x-jg',
                            'image/x-emf',
                            'image/x-wmf',
                            'image/x-xpixmap',
                            'image/x-xwindowdump',
                            'image/x-portable-pixmap',
                            'image/x-portable-greymap',
                            'image/x-portable-anymap',
                            'image/x-portable-bitmap',
                        ],
                        'mimeTypesMessage' => 'Veuillez choisir un fichier valide',
                    ])
                ]
            ])
            ->add('fileFile3', VichFileType::class, [
                'label' => 'Fichier du courrier',
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'download_label' => false,
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Choisissez un fichier'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-powerpoint',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/svg+xml',
                            'image/webp',
                            'image/tiff',
                            'image/bmp',
                            'image/vnd.microsoft.icon',
                            'image/vnd.wap.wbmp',
                            'image/x-xbitmap',
                            'image/x-jg',
                            'image/x-emf',
                            'image/x-wmf',
                            'image/x-xpixmap',
                            'image/x-xwindowdump',
                            'image/x-portable-pixmap',
                            'image/x-portable-greymap',
                            'image/x-portable-anymap',
                            'image/x-portable-bitmap',
                        ],
                        'mimeTypesMessage' => 'Veuillez choisir un fichier valide',
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary btn-block mt-4'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Archmail::class,
        ]);
    }
}
