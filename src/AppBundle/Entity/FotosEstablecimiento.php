<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FotosEstablecimiento
 * 
 * @ORM\Table(name="fotosEstablecimiento", options={"comment" = "Fotos del establecimiento"})
 * @ORM\Entity(repositoryClass="FotosEstablecimientoRepository") 
 *
 * @author ALEJANDRO
 */
class FotosEstablecimiento {
    
	/**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $titulo
     *
     * @ORM\Column(name="titulo", type="string", length=120, nullable=false, options=
     * {"comment" = "titulo del video"})
     */
    private $titulo;
    
    /**
     * @var string $ruta
     *
     * @ORM\Column(name="ruta", type="string", length=255, options=
     * {"comment" = "Ruta del recurso a desplegar"})
     */
    private $ruta;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Establecimiento", inversedBy="fotosEstablecimientos")
     * @ORM\JoinColumn(name="id_establecimiento", referencedColumnName="id")
     **/
    private $establecimiento;


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
     * @return FotosEstablecimiento
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
     * Set ruta
     *
     * @param string $ruta
     * @return FotosEstablecimiento
     */
    public function setRuta($ruta)
    {
        $this->ruta = $ruta;

        return $this;
    }

    /**
     * Get ruta
     *
     * @return string 
     */
    public function getRuta()
    {
        return $this->ruta;
    }

    /**
     * Set establecimiento
     *
     * @param \AppBundle\Entity\Establecimiento $establecimiento
     * @return FotosEstablecimiento
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
}
