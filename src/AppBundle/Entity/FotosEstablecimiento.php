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

    private $file;
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
        return 'imagenes/establecimiento';
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
        $this->logo = $nombrefinal;

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }
}
