<?php

namespace Libreame\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Detalleplan
 *
 * @ORM\Table(name="detalleplan", indexes={@ORM\Index(name="fk_detalleplan_plansuscripcion1_idx", columns={"detalleplan_idplan"})})
 * @ORM\Entity
 */
class Detalleplan
{
    /**
     * @var integer
     *
     * @ORM\Column(name="iddetalleplan", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iddetalleplan;

    /**
     * @var integer
     *
     * @ORM\Column(name="innumtarifa", type="integer", nullable=false)
     */
    private $innumtarifa = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="indiastarifa", type="integer", nullable=false)
     */
    private $indiastarifa = '-1';

    /**
     * @var integer
     *
     * @ORM\Column(name="incantidadcambios", type="integer", nullable=false)
     */
    private $incantidadcambios = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="inperiodicidad", type="integer", nullable=false)
     */
    private $inperiodicidad = '0';

    /**
     * @var \AppBundle\Entity\Plansuscripcion
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Plansuscripcion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="detalleplan_idplan", referencedColumnName="idplansuscripcion")
     * })
     */
    private $detalleplanplan;


}

