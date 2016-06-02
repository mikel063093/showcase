<?php



namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * Usuario
 *
 * @ORM\Table(name="usuario", options={"comment" = "usuarios del sistema"})
 * @ORM\Entity(repositoryClass="UsuarioRepository") 
 * @author ALEJANDRO
 */
class Usuario implements UserInterface, \Serializable{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $nombres
     *
     * @ORM\Column(name="nombres", type="string", length=120, nullable=false, options=
     * {"comment" = "Nombres del usuario"})
     */
    private $nombres;

    /**
     * @var string $apellidos
     *
     * @ORM\Column(name="apellidos", type="string", length=120, nullable=false, options=
     * {"comment" = "Apellidos del usuario"})
     */
    private $apellidos;

    /**
     * @var string $correo
     *
     * @ORM\Column(name="correo", type="string", length=120, nullable=false, options=
     * {"comment" = "Correo del usuario"})
     */
    private $correo;


    /**
     * @var string $foto
     *
     * @ORM\Column(name="foto", type="string", length=120, nullable=true, options=
     * {"comment" = "ruta de la fotografia del usuario"})
     */
    private $foto;

    /**
     * @var string $gcmId
     *
     * @ORM\Column(name="gcmId", type="string", length=120, nullable=true, options=
     * {"comment" = ""})
     */
    private $gcmId;

    /**
     * @ORM\Column(type="string", length=255, options=
     * {"comment" = "Nombre de usuario"})
     */
    protected $username;
    
    /**
     * @ORM\Column(name="password", type="string", length=255 , options=
     * {"comment" = "Clave del usuario encriptada"})
     */
    protected $password;
 
    /**
     * @ORM\Column(name="salt", type="string", length=255, options=
     * {"comment" = "Semilla para la ancriptacion de la clave"})
     */
    protected $salt;

    /**
     * 
     * @ORM\OneToMany(targetEntity="Pedido", mappedBy="usuario")
     */
    private $pedidos;

    /**
     * 
     * @ORM\OneToMany(targetEntity="Carrito", mappedBy="usuario")
     */
    private $carritos;
    /**
     * 
     * @ORM\OneToMany(targetEntity="Direccion", mappedBy="usuario")
     */
    private $direcciones;

     /**
     * 
     * @ORM\ManyToOne(targetEntity="Rol", inversedBy="usuarios")
     * @ORM\JoinColumn(name="id_rol", referencedColumnName="id")
     **/
    private $rol;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pedidos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->carritos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->direcciones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombres
     *
     * @param string $nombres
     * @return Usuario
     */
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;

        return $this;
    }

    /**
     * Get nombres
     *
     * @return string 
     */
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * Set apellidos
     *
     * @param string $apellidos
     * @return Usuario
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string 
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set correo
     *
     * @param string $correo
     * @return Usuario
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
     * Set foto
     *
     * @param string $foto
     * @return Usuario
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get foto
     *
     * @return string 
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set gcmId
     *
     * @param string $gcmId
     * @return Usuario
     */
    public function setGcmId($gcmId)
    {
        $this->gcmId = $gcmId;

        return $this;
    }

    /**
     * Get gcmId
     *
     * @return string 
     */
    public function getGcmId()
    {
        return $this->gcmId;
    }

    /**
     * Add pedidos
     *
     * @param \AppBundle\Entity\Pedido $pedidos
     * @return Usuario
     */
    public function addPedido(\AppBundle\Entity\Pedido $pedidos)
    {
        $this->pedidos[] = $pedidos;

        return $this;
    }

    /**
     * Remove pedidos
     *
     * @param \AppBundle\Entity\Pedido $pedidos
     */
    public function removePedido(\AppBundle\Entity\Pedido $pedidos)
    {
        $this->pedidos->removeElement($pedidos);
    }

    /**
     * Get pedidos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPedidos()
    {
        return $this->pedidos;
    }

    /**
     * Add carritos
     *
     * @param \AppBundle\Entity\Carrito $carritos
     * @return Usuario
     */
    public function addCarrito(\AppBundle\Entity\Carrito $carritos)
    {
        $this->carritos[] = $carritos;

        return $this;
    }

    /**
     * Remove carritos
     *
     * @param \AppBundle\Entity\Carrito $carritos
     */
    public function removeCarrito(\AppBundle\Entity\Carrito $carritos)
    {
        $this->carritos->removeElement($carritos);
    }

    /**
     * Get carritos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCarritos()
    {
        return $this->carritos;
    }

    /**
     * Add direcciones
     *
     * @param \AppBundle\Entity\Direccion $direcciones
     * @return Usuario
     */
    public function addDireccione(\AppBundle\Entity\Direccion $direcciones)
    {
        $this->direcciones[] = $direcciones;

        return $this;
    }

    /**
     * Remove direcciones
     *
     * @param \AppBundle\Entity\Direccion $direcciones
     */
    public function removeDireccione(\AppBundle\Entity\Direccion $direcciones)
    {
        $this->direcciones->removeElement($direcciones);
    }

    /**
     * Get direcciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDirecciones()
    {
        return $this->direcciones;
    }

    /**
     * Set rol
     *
     * @param \AppBundle\Entity\Rol $rol
     * @return Usuario
     */
    public function setRol(\AppBundle\Entity\Rol $rol = null)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return \AppBundle\Entity\Rol 
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }
 
    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
    
     /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
 
    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
 
    /**
     * Set salt
     *
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }
 
    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Erases the user credentials.
     */
    public function eraseCredentials() {
 
    }

    public function serialize() {
        return \json_encode(array($this->username, $this->password, $this->salt, $this->id));
    }

    public function unserialize($serialized) {
        list($this->username, $this->password, $this->salt, $this->id) = \json_decode($serialized);
    }

    public function getRoles()
    {
     
        return array($this->rol->getRole());
    }
}
