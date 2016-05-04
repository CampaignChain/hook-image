<?php

namespace CampaignChain\Hook\ImageBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('path', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'CampaignChain\Hook\ImageBundle\Entity\Image',
            'label' => false,
        ]);
    }


    public function getBlockPrefix()
    {
        return $this->getName();
    }

    public function getName()
    {
        return 'campaignchain_hook_image';
    }
}