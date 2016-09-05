<?php

namespace WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Usuario;

/**
* @Route("/")
*/
class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        
        /*
        //Codigo para crear el usuario administrador
        $factory = $this->get('security.encoder_factory');
        $user = new Usuario();
        $password='1234';
        $encoder = $factory->getEncoder($user);
        $user->setSalt(md5(time()));
        $pass = $encoder->encodePassword($password, $user->getSalt());
        $user->setNombres('admin');
        $user->setApellidos('admin');
        $user->setUsername('admin');
        $user->setCorreo('alejo491@gmail.com');
        $user->setPassword($pass);

        $em = $this->getDoctrine()->getEntityManager();
        $rol=$em->getRepository('AppBundle:Rol')->find(2);
        $user->setRol($rol);
        $em->persist($user);
        $em->flush();
        
        //Codigo para crear el usuario normal
        $factory = $this->get('security.encoder_factory');
        $user = new Usuario();
        $password='1234';
        $encoder = $factory->getEncoder($user);
        $user->setSalt(md5(time()));
        $pass = $encoder->encodePassword($password, $user->getSalt());
        $user->setNombres('Alejandro');
        $user->setApellidos('Giraldo');
        $user->setUsername('alejo491@hotmail.es');
        $user->setCorreo('alejo491@hotmail.es');
        $user->setPassword($pass);

        $em = $this->getDoctrine()->getEntityManager();
        $rol=$em->getRepository('AppBundle:Rol')->find(1);
        $user->setRol($rol);
        $em->persist($user);
        $em->flush();
        */
        $em = $this->getDoctrine()->getManager();
        $categorias = $em->getRepository('AppBundle:Categoria')->findAll();
        $promociones = $em->getRepository('AppBundle:Promocion')->findPromocionesActivas();
        $establecimientosDestacados = $em->getRepository('AppBundle:Establecimiento')->obtenerEstablecimintosDestacados();
        return $this->render('web/home.html.twig',array(
            'categorias' => $categorias,
            'promociones' => $promociones,
            'establecimientosDestacados' => $establecimientosDestacados,
            
        ));
    }

    /**
     * @Route("/establecimientos/{idCategoria}", name="establecimientosCategoria",defaults={"idCategoria" = null})
     * @Method({"GET"})
     */
    public function establecimientosCategoriaAction(Request $peticion, $idCategoria){
        $em = $this->getDoctrine()->getManager();
        $categoria = null;
        $establecimientos = array();
        if($idCategoria < 0){
            $idCategoria = 0;
        }
        if($idCategoria == 0){
            $establecimientos = $em->getRepository('AppBundle:Establecimiento')->findAll();
        }else{
            $categoria = $em->getRepository('AppBundle:Categoria')->find($idCategoria);
            $establecimientos = $categoria->getEstablecimientos();
        }
        $establecimientosDestacados = $em->getRepository('AppBundle:Establecimiento')->obtenerEstablecimintosDestacados($idCategoria);
        return $this->render('web/establecimiento/registros.html.twig',array(
            'categoria' => $categoria,
            'establecimientos' => $establecimientos,
            'establecimientosDestacado' => $establecimientosDestacados
        ));
    }

    /**
     * @Route("/establecimiento/{idEstablecimiento}", name="establecimiento",defaults={"idEstablecimiento" = null})
     * @Method({"GET"})
     */
    public function establecimientoAction(Request $peticion, $idEstablecimiento){
        $em = $this->getDoctrine()->getManager();
        $establecimiento = $em->getRepository('AppBundle:Establecimiento')->find($idEstablecimiento);
        $articulos= $em->getRepository('AppBundle:Articulo')->obtenerArticulosEstablecimiento($idEstablecimiento);
        return $this->render('web/establecimiento/ver.html.twig',array(
            'establecimiento' => $establecimiento,
            'articulos' => $articulos
        ));
    }
}
