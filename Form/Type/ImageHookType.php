<?php
/*
 * This file is part of the CampaignChain package.
 *
 * (c) CampaignChain, Inc. <info@campaignchain.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CampaignChain\Hook\ImageBundle\Form\Type;

use CampaignChain\CoreBundle\Form\Type\HookType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageHookType extends HookType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'entry_type' => ImageType::class,
            'label' => false,
            'allow_add' => true,
            'allow_delete' => true,
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($this->hooksOptions && isset($this->hooksOptions['number_of_images'])) {
            $view->vars['number_of_images'] = $this->hooksOptions['number_of_images'];
        }
    }


    /**
     * @inheritdoc
     */
    public function getBlockPrefix()
    {
        return $this->getName();
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'campaignchain_hook_campaignchain_image';
    }

    public function getParent()
    {
        return CollectionType::class;
    }
}