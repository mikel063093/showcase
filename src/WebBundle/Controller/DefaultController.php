<?php

namespace WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Usuario;
/**
* @Route("/web", name="homepage")
*/
class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        /*
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
        */
        /*
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
        */
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }
    
    /**
     * @Route("/registro", name="registro")
     */
    public function registroAction()
    {
        return $this->render('web/registro.html.twig');
    }

    /**
     * @Route("/registrar", name="registrar")
     * @Method({"POST"})
     */
    public function registrarAction(Request $peticion)
    {
        
        try{
            $factory = $this->get('security.encoder_factory');
            $user = new Usuario();
            $password=$peticion->get('pass');
            $encoder = $factory->getEncoder($user);
            $user->setSalt(md5(time()));
            $pass = $encoder->encodePassword($password, $user->getSalt());
            $user->setNombres($peticion->get('nombres'));
            $user->setApellidos($peticion->get('apellidos'));
            $user->setUsername($peticion->get('correo'));
            $user->setCorreo($peticion->get('correo'));
            $user->setPassword($pass);

            $em = $this->getDoctrine()->getEntityManager();
            $rol=$em->getRepository('AppBundle:Rol')->find(1);
            $user->setRol($rol);
            $em->persist($user);
            $em->flush();
            return new JsonResponse( array('valor' => true ));
        }catch(\Exception $e){
            return new JsonResponse( array('valor' => false ));
        }
    }
}
