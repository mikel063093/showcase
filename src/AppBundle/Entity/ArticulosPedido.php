<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * ArticulosPedido
 * @ORM\Table(name="articulosPedido", options={"comment" = "Relacion de productos que se tomaron en un pedido"})
 * @ORM\Entity(repositoryClass="ArticulosPedidoRepository")
 * @author ALEJANDRO
 */
class ArticulosPedido {
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var integer $precio
     *
     * @ORM\Column(name="precio", type="integer", nullable=false, options=
     * {"comment" = "Precio del articulo"})
     */
    private $precio;
    
    /**
     * @var integer $cantidad
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=false, options=
     * {"comment" = "Cantidad de articulos pedidos"})
     */
    private $cantidad;

     /**
     * 
     * @ORM\ManyToOne(targetEntity="Articulo", inversedBy="articulosPedidos")
     * @ORM\JoinColumn(name="id_articulo", referencedColumnName="id")
     **/
    private $articulo;


    /**
     * 
     * @ORM\ManyToOne(targetEntity="Pedido", inversedBy="articulosPedidos")
     * @ORM\JoinColumn(name="id_pedido", referencedColumnName="id")
     **/
    private $pedido;

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
     * Set precio
     *
     * @param integer $precio
     * @return ArticulosPedido
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return integer 
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     * @return ArticulosPedido
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set articulo
     *
     * @param \AppBundle\Entity\Articulo $articulo
     * @return ArticulosPedido
     */
    public function setArticulo(\AppBundle\Entity\Articulo $articulo = null)
    {
        $this->articulo = $articulo;

        return $this;
    }

    /**
     * Get articulo
     *
     * @return \AppBundle\Entity\Articulo 
     */
    public function getArticulo()
    {
        return $this->articulo;
    }

    /**
     * Set pedido
     *
     * @param \AppBundle\Entity\Pedido $pedido
     * @return ArticulosPedido
     */
    public function setPedido(\AppBundle\Entity\Pedido $pedido = null)
    {
        $this->pedido = $pedido;

        return $this;
    }

    /**
     * Get pedido
     *
     * @return \AppBundle\Entity\Pedido 
     */
    public function getPedido()
    {
        return $this->pedido;
    }
}
