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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
