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

namespace CampaignChain\Hook\ImageBundle\EntityService;

use CampaignChain\CoreBundle\Entity\Activity;
use CampaignChain\CoreBundle\EntityService\HookServiceDefaultInterface;
use CampaignChain\Hook\AssigneeBundle\Model\Assignee;
use CampaignChain\Hook\ImageBundle\Entity\Image;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Inflector\Inflector;

class ImageService extends HookServiceDefaultInterface
{
    protected $em;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->em = $managerRegistry->getManager();
    }

    public function getHook($entity){
        return $entity->getImages();
    }

    /**
     * @param $entity
     * @param Image[] $hook
     * @return mixed
     */
    public function processHook($entity, $hook){
        // TODO: https://github.com/CampaignChain/campaignchain-ce/issues/82
        if (!$entity instanceof Activity) {
            return $entity;
        }

        // Copy new or remaining images.
        $newHook = array();
        foreach ($hook as $image) {
            if (!$image->getPath()) {
                continue;
            }

            $newImage = new Image();
            $newImage->setPath($image->getPath());

            $newHook[] = $newImage;
        }

        // Clean up all existing images.
        $this->processOrphaneHook($entity);

        // Persist images
        if(count($newHook)) {
            foreach ($newHook as $image) {
                // TODO: https://github.com/CampaignChain/campaignchain-ce/issues/82
                $image->setActivity($entity);
                $entity->addImage($image);
                $this->em->persist($image);
            }
        }

        $this->setEntity($entity);

        return true;
    }

    public function arrayToObject($hookData){
        if(is_array($hookData) && count($hookData)){
            foreach($hookData as $imageData){
                $image = new Image();
                $image->setPath($imageData['path']);

                $hook[] = $image;
            }
        }

        return $hook;
    }

    public function processOrphaneHook($entity)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->delete('CampaignChain\Hook\ImageBundle\Entity\Image', 'i');
        // TODO: https://github.com/CampaignChain/campaignchain-ce/issues/82
        $qb->where('i.activity = :activity');
        $qb->orWhere('i.activity IS NULL');
        $qb->setParameter('activity', $entity);
        $qb->getQuery()->execute();
    }

    public function tplInline($entity){
    }
}