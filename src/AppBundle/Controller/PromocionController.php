<?php

namespace AppBundle\Controller;

use AppBundle\Entity\FotosEstablecimiento;
use AppBundle\Form\FotoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Articulo;
use AppBundle\Form\ArticuloType;

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
        $idEstablecimiento = $request->get('idElemento');
        $establecimiento = $em->getRepository('AppBundle:Establecimiento')->find($idEstablecimiento);
        return $this->render('administrador/imagenes/principal.html.twig',array(
            'idEstablecimiento' => $idEstablecimiento,
            'establecimiento' => $establecimiento
        ));
    }

    /**
     * @Route("/registros", name="registrosPromociones")
     */
    public function registrosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $idEstablecimiento = $request->get('idElemento');
        $fotos = $em->getRepository('AppBundle:FotosEstablecimiento')->findBy(array('establecimiento' => $idEstablecimiento));
        return $this->render('administrador/imagenes/registros.html.twig',array(
            'fotos' => $fotos,
            'idEstablecimiento' => $idEstablecimiento
        ));
    } 

    /**
    *
    * @Route("/nuevo", name="nuevoPromocion")
    */
    public function nuevoaAction(Request $request){
    	$em = $this->getDoctrine()->getManager();
        $idEstablecimiento = $request->get('idElemento');

    	$entity = new FotosEstablecimiento();
        $form   = $this->formularioCrear($entity);
		return $this->render('administrador/imagenes/nuevo.html.twig',array(
			'form'=>$form->createView(),
			'entity'=>$entity,
			'idEstablecimiento'=>$idEstablecimiento,
			));

    }

    /**
    *
    * @Route("/guardar", name="guardarPromocion")
    */
    public function guardarAction(Request $request){
        $entity = new FotosEstablecimiento();
        $form   = $this->formularioCrear($entity);
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
            $establecimiento=$em->getRepository('AppBundle:Establecimiento')->find($request->get('establecimiento'));
            $entity->setEstablecimiento($establecimiento);
            $em->persist($entity);
            $em->flush();

            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=>true,
                'mensaje'=>'Foto creada satisfactoriamente'
            ));
        }
        return new JsonResponse(array(
            'valor'=>false,
            'mensaje'=>'La foto no se pudo crear.'
        ));

    }


    /**
     * @Route("/editar", name="editarPromocion")
     * @Method({"POST"})
     */
    public function editarAction(Request $peticion){
        $idElemento=$peticion->request->get('idElemento');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:FotosEstablecimiento')->find($idElemento);
        $form   = $this->formularioEditar($entity);
        $idEstablecimiento = $peticion->get('idEstablecimiento');
        
        return $this->render('administrador/imagenes/editar.html.twig', array(
            'idEstablecimiento'=>$idEstablecimiento,
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
        $entity = $em->getRepository('AppBundle:FotosEstablecimiento')->find($idEntidad);
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

            $establecimiento=$em->getRepository('AppBundle:Establecimiento')->find($peticion->get('establecimiento'));
            $entity->setEstablecimiento($establecimiento);
        
            $em->persist($entity);
            $em->flush();
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=>true,
                'mensaje'=>'Foto actualizada satisfactoriamente'
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
        $foto = $em->getRepository('AppBundle:FotosEstablecimiento')->find($idElmento);
        try{
            $em->remove($foto);
            $em->flush();
            return new JsonResponse(array(
                'valor'=>true,
                'mensaje'=>'Foto eliminada con exito'
            ));
        }catch (Exception $e){
            return new JsonResponse(array(
                'valor'=>false,
                'mensaje'=>'Fallo al eliminar la foto'
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