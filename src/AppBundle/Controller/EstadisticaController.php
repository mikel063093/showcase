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
* @Route("/administracion/estadisticas")
*/
class EstadisticaController extends Controller
{
    /**
     * @Route("/", name="estadisticasPrincipal")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        return $this->render('administrador/estadistica/principal.html.twig');
    }

    /**
     * @Route("/ver", name="verEstadisticas")
     */
    public function verAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $establecimientos = $em->getRepository('AppBundle:Establecimiento')->findAll();
        $zonas = $em->getRepository('AppBundle:Zona')->findAll();
        $planes = $em->getRepository('AppBundle:Plan')->findAll();
        return $this->render('administrador/estadistica/ver.html.twig',array(
            'establecimientos' => $establecimientos,
            'zonas' => $zonas,
            'planes' => $planes
        ));
    } 






}