<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Usuario;
use AppBundle\Form\UsuarioType;

/**
* @Route("/administracion/usuarios")
*/
class UsuarioController extends Controller
{
    /**
     * @Route("/", name="usuarioPrincipal")
     */
    public function indexAction(Request $request)
    {
        
        return $this->render('administrador/usuario/principal.html.twig');
    }

    /**
     * @Route("/registros", name="registrosUsuarios")
     */
    public function registrosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $usuarios = $em->getRepository('AppBundle:Usuario')->findAll();
        return $this->render('administrador/usuario/registros.html.twig',array('usuarios'=>$usuarios));
    } 

    /**
    *
    * @Route("/nuevo", name="nuevoUsuario")
    */
    public function nuevoaAction(Request $request){
    	$em = $this->getDoctrine()->getManager();
    	$roles = $em->getRepository('AppBundle:Rol')->findAll();
    	$entity = new Usuario();
        $form   = $this->formularioCrear($entity);
		return $this->render('administrador/usuario/nuevo.html.twig',array(
			'form'=>$form->createView(),
			'entity'=>$entity,
			'roles'=>$roles
			));

    }

    /**
    *
    * @Route("/guardar", name="guardarUsuario")
    */
    public function guardarAction(Request $request){
        $entity = new Usuario();
        $form   = $this->formularioCrear($entity);
        $form->handleRequest($request);
        
        $errors = $this->get('validator')->validate($entity);
        if (count($errors) > 0){
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=> false,
                'mensaje'=> $errors[0]->getMessage()
            ));
        }
        if($entity->getPassword()==''){
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=> false,
                'mensaje'=> 'El campo contaseña no debe quedar en Blanco.'
            ));
        }
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setSalt(md5(time()));
            $encoder = $this->container->get('security.encoder_factory')->getEncoder($entity);
            $passwordCodificado = $encoder->encodePassword($entity->getPassword(),$entity->getSalt());
            $entity->setPassword($passwordCodificado);  
            $rol=$em->getRepository('AppBundle:Rol')->find($request->get('rol'));
            $entity->setRol($rol);
            $entity->setUsername($entity->getCorreo());  
            $em->persist($entity);
            $em->flush();

            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=>true,
                'mensaje'=>'Usuario creado satisfactoriamente'
            ));
        }
        return new JsonResponse(array(
            'valor'=>false,
            'mensaje'=>'El usuario no se pudo crear.'
        ));

    }


    /**
     * @Route("/editar", name="editarUsuario")
     * @Method({"POST"})
     */
    public function editarAction(Request $peticion){
        $idUsuario=$peticion->request->get('idElemento');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Usuario')->find($idUsuario);
        $form   = $this->formularioEditar($entity);
        $roles = $em->getRepository('AppBundle:Rol')->findAll();
        
        return $this->render('administrador/usuario/editar.html.twig', array(
            'roles' => $roles,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Route("/actualizar", name="actualizarUsuario")
     * @Method({"POST"})
     */
    public function actualizarAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $idEntidad=$peticion->request->get('id_entity');
        $entity = $em->getRepository('AppBundle:Usuario')->find($idEntidad);
        $passwordOriginal = $entity->getPassword();
        $form   = $this->formularioCrear($entity);
        $form->handleRequest($peticion);
        
        $errors = $this->get('validator')->validate($entity);
        if (count($errors) > 0){
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=> false,
                'mensaje'=> $errors[0]->getMessage()
            ));
        }

        if ($form->isValid()) {
            if($entity->getPassword()!=''){
                $entity->setSalt(md5(time()));
                $encoder = $this->container->get('security.encoder_factory')->getEncoder($entity);
                $passwordCodificado = $encoder->encodePassword($entity->getPassword(),$entity->getSalt());
                $entity->setPassword($passwordCodificado);    
            }else{
                $entity->setPassword($passwordOriginal);
            }
            $rol=$em->getRepository('AppBundle:Rol')->find($peticion->get('rol'));
            $entity->setRol($rol);
            $em->persist($entity);
            $em->flush();
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=>true,
                'mensaje'=>'Usuario actualizado satisfactoriamente'
            ));
        }
        return new JsonResponse(array(
            'valor'=>false,
            'mensaje'=>'El usuario no se pudo actualizar.'
        ));
    }


    /**
     * @Route("/eliminar", name="eliminarUsuario")
     * @Method({"POST"})
     */
    public function eliminarAction(Request $peticion){
        
        return new JsonResponse(array(
            'valor'=>true,
            'mensaje'=>'Usuario Eliminado con exito'
        ));
    }

    private function formularioCrear(Usuario $entity){
        $form = $this->createForm(new UsuarioType(), $entity, array(
            'action' => $this->generateUrl('registrosUsuarios'),
            'method' => 'POST',
        ));

        return $form;
    }

    private function formularioEditar(Usuario $entity){
        $form = $this->createForm(new UsuarioType(), $entity, array(
            'action' => $this->generateUrl('registrosUsuarios'),
            'method' => 'POST',
        ));

        return $form;
    }
}