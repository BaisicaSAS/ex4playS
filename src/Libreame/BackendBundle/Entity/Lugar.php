<?php

namespace Libreame\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lugar
 *
 * @ORM\Table(name="lugar", indexes={@ORM\Index(name="fk_lugar_lugar1_idx", columns={"inlugpadre"})})
 * @ORM\Entity
 */
class Lugar
{
    /**
     * @var integer
     *
     * @ORM\Column(name="inlugar", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $inlugar;

    /**
     * @var string
     *
     * @ORM\Column(name="txlugcodigo", type="string", length=45, nullable=false)
     */
    private $txlugcodigo;

    /**
     * @var string
     *
     * @ORM\Column(name="txlugnombre", type="string", length=100, nullable=false)
     */
    private $txlugnombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="inlugelegible", type="integer", nullable=true)
     */
    private $inlugelegible = '0';

    /**
     * @var \AppBundle\Entity\Lugar
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Lugar")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="inlugpadre", referencedColumnName="inlugar")
     * })
     */
    private $inlugpadre;


}

