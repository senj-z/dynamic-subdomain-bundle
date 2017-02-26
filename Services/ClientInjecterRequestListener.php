<?php

namespace Senj\DynamicSubdomainBundle\Services;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Doctrine\ORM\EntityManager;
use Senj\DynamicSubdomainBundle\Exception\DomainNotFoundException;

class ClientInjecterRequestListener
{
    private $entityManager;
    private $base_host;
    private $parameter_name;
    private $entity;
    private $method;
    private $property;

    public function __construct(EntityManager $entityManager, $base_host, $parameter_name, $entity, $method, $property)
    {
        $this->entityManager = $entityManager;
        $this->base_host = $base_host;
        $this->parameter_name = $parameter_name;
        $this->entity= $entity;
        $this->method = $method;
        $this->property = $property;
    }
    
    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $host = $request->getHost();
        $subdomain = substr($host, 0, ((strlen($this->base_host) + 1) * -1));

        if ('findOneBy' === $this->method) {
            $subdomainobject = $this->entityManager->getRepository($this->entity)->findOneBy([
                $this->property => $subdomain
            ]);
        } else {
            $subdomainobject = $this->entityManager->getRepository($this->entity)->{$this->method}($subdomain);
        }

        if(!$subdomainobject) {
            throw new DomainNotFoundException(sprintf(
                'No subdomain mapped for host "%s", subdomain "%s"',
                $host,
                $subdomain
            ));
        }

        $event->getRequest()->attributes->set($this->parameter_name, $subdomainobject);
    }
}
