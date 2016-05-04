<?php
/*
 * This file is part of the CampaignChain package.
 *
 * (c) CampaignChain, Inc. <info@campaignchain.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CampaignChain\Hook\ImageBundle\Repository;

use CampaignChain\CoreBundle\Entity\Operation;
use CampaignChain\Hook\ImageBundle\Entity\Image;
use Doctrine\ORM\EntityRepository;

class ImageRepository extends EntityRepository
{
    /**
     * @param Operation $operation
     * @return Image[]
     */
    public function getImagesForOperation(Operation $operation)
    {
       return $this->createQueryBuilder('image')
           ->select('image')
           ->join('image.activity', 'activity')
           ->join('activity.operations', 'operation')
           ->where('operation.id = :operationId')
           ->setParameter('operationId', $operation)
           ->getQuery()
           ->getResult();
    }
}