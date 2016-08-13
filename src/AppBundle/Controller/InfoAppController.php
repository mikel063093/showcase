<?php

namespace AppBundle\Controller;

use AppBundle\Entity\InformacionApp;
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
        return $this->render('administrador/informacion/editar.html.twig', array(
            'entity' => $entity,
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
        $precio = $peticion->get('precio');
        if (!$precio){
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=> false,
                'mensaje'=> 'El campo Precio es obligatorio'
            ));
        }

        $entity->setPrecioDomicilio($precio);

        $em->persist($entity);
        $em->flush();
        return new \Symfony\Component\HttpFoundation\JsonResponse(array(
            'valor'=>true,
            'mensaje'=>'Informacion actualizada satisfactoriamente'
        ));

    }


}