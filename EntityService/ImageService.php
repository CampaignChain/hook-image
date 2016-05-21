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