<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Contactenos
 * 
 * @ORM\Table(name="contactenos", options={"comment" = "Se almacena las solicitudes de contactenos de la aplicacion"})
 * @ORM\Entity(repositoryClass="ContactenosRepository")
 *
 * @author ALEJANDRO
 */
class Contactenos {
    
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $nombreCompleto
     *
     * @ORM\Column(name="nombreCompleto", type="string", length=120, nullable=false, options=
     * {"comment" = "Nombre de quien envio la solicitud de contactenos"})
     */
    private $nombreCompleto;

    /**
     * @var string $correo
     *
     * @ORM\Column(name="correo", type="string", length=120, nullable=false, options=
     * {"comment" = "Correo de quien envio la solicitud de contactenos"})
     */
    private $correo;

    /**
     * @var string $asunto
     *
     * @ORM\Column(name="asunto", type="string", length=31, nullable=false, options=
     * {"comment" = "Asunto la solicitud de contactenos"})
     */
    private $asunto;

    /**
     * @var string $comentario
     *
     * @ORM\Column(name="comentario", type="string", length=255, nullable=false, options=
     * {"comment" = "Comentario de la solicitud de contactenos"})
     */
    private $comentario;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime",nullable=true, options=
     * {"comment" = "fecha en que se envio la  solicitud"})
     */
    private $fecha;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombreCompleto
     *
     * @param string $nombreCompleto
     * @return Contactenos
     */
    public function setNombreCompleto($nombreCompleto)
    {
        $this->nombreCompleto = $nombreCompleto;

        return $this;
    }

    /**
     * Get nombreCompleto
     *
     * @return string 
     */
    public function getNombreCompleto()
    {
        return $this->nombreCompleto;
    }

    /**
     * Set correo
     *
     * @param string $correo
     * @return Contactenos
     */
    public function setCorreo($correo)
    {
        $this->correo = $correo;

        return $this;
    }

    /**
     * Get correo
     *
     * @return string 
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    

    /**
     * Set comentario
     *
     * @param string $comentario
     * @return Contactenos
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;

        return $this;
    }

    /**
     * Get comentario
     *
     * @return string 
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Contactenos
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set asunto
     *
     * @param string $asunto
     *
     * @return Contactenos
     */
    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;

        return $this;
    }

    /**
     * Get asunto
     *
     * @return string
     */
    public function getAsunto()
    {
        return $this->asunto;
    }
}
