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
* @Route("/administracion/reservas")
*/
class ReservaController extends Controller
{
    /**
     * @Route("/", name="reservaPrincipal")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        return $this->render('administrador/reserva/principal.html.twig');
    }

    /**
     * @Route("/registros", name="registrosReservas")
     */
    public function registrosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $reservas = $em->getRepository('AppBundle:Pedido')->buscarReservas();
        return $this->render('administrador/reserva/registros.html.twig',array(
            'reservas' => $reservas
        ));
    } 




    /**
     * @Route("/editar", name="editarReserva")
     * @Method({"POST"})
     */
    public function editarAction(Request $peticion){
        $idElemento=$peticion->request->get('idElemento');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Pedido')->find($idElemento);
        $estados = array();
        switch ($entity->getEstado()){
            case 'Activo':
                $estados = array(
                    'Activo',
                    'En Progreso',
                    'Cancelado'
                );
                break;
            case 'En Progreso':
                $estados = array(
                    'En Progreso',
                    'Cancelado',
                    'Finalizado'
                );
                break;
        }
        return $this->render('administrador/reserva/editar.html.twig', array(

            'entity' => $entity,
            'estados' => $estados
        ));
    }

    /**
     * @Route("/actualizar", name="actualizarReserva")
     * @Method({"POST"})
     */
    public function actualizarAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $idEntidad=$peticion->request->get('id_entity');
        $entity = $em->getRepository('AppBundle:Pedido')->find($idEntidad);
        $estado = $peticion->get('estado');

        try {

            $entity->setEstado($estado);

        
            $em->persist($entity);
            $em->flush();
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'valor'=>true,
                'mensaje'=>'Reserva actualizada satisfactoriamente'
            ));
        }catch (\Exception $e) {
            return new JsonResponse(array(
                'valor' => false,
                'mensaje' => 'Error al actualizar la reserva'
            ));
        }
    }

    /**
     * @Route("/ver", name="verReserva")
     * @Method({"POST"})
     */
    public function verAction(Request $peticion){
        $idElemento=$peticion->request->get('idElemento');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Pedido')->find($idElemento);

        return $this->render('administrador/reserva/ver.html.twig', array(

            'entity' => $entity,

        ));
    }

}