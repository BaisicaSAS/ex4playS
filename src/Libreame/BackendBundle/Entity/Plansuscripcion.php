<?php

namespace Libreame\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Plansuscripcion
 *
 * @ORM\Table(name="plansuscripcion")
 * @ORM\Entity
 */
class Plansuscripcion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idplansuscripcion", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idplansuscripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="txnomplan", type="string", length=45, nullable=false)
     */
    private $txnomplan;

    /**
     * @var string
     *
     * @ORM\Column(name="txdescripcionplan", type="string", length=300, nullable=false)
     */
    private $txdescripcionplan;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fevigencia", type="datetime", nullable=false)
     */
    private $fevigencia;

    /**
     * @var integer
     *
     * @ORM\Column(name="ingratis", type="integer", nullable=false)
     */
    private $ingratis = '1';
    
    /**
     * @var integer
     *
     * @ORM\Column(name="inmesesplan", type="integer", nullable=false)
     */
    private $inmesesplan = '0';
    
    /**
     * @var integer
     *
     * @ORM\Column(name="dbvalsuscripcion", type="float", nullable=false)
     */
    private $dbvalsuscripcion = '0';
    
   
    public function getidplansuscripcion()
    {
        return $this->idplansuscripcion;
    }
    
    public function gettxnomplan()
    {
        return $this->txnomplan;
    }
    
    public function gettxdescripcionplan()
    {
        return $this->txdescripcionplan;
    }
    
    public function getfevigencia()
    {
        return $this->fevigencia;
    }
    
    public function getingratis()
    {
        return $this->ingratis;
    }
    
    public function getinmesesplan()
    {
        return $this->inmesesplan;
    }
    
    public function getdbvalsuscripcion()
    {
        return $this->dbvalsuscripcion;
    }
    
    /* 
     * setters ex4playS
     */
   
    public function settxnomplan($txnomplan)
    {
        $this->txnomplan = $txnomplan;

        return $this;
    }
    
    public function settxdescripcionplan($txdescripcionplan)
    {
        $this->txdescripcionplan= $txdescripcionplan;

        return $this;
    }
    
    public function setfevigencia($fevigencia)
    {
        $this->fevigencia = $fevigencia;

        return $this;
    }
    
    public function setingratis($ingratis)
    {
        $this->ingratis = $ingratis;
        
        return $this;
    }
    
    public function setinmesesplan($inmesesplan)
    {
        $this->inmesesplan = $inmesesplan;
        
        return $this;
    }
    
    public function setdbvalsuscripcion($dbvalsuscripcion)
    {
        $this->dbvalsuscripcion= $dbvalsuscripcion;
        
       return $this;
    }
    
    }

