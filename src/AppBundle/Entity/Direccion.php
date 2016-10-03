<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
/**
 * Direccion
 *
 * @ORM\Table(name="direccion", options={"comment" = "las direcciones de los usuario del sistema"})
 * @ORM\Entity(repositoryClass="DireccionRepository") 
 * 
 * @author ALEJANDRO
 */
class Direccion {
    
	/**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string", length=120, nullable=false, options=
     * {"comment" = "Nombre de la direccion"})
     */
    private $nombre;

    /**
     * @var string $tipo
     *
     * @ORM\Column(name="tipo", type="string", length=60, nullable=false, options=
     * {"comment" = "tipo de localizacion de la direccion (Calle, carrera)"})
     */
    private $tipo;

    /**
     * @var string $numero
     *
     * @ORM\Column(name="numero", type="string", length=60, nullable=false, options=
     * {"comment" = "el numero de localizacion de la direccion"})
     */
    private $numero;

    /**
     * @var string $nomenclatura
     *
     * @ORM\Column(name="nomenclatura", type="string", length=60, nullable=false, options=
     * {"comment" = "la nomenclatura de la casa"})
     */
    private $nomenclatura;



    /**
     * @var string $informacionAdicional
     *
     * @ORM\Column(name="informacionAdicional", type="string", length=120, nullable=true, options=
     * {"comment" = "informacion adicional sobre la direccion"})
     */
    private $informacionAdicional;

    /**
     * @var string $barrio
     *
     * @ORM\Column(name="barrio", type="string", length=120, nullable=false, options=
     * {"comment" = "Barrio en la que esta la direccion"})
     */
    private $barrio;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Ciudad", inversedBy="direcciones")
     * @ORM\JoinColumn(name="id_ciudad", referencedColumnName="id")
     **/
    private $ciudad;



    /**
     * 
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="direcciones")
     * @ORM\JoinColumn(name="id_usuario", referencedColumnName="id")
     **/
    private $usuario;

    /**
     * Constructor
     */
    public function __construct()
    {

    }

    

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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Direccion
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return Direccion
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set numero
     *
     * @param string $numero
     *
     * @return Direccion
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set nomenclatura
     *
     * @param string $nomenclatura
     *
     * @return Direccion
     */
    public function setNomenclatura($nomenclatura)
    {
        $this->nomenclatura = $nomenclatura;

        return $this;
    }

    /**
     * Get nomenclatura
     *
     * @return string
     */
    public function getNomenclatura()
    {
        return $this->nomenclatura;
    }

    /**
     * Set informacionAdicional
     *
     * @param string $informacionAdicional
     *
     * @return Direccion
     */
    public function setInformacionAdicional($informacionAdicional)
    {
        $this->informacionAdicional = $informacionAdicional;

        return $this;
    }

    /**
     * Get informacionAdicional
     *
     * @return string
     */
    public function getInformacionAdicional()
    {
        return $this->informacionAdicional;
    }

    /**
     * Set barrio
     *
     * @param string $barrio
     *
     * @return Direccion
     */
    public function setBarrio($barrio)
    {
        $this->barrio = $barrio;

        return $this;
    }

    /**
     * Get barrio
     *
     * @return string
     */
    public function getBarrio()
    {
        return $this->barrio;
    }

    /**
     * Set ciudad
     *
     * @param \AppBundle\Entity\Ciudad $ciudad
     *
     * @return Direccion
     */
    public function setCiudad(\AppBundle\Entity\Ciudad $ciudad = null)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Get ciudad
     *
     * @return \AppBundle\Entity\Ciudad
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }



    /**
     * Set usuario
     *
     * @param \AppBundle\Entity\Usuario $usuario
     *
     * @return Direccion
     */
    public function setUsuario(\AppBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \AppBundle\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }


}
