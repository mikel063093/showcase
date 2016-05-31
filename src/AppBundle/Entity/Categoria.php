<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Categoria
 *
 * @ORM\Table(name="categoria", options={"comment" = "Categorias a las que puede pertenecer un establecimeinto"})
 * @ORM\Entity(repositoryClass="CategoriaRepository")
 * 
 * @author ALEJANDRO
 */
class Categoria {
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
     * {"comment" = "Nombre de la categoria"})
     */
    private $nombre;

    /**
     * 
     * @ORM\OneToMany(targetEntity="Establecimiento", mappedBy="categoria")
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
     * @return Categoria
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
     * @return Categoria
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
