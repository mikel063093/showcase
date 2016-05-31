<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Pedido
 *
 * @ORM\Table(name="pedido", options={"comment" = "Los pedidos hechos por un usuario"})
 * @ORM\Entity(repositoryClass="PedidoRepository") 
 *
 * @author ALEJANDRO
 */
class Pedido {
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var datetime $fechaCreacion
     *
     * @ORM\Column(name="fechaCreacion", type="datetime", nullable=true)
     */
    private $fechaCreacion;

    /**
     * @var string $estado
     *
     * @ORM\Column(name="estado", type="string", length=120, nullable=false, options=
     * {"comment" = "estado por el cual se identifica la entapa en la que se encuentra un pedido"})
     */
    private $estado;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Cupon", inversedBy="pedidos")
     * @ORM\JoinColumn(name="id_cupon", referencedColumnName="id")
     **/
    private $cupon;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Direccion", inversedBy="pedidos")
     * @ORM\JoinColumn(name="id_direccion", referencedColumnName="id")
     **/
    private $direccion;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="pedidos")
     * @ORM\JoinColumn(name="id_usuario", referencedColumnName="id")
     **/
    private $usuario;

    /**
     * 
     * @ORM\OneToMany(targetEntity="ArticulosPedido", mappedBy="pedido")
     */
    private $articulosPedidos;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articulosPedidos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return Pedido
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime 
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return Pedido
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set cupon
     *
     * @param \AppBundle\Entity\Cupon $cupon
     * @return Pedido
     */
    public function setCupon(\AppBundle\Entity\Cupon $cupon = null)
    {
        $this->cupon = $cupon;

        return $this;
    }

    /**
     * Get cupon
     *
     * @return \AppBundle\Entity\Cupon 
     */
    public function getCupon()
    {
        return $this->cupon;
    }

    /**
     * Set direccion
     *
     * @param \AppBundle\Entity\Direccion $direccion
     * @return Pedido
     */
    public function setDireccion(\AppBundle\Entity\Direccion $direccion = null)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return \AppBundle\Entity\Direccion 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set usuario
     *
     * @param \AppBundle\Entity\Usuario $usuario
     * @return Pedido
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

    /**
     * Add articulosPedidos
     *
     * @param \AppBundle\Entity\ArticulosPedido $articulosPedidos
     * @return Pedido
     */
    public function addArticulosPedido(\AppBundle\Entity\ArticulosPedido $articulosPedidos)
    {
        $this->articulosPedidos[] = $articulosPedidos;

        return $this;
    }

    /**
     * Remove articulosPedidos
     *
     * @param \AppBundle\Entity\ArticulosPedido $articulosPedidos
     */
    public function removeArticulosPedido(\AppBundle\Entity\ArticulosPedido $articulosPedidos)
    {
        $this->articulosPedidos->removeElement($articulosPedidos);
    }

    /**
     * Get articulosPedidos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticulosPedidos()
    {
        return $this->articulosPedidos;
    }
}
