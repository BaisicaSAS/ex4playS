<?php

namespace Libreame\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ejemplarusuario
 *
 * @ORM\Table(name="ejemplarusuario", indexes={@ORM\Index(name="fk_ejemplarusuario_usuario1_idx", columns={"ejemplarusuario_idusuario"}), @ORM\Index(name="fk_ejemplarusuario_ejemplar1_idx", columns={"ejemplarusuario_idejemplar"})})
 * @ORM\Entity
 */
class Ejemplarusuario
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idejemplarusuario", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idejemplarusuario;

    /**
     * @var integer
     *
     * @ORM\Column(name="invigente", type="integer", nullable=true)
     */
    private $invigente = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="feduenodesde", type="datetime", nullable=false)
     */
    private $feduenodesde;

    /**
     * @var integer
     *
     * @ORM\Column(name="inpublicado", type="integer", nullable=false)
     */
    private $inpublicado = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="inbloqueado", type="integer", nullable=false)
     */
    private $inbloqueado = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fepublicacion", type="datetime", nullable=true)
     */
    private $fepublicacion;

    /**
     * @var \AppBundle\Entity\Ejemplar
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ejemplar")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ejemplarusuario_idejemplar", referencedColumnName="idejemplar")
     * })
     */
    private $ejemplarusuarioejemplar;

    /**
     * @var \AppBundle\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ejemplarusuario_idusuario", referencedColumnName="idusuario")
     * })
     */
    private $ejemplarusuariousuario;
    
    ///getter y setter
    
    public function getidejemplarusuario()
    {
        return $this->inlugpadre;
    }
 
    public function getinvigente()
    {
        return $this->invigente;
    }

    public function getfeduenodesde()
    {
        return $this->feduenodesde;
    }
 
    public function getinpublicado()
    {
        return $this->inpublicado;
    }

    public function getinbloqueado()
    {
        return $this->inbloqueado;
    }

    public function getfepublicacion()
    {
        return $this->fepublicacion;
    }
 
    public function getejemplarusuarioejemplar()
    {
        return $this->ejemplarusuarioejemplar;
    }
 
    public function getejemplarusuariousuario()
    {
        return $this->ejemplarusuariousuario;
    }
 
    //setter
    public function setinvigente($invigente)
    {
        $this->invigente = $invigente;

        return $this;
    }

    public function setfeduenodesde($feduenodesde)
    {
        $this->feduenodesde = $feduenodesde;

        return $this;
    }

    public function setinpublicado($inpublicado)
    {
        $this->inpublicado = $inpublicado;

        return $this;
    }

    public function setinbloqueado($inbloqueado)
    {
        $this->inbloqueado = $inbloqueado;

        return $this;
    }

    public function setfepublicacion($fepublicacion)
    {
        $this->fepublicacion = $fepublicacion;

        return $this;
    }

    public function setejemplarusuarioejemplar($ejemplarusuarioejemplar)
    {
        $this->ejemplarusuarioejemplar = $ejemplarusuarioejemplar;

        return $this;
    }

    public function setejemplarusuariousuario($ejemplarusuariousuario)
    {
        $this->ejemplarusuariousuario = $ejemplarusuariousuario;

        return $this;
    }



}

