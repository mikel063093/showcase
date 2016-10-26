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
 * Articulo
 * @ORM\Table(name="articulo", options={"comment" = "Productos o Servicios de un establecimiento","collation"="utf8_unicode_ci"})
 * @ORM\Entity(repositoryClass="ArticuloRepository")
 * @author ALEJANDRO
 */
class Articulo {
    
    
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
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true, options=
     * {"comment" = "Nombre del articulo"})
     */
    private $nombre;
    /**
     * @var string $descripcion
     *
     * @ORM\Column(name="descripcion", type="string", length=1000, nullable=true, options=
     * {"comment" = "Descripcion del articulo"})
     */
    private $descripcion;
    /**
     * @var integer $precio
     *
     * @ORM\Column(name="precio", type="integer", nullable=false, options=
     * {"comment" = "Precio del articulo"})
     */
    private $precio;
    /**
     * @var string $unidadMedida
     *
     * @ORM\Column(name="unidadMedida", type="string", length=31, nullable=true, options=
     * {"comment" = "Unidad de medida del articulo"})
     */
    private $unidadMedida;
    /**
     * @var integer $valorMedida
     *
     * @ORM\Column(name="valorMedida", type="string",length=31, nullable=true, options=
     * {"comment" = "Valor unidad de medida del articulo"})
     */
    private $valorMedida;
    /**
     * @var integer $cantidad
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=false, options=
     * {"comment" = "Cantidad de articulos que hay por reservar"})
     */
    private $cantidad;

    /**
     *
     * @ORM\OneToMany(targetEntity="FotosArticulo", mappedBy="articulo")
     */
    private $fotosArticulos;

    private $file;
    
    /**
     * 
     * @ORM\ManyToOne(targetEntity="Establecimiento", inversedBy="articulos")
     * @ORM\JoinColumn(name="id_establecimiento", referencedColumnName="id")
     **/
    private $establecimiento;

    /**
     * 
     * @ORM\OneToMany(targetEntity="Item", mappedBy="articulo")
     */
    private $items;


    /**
     * 
     * @ORM\OneToMany(targetEntity="ArticulosPedido", mappedBy="articulo")
     */
    private $articulosPedidos;
    

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
     * @return Articulo
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
     * @return Articulo
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
     * Set precio
     *
     * @param integer $precio
     * @return Articulo
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
     * Set unidadMedida
     *
     * @param string $unidadMedida
     * @return Articulo
     */
    public function setUnidadMedida($unidadMedida)
    {
        $this->unidadMedida = $unidadMedida;

        return $this;
    }

    /**
     * Get unidadMedida
     *
     * @return string 
     */
    public function getUnidadMedida()
    {
        return $this->unidadMedida;
    }

    /**
     * Set valorMedida
     *
     * @param integer $valorMedida
     * @return Articulo
     */
    public function setValorMedida($valorMedida)
    {
        $this->valorMedida = $valorMedida;

        return $this;
    }

    /**
     * Get valorMedida
     *
     * @return integer 
     */
    public function getValorMedida()
    {
        return $this->valorMedida;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     * @return Articulo
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
        $this->articulosPedidos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fotosArticulos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set establecimiento
     *
     * @param \AppBundle\Entity\Establecimiento $establecimiento
     * @return Articulo
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
     * Add items
     *
     * @param \AppBundle\Entity\Item $items
     * @return Articulo
     */
    public function addItem(\AppBundle\Entity\Item $items)
    {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Remove items
     *
     * @param \AppBundle\Entity\Item $items
     */
    public function removeItem(\AppBundle\Entity\Item $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Add articulosPedidos
     *
     * @param \AppBundle\Entity\ArticulosPedido $articulosPedidos
     * @return Articulo
     */
    public function addArticulosPedido(\AppBundle\Entity\ArticulosPedido $articulosPedidos)
    {
        $this->articulosPedidos[] = $articulosPedidos;

        return $this;
    }

    /**
     * Remove articulosPedidos
     *
     * @param \AppBundle\Entity\ArticulosPedido $articulosPedidos
     */
    public function removeArticulosPedido(\AppBundle\Entity\ArticulosPedido $articulosPedidos)
    {
        $this->articulosPedidos->removeElement($articulosPedidos);
    }

    /**
     * Get articulosPedidos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticulosPedidos()
    {
        return $this->articulosPedidos;
    }

    /**
     * Set imagen
     *
     * @param string $imagen
     *
     * @return Articulo
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
        return 'imagenes/articulos';
    }
    
    public function upload()
    {
        $em = $GLOBALS['kernel']->getContainer()->get('doctrine.orm.entity_manager');
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

        $foto = new FotosArticulo();
        $foto->setRuta($nombrefinal);
        $foto->setTitulo('Principal');
        $em->persist($foto);
        // set the path property to the filename where you've saved the file
        $this->addFotosArticulo($foto);

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    /**
     * Add fotosArticulos
     *
     * @param \AppBundle\Entity\FotosArticulo $fotosArticulos
     * @return Articulo
     */
    public function addFotosArticulo(\AppBundle\Entity\FotosArticulo $fotosArticulos)
    {
        $this->fotosArticulos[] = $fotosArticulos;

        return $this;
    }

    /**
     * Remove fotosArticulos
     *
     * @param \AppBundle\Entity\FotosArticulo $fotosArticulos
     */
    public function removeFotosArticulo(\AppBundle\Entity\FotosArticulo $fotosArticulos)
    {
        $this->fotosArticulos->removeElement($fotosArticulos);
    }

    /**
     * Get fotosArticulos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFotosArticulos()
    {
        return $this->fotosArticulos;
    }
}
