<?php

namespace Libreame\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Resenavideojuego
 *
 * @ORM\Table(name="resenavideojuego", indexes={@ORM\Index(name="fk_resenavideojuego_videojuego1_idx", columns={"resena_videojuego"}), @ORM\Index(name="fk_resenavideojuego_usuario1_idx", columns={"resena_usuariopublica"})})
 * @ORM\Entity
 */
class Resenavideojuego
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idresenavideojuego", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idresenavideojuego;

    /**
     * @var integer
     *
     * @ORM\Column(name="intipocontenido", type="integer", nullable=false)
     */
    private $intipocontenido = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="txcontenido", type="string", length=2000, nullable=false)
     */
    private $txcontenido;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fepublica", type="datetime", nullable=true)
     */
    private $fepublica;

    /**
     * @var \AppBundle\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="resena_usuariopublica", referencedColumnName="idusuario")
     * })
     */
    private $resenaUsuariopublica;

    /**
     * @var \AppBundle\Entity\Videojuego
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Videojuego")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="resena_videojuego", referencedColumnName="idvideojuego")
     * })
     */
    private $resenaVideojuego;


}

