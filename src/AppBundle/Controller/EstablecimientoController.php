<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Establecimiento;
use AppBundle\Form\EstablecimientoType;

/**
* @Route("/administracion/establecimientos")
*/
class EstablecimientoController extends Controller
{
    /**
     * @Route("/", name="establecimientoPrincipal")
     */
    public function indexAction(Request $request)
    {
        
        return $this->render('administrador/establecimiento/principal.html.twig');
    }

    /**
     * @Route("/registros", name="registrosEstablecimientos")
     */
    public function registrosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $filtro = $request->get('filtroNombre');
        if (!$filtro){
            $filtro = '';
        }
        if($filtro == '') {
            $establecimientos = $em->getRepository('AppBundle:Establecimiento')->buscarEstablecimientos();
        }else{
            $establecimientos = $em->getRepository('AppBundle:Establecimiento')->buscarEstablecimientosNombre($filtro);
        }

        return $this->render('administrador/establecimiento/registros.html.twig',array(
                'establecimientos'=>$establecimientos,
                'filtro' => $filtro
            )
        );
    } 

    /**
    *
    * @Route("/nuevo", name="nuevoEstablecimiento")
    */
    public function nuevoaAction(Request $request){
    	$em = $this->getDoctrine()->getManager();
    	$categorias = $em->getRepository('AppBundle:Categoria')->findAll();
        $zonas = $em->getRepository('AppBundle:Zona')->findAll();
        $planes = $em->getRepository('AppBundle:Plan')->findAll();
    	$entity = new Establecimiento();
        $form   = $this->formularioCrear($entity);
		return $this->render('administrador/establecimiento/nuevo.html.twig',array(
			'form'=>$form->createView(),
			'entity'=>$entity,
			'categorias'=>$categorias,
            'zonas'=>$zonas,
            'planes'=>$planes
			));

    }

    /**
    *
    * @Route("/guardar", name="guardarEstablecimiento")
    */
    public function guardarAction(Request $request){
        $entity = new Establecimiento();
        $form   = $this->formularioCrear($entity);
        $form->handleRequest($request);
        $coordenadas = $request->get('coordenadas');
        $errors = $this->get('validator')->validate($entity);
        if (count($errors) > 0){
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=> false,
                'mensaje'=> $errors[0]->getMessage()
            ));
        }
        if ($coordenadas == ""){
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=> false,
                'mensaje'=> 'La localizacion es obligatoria'
            ));
        }
        $entity->upload();
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $plan=$em->getRepository('AppBundle:Plan')->find($request->get('plan'));
            $categoria=$em->getRepository('AppBundle:Categoria')->find($request->get('categoria'));
            $zona=$em->getRepository('AppBundle:Zona')->find($request->get('zona'));
            $entity->setPlan($plan);
            $entity->setCategoria($categoria);
            $entity->setZona($zona);
            $entity->setLocalizacion($coordenadas);
            $em->persist($entity);
            $em->flush();

            $client = $this->get('notificaciones');
            $resultado = $client->enviarNotificacion('Nuevo Establecimiento','Showcase cuenta con un nuevo establecimiento');
            //var_dump(json_encode( $resultado));

            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=>true,
                'mensaje'=>'Establecimiento creado satisfactoriamente'
            ));
        }
        return new JsonResponse(array(
            'valor'=>false,
            'mensaje'=>'El establecimiento no se pudo crear.'
        ));

    }


    /**
     * @Route("/editar", name="editarEstablecimiento")
     * @Method({"POST"})
     */
    public function editarAction(Request $peticion){
        $idUsuario=$peticion->request->get('idElemento');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Establecimiento')->find($idUsuario);
        $form   = $this->formularioEditar($entity);
        $categorias = $em->getRepository('AppBundle:Categoria')->findAll();
        $zonas = $em->getRepository('AppBundle:Zona')->findAll();
        $planes = $em->getRepository('AppBundle:Plan')->findAll();
        
        return $this->render('administrador/establecimiento/editar.html.twig', array(
            'categorias'=>$categorias,
            'zonas'=>$zonas,
            'planes'=>$planes,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Route("/actualizar", name="actualizarEstablecimiento")
     * @Method({"POST"})
     */
    public function actualizarAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $idEntidad=$peticion->request->get('id_entity');
        $entity = $em->getRepository('AppBundle:Establecimiento')->find($idEntidad);
        $form   = $this->formularioCrear($entity);
        $form->handleRequest($peticion);
        $coordenadas = $peticion->get('coordenadas');
        $errors = $this->get('validator')->validate($entity);
        if (count($errors) > 0){
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=> false,
                'mensaje'=> $errors[0]->getMessage()
            ));
        }
        if ($coordenadas == ""){
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=> false,
                'mensaje'=> 'La localizacion es obligatoria'
            ));
        }
        $entity->upload();
        if ($form->isValid()) {
            

            $plan=$em->getRepository('AppBundle:Plan')->find($peticion->get('plan'));
            $categoria=$em->getRepository('AppBundle:Categoria')->find($peticion->get('categoria'));
            $zona=$em->getRepository('AppBundle:Zona')->find($peticion->get('zona'));
            $entity->setPlan($plan);
            $entity->setCategoria($categoria);
            $entity->setZona($zona);
            $entity->setLocalizacion($coordenadas);
            $em->persist($entity);
            $em->flush();
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=>true,
                'mensaje'=>'Establecimiento actualizado satisfactoriamente'
            ));
        }
        return new JsonResponse(array(
            'valor'=>false,
            'mensaje'=>'El establecimiento no se pudo actualizar.'
        ));
    }


    /**
     * @Route("/eliminar", name="eliminarEstablecimiento")
     * @Method({"POST"})
     */
    public function eliminarAction(Request $peticion){
        $em = $this->getDoctrine()->getEntityManager();
        $idElemento = $peticion->get('idElemento');
        try{
            $establecimiento =  $em->getRepository('AppBundle:Establecimiento')->find($idElemento);

            if(count($establecimiento->getArticulos()) == 0 &&
                count($establecimiento->getFotosEstablecimientos()) == 0 &&
                count($establecimiento->getPuntuaciones()) == 0 ){
                $em->remove($establecimiento);
            }else {
                $plan = $establecimiento->getPlan();
                $zona = $establecimiento->getZona();
                $categoria = $establecimiento->getCategoria();

                $plan->removeEstablecimiento($establecimiento);
                $zona->removeEstablecimiento($establecimiento);
                $categoria->removeEstablecimiento($establecimiento);
                $establecimiento->setCategoria(null);
                $establecimiento->setPlan(null);
                $establecimiento->setZona(null);
                $em->persist($plan);
                $em->persist($categoria);
                $em->persist($zona);
                $em->persist($establecimiento);
            }
            $em->flush();
        }catch (\Exception $e){
            return new JsonResponse(array(
                'valor'=>false,
                'mensaje'=>'Fallo al eliminar el establecimiento'
            ));
        }

        return new JsonResponse(array(
            'valor'=>true,
            'mensaje'=>'Establecimiento eliminado con exito'
        ));
    }

    private function formularioCrear(Establecimiento $entity){
        $form = $this->createForm(new EstablecimientoType(), $entity, array(
            'action' => $this->generateUrl('registrosEstablecimientos'),
            'method' => 'POST',
        ));

        return $form;
    }

    private function formularioEditar(Establecimiento $entity){
        $form = $this->createForm(new EstablecimientoType(), $entity, array(
            'action' => $this->generateUrl('registrosEstablecimientos'),
            'method' => 'POST',
        ));

        return $form;
    }


}