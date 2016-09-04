<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
/**
 * CuponUsuario
 *
 * @ORM\Table(name="cuponUsuario", options={"comment" = "Se almacena los cupones que usa un usuario"})
 * @ORM\Entity(repositoryClass="CuponUsuarioRepository")
 *
 * @author ALEJANDRO
 */
class CuponUsuario {
    
	/**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="cuponUsuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_usuario", referencedColumnName="id")
     * })
     */
    private $usuario;
    /**
     * @var Cupon
     *
     * @ORM\ManyToOne(targetEntity="Cupon", inversedBy="cuponUsuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_cupon", referencedColumnName="id")
     * })
     */
    private $cupon;

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
     * Set usuario
     *
     * @param \AppBundle\Entity\Usuario $usuario
     *
     * @return CuponUsuario
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
     * Set cupon
     *
     * @param \AppBundle\Entity\Cupon $cupon
     *
     * @return CuponUsuario
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
}
