<?php

namespace Senj\DynamicSubdomainBundle\Services;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

class SubdomainObjectGetter
{
    /**
     * SubdomainObjectGetter constructor.
     *
     * @param EntityManager $entityManager
     * @param Request       $request
     */
    public function __construct(EntityManager $entityManager, Request $request)
    {
        $this->entityManager = $entityManager;
        $this->request = $request;
    }
    
    /**
     * @return mixed
     */
    public function getSubdomainObject()
    {
        return $this->entityManager->getRepository($this->request->attributes->get('subdomainobject_class'))
            ->findOneById($this->request->attributes->get('subdomainobject_id'));
    }
}