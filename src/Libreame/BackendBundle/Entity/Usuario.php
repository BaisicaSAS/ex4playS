<?php

namespace Libreame\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario", indexes={@ORM\Index(name="fk_usuario_lugar1_idx", columns={"usuario_inlugar"})})
 * @ORM\Entity
 */
class Usuario
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idusuario", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idusuario;

    /**
     * @var string
     *
     * @ORM\Column(name="txnomusuario", type="string", length=20, nullable=false)
     */
    private $txnomusuario;

    /**
     * @var string
     *
     * @ORM\Column(name="txnickname", type="string", length=45, nullable=false)
     */
    private $txnickname;

    /**
     * @var string
     *
     * @ORM\Column(name="txmailusuario", type="string", length=120, nullable=false)
     */
    private $txmailusuario;

    /**
     * @var string
     *
     * @ORM\Column(name="txclaveusuario", type="string", length=255, nullable=false)
     */
    private $txclaveusuario;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecreacionusuario", type="datetime", nullable=false)
     */
    private $fecreacionusuario;

    /**
     * @var integer
     *
     * @ORM\Column(name="inusuestado", type="integer", nullable=false)
     */
    private $inusuestado = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="txusuvalidacion", type="string", length=300, nullable=true)
     */
    private $txusuvalidacion;

    /**
     * @var \AppBundle\Entity\Lugar
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Lugar")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_inlugar", referencedColumnName="inlugar")
     * })
     */
    private $usuarioInlugar;

    
    function __construct(){ 
        $strBlanco = "";
        $this->txmailusuario = $strBlanco;
        $this->txnomusuario = $strBlanco;  
        $this->txnickname = $strBlanco;
        $this->txclaveusuario = $strBlanco;
        $this->usuarioInlugar = $strBlanco;
        $this->txusuvalidacion = $strBlanco;
    } 
    
    //Función que crea un usuario para su registro en el sistema
    public function creaUsuario($pSolicitud, $Lugar)
    {   
        $usuario = new Usuario() ;
        try {
            setlocale (LC_TIME, "es_CO");
            $fechaReg = new \DateTime('c');
            
            $usuario->se($pSolicitud->getEmail());  
            $usuario->settxusunombre($pSolicitud->getEmail());  
            $usuario->settxusunommostrar($pSolicitud->getEmail());
            $usuario->setFefecregistro($fechaReg);
            $usuario->setFeusuultingreso($fechaReg);
            if (trim($pSolicitud->getTelefono()) == ""){
                $usuario->settxusutelefono($pSolicitud->getEmail());  
            } else {
                $usuario->settxusutelefono($pSolicitud->getTelefono());  
            }
            $usuario->settxusuclave($pSolicitud->getClave());  
            $usuario->settxusuimagen('DEFAULT IMAGE URL');  
            $usuario->setinusulugar($Lugar);  
            $usuario->settxusuvalidacion(Logica::generaRand(AccesoController::inTamVali));  

            return $usuario;
        } catch (Exception $ex)  {    
            return $usuario;
        }
    }
    
}

