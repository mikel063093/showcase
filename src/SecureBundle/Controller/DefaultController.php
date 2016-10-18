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
     * @Route("/", name="user")
     */
    public function indexAction()
    {
        $user = $this->getUser();

        if($user->getRol()->getCodigo()=="ROLE_ADMIN"){

        	return $this->redirectToRoute('principal');
        }

        return new Response('Acceso Denegado');
    }
}
