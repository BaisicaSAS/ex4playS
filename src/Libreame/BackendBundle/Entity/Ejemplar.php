<?php

namespace Libreame\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ejemplar
 *
 * @ORM\Table(name="ejemplar", indexes={@ORM\Index(name="fk_ejemplar_videojuego1_idx", columns={"ejemplar_videojuego"})})
 * @ORM\Entity
 */
class Ejemplar
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idejemplar", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idejemplar;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecargue", type="datetime", nullable=false)
     */
    private $fecargue;

    /**
     * @var integer
     *
     * @ORM\Column(name="inejemplarpublicado", type="integer", nullable=false)
     */
    private $inejemplarpublicado = '0';

    /**
     * @var \AppBundle\Entity\Videojuego
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Videojuego")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ejemplar_videojuego", referencedColumnName="idvideojuego")
     * })
     */
    private $ejemplarVideojuego;
    
    //getter y setter
    
    public function getidejemplar()
    {
        return $this->idejemplar;
    }
 
    public function getfecargue()
    {
        return $this->fecargue;
    }
 
    public function getinejemplarpublicado()
    {
        return $this->inejemplarpublicado;
    }
 
    public function getejemplarVideojuego()
    {
        return $this->ejemplarVideojuego;
    }
 
    //setter
    public function setfecargue($fecargue)
    {
        $this->fecargue = $fecargue;

        return $this;
    }

    public function setinejemplarpublicado($inejemplarpublicado)
    {
        $this->inejemplarpublicado = $inejemplarpublicado;

        return $this;
    }

    public function setejemplarVideojuego($ejemplarVideojuego)
    {
        $this->ejemplarVideojuego = $ejemplarVideojuego;

        return $this;
    }


}

