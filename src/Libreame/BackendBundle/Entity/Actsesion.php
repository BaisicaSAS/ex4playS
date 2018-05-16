<?php

namespace Libreame\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Actsesion
 *
 * @ORM\Table(name="actsesion", indexes={@ORM\Index(name="fk_actsesion_sesion1_idx", columns={"actsesion_insesion"})})
 * @ORM\Entity
 */
class Actsesion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="inactsesion", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $inactsesion;

    /**
     * @var integer
     *
     * @ORM\Column(name="inactaccion", type="integer", nullable=false)
     */
    private $inactaccion = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="txactmensaje", type="string", length=500, nullable=false)
     */
    private $txactmensaje;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="feactfecha", type="datetime", nullable=false)
     */
    private $feactfecha;

    /**
     * @var integer
     *
     * @ORM\Column(name="inactfinalizada", type="integer", nullable=false)
     */
    private $inactfinalizada = '0';

    /**
     * @var \AppBundle\Entity\Sesion
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Sesion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="actsesion_insesion", referencedColumnName="insesion")
     * })
     */
    private $actsesionInsesion;


    /***/
    /*SETTERS Y GETTERS 
    /***/
    public function getinactsesion()
    {
        return $this->inactsesion;
    }

    public function getinactaccion()
    {
        return $this->inactaccion;
    }

    public function gettxactmensaje()
    {
        return $this->txactmensaje;
    }
    
    public function getfeactfecha()
    {
        return $this->feactfecha;
    }
    
    public function getinactfinalizada()
    {
        return $this->inactfinalizada;
    }
    
    public function getactsesionInsesion()
    {
        return $this->actsesionInsesion;
    }

 /*setter*/

    public function setinactsesion($inactsesion)
    {
        $this->inactsesion = $inactsesion;

        return $this;
    }

    public function setinactaccion($inactaccion)
    {
        $this->inactaccion = $inactaccion;

        return $this;
    }

    public function settxactmensaje($txactmensaje)
    {
        $this->txactmensaje = $txactmensaje;

        return $this;
    }
    
    public function setfeactfecha($feactfecha)
    {
        $this->feactfecha = $feactfecha;

        return $this;
    }
    
    public function setinactfinalizada($inactfinalizada)
    {
        $this->inactfinalizada = $inactfinalizada;
        
        return $this;
    }
    
    public function setactsesionInsesion($actsesionInsesion)
    {
        $this->actsesionInsesion = $actsesionInsesion;
        
        return $this;
    }

    
}

