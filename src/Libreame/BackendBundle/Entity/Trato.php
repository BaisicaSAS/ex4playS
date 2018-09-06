<?php

namespace Libreame\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trato
 *
 * @ORM\Table(name="trato", indexes={@ORM\Index(name="fk_tratoaccion_usuario1_idx", columns={"trato_idusrdueno"}), @ORM\Index(name="fk_tratoaccion_usuario2_idx", columns={"trato_idusrsolicita"}), @ORM\Index(name="fk_tratoaccion_ejemplar1_idx", columns={"trato_idejemplar"})})
 * @ORM\Entity
 */
class Trato
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idtrato", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idtrato;

    /**
     * @var string
     *
     * @ORM\Column(name="idtratotexto", type="string", length=45, nullable=false)
     */
    private $idtratotexto;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fefechatrato", type="datetime", nullable=false)
     */
    private $fefechatrato;

    /**
     * @var integer
     *
     * @ORM\Column(name="inestadotrato", type="integer", nullable=true)
     */
    private $inestadotrato = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="inestadoentrega", type="integer", nullable=true)
     */
    private $inestadoentrega = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="inestadocancela", type="integer", nullable=true)
     */
    private $inestadocancela = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="inestadocalifica", type="integer", nullable=true)
     */
    private $inestadocalifica = '0';

    /**
     * @var \AppBundle\Entity\Ejemplar
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ejemplar")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="trato_idejemplar", referencedColumnName="idejemplar")
     * })
     */
    private $tratoejemplar;

    /**
     * @var \AppBundle\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="trato_idusrdueno", referencedColumnName="idusuario")
     * })
     */
    private $tratousrdueno;

    /**
     * @var \AppBundle\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="trato_idusrsolicita", referencedColumnName="idusuario")
     * })
     */
    private $tratousrsolicita;
    /**
     * @var integer
     *
     * @ORM\Column(name="intrato_acciondueno", type="integer", nullable=false)
     */
    private $intratoacciondueno = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="intrato_accionsolicitante", type="integer", nullable=false)
     */
    private $intratoaccionsolicitante = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="inestadocalifica", type="integer", nullable=true)
     */
    private $inestadocalifica = '0';


    //getter y setter
    public function getidtrato()
    {
        return $this->idtrato;
    }
 
    public function getidtratotexto()
    {
        return $this->idtratotexto;
    }
 
    public function getinestadotrato()
    {
        return $this->inestadotrato;
    }
 
    public function getinestadoentrega()
    {
        return $this->inestadoentrega;
    }
 
    public function getinestadocalifica()
    {
        return $this->inestadocalifica;
    }
 
    public function getinestadocancela()
    {
        return $this->inestadocancela;
    }
 
    public function gettratoejemplar()
    {
        return $this->tratoejemplar;
    }
 
    public function gettratousrdueno()
    {
        return $this->tratousrdueno;
    }
 
    public function gettratousrsolicita()
    {
        return $this->tratousrsolicita;
    }
 
    public function getfefechatrato()
    {
        return $this->fefechatrato;
    }
 
    public function getintratoacciondueno()
    {
        return $this->intratoacciondueno;
    }
 
    public function getintratoaccionsolicitante()
    {
        return $this->intratoaccionsolicitante;
    }
 
 
    //setter
    public function setidtratotexto($idtratotexto)
    {
        $this->idtratotexto = $idtratotexto;

        return $this;
    }

    public function setinestadotrato($inestadotrato)
    {
        $this->inestadotrato = $inestadotrato;

        return $this;
    }

    public function setinestadoentrega($inestadoentrega)
    {
        $this->inestadoentrega = $inestadoentrega;

        return $this;
    }

    public function setinestadocancela($inestadocancela)
    {
        $this->inestadocancela = $inestadocancela;

        return $this;
    }

    public function setinestadocalifica($inestadocalifica)
    {
        $this->inestadocalifica = $inestadocalifica;

        return $this;
    }

    public function settratoejemplar($tratoejemplar)
    {
        $this->tratoejemplar = $tratoejemplar;

        return $this;
    }

    public function settratousrdueno($tratousrdueno)
    {
        $this->tratousrdueno = $tratousrdueno;

        return $this;
    }

    public function settratousrsolicita($tratousrsolicita)
    {
        $this->tratousrsolicita = $tratousrsolicita;

        return $this;
    }

    public function setfefechatrato($fefechatrato)
    {
        $this->fefechatrato = $fefechatrato;

        return $this;
    }

    public function setintratoacciondueno($intratoacciondueno)
    {
        $this->intratoacciondueno = $intratoacciondueno;

        return $this;
    }

    public function setintratoaccionsolicitante($intratoaccionsolicitante)
    {
        $this->intratoaccionsolicitante = $intratoaccionsolicitante;

        return $this;
    }



    
}

