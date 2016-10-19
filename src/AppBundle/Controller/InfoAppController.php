<?php

namespace AppBundle\Controller;

use AppBundle\Entity\InformacionApp;
use AppBundle\Form\InfoAppType;
use AppBundle\Form\InfoAppInicioType;
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
        return $this->render('administrador/informacion/inicio.html.twig',array(
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
     * @Route("/editarInicio", name="editarInicio")
     * @Method({"POST"})
     */
    public function editarInicioAction(Request $peticion){
        $idInfo=$peticion->request->get('idElemento');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:InformacionApp')->find($idInfo);
        $form   = $this->formularioCrearInicio($entity);
        return $this->render('administrador/informacion/editarInicio.html.twig', array(
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

    /**
     * @Route("/actualizarInicio", name="actualizarInicio")
     * @Method({"POST"})
     */
    public function actualizarInicioAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $idEntidad=$peticion->request->get('id_entity');
        $entity = $em->getRepository('AppBundle:InformacionApp')->find($idEntidad);
        $form   = $this->formularioCrearInicio($entity);
        $form->handleRequest($peticion);
        $errors = $this->get('validator')->validate($entity);
        if (count($errors) > 0){
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=> false,
                'mensaje'=> $errors[0]->getMessage()
            ));
        }
        $entity->uploadInicio();
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

    /**
     * @Route("/nosotrosAdmin", name="nosotrosAdmin")
     */
    public function nosotrosAction(Request $request)
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
        return $this->render('administrador/informacion/nosotros.html.twig',array(
            'informacion' => $informacion
        ));
    }

    /**
     * @Route("/editarNosotros", name="editarNosotros")
     * @Method({"POST"})
     */
    public function editarNosotrosAction(Request $peticion){
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
     * @Route("/actualizarNosotros", name="actualizarNosotros")
     * @Method({"POST"})
     */
    public function actualizarNosotrosAction(Request $peticion){
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



    /**
     * @Route("/precioAdmin", name="precioAdmin")
     */
    public function precioAction(Request $request)
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
        return $this->render('administrador/informacion/precio.html.twig',array(
            'informacion' => $informacion
        ));
    }

    /**
     * @Route("/editarPrecio", name="editarPrecio")
     * @Method({"POST"})
     */
    public function editarPrecioAction(Request $peticion){
        $idInfo=$peticion->request->get('idElemento');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:InformacionApp')->find($idInfo);

        return $this->render('administrador/informacion/editarPrecio.html.twig', array(
            'entity' => $entity,

        ));
    }

    /**
     * @Route("/actualizarPrecio", name="actualizarPrecio")
     * @Method({"POST"})
     */
    public function actualizarPrecioAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $idEntidad=$peticion->request->get('id_entity');
        $precio = $peticion->get('precio');
        $entity = $em->getRepository('AppBundle:InformacionApp')->find($idEntidad);

        if ($precio == 0){
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=> false,
                'mensaje'=> 'El precio debe ser mayor a 0'
            ));
        }

            $entity->setPrecioDomicilio($precio);
            $em->persist($entity);
            $em->flush();
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor' => true,
                'mensaje' => 'Información actualizada satisfactoriamente'
            ));




    }

    private function formularioCrear(InformacionApp $entity){
        $form = $this->createForm(new InfoAppType(), $entity, array(
            'action' => $this->generateUrl('infoAppPrincipal'),
            'method' => 'POST',
        ));

        return $form;
    }

    private function formularioCrearInicio(InformacionApp $entity){
        $form = $this->createForm(new InfoAppInicioType(), $entity, array(
            'action' => $this->generateUrl('infoAppPrincipal'),
            'method' => 'POST',
        ));

        return $form;
    }

}