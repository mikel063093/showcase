<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
/**
 * Articulo
 * @ORM\Table(name="articulo", options={"comment" = "Productos o Servicios de un establecimiento"})
 * @ORM\Entity(repositoryClass="ArticuloRepository")
 * @author ALEJANDRO
 */
class Articulo {
    
    
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string $titulo
     *
     * @ORM\Column(name="titulo", type="string", length=120, nullable=false, options=
     * {"comment" = "Nombre del articulo"})
     */
    private $titulo;
    /**
     * @var string $descripcion
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=true, options=
     * {"comment" = "Descripcion del articulo"})
     */
    private $descripcion;
    /**
     * @var integer $precio
     *
     * @ORM\Column(name="precio", type="integer", nullable=false, options=
     * {"comment" = "Precio del articulo"})
     */
    private $precio;
    /**
     * @var string $unidadMedida
     *
     * @ORM\Column(name="unidadMedida", type="string", length=31, nullable=true, options=
     * {"comment" = "Unidad de medida del articulo"})
     */
    private $unidadMedida;
    /**
     * @var integer $valorMedida
     *
     * @ORM\Column(name="valorMedida", type="integer", nullable=true, options=
     * {"comment" = "Valor unidad de medida del articulo"})
     */
    private $valorMedida;
    /**
     * @var integer $cantidad
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=false, options=
     * {"comment" = "Cantidad de articulos que hay por reservar"})
     */
    private $cantidad;
    
    /**
     * 
     * @ORM\ManyToOne(targetEntity="Establecimiento", inversedBy="articulos")
     * @ORM\JoinColumn(name="id_establecimiento", referencedColumnName="id")
     **/
    private $establecimiento;

    /**
     * 
     * @ORM\OneToMany(targetEntity="Item", mappedBy="articulo")
     */
    private $items;


    /**
     * 
     * @ORM\OneToMany(targetEntity="ArticulosPedido", mappedBy="articulo")
     */
    private $articulosPedidos;
    

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
     * Set titulo
     *
     * @param string $titulo
     * @return Articulo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Articulo
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set precio
     *
     * @param integer $precio
     * @return Articulo
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
     * Set unidadMedida
     *
     * @param string $unidadMedida
     * @return Articulo
     */
    public function setUnidadMedida($unidadMedida)
    {
        $this->unidadMedida = $unidadMedida;

        return $this;
    }

    /**
     * Get unidadMedida
     *
     * @return string 
     */
    public function getUnidadMedida()
    {
        return $this->unidadMedida;
    }

    /**
     * Set valorMedida
     *
     * @param integer $valorMedida
     * @return Articulo
     */
    public function setValorMedida($valorMedida)
    {
        $this->valorMedida = $valorMedida;

        return $this;
    }

    /**
     * Get valorMedida
     *
     * @return integer 
     */
    public function getValorMedida()
    {
        return $this->valorMedida;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     * @return Articulo
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
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
        $this->articulosPedidos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set establecimiento
     *
     * @param \AppBundle\Entity\Establecimiento $establecimiento
     * @return Articulo
     */
    public function setEstablecimiento(\AppBundle\Entity\Establecimiento $establecimiento = null)
    {
        $this->establecimiento = $establecimiento;

        return $this;
    }

    /**
     * Get establecimiento
     *
     * @return \AppBundle\Entity\Establecimiento 
     */
    public function getEstablecimiento()
    {
        return $this->establecimiento;
    }

    /**
     * Add items
     *
     * @param \AppBundle\Entity\Item $items
     * @return Articulo
     */
    public function addItem(\AppBundle\Entity\Item $items)
    {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Remove items
     *
     * @param \AppBundle\Entity\Item $items
     */
    public function removeItem(\AppBundle\Entity\Item $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Add articulosPedidos
     *
     * @param \AppBundle\Entity\ArticulosPedido $articulosPedidos
     * @return Articulo
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
