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


}

