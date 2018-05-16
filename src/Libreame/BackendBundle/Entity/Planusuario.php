<?php

namespace Libreame\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Planusuario
 *
 * @ORM\Table(name="planusuario", indexes={@ORM\Index(name="fk_planusuario_plansuscripcion1_idx", columns={"planusuario_idplan"}), @ORM\Index(name="fk_planusuario_usuario1_idx", columns={"planusuario_idusuario"})})
 * @ORM\Entity
 */
class Planusuario
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idplanusuario", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idplanusuario;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fevigencia", type="datetime", nullable=false)
     */
    private $fevigencia;

    /**
     * @var \AppBundle\Entity\Plansuscripcion
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Plansuscripcion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="planusuario_idplan", referencedColumnName="idplansuscripcion")
     * })
     */
    private $planusuarioplan;

    /**
     * @var \AppBundle\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="planusuario_idusuario", referencedColumnName="idusuario")
     * })
     */
    private $planusuariousuario;


}

