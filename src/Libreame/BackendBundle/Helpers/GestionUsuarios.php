<?php


namespace Libreame\BackendBundle\Helpers;

use Libreame\BackendBundle\Controller\GamesController;
use Libreame\BackendBundle\Repository\ManejoDataRepository;

use Libreame\BackendBundle\Entity\Usuario;
use Libreame\BackendBundle\Entity\Sesion;
use Libreame\BackendBundle\Entity\Lugar;
use Libreame\BackendBundle\Entity\LbMensajes;
use Libreame\BackendBundle\Entity\LbCalificausuarios;
/**
 * Description of Gestion Usuarios
 *
 * @author mramirez
 */
class GestionUsuarios {
    
    /* ObtenerParametros 
     * Retorna la información del usuario que se encuentra logueado, para visualización
     * ex4playS : Cambios al modelo y $em
     */

    public function obtenerParametros($psolicitud, $em)
    {   
        /*setlocale (LC_TIME, "es_CO");
        $fecha = new \DateTime;*/
        $respuesta = new Respuesta();
        
        $usuario = new Usuario();
        $sesion = new Sesion();
        //$califica = new LbCalificausuarios();
        try {
            //Valida que la sesión corresponda y se encuentre activa
            $respSesionVali=ManejoDataRepository::validaSesionUsuario($psolicitud, $em);
            //echo "<script>alert(' obtenerParametros :: Validez de sesion ".$respSesionVali." ')</script>";
            if ($respSesionVali==GamesController::inULogged) 
            {    
                //Busca el usuario 
                $usuario = ManejoDataRepository::getUsuarioByEmail($psolicitud->getEmail(), $em);
                if ($usuario != NULL) 
                {
                    //$calificaciones = ManejoDataRepository::getCalificaUsuarioRecibidas($usuario);
                    //echo "<script>alert('RESP cali ".count($califica)." ')</script>";

                    $respuesta->setRespuesta(GamesController::inExitoso);

                    //Ingresa el usuario en el arreglo de la Clase respuesta
                    //echo "<script>alert('ALEX ')</script>";
                    $respuesta->setArrUsuarios($usuario);
                    //echo "<script>alert('ALEX ".$respuesta->RespUsuarios[0]->getTxusunombre()." ')</script>";
                    
                    /*$arrCalifica = array();
                    foreach ($calificaciones as $califica) {
                       $arrCalifica[] = array("idcalifica"=>$califica->getIncalificacion(),
                                            "idusrcalif" => $califica->getIncalusucalifica()->getInusuario(),
                                            "nomusrcalif" => $califica->getIncalusucalifica()->getTxusunommostrar(),
                                            "incalificacion" => $califica->getIncalcalificacion(),
                                            "comentario" => $califica->getTxcalobservacion(),
                                            "fecha" => $califica->getfeFecha()->format('d/m/Y H:i:s'));
                    }
                    */
                    //$respuesta->setArrCalificaciones($arrCalifica);
                    //$respuesta->setArrGrupos($grupos);
                    
                } else {
                    $usuario = new Usuario();
                    $respuesta->setRespuesta(GamesController::inMenNoEx);
                    $respuesta->setArrUsuarios($usuario);
                }
            } else {
                $usuario = new Usuario();
                $respuesta->setRespuesta($respSesionVali);
                $respuesta->setArrUsuarios($usuario);
            }
        } catch (Exception $ex) {
            $respuesta->setRespuesta(GamesController::inPlatCai);
        } finally {
            return Logica::generaRespuesta($respuesta, $psolicitud, $usuario, $em);
        }
    }
    
    /* recuperarMensajes 
     * Retorna la información de los mensajes del usuario
     */
    
    public function recuperarMensajes($psolicitud)
    {
        /*setlocale (LC_TIME, "es_CO");
        $fecha = new \DateTime;*/
        $mensaje = new LbMensajes();
        $respuesta = new Respuesta();
        $objLogica = $this->get('logica_service');
        $usuario = new LbUsuarios();
        try {
            //Valida que la sesión corresponda y se encuentre activa
            $respSesionVali=ManejoDataRepository::validaSesionUsuario($psolicitud);
           //echo "<script>alert(' obtenerParametros :: Validez de sesion ".$respSesionVali." ')</script>";
            if ($respSesionVali==AccesoController::inULogged) 
            {    
                //Busca el usuario 
                $usuario = ManejoDataRepository::getUsuarioByEmail($psolicitud->getEmail());
                //echo "[".$usuario->getTxusuemail()."]";
                $mensaje = ManejoDataRepository::getMensajesUsuario($usuario);

                //SE INACTIVA PORQUE PUEDE GENERAR UNA GRAN CANTIDAD DE REGISTROS EN UNA SOLA SESION
                //Busca y recupera el objeto de la sesion:: 
                //$sesion = ManejoDataRepository::recuperaSesionUsuario($usuario,$psolicitud);
                //Guarda la actividad de la sesion:: 
                //ManejoDataRepository::generaActSesion($sesion,AccesoController::inDatoUno,"Datos de usuario ".$psolicitud->getEmail()." recuperados con éxito",$psolicitud->getAccion(),$fecha,$fecha);
                //echo "<script>alert('Generó actividad de sesion ')</script>";
                
                $respuesta->setRespuesta(AccesoController::inExitoso);
            } else {
                $respuesta->setRespuesta($respSesionVali);
            }
        } catch (Exception $ex) {
            $respuesta->setRespuesta(AccesoController::inPlatCai);
        } finally {
            return $objLogica::generaRespuesta($respuesta, $psolicitud, $mensaje);
        }
    }
    
    /* marcarMensajes 
     * Marca un mensaje como leído o no leído según el usuario lo indique
     */

    public function marcarMensajes(Solicitud $psolicitud)
    {   
        /*setlocale (LC_TIME, "es_CO");
        $fecha = new \DateTime;*/
        $respuesta = new Respuesta();
        $objLogica = $this->get('logica_service');
        try {
            //Valida que la sesión corresponda y se encuentre activa
            $respSesionVali=  ManejoDataRepository::validaSesionUsuario($psolicitud);
            //echo "<script>alert(' marcarMensajes :: Validez de sesion ".$respSesionVali." ')</script>";
            if ($respSesionVali==AccesoController::inULogged) 
            {    
                //Genera la oferta para el ejemplar
                $marca = ManejoDataRepository::setMarcaMensaje($psolicitud);
                if ($marca == AccesoController::inMenNoEx)
                    $respuesta->setRespuesta(AccesoController::inMenNoEx);
                else
                    $respuesta->setRespuesta($respSesionVali);
                
                return $objLogica::generaRespuesta($respuesta, $psolicitud, NULL);
            } else {
                $respuesta->setRespuesta($respSesionVali);
                return $objLogica::generaRespuesta($respuesta, $psolicitud, NULL);
            }
        } catch (Exception $ex) {
            $respuesta->setRespuesta(AccesoController::inPlatCai);
            return $objLogica::generaRespuesta($respuesta, $psolicitud, NULL);
        }
       
    }
    
    public function verUsuarioOtro($psolicitud)
    {   
        /*setlocale (LC_TIME, "es_CO");
        $fecha = new \DateTime;*/
        $respuesta = new Respuesta();
        $objLogica = $this->get('logica_service');
        $usuario = new LbUsuarios();
        $sesion = new LbSesiones();
        $califica = new LbCalificausuarios();
        try {
            //Valida que la sesión corresponda y se encuentre activa
            $respSesionVali=ManejoDataRepository::validaSesionUsuario($psolicitud);
           //echo "<script>alert(' obtenerParametros :: Validez de sesion ".$respSesionVali." ')</script>";
            if ($respSesionVali==AccesoController::inULogged) 
            {    
                //Busca el usuario 
                $usuario = ManejoDataRepository::getUsuarioById($psolicitud->getIdusuariover());
                if ($usuario != NULL)
                {
                    $califica = ManejoDataRepository::getCalificaUsuarioRecibidas($usuario);
                    //echo "<script>alert('RESP cali ".count($califica)." ')</script>";
                    //echo "<script>alert('La sesion es ".$usuario->getTxusuemail()."')</script>";

                    //SE INACTIVA PORQUE PUEDE GENERAR UNA GRAN CANTIDAD DE REGISTROS EN UNA SOLA SESION
                    //Busca y recupera el objeto de la sesion:: 
                    //$sesion = ManejoDataRepository::recuperaSesionUsuario($usuario,$psolicitud);
                    //Guarda la actividad de la sesion:: 
                    //ManejoDataRepository::generaActSesion($sesion,AccesoController::inDatoUno,"Datos de usuario ".$psolicitud->getEmail()." recuperados con éxito",$psolicitud->getAccion(),$fecha,$fecha);
                    //echo "<script>alert('Generó actividad de sesion ')</script>";

                    $respuesta->setRespuesta(AccesoController::inExitoso);

                    //echo "<script>alert('2 Validez de sesion ".$respuesta." ')</script>";
                    //Ingresa el usuario en el arreglo de la Clase respuesta
                    //echo "<script>alert('ALEX ')</script>";
                    $respuesta->setArrUsuarios($usuario);
                    //echo "<script>alert('ALEX ".$respuesta->RespUsuarios[0]->getTxusunombre()." ')</script>";

                    $respuesta->setArrCalificaciones($califica);
                } else {
                    $usuario = new LbUsuarios();
                    $respuesta->setRespuesta(AccesoController::inMenNoEx);
                    $respuesta->setArrUsuarios($usuario);
                }
            } else {
                $usuario = new LbUsuarios();
                $respuesta->setRespuesta($respSesionVali);
                $respuesta->setArrUsuarios($usuario);
            }
        } catch (Exception $ex) {
            $respuesta->setRespuesta(AccesoController::inPlatCai);
        } finally {
            return $objLogica::generaRespuesta($respuesta, $psolicitud, $usuario);
        }
    }
    
    public function listarLugares(Solicitud $psolicitud)
    {   
        /*setlocale (LC_TIME, "es_CO");
        $fecha = new \DateTime;*/
        $respuesta = new Respuesta();
        $objLogica = $this->get('logica_service');
        try {
            //Valida que la sesión corresponda y se encuentre activa
            $respSesionVali=  ManejoDataRepository::validaSesionUsuario($psolicitud);
            //echo "<script>alert(' buscarEjemplares :: Validez de sesion ".$respSesionVali." ')</script>";
            if ($respSesionVali==AccesoController::inULogged) 
            {    

                //SE INACTIVA PORQUE PUEDE GENERAR UNA GRAN CANTIDAD DE REGISTROS EN UNA SOLA SESION
                //Busca y recupera el objeto de la sesion:: 
                //$sesion = ManejoDataRepository::recuperaSesionUsuario($usuario,$psolicitud);
                //echo "<script>alert('La sesion es ".$sesion->getTxsesnumero()." ')</script>";
                //Guarda la actividad de la sesion:: 
                //ManejoDataRepository::generaActSesion($sesion,AccesoController::inDatoUno,"Recupera Feed de Ejemplares".$psolicitud->getEmail()." recuperados con éxito ",$psolicitud->getAccion(),$fecha,$fecha);
                //echo "<script>alert('Generó actividad de sesion ')</script>";
                
                $respuesta->setRespuesta(AccesoController::inExitoso);
                
                //$arLugares = new array();
                
                $lugares = ManejoDataRepository::getLugares();
                $lugar = new LbLugares();
                $arLugares = array();
                
                foreach ($lugares as $lugar) {
                
                    $arLugares[] = array("idlugar"=>$lugar->getInlugar(),"nomlugar"=>$lugar->getTxlugnombre());
                }
            
                return $objLogica::generaRespuesta($respuesta, $psolicitud, $arLugares);
            } else {
                $respuesta->setRespuesta($respSesionVali);
                $arLugares = array();
                return $objLogica::generaRespuesta($respuesta, $psolicitud, $arLugares);
            }
        } catch (Exception $ex) {
            $respuesta->setRespuesta(AccesoController::inPlatCai);
            $arLugares = array();
            return $objLogica::generaRespuesta($respuesta, $psolicitud, $arLugares);
        }
       
    }

    
    public function actualizarDatosUsuario(Solicitud $psolicitud)
    {   
        /*setlocale (LC_TIME, "es_CO");
        $fecha = new \DateTime;*/
        $respuesta = new Respuesta();
        $objLogica = $this->get('logica_service');
        try {
            //Valida que la sesión corresponda y se encuentre activa
            $respSesionVali=  ManejoDataRepository::validaSesionUsuario($psolicitud);
            //echo "<script>alert(' marcarMensajes :: Validez de sesion ".$respSesionVali." ')</script>";
            if ($respSesionVali==AccesoController::inULogged) 
            {    
                //Genera la oferta para el ejemplar
                $actualiza = ManejoDataRepository::setActualizaUsuario($psolicitud);
                if ($actualiza == AccesoController::inFallido)
                    $respuesta->setRespuesta(AccesoController::inFallido);
                else
                    $respuesta->setRespuesta($respSesionVali);
                
                return $objLogica::generaRespuesta($respuesta, $psolicitud, NULL);
            } else {
                $respuesta->setRespuesta($respSesionVali);
                return $objLogica::generaRespuesta($respuesta, $psolicitud, NULL);
            }
        } catch (Exception $ex) {
            $respuesta->setRespuesta(AccesoController::inPlatCai);
            return $objLogica::generaRespuesta($respuesta, $psolicitud, NULL);
        }
       
    }    

    public function cambiarClave(Solicitud $psolicitud)
    {   
        /*setlocale (LC_TIME, "es_CO");
        $fecha = new \DateTime;*/
        $respuesta = new Respuesta();
        $objLogica = $this->get('logica_service');
        try {
            //Valida que la sesión corresponda y se encuentre activa
            $respSesionVali=  ManejoDataRepository::validaSesionUsuario($psolicitud);
            //echo "<script>alert(' marcarMensajes :: Validez de sesion ".$respSesionVali." ')</script>";
            if ($respSesionVali==AccesoController::inULogged) 
            {    
                //Genera la oferta para el ejemplar
                $actualiza = ManejoDataRepository::setActualizaUsuario($psolicitud);
                if ($actualiza == AccesoController::inFallido)
                    $respuesta->setRespuesta(AccesoController::inFallido);
                else
                    $respuesta->setRespuesta($respSesionVali);
                
                return $objLogica::generaRespuesta($respuesta, $psolicitud, NULL);
            } else {
                $respuesta->setRespuesta($respSesionVali);
                return $objLogica::generaRespuesta($respuesta, $psolicitud, NULL);
            }
        } catch (Exception $ex) {
            $respuesta->setRespuesta(AccesoController::inPlatCai);
            return $objLogica::generaRespuesta($respuesta, $psolicitud, NULL);
        }
       
    }    
}
