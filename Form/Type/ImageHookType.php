<?php
/*
 * Copyright 2016 CampaignChain, Inc. <info@campaignchain.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
    public function getBlockPrefix()
    {
        return 'campaignchain_hook_campaignchain_image';
    }

    public function getParent()
    {
        return CollectionType::class;
    }
}