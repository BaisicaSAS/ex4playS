<?php

namespace Libreame\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Consola
 *
 * @ORM\Table(name="consola", indexes={@ORM\Index(name="fk_consola_fabricante_idx", columns={"consola_fabricante"})})
 * @ORM\Entity
 */
class Consola
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idconsola", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idconsola;

    /**
     * @var string
     *
     * @ORM\Column(name="txnombreconsola", type="string", length=200, nullable=false)
     */
    private $txnombreconsola;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="felanzamiento", type="datetime", nullable=false)
     */
    private $felanzamiento;

    /**
     * @var \AppBundle\Entity\Fabricante
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Fabricante")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="consola_fabricante", referencedColumnName="idfabricante")
     * })
     */
    private $consolaFabricante;

    //getter y setter
    public function getidconsola()
    {
        return $this->idconsola;
    }
 
    public function gettxnombreconsola()
    {
        return $this->txnombreconsola;
    }
 
    public function getfelanzamiento()
    {
        return $this->felanzamiento;
    }
 
    public function getconsolafabricante()
    {
        return $this->consolaFabricante;
    }
 
    //setter
    public function settxnombreconsola($txnombreconsola)
    {
        $this->txnombreconsola = $txnombreconsola;

        return $this;
    }

    public function setfelanzamiento($felanzamiento)
    {
        $this->felanzamiento = $felanzamiento;

        return $this;
    }

    public function setconsolafabricante($consolaFabricante)
    {
        $this->consolaFabricante = $consolaFabricante;

        return $this;
    }

    
}

