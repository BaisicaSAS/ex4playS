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


}

