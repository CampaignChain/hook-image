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

use CampaignChain\CoreBundle\Entity\AssignableInterface;
use CampaignChain\CoreBundle\EntityService\HookServiceDefaultInterface;
use CampaignChain\Hook\AssigneeBundle\Model\Assignee;
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
        $hook = new Assignee();
        if($entity && $entity->getId() && $entity instanceof AssignableInterface){
            $hook->setUser($entity->getAssignee());
        }
        return $hook;
    }

    public function processHook($entity, $hook){
        if (!$entity instanceof AssignableInterface) {
            return $entity;
        }

        $entity->setAssignee($hook->getUser());

        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    public function arrayToObject($hookData){
        if(is_array($hookData) && count($hookData)){
            $hook = new Assignee();
            foreach($hookData as $property => $value){
                // TODO: Research whether this is a security risk, e.g. if the property name has been injected via a REST post.
                $method = (string) 'set'.Inflector::classify($property);
                if($method == 'setUser' && !is_object($value)){
                    $value = $this->em->getRepository('CampaignChainCoreBundle:User')->find($value);
                }
                $hook->$method($value);
            }
        }

        return $hook;
    }

    public function tplInline($entity){
        $hook = $this->getHook($entity);
        return $this->template->render(
            'CampaignChainHookAssigneeBundle::inline.html.twig',
            array('hook' => $hook)
        );
    }
}