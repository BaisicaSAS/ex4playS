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


}

