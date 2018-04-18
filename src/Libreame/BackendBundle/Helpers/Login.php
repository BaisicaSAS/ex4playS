<?php

namespace Libreame\BackendBundle\Helpers;

use Libreame\BackendBundle\Repository\ManejoDataRepository;
use Libreame\BackendBundle\Controller\GamesController;
use Libreame\BackendBundle\Entity\Usuario;


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
     *  2. DESPUES:::Dispositivo registrado??:::Se valida si el dispositivo es aquel que el usuario acostumbra a utilizar
     *  3. Usuario tiene sesión activa....si es así retorna -2 y aborta
     * 
     * 
     */
    
    public function loginUsuario($pSolicitud)
    {   
        $respuesta = new Respuesta();
        $objLogica = $this->get('logica_service');
        setlocale (LC_TIME, "es_CO");
        $fecha = new \DateTime;
        try {
            //echo "<script>alert('Ingresa Login')</script>";
            $usuario = new LbUsuarios();
            $device = new LbDispusuarios();
            $sesion = new LbSesiones();
            $actsesion = new LbActsesion();
            //echo "<script>alert('Mail usuario ".$pSolicitud->getEmail()."')</script>";
            //Verifica si el usuario existe
            if ($usuario = ManejoDataRepository::getUsuarioByEmail($pSolicitud->getEmail())){
                
                $respuesta->setArrUsuarios($usuario);
                $estado = $usuario->getInusuestado();
                //echo "<script>alert('-----Estado usuario ".$estado."')</script>";

                //Busca el dispositivo si no esta asociado al usuario lo crea y lo asocia
                if (!ManejoDataRepository::getDispositivoUsuario($pSolicitud->getDeviceMAC(), $usuario, NULL)){
                    //echo "<script>alert('Dispositivo [".$pSolicitud->getDeviceMAC()."-guardadevice".$guardadevice." ] NO existe')</script>";

                    $device=$device->creaDispusuario($usuario, $pSolicitud);
                    //echo "<script>alert('Persiste device')</script>";
                    !ManejoDataRepository::persistEntidad($device);
                }
                else {
                    $device = ManejoDataRepository::getDispositivoUsuario($pSolicitud->getDeviceMAC(), $usuario, NULL);
                }

               //echo "<script>alert('Dispositivo ".$device->getTxdisid()." - ".$device->getIndispusuario()."')</script>";

                
                //Verifica si el usuario está activo
                if ($estado == GamesController::inUsuActi)
                {
                    
                    //Verifica si la clave es correcta
                    if ($usuario->getTxusuclave() == $pSolicitud->getClave()){
                        //Verifica si el usuario tiene una sesion activa
                        if (ManejoDataRepository::usuarioSesionActiva($pSolicitud, $device, NULL)){
                            
                            //$respuesta->setRespuesta(GamesController::inUSeActi);
                            //Si tiene sesion activa, la recupera para reutilizarla
                            $sesion = ManejoDataRepository::recuperaSesionUsuario($usuario,$pSolicitud,NULL);
                            //Genera sesion activa sin fecha de finalización
                            ManejoDataRepository::generaActSesion($sesion,GamesController::inDatoUno,'Login usuario : Sesion ACTIVA Retomada por el sistema '.$usuario->getTxusuemail().'[Sesion: '.$sesion->getTxsesnumero().'] -  exitoso',$pSolicitud->getAccion(),$fecha,$fecha,NULL);
                            $respuesta->setRespuesta(GamesController::inULogged);    
                            $respuesta->setSession($sesion->getTxsesnumero());  
                            
                            //Busca la cantidad de mensajes del usuario sin leer 
                            $respuesta->setCantMensajes(ManejoDataRepository::cantMsgUsr($usuario));    
                        }
                        else
                        {
                            //AQUI SE LOGUEA FINALMENTE

                            //Crea sesion
                            //echo "<script>alert('-----Creará sesion"  .GamesController::inSesActi."')</script>";
                            $sesion = ManejoDataRepository::generaSesion(GamesController::inSesActi,$fecha,NULL,$device,$pSolicitud->getIPaddr(),NULL);
                            //Genera sesion activa sin fecha de finalización
                            ManejoDataRepository::generaActSesion($sesion,GamesController::inDatoUno,'Login usuario '.$usuario->getTxusuemail().' exitoso',$pSolicitud->getAccion(),$fecha,$fecha,NULL);
                            $respuesta->setRespuesta(GamesController::inULogged);    
                            $respuesta->setSession($sesion->getTxsesnumero());  
                            
                            //Busca la cantidad de mensajes del usuario sin leer 
                            $respuesta->setCantMensajes(ManejoDataRepository::cantMsgUsr($usuario));    
                            
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

            return $objLogica::generaRespuesta($respuesta, $pSolicitud, NULL);
            
        }
        catch (Exception $ex) {
            $respuesta->setRespuesta(GamesController::inPlatCai);
            return $objLogica::generaRespuesta($respuesta, $pSolicitud, NULL);
        }     
        
    }
    

    public function logoutUsuario($pSolicitud)
    {   
        $respuesta = new Respuesta();
        $objLogica = $this->get('logica_service');
        setlocale (LC_TIME, "es_CO");
        $fecha = new \DateTime;
        try {
            //echo "<script>alert('Ingresa Login')</script>";
            $usuario = new LbUsuarios();
            $device = new LbDispusuarios();
            $sesion = new LbSesiones();
            $actsesion = new LbActsesion();
            //echo "<script>alert('Mail usuario ".$pSolicitud->getEmail()."')</script>";
            //Verifica si el usuario existe
            if ($usuario = ManejoDataRepository::getUsuarioByEmail($pSolicitud->getEmail())){
                
                $estado = $usuario->getInusuestado();
                //echo "<script>alert('-----Estado usuario ".$estado."')</script>";

                //Busca el dispositivo si no esta asociado al usuario lo crea y lo asocia
                if (!ManejoDataRepository::getDispositivoUsuario($pSolicitud->getDeviceMAC(), $usuario, NULL)){
                    //echo "<script>alert('Dispositivo [".$pSolicitud->getDeviceMAC()."-guardadevice".$guardadevice." ] NO existe')</script>";

                    $device=$device->creaDispusuario($usuario, $pSolicitud);
                    //echo "<script>alert('Persiste device')</script>";
                    !ManejoDataRepository::persistEntidad($device);
                }
                else {
                    $device = ManejoDataRepository::getDispositivoUsuario($pSolicitud->getDeviceMAC(), $usuario, NULL);
                }

               //echo "<script>alert('Dispositivo ".$device->getTxdisid()." - ".$device->getIndispusuario()."')</script>";

                
                //Verifica si el usuario está activo
                if ($estado == GamesController::inUsuActi)
                {
                    
                    //Verifica si la clave es correcta
                    if ($usuario->getTxusuclave() == $pSolicitud->getClave()){
                        //Verifica si el usuario NO tiene la sesion activa
                        if (ManejoDataRepository::usuarioSesionActiva($pSolicitud, $device, $pSolicitud->getSession()) == FALSE){
                            $respuesta->setRespuesta(GamesController::inUsSeIna);
                        }
                        else
                        {
                            //AQUI CIERRA SESION FINALMENTE
                            //echo "<script>alert('-----Creará sesion"  .GamesController::inSesActi."')</script>";
                            //$sesion = ManejoDataRepository::recuperaSesionUsuario($usuario, $pSolicitud);
                            $sesion = ManejoDataRepository::cerrarSesionUsuario(ManejoDataRepository::recuperaSesionUsuario($usuario, $pSolicitud, NULL));
                            //Genera sesion activa sin fecha de finalización
                            ManejoDataRepository::generaActSesion($sesion,GamesController::inDatoUno,'Logout usuario '.$usuario->getTxusuemail().' exitoso',$pSolicitud->getAccion(),$fecha,$fecha, NULL);
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
            
            return $objLogica::generaRespuesta($respuesta, $pSolicitud, NULL);
            
        }
        catch (Exception $ex) {
            $respuesta->setRespuesta(GamesController::inPlatCai);
            return $objLogica::generaRespuesta($respuesta, $pSolicitud, NULL);
        }     
        
    }
    
    
}

