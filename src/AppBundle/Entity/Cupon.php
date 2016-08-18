<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
/**
 * Cupon
 *
 * @ORM\Table(name="cupon", options={"comment" = "Se almacena los cupones de la aplicacion"})
 * @ORM\Entity(repositoryClass="CuponRepository")
 *
 * @author ALEJANDRO
 */
class Cupon {
    
	/**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $codigo
     *
     * @ORM\Column(name="codigo", type="string", length=120, nullable=false, options=
     * {"comment" = "codigo por el cual se redime un cupon"})
     */
    private $codigo;

    /**
     * @var integer $valor
     *
     * @ORM\Column(name="valor", type="integer", nullable=false, options=
     * {"comment" = "valor de descuento del cupon"})
     */
    private $valor;

    /**
     * @var string $fechaLimite
     *
     * @ORM\Column(name="fechaLimite", type="datetime", nullable=true, options=
     * {"comment" = "fecha limite para redimir un cupon"})
     */
    private $fechaLimite;

    /**
     * @var string $estado
     *
     * @ORM\Column(name="estado", type="string", length=120, nullable=true, options=
     * {"comment" = "estado por el cual se identifica si un cupon sepueden redimir o no"})
     */
    private $estado;



    /**
     * 
     * @ORM\OneToMany(targetEntity="Pedido", mappedBy="cupon")
     */
    private $pedidos;

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
     * Set codigo
     *
     * @param string $codigo
     * @return Cupon
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set valor
     *
     * @param integer $valor
     * @return Cupon
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return integer 
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return Cupon
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
     * Add pedidos
     *
     * @param \AppBundle\Entity\Pedido $pedidos
     * @return Cupon
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
     * Set fechaLimite
     *
     * @param \DateTime $fechaLimite
     *
     * @return Cupon
     */
    public function setFechaLimite($fechaLimite)
    {
        $this->fechaLimite = $fechaLimite;

        return $this;
    }

    /**
     * Get fechaLimite
     *
     * @return \DateTime
     */
    public function getFechaLimite()
    {
        return $this->fechaLimite;
    }
}
