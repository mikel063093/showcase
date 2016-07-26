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
     * {"comment" = "Nombre del plan"})
     */
    private $nombre;

    /**
     * @var string $precio
     *
     * @ORM\Column(name="precio", type="integer",  nullable=false, options=
     * {"comment" = "Precio del plan"})
     */
    private $precio;
    /**
     * @var string $descripcion
     *
     * @ORM\Column(name="descripcion", type="string", length=1000, nullable=true, options=
     * {"comment" = "Descripcion del plan"})
     */
    private $descripcion;

    /**
     * @var string $imagen
     *
     * @ORM\Column(name="imagen", type="string", length=255, nullable=true, options=
     * {"comment" = "Imagen del plan"})
     */
    private $imagen;

    private $file;
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

    /**
     * Set precio
     *
     * @param integer $precio
     *
     * @return Plan
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return integer
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set imagen
     *
     * @param string $imagen
     *
     * @return Plan
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Get imagen
     *
     * @return string
     */
    public function getImagen()
    {
        return $this->imagen;
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
        return null === $this->imagen
            ? null
            : $this->getUploadRootDir().'/'.$this->imagen;
    }

    public function getWebPath()
    {
        return null === $this->imagen
            ? null
            : $this->getUploadDir().'/'.$this->imagen;
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
        return 'imagenes/planes';
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
        $this->imagen = $nombrefinal;

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }
}
