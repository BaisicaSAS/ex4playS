<?php


namespace Libreame\BackendBundle\Helpers;

use Libreame\BackendBundle\Controller\AccesoController;
use Libreame\BackendBundle\Controller\GamesController;
use Libreame\BackendBundle\Repository\ManejoDataRepository;
use Libreame\BackendBundle\Helpers\Logica;
use Libreame\BackendBundle\Entity\Usuario;
use Libreame\BackendBundle\Entity\Ejemplar;
use Libreame\BackendBundle\Entity\Ejemplarusuario;
use Libreame\BackendBundle\Entity\Videojuego;
use Libreame\BackendBundle\Entity\Sesion;
use Libreame\BackendBundle\Entity\Actsesion;
use Libreame\BackendBundle\Entity\Trato;
use Libreame\BackendBundle\Entity\Actividadusuario;
/**
 * Description of Feeds
 *
 * @author mramirez
 */
class GestionEjemplares {
    
    public static function buscarEjemplares(Solicitud $psolicitud, $em)
    {   
        /*setlocale (LC_TIME, "es_CO");
        $fecha = new \DateTime;*/
        $respuesta = new Respuesta();
        //$objLogica = $this->get('logica_service');
        $usuario = new Usuario();
        $sesion = new Sesion();
        $ejemplares = new Ejemplar();
        try {
            //Valida que la sesión corresponda y se encuentre activa
            $respSesionVali=  ManejoDataRepository::validaSesionUsuario($psolicitud, $em);
            //echo "<script>alert(' buscarEjemplares :: Validez de sesion ".$respSesionVali." ')</script>";
            if ($respSesionVali==  GamesController::inULogged) 
            {    
                //echo "<script>alert(' buscaEjemplares :: FindAll ')</script>";
                //Busca el usuario 
                $usuario = ManejoDataRepository::getUsuarioByEmail($psolicitud->getEmail(),$em);
                
                $ejemplares = ManejoDataRepository::getBuscarEjemplares($usuario, $psolicitud->getTextoBuscar(), $em);
                //echo "Recuperó ejemplares...gestionejemplares:buscarEjemplares \n";
                $respuesta->setRespuesta(GamesController::inExitoso);
                
                return Logica::generaRespuesta($respuesta, $psolicitud, $ejemplares, $em);
            } else {
                $respuesta->setRespuesta($respSesionVali);
                $ejemplares = array();
                return Logica::generaRespuesta($respuesta, $psolicitud, $ejemplares, $em);
            }
        } catch (Exception $ex) {
            $respuesta->setRespuesta(AccesoController::inPlatCai);
            $ejemplares = array();
            return Logica::generaRespuesta($respuesta, $psolicitud, $ejemplares, $em);
        }
       
    }

    /*
     * feeds 
     * Retorna la lista de todos los ejemplares nuevos cargados en la plataforma. 
     * Solo a partir del ID que envía el cliente (Android), en adelante.
     * Por ahora solo tendrá Ejemplares, luego se evaluará si tambien se cargan TRATOS Cerrados / Ofertas realizadas
     */
    
    public static function recuperarFeedEjemplares($psolicitud, $em)
    {   
        $fecha = new \DateTime;
        $respuesta = new Respuesta();
        //$objAcceso = $this->get('acceso_service');
        $usuario = new Usuario();
        $sesion = new Sesion();
        $ejemplares = new Ejemplar();
        try {
            //Valida que la sesión corresponda y se encuentre activa
            $respSesionVali= ManejoDataRepository::validaSesionUsuario($psolicitud, $em);
            //echo "<script>alert(' recuperarFeedEjemplares :: Validez de sesion ".$respSesionVali." ')</script>";
            if ($respSesionVali==GamesController::inULogged) 
            {    
                $ejemplares = ManejoDataRepository::getEjemplaresDisponibles($psolicitud->getUltEjemplar(), $em);
                $respuesta->setRespuesta(GamesController::inExitoso);
                
                return Logica::generaRespuesta($respuesta, $psolicitud, $ejemplares, $em);
            } else {
                $respuesta->setRespuesta($respSesionVali);
                $ejemplares = array();
                return Logica::generaRespuesta($respuesta, $psolicitud, $ejemplares, $em);
            }
        } catch (Exception $ex) {
            $respuesta->setRespuesta(GamesController::inPlatCai);
            $ejemplares = array();
            return Logica::generaRespuesta($respuesta, $psolicitud, $ejemplares, $em);
        }
       
    }
    
    public static function publicarEjemplar(Solicitud $psolicitud, $em)
    {   
        //error_reporting(E_ALL);
        //echo "GestionEjemplares : publicarEjemplar : entra a Publicar Ejemplar Usuario-".$psolicitud->getEmail()." \n";
        /*setlocale (LC_TIME, "es_CO");
        $fecha = new \DateTime;*/
        $respuesta = new Respuesta();
        //echo "GestionEjemplares : publicarEjemplar : Objeto Respuesta creado \n";
        //$objLogica = $this->get('logica_service');
        try {
            //Valida que la sesión corresponda y se encuentre activa
            $respSesionVali= ManejoDataRepository::validaSesionUsuario($psolicitud, $em);
            //echo "GestionEjemplares :: publicarEjemplar :: Validez de sesion ".$respSesionVali." \n";
            if ($respSesionVali==GamesController::inULogged) 
            {    
                //Genera la oferta para el ejemplar si la accion es 1}
                //echo "GestionEjemplares :: publicarEjemplar :: Decide accion para ejemplar : ".$psolicitud->getAccionComm()." \n";
                if ($psolicitud->getAccionComm() == GamesController::inAccPublica) {
                    //echo "GestionEjemplares :: publicarEjemplar :: La acion es publicar \n ";
                    $respPub = ManejoDataRepository::generarPublicacionEjemplar($psolicitud, $em, $respuesta);
                    $respuesta->setRespuesta($respPub);
                } elseif ($psolicitud->getAccionComm() == GamesController::inAccDespubl) {
                    //echo "GestionEjemplares :: publicarEjemplar :: La acion es des - publicar \n ";
                    //echo "GestionEjemplares :: publicarEjemplar :: Debe traer Idejemplar = idejemusuario e idvideojuego = OBLIGATORIO \n ";
                    $respPub = ManejoDataRepository::generarDESPublicacionEjemplar($psolicitud, $em, $respuesta);
                    $respuesta->setRespuesta($respPub);
                } elseif ($psolicitud->getAccionComm() == GamesController::inAccElimina) {
                    //echo "GestionEjemplares :: publicarEjemplar :: La acion es eliminar el ejemplar \n ";
                    $respPub = ManejoDataRepository::generarEliminacionEjemplar($psolicitud, $em, $respuesta);
                    $respuesta->setRespuesta($respPub);
                }
            } else {
                $respuesta->setRespuesta($respSesionVali);
            }
            return Logica::generaRespuesta($respuesta, $psolicitud, NULL, $em);
        } catch (Exception $ex) {
            $respuesta->setRespuesta(GamesController::inPlatCai);
            return Logica::generaRespuesta($respuesta, $psolicitud, NULL, $em);
        }
       
    }

    
    public static function visualizarBiblioteca(Solicitud $psolicitud, $em)
    {   
        /*setlocale (LC_TIME, "es_CO");
        $fecha = new \DateTime;*/
        $respuesta = new Respuesta();
        //$objLogica = $this->get('logica_service');
        $usuario = new Usuario();
        $sesion = new Sesion();
        $ejemplares = new Ejemplar();

        try {
            //Valida que la sesión corresponda y se encuentre activa
            $respSesionVali= ManejoDataRepository::validaSesionUsuario($psolicitud, $em);
            //echo "visualizarBiblioteca :: Validez de sesion ".$respSesionVali." \n";
            if ($respSesionVali== GamesController::inULogged) 
            {    
                //echo "<script>alert(' buscaEjemplares :: FindAll ')</script>";
                //Busca el usuario 
                $usuario = ManejoDataRepository::getUsuarioByEmail($psolicitud->getEmail(), $em);
                
                //TODO: Por ahora filtro será solo 1: TODOS, filtro no vendrá en el json
                //$ejemplares = ManejoDataRepository::getVisualizarBiblioteca($usuario, $psolicitud->getFiltro(), $em);
                $ejemplares = ManejoDataRepository::getVisualizarBiblioteca($usuario, GamesController::inDatoUno, $em);
                //echo "Recuperó ejemplares...gestionejemplares:buscarEjemplares \n";
                $respuesta->setRespuesta(GamesController::inExitoso);
                
                //SE INACTIVA PORQUE PUEDE GENERAR UNA GRAN CANTIDAD DE REGISTROS EN UNA SOLA SESION
                //Busca y recupera el objeto de la sesion:: 
                //$sesion = ManejoDataRepository::recuperaSesionUsuario($usuario,$psolicitud);
                //echo "<script>alert('La sesion es ".$sesion->getTxsesnumero()." ')</script>";
                //Guarda la actividad de la sesion:: 
                //ManejoDataRepository::generaActSesion($sesion,AccesoController::inDatoUno,"Recupera Feed de Ejemplares".$psolicitud->getEmail()." recuperados con éxito ",$psolicitud->getAccion(),$fecha,$fecha);
                //echo "<script>alert('Generó actividad de sesion ')</script>";

                
                return Logica::generaRespuesta($respuesta, $psolicitud, $ejemplares, $em);
                
            } else {
                $respuesta->setRespuesta($respSesionVali);
                $ejemplares = array();
                return Logica::generaRespuesta($respuesta, $psolicitud, $ejemplares, $em);
            }
        } catch (Exception $ex) {
            $respuesta->setRespuesta(GamesController::inPlatCai);
            $ejemplares = array();
            return Logica::generaRespuesta($respuesta, $psolicitud, $ejemplares, $em);
        }
       
    }

    public static function solicitarEjemplar(Solicitud $psolicitud, $em)
    {   
        /*setlocale (LC_TIME, "es_CO");
        $fecha = new \DateTime;*/
        $respuesta = new Respuesta();
        $usuario = new Usuario();

        try {
            //Valida que la sesión corresponda y se encuentre activa
            $respSesionVali= ManejoDataRepository::validaSesionUsuario($psolicitud, $em);
            //echo "solicitarEjemplar :: Validez de sesion ".$respSesionVali." \n";
            if ($respSesionVali== GamesController::inULogged) 
            {    
                //echo "solicitarEjemplar :: FindAll ";
                //Busca el usuario 
                $usuario = ManejoDataRepository::getUsuarioByEmail($psolicitud->getEmail(), $em);
                $ejemplar = ManejoDataRepository::getEjemplarById($psolicitud->getIdEjemplar(), $em);
                $videojuego = ManejoDataRepository::getVideojuego($ejemplar->getejemplarVideojuego(), $em);
                $ejemplarduenousuario = ManejoDataRepository::getEjemplarusuario($psolicitud->getIdEjemusuario(), $em);        
                
                //Crea el trato
                //echo "solicitarEjemplar :: Crea el trato";
                $resp = ManejoDataRepository::solicitaEjemplarVideojuego($usuario, $ejemplar, $ejemplarduenousuario, $respuesta, $em);
                
                $respuesta->setRespuesta(GamesController::inExitoso);
                
                //SE INACTIVA PORQUE PUEDE GENERAR UNA GRAN CANTIDAD DE REGISTROS EN UNA SOLA SESION
                //Busca y recupera el objeto de la sesion:: 
                //$sesion = ManejoDataRepository::recuperaSesionUsuario($usuario,$psolicitud);
                //echo "<script>alert('La sesion es ".$sesion->getTxsesnumero()." ')</script>";
                //Guarda la actividad de la sesion:: 
                //ManejoDataRepository::generaActSesion($sesion,AccesoController::inDatoUno,"Recupera Feed de Ejemplares".$psolicitud->getEmail()." recuperados con éxito ",$psolicitud->getAccion(),$fecha,$fecha);
                //echo "<script>alert('Generó actividad de sesion ')</script>";
     
                return Logica::generaRespuesta($respuesta, $psolicitud, $ejemplar, $em);
                
            } else {
                $respuesta->setRespuesta($respSesionVali);
                $ejemplares = array();
                return Logica::generaRespuesta($respuesta, $psolicitud, $ejemplares, $em);
            }
        } catch (Exception $ex) {
            $respuesta->setRespuesta(GamesController::inPlatCai);
            $ejemplares = array();
            return Logica::generaRespuesta($respuesta, $psolicitud, $ejemplares, $em);
        }
       
    }

    public static function enviarMensajeChat(Solicitud $psolicitud, $em)
    {   
        /*setlocale (LC_TIME, "es_CO");
        $fecha = new \DateTime;*/
        $respuesta = new Respuesta();
        $usuario = new Usuario();

        try {
            //Valida que la sesión corresponda y se encuentre activa
            $respSesionVali= ManejoDataRepository::validaSesionUsuario($psolicitud, $em);
            //echo "enviarMensajeChat :: Validez de sesion ".$respSesionVali." \n";
            if ($respSesionVali== GamesController::inULogged) 
            {    
                //echo "enviarMensajeChat :: FindAll ";
                //Busca el usuario 
                $usuario = ManejoDataRepository::getUsuarioByEmail($psolicitud->getEmail(), $em);
                $usuariodes = ManejoDataRepository::getUsuarioById($psolicitud->getIdusuariodes(), $em);
                $trato = ManejoDataRepository::getTratoById($psolicitud->getIdTrato(), $em);
                $ejemplar = ManejoDataRepository::getEjemplarById($psolicitud->getIdEjemplar(), $em);
                
                //Crea el trato
                //echo "enviarMensajeChat :: Crea el trato";
                $resp = ManejoDataRepository::enviarMensaje($usuario, $usuariodes, $trato, $ejemplar, $psolicitud->getComentario(), $em);
                
                $respuesta->setRespuesta($resp);
                
                $conversacion = ManejoDataRepository::getConversacionTrato($trato, $em);
                
                //SE INACTIVA PORQUE PUEDE GENERAR UNA GRAN CANTIDAD DE REGISTROS EN UNA SOLA SESION
                //Busca y recupera el objeto de la sesion:: 
                //$sesion = ManejoDataRepository::recuperaSesionUsuario($usuario,$psolicitud);
                //echo "<script>alert('La sesion es ".$sesion->getTxsesnumero()." ')</script>";
                //Guarda la actividad de la sesion:: 
                //ManejoDataRepository::generaActSesion($sesion,AccesoController::inDatoUno,"Recupera Feed de Ejemplares".$psolicitud->getEmail()." recuperados con éxito ",$psolicitud->getAccion(),$fecha,$fecha);
                //echo "<script>alert('Generó actividad de sesion ')</script>";
     
                return Logica::generaRespuesta($respuesta, $psolicitud, $conversacion, $em);
                
            } else {
                $respuesta->setRespuesta($respSesionVali);
                $ejemplares = array();
                return Logica::generaRespuesta($respuesta, $psolicitud, $conversacion, $em);
            }
        } catch (Exception $ex) {
            $respuesta->setRespuesta(GamesController::inPlatCai);
            $conversacion = array();
            return Logica::generaRespuesta($respuesta, $psolicitud, $conversacion, $em);
        }
       
    }
    
    public static function cancelarTrato($psolicitud, $em)
    {   
        $fecha = new \DateTime;
        $respuesta = new Respuesta();
        //$objAcceso = $this->get('acceso_service');
        $ucancela = new Usuario();
        $udueno = new Usuario();
        $usolicita = new Usuario();
        $sesion = new Sesion();
        $trato = new Trato();
        try {
            //Valida que la sesión corresponda y se encuentre activa
            $respSesionVali= ManejoDataRepository::validaSesionUsuario($psolicitud, $em);
            //echo "<script>alert(' recuperarFeedEjemplares :: Validez de sesion ".$respSesionVali." ')</script>";
            if ($respSesionVali==GamesController::inULogged) 
            {    
                //Recupera el trato
                $trato = ManejoDataRepository::getTratoById($psolicitud->getIdTrato(), $em);
                //identifica si el usuario que cancela es el dueño
                //Usuario que cancela
                $ucancela = ManejoDataRepository::getUsuarioByEmail($psolicitud->getEmail(), $em);
                //Usuario dueño
                $udueno = ManejoDataRepository::getUsuarioById($trato->gettratousrdueno(), $em);
                //Usuario que solicita
                $usolicita = ManejoDataRepository::getUsuarioById($trato->gettratousrsolicita(), $em);
                
                //Verifica si se puede cancelar el trato
                //Si el trato esta en estado = 0 / solicitado
                $acciongener = -1;
                $acciondueno = -1;
                $accionsolic = -1;
                if ($trato->getinestadotrato() == GamesController::inEsTrSol) {
                    if ($udueno==$ucancela) {
                        if ($trato->getinestadocancela()==GamesController::inEsTrCnS) {
                            $acciongener = GamesController::inEsTrCnA;
                        } else {
                            $acciongener = GamesController::inEsTrCnD;
                        }
                        $acciondueno = GamesController::inDueRechSol;
                    } else {
                        if ($trato->getinestadocancela()==GamesController::inEsTrCnD) {
                            $acciongener = GamesController::inEsTrCnA;
                        } else {
                            $acciongener = GamesController::inEsTrCnS;
                        }
                        $accionsolic = GamesController::inSolCancela;
                    }
                    ManejoDataRepository::actualizarTrato($trato, $acciongener, $acciondueno, $accionsolic,  $em);
                    $respuesta->setRespuesta(GamesController::inExitoso);
                } else if ($trato->getinestadotrato() == GamesController::inDatoUno)  { //Estado Cancelado 
                    $respuesta->setRespuesta(GamesController::inTraCance);
                } else if ($trato->getinestadotrato() == GamesController::inDatoDos)  { //Estado finalizado
                    $respuesta->setRespuesta(GamesController::inTraFinal);
                }

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
    
    
}
