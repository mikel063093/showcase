<?php

namespace WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Usuario;

/**
* @Route("/")
*/
class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        

        //Codigo para crear el usuario administrador
        $factory = $this->get('security.encoder_factory');
        $user = new Usuario();
        $password='1234';
        $encoder = $factory->getEncoder($user);
        $user->setSalt(md5(time()));
        $pass = $encoder->encodePassword($password, $user->getSalt());
        $user->setNombres('admin');
        $user->setApellidos('admin');
        $user->setUsername('admin');
        $user->setCorreo('alejo491@gmail.com');
        $user->setPassword($pass);

        $em = $this->getDoctrine()->getEntityManager();
        $rol=$em->getRepository('AppBundle:Rol')->find(2);
        $user->setRol($rol);
        $em->persist($user);
        $em->flush();
        
        //Codigo para crear el usuario normal
        $factory = $this->get('security.encoder_factory');
        $user = new Usuario();
        $password='1234';
        $encoder = $factory->getEncoder($user);
        $user->setSalt(md5(time()));
        $pass = $encoder->encodePassword($password, $user->getSalt());
        $user->setNombres('Alejandro');
        $user->setApellidos('Giraldo');
        $user->setUsername('alejo491@hotmail.es');
        $user->setCorreo('alejo491@hotmail.es');
        $user->setPassword($pass);

        $em = $this->getDoctrine()->getEntityManager();
        $rol=$em->getRepository('AppBundle:Rol')->find(1);
        $user->setRol($rol);
        $em->persist($user);
        $em->flush();
        
        return new Response('Bienvenido a showcase');
    }
}
