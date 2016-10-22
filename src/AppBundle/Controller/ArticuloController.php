<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Establecimiento;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Articulo;
use AppBundle\Form\ArticuloType;

/**
* @Route("/administracion/articulos")
*/
class ArticuloController extends Controller
{
    /**
     * @Route("/", name="articuloPrincipal")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $idEstablecimiento = $request->get('idElemento');
        $establecimiento = $em->getRepository('AppBundle:Establecimiento')->find($idEstablecimiento);
        return $this->render('administrador/articulo/principal.html.twig',array(
            'idEstablecimiento' => $idEstablecimiento,
            'establecimiento' => $establecimiento
        ));
    }

    /**
     * @Route("/registros", name="registrosArticulos")
     */
    public function registrosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $idEstablecimiento = $request->get('idElemento');
        $articulos = $em->getRepository('AppBundle:Articulo')->findBy(array('establecimiento' => $idEstablecimiento));
        return $this->render('administrador/articulo/registros.html.twig',array(
            'articulos' => $articulos,
            'idEstablecimiento' => $idEstablecimiento
        ));
    } 

    /**
    *
    * @Route("/nuevo", name="nuevoArticulo")
    */
    public function nuevoaAction(Request $request){
    	$em = $this->getDoctrine()->getManager();
        $idEstablecimiento = $request->get('idElemento');

    	$entity = new Articulo();
        $form   = $this->formularioCrear($entity);
		return $this->render('administrador/articulo/nuevo.html.twig',array(
			'form'=>$form->createView(),
			'entity'=>$entity,
			'idEstablecimiento'=>$idEstablecimiento,
			));

    }

    /**
    *
    * @Route("/guardar", name="guardarArticulo")
    */
    public function guardarAction(Request $request){
        $entity = new Articulo();
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
            $establecimiento=$em->getRepository('AppBundle:Establecimiento')->find($request->get('establecimiento'));
            $entity->setEstablecimiento($establecimiento);
            $em->persist($entity);
            $em->flush();

            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=>true,
                'mensaje'=>'Articulo creado satisfactoriamente'
            ));
        }
        var_dump($form->getErrors());
        return new JsonResponse(array(
            'valor'=>false,
            'mensaje'=>'El articulo no se pudo crear.'
        ));

    }


    /**
     * @Route("/editar", name="editarArticulo")
     * @Method({"POST"})
     */
    public function editarAction(Request $peticion){
        $idUsuario=$peticion->request->get('idElemento');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Articulo')->find($idUsuario);
        $form   = $this->formularioEditar($entity);
        $idEstablecimiento = $peticion->get('idEstablecimiento');
        
        return $this->render('administrador/articulo/editar.html.twig', array(
            'idEstablecimiento'=>$idEstablecimiento,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Route("/actualizar", name="actualizarArticulo")
     * @Method({"POST"})
     */
    public function actualizarAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $idEntidad=$peticion->request->get('id_entity');
        $entity = $em->getRepository('AppBundle:Articulo')->find($idEntidad);
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

            $establecimiento=$em->getRepository('AppBundle:Establecimiento')->find($peticion->get('establecimiento'));
            $entity->setEstablecimiento($establecimiento);
        
            $em->persist($entity);
            $em->flush();
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=>true,
                'mensaje'=>'Articulo actualizado satisfactoriamente'
            ));
        }
        return new JsonResponse(array(
            'valor'=>false,
            'mensaje'=>$form->createView()
        ));
    }


    /**
     * @Route("/eliminar", name="eliminarArticulo")
     * @Method({"POST"})
     */
    public function eliminarAction(Request $peticion){
        $em = $this->getDoctrine()->getEntityManager();
        $idElemento = $peticion->get('idElemento');

        try{
            $articulo = $em->getRepository('AppBundle:Articulo')->find($idElemento);

            if(count($articulo->getArticulosPedidos()) == 0 &&
                count($articulo->getItems()) == 0){
                $em->remove($articulo);
            }else {
                $establecimiento = $articulo->getEstablecimiento();

                $establecimiento->removeArticulo($articulo);
                $articulo->setEstablecimiento(null);
                $em->persist($articulo);
                $em->persist($establecimiento);
            }
            $em->flush();
        }catch (\Exception $e){
            return new JsonResponse(array(
                'valor'=>false,
                'mensaje'=>/*'Fallo al eliminar el articulo'*/ $e->getMessage()
            ));
        }
        return new JsonResponse(array(
            'valor'=>true,
            'mensaje'=>'Articulo Eliminado con exito'
        ));
    }

    private function formularioCrear(Articulo $entity){
        $form = $this->createForm(new ArticuloType(), $entity, array(
            'action' => $this->generateUrl('registrosArticulos'),
            'method' => 'POST',
        ));

        return $form;
    }

    private function formularioEditar(Articulo $entity){
        $form = $this->createForm(new ArticuloType(), $entity, array(
            'action' => $this->generateUrl('registrosArticulos'),
            'method' => 'POST',
        ));

        return $form;
    }
}