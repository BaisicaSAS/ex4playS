<?php

namespace Libreame\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Videojuego
 *
 * @ORM\Table(name="videojuego", indexes={@ORM\Index(name="fk_videojuego_consola1_idx", columns={"videojuego_consola"})})
 * @ORM\Entity
 */
class Videojuego
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idvideojuego", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idvideojuego;

    /**
     * @var string
     *
     * @ORM\Column(name="txnomvideojuego", type="string", length=300, nullable=false)
     */
    private $txnomvideojuego;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="felanzamiento", type="datetime", nullable=false)
     */
    private $felanzamiento;

    /**
     * @var integer
     *
     * @ORM\Column(name="incategvideojuego", type="integer", nullable=false)
     */
    private $incategvideojuego;

    /**
     * @var \AppBundle\Entity\Consola
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Consola")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="videojuego_consola", referencedColumnName="idconsola")
     * })
     */
    private $videojuegoConsola;

    /**
     * @var string
     *
     * @ORM\Column(name="txurlinformacion", type="string", length=1000, nullable=false)
     */
    private $txurlinformacion;

    /**
     * @var string
     *
     * @ORM\Column(name="txobservaciones", type="string", length=1000, nullable=false)
     */
    private $txobservaciones;

    /**
     * @var string
     *
     * @ORM\Column(name="txgenerovideojuego", type="string", length=200, nullable=false)
     */
    private $txgenerovideojuego;

    /**
     * @var string
     *
     * @ORM\Column(name="tximagen", type="string", length=300, nullable=false)
     */
    private $tximagen;

    //getter y setter
    
    public function getidvideojuego()
    {
        return $this->idvideojuego;
    }
 
    public function gettxnomvideojuego()
    {
        return $this->txnomvideojuego;
    }

    public function getfelanzamiento()
    {
        return $this->felanzamiento;
    }
 
    public function getincategvideojuego()
    {
        return $this->incategvideojuego;
    }

    public function getvideojuegoConsola()
    {
        return $this->videojuegoConsola;
    }
 
    public function gettxurlinformacion()
    {
        return $this->txurlinformacion;
    }
 
    public function gettxobservaciones()
    {
        return $this->txobservaciones;
    }
 
    public function gettxgenerovideojuego()
    {
        return $this->txgenerovideojuego;
    }
 
    public function gettximagen()
    {
        return $this->tximagen;
    }
 
    //setter
    public function settxnomvideojuego($txnomvideojuego)
    {
        $this->txnomvideojuego = $txnomvideojuego;

        return $this;
    }
   
    public function setfelanzamiento($felanzamiento)
    {
        $this->felanzamiento = $felanzamiento;

        return $this;
    }
   
    public function setincategvideojuego($incategvideojuego)
    {
        $this->incategvideojuego = $incategvideojuego;

        return $this;
    }

    public function setvideojuegoConsola($videojuegoConsola)
    {
        $this->videojuegoConsola = $videojuegoConsola;

        return $this;
    }

    public function settxurlinformacion($txurlinformacion)
    {
        $this->txurlinformacion = $txurlinformacion;

        return $this;
    }

    public function settxobservaciones($txobservaciones)
    {
        $this->txobservaciones = $txobservaciones;

        return $this;
    }

    public function settxgenerovideojuego($txgenerovideojuego)
    {
        $this->txgenerovideojuego = $txgenerovideojuego;

        return $this;
    }

    public function settximagen($tximagen)
    {
        $this->tximagen = $tximagen;

        return $this;
    }

}

