<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Plan
 *
 * @ORM\Table(name="plan", options={"comment" = "los planes con los que cuenta la aplicacion"})
 * @ORM\Entity(repositoryClass="PlanRepository") 
 *
 * @author ALEJANDRO
 */
class Plan {
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
     * @var string $descripcion
     *
     * @ORM\Column(name="descripcion", type="string", length=1000, nullable=false, options=
     * {"comment" = "Nombre de la direccion"})
     */
    private $descripcion;


    /**
     * 
     * @ORM\OneToMany(targetEntity="Establecimiento", mappedBy="plan")
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
     * @return Plan
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Plan
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
     * Add establecimientos
     *
     * @param \AppBundle\Entity\Establecimiento $establecimientos
     * @return Plan
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
}
