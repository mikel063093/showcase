<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Zona
 *
 * @ORM\Table(name="zona", options={"comment" = "las zonas de ubicacion de los establecimientos"})
 * @ORM\Entity(repositoryClass="ZonaRepository") 
 * @author ALEJANDRO
 */
class Zona {
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
     * {"comment" = "Nombre de la zona"})
     */
    private $nombre;

    /**
     * @var string $centro
     *
     * @ORM\Column(name="centro", type="string", length=63, nullable=true, options=
     * {"comment" = "Centro de la zona en google maps"})
     */
    private $centro;

    /**
     * @var string $zoom
     *
     * @ORM\Column(name="zoom", type="string", length=63, nullable=true, options=
     * {"comment" = "Zoom de la zona en google maps"})
     */
    private $zoom;

    /**
     * 
     * @ORM\OneToMany(targetEntity="Establecimiento", mappedBy="zona")
     */
    private $establecimientos;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->establecimientos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Zona
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
     * Add establecimientos
     *
     * @param \AppBundle\Entity\Establecimiento $establecimientos
     * @return Zona
     */
    public function addEstablecimiento(\AppBundle\Entity\Establecimiento $establecimientos)
    {
        $this->establecimientos[] = $establecimientos;

        return $this;
    }

    /**
     * Remove establecimientos
     *
     * @param \AppBundle\Entity\Establecimiento $establecimientos
     */
    public function removeEstablecimiento(\AppBundle\Entity\Establecimiento $establecimientos)
    {
        $this->establecimientos->removeElement($establecimientos);
    }

    /**
     * Get establecimientos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstablecimientos()
    {
        return $this->establecimientos;
    }

    /**
     * Set centro
     *
     * @param string $centro
     *
     * @return Zona
     */
    public function setCentro($centro)
    {
        $this->centro = $centro;

        return $this;
    }

    /**
     * Get centro
     *
     * @return string
     */
    public function getCentro()
    {
        return $this->centro;
    }

    /**
     * Set zoom
     *
     * @param string $zoom
     *
     * @return Zona
     */
    public function setZoom($zoom)
    {
        $this->zoom = $zoom;

        return $this;
    }

    /**
     * Get zoom
     *
     * @return string
     */
    public function getZoom()
    {
        return $this->zoom;
    }
}
