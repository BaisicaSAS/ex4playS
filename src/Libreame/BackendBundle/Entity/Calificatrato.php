<?php

namespace Libreame\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Calificatrato
 *
 * @ORM\Table(name="calificatrato", indexes={@ORM\Index(name="fk_calificatrato_tratoaccion1_idx", columns={"calificatrato_idtrato"}), @ORM\Index(name="fk_calificatrato_usuario1_idx", columns={"calificatr_usrcalifica"}), @ORM\Index(name="fk_calificatrato_usuario2_idx", columns={"calificatr_usrcalificado"})})
 * @ORM\Entity
 */
class Calificatrato
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idcalificatrato", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcalificatrato;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecalifica", type="datetime", nullable=false)
     */
    private $fecalifica;

    /**
     * @var string
     *
     * @ORM\Column(name="idtrato", type="string", length=45, nullable=false)
     */
    private $idtrato;

    /**
     * @var integer
     *
     * @ORM\Column(name="incalificacion", type="integer", nullable=false)
     */
    private $incalificacion;

    /**
     * @var string
     *
     * @ORM\Column(name="txobservacioncalifica", type="string", length=120, nullable=false)
     */
    private $txobservacioncalifica;

    /**
     * @var \AppBundle\Entity\Trato
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Trato")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="calificatrato_idtrato", referencedColumnName="idtrato")
     * })
     */
    private $calificatratotrato;

    /**
     * @var \AppBundle\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="calificatr_usrcalifica", referencedColumnName="idusuario")
     * })
     */
    private $calificatrUsrcalifica;

    /**
     * @var \AppBundle\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="calificatr_usrcalificado", referencedColumnName="idusuario")
     * })
     */
    private $calificatrUsrcalificado;


}

