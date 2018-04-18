<?php

namespace Libreame\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sesion
 *
 * @ORM\Table(name="sesion", indexes={@ORM\Index(name="fk_sesion_usuario1_idx", columns={"sesion_idusuario"})})
 * @ORM\Entity
 */
class Sesion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="insesion", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $insesion;

    /**
     * @var string
     *
     * @ORM\Column(name="txsesnumero", type="string", length=100, nullable=false)
     */
    private $txsesnumero;

    /**
     * @var integer
     *
     * @ORM\Column(name="insesactiva", type="integer", nullable=false)
     */
    private $insesactiva = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fesesfechaini", type="datetime", nullable=false)
     */
    private $fesesfechaini;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fesesfechafin", type="datetime", nullable=true)
     */
    private $fesesfechafin;

    /**
     * @var string
     *
     * @ORM\Column(name="txipaddr", type="string", length=30, nullable=false)
     */
    private $txipaddr = '000.000.000.000';

    /**
     * @var \AppBundle\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sesion_idusuario", referencedColumnName="idusuario")
     * })
     */
    private $sesionusuario;


}

