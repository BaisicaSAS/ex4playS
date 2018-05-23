<?php

namespace Libreame\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Planusuario
 *
 * @ORM\Table(name="planusuario", indexes={@ORM\Index(name="fk_planusuario_plansuscripcion1_idx", columns={"planusuario_idplan"}), @ORM\Index(name="fk_planusuario_usuario1_idx", columns={"planusuario_idusuario"})})
 * @ORM\Entity
 */
class Planusuario
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idplanusuario", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idplanusuario;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fevigencia", type="datetime", nullable=false)
     */
    private $fevigencia;

    /**
     * @var \AppBundle\Entity\Plansuscripcion
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Plansuscripcion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="planusuario_idplan", referencedColumnName="idplansuscripcion")
     * })
     */
    private $planusuarioplan;

    /**
     * @var \AppBundle\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="planusuario_idusuario", referencedColumnName="idusuario")
     * })
     */
    private $planusuariousuario;

/**
     * @var integer
     *
     * @ORM\Column(name="dbvalsuscripcion", type="float", nullable=false)
     */
    private $dbvalsuscripcion = '0';
    
    ///getter y setter
    
    public function getidplanusuario()
    {
        return $this->idplanusuario;
    }
    
    public function getfevigencia()
    {
        return $this->fevigencia;
    }
    
    public function getplanusuarioplan()
    {
        return $this->planusuarioplan;
    }
    
    public function getplanusuariousuario()
    {
        return $this->planusuariousuario;
    }
    
    public function getdbvalsuscripcion()
    {
        return $this->dbvalsuscripcion;
    }
    
    /* 
     * setters ex4playS
     */
    public function setfevigencia($fevigencia)
    {
        $this->fevigencia = $fevigencia;

        return $this;
    }
    
    public function setplanusuarioplan($planusuarioplan)
    {
        $this->planusuarioplan = $planusuarioplan;

        return $this;
    }
    
    public function setplanusuariousuario($planusuariousuario)
    {
        $this->planusuariousuario = $planusuariousuario;

        return $this;
    }
    
    public function setdbvalsuscripcion($dbvalsuscripcion)
    {
        $this->dbvalsuscripcion = $dbvalsuscripcion;

        return $this;
    }
    
}

