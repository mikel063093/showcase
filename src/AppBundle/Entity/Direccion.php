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
     * @var string $direccion
     *
     * @ORM\Column(name="direccion", type="string", length=120, nullable=false, options=
     * {"comment" = "direccion fisica"})
     */
    private $direccion;

    /**
     * @var string $barrio
     *
     * @ORM\Column(name="barrio", type="string", length=120, nullable=false, options=
     * {"comment" = "Barrio en la que esta la direccion"})
     */
    private $barrio;

    /**
     * @var string $nombreCompleto
     *
     * @ORM\Column(name="nombreCompleto", type="string", length=120, nullable=false, options=
     * {"comment" = "Nombre de quien envio la solicitud de contactenos"})
     */
    private $ciudad;

    /**
     * 
     * @ORM\OneToMany(targetEntity="Pedido", mappedBy="direccion")
     */
    private $pedidos;

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
        $this->pedidos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set direccion
     *
     * @param string $direccion
     * @return Direccion
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set barrio
     *
     * @param string $barrio
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
     * @param string $ciudad
     * @return Direccion
     */
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Get ciudad
     *
     * @return string 
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Add pedidos
     *
     * @param \AppBundle\Entity\Pedido $pedidos
     * @return Direccion
     */
    public function addPedido(\AppBundle\Entity\Pedido $pedidos)
    {
        $this->pedidos[] = $pedidos;

        return $this;
    }

    /**
     * Remove pedidos
     *
     * @param \AppBundle\Entity\Pedido $pedidos
     */
    public function removePedido(\AppBundle\Entity\Pedido $pedidos)
    {
        $this->pedidos->removeElement($pedidos);
    }

    /**
     * Get pedidos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPedidos()
    {
        return $this->pedidos;
    }

    /**
     * Set usuario
     *
     * @param \AppBundle\Entity\Usuario $usuario
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
