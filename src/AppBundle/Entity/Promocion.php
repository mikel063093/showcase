<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * Promocion
 *
 * @ORM\Table(name="promocion", options={"comment" = "Se almacena las promociones de la aplicacion"})
 * @ORM\Entity(repositoryClass="PromocionRepository")
 *
 * @author ALEJANDRO
 */
class Promocion {
    
	/**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;



    /**
     * @var string $fechaInicio
     *
     * @ORM\Column(name="fechaInicio", type="datetime", nullable=true, options=
     * {"comment" = "fecha cuando empieza aparecer la promocion"})
     */
    private $fechaInicio;

    /**
     * @var string $fechaFin
     *
     * @ORM\Column(name="fechaFin", type="datetime", nullable=true, options=
     * {"comment" = "fecha cuando termina de aparecer la promocion"})
     */
    private $fechaFin;

    /**
     * @var string $ruta
     *
     * @ORM\Column(name="ruta", type="string", length=255, options=
     * {"comment" = "Ruta del recurso a desplegar"})
     */
    private $ruta;

    private $file;



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
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }


    public function getAbsolutePath()
    {
        return null === $this->ruta
            ? null
            : $this->getUploadRootDir().'/'.$this->ruta;
    }

    public function getWebPath()
    {
        return null === $this->ruta
            ? null
            : $this->getUploadDir().'/'.$this->ruta;
    }

    public function getUploadRootDir()
    {
        // la ruta absoluta del directorio donde se deben
        // guardar los archivos cargados
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    public function getUploadDir()
    {
        // se deshace del __DIR__ para no meter la pata
        // al mostrar el documento/imagen cargada en la vista.
        return 'imagenes/promociones';
    }

    public function upload()
    {

        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {

            return;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to

        $posExten = strripos($this->getFile()->getClientOriginalName(), ".");
        $exten = substr($this->getFile()->getClientOriginalName(), $posExten);
        $nombrefinal = sha1_file($this->getFile()).$exten;

        $this->getFile()->move(
            $this->getUploadRootDir(),
            $nombrefinal
        );

        // set the path property to the filename where you've saved the file
        $this->ruta = $nombrefinal;

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }



    /**
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     *
     * @return Promocion
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     *
     * @return Promocion
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }
}
