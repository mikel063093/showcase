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

    private $file;
    /**
     * @var integer $nosotros
     *
     * @ORM\Column(name="nosotros", type="text", nullable=true, options=
     * {"comment" = "texto para la pagina Nosotros"})
     */
    private $nosotros;

    /**
     * @var integer $imagenInicio
     *
     * @ORM\Column(name="imagenInicio", type="string", nullable=true, options=
     * {"comment" = "Imagen para la pagina inicio"})
     */
    private $imagenInicio;

    private $fileCelular;

    /**
     * @var integer $inicio
     *
     * @ORM\Column(name="inicio", type="string", nullable=true,length=1000, options=
     * {"comment" = "texto para la pagina de inicio"})
     */
    private $inicio;

    /**
     * @var integer $urlPlayStore
     *
     * @ORM\Column(name="urlPlayStore", type="string", nullable=true ,options=
     * {"comment" = "url de la aplicacion en la play store"})
     */
    private $urlPlayStore;

    /**
     * @var integer $terminos
     *
     * @ORM\Column(name="terminos", type="text", nullable=true ,options=
     * {"comment" = "url de la aplicacion en la play store"})
     */
    private $terminos;

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
        return null === $this->imagenNosotros
            ? null
            : $this->getUploadRootDir().'/'.$this->imagenNosotros;
    }

    public function getWebPath()
    {
        return null === $this->imagenNosotros
            ? null
            : $this->getUploadDir().'/'.$this->imagenNosotros;
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
        return 'imagenes/nosotros';
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
        $this->imagenNosotros = $nombrefinal;

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    public function uploadInicio()
    {

        // the file property can be empty if the field is not required
        if (null === $this->getFileCelular()) {

            return;
        }


        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to

        $posExten = strripos($this->getFileCelular()->getClientOriginalName(), ".");
        $exten = substr($this->getFileCelular()->getClientOriginalName(), $posExten);
        $nombrefinal = sha1_file($this->getFileCelular()).$exten;




        $this->getFileCelular()->move(
            __DIR__.'/../../../web/imagenes/inicio',
            $nombrefinal
        );

        // set the path property to the filename where you've saved the file
        $this->imagenInicio = $nombrefinal;

        // clean up the file property as you won't need it anymore
        $this->fileCelular = null;
    }

    /**
     * @return int
     */
    public function getImagenInicio()
    {
        return $this->imagenInicio;
    }

    /**
     * @param int $imagenInicio
     */
    public function setImagenInicio($imagenInicio)
    {
        $this->imagenInicio = $imagenInicio;
    }

    /**
     * @return int
     */
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * @param int $inicio
     */
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;
    }

    /**
     * @return int
     */
    public function getUrlPlayStore()
    {
        return $this->urlPlayStore;
    }

    /**
     * @param int $urlPlayStore
     */
    public function setUrlPlayStore($urlPlayStore)
    {
        $this->urlPlayStore = $urlPlayStore;
    }

    /**
     * @return mixed
     */
    public function getFileCelular()
    {
        return $this->fileCelular;
    }

    /**
     * @param mixed $fileCelular
     */
    public function setFileCelular($fileCelular)
    {
        $this->fileCelular = $fileCelular;
    }

    /**
     * @return int
     */
    public function getTerminos()
    {
        return $this->terminos;
    }

    /**
     * @param int $terminos
     */
    public function setTerminos($terminos)
    {
        $this->terminos = $terminos;
    }





}
