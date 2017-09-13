<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\CmsPlugin\Form\Type\Translation;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Patryk Drapik <patryk.drapik@bitbag.pl>
 */
final class PageTranslationType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'bitbag.cms.name',
            ])
            ->add('slug', TextType::class, [
                'label' => 'bitbag.cms.slug',
            ])
            ->add('metaKeywords', TextareaType::class, [
                'label' => 'bitbag.cms.meta_keywords',
                'required' => false,
            ])
            ->add('metaDescription', TextareaType::class, [
                'label' => 'bitbag.cms.meta_description',
                'required' => false,
            ])
            ->add('content', TextareaType::class, [
                'label' => 'bitbag.cms.content',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'bitbag_cms_plugin_page_translation';
    }
}