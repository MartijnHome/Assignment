<?php

namespace App\Form;

use App\Config\BlogStyle;
use App\Entity\Blog;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class BlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('subtitle')
            ->add('text')
            ->add('style', EnumType::class, [
                'class' => BlogStyle::class,
                'choice_label' => fn ($choice) => match ($choice) {
                    BlogStyle::Default => 'Default style',
                    BlogStyle::TitleInsideLead => 'Place title on top of lead image',
                    BlogStyle::LeadOnTop  => 'Place lead image on top of page',
                },
            ])
            ->add('main_image', FileType::class, [
                'label' => $options['main_image_label'],
                'mapped' => false,
                'required' => $options['main_image_required'],
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])
            ->add('additional_images', FileType::class, [
                'label' => $options['additional_images_label'],
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'constraints' => [
                    new All([
                        new File([
                            'maxSize' => '1024k',
                            'mimeTypes' => [
                                'image/*',
                            ],
                            'mimeTypesMessage' => 'Please upload a valid image',
                        ])
                    ])
                ],
            ])
            ->add('gallery', CheckboxType::class, [
                'label'    => 'Show additional images at the bottom as a image gallery',
                'required' => false,
            ])
            ->get('style')
                ->addModelTransformer(new CallbackTransformer(
                    function ($intAsEnum) {
                        return BlogStyle::cases()[$intAsEnum];
                    },
                    function ($enumAsInt) {
                        return $enumAsInt->value;
                    }
                ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
            'main_image_required' => true,
            'main_image_label' => "Main image (banner)",
            'additional_images_label' => "Optional - Additional images"
        ]);
    }
}
