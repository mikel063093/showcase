<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Categoria;
use AppBundle\Form\CategoriaType;

/**
* @Route("/administracion/categorias")
*/
class CategoriaController extends Controller
{
    /**
     * @Route("/", name="categoriaPrincipal")
     */
    public function indexAction(Request $request)
    {
        
        return $this->render('administrador/categoria/principal.html.twig');
    }

    /**
     * @Route("/registros", name="registrosCategorias")
     */
    public function registrosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categorias = $em->getRepository('AppBundle:Categoria')->findAll();
        return $this->render('administrador/categoria/registros.html.twig',array('categorias'=>$categorias));
    } 

    /**
    *
    * @Route("/nuevo", name="nuevoCategoria")
    */
    public function nuevoaAction(Request $request){
    	$em = $this->getDoctrine()->getManager();
    	$entity = new Categoria();
        $form   = $this->formularioCrear($entity);
		return $this->render('administrador/categoria/nuevo.html.twig',array(
			'form'=>$form->createView(),
			'entity'=>$entity
			));

    }

    /**
    *
    * @Route("/guardar", name="guardarCategoria")
    */
    public function guardarAction(Request $request){
        $entity = new Categoria();
        $form   = $this->formularioCrear($entity);
        $form->handleRequest($request);
        
        $errors = $this->get('validator')->validate($entity);
        if (count($errors) > 0){
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=> false,
                'mensaje'=> $errors[0]->getMessage()
            ));
        }
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=>true,
                'mensaje'=>'Categoria creada satisfactoriamente'
            ));
        }
        return new JsonResponse(array(
            'valor'=>false,
            'mensaje'=>'La categoria no se pudo crear.'
        ));

    }


    /**
     * @Route("/editar", name="editarCategoria")
     * @Method({"POST"})
     */
    public function editarAction(Request $peticion){
        $idCategoria=$peticion->request->get('idElemento');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Categoria')->find($idCategoria);
        $form   = $this->formularioEditar($entity);
        
        return $this->render('administrador/categoria/editar.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Route("/actualizar", name="actualizarCategoria")
     * @Method({"POST"})
     */
    public function actualizarAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $idEntidad=$peticion->request->get('id_entity');
        $entity = $em->getRepository('AppBundle:Categoria')->find($idEntidad);
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
            $em->persist($entity);
            $em->flush();
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=>true,
                'mensaje'=>'Categoria actualizada satisfactoriamente'
            ));
        }
        return new JsonResponse(array(
            'valor'=>false,
            'mensaje'=>'La categoria no se pudo actualizar.'
        ));
    }


    /**
     * @Route("/eliminar", name="eliminarCategoria")
     * @Method({"POST"})
     */
    public function eliminarAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $idEntidad=$peticion->request->get('idElemento');
        $entity = $em->getRepository('AppBundle:Categoria')->find($idEntidad);

        if(count($entity->getEstablecimientos())>0){
            return new JsonResponse(array(
                'valor'=>false,
                'mensaje'=>'No se puede eliminar la categoria, tiene establecimientos asociados'
            ));
        }
        $em->remove($entity);
        $em->flush();
        return new JsonResponse(array(
            'valor'=>true,
            'mensaje'=>'Categoria Eliminada con exito'
        ));
    }

    private function formularioCrear(Categoria $entity){
        $form = $this->createForm(new CategoriaType(), $entity, array(
            'action' => $this->generateUrl('registrosCategorias'),
            'method' => 'POST',
        ));

        return $form;
    }

    private function formularioEditar(Categoria $entity){
        $form = $this->createForm(new CategoriaType(), $entity, array(
            'action' => $this->generateUrl('registrosCategorias'),
            'method' => 'POST',
        ));

        return $form;
    }
}