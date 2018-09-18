<?php

namespace Libreame\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Detalleplan
 *
 * @ORM\Table(name="detalleplan", indexes={@ORM\Index(name="fk_detalleplan_plansuscripcion1_idx", columns={"detalleplan_idplan"})})
 * @ORM\Entity
 */
class Detalleplan
{
    /**
     * @var integer
     *
     * @ORM\Column(name="iddetalleplan", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iddetalleplan;

    /**
     * @var integer
     *
     * @ORM\Column(name="innumtarifa", type="integer", nullable=false)
     */
    private $innumtarifa = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="indiastarifa", type="integer", nullable=false)
     */
    private $indiastarifa = '-1';

    /**
     * @var integer
     *
     * @ORM\Column(name="incantidadcambios", type="integer", nullable=false)
     */
    private $incantidadcambios = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="inperiodicidad", type="integer", nullable=false)
     */
    private $inperiodicidad = '0';

    /**
     * @var \AppBundle\Entity\Plansuscripcion
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Plansuscripcion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="detalleplan_idplan", referencedColumnName="idplansuscripcion")
     * })
     */
    private $detalleplanplan;

    /**
     * @var integer
     *
     * @ORM\Column(name="invjcredito", type="integer", nullable=false)
     */
    private $invjcredito = '-1';
    
    /**
     * @var integer
     *
     * @ORM\Column(name="incatjuegoscredito", type="integer", nullable=false)
     */
    private $incatjuegoscredito = '0';
    
    
    /* 
     * getters ex4playS
    */
    public function getiddetalleplan()
    {
        return $this->iddetalleplan;
    }
    
    public function getinnumtarifa()
    {
        return $this->innumtarifa;
    }
    
    public function getindiastarifa()
    {
        return $this->indiastarifa;
    }
    
    public function getincantidadcambios()
    {
        return $this->incantidadcambios;
    }
    
    public function getinperiodicidad()
    {
        return $this->inperiodicidad;
    }
    
    public function getdetalleplanplan()
    {
        return $this->detalleplanplan;
    }
    
    public function getinvjcredito()
    {
       //if ($this->invjcredito == NULL) $this->invjcredito = 0;
        return $this->invjcredito;
    }
    
    public function getincatjuegoscredito()
    {
        return $this->incatjuegoscredito;
    }
    
    /* 
     * setters ex4playS
    */
   
    public function setinnumtarifa($innumtarifa)
    {
        $this->innumtarifa = $innumtarifa;
        return $this;
    }
    
    public function setindiastarifa($indiastarifa)
    {
        $this->indiastarifa = $indiastarifa;
        return $this;
    }
    
    public function setincantidadcambios($incantidadcambios)
    {
        $this->incantidadcambios = $incantidadcambios;
        return $this;
    }
    
    public function setinperiodicidad($inperiodicidad)
    {
        $this->inperiodicidad = $inperiodicidad;
        return $this;
    }
    
    public function setdetalleplanplan($detalleplanplan)
    {
        $this->detalleplanplan = $detalleplanplan;
        return $this;
    }
    
    public function setinvjcredito($invjcredito)
    {
        $this->invjcredito = $invjcredito;
        return $this;
    }
    
    public function setincatjuegoscredito($incatjuegoscredito)
    {
        $this->incatjuegoscredito = $incatjuegoscredito;
        return $this;
    }
    
}

