<?php

namespace Libreame\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Actividadusuario
 *
 * @ORM\Table(name="actividadusuario", indexes={@ORM\Index(name="fk_actividadusuario_usuario1_idx", columns={"actusuario_idusuarioescribe"}), @ORM\Index(name="fk_actividadusuario_ejemplar1_idx", columns={"actusuario_idejemplar"}), @ORM\Index(name="fk_actividadusuario_tratoaccion1_idx", columns={"actusuario_idtrato"})})
 * @ORM\Entity
 */
class Actividadusuario
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idactividadusuario", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idactividadusuario;

    /**
     * @var \AppBundle\Entity\Ejemplar
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ejemplar")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="actusuario_idejemplar", referencedColumnName="idejemplar")
     * })
     */
    private $actusuarioejemplar;

    /**
     * @var \AppBundle\Entity\Trato
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Trato")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="actusuario_idtrato", referencedColumnName="idtrato")
     * })
     */
    private $actusuariotrato;

    /**
     * @var \AppBundle\Entity\Usuario
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="actusuario_idusuarioescribe", referencedColumnName="idusuario")
     * })
     */
    private $actusuariousuarioescribe;


}

