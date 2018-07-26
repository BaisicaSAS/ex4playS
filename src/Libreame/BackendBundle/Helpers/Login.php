<?php

namespace Libreame\BackendBundle\Helpers;

use Libreame\BackendBundle\Repository\ManejoDataRepository;
use Doctrine\ORM\EntityManager;
use Libreame\BackendBundle\Controller\GamesController;
use Libreame\BackendBundle\Entity\Usuario;
use Libreame\BackendBundle\Entity\Sesion;
use Libreame\BackendBundle\Entity\Actsesion;


class Login 
{   
    /*
     * Esta funcion ejecuta la aacion necesaria para crear/Cerrar una sesión de un usuario en la plataforma::: 
     * Login del usuario, Logout del usuario
     * 
     * Retorna: ***JSONData(IDSESION)*** = 1. Opción Solicitada=C02  -  2. Usuario=Usuario digitado  -  
     * 3.Sesión=NULL  -  4.IP=Del dispositivo  -  5.Dispositivo=Id del dispositivo: MAC  -  
     * 6.Marca=Marca del dispositivo  -  7.Modelo=Modelo del dispositivo  -  
     * 8. SO=Sistema operativo del dispositivo         
     * ***     JSONData (IDRESPUESTA)  ***  =  
     * 1.Respuesta: (0: si el usuario o la clave son inválidos; 
     *              Retorna -1: Si no se pudo loguear por disponibilidad de la plataforma;  == -1
     *              Retorna 1 : si se logró el login. El id de la sesión se persiste en la base de datos.  
     *              Retorna -2 Si el usuario tiene una sesión activa   
     *              Retorna -3 si la sesion es sospechosa de ser ataque, 
     *              Retorna -4 Si el usuario no está activo o esta en espera de confirmación de registro)  
     * 2. IdSesion La sesión de arriba no se utiliza, la sesión creada se envía en este campo, si se generó alguna
     * 3. nummensajes: Cantidad de mensajes nuevos
     *  
     * 
     * Estados del usuario: 0: Esperando confirmación 1: Activo 2: Cuarentena 3: Inactivo
     * Valida:
     *  1. Usuario existe y la clave es valida
     *  2. Si el usuario está activo (Solo puede estar en estado 1)
     *  3. Usuario tiene sesión activa....si es así retorna -2 y aborta
     */
    
    /* ex4palys :: Adicionado $em
     */
    
    public static function loginUsuario($pSolicitud, $em)
    {   
        //error_reporting(E_ALL);
        $respuesta = new Respuesta();
        //ex4plays :: Nuevo llamado del servicio, de manera estática
        //$objLogica = $this->get('logica_service');
        setlocale (LC_TIME, "es_CO");
        $fecha = new \DateTime;
        try {
            //echo "loginUsuario : Ingresa Login \n";

            $sesion = new Sesion();
            $actsesion = new Actsesion();
            $txemail = utf8_decode($pSolicitud->getEmail());
            //echo "loginUsuario : Mail usuario ".$pSolicitud->getEmail()."-".$txemail." \n";
            /*$usuario = NULL;
            //echo "loginUsuario : USUARIO = NULL \n";*/
            //echo "loginUsuario : VA A RECUPERAR EL USUARIO \n";
            $usuario = ManejoDataRepository::getUsuarioByMail($txemail, $em);
            //Verifica si el usuario existe
            //echo "loginUsuario : va a verificar si el usuario quedó o no NULL \n";
            if ($usuario != NULL){
                //echo "loginUsuario : no es null -> usuario ".$usuario->getTxmailusuario()." \n";
                $respuesta->setArrUsuarios($usuario);
                $estado = $usuario->getInusuestado();
                //echo "<script>alert('-----Estado usuario ".$estado."')</script>";

                //Verifica si el usuario está activo
                if ($estado == GamesController::inUsuActi) {
                    //Verifica si la clave es correcta
                    //if ($usuario->getTxclaveusuario() == $pSolicitud->getClave()){
                    //$clavebinaria = base64_decode($usuario->getTxclave()); 
                    //echo "clavebinaria = [".$clavebinaria."]";
                    if (ManejoDataRepository::fnDecrypt($usuario->getTxclave(), GamesController::txSecret) == ManejoDataRepository::fnDecrypt($pSolicitud->getClave(), GamesController::txSecret)){
                        //echo "loginUsuario : Verifica si el usuario tiene una sesion activa \n";
                        $estadoSesion = GamesController::inDatoCer;
                        //Recupera el estado de login (sesion activa, inactiva) del suario
                        $sesion = ManejoDataRepository::recuperaEstadoSesionUsuario($usuario,$pSolicitud,$em,$estadoSesion);
                        //echo "loginUsuario : retornó la sesion \n";
                        switch ($estadoSesion) {
                            case GamesController::inDatoCer: //Error, debe especificar sesion : El usuario tiene una sesion activa
                                $respuesta->setRespuesta(GamesController::inUSeActi);    
                                break;
                            case GamesController::inDatoUno: //Logear
                                $sesion = ManejoDataRepository::generaSesion($usuario,GamesController::inSesActi,$fecha,NULL,$pSolicitud->getIPaddr(),$em);
                                //Genera sesion activa sin fecha de finalización
                                //echo "loginUsuario : Crea la sesion ".$sesion->gettxsesnumero()." \n ";
                                ManejoDataRepository::generaActSesion($sesion,GamesController::inDatoUno,'Login usuario '.$usuario->getTxmailusuario().' exitoso',$pSolicitud->getAccion(),$fecha,$fecha,$em);
                                $respuesta->setRespuesta(GamesController::inULogged);    
                                $respuesta->setSession($sesion->gettxsesnumero());  

                                //Busca la cantidad de mensajes del usuario sin leer 
                                $respuesta->setCantMensajes(ManejoDataRepository::cantMsgUsr($usuario));    
                                break;
                            case GamesController::inDatoDos: //Logín valido : No cambia sesion, registra intento de relogeo
                                ManejoDataRepository::generaActSesion($sesion,GamesController::inDatoUno,'Intento Nuevo Login usuario '.$usuario->getTxmailusuario().' exitoso',$pSolicitud->getAccion(),$fecha,$fecha,$em);
                                $respuesta->setRespuesta(GamesController::inULogged);    
                                //echo "loginUsuario : GENERA la sesion ".$sesion->gettxsesnumero()." \n ";
                                $respuesta->setSession($sesion->gettxsesnumero());  
                                //Busca la cantidad de mensajes del usuario sin leer 
                                $respuesta->setCantMensajes(ManejoDataRepository::cantMsgUsr($usuario));    
                                break;
                            case GamesController::inDatoTre: //Error, Sesion inválida :: Se da mensaje de sesion inactiva
                                $respuesta->setRespuesta(GamesController::inUsSeIna);    
                                break;

                            default:
                                break;
                        } 
                    } else {
                        //echo "loginUsuario : Clave incorrecta \n ";
                        $respuesta->setRespuesta(GamesController::inUsClInv);
                    }    
                } else {
                    //echo "loginUsuario : Usuario no está activo \n ";
                    $respuesta->setRespuesta(GamesController::inUsInact);
                }

            } else {
                //echo "loginUsuario : Usuario no existe \n ";
                $respuesta->setRespuesta(GamesController::inUsClInv);
            }

            //ex4plays :: Nuevo llamado del servicio, de manera estática
            //return $objLogica::generaRespuesta($respuesta, $pSolicitud, NULL);
            return Logica::generaRespuesta($respuesta, $pSolicitud, NULL, $em);
            
        }
        catch (Exception $ex) {
            $respuesta->setRespuesta(GamesController::inPlatCai);
            //ex4plays :: Nuevo llamado del servicio, de manera estática
            return Logica::generaRespuesta($respuesta, $pSolicitud, NULL, $em);
        }     
    }
    
    /*
     * ex4plays :: Adicionado $em
     * Cambios en el modelo de datos y de json
     */
    public function logoutUsuario($pSolicitud, EntityManager $em)
    {   
        $respuesta = new Respuesta();
        //ex4plays :: Nuevo llamado del servicio, de manera estática
        //$objLogica = $this->get('logica_service');
        setlocale (LC_TIME, "es_CO");
        $fecha = new \DateTime;
        try {
            //echo "<script>alert('Ingresa Login')</script>";
            $usuario = new Usuario();
            $sesion = new Sesion();
            $actsesion = new Actsesion();
            //echo "<script>alert('Mail usuario ".$pSolicitud->getEmail()."')</script>";
            //Verifica si el usuario existe
            if ($usuario = ManejoDataRepository::getUsuarioByEmail($pSolicitud->getEmail(), $em)){
                
                $estado = $usuario->getInusuestado();
                //Verifica si el usuario está activo
                if ($estado == GamesController::inUsuActi)
                {
                    //Verifica si la clave es correcta
                    //if ($usuario->getTxclaveusuario() == $pSolicitud->getClave()){
                    if (ManejoDataRepository::fnDecrypt($usuario->getTxclave(), GamesController::txSecret) == ManejoDataRepository::fnDecrypt($pSolicitud->getClave(), GamesController::txSecret)){
                        //Verifica si el usuario NO tiene la sesion activa
                        if (ManejoDataRepository::usuarioSesionActiva($pSolicitud, $pSolicitud->getSession(), $em) == FALSE){
                            $respuesta->setRespuesta(GamesController::inUsSeIna);
                        }
                        else
                        {
                            //AQUI CIERRA SESION FINALMENTE
                            $estadosesion = GamesController::inDatoCer;
                            $sesion = ManejoDataRepository::cerrarSesionUsuario(ManejoDataRepository::recuperaEstadoSesionUsuario($usuario, $pSolicitud, $em, $estadosesion), $em);
                            //Genera sesion activa sin fecha de finalización
                            ManejoDataRepository::generaActSesion($sesion,GamesController::inDatoUno,'Logout usuario '.$usuario->getTxmailusuario().' exitoso',$pSolicitud->getAccion(),$fecha,$fecha, $em);
                            $respuesta->setRespuesta(GamesController::inULogged);    
                        }
                    }
                    //Clave incorrecta
                    else{$respuesta->setRespuesta(GamesController::inUsClInv);}    
                }
                //Usuario no está activo
                else {$respuesta->setRespuesta(GamesController::inUsInact);}

            }
            //Usuario no existe
            else {$respuesta->setRespuesta(GamesController::inUsClInv);}

            //Flush al entity manager
            
            //ex4plays :: Nuevo llamado del servicio, de manera estática
            return Logica::generaRespuesta($respuesta, $pSolicitud, NULL, $em);
            
        }
        catch (Exception $ex) {
            $respuesta->setRespuesta(GamesController::inPlatCai);
            //ex4plays :: Nuevo llamado del servicio, de manera estática
            return Logica::generaRespuesta($respuesta, $pSolicitud, NULL, $em);
        }     
        
    }
    
    
}

