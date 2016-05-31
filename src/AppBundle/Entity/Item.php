<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Item
 * 
 * @ORM\Table(name="item", options={"comment" = "Relaciona los productos con el carrito de compras"})
 * @ORM\Entity(repositoryClass="ItemRepository") 
 *
 * @author ALEJANDRO
 */
class Item {
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer $cantidad
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=false, options=
     * {"comment" = "la cantidad de un producto que se reservo en el carrito"})
     */
    private $cantidad;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Articulo", inversedBy="items")
     * @ORM\JoinColumn(name="id_articulo", referencedColumnName="id")
     **/
    private $articulo;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Carrito", inversedBy="items")
     * @ORM\JoinColumn(name="id_carrito", referencedColumnName="id")
     **/
    private $carrito;


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
     * Set cantidad
     *
     * @param integer $cantidad
     * @return Item
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
     * @return Item
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
     * Set carrito
     *
     * @param \AppBundle\Entity\Carrito $carrito
     * @return Item
     */
    public function setCarrito(\AppBundle\Entity\Carrito $carrito = null)
    {
        $this->carrito = $carrito;

        return $this;
    }

    /**
     * Get carrito
     *
     * @return \AppBundle\Entity\Carrito 
     */
    public function getCarrito()
    {
        return $this->carrito;
    }
}
