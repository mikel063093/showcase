<?php

namespace AppBundle\Entity;

use AppBundle\AppBundle;
use Doctrine\ORM\Mapping as ORM;

/**
 * Ciudad
 *
 * @ORM\Table(name="ciudad", options={"comment" = "Ciudades que se usaran en las rutas"})
 * @ORM\Entity(repositoryClass="CiudadRepository")
 */
class Ciudad
{
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
     * {"comment" = "Nombre del lugar"})
     */
    private $nombre;
    
    
    /**
     * @var Depto
     *
     * @ORM\ManyToOne(targetEntity="Depto", inversedBy="ciudades")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_depto", referencedColumnName="id")
     * })
     */
    private $depto;

    /**
     *
     * @ORM\OneToMany(targetEntity="Direccion", mappedBy="ciudad")
     */
    private $direcciones;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        
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
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
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
     * @return string
     */
    public function __toString()
    {
        return $this->getNombre();
    }
    
    /**
     * Set depto
     *
     * @param Depto $depto
     */
    public function setDepto(Depto $depto)
    {
        $this->depto = $depto;
    }

    /**
     * Get depto
     *
     * @return Depto $depto
     */
    public function getDepto()
    {
        return $this->depto;
    }

    

    /**
     * Add direccione
     *
     * @param \AppBundle\Entity\Direccion $direccione
     *
     * @return Ciudad
     */
    public function addDireccione(\AppBundle\Entity\Direccion $direccione)
    {
        $this->direcciones[] = $direccione;

        return $this;
    }

    /**
     * Remove direccione
     *
     * @param \AppBundle\Entity\Direccion $direccione
     */
    public function removeDireccione(\AppBundle\Entity\Direccion $direccione)
    {
        $this->direcciones->removeElement($direccione);
    }

    /**
     * Get direcciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDirecciones()
    {
        return $this->direcciones;
    }
}
