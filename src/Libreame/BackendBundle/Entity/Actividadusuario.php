<?php

namespace Libreame\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Actividadusuario
 *
 * @ORM\Table(name="actividadusuario", indexes={@ORM\Index(name="fk_actividadusuario_usuario1_idx", columns={"actusuario_idusuarioescribe"}), @ORM\Index(name="fk_actividadusuario_ejemplar1_idx", columns={"actusuario_idejemplar"}), @ORM\Index(name="fk_actividadusuario_tratoaccion1_idx", columns={"actusuario_idtrato"})})
 * @ORM\Entity
 */
class Actividadusuario
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idactividadusuario", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idactividadusuario;

    /**
     * @var \AppBundle\Entity\Ejemplar
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ejemplar")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="actusuario_idejemplar", referencedColumnName="idejemplar")
     * })
     */
    private $actusuarioejemplar;

    /**
     * @var \AppBundle\Entity\Trato
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Trato")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="actusuario_idtrato", referencedColumnName="idtrato")
     * })
     */
    private $actusuariotrato;

    /**
     * @var \AppBundle\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="actusuario_idusuarioescribe", referencedColumnName="idusuario")
     * })
     */
    private $actusuariousuarioescribe;

    /**
     * @var \AppBundle\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="actusuario_idusuariolee", referencedColumnName="idusuario")
     * })
     */
    private $actusuariousuariolee;

    /**
     * @var integer
     *
     * @ORM\Column(name="actusuario_fecha", type="datetime", nullable=false)
     */
    private $actusuariofecha;


    /**
     * @var integer
     *
     * @ORM\Column(name="actusuario_mensaje", type="string", nullable=false)
     */
    private $actusuariomensaje;


    /**
     * @var integer
     *
     * @ORM\Column(name="actusuario_tipoaccion", type="integer", nullable=false)
     */
    private $actusuariotipoaccion = '0';


    
    /***/
    /*SETTERS Y GETTERS 
    /***/
    public function getidactividadusuario()
    {
        return $this->idactividadusuario;
    }

    public function getactusuarioejemplar()
    {
        return $this->actusuarioejemplar;
    }

    public function getactusuariotrato()
    {
        return $this->actusuariotrato;
    }

    public function getactusuariousuarioescribe()
    {
        return $this->actusuariousuarioescribe;
    }

    public function getactusuariousuariolee()
    {
        return $this->actusuariousuariolee;
    }

    public function getactusuariofecha()
    {
        return $this->actusuariofecha;
    }

    public function getactusuariomensaje()
    {
        return $this->actusuariomensaje;
    }

    public function getactusuariotipoaccion()
    {
        return $this->actusuariotipoaccion;
    }

     /*setter*/

    public function setidactividadusuario($idactividadusuario)
    {
        $this->idactividadusuario = $idactividadusuario;

        return $this;
    }

    public function setactusuarioejemplar($actusuarioejemplar)
    {
        $this->actusuarioejemplar = $actusuarioejemplar;

        return $this;
    }

    public function setactusuariotrato($actusuariotrato)
    {
        $this->actusuariotrato = $actusuariotrato;

        return $this;
    }

    public function setactusuariousuarioescribe($actusuariousuarioescribe)
    {
        $this->actusuariousuarioescribe = $actusuariousuarioescribe;

        return $this;
    }

    public function setactusuariousuariolee($actusuariousuariolee)
    {
        $this->actusuariousuariolee = $actusuariousuariolee;

        return $this;
    }

    public function setactusuariofecha($actusuariofecha)
    {
        $this->actusuariofecha = $actusuariofecha;

        return $this;
    }

    public function setactusuariomensaje($actusuariomensaje)
    {
        $this->actusuariomensaje = $actusuariomensaje;

        return $this;
    }

    public function setactusuariotipoaccion($actusuariotipoaccion)
    {
        $this->actusuariotipoaccion = $actusuariotipoaccion;

        return $this;
    }



}

