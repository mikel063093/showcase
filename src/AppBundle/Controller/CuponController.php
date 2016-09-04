<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Cupon;
use AppBundle\Form\CuponType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
* @Route("/administracion/cupones")
*/
class CuponController extends Controller
{
    /**
     * @Route("/", name="cuponPrincipal")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        return $this->render('administrador/cupon/principal.html.twig');
    }

    /**
     * @Route("/registros", name="registrosCupones")
     */
    public function registrosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $cupones = $em->getRepository('AppBundle:Cupon')->findAll();
        return $this->render('administrador/cupon/registros.html.twig',array(
            'cupones' => $cupones
        ));
    } 

    /**
    *
    * @Route("/nuevo", name="nuevoCupon")
    */
    public function nuevoaAction(Request $request){
    	$em = $this->getDoctrine()->getManager();
    	$entity = new Cupon();
        $form   = $this->formularioCrear($entity);
		return $this->render('administrador/cupon/nuevo.html.twig',array(
			'form'=>$form->createView(),
			'entity'=>$entity,
			));

    }

    /**
    *
    * @Route("/guardar", name="guardarCupon")
    */
    public function guardarAction(Request $request){
        $entity = new Cupon();
        $form   = $this->formularioCrear($entity);
        $fechaLimite = $request->get('fechaLimite');
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);
        $errors = $this->get('validator')->validate($entity);
        if (count($errors) > 0){
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=> false,
                'mensaje'=> $errors[0]->getMessage()
            ));
        }
        $cuponVal = $em->getRepository('AppBundle:Cupon')->findOneBy(array('codigo'=>$entity->getCodigo()));
        if ($cuponVal){
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=> false,
                'mensaje'=> 'Ya existe un cupon con este codigo'
            ));
        }

        if ($form->isValid()) {


            $entity->setFechaLimite(new \DateTime($fechaLimite));
            $entity->setEstado('Activo');
            $em->persist($entity);
            $em->flush();

            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=>true,
                'mensaje'=>'Cupon creado satisfactoriamente'
            ));
        }
        return new JsonResponse(array(
            'valor'=>false,
            'mensaje'=>'El cupon no se pudo crear.'
        ));

    }


    /**
     * @Route("/editar", name="editarCupon")
     * @Method({"POST"})
     */
    public function editarAction(Request $peticion){
        $idElemento=$peticion->request->get('idElemento');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Cupon')->find($idElemento);
        $form   = $this->formularioEditar($entity);
        return $this->render('administrador/cupon/editar.html.twig', array(

            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Route("/actualizar", name="actualizarCupon")
     * @Method({"POST"})
     */
    public function actualizarAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $idEntidad=$peticion->request->get('id_entity');
        $entity = $em->getRepository('AppBundle:Cupon')->find($idEntidad);
        $fechaLimite = $peticion->get('fechaLimite');
        $estado = $peticion->get('estado');
        $form   = $this->formularioCrear($entity);
        $form->handleRequest($peticion);
        $errors = $this->get('validator')->validate($entity);
        if (count($errors) > 0) {
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor' => false,
                'mensaje' => $errors[0]->getMessage()
            ));
        }
        if ($form->isValid()) {
            $entity->setFechaLimite(new \DateTime($fechaLimite));
            $entity->setEstado($estado);
            $em->persist($entity);
            $em->flush();
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=>true,
                'mensaje'=>'Cupon actualizado satisfactoriamente'
            ));
        }
        return new JsonResponse(array(
            'valor'=>false,
            'mensaje'=>$form->createView()
        ));
    }


    /**
     * @Route("/eliminar", name="eliminarCupon")
     * @Method({"POST"})
     */
    public function eliminarAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $idElmento = $peticion->get('idElemento');
        $cupon = $em->getRepository('AppBundle:Cupon')->find($idElmento);
        try{

            $cupon->setEstado('Inactivo');
            $em->persist($cupon);
            $em->flush();
            return new JsonResponse(array(
                'valor'=>true,
                'mensaje'=>'Cupon eliminado con exito'
            ));
        }catch (Exception $e){
            return new JsonResponse(array(
                'valor'=>false,
                'mensaje'=>'Fallo al eliminar el cupon'
            ));
        }

    }

    private function formularioCrear(Cupon $entity){
        $form = $this->createForm(new CuponType(), $entity, array(
            'action' => $this->generateUrl('registrosCupones'),
            'method' => 'POST',
        ));

        return $form;
    }

    private function formularioEditar(Cupon $entity){
        $form = $this->createForm(new CuponType(), $entity, array(
            'action' => $this->generateUrl('registrosCupones'),
            'method' => 'POST',
        ));

        return $form;
    }
}