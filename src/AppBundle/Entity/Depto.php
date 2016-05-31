<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entidades\Depto
 *
 * @ORM\Table(name="depto", options={"comment" = "Departamentos para la clasificacion de ciudades"})
 * @ORM\Entity(repositoryClass="DeptoRepository")
 */
class Depto
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string", length=30, nullable=true, options=
     * {"comment" = "Nombre del departamento"})
     */
    private $nombre;
    
    /**
     * @var Ciudad $ciudades
     *
     * @ORM\OneToMany(targetEntity="Ciudad", mappedBy="depto")
     * @ORM\OrderBy({"nombre" = "ASC"})
     */
    private $ciudades;

    
    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ciudades = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Depto
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
     * Add ciudades
     *
     * @param \AppBundle\Entity\Ciudad $ciudades
     * @return Depto
     */
    public function addCiudade(\AppBundle\Entity\Ciudad $ciudades)
    {
        $this->ciudades[] = $ciudades;

        return $this;
    }

    /**
     * Remove ciudades
     *
     * @param \AppBundle\Entity\Ciudad $ciudades
     */
    public function removeCiudade(\AppBundle\Entity\Ciudad $ciudades)
    {
        $this->ciudades->removeElement($ciudades);
    }

    /**
     * Get ciudades
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCiudades()
    {
        return $this->ciudades;
    }
}
