<?php


namespace Libreame\BackendBundle\Helpers;

use Libreame\BackendBundle\Controller\GamesController;
use Libreame\BackendBundle\Repository\ManejoDataRepository;

use Libreame\BackendBundle\Entity\Usuario;
use Libreame\BackendBundle\Entity\Sesion;
use Libreame\BackendBundle\Entity\Lugar;
use Libreame\BackendBundle\Entity\Calificatrato;
use Libreame\BackendBundle\Entity\Planusuario;
use Libreame\BackendBundle\Entity\Plansuscripcion;
use Libreame\BackendBundle\Entity\Detalleplan;
use Libreame\BackendBundle\Entity\Trato;
use Libreame\BackendBundle\Entity\Actividadusuario;
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

    public static function obtenerParametros($psolicitud, $em)
    {   
        /*setlocale (LC_TIME, "es_CO");
        $fecha = new \DateTime;*/
        $respuesta = new Respuesta();
        
        $usuario = new Usuario();
        $sesion = new Sesion();
        //$califica = new LbCalificausuarios();
        try {
            //Valida que la sesión corresponda y se encuentre activa
            //echo "\n obtenerParametros :: Inicia Obtener parámetros";
            $respSesionVali=ManejoDataRepository::validaSesionUsuario($psolicitud, $em);
            //echo "\n obtenerParametros :: Validez de sesion ".$respSesionVali;
            if ($respSesionVali==GamesController::inULogged) 
            {    
                //Busca el usuario 
                $usuario = ManejoDataRepository::getUsuarioByEmail($psolicitud->getEmail(), $em);
                if ($usuario != NULL) 
                {
                    //echo "obtenerParametros :: Usuario no es NULL :: ".$usuario->getTxnickname().":: \n";
                    //echo "<script>alert('RESP cali ".count($usuario)." ')</script>";

                    $respuesta->setRespuesta(GamesController::inExitoso);

                    //Ingresa el usuario en el arreglo de la Clase respuesta
                    //echo "<script>alert('ALEX ')</script>";
                    $respuesta->setArrUsuarios($usuario);
                    //echo "SetArrUsuarios :: ".":: \n";
                    //echo "<script>alert('ALEX ".$respuesta->RespUsuarios[0]->getTxusunombre()." ')</script>";
                    //echo "Calculó promedio :: ".":: \n";
                    $respuesta->setPromCalificaciones(ManejoDataRepository::getPromedioCalifica($usuario->getIdusuario(),$em));
                    //echo "setPromCalificaciones :: ".":: \n";
                    $efectivos = 0;
                    $credito = 0;
                    $comprometidos = 0;
                    //echo "Recupera Barts :: ".":: \n";
                    ManejoDataRepository::obtenerSaldosBARTs($usuario, $efectivos, $credito, $comprometidos, $em);
                    $respuesta->setBartEf($efectivos);
                    //echo "setBartEf"." \n";
                    $respuesta->setBartCr($credito);
                    //echo "setBartCr"." \n";
                    $respuesta->setBartCo($comprometidos);
                    //echo "setPunUsuario"." \n";
                    
                    
                    //Calificaciones recibidas
                    $calificacionesrec = ManejoDataRepository::getCalificaUsuarioRecibidas($usuario, $em);
                    $arrCalificarec = array();
                    foreach ($calificacionesrec as $califica) { 
                       $arrCalificarec[] = array("idcalifica"=>$califica->getidcalificatrato(),
                                            "idusrcalif" => $califica->getcalificatrUsrcalifica()->getIdusuario(),
                                            "nomusrcalif" => $califica->getcalificatrUsrcalifica()->getTxnickname(),
                                            "incalificacion" => $califica->getincalificacion(),
                                            "comentario" => $califica->gettxobservacioncalifica(),
                                            "fecha" => $califica->getfecalifica()->format('d/m/Y H:i:s'));
                    }
                    //echo "Calificaciones Recibidas"." \n";
                    
                    $respuesta->setArrCalificacionesReci($arrCalificarec);
                    
                    //Calificaicones realizadas
                    $calificacionesrea = ManejoDataRepository::getCalificaUsuarioRealizadas($usuario, $em);
                    $arrCalificarea = array();
                    foreach ($calificacionesrea as $calificar) { 
                       $arrCalificarea[] = array("idcalifica"=>$calificar->getidcalificatrato(),
                                            "idusrcalif" => $calificar->getcalificatrUsrcalifica()->getIdusuario(),
                                            "nomusrcalif" => $calificar->getcalificatrUsrcalifica()->getTxnickname(),
                                            "incalificacion" => $calificar->getincalificacion(),
                                            "comentario" => $calificar->gettxobservacioncalifica(),
                                            "fecha" => $calificar->getfecalifica()->format('d/m/Y H:i:s'));
                    }
                    
                    //echo "Calificaciones Realizadas"." \n";
                    $respuesta->setArrCalificacionesReali($arrCalificarea);
                    
//                    $planusuario = new Planusuario();
                    $planusuario = ManejoDataRepository::getPlanUsuario($usuario, $em);
                    
//                    $plansuscrip = new Plansuscripcion();
                    $plansuscrip = ManejoDataRepository::getPlanSuscripcion($planusuario, $em);
                    //echo "\n obtenerParametros :: Plan :: ".utf8_encode($plansuscrip->gettxnomplan());
                    //$arrPlanUsuario = array();
                    //foreach ($planusuario as $plan) {
                    $arrPlanUsuario = array("inplan"=>$plansuscrip->getidplansuscripcion(),
                                            "txplannombre" => utf8_encode($plansuscrip->gettxnomplan()),
                                            "txplandescripcion" => utf8_encode($plansuscrip->gettxdescripcionplan()),
                                            "gratis" => $plansuscrip->getingratis(),
                                            "cantmeses" => $plansuscrip->getinmesesplan(),
                                            //"vigencia" => utf8_encode($planusuario->getFeplanfinvigencia()->format('Y-m-d H:i:s')),
                                            "fecha" => utf8_encode($planusuario->getfevigencia()->format('Y-m-d H:i:s')));
                    //echo "<script>alert('RESP PLANES ".count($planusuario)." ')</script>";

                    $ar = ManejoDataRepository::getPreferenciasUsuario($usuario, 5, $em);
                    //echo "Fin preferencias  \n";
                    
                    $respuesta->setArrPlanUsuario($arrPlanUsuario);
                    $respuesta->setArrPreferenciasU($ar); //Solo 5 registros de preferencias máximo
                    $respuesta->setArrResumenU(ManejoDataRepository::getResumenUsuario($usuario, $em)); 
                } else {
                    //echo "\n obtenerParametros :: El usuario es NULL";
                    $usuario = new Usuario();
                    $respuesta->setRespuesta(GamesController::inMenNoEx);
                    $respuesta->setArrUsuarios(NULL);
                }
            } else {
                //echo "\n obtenerParametros :: OJO El usuario no está LOGGEADO";
                $usuario = new Usuario();
                $respuesta->setRespuesta($respSesionVali);
                $respuesta->setArrUsuarios($usuario);
            }
            //echo "\n obtenerParametros :: Finaliza \n";
        } catch (Exception $ex) {
            $respuesta->setRespuesta(GamesController::inPlatCai);
        } finally {
            return Logica::generaRespuesta($respuesta, $psolicitud, $usuario, $em);
        }
    }
    
    
    /* actualizarClaveUsuario 
     * Cambia las clave del usuario
     */
    
    public static function actualizarClaveUsuario(Solicitud $psolicitud, $em)
    {   
        //echo "actualizarClaveUsuario : INGRESA ACTUALIZAR CLAVE \n";
         /*setlocale (LC_TIME, "es_CO");
        $fecha = new \DateTime;*/
        $respuesta = new Respuesta();
        try {
            //Si la clave actual del usuario es válida
            //if $psolicitud->getClave() ==  {   
                //Valida que la sesión corresponda y se encuentre activa
                $respSesionVali=  ManejoDataRepository::validaSesionUsuario($psolicitud, $em);
                //echo "actualizarClaveUsuario : Validez de sesion ".$respSesionVali." \n";
                if ($respSesionVali== GamesController::inULogged) 
                {    
                    //echo "actualizarClaveUsuario : Esta logueado \n";
                    $actualiza = ManejoDataRepository::setCambiarClave($psolicitud, $em);
                    //echo "actualizarClaveUsuario Cambió la clave ".$actualiza." \n";
                    if ($actualiza == GamesController::inFallido){
                        //echo "actualizarClaveUsuario : Responde fallido \n";
                        $respuesta->setRespuesta(GamesController::inFallido);
                    } else {
                        //echo "actualizarClaveUsuario : Responde sesion valida \n";
                        $respuesta->setRespuesta($respSesionVali);
                    }

                    return Logica::generaRespuesta($respuesta, $psolicitud, NULL, $em);
                } else {
                    //echo "actualizarClaveUsuario : NO LOGUEADO \n";
                    $respuesta->setRespuesta($respSesionVali);
                    return Logica::generaRespuesta($respuesta, $psolicitud, NULL, $em);
                }
            //} else {
            //    $respuesta->setRespuesta(GamesController::inUsClAcI);
            //}
        } catch (Exception $ex) {
            $respuesta->setRespuesta(GamesController::inPlatCai);
            return Logica::generaRespuesta($respuesta, $psolicitud, NULL);
        }
       
    }    
    
    /* recuperarMensajes 
     * Retorna la información de los mensajes del usuario
     */
    
    public function recuperarMensajes($psolicitud, $em)
    {
        /*setlocale (LC_TIME, "es_CO");
        $fecha = new \DateTime;*/
        $trato = new Trato();
        $respuesta = new Respuesta();
        //$objLogica = $this->get('logica_service');
        //echo "Recuperar mensajes \n";
        $usuario = new Usuario();
        try {
            //Valida que la sesión corresponda y se encuentre activa
            $respSesionVali=ManejoDataRepository::validaSesionUsuario($psolicitud, $em);
           //echo "<script>alert(' obtenerParametros :: Validez de sesion ".$respSesionVali." ')</script>";
            if ($respSesionVali==GamesController::inULogged) 
            {    
                //Busca el usuario 
                $usuario = ManejoDataRepository::getUsuarioByEmail($psolicitud->getEmail(), $em);
                //echo "[".$usuario->getTxmailusuario()."] \n";
                //echo "Va a recuperar los tratos \n";
                $trato = ManejoDataRepository::getTratosUsuario($usuario, $em);
                //echo "Ejecuto getTratosUsuario\n";
                
                //SE INACTIVA PORQUE PUEDE GENERAR UNA GRAN CANTIDAD DE REGISTROS EN UNA SOLA SESION
                //Busca y recupera el objeto de la sesion:: 
                //$sesion = ManejoDataRepository::recuperaSesionUsuario($usuario,$psolicitud);
                //Guarda la actividad de la sesion:: 
                //ManejoDataRepository::generaActSesion($sesion,AccesoController::inDatoUno,"Datos de usuario ".$psolicitud->getEmail()." recuperados con éxito",$psolicitud->getAccion(),$fecha,$fecha);
                //echo "<script>alert('Generó actividad de sesion ')</script>";
                
                $respuesta->setRespuesta(GamesController::inExitoso);
                //echo "Exitoso : ".GamesController::inExitoso." \n";
            } else {
                //echo "Sesion invalida [".$respSesionVali."] \n";
                $respuesta->setRespuesta($respSesionVali);
            }
        } catch (Exception $ex) {
            $respuesta->setRespuesta(GamesController::inPlatCai);
        } finally {
            //echo "...Generará Respuesta ... ".count($trato)."\n";
            return Logica::generaRespuesta($respuesta, $psolicitud, $trato, $em);
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
                $usuario = ManejoDataRepository::getUsuarioById($psolicitud->getIdusuariover(), $em);
                if ($usuario != NULL)
                {
                    $califica = ManejoDataRepository::getCalificaUsuarioRecibidas($usuario, $em);
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

    public function actualizarDatosUsuario(Solicitud $psolicitud, $em)
    {   
        /*setlocale (LC_TIME, "es_CO");
        $fecha = new \DateTime;*/
         //echo "actualizarDatosUsuario : Ingresó \n";
        $respuesta = new Respuesta();
        //$objLogica = $this->get('logica_service');
        try {
            //Valida que la sesión corresponda y se encuentre activa
            //echo "actualizarDatosUsuario : va a validar la sesion \n";
            $respSesionVali=ManejoDataRepository::validaSesionUsuario($psolicitud, $em);
            //echo "actualizarDatosUsuario :: Validez de sesion ".$respSesionVali." ' \n";
            if ($respSesionVali== GamesController::inULogged) 
            {    
                //Genera la oferta para el ejemplar
                $actualiza = ManejoDataRepository::setActualizaUsuario($psolicitud, $em);
                if ($actualiza == GamesController::inFallido)
                    $respuesta->setRespuesta(GamesController::inFallido);
                else
                    $respuesta->setRespuesta($respSesionVali);
                
                return Logica::generaRespuesta($respuesta, $psolicitud, NULL, $em);
            } else {
                $respuesta->setRespuesta($respSesionVali);
                return Logica::generaRespuesta($respuesta, $psolicitud, NULL, $em);
            }
        } catch (Exception $ex) {
            $respuesta->setRespuesta(GamesController::inPlatCai);
            return Logica::generaRespuesta($respuesta, $psolicitud, NULL, $em);
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
