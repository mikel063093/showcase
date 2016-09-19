<?php

namespace WebBundle\Controller;

use AppBundle\Entity\Carrito;
use AppBundle\Entity\Contactenos;
use AppBundle\Entity\Item;
use AppBundle\Form\ContactoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Usuario;
use Symfony\Component\HttpFoundation\Session\Session;



/**
* @Route("/")
*/
class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $peticion)
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
        $articulosDestacados = $em->getRepository('AppBundle:Articulo')->obtenerArticulosDestacados();
        $this->limpiarCarritos();
        $session = $peticion->getSession();

        if(!$session){

            $session = new Session();
        }
        $carrito = $session->get('carrito');

        if(!$carrito){

            $carro = new Carrito();
            $fecha = new \DateTime();
            $fechaLimite =  new \DateTime();
            $fechaLimite->add(new \DateInterval('PT60M'));
            $carro->setFechaCreacion($fecha);
            $carro->setFechaLimite($fechaLimite);
            $em->persist($carro);
            $em->flush();
            $carrito = array(
                'id' => $carro->getId(),
                'items' => array()
            );
            $session->set('carrito',$carrito);
        }
        return $this->render('web/home.html.twig',array(
            'categorias' => $categorias,
            'promociones' => $promociones,
            'establecimientosDestacados' => $establecimientosDestacados,
            'articulosDestacados' => $articulosDestacados,
            
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

    /**
     * @Route("/nosotros", name="nosotros")
     * @Method({"GET"})
     */
    public function nosotrosAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $infoApp = $em->getRepository('AppBundle:InformacionApp')->find(1);
        return $this->render('web/nosotros.html.twig',array(
            'nosotros' => $infoApp,

        ));
    }

    /**
     * @Route("/planes", name="planes")
     * @Method({"GET"})
     */
    public function planesAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $planes = $em->getRepository('AppBundle:Plan')->findAll();
        return $this->render('web/planes.html.twig',array(
            'planes' => $planes,

        ));
    }
    /**
     * @Route("/contacto/{idPlan}", name="contacto",defaults={"idPlan" = null} )
     * @Method({"GET"})
     */
    public function contactoAction(Request $peticion,$idPlan){
        $em = $this->getDoctrine()->getManager();
        $contacto = new Contactenos();

        $plan = null;
        if($idPlan){
            $plan = $em->getRepository('AppBundle:Plan')->find($idPlan);
            $contacto->setAsunto("Deseo acceder al plan ".strtoupper($plan->getNombre()));
        }
        $form = $this->formularioCrear($contacto);
        return $this->render('web/contacto.html.twig',array(
            'plan' => $plan,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/enviarContacto", name="enviarContacto" )
     * @Method({"POST"})
     */
    public function enviarContactoAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $rta = array(
            'estado' => 1
        );
        $contacto = new Contactenos();
        try {
            $form = $this->formularioCrear($contacto);
            $form->handleRequest($peticion);

            if ($form->isValid()) {

                $contacto->setFecha(new \DateTime());
                $mail=$this->get('correo');
                $mail->setVista('web/correo.html.twig')
                    ->setPara(array('showcase.popayan@gmail.com',$contacto->getCorreo()))
                    ->setTitulo('Correo de contacto')
                    ->setContenido(
                        'Asunto: '.$contacto->getAsunto().'<br/>'.
                        'Nombre: '.$contacto->getNombreCompleto().'<br/>'.
                        'Correo: '.$contacto->getCorreo().'<br/>'.
                        'Mensaje: <p>'.$contacto->getComentario().'</p><br/>'.
                        'enviado el '.$contacto->getFecha()->format('Y-m-d H:i:s')
                    );
                $mail->enviar();

                $em->persist($contacto);
                $em->flush();
            } else {
                $rta = array(
                    'estado' => 0,
                    'mensaje' => $form->getErrorsAsString()
                );
            }
        }catch (Exception $e){
            $rta = array(
                'estado' => 0,
                'mensaje' => $e->getMessage()
            );
        }

        return new JsonResponse($rta);

    }

    private function formularioCrear(Contactenos $entity){
        $form = $this->createForm(new ContactoType(), $entity, array(
            'action' => $this->generateUrl('home'),
            'method' => 'POST',
        ));

        return $form;
    }

    private function limpiarCarritos(){
        $em = $this->getDoctrine()->getManager();
        $carritos = $em->getRepository('AppBundle:Carrito')->buscarCarritosVencidos();
        foreach ($carritos as $carrito){
            foreach($carrito->getItems() as $item){

                $articulo = $item->getArticulo();
                $articulo->setCantidad($articulo->getCantidad()+$item->getCantidad());
                $em->persist($articulo);
                $em->remove($item);
            }

            $em->remove($carrito);
        }

        $em->flush();
    }

    /**
     * @Route("/agregarProductoCarrito", name="agregarProductoCarritoWeb")
     */
    public function agregarProductoCarritoAction(Request $peticion)
    {
        $this->limpiarCarritos();
        $em = $this->getDoctrine()->getManager();
        $idArticulo = $peticion->get('idArticulo');
        $idCarrito = $peticion->get();
        $cantidad = $peticion->get('cantidad');
        $rta=array(
            'estado'=>1,
            'mensaje'=> 'Exito al agregar el producto al carrito',
            'borrado' => false
        );

        try{
            $articulo = $em->getRepository('AppBundle:Articulo')->find($idArticulo);
            if($articulo->getCantidad() < $cantidad){
                return new JsonResponse(array(
                    'estado'=>0,
                    'mensaje'=> 'No se puede reservar esta cantidad para este articulo, cantidad disponible: '.$articulo->getCantidad(),
                    'borrado' => false
                ));
            }
            $articulo->setCantidad($articulo->getCantidad() - $cantidad );
            $em->persist($articulo);

            $carrito = $em->getRepository('AppBundle:Carrito')->find($idCarrito);
            $sesion = $peticion->getSession();

            if(!$carrito){
                $carrito = new Carrito();
                $carrito->setFechaCreacion(new \DateTime());
                $fechaLimite = new \DateTime();
                $fechaLimite->add(new \DateInterval('PT60M'));
                $carrito->setFechaLimite($fechaLimite);
                $em->persist($carrito);

            }else{
                $rta['borrado'] = true;
            }

            $item = new Item();
            $item->setCantidad($cantidad);
            $item->setCarrito($carrito);
            $item->setArticulo($articulo);
            $em->persist($item);
            $em->flush();
            $carro = array(
                'id' => $carrito->getId(),

            );
            $rta['carrito'] = $carro;
        }catch (Exception $e){
            $rta=array(
                'estado'=>0,
                'mensaje'=> 'Error al agregar el producto al carrito',
                'borrado' => false
            );
        }
        return new JsonResponse( $rta);

    }
}
