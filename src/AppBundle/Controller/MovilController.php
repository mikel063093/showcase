<?php

namespace AppBundle\Controller;
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
        
        $datos = array( "id"=> '0',"nombre"=> "","apellido"=>"","correo"=> "",
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
                //var_dump($response);
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
        $datos = array( "id"=> '0',"nombre"=> '',"apellido"=>"","correo"=> '',
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
        $datos = array( "id"=> '0',"nombre"=> "","apellido"=>"","correo"=> "",
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
     * @Route("/hola", name="movilHola")
     */
    public function holaAction(Request $peticion){
        return new JsonResponse( array('valor' => 'hola' ));
    }
    
    
}
