<?php

namespace WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
* @Route("/seguridad")
*/
class SecureController extends Controller
{
     
    /**
     *  
     * La capa de seguridad interceptará esta solicitud
     * @Route("/inicioSesion", name="inicioWeb")
     */
    public function loginAction(Request $request){
        
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {

            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $request->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);

        }
        $em=$this->getDoctrine()->getManager();
        
        
        return $this->render('validacion/login.html.twig', 
        array(
            'ultimo_usuario' => $request->getSession()->get(SecurityContext::LAST_USERNAME),
            'error' => $error,
		));
    }
    
    /**
     *  
     * La capa de seguridad interceptará esta solicitud
     * @Route("/login_check_web", name="web_login_check_web")
     */
    public function securityCheckAction(Request $request){
         return new Response('hola');
    }

    /**
     * 
     * La capa de seguridad interceptará esta solicitud
     * @Route("/logout", name="web_logout_web")
     */
    public function webLogoutAction(){
        
    }
}