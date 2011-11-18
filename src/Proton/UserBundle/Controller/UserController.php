<?php

namespace Proton\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Proton\UserBundle\Entity\User;

class UserController extends Controller
{

    public function placardAction(User $user)
    {
        $redis = $this->container->get('snc_redis.default_client');

        $userMetadata = $redis->hgetall(sprintf('user:%d', $user->getId()));

        return $this->render('ProtonUserBundle:User:placard.html.twig', array(
            'user' => $user,
            'userMetadata' => $userMetadata,
        ));
    }

}
