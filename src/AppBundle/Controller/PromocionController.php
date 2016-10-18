<?php

namespace AppBundle\Controller;

use AppBundle\Entity\FotosEstablecimiento;
use AppBundle\Entity\Promocion;
use AppBundle\Form\PromocionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
* @Route("/administracion/promociones")
*/
class PromocionController extends Controller
{
    /**
     * @Route("/", name="promocionPrincipal")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        return $this->render('administrador/promocion/principal.html.twig');
    }

    /**
     * @Route("/registros", name="registrosPromociones")
     */
    public function registrosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $promociones = $em->getRepository('AppBundle:Promocion')->findAll();
        return $this->render('administrador/promocion/registros.html.twig',array(
            'promociones' => $promociones
        ));
    } 

    /**
    *
    * @Route("/nuevo", name="nuevoPromocion")
    */
    public function nuevoaAction(Request $request){
    	$em = $this->getDoctrine()->getManager();
    	$entity = new Promocion();
        $form   = $this->formularioCrear($entity);
		return $this->render('administrador/promocion/nuevo.html.twig',array(
			'form'=>$form->createView(),
			'entity'=>$entity,
			));

    }

    /**
    *
    * @Route("/guardar", name="guardarPromocion")
    */
    public function guardarAction(Request $request){
        $entity = new Promocion();
        $form   = $this->formularioCrear($entity);
        $fechaInicio = $request->get('fechaInicio');
        $fechaFin = $request->get('fechaFin');
        $form->handleRequest($request);
        $errors = $this->get('validator')->validate($entity);
        if (count($errors) > 0){
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=> false,
                'mensaje'=> $errors[0]->getMessage()
            ));
        }
        $entity->upload();
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $entity->setFechaInicio(new \DateTime($fechaInicio));
            $entity->setFechaFin(new \DateTime($fechaFin));
            $em->persist($entity);
            $em->flush();
            $client = $this->get('notificaciones');
            $resultado = $client->enviarNotificacion('Nueva Promoción','Showcase cuenta con una nuevoa promoción');
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=>true,
                'mensaje'=>'Promoción creada satisfactoriamente'
            ));
        }
        return new JsonResponse(array(
            'valor'=>false,
            'mensaje'=>'La promoción no se pudo crear.'
        ));

    }


    /**
     * @Route("/editar", name="editarPromocion")
     * @Method({"POST"})
     */
    public function editarAction(Request $peticion){
        $idElemento=$peticion->request->get('idElemento');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Promocion')->find($idElemento);
        $form   = $this->formularioEditar($entity);

        
        return $this->render('administrador/promocion/editar.html.twig', array(

            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Route("/actualizar", name="actualizarPromocion")
     * @Method({"POST"})
     */
    public function actualizarAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $idEntidad=$peticion->request->get('id_entity');
        $entity = $em->getRepository('AppBundle:Promocion')->find($idEntidad);
        $fechaInicio = $peticion->get('fechaInicio');
        $fechaFin = $peticion->get('fechaFin');
        $form   = $this->formularioCrear($entity);
        $form->handleRequest($peticion);
        $errors = $this->get('validator')->validate($entity);
        if (count($errors) > 0){
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=> false,
                'mensaje'=> $errors[0]->getMessage()
            ));
        }
        $entity->upload();
        if ($form->isValid()) {

            $entity->setFechaInicio(new \DateTime($fechaInicio));
            $entity->setFechaFin(new \DateTime($fechaFin));
        
            $em->persist($entity);
            $em->flush();
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=>true,
                'mensaje'=>'Promoción actualizada satisfactoriamente'
            ));
        }
        return new JsonResponse(array(
            'valor'=>false,
            'mensaje'=>$form->createView()
        ));
    }


    /**
     * @Route("/eliminar", name="eliminarPromocion")
     * @Method({"POST"})
     */
    public function eliminarAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $idElmento = $peticion->get('idElemento');
        $promocion = $em->getRepository('AppBundle:Promocion')->find($idElmento);
        try{
            $em->remove($promocion);
            $em->flush();
            return new JsonResponse(array(
                'valor'=>true,
                'mensaje'=>'Promoción eliminada con exito'
            ));
        }catch (Exception $e){
            return new JsonResponse(array(
                'valor'=>false,
                'mensaje'=>'Fallo al eliminar la promoción'
            ));
        }

    }

    private function formularioCrear(Promocion $entity){
        $form = $this->createForm(new PromocionType(), $entity, array(
            'action' => $this->generateUrl('registrosPromociones'),
            'method' => 'POST',
        ));

        return $form;
    }

    private function formularioEditar(Promocion $entity){
        $form = $this->createForm(new PromocionType(), $entity, array(
            'action' => $this->generateUrl('registrosPromociones'),
            'method' => 'POST',
        ));

        return $form;
    }
}