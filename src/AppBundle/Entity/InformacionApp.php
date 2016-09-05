<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * InformacionApp
 * 
 * @ORM\Table(name="informacionApp", options={"comment" = "Informacion de la aplicacion"})
 * @ORM\Entity(repositoryClass="InformacionAppRepository") 
 *
 * @author ALEJANDRO
 */
class InformacionApp {
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer $precioDomicilio
     *
     * @ORM\Column(name="precioDomicilio", type="integer", nullable=true, options=
     * {"comment" = "Precio del domicio de showcase"})
     */
    private $precioDomicilio;

    /**
     * @var integer $imagenNosotros
     *
     * @ORM\Column(name="imagenNosotros", type="string", nullable=true, options=
     * {"comment" = "Imagen para la pagina Nosotros"})
     */
    private $imagenNosotros;

    /**
     * @var integer $nosotros
     *
     * @ORM\Column(name="nosotros", type="string", nullable=true, options=
     * {"comment" = "texto para la pagina Nosotros"})
     */
    private $nosotros;

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
     * Set precioDomicilio
     *
     * @param integer $precioDomicilio
     *
     * @return InformacionApp
     */
    public function setPrecioDomicilio($precioDomicilio)
    {
        $this->precioDomicilio = $precioDomicilio;

        return $this;
    }

    /**
     * Get precioDomicilio
     *
     * @return integer
     */
    public function getPrecioDomicilio()
    {
        return $this->precioDomicilio;
    }

    /**
     * Set imagenNosotros
     *
     * @param string $imagenNosotros
     *
     * @return InformacionApp
     */
    public function setImagenNosotros($imagenNosotros)
    {
        $this->imagenNosotros = $imagenNosotros;

        return $this;
    }

    /**
     * Get imagenNosotros
     *
     * @return string
     */
    public function getImagenNosotros()
    {
        return $this->imagenNosotros;
    }

    /**
     * Set nosotros
     *
     * @param string $nosotros
     *
     * @return InformacionApp
     */
    public function setNosotros($nosotros)
    {
        $this->nosotros = $nosotros;

        return $this;
    }

    /**
     * Get nosotros
     *
     * @return string
     */
    public function getNosotros()
    {
        return $this->nosotros;
    }
}
