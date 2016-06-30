<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Plan;
use AppBundle\Form\PlanType;

/**
* @Route("/administracion/planes")
*/
class PlanController extends Controller
{
    /**
     * @Route("/", name="planPrincipal")
     */
    public function indexAction(Request $request)
    {
        
        return $this->render('administrador/plan/principal.html.twig');
    }

    /**
     * @Route("/registros", name="registrosPlanes")
     */
    public function registrosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $planes = $em->getRepository('AppBundle:Plan')->findAll();
        return $this->render('administrador/plan/registros.html.twig',array('planes'=>$planes));
    } 

    /**
    *
    * @Route("/nuevo", name="nuevoPlan")
    */
    public function nuevoaAction(Request $request){
    	$em = $this->getDoctrine()->getManager();
    	$entity = new Plan();
        $form   = $this->formularioCrear($entity);
		return $this->render('administrador/plan/nuevo.html.twig',array(
			'form'=>$form->createView(),
			'entity'=>$entity
			));

    }

    /**
    *
    * @Route("/guardar", name="guardarPlan")
    */
    public function guardarAction(Request $request){
        $entity = new Plan();
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
                'mensaje'=>'Plan creado satisfactoriamente'
            ));
        }
        return new JsonResponse(array(
            'valor'=>false,
            'mensaje'=>'El plan no se pudo crear.'
        ));

    }


    /**
     * @Route("/editar", name="editarPlan")
     * @Method({"POST"})
     */
    public function editarAction(Request $peticion){
        $idPlan=$peticion->request->get('idElemento');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Plan')->find($idPlan);
        $form   = $this->formularioEditar($entity);
        
        return $this->render('administrador/plan/editar.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Route("/actualizar", name="actualizarPlan")
     * @Method({"POST"})
     */
    public function actualizarAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $idEntidad=$peticion->request->get('id_entity');
        $entity = $em->getRepository('AppBundle:Plan')->find($idEntidad);
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
                'mensaje'=>'Plan actualizado satisfactoriamente'
            ));
        }
        return new JsonResponse(array(
            'valor'=>false,
            'mensaje'=>'El plan no se pudo actualizar.'
        ));
    }


    /**
     * @Route("/eliminar", name="eliminarPlan")
     * @Method({"POST"})
     */
    public function eliminarAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $idEntidad=$peticion->request->get('idElemento');
        $entity = $em->getRepository('AppBundle:Plan')->find($idEntidad);

        if(count($entity->getEstablecimientos())>0){
            return new JsonResponse(array(
                'valor'=>false,
                'mensaje'=>'No se puede eliminar el plan, tiene establecimientos asociados'
            ));
        }
        $em->remove($entity);
        $em->flush();
        return new JsonResponse(array(
            'valor'=>true,
            'mensaje'=>'Plan Eliminado con exito'
        ));
    }

    private function formularioCrear(Plan $entity){
        $form = $this->createForm(new PlanType(), $entity, array(
            'action' => $this->generateUrl('registrosPlanes'),
            'method' => 'POST',
        ));

        return $form;
    }

    private function formularioEditar(Plan $entity){
        $form = $this->createForm(new PlanType(), $entity, array(
            'action' => $this->generateUrl('registrosPlanes'),
            'method' => 'POST',
        ));

        return $form;
    }
}