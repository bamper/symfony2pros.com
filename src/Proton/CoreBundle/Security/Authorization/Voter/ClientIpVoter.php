<?php

namespace Proton\CoreBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ClientIpVoter implements VoterInterface
{

    protected $container;
    protected $ipBlacklist;

    public function __construct(ContainerInterface $container, array $ipBlacklist = array())
    {
        $this->container = $container;
        $this->ipBlacklist = $ipBlacklist;
    }

    public function supportsAttribute($attribute)
    {
        return true;
    }

    public function supportsClass($class)
    {
        return true;
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        $request = $this->container->get('request');
        if ('/login' !== $request->getPathInfo() && in_array($request->getClientIp(), $this->ipBlacklist)) {
            return VoterInterface::ACCESS_DENIED;
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }

}
