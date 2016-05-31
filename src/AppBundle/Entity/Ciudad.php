<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ciudad
 *
 * @ORM\Table(name="ciudad", options={"comment" = "Ciudades que se usaran en las rutas"})
 * @ORM\Entity(repositoryClass="CiudadRepository")
 */
class Ciudad
{
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
     * {"comment" = "Nombre del lugar"})
     */
    private $nombre;
    
    
    /**
     * @var Depto
     *
     * @ORM\ManyToOne(targetEntity="Depto", inversedBy="ciudades")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_depto", referencedColumnName="id")
     * })
     */
    private $depto;
    
   
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        
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
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
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
     * @return string
     */
    public function __toString()
    {
        return $this->getNombre();
    }
    
    /**
     * Set depto
     *
     * @param Entidades\Depto $depto
     */
    public function setDepto(\Entidades\Depto $depto)
    {
        $this->depto = $depto;
    }

    /**
     * Get depto
     *
     * @return Entidades\Depto $depto
     */
    public function getDepto()
    {
        return $this->depto;
    }
    
    /**
     * Add terminal
     *
     * @param Terminal $terminales
     *
     * @return $this
     */
    public function addTerminal(Terminal $terminal){
        $this->terminales[] = $terminal;

        return $this;
    }

    /**
     * Remove Terminal
     *
     * @param Terminal $terminal
     */
    public function removeTerminal(Terminal $terminal)    {
        $this->terminal->removeElement($terminal);
    }

    /**
     * Get Terminal
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTerminales(){
        return $this->terminales;
    }
    
    /**
     * Add LugarRuta
     *
     * @param LugarRuta $lugarRuta
     *
     * @return $this
     */
    public function addLugarRuta(LugarRuta $lugarRuta){
        $this->lugaresRuta[] = $lugarRuta;

        return $this;
    }

    /**
     * Remove LugarRuta
     *
     * @param Terminal $terminal
     */
    public function removeLugarRuta(LugarRuta $lugarRuta)    {
        $this->lugaresRuta->removeElement($lugarRuta);
    }

    /**
     * Get LugarRuta
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLugaresRuta(){
        return $this->lugaresRuta;
    }
    
    /**
     * Add Trayecto
     *
     * @param Trayecto $trayecto
     *
     * @return $this
     */
    public function addTrayectoOrigen(Trayecto $trayecto){
        $this->trayectosOrigen[] = $trayecto;

        return $this;
    }

    /**
     * Remove Trayecto
     *
     * @param Trayecto $trayecto
     */
    public function removeTrayectoOrigen(Trayecto $trayecto)    {
        $this->trayectosOrigen->removeElement($trayecto);
    }

    /**
     * Get Trayecto
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTrayectosOrigen(){
        return $this->trayectosOrigen;
    }
    
    /**
     * Add Trayecto
     *
     * @param Trayecto $trayecto
     *
     * @return $this
     */
    public function addTrayectoDestino(Trayecto $trayecto){
        $this->trayectosDestino[] = $trayecto;

        return $this;
    }

    /**
     * Remove Trayecto
     *
     * @param Trayecto $trayecto
     */
    public function removeTrayectoDestino(Trayecto $trayecto)    {
        $this->trayectosDestino->removeElement($trayecto);
    }

    /**
     * Get Trayecto
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTrayectosDestino(){
        return $this->trayectosDestino;
    }
    
    /**
     * Set Empresa
     *
     * @param mixed $empresa
     *
     * @return $this
     */
    public function setEmpresa($empresa = null)
    {
        $this->empresa = $empresa;
    
        return $this;
    }

    /**
     * Get Empresa
     *
     * @return Empresa
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }
    
}
