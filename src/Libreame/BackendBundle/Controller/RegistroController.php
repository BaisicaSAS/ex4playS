<?php

namespace Libreame\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Libreame\BackendBundle\Repository\ManejoDataRepository;
use Libreame\BackendBundle\Helpers\Logica;
use Libreame\BackendBundle\Entity\Usuario;

/*
 * Controlador que contiene las funciones que permiten que un usuario valide su 
 * registro en el sistema, incluye el despliegue de la url, la captura de la clave,
 * la activacion del usuario y el envio del correo que indica que se activó o no, 
 * con todas las validaciones que implique
 *  
 */
class RegistroController extends Controller
{    

    private $clave;
    private $usuario;
    const pos1mail = 2;
    const pos2mail = 4;
    const pos3mail = 6;

    const pos1pat = 3;
    const pos2pat = 5;
    const pos3pat = 7;

    public function confirmarRegistroAction($id)
    {   
        try {
            error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_STRICT & ~E_DEPRECATED);
            //$objMDR = $this->get('manejodatos_repo_service');
            //$objLogica = new Logica();
            $this->descomponerDatosEntrada($id);

            //echo "regcontroller:usr ".$this->usuario;
            //echo "regcontroller:clave ".$this->clave;
            $respuesta = Logica::validarRegistroGeneradoUsuario($this->usuario, $this->clave);
            
            if ($respuesta == GamesController::inExitoso) {
                return $this->render('AppBundle:Registro:confirmarRegistro.html.twig', array('id' => $this->clave, 'usr' => $this->usuario));
            } else {
                return $this->render('AppBundle:Registro:failConfirmarRegistro.html.twig', array('usr' => $this->usuario));
            }
        }            
        catch (Exception $ex) {
            return new RESPONSE(-1500);
        }
             
    }
 
    /*
     * descomponerDatosEntrada: 
     * Obtiene los datos separados de usuario y clave
     */
    private function descomponerDatosEntrada($datos)
    {   
        $this->clave='';
        $this->usuario='';
        //Caracteres 8 * 5 * 10  Dan el patron de descubrimiento de la clave. Juan (Patron es datos 1,2,3) CAracteres de corrimiento
        //Caracteres 14 * 9 * 12  Indican la cantidad de datos del correo
        $longdatos = strlen($datos); 
        #echo "\n Long Cadena: ".$longdatos.'  ';
        #echo "\n Cadena: ".$datos.'  ';
        //Obtener el patron de ocurrencia de datos
        $patron = array(substr($datos,self::pos1pat,1),substr($datos,self::pos2pat,1),substr($datos,self::pos3pat,1));
        //Obtener la cantidad de caracteres del correo
        $caracteres = (integer) (substr($datos,self::pos1mail,1).substr($datos,self::pos2mail,1).substr($datos,self::pos3mail,1));
        #echo "\nLong Mail: ".$caracteres.'  ';
        $pat = 0;
        #echo "\nPosiciones: ";
        for ($i=0;$i<$caracteres;$i++) {
            if ($i==0) {
                $posClave[$i] = 8 + $patron[$pat];
            } else {
                $posClave[$i] = $posClave[$i-1] + $patron[$pat];
            }
            #echo $i.' : '.$posClave[$i].' - ';
            if ($pat==2) { $pat = 0; } else { $pat++; }
        }
        
        //Recupera el mail del usuario
        for ($i=0;$i<$caracteres;$i++) {
            #echo substr($datos,$posClave[$i],1);;
            $this->usuario.=substr($datos,$posClave[$i],1);
        }

        //echo "\nUSUARIO: [ ".$this->usuario.' ]';
    
        //Recupera la clave
        for ($i=0;$i<$longdatos;$i++) {
            if (!in_array($i,$posClave) and (($i<2) or ($i>7)) ) {
                //echo substr($datos,$i,1);
                $this->clave.=substr($datos,$i,1);
            } //else { echo "\nNo hace parte: ".$i.' - '. substr($datos,$i,1).' ||| ';}
        }
        //echo "  \nCLAVE: [ ".$this->clave.' ]';
    }
    
}
