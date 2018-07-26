<?php

namespace Libreame\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Libreame\BackendBundle\Helpers\Logica;
use Libreame\BackendBundle\Controller\GamesController;
use Libreame\BackendBundle\Repository\ManejoDataRepository;
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
     * @ORM\Column(name="txtelefono", type="string", length=45, nullable=false)
     */
    private $txtelefono = 'PENDIENTE';

    /**
     * @var string
     *
     * @ORM\Column(name="txdireccion", type="string", length=250, nullable=false)
     */
    private $txdireccion = 'PENDIENTE';

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

     /**
     * @var string
     *
     * @ORM\Column(name="txusuimagen", type="text", nullable=false)
     */
    private $txusuimagen;

    /**
     * @var string
     *
     * @ORM\Column(name="txclave", type="blob")
     */
    private $txclave;

    /**
     * @var integer
     *
     * @ORM\Column(name="inusugenero", type="integer" nullable=false)
     */
    private $inusugenero = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="feusunacimiento", type="datetime", nullable=true)
     */
    private $feusunacimiento;
    /**
     * @var float
     *
     * @ORM\Column(name="dblatitud", type="float", nullable=true)
     */
    private $dblatitud = 0;
    /**
     * @var float
     *
     * @ORM\Column(name="dblongitud", type="float", nullable=true)
     */
    private $dblongitud = 0;

    /* 
     * getters ex4playS
     */
    
    public function getIdusuario()
    {
        return $this->idusuario;
    }
    
    public function getTxnomusuario()
    {
        return $this->txnomusuario;
    }
    
    public function getTxnickname()
    {
        return $this->txnomusuario;
    }
    
    public function getTxmailusuario()
    {
        return $this->txmailusuario;
    }
    
    public function getTxtelefono()
    {
        return $this->txtelefono;
    }
    
    public function getTxdireccion()
    {
        return $this->txdireccion;
    }
    
    public function getTxclaveusuario()
    {
        return $this->txclaveusuario;
    }
    
    public function getFecreacionusuario()
    {
        return $this->fecreacionusuario;
    }
    
    public function getInusuestado()
    {
        return $this->inusuestado;
    }

    public function getTxusuvalidacion()
    {
        return $this->txusuvalidacion;
    }

    public function getUsuarioInlugar()
    {
        return $this->usuarioInlugar;
    }
    
    public function getTxusuimagen()
    {
        return $this->txusuimagen;
    }
    
    public function getTxclave()
    {
        return $this->txusuimagen;
    }
    
    public function getinusugenero()
    {
        return $this->inusugenero;
    }
    
    public function getfeusunacimiento()
    {
        return $this->feusunacimiento;
    }
    
    public function getdblatitud()
    {
        return $this->dblatitud;
    }
    
    public function getdblongitud()
    {
        return $this->dblongitud;
    }
    
    /* 
     * setters ex4playS
     */
    public function setTxnomusuario($txnomusuario)
    {
        $this->txnomusuario = $txnomusuario;

        return $this;
    }
    
    public function setTxnickname($txnickname)
    {
        $this->txnickname = $txnickname;

        return $this;
    }

    public function setTxmailusuario($txmailusuario)
    {
        $this->txmailusuario = $txmailusuario;

        return $this;
    }

    public function setTxtelefono($txtelefono)
    {
        $this->txtelefono = $txtelefono;

        return $this;
    }

    public function setTxdireccion($txdireccion)
    {
        $this->txdireccion = $txdireccion;

        return $this;
    }

    public function setTxclaveusuario($txclaveusuario)
    {
        $this->txclaveusuario = $txclaveusuario;

        return $this;
    }

    public function setFecreacionusuario($fecreacionusuario)
    {
        $this->fecreacionusuario = $fecreacionusuario;

        return $this;
    }

    public function setInusuestado($inusuestado)
    {
        $this->inusuestado = $inusuestado;

        return $this;
    }

    public function setTxusuvalidacion($txusuvalidacion)
    {
        $this->txusuvalidacion = $txusuvalidacion;

        return $this;
    }

    public function setUsuarioInlugar(\Libreame\BackendBundle\Entity\Lugar $usuarioInlugar = null)
    {
        $this->usuarioInlugar = $usuarioInlugar;

        return $this;
    }
    
    public function setTxusuimagen($txusuimagen)
    {
        $this->txusuimagen = $txusuimagen;

        return $this;
    }
    
    public function setTxclave($txclave)
    {
        $this->txclave = $txclave;

        return $this;
    }

    public function setinusugenero($inusugenero)
    {
        $this->inusugenero = $inusugenero;

        return $this;
    }

    public function setfeusunacimiento($feusunacimiento)
    {
        $this->feusunacimiento = $feusunacimiento;

        return $this;
    }

    public function setdblatitud($dblatitud)
    {
        $this->dblatitud = $dblatitud;

        return $this;
    }

    public function setdblongitud($dblongitud)
    {
        $this->dblongitud = $dblongitud;

        return $this;
    }

    function __construct(){ 
        $strBlanco = "";
        $this->txmailusuario = $strBlanco;
        $this->txnomusuario = $strBlanco;  
        $this->txnickname = $strBlanco;
        $this->txclaveusuario = $strBlanco;
        $this->usuarioInlugar = $strBlanco;
        $this->txusuvalidacion = $strBlanco;
        $this->txusuimagen = $strBlanco;
        $this->txclave = $strBlanco;
    } 
    
    //Función que crea un usuario para su registro en el sistema
    public function creaUsuario($pSolicitud, $Lugar)
    {   
        $usuario = new Usuario() ;
        try {
            setlocale (LC_TIME, "es_CO");
            $fechaReg = new \DateTime('c');
            
            $usuario->setTxmailusuario($pSolicitud->getEmail());  
            $usuario->setTxnomusuario($pSolicitud->getEmail());  
            $usuario->setTxnickname($pSolicitud->getEmail());
            $usuario->setFecreacionusuario($fechaReg);

            $usuario->setTxusuimagen('DEFAULT IMAGE URL');  
            $usuario->setUsuarioInlugar($Lugar);  
            $txusuvalidacion = Logica::generaRand(GamesController::inTamVali);
            $usuario->settxusuvalidacion($txusuvalidacion);  
            //echo "va al cifrado \n"; 
            //$usuario->setTxclave(ManejoDataRepository::fnEncrypt($pSolicitud->getClave(), $txusuvalidacion));
            //@TODO : Clave de cifrado establecida : [fyxwwk-+ieekrz2ve1pkv0ayxkimxce
            //$usuario->setTxclave(ManejoDataRepository::fnEncrypt($pSolicitud->getClave(), GamesController::txSecret));  
            //$clavebinaria = Logica::bin2text(ManejoDataRepository::fnDecrypt($pSolicitud->getClave(), GamesController::txSecret)); 
            //echo "clavebin [".$clavebinaria."]";
            $usuario->setTxclave($pSolicitud->getClave(), GamesController::txSecret);  
            //@TODO : Inhabilitarla...esta clave no se guardara despues: Se guardará una constante
            $usuario->setTxclaveusuario(ManejoDataRepository::fnDecrypt($pSolicitud->getClave(), GamesController::txSecret));  

            return $usuario;
        } catch (Exception $ex)  {    
            return $usuario;
        }
    }
    
}

