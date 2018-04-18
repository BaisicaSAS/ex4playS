<?php

namespace Libreame\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Plansuscripcion
 *
 * @ORM\Table(name="plansuscripcion")
 * @ORM\Entity
 */
class Plansuscripcion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idplansuscripcion", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idplansuscripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="txnomplan", type="string", length=45, nullable=false)
     */
    private $txnomplan;

    /**
     * @var string
     *
     * @ORM\Column(name="txdescripcionplan", type="string", length=300, nullable=false)
     */
    private $txdescripcionplan;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fevigencia", type="datetime", nullable=false)
     */
    private $fevigencia;

    /**
     * @var integer
     *
     * @ORM\Column(name="ingratis", type="integer", nullable=false)
     */
    private $ingratis = '1';


}

