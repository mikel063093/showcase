<?php

namespace SecureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
* @Route("/user")
*/
class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $user = $this->getUser();

        if($user->getRol()->getCodigo()=="ROLE_ADMIN"){
        	return new Response('Administrador');
        }else if($user->getRol()->getCodigo()=="ROLE_USER"){
        	return new Response('Usuario Comprador');
        }

        return new Response('no usuario');
    }
}
