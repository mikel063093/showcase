<?php

namespace AppBundle\Controller;

use AppBundle\Entity\FotosArticulo;
use AppBundle\Entity\FotosEstablecimiento;
use AppBundle\Form\FotoArticuloType;
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
* @Route("/administracion/fotosArticulo")
*/
class FotoArticuloController extends Controller
{
    /**
     * @Route("/", name="fotoArticuloPrincipal")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $idArticulo = $request->get('idElemento');

        $articulo = $em->getRepository('AppBundle:Articulo')->find($idArticulo);
        return $this->render('administrador/imagenesArticulo/principal.html.twig',array(
            'idArticulo' => $idArticulo,
            'articulo' => $articulo
        ));
    }

    /**
     * @Route("/registros", name="registrosFotosArticulo")
     */
    public function registrosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $idArticulo = $request->get('idElemento');
        $fotos = $em->getRepository('AppBundle:FotosArticulo')->findBy(array('articulo' => $idArticulo));
        return $this->render('administrador/imagenesArticulo/registros.html.twig',array(
            'fotos' => $fotos,
            'idArticulo' => $idArticulo
        ));
    } 

    /**
    *
    * @Route("/nuevo", name="nuevoFotoArticulo")
    */
    public function nuevoaAction(Request $request){
    	$em = $this->getDoctrine()->getManager();
        $idArticulo = $request->get('idElemento');

    	$entity = new FotosArticulo();
        $form   = $this->formularioCrear($entity);
		return $this->render('administrador/imagenesArticulo/nuevo.html.twig',array(
			'form'=>$form->createView(),
			'entity'=>$entity,
			'idArticulo'=>$idArticulo,
			));

    }

    /**
    *
    * @Route("/guardar", name="guardarFotoArticulo")
    */
    public function guardarAction(Request $request){
        $entity = new FotosArticulo();
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
            $articulo=$em->getRepository('AppBundle:Articulo')->find($request->get('articulo'));
            $entity->setArticulo($articulo);
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
     * @Route("/editar", name="editarFotoArticulo")
     * @Method({"POST"})
     */
    public function editarAction(Request $peticion){
        $idElemento=$peticion->request->get('idElemento');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:FotosArticulo')->find($idElemento);
        $form   = $this->formularioEditar($entity);
        $idArticulo = $peticion->get('idArticulo');
        
        return $this->render('administrador/imagenesArticulo/editar.html.twig', array(
            'idArticulo'=>$idArticulo,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Route("/actualizar", name="actualizarFotoArticulo")
     * @Method({"POST"})
     */
    public function actualizarAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $idEntidad=$peticion->request->get('id_entity');
        $entity = $em->getRepository('AppBundle:FotosArticulo')->find($idEntidad);
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

            $articulo=$em->getRepository('AppBundle:Articulo')->find($peticion->get('articulo'));
            $entity->setArticulo($articulo);
        
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
     * @Route("/eliminar", name="eliminarFotoArticulo")
     * @Method({"POST"})
     */
    public function eliminarAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $idElmento = $peticion->get('idElemento');
        $foto = $em->getRepository('AppBundle:FotosArticulo')->find($idElmento);
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

    private function formularioCrear(FotosArticulo $entity){
        $form = $this->createForm(new FotoArticuloType(), $entity, array(
            'action' => $this->generateUrl('registrosFotosArticulo'),
            'method' => 'POST',
        ));

        return $form;
    }

    private function formularioEditar(FotosArticulo $entity){
        $form = $this->createForm(new FotoArticuloType(), $entity, array(
            'action' => $this->generateUrl('registrosFotosArticulo'),
            'method' => 'POST',
        ));

        return $form;
    }
}