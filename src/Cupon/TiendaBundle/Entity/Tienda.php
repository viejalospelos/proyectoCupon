<?php
namespace Cupon\TiendaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cupon\OfertaBundle\Util\Util;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="Cupon\TiendaBundle\Entity\TiendaRepository")
 * @author Javier
 *
 */

class Tienda implements UserInterface {
	function eraseCredentials()
	{	
	}
	
	function getRoles()
	{
		return array('ROLE_TIENDA');
	}
	
	function getUsername()
	{
		return $this->getLogin();
	}
//No tenemos que añadir los métodos getSalt ni getPassword porque ya existían en la entidad

	
	
	
	/**
	 * 
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	protected $id;
	/**
	 * 
	 * @ORM\Column(type="string",length=100)
	 */
	protected $nombre;
	/**
	 * 
	 * @ORM\Column(type="string",length=100)
	 */
	protected $slug;
	/**
	 * 
	 * @ORM\Column(type="string",length=10)
	 */
	protected $login;
	/**
	 * 
	 * @ORM\Column(type="string",length=255)
	 */
	protected $password;
	/**
	 * 
	 * @ORM\Column(type="string",length=255)
	 */
	protected $salt;
	/**
	 * 
	 * @ORM\Column(type="text")
	 */
	protected $description;
	/**
	 * 
	 * @ORM\Column(type="text")
	 */
	protected $direccion;
	/**
	 * 
	 * @ORM\ManyToOne(targetEntity="Cupon\CiudadBundle\Entity\Ciudad")
	 */
	protected $ciudad;
	
	

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
     * @param integer $nombre
     *
     * @return Tienda
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        $this->slug=Util::getSlug($nombre);

        return $this;
    }

    /**
     * Get nombre
     *
     * @return integer
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set slug
     *
     * @param integer $slug
     *
     * @return Tienda
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return integer
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set login
     *
     * @param integer $login
     *
     * @return Tienda
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return integer
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param integer $password
     *
     * @return Tienda
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return integer
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param integer $salt
     *
     * @return Tienda
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return integer
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Tienda
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Tienda
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
     * Set ciudad
     *
     * @param \Cupon\CiudadBundle\Entity\Ciudad $ciudad
     *
     * @return Tienda
     */
    public function setCiudad(\Cupon\CiudadBundle\Entity\Ciudad $ciudad = null)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Get ciudad
     *
     * @return \Cupon\CiudadBundle\Entity\Ciudad
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }
    
    public function __toString(){
    	return $this->getNombre();
    }
}
