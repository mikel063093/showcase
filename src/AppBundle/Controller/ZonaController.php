<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Zona;
use AppBundle\Form\ZonaType;

/**
* @Route("/administracion/zonas")
*/
class ZonaController extends Controller
{
    /**
     * @Route("/", name="zonaPrincipal")
     */
    public function indexAction(Request $request)
    {
        
        return $this->render('administrador/zona/principal.html.twig');
    }

    /**
     * @Route("/registros", name="registrosZonas")
     */
    public function registrosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $zonas = $em->getRepository('AppBundle:Zona')->findAll();
        return $this->render('administrador/zona/registros.html.twig',array('zonas'=>$zonas));
    } 

    /**
    *
    * @Route("/nuevo", name="nuevoZona")
    */
    public function nuevoaAction(Request $request){
    	$em = $this->getDoctrine()->getManager();
    	$entity = new Zona();
        $form   = $this->formularioCrear($entity);
		return $this->render('administrador/zona/nuevo.html.twig',array(
			'form'=>$form->createView(),
			'entity'=>$entity
			));

    }

    /**
    *
    * @Route("/guardar", name="guardarZona")
    */
    public function guardarAction(Request $request){
        $entity = new Zona();
        $form   = $this->formularioCrear($entity);
        $form->handleRequest($request);
        $centro = $request->get('coordenadas');
        $errors = $this->get('validator')->validate($entity);
        if (count($errors) > 0){
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=> false,
                'mensaje'=> $errors[0]->getMessage()
            ));
        }
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setCentro($centro);
            $entity->setZoom("14");
            $em->persist($entity);
            $em->flush();

            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=>true,
                'mensaje'=>'Zona creada satisfactoriamente'
            ));
        }
        return new JsonResponse(array(
            'valor'=>false,
            'mensaje'=>'La zona no se pudo crear.'
        ));

    }


    /**
     * @Route("/editar", name="editarZona")
     * @Method({"POST"})
     */
    public function editarAction(Request $peticion){
        $idUsuario=$peticion->request->get('idElemento');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Zona')->find($idUsuario);
        $form   = $this->formularioEditar($entity);
        
        return $this->render('administrador/zona/editar.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Route("/actualizar", name="actualizarZona")
     * @Method({"POST"})
     */
    public function actualizarAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $idEntidad=$peticion->request->get('id_entity');
        $entity = $em->getRepository('AppBundle:Zona')->find($idEntidad);
        $form   = $this->formularioCrear($entity);
        $form->handleRequest($peticion);
        $centro = $peticion->get('coordenadas');
        $errors = $this->get('validator')->validate($entity);
        if (count($errors) > 0){
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=> false,
                'mensaje'=> $errors[0]->getMessage()
            ));
        }

        if ($form->isValid()) {
            $em->persist($entity);
            $entity->setCentro($centro);
            $entity->setZoom("14");
            $em->flush();
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=>true,
                'mensaje'=>'Zona actualizada satisfactoriamente'
            ));
        }
        return new JsonResponse(array(
            'valor'=>false,
            'mensaje'=>'La zona no se pudo actualizar.'
        ));
    }


    /**
     * @Route("/eliminar", name="eliminarZona")
     * @Method({"POST"})
     */
    public function eliminarAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $idEntidad=$peticion->request->get('idElemento');
        $entity = $em->getRepository('AppBundle:Zona')->find($idEntidad);

        if(count($entity->getEstablecimientos())>0){
            return new JsonResponse(array(
                'valor'=>false,
                'mensaje'=>'No se puede eliminar la zona, tiene establecimientos asociados'
            ));
        }
        $em->remove($entity);
        $em->flush();
        return new JsonResponse(array(
            'valor'=>true,
            'mensaje'=>'Zona Eliminada con exito'
        ));
    }

    private function formularioCrear(Zona $entity){
        $form = $this->createForm(new ZonaType(), $entity, array(
            'action' => $this->generateUrl('registrosZonas'),
            'method' => 'POST',
        ));

        return $form;
    }

    private function formularioEditar(Zona $entity){
        $form = $this->createForm(new ZonaType(), $entity, array(
            'action' => $this->generateUrl('registrosZonas'),
            'method' => 'POST',
        ));

        return $form;
    }
}