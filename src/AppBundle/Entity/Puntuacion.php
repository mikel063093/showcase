<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * Puntuacion
 *
 * @ORM\Table(name="puntuacion", options={"comment" = "se almacenan las puntuaciones que se hacen sobre un establecimiento"})
 * @ORM\Entity(repositoryClass="PuntuacionRepository") 
 *
 * @author ALEJANDRO
 */
class Puntuacion {
     /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer $valor
     *
     * @ORM\Column(name="valor", type="integer", nullable=false, options=
     * {"comment" = "la puntuacion dada al establecimiento"})
     */
    private $valor;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Establecimiento", inversedBy="puntuaciones")
     * @ORM\JoinColumn(name="id_establecimiento", referencedColumnName="id")
     **/
    private $establecimiento;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="puntuaciones")
     * @ORM\JoinColumn(name="id_usuario", referencedColumnName="id")
     **/
    private $usuario;

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
     * Set valor
     *
     * @param integer $valor
     * @return Puntuacion
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return integer 
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set establecimiento
     *
     * @param \AppBundle\Entity\Establecimiento $establecimiento
     * @return Puntuacion
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
     * Set usuario
     *
     * @param \AppBundle\Entity\Usuario $usuario
     *
     * @return Puntuacion
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
}
