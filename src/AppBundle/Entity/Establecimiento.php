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
 * Establecimiento
 *
 * @ORM\Table(name="establecimiento", options={"comment" = "Establecimientos que ofrecen productos y servicios en la aplicacion"})
 * @ORM\Entity(repositoryClass="EstablecimientoRepository")
 * 
 * @author ALEJANDRO
 */
class Establecimiento {
    
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
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true, options=
     * {"comment" = "Nombre del establecimiento"})
     */
    private $nombre;

    /**
     * @var string $descripcion
     *
     * @ORM\Column(name="descripcion", type="string", length=1000, nullable=true, options=
     * {"comment" = "Descripcion del establecimiento"})
     */
    private $descripcion;

    /**
     * @var string $direccion
     *
     * @ORM\Column(name="direccion", type="string", length=120, nullable=true, options=
     * {"comment" = "direccion del establecimiento"})
     */
    private $direccion;

    /**
     * @var string $telefono
     *
     * @ORM\Column(name="telefono", type="string", length=31, nullable=true, options=
     * {"comment" = "Telefono del establecimiento"})
     */
    private $telefono;

    /**
     * @var string $sitioWeb
     *
     * @ORM\Column(name="sitioWeb", type="string", length=255, nullable=true, options=
     * {"comment" = "url del sitio web del establecimiento"})
     */
    private $sitioWeb;

    /**
     * @var string $facebook
     *
     * @ORM\Column(name="facebook", type="string", length=63, nullable=true, options=
     * {"comment" = "Facebook del establecimiento"})
     */
    private $facebook;

    /**
     * @var string $twitter
     *
     * @ORM\Column(name="twitter", type="string", length=63, nullable=true, options=
     * {"comment" = "Twitter del establecimiento"})
     */
    private $twitter;

    /**
     * @var string $snapchat
     *
     * @ORM\Column(name="snapchat", type="string", length=63, nullable=true, options=
     * {"comment" = "Snapchat del establecimiento"})
     */
    private $snapchat;

    /**
     * @var string $youtube
     *
     * @ORM\Column(name="youtube", type="string", length=63, nullable=true, options=
     * {"comment" = "Direccion de Youtube del establecimiento"})
     */
    private $youtube;

    /**
     * @var string $instagram
     *
     * @ORM\Column(name="instagram", type="string", length=63, nullable=true, options=
     * {"comment" = "Instagram del establecimiento"})
     */
    private $instagram;

    /**
     * @var string $correo
     *
     * @ORM\Column(name="correo", type="string", length=63, nullable=true, options=
     * {"comment" = "Correo del establecimiento"})
     */
    private $correo;

    /**
     * @var string $whatsapp
     *
     * @ORM\Column(name="whatsapp", type="string", length=63, nullable=true, options=
     * {"comment" = "Whatsapp del establecimiento"})
     */
    private $whatsapp;

    /**
     * @var float $peso
     *
     * @ORM\Column(name="peso", type="float", nullable=true, options=
     * {"comment" = "Peso de prioridad de un establecimiento en la aplicacion"})
     */
    private $peso;

    /**
     * @var string $localizacion
     *
     * @ORM\Column(name="localizacion", type="string", length=63, nullable=true, options=
     * {"comment" = "Localizacion en google maps del establecimiento"})
     */
    private $localizacion;
    /**
     * @var string $logo
     *
     * @ORM\Column(name="logo", type="string", nullable=true,length=63)
     * */
    private $logo;

    private $file;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Categoria", inversedBy="establecimientos")
     * @ORM\JoinColumn(name="id_categoria", referencedColumnName="id")
     **/
    private $categoria;
    
    /**
     * 
     * @ORM\ManyToOne(targetEntity="Plan", inversedBy="establecimientos")
     * @ORM\JoinColumn(name="id_plan", referencedColumnName="id")
     **/
    private $plan;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Zona", inversedBy="establecimientos")
     * @ORM\JoinColumn(name="id_zona", referencedColumnName="id")
     **/
    private $zona;

    /**
     * 
     * @ORM\OneToMany(targetEntity="Puntuacion", mappedBy="establecimiento")
     */
    private $puntuaciones;

    /**
     * 
     * @ORM\OneToMany(targetEntity="FotosEstablecimiento", mappedBy="establecimiento")
     */
    private $fotosEstablecimientos;

    /**
     * 
     * @ORM\OneToMany(targetEntity="Articulo", mappedBy="establecimiento")
     */
    private $articulos;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->puntuaciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fotosEstablecimientos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->articulos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Establecimiento
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
     * @return Establecimiento
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
     * Set direccion
     *
     * @param string $direccion
     * @return Establecimiento
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Establecimiento
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set sitioWeb
     *
     * @param string $sitioWeb
     * @return Establecimiento
     */
    public function setSitioWeb($sitioWeb)
    {
        $this->sitioWeb = $sitioWeb;

        return $this;
    }

    /**
     * Get sitioWeb
     *
     * @return string 
     */
    public function getSitioWeb()
    {
        return $this->sitioWeb;
    }

    /**
     * Set facebook
     *
     * @param string $facebook
     * @return Establecimiento
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook
     *
     * @return string 
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set twitter
     *
     * @param string $twitter
     * @return Establecimiento
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * Get twitter
     *
     * @return string 
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set snapchat
     *
     * @param string $snapchat
     * @return Establecimiento
     */
    public function setSnapchat($snapchat)
    {
        $this->snapchat = $snapchat;

        return $this;
    }

    /**
     * Get snapchat
     *
     * @return string 
     */
    public function getSnapchat()
    {
        return $this->snapchat;
    }

    /**
     * Set youtube
     *
     * @param string $youtube
     * @return Establecimiento
     */
    public function setYoutube($youtube)
    {
        $this->youtube = $youtube;

        return $this;
    }

    /**
     * Get youtube
     *
     * @return string 
     */
    public function getYoutube()
    {
        return $this->youtube;
    }

    /**
     * Set instagram
     *
     * @param string $instagram
     * @return Establecimiento
     */
    public function setInstagram($instagram)
    {
        $this->instagram = $instagram;

        return $this;
    }

    /**
     * Get instagram
     *
     * @return string 
     */
    public function getInstagram()
    {
        return $this->instagram;
    }

    /**
     * Set peso
     *
     * @param float $peso
     * @return Establecimiento
     */
    public function setPeso($peso)
    {
        $this->peso = $peso;

        return $this;
    }

    /**
     * Get peso
     *
     * @return float 
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * Set localizacion
     *
     * @param string $localizacion
     * @return Establecimiento
     */
    public function setLocalizacion($localizacion)
    {
        $this->localizacion = $localizacion;

        return $this;
    }

    /**
     * Get localizacion
     *
     * @return string 
     */
    public function getLocalizacion()
    {
        return $this->localizacion;
    }

    /**
     * Set categoria
     *
     * @param \AppBundle\Entity\Categoria $categoria
     * @return Establecimiento
     */
    public function setCategoria(\AppBundle\Entity\Categoria $categoria = null)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return \AppBundle\Entity\Categoria 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set plan
     *
     * @param \AppBundle\Entity\Plan $plan
     * @return Establecimiento
     */
    public function setPlan(\AppBundle\Entity\Plan $plan = null)
    {
        $this->plan = $plan;

        return $this;
    }

    /**
     * Get plan
     *
     * @return \AppBundle\Entity\Plan 
     */
    public function getPlan()
    {
        return $this->plan;
    }

    /**
     * Set zona
     *
     * @param \AppBundle\Entity\Zona $zona
     * @return Establecimiento
     */
    public function setZona(\AppBundle\Entity\Zona $zona = null)
    {
        $this->zona = $zona;

        return $this;
    }

    /**
     * Get zona
     *
     * @return \AppBundle\Entity\Zona 
     */
    public function getZona()
    {
        return $this->zona;
    }

    /**
     * Add puntuaciones
     *
     * @param \AppBundle\Entity\Puntuacion $puntuaciones
     * @return Establecimiento
     */
    public function addPuntuacione(\AppBundle\Entity\Puntuacion $puntuaciones)
    {
        $this->puntuaciones[] = $puntuaciones;

        return $this;
    }

    /**
     * Remove puntuaciones
     *
     * @param \AppBundle\Entity\Puntuacion $puntuaciones
     */
    public function removePuntuacione(\AppBundle\Entity\Puntuacion $puntuaciones)
    {
        $this->puntuaciones->removeElement($puntuaciones);
    }

    /**
     * Get puntuaciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPuntuaciones()
    {
        return $this->puntuaciones;
    }

    /**
     * Add fotosEstablecimientos
     *
     * @param \AppBundle\Entity\FotosEstablecimiento $fotosEstablecimientos
     * @return Establecimiento
     */
    public function addFotosEstablecimiento(\AppBundle\Entity\FotosEstablecimiento $fotosEstablecimientos)
    {
        $this->fotosEstablecimientos[] = $fotosEstablecimientos;

        return $this;
    }

    /**
     * Remove fotosEstablecimientos
     *
     * @param \AppBundle\Entity\FotosEstablecimiento $fotosEstablecimientos
     */
    public function removeFotosEstablecimiento(\AppBundle\Entity\FotosEstablecimiento $fotosEstablecimientos)
    {
        $this->fotosEstablecimientos->removeElement($fotosEstablecimientos);
    }

    /**
     * Get fotosEstablecimientos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFotosEstablecimientos()
    {
        return $this->fotosEstablecimientos;
    }

    /**
     * Add articulos
     *
     * @param \AppBundle\Entity\Articulo $articulos
     * @return Establecimiento
     */
    public function addArticulo(\AppBundle\Entity\Articulo $articulos)
    {
        $this->articulos[] = $articulos;

        return $this;
    }

    /**
     * Remove articulos
     *
     * @param \AppBundle\Entity\Articulo $articulos
     */
    public function removeArticulo(\AppBundle\Entity\Articulo $articulos)
    {
        $this->articulos->removeElement($articulos);
    }

    /**
     * Get articulos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticulos()
    {
        return $this->articulos;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo() {
        return $this->logo;
    }

    /**
     * Set logo
     *
     * @param string $logo
     * @return Teatro
     */
    public function setLogo($logo) {
        $this->logo = $logo;
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
        return null === $this->logo
            ? null
            : $this->getUploadRootDir().'/'.$this->logo;
    }

    public function getWebPath()
    {
        return null === $this->logo
            ? null
            : $this->getUploadDir().'/'.$this->logo;
    }

    protected function getUploadRootDir()
    {
        // la ruta absoluta del directorio donde se deben
        // guardar los archivos cargados
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // se deshace del __DIR__ para no meter la pata
        // al mostrar el documento/imagen cargada en la vista.
        return 'imagenes/logos';
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

    /**
     * Set correo
     *
     * @param string $correo
     *
     * @return Establecimiento
     */
    public function setCorreo($correo)
    {
        $this->correo = $correo;

        return $this;
    }

    /**
     * Get correo
     *
     * @return string
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * Set whatsapp
     *
     * @param string $whatsapp
     *
     * @return Establecimiento
     */
    public function setWhatsapp($whatsapp)
    {
        $this->whatsapp = $whatsapp;

        return $this;
    }

    /**
     * Get whatsapp
     *
     * @return string
     */
    public function getWhatsapp()
    {
        return $this->whatsapp;
    }

    public function promedio(){
        $puntuacion = 0;
        if(count($this->getPuntuaciones())>0) {
            $total = 0;
            foreach ($this->getPuntuaciones() as $p) {
                $total = $total + $p->getValor();
            }
            $puntuacion = $total / count($this->getPuntuaciones());
        }
        if($puntuacion < 3){
            $puntuacion = 3;
        }

        return $puntuacion;
    }
}
