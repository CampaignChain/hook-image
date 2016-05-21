<?php
/*
 * This file is part of the CampaignChain package.
 *
 * (c) CampaignChain, Inc. <info@campaignchain.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CampaignChain\Hook\ImageBundle\EntityService;

use CampaignChain\CoreBundle\Entity\Activity;
use CampaignChain\CoreBundle\EntityService\HookServiceDefaultInterface;
use CampaignChain\Hook\AssigneeBundle\Model\Assignee;
use CampaignChain\Hook\ImageBundle\Entity\Image;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Inflector\Inflector;

class ImageService implements HookServiceDefaultInterface
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
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
        if (!$entity instanceof Activity) {
            return $entity;
        }

        foreach ($hook as $image) {
            if (!$image->getPath()) {
                continue;
            }

            $image->setActivity($entity);
            // TODO: https://github.com/CampaignChain/campaignchain-ce/issues/82
            $entity->addImage($image);
            $this->em->persist($image);
        }

        return $entity;
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

    public function tplInline($entity){
    }
}