<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Articulo;
use AppBundle\Entity\ArticulosPedido;
use AppBundle\Entity\Carrito;
use AppBundle\Entity\CuponUsuario;
use AppBundle\Entity\Direccion;
use AppBundle\Entity\InformacionApp;
use AppBundle\Entity\Item;
use AppBundle\Entity\Pedido;
use AppBundle\Entity\Puntuacion;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use AppBundle\Entity\Usuario;
/**
 * 
 * @Route("/movil", name="movil")
 */
class MovilController extends Controller
{
    
    
    /**
     * @Route("/registrar", name="movilRegistrar")
     * 
     */
    public function registrarAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $nombre = $peticion->request->get('nombre');
        $apellido = $peticion->request->get('apellido');
        $correo = $peticion->request->get('correo');
        $clave = $peticion->request->get('clave');
        
        $datos = array( "id"=> '0',"nombre"=> "","apellido"=>"","correo"=> "","foto" => "",
            "estado"=> 'exito',
            "mensaje"=> 'Usuario registrado correctamente.'
        );
        if(!$correo || $correo==null || $correo=='' ){
            $datos['estado'] = 'error';
            $datos['mensaje'] = 'Validación incorrecta, rectifique sus datos.';
            return new JsonResponse($datos);
        }
        
        try {
            
                $existe = $em->getRepository('AppBundle:Usuario')->findOneBy(array('correo' => $correo));
            
            
            if(!$existe){
                $existe = new Usuario();
                $existe->setNombres($nombre);
                $existe->setApellidos($apellido);
                $existe->setCorreo($correo);
                $existe->setUsername($correo);
                $rol = $em->getRepository('AppBundle:Rol')->findOneBy(array('codigo' => "ROLE_USER"));
                $existe->setRol($rol);
                $existe->setSalt(md5(time()));
                $encoder = $this->container->get('security.encoder_factory')->getEncoder($existe);
                $passwordCodificado = $encoder->encodePassword($clave,$existe->getSalt());
                $existe->setPassword($passwordCodificado);    
                $em->persist($existe);
                $em->flush();
                $datos['id'] = "".$existe->getId();
                $datos['nombre'] = $existe->getNombres();
                $datos['apellido'] = $existe->getApellidos();
                $datos['correo'] = $existe->getCorreo();
                
            }else{
                
                $datos['id'] = "".$existe->getId();
                $datos['nombre'] = $existe->getNombres();
                $datos['apellido'] = $existe->getApellidos();
                $datos['correo'] = $existe->getCorreo();
                if($existe->getFoto()){
                    $datos['foto'] = $this->container->getParameter('servidor').'/imagenes/perfil/'.$existe->getFoto();
                }
                $passwordCodificado = $existe->getPassword();
            }
            
            $url = $this->generateUrl('api_login_check', array(), UrlGeneratorInterface::ABSOLUTE_URL);

            $par = array(
                '_username' => $existe->getUsername(),
                '_password' => $clave
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($par));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            $response = curl_exec($ch);
            curl_close($ch);

            if ($response === false) {
                $datos['estado'] = 'error';
                $datos['mensaje'] = 'Validacion incorrecta.';
            } else {
                
                $jsonStr = strpbrk($response, "{");
                $jsonToken = json_decode($jsonStr);

                if (isset($jsonToken->token)) {
                    $datos["token"] = $jsonToken->token;
                }
            }
        }  catch (\Exception $e){
            $datos['estado'] = 'error';
            $datos['mensaje'] = 'Error en el sistema.';
            //$datos['mensaje'] = $e->getMessage();
        }
       return new JsonResponse($datos);
    }
    
    
    /**
     * @Route("/validar", name="movilValidar")
     */
    public function validarAction(Request $peticion){
        
        $em = $this->getDoctrine()->getManager();
        $login = $peticion->request->get('login');
        $pass = $peticion->request->get('pass');       
        $datos = array( "id"=> '0',"nombre"=> '',"apellido"=>"","correo"=> '',"foto" => "",
            "estado"=> 'exito',
            "mensaje"=> 'Usuario validado correctamente.',
            "token" => '0'
        );
        
        try {

            $usuarios = $em->getRepository('AppBundle:Usuario')->findBy(array('username'=>$login));
            if (count($usuarios) == 0) {
                $datos['estado'] = 'error';
                $datos['mensaje'] = 'Validacion incorrecta.';
            } else {
                $existe = $usuarios[0];
                $encoder = $this->container->get('security.encoder_factory')->getEncoder($existe);
                $passwordCodificado = $encoder->encodePassword($pass, $existe->getSalt());
                
                if ($passwordCodificado == $existe->getPassword()) {
                    
                    $datos['id'] = "" . $existe->getId();
                    $datos['nombre'] = $existe->getNombres();
                    $datos['correo'] = $existe->getCorreo();
                    $datos['apellido'] = $existe->getApellidos();
                    if($existe->getFoto()){
                        $datos['foto'] = $this->container->getParameter('servidor').'/imagenes/perfil/'.$existe->getFoto();
                    }
                    
                    $url = $this->generateUrl('api_login_check', array(), UrlGeneratorInterface::ABSOLUTE_URL);
                    $par = array(
                        '_username' => $existe->getUsername(),
                        '_password' => $pass
                    );
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_HEADER, true);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($par));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    $response = curl_exec($ch);
                    curl_close($ch);

                    if ($response === false) {
                        $datos['estado'] = 'error';
                        $datos['mensaje'] = 'Validacion incorrecta.';
                    } else {
                       
                        $jsonStr = strpbrk($response, "{");
                        $jsonToken = json_decode($jsonStr);
                        if (isset($jsonToken->token)) {
                            $datos["token"] = $jsonToken->token;
                        }
                    }
                }else{
                    $datos['estado'] = 'error';
                    $datos['mensaje'] = 'Contraseña incorrecta.';

                }
            }
        } catch (\Exception $e) {
            $datos['estado'] = 'error';
            $datos['mensaje'] = $e->getMessage();
        }
        
        
        
        return new JsonResponse($datos);
    }

    /**
     * @Route("/registroRedesMovil", name="registroRedesMovil")
     */
    public function registroRedesMovilAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
       
        $accessToken = $peticion->request->get('access_token');

        $email = "";
        $nombres = "";
        $apellidos = "";
        $datos = array( "id"=> '0',"nombre"=> "","apellido"=>"","correo"=> "","foto" => "",
            "estado"=> 'exito',
            "mensaje"=> 'Usuario registrado correctamente.'
        );
        try{
            $facebookApp = new \Facebook\FacebookApp($this->container->getParameter('facebookId'), $this->container->getParameter('facebookSecret'));
            $fb = new \Facebook\Facebook([
                'app_id' => $this->container->getParameter('facebookId'),
                'app_secret' => $this->container->getParameter('facebookSecret'),
                'default_graph_version' => 'v2.6',
            ]);
            $request = new \Facebook\FacebookRequest($facebookApp, $accessToken, 'GET', '/me', array(
                'fields' => 'id,email,first_name,last_name'
            ));
            $response = $fb->getClient()->sendRequest($request);
            if (isset($response->getGraphUser()["email"])) {
                $email = $response->getGraphUser()["email"];
                $nombres = $response->getGraphUser()["first_name"];
                $apellidos = $response->getGraphUser()["last_name"];
            }
                
            if ($email != "") {
                $password = $response->getGraphUser()["id"];
                $user = $em->getRepository('AppBundle:Usuario')->findOneBy(array('username' => $email));
                if (!$user) {
                    $user = new \AppBundle\Entity\Usuario();
                    $user->setCorreo($email);
                    $user->setNombres($nombres);
                    $user->setApellidos($apellidos);
                    $user->setUsername($email);
                    $user->setSalt(md5(time()));
                    $rol = $em->getRepository('AppBundle:Rol')->findOneBy(array('codigo' => "ROLE_USER"));
                    $user->setRol($rol);
                    $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
                    $passwordCodificado = $encoder->encodePassword($password, $user->getSalt());
                    $user->setPassword($passwordCodificado);
                    $em->persist($user);
                    $em->flush();

                }
                $url = $this->generateUrl('api_login_check', array(), UrlGeneratorInterface::ABSOLUTE_URL);
                $par = array(
                    '_username' => $user->getUsername(),
                    '_password' => $password
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($par));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                $response = curl_exec($ch);
                curl_close($ch);
                if ($response === false) {
                    $datos['estado'] = 'error';
                    $datos['mensaje'] = 'Validacion incorrecta.';
                } else {
                    
                    $jsonStr = strpbrk($response, "{");
                    $jsonToken = json_decode($jsonStr);
                    if (isset($jsonToken->token)) {
                        $datos["id"] = $user->getId();
                        $datos["nombre"] = $user->getNombres();
                        $datos["apellido"] = $user->getApellidos();
                        $datos["correo"] = $user->getCorreo();
                        if($user->getFoto()){
                            $datos['foto'] = $this->container->getParameter('servidor').'/imagenes/perfil/'.$user->getFoto();
                        }
                        $datos["token"] = $jsonToken->token;
                    }elseif(isset($jsonToken->code)){
                        if($jsonToken->code){
                            $datos['estado'] = 'error';
                            $datos['mensaje'] = 'Ya existe un usuario registrado con el correo '.$email.', porfavor intente recuperando la contraseña.';
                        }
                    }
                }
            } else {
                $datos['estado'] = 'error';
                $datos['mensaje'] = 'Imposible acceder a la información del Usuario.';
            }
        } catch (\Exception $e) {
            return new JsonResponse(array(
                "estado" => 'error',
                //"mensaje" => 'Imposible acceder a la información del Usuario.'
                "mensaje" => $e->getMessage()
            ));
        }
        return new JsonResponse($datos);
    }

    /**
     * @Route("/registroNotificacion", name="registroNotificaion")
     */
    public function registroNotificacionAction(Request $peticion){
        
        $em = $this->getDoctrine()->getManager();
       
        $id = $peticion->request->get('id');
        $gcmid = $peticion->request->get('gcmid');

        
        $datos = array( "id"=> '0',"gcmid"=> "",
            "estado"=> 'exito',
            "mensaje"=> 'Identificador de notificaciones registrado correctamente.'
        );
        try{

            $user = $em->getRepository('AppBundle:Usuario')->find($id);
            if ($user) {
                
                $user->setGcmId($gcmid);
                $em->persist($user);
                $em->flush();
                $datos["id"] = $user->getId();
                $datos["gcmid"] = $user->getGcmId();
            }else {
                $datos['estado'] = 'error';
                $datos['mensaje'] = 'Imposible acceder a la información del Usuario.';
            }
        } catch (\Exception $e) {
            return new JsonResponse(array(
                "estado" => 'error',
                //"mensaje" => 'Imposible acceder a la información del Usuario.'
                "mensaje" => $e->getMessage()
            ));
        }
        return new JsonResponse($datos);
    }

     /**
     * @Route("/editarPerfil", name="editarPerfilMovil")
     */
    public function editarPerfilAction(Request $peticion){
        
        $em = $this->getDoctrine()->getManager();
       
        $id = $peticion->request->get('id');
        $contenido = $peticion->request->get('contenido');
        $tipo = $peticion->request->get('tipo');
        $nombres = $peticion->request->get('nombres');
        $apellidos = $peticion->request->get('apellidos');
        $password = $peticion->request->get('password');
        $telefono = $peticion->request->get('telefono');

        $datos = array( "id"=> '0',"foto"=> "",
            "estado"=> 'exito',
            "mensaje"=> 'Perfil editado correctamente.'
        );
        try{

            $user = $em->getRepository('AppBundle:Usuario')->find($id);
            if ($user) {
                if($contenido != ""){
                    $data = base64_decode($contenido);
                    $file =   sha1($data) . $tipo;
                    $success = file_put_contents($user->getUploadRootDir().'/'.$file, $data);
                    $user->setFoto($file);
                }
                $user->setNombres($nombres);
                $user->setApellidos($apellidos);
                $user->setTelefono($telefono);
                if($password != ""){
                    $user->setSalt(md5(time()));
                    $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
                    $passwordCodificado = $encoder->encodePassword($password,$user->getSalt());
                    $user->setPassword($passwordCodificado);    
                }
                $em->persist($user);
                $em->flush();
                $datos["id"] = $user->getId();
                $datos["foto"] = $this->container->getParameter('servidor').'/imagenes/perfil/'.$user->getFoto();
                $datos["nombre"] = $user->getNombres();
                $datos["apellido"] = $user->getApellidos();
                $datos["telefono"] = $user->getTelefono();
                $datos["correo"] = $user->getCorreo();
            }else {
                $datos['estado'] = 'error';
                $datos['mensaje'] = 'Imposible acceder a la información del Usuario.';
            }
        } catch (\Exception $e) {
            return new JsonResponse(array(
                "estado" => 'error',
                //"mensaje" => 'Imposible acceder a la información del Usuario.'
                "mensaje" => $e->getMessage()
            ));
        }
        return new JsonResponse($datos);
    } 



    /**
     * @Route("/establecimientos", name="establecimientosMovil")
     */
    public function establecimientosAction(Request $peticion){
        
        $em = $this->getDoctrine()->getManager();
        $datos =array("estado" => 'exito',
                "mensaje" => "Establecimientos obtenidos exitosamente",
                "categorias" => array(),
                "totalPaginas" => 0,
                "pagina" => 0
                );
        
        try{

            $cats = $em->getRepository('AppBundle:Categoria')->findAll();
            foreach ($cats as  $c) {
                $cat = array("id" => $c->getId(),"nombre" => $c->getNombre());
                $establecimientos = $em->getRepository('AppBundle:Establecimiento')->findEstablecimientosCategoria($c->getId());
                $est = array();

                foreach ($establecimientos as $e) {
                    
                    $est[] = array(
                        'id' => $e->getId(),
                        'nombre' => $e->getNombre(),
                        'logo' => array($this->container->getParameter('servidor').$e->getWebPath())
                        );
                    
                }
                $cat['establecimientos']=$est;

                $datos["categorias"][] = $cat; 
            }

            $promociones = $em->getRepository('AppBundle:Promocion')->findPromocionesActivas();
            $promocionesArray = array();
            foreach($promociones as $promocion){
                $promocionesArray[] = $this->container->getParameter('servidor').$promocion->getWebPath();
            }
            $datos['promociones'] = $promocionesArray;

        } catch (\Exception $e) {
            return new JsonResponse(array(
                "estado" => 'error',
                //"mensaje" => 'Imposible acceder a la información del Usuario.'
                "mensaje" => $e->getMessage()
            ));
        }
        return new JsonResponse($datos);
    }

    /**
     * @Route("/establecimiento", name="establecimientoMovil")
     */
    public function establecimientoAction(Request $peticion){
        
        $em = $this->getDoctrine()->getManager();
        $datos =array("estado" => 'exito',
                "mensaje" => "Establecimiento obtenido exitosamente",
                
                );
        
        try{
            $imagenes = array();
            $id = $peticion->get('id');
            $establecimiento = $em->getRepository('AppBundle:Establecimiento')->find($id);
            $datos["id"] = $establecimiento->getId();
            $datos["nombre"] = $establecimiento->getNombre();
            $datos["descripcion"] = $establecimiento->getDescripcion();
            $datos["direccion"] = $establecimiento->getDireccion();
            $datos["telefono"] = $establecimiento->getTelefono();
            $datos["sitioWeb"] = $establecimiento->getSitioWeb();
            $datos["facebook"] = $establecimiento->getFacebook();
            $datos["twitter"] = $establecimiento->getTwitter();
            $datos["snapchat"] = $establecimiento->getSnapchat();
            $datos["youtube"] = $establecimiento->getYoutube();
            $datos["instagram"] = $establecimiento->getInstagram();
            $datos["correo"] = $establecimiento->getCorreo();
            $datos["whatsapp"] = $establecimiento->getWhatsapp();
            if($establecimiento->getLogo() != "" ){
                $imagenes[] = $this->container->getParameter('servidor').'/'.$establecimiento->getWebPath();
            }

            foreach ($establecimiento->getFotosEstablecimientos() as $foto){
                $imagenes[] = $this->container->getParameter('servidor').'/'.$foto->getWebPath();
            }

            $puntuacion = 0;
            if(count($establecimiento->getPuntuaciones())>0) {
                $total = 0;
                foreach ($establecimiento->getPuntuaciones() as $p) {
                    $total = $total + $p->getValor();
                }
                $puntuacion = $total / count($establecimiento->getPuntuaciones());
            }
            $puntuacionUsuario = $em->getRepository('AppBundle:Puntuacion')->findOneBy(array(
                'usuario' => $this->getUser(),
                'establecimiento' => $establecimiento
            ));

            if($puntuacionUsuario){
                $datos['puntuacionUsuario'] = $puntuacionUsuario->getValor();
            }else{
                $datos['puntuacionUsuario'] = null;
            }
            if($puntuacion < 3){
                $puntuacion = 3;
            }

            $datos['puntuacion'] = $puntuacion;
            $datos["articulos"] = [];

            $articulos = $em->getRepository('AppBundle:Articulo')->getArticulosEstablecimiento($establecimiento->getId());

            foreach ($articulos as  $a) {
                $articulo = array(
                    "id" => $a->getId(),
                    "nombre" => $a->getNombre(),
                    "precio" => $a->getPrecio(),
                    "unidades" => $a->getUnidadMedida(),
                    "valorUnidades" => $a->getValorMedida()
                    );
                if ($a->getImagen()) {
                    $articulo["imagen"] = $this->container->getParameter('servidor').'/'.$a->getWebPath();
                } else {
                   $articulo["imagen"] = "";
                }

                 $datos["articulos"][] = $articulo;
                
                
            }
            $datos["logo"] = $imagenes;

        } catch (\Exception $e) {
            return new JsonResponse(array(
                "estado" => 'error',
                //"mensaje" => 'Imposible acceder a la información del Usuario.'
                "mensaje" => $e->getMessage()
            ));
        }
        return new JsonResponse($datos);
    }

    /**
     * @Route("/recuperarContrasena", name="recuperarContrasena")
     * 
     */
    public function recuperarContrasenaAction(Request $peticion)
    {
        $correo=  ($peticion->request->get('correo'));
        $em=$this->getDoctrine()->getManager();
        $entity= $em->getRepository('AppBundle:Usuario')->findOneBy(array(
            'correo'=>$correo
        ));
        
        if ($entity){

            $str = rand(0, 9999999999);
            $clave = str_pad($str, 10, "0", STR_PAD_LEFT);

            $entity->setCodigoCambio($clave);
            $em->persist($entity);
            $em->flush();
            $link = $this->generateUrl('cambiarContrasenaWeb',array(
                'clave' => $clave,
                'correo' => $correo
            ));

            $mail=$this->get('correo');
            $mail->setVista('web/correo.html.twig')
                ->setPara(array($correo))
                ->setTitulo("Nueva Contraseña")
                ->setContenido("Para cambiar su contraseña por favor ingrese a este <a href='http://test.showcase.com.co".$link."'>link</a>");
            $mail->enviar();
            $rta = array(
                "estado"=> 'exito',
                "mensaje"=> 'Contraseña enviada su correo correctamente.'
            );
        }else{
            $rta=array(
                'estado'=>0,
                'mensaje'=> 'El correo no se encuentra en nuestra base de datos.'
            );
        }
        return new JsonResponse($rta);
    }



    /**
     * @Route("/hola", name="movilHola")
     */
    public function holaAction(Request $peticion){
        return new JsonResponse( array('valor' => 'hola' ));
    }

    /**
     * @Route("/categorias", name="categorias")
     */
    public function categoriasAction(Request $peticion){
       $rta = array();
       try {
           $em = $this->getDoctrine()->getManager();
           $categorias = $em->getRepository('AppBundle:Categoria')->findAll();
           $cats = array();
           foreach ($categorias as $categoria){
               array_push($cats,array('id' => $categoria->getId() ,'nombre' => $categoria->getNombre() ));
           }
           $rta=array(
                'estado'=>1,
                'mensaje'=> 'Categorias encontradas con exito.',
                'categorias' => $cats
            );

       } catch (Exception $e) {
           $rta=array(
                'estado'=>0,
                'mensaje'=> 'Error buscando las categorias.'
            );
       }

       return new JsonResponse( $rta);
       
    }

    /**
     * @Route("/categoria", name="categoria")
     */
    public function categoriaAction(Request $peticion){
       $rta = array();
       try {
           $em = $this->getDoctrine()->getManager();
           $id = $peticion->get('id');
           $categoria = $em->getRepository('AppBundle:Categoria')->find($id);
           $cat = array();
           $establecimientos = array();
           if($categoria){
                $establecimientos = $em->getRepository('AppBundle:Establecimiento')->findEstablecimientosCategoria($categoria->getId());
                $est = array();

                foreach ($establecimientos as $e) {
                    
                    $est[] = array(
                        'id' => $e->getId(),
                        'nombre' => $e->getNombre(),
                        'logo' => array($this->container->getParameter('servidor').$e->getWebPath())
                        );
                    
                }
                $cat = array('id' => $categoria->getId() ,'nombre' => $categoria->getNombre(),'establecimientos' => $est);
            }

           $rta=array(
                'estado'=>1,
                'mensaje'=> 'Categorias encontradas con exito.',
                'categorias' => $cat
            );

       } catch (Exception $e) {
           $rta=array(
                'estado'=>0,
                'mensaje'=> 'Error buscando las categorias.'
            );
       }

       return new JsonResponse( $rta);
       
    }


    /**
     * @Route("/direcciones", name="direcciones")
     */
    public function direccionesAction(Request $peticion){
       $rta = array();
       try {
           $em = $this->getDoctrine()->getManager();
           $id = $peticion->get('id');
           $usuario = $em->getRepository('AppBundle:Usuario')->find($id);
           $direcciones = array();
           if($usuario){
                foreach ($usuario->getDirecciones() as $direccion) {
                    array_push($direcciones, array(
                        'id' => $direccion->getId(),
                        'nombre' => $direccion->getNombre(),
                        'tipo'=> $direccion->getTipo(),
                        'numero' => $direccion->getNumero(),
                        'nomenclatura' => $direccion->getNomenclatura()
                    ));
                }
                $rta=array(
                    'estado'=>1,
                    'mensaje'=> 'Direcciones encontradas con exito.',
                    'direcciones' => $direcciones
                );
           }else{
                $rta=array(
                    'estado'=>0,
                    'mensaje'=> 'Usuario no encontrado.'
                );
           }
           
           

       } catch (Exception $e) {
           $rta=array(
                'estado'=>0,
                'mensaje'=> 'Error buscando las direcciones.'
            );
       }

       return new JsonResponse( $rta);
       
    }

    /**
     * @Route("/puntuarEstablecimiento", name="puntuarEstablecimiento")
     */
    public function puntuarEstablecimientoAction(Request $peticion){
        $rta = array(
            'estado'=>1,
            'mensaje' => 'Exito al puntuar establecimiento',
        );
        try {
            $em = $this->getDoctrine()->getManager();
            $id = $peticion->get('id');
            $valor = $peticion->get('valor');
            $establecimiento = $em->getRepository('AppBundle:Establecimiento')->find($id);
            if($establecimiento){
                $puntuacionUsuario = $em->getRepository('AppBundle:Puntuacion')->findOneBy(array(
                    'usuario' => $this->getUser(),
                    'establecimiento' => $establecimiento
                ));
                if($puntuacionUsuario){
                    $puntuacionUsuario->setValor($valor);
                }else{
                    $puntuacionUsuario = new Puntuacion();
                    $puntuacionUsuario->setEstablecimiento($establecimiento);
                    $puntuacionUsuario->setUsuario($this->getUser());
                    $puntuacionUsuario->setValor($valor);
                }
                $em->persist($puntuacionUsuario);
                $em->flush();
                $puntuacion = 0;
                if(count($establecimiento->getPuntuaciones())>0) {
                    $total = 0;
                    foreach ($establecimiento->getPuntuaciones() as $p) {
                        $total = $total + $p->getValor();
                    }
                    $puntuacion = $total / count($establecimiento->getPuntuaciones());
                }
                $rta['puntuacionUsuario'] = $puntuacionUsuario->getValor();

                if($puntuacion < 3){
                    $puntuacion = 3;
                }
                $rta['puntuacion'] = $puntuacion;
            }else{
                $rta=array(
                    'estado'=>0,
                    'mensaje'=> 'Establecimiento no encontrado.'
                );
            }
        } catch (Exception $e) {
            $rta=array(
                'estado'=>0,
                'mensaje'=> 'Error al calificar el establecimiento.'
            );
        }
        return new JsonResponse( $rta);
    }

    /**
     * @Route("/autoCompletarBusqueda", name="autoCompletarBusqueda")
     */
    public function autoCompletarBusquedaAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $palabra = $peticion->get('palabra');
        $rta = array(
            'estado'=>1,
            'mensaje' => 'Exito al buscar posibles palabras',
        );
        try{
            $palabras = $em->getRepository('AppBundle:Articulo')->autocompletar(strtolower($palabra));

            $posiblesPalabras = array();
            foreach ($palabras as $p){
                $listaPalabras = explode(" ",$p['nombre']);
                foreach ($listaPalabras as $lp){
                    $bandera = strpos($lp,$palabra);
                    if(gettype($bandera) == "integer"){

                        array_push($posiblesPalabras,$lp);
                    }

                }
                $nuevaPalabra = $listaPalabras[0];
                foreach ( array_slice($listaPalabras,1) as $lp){
                    $nuevaPalabra = $nuevaPalabra." ".$lp;
                    if(strpos($lp,$palabra)){
                        array_push($posiblesPalabras,$nuevaPalabra);
                    }
                }
            }
            $posiblesPalabras = array_unique($posiblesPalabras);
            if(count($posiblesPalabras) > 0){
                $rta['palabras'] = $posiblesPalabras;
            }else{
                $rta=array(
                    'estado'=>0,
                    'mensaje'=> 'No hay posibles palabras'
                );
            }

        }catch (Exception $e){
            $rta=array(
                'estado'=>0,
                'mensaje'=> 'Error al buscar posibles palabras'
            );
        }
        return new JsonResponse( $rta);

    }

    /**
     * @Route("/realizarBusqueda", name="realizarBusqueda")
     */
    public function realizarBusquedaAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $palabra = $peticion->get('palabra');
        $rta = array(
            'estado'=>1,
            'mensaje' => 'Exito al buscar posibles palabras',
        );
        try{
            $articulos = $em->getRepository('AppBundle:Articulo')->realizarBusqueda($palabra);
            $art = array();
            foreach ($articulos as $a){
                $articulo = array(
                    "id" => $a->getId(),
                    "nombre" => $a->getNombre(),
                    "precio" => $a->getPrecio(),
                    "unidades" => $a->getUnidadMedida(),
                    "valorUnidades" => $a->getValorMedida()
                );
                if ($a->getImagen()) {
                    $articulo["imagen"] = $this->container->getParameter('servidor').'/'.$a->getWebPath();
                } else {
                    $articulo["imagen"] = "";
                }
                array_push($art,$articulo);
            }
            $rta['articulos'] = $art;
        }catch (Exception $e){
            $rta=array(
                'estado'=>0,
                'mensaje'=> 'Error al buscar posibles palabras'
            );
        }
        return new JsonResponse( $rta);

    }

    /**
     * @Route("/detalleProducto", name="detalleProducto")
     */
    public function detalleProductoAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $id = $peticion->get('id');
        $rta = array(
            'estado'=>1,
            'mensaje' => 'Exito al buscar el articulo',
        );
        try{
            $a = $em->getRepository('AppBundle:Articulo')->find($id);
            $articulo = array(
                "id" => $a->getId(),
                "descripcion" => $a->getDescripcion(),
                "nombre" => $a->getNombre(),
                "precio" => $a->getPrecio(),
                "unidades" => $a->getUnidadMedida(),
                "valorUnidades" => $a->getValorMedida(),
                'cantidad' => $a->getCantidad()
            );
            if ($a->getImagen()) {
                $articulo["imagen"] = array($this->container->getParameter('servidor').'/'.$a->getWebPath());
            } else {
                $articulo["imagen"] = "";
            }

            $rta['articulo'] = $articulo;
        }catch (Exception $e){
            $rta=array(
                'estado'=>0,
                'mensaje'=> 'Error al buscar el articulo'
            );
        }
        return new JsonResponse( $rta);

    }

    /**
     * @Route("/agregarDireccion", name="agregarDireccion")
     */
    public function agregarDireccionAction(Request $peticion)
    {
        $em = $this->getDoctrine()->getManager();
        $nombre = $peticion->get('nombre');
        $barrio = $peticion->get('barrio');
        $ciudad = $peticion->get('ciudad');
        $tipo = $peticion->get('tipo');
        $numero = $peticion->get('numero');

        $nomenclatura = $peticion->get('nomenclatura');
        $informacionAdicional = $peticion->get('informacionAdicional');
        $rta=array(
            'estado'=>1,
            'mensaje'=> 'Exito al agregar la direccion'
        );
        $direcciones = array();
        try{
            $direccion = new Direccion();
            $direccion->setUsuario($this->getUser());
            $direccion->setNombre($nombre);
            $direccion->setBarrio($barrio);
            $objCiudad = $em->getRepository('AppBundle:Ciudad')->findOneBy(array('nombre' => strtoupper($ciudad)));

            $direccion->setCiudad($objCiudad);
            $direccion->setTipo($tipo);
            $direccion->setNumero($numero);
            $direccion->setNomenclatura($nomenclatura);

            $direccion->setInformacionAdicional($informacionAdicional);
            $em->persist($direccion);
            $em->flush();
            foreach ($this->getUser()->getDirecciones() as $d) {
                array_push($direcciones, array(
                    'id' => $d->getId(),
                    'nombre' => $d->getNombre(),
                    'tipo'=> $d->getTipo(),
                    'numero' => $d->getNumero(),
                    'nomenclatura' => $d->getNomenclatura()
                ));
            }
            $rta['direcciones'] = $direcciones;
        }catch (Exception $e){
            $rta=array(
                'estado'=>0,
                'mensaje'=> 'Error al agregar la direccion'
            );
        }
        return new JsonResponse( $rta);

    }

    /**
     * @Route("/obtenerCiudades", name="obtenerCiudades")
     */
    public function obtenerCiudadesAction(Request $peticion)
    {
        $em = $this->getDoctrine()->getManager();

        $rta=array(
            'estado'=>1,
            'mensaje'=> 'Exito al obtener las ciudades'
        );

        try{
            $ciudades = $em->getRepository('AppBundle:Ciudad')->findAll();
            $arrayCiudades = array();
            foreach ($ciudades as $c){
                array_push($arrayCiudades, array(
                   'id' => $c->getId(),
                    'nombre' => $c->getNombre()
                ));
            }
            $rta['ciudades'] = $arrayCiudades;
        }catch (Exception $e){
            $rta=array(
                'estado'=>0,
                'mensaje'=> 'Error al obtener las ciudades'
            );
        }
        return new JsonResponse( $rta);

    }

    /**
     * @Route("/pedidos", name="pedidos")
     */
    public function pedidosAction(Request $peticion)
    {
        $em = $this->getDoctrine()->getManager();
        $rta=array(
            'estado'=>1,
            'mensaje'=> 'Exito al obtener los pedidos del usuario'
        );

        try{
            $pedidos = $em->getRepository('AppBundle:Pedido')->listarPedidos($this->getUser());

            $arrayPedidos = array();
            foreach ($pedidos as $p){
                array_push($arrayPedidos, array(
                    'id' => $p->getId(),
                    'fechaCreacion' => $p->getFechaCreacion()->format('j'). " de ".$p->getFechaCreacion()->format('F')." de ".$p->getFechaCreacion()->format('Y'),
                    'estado' => $this->obtenerEstado($p->getEstado())
                ));
            }
            $rta['pedidos'] = $arrayPedidos;
        }catch (Exception $e){
            $rta=array(
                'estado'=>0,
                'mensaje'=> 'Error al obtener los pedidos'
            );
        }
        return new JsonResponse( $rta);

    }

    /**
     * @Route("/zonas", name="zonas")
     */
    public function zonasAction(Request $peticion)
    {
        $em = $this->getDoctrine()->getManager();
        $rta=array(
            'estado'=>1,
            'mensaje'=> 'Exito al obtener las zonas'
        );

        try{
            $zonas = $em->getRepository('AppBundle:Zona')->findAll();

            $arrayZonas = array();
            foreach ($zonas as $z){
                array_push($arrayZonas, array(
                    'id' => $z->getId(),
                    'nombre' => $z->getNombre()
                ));
            }
            $rta['zonas'] = $arrayZonas;
        }catch (Exception $e){
            $rta=array(
                'estado'=>0,
                'mensaje'=> 'Error al obtener las zonas'
            );
        }
        return new JsonResponse( $rta);

    }

    /**
     * @Route("/categoriasLocalizacion", name="categoriasLocalizacion")
     */
    public function categoriasLocalizacionAction(Request $peticion){
        $em = $this->getDoctrine()->getManager();
        $filtro = $peticion->get('filtro');
        $datos =array("estado" => 'exito',
            "mensaje" => "Establecimientos obtenidos exitosamente",
            "categorias" => array(),
            "totalPaginas" => 0,
            "pagina" => 0
        );
        $localizacion = array(
            "type" => "FeatureCollection"
        );
        $features = array();
        try{
            $marcador = 1;
            if($filtro){
                $cats = $em->getRepository('AppBundle:Categoria')->findByFiltro($filtro);
            }else {
                $cats = $em->getRepository('AppBundle:Categoria')->findAll();
            }
            foreach ($cats as  $c) {
                $cat = array("id" => $c->getId(),"nombre" => $c->getNombre());
                $establecimientos = $em->getRepository('AppBundle:Establecimiento')->findEstablecimientosCategoria($c->getId());
                $est = array();

                foreach ($establecimientos as $e) {

                    $est[] = array(
                        'id' => $e->getId(),
                        'nombre' => $e->getNombre(),
                        'logo' => array($this->container->getParameter('servidor').$e->getWebPath()),
                        'marcador' => $marcador
                    );

                    $coordenadas = explode(",",$e->getLocalizacion());
                    $features[] = array(
                        "type" => "Feature",
                        "properties" => array(
                                        "marker-color" => "#f5a623",
                                        "marker-size" => "small",
                                        "marker-symbol" => $marcador
                                    ),
                        "geometry" => array(
                                        "type" => "Point",
                                        "coordinates" => array(
                                                        doubleval($coordenadas[1]),
                                                        doubleval($coordenadas[0])
                                                    )
                                    )

                    );
                    $marcador++;
                }
                $cat['establecimientos']=$est;

                $datos["categorias"][] = $cat;
            }
            $localizacion['features'] = $features;
            $datos['localizacion'] = $localizacion;


        } catch (\Exception $e) {
            return new JsonResponse(array(
                "estado" => 'error',
                "mensaje" => $e->getMessage()
            ));
        }
        return new JsonResponse($datos);

    }

    /**
     * @Route("/verCarrito", name="verCarrito")
     */
    public function verCarritoAction(Request $peticion)
    {
        $em = $this->getDoctrine()->getManager();
        $rta=array(
            'estado'=>1,
            'mensaje'=> 'Exito al obtener el carrito'
        );

        try{
            $infoApp = $em->getRepository('AppBundle:InformacionApp')->find(1);
            $usuario = $this->getUser();
            $carritos = $usuario->getCarritos();
            if(count($carritos) == 0){
                $carrito = new Carrito();
                $carrito->setFechaCreacion(new \DateTime());
                $carrito->setUsuario($usuario);
                $em->persist($carrito);
                $em->flush();
            }else{
                $carrito = $carritos[0];
            }
            $carro = array(
                'id' => $carrito->getId(),
                'fechaCreacion' => $carrito->getFechaCreacion()->format('Y-m-d H:i:s')
            );
            $items = array();
            $subtotal = 0;
            foreach ($carrito->getItems() as $item){
                $subtotal = $subtotal + $item->getCantidad() * $item->getArticulo()->getPrecio();
                $items[] = array(
                    'id' => $item->getId(),
                    'idArticulo' => $item->getArticulo()->getId(),
                    'nombre' => $item->getArticulo()->getNombre(),
                    'imagen' => $this->container->getParameter('servidor').'/'.$item->getArticulo()->getWebPath(),
                    'precio' => $item->getArticulo()->getPrecio(),
                    'cantidad' => $item->getCantidad()
                );
            }
            $carro['subtotal'] = $subtotal;
            $domicilio = $infoApp->getPrecioDomicilio();
            $carro['domicilio'] = $domicilio;
            $carro['total'] = $subtotal + $domicilio;
            $carro['items'] = $items;

            $rta['carrito'] = $carro;
        }catch (Exception $e){
            $rta=array(
                'estado'=>0,
                'mensaje'=> 'Error al obtener el carrito'
            );
        }
        return new JsonResponse( $rta);

    }

    /**
     * @Route("/agregarProductoCarrito", name="agregarProductoCarrito")
     */
    public function agregarProductoCarritoAction(Request $peticion)
    {
        $em = $this->getDoctrine()->getManager();
        $idArticulo = $peticion->get('idArticulo');
        $cantidad = $peticion->get('cantidad');
        $rta=array(
            'estado'=>1,
            'mensaje'=> 'Exito al agregar el producto al carrito'
        );

        try{
            $articulo = $em->getRepository('AppBundle:Articulo')->find($idArticulo);
            if($articulo->getCantidad() < $cantidad){
                return new JsonResponse(array(
                    'estado'=>0,
                    'mensaje'=> 'No se puede reservar esta cantidad para este articulo, cantidad disponible: '.$articulo->getCantidad()
                ));
            }
            $articulo->setCantidad($articulo->getCantidad() - $cantidad );
            $em->persist($articulo);
            $usuario = $this->getUser();
            $carritos = $usuario->getCarritos();
            if(count($carritos) == 0){
                $carrito = new Carrito();
                $carrito->setFechaCreacion(new \DateTime());
                $carrito->setUsuario($usuario);
                $em->persist($carrito);

            }else{
                $carrito = $carritos[0];
            }
            $item = new Item();
            $item->setCantidad($cantidad);
            $item->setCarrito($carrito);
            $item->setArticulo($articulo);
            $em->persist($item);
            $em->flush();
            $carro = array(
                'id' => $carrito->getId(),
                'fechaCreacion' => $carrito->getFechaCreacion()->format('Y-m-d H:i:s')
            );
            $rta['carrito'] = $carro;
        }catch (Exception $e){
            $rta=array(
                'estado'=>0,
                'mensaje'=> 'Error al agregar el producto al carrito'
            );
        }
        return new JsonResponse( $rta);

    }


    /**
     * @Route("/eliminarProductoCarrito", name="eliminarProductoCarrito")
     */
    public function eliminarProductoCarritoAction(Request $peticion)
    {
        $em = $this->getDoctrine()->getManager();
        $idItem = $peticion->get('idItem');

        $rta=array(
            'estado'=>1,
            'mensaje'=> 'Exito al eliminar el producto del carrito'
        );

        try{
            $item = $em->getRepository('AppBundle:Item')->find($idItem);
            $articulo = $item->getArticulo();
            $articulo->setCantidad($articulo->getCantidad() + $item->getCantidad() );


            $usuario = $this->getUser();
            $carritos = $usuario->getCarritos();
            if(count($carritos) == 0){
                $carrito = new Carrito();
                $carrito->setFechaCreacion(new \DateTime());
                $carrito->setUsuario($usuario);


            }else{
                $carrito = $carritos[0];
            }

            $carrito->removeItem($item);
            $em->persist($carrito);
            $em->persist($articulo);
            $em->remove($item);
            $em->flush();
            $carro = array(
                'id' => $carrito->getId(),
                'fechaCreacion' => $carrito->getFechaCreacion()->format('Y-m-d H:i:s')
            );
            $rta['carrito'] = $carro;
        }catch (Exception $e){
            $rta=array(
                'estado'=>0,
                'mensaje'=> 'Error al eliminar el articulo del carrito'
            );
        }
        return new JsonResponse( $rta);

    }

    /**
     * @Route("/editarProductoCarrito", name="editarProductoCarrito")
     */
    public function editarProductoCarritoAction(Request $peticion)
    {
        $em = $this->getDoctrine()->getManager();
        $idItem = $peticion->get('idItem');
        $cantidad = $peticion->get('cantidad');
        $rta=array(
            'estado'=>1,
            'mensaje'=> 'Exito al editar el producto del carrito'
        );

        try{
            $item = $em->getRepository('AppBundle:Item')->find($idItem);
            $articulo = $item->getArticulo();
            if($articulo->getCantidad() + $item->getCantidad() < $cantidad){
                return new JsonResponse(array(
                    'estado'=>0,
                    'mensaje'=> 'No se puede reservar esta cantidad para este articulo, cantidad disponible: '.($articulo->getCantidad() + $item->getCantidad())
                ));
            }

            $cantidadArticulo = ($articulo->getCantidad() + $item->getCantidad()) - $cantidad;
            $articulo->setCantidad(($articulo->getCantidad() + $item->getCantidad()) - $cantidad );
            $item->setCantidad($cantidad);

            $usuario = $this->getUser();
            $carritos = $usuario->getCarritos();
            if(count($carritos) == 0){
                $carrito = new Carrito();
                $carrito->setFechaCreacion(new \DateTime());
                $carrito->setUsuario($usuario);


            }else{
                $carrito = $carritos[0];
            }

            $em->persist($articulo);
            $em->persist($item);
            $em->flush();
            $carro = array(
                'id' => $carrito->getId(),
                'fechaCreacion' => $carrito->getFechaCreacion()->format('Y-m-d H:i:s')
            );
            $rta['carrito'] = $carro;
        }catch (Exception $e){
            $rta=array(
                'estado'=>0,
                'mensaje'=> 'Error al editar el articulo del carrito'
            );
        }
        return new JsonResponse( $rta);

    }

    /**
     * @Route("/cancelarCarrito", name="cancelarCarrito")
     */
    public function cancelarCarritoAction(Request $peticion)
    {
        $em = $this->getDoctrine()->getManager();

        $rta=array(
            'estado'=>1,
            'mensaje'=> 'Exito al cancelar el pedido'
        );

        try{
            $usuario = $this->getUser();
            $carritos = $usuario->getCarritos();
            if(count($carritos) == 0){
                $carrito = new Carrito();
                $carrito->setFechaCreacion(new \DateTime());
                $carrito->setUsuario($usuario);


            }else{
                $carrito = $carritos[0];
            }
            foreach($carrito->getItems() as $item){
                $em->remove($item);
            }

            $em->remove($carrito);
            $em->flush();

        }catch (Exception $e){
            $rta=array(
                'estado'=>0,
                'mensaje'=> 'Error al cancelar el pedido'
            );
        }
        return new JsonResponse( $rta);

    }

    /**
     * @Route("/redimirCupon", name="redimirCupon")
     */
    public function redimirCuponAction(Request $peticion)
    {
        $em = $this->getDoctrine()->getManager();
        $codigo = $peticion->get('codigo');
        $rta=array(
            'estado'=>1,
            'mensaje'=> 'Cupon Valido'
        );

        try{
            $usuario = $this->getUser();
            $cupon = $em->getRepository('AppBundle:Cupon')->buscarCupon($codigo);
            if($cupon){
                $validar = $em->getRepository('AppBundle:Cupon')->validarCupon($cupon,$usuario);
                if(!$validar) {
                    $cuponUsuario = new CuponUsuario();
                    $cuponUsuario->setCupon($cupon);
                    $cuponUsuario->setUsuario($usuario);
                    $em->persist($cuponUsuario);
                    $em->flush();
                    $rta['id'] = $cupon->getId();
                    $rta['valor'] = $cupon->getValor();
                }else{
                    $rta=array(
                        'estado'=>0,
                        'mensaje'=> 'Este cupon ya fue redimido'
                    );
                }
            }else{
                $rta=array(
                    'estado'=>0,
                    'mensaje'=> 'Cupon No Valido'
                );
            }
        }catch (Exception $e){
            $rta=array(
                'estado'=>0,
                'mensaje'=> 'Error al validar el cupon'
            );
        }
        return new JsonResponse( $rta);

    }

    /**
     * @Route("/pedidosActivos", name="pedidosActivos")
     */
    public function pedidosActivosAction(Request $peticion)
    {
        $em = $this->getDoctrine()->getManager();
        $rta=array(
            'estado'=>1,
            'mensaje'=> 'Exito al obtener los pedidos del usuario'
        );

        try{
            $pedidos = $em->getRepository('AppBundle:Pedido')->listarPedidosActivos($this->getUser());

            $arrayPedidos = array();
            foreach ($pedidos as $p){
                array_push($arrayPedidos, array(
                    'id' => $p->getId(),
                    'fechaCreacion' => $p->getFechaCreacion()->format('j'). " de ".$p->getFechaCreacion()->format('F')." de ".$p->getFechaCreacion()->format('Y'),
                    'estado' => $this->obtenerEstado($p->getEstado())
                ));
            }
            $rta['pedidos'] = $arrayPedidos;
        }catch (Exception $e){
            $rta=array(
                'estado'=>0,
                'mensaje'=> 'Error al obtener los pedidos'
            );
        }
        return new JsonResponse( $rta);

    }

    public function obtenerEstado($estado){
        $string = "";
        switch ($estado){
            case 'En Progreso':
                $string = "<font color=#f5a623>Procesando pedido</font>";
                break;
            case 'Preparando':
                $string = "<font color=#7ed321>En Proceso de Entrega </font>";
                break;
            case 'Cancelado':
                $string = "<font color=#f00f0f>Cancelado</font>";
                break;
            case 'Entregado' :
                $string = "<font color=#7ed321>Entregado satisfactoriamente</font>";
                break;

        }
        return $string;
    }

    /**
     * @Route("/detallePedido", name="detallePedido")
     */
    public function detallePedidoAction(Request $peticion)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $peticion->get('id');
        $rta=array(
            'estado'=>1,
            'mensaje'=> 'Exito al obtener el detalle del pedido'
        );

        try{
            $pedidoUsuario = $em->getRepository('AppBundle:Pedido')->find($id);
            if($pedidoUsuario) {
                $pedido = array(
                    'id' => $pedidoUsuario->getId(),
                    'estado' => $pedidoUsuario->getEstado(),
                    'direccion' => $pedidoUsuario->getDireccion(),
                    'telefono' => $pedidoUsuario->getTelefonoContacto(),
                    'metodoPago' => $pedidoUsuario->getMetodoPago(),

                    'nombres' => $pedidoUsuario->getUsuario()->getNombres(),
                    'apellidos' => $pedidoUsuario->getUsuario()->getApellidos(),

                );
                $items = array();
                $subtotal = 0;
                foreach ($pedidoUsuario->getArticulosPedidos() as $item) {

                    $subtotal = $subtotal + $item->getCantidad() * $item->getPrecio();
                    $items[] = array(
                        'nombre' => $item->getArticulo()->getNombre(),
                        'imagen' => $this->container->getParameter('servidor') . '/' . $item->getArticulo()->getWebPath(),
                        'precio' => $item->getPrecio(),
                        'cantidad' => $item->getCantidad()
                    );
                }
                $descuento = 0;
                if($pedidoUsuario->getCupon()){
                    $descuento = $pedidoUsuario->getCupon()->getValor();
                    $pedido['cupon'] = $pedidoUsuario->getCupon()->getCodigo() . " - $" . $descuento;
                }else{
                    $pedido['cupon'] = 'Sin cupón';
                }
                $pedido['subtotal'] = $subtotal - $descuento;
                $domicilio = $pedidoUsuario->getValorDomicilio();
                $pedido['domicilio'] = $domicilio;
                $pedido['total'] = $subtotal + $domicilio;
                $pedido['items'] = $items;

                $rta['pedido'] = $pedido;
            }else{
                $rta=array(
                    'estado'=>0,
                    'mensaje'=> 'No existe el pedido'
                );
            }
        }catch (Exception $e){
            $rta=array(
                'estado'=>0,
                'mensaje'=> 'Error al obtener el detalle del pedido'
            );
        }
        return new JsonResponse( $rta);

    }

    /**
     * @Route("/realizarPedido", name="realizarPedido")
     */
    public function realizarPedidoAction(Request $peticion)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $peticion->get('datosPedido');
        $datosPedido = json_decode($id);

        $rta=array(
            'estado'=>1,
            'mensaje'=> 'Exito al realizar el pedido',

        );

        try{

            $pedido = new Pedido();
            $direccion = $em->getRepository('AppBundle:Direccion')->find($datosPedido->direccion);
            $cupon = null;

            if(isset($datosPedido->cupon)) {
                $cupon = $em->getRepository('AppBundle:Cupon')->buscarCupon($datosPedido->cupon);
            }
            $infoApp = $em->getRepository('AppBundle:InformacionApp')->find(1);

            $strDireccion = $direccion->getTipo()." ".$direccion->getNumero()." # ".$direccion->getNomenclatura();

            $pedido->setDireccion($strDireccion);
            $pedido->setInformacionAdicional($direccion->getInformacionAdicional());
            $pedido->setTelefonoContacto($datosPedido->telefono);
            $pedido->setMetodoPago($datosPedido->formaPago);
            $pedido->setUsuario($this->getUser());
            $pedido->setFechaCreacion(new \DateTime());
            $pedido->setEstado('En Progreso');
            if($cupon) {
                $pedido->setCupon($cupon);
            }
            $pedido->setValorDomicilio($infoApp->getPrecioDomicilio());
            foreach ($datosPedido->items as $item ){
                $articuloPedido = new ArticulosPedido();
                $articulo = $em->getRepository('AppBundle:Articulo')->find($item->id);
                $articuloPedido->setArticulo($articulo);
                $articuloPedido->setPrecio($articulo->getPrecio());
                $articuloPedido->setCantidad($item->cantidad);
                $articuloPedido->setPedido($pedido);
                $pedido->addArticulosPedido($articuloPedido);
                $em->persist($articulo);
                $em->persist($articuloPedido);
            }
            $em->persist($pedido);
            $em->flush();
            $rta['ordenPedido'] = $pedido->getId();

        }catch (Exception $e){
            $rta=array(
                'estado'=>0,
                'mensaje'=> 'Error al realizar el pedido'
            );
        }
        return new JsonResponse( $rta);

    }






}
