<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Usuario;


/**
* @Route("/administracion")
*/
class DefaultController extends Controller
{
    /**
     * @Route("/", name="principal")
     */
    public function indexAction(Request $request)
    {
        $securityContext = $this->container->get('security.context');
        if($securityContext->isGranted('ROLE_SUPER')){
            return $this->render('administrador/principal.html.twig');
        }elseif ($securityContext->isGranted('ROLE_ADMIN')){
            return $this->render('administrador/reserva/principal.html.twig');
        }

    }
}
