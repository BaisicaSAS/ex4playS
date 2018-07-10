<?php

namespace Libreame\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Puntosusuario
 *
 * @ORM\Table(name="puntosusuario", indexes={@ORM\Index(name="fk_puntosusuario_usuario1_idx", columns={"puntosusuario_idusuario"}), @ORM\Index(name="fk_puntosusuario_actividadusuario1_idx", columns={"punusuario_idactiusuario"}), @ORM\Index(name="fk_puntosusuario_resenavideojuego1_idx", columns={"punusuario_resenavideojuego"}), @ORM\Index(name="fk_puntosusuario_ejemplar1_idx", columns={"punusuario_idejemplar"})})
 * @ORM\Entity
 */
class Puntosusuario
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idpuntosusuario", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idpuntosusuario;

    /**
     * @var integer
     *
     * @ORM\Column(name="inpuntaje", type="integer", nullable=false)
     */
    private $inpuntaje;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fefechapuntos", type="datetime", nullable=false)
     */
    private $fefechapuntos;

    /**
     * @var integer
     *
     * @ORM\Column(name="insumaresta", type="integer", nullable=false)
     */
    private $insumaresta = '1';

    /**
     * @var \AppBundle\Entity\Actividadusuario
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Actividadusuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="punusuario_idactiusuario", referencedColumnName="idactividadusuario")
     * })
     */
    private $punusuarioactiusuario;

    /**
     * @var \AppBundle\Entity\Ejemplar
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ejemplar")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="punusuario_idejemplar", referencedColumnName="idejemplar")
     * })
     */
    private $punusuarioejemplar;

    /**
     * @var \AppBundle\Entity\Resenavideojuego
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Resenavideojuego")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="punusuario_resenavideojuego", referencedColumnName="idresenavideojuego")
     * })
     */
    private $punusuarioResenavideojuego;

    /**
     * @var \AppBundle\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="puntosusuario_idusuario", referencedColumnName="idusuario")
     * })
     */
    private $puntosusuariousuario;
    
//*********************************
//  Getter y seter ex4play
//*********************************    
    public function getidpuntosusuario()
    {
        return $this->idpuntosusuario;
    }
    
    public function getinpuntaje()
    {
        return $this->inpuntaje;
    }
    
    public function getfefechapuntos()
    {
        return $this->fefechapuntos;
    }
    
    public function getinsumaresta()
    {
        return $this->insumaresta;
    }
    
    public function getpunusuarioactiusuario()
    {
        return $this->punusuarioactiusuario;
    }
    
    public function getpunusuarioejemplar()
    {
        return $this->punusuarioejemplar;
    }
    
    public function getpunusuarioResenavideojuego()
    {
        return $this->punusuarioResenavideojuego;
    }
    
    public function getpuntosusuariousuario()
    {
        return $this->puntosusuariousuario;
    }
    
    /* 
     * setters ex4playS
     */
   
    public function setinpuntaje($inpuntaje)
    {
        $this->inpuntaje = $inpuntaje;
        return $this;
    }
    
    public function setfefechapuntos($fefechapuntos)
    {
        $this->fefechapuntos = $fefechapuntos;
        return $this;
    }
    
    public function setinsumaresta($insumaresta)
    {
        $this->insumaresta = $insumaresta;
        return $this;
    }
    
    public function setpunusuarioactiusuario($punusuarioactiusuario)
    {
        $this->punusuarioactiusuario = $punusuarioactiusuario;
        return $this;
    }
    
    public function setpunusuarioejemplar($punusuarioejemplar)
    {
        $this->punusuarioejemplar = $punusuarioejemplar;
        return $this;
    }
    
    public function setpunusuarioResenavideojuego($punusuarioResenavideojuego)
    {
        $this->punusuarioResenavideojuego = $punusuarioResenavideojuego;
        return $this;
    }
    
    public function setpuntosusuariousuario($puntosusuariousuario)
    {
        $this->puntosusuariousuario = $puntosusuariousuario;
        return $this;
    }
    
}

