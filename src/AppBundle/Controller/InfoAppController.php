<?php

namespace AppBundle\Controller;

use AppBundle\Entity\InformacionApp;
use AppBundle\Form\InfoAppType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Articulo;
use AppBundle\Form\ArticuloType;

/**
* @Route("/administracion/informacion")
*/
class InfoAppController extends Controller
{
    /**
     * @Route("/", name="infoAppPrincipal")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $informaciones = $em->getRepository('AppBundle:InformacionApp')->findAll();
        if(count($informaciones)==0){
            $informacion = new InformacionApp();
            $em->persist($informacion);
            $em->flush();
        }else{
            $informacion = $informaciones[0];
        }
        return $this->render('administrador/informacion/principal.html.twig',array(
            'informacion' => $informacion
        ));
    }

    /**
     * @Route("/editar", name="editarInformacion")
     * @Method({"POST"})
     */
    public function editarAction(Request $peticion){
        $idInfo=$peticion->request->get('idElemento');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:InformacionApp')->find($idInfo);
        $form   = $this->formularioCrear($entity);
        return $this->render('administrador/informacion/editar.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Route("/actualizar", name="actualizarInformacion")
     * @Method({"POST"})
     */
    public function actualizarAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $idEntidad=$peticion->request->get('id_entity');
        $entity = $em->getRepository('AppBundle:InformacionApp')->find($idEntidad);
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

            $em->persist($entity);
            $em->flush();
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor' => true,
                'mensaje' => 'Información actualizada satisfactoriamente'
            ));
        }

        return new JsonResponse(array(
            'valor'=>false,
            'mensaje'=>'No se pudo actualizar la información'
        ));

    }

    private function formularioCrear(InformacionApp $entity){
        $form = $this->createForm(new InfoAppType(), $entity, array(
            'action' => $this->generateUrl('infoAppPrincipal'),
            'method' => 'POST',
        ));

        return $form;
    }


}