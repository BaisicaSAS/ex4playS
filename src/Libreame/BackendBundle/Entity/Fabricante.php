<?php

namespace Libreame\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fabricante
 *
 * @ORM\Table(name="fabricante")
 * @ORM\Entity
 */
class Fabricante
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idfabricante", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idfabricante;

    /**
     * @var string
     *
     * @ORM\Column(name="txnomfabricante", type="string", length=120, nullable=false)
     */
    private $txnomfabricante;

    /**
     * @var string
     *
     * @ORM\Column(name="txpaisfabricante", type="string", length=45, nullable=false)
     */
    private $txpaisfabricante;

    //getter y setter
    
    public function getidfabricante()
    {
        return $this->idfabricante;
    }
 
    public function gettxnomfabricante()
    {
        return $this->txnomfabricante;
    }
 
    public function gettxpaisfabricante()
    {
        return $this->txpaisfabricante;
    }
 
    //setter
    public function settxnomfabricante($txnomfabricante)
    {
        $this->txnomfabricante = $txnomfabricante;

        return $this;
    }

    public function settxpaisfabricante($txpaisfabricante)
    {
        $this->txpaisfabricante = $txpaisfabricante;

        return $this;
    }


}

