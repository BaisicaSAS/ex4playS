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
                } elseif ($psolicitud->getAccionComm() == GamesController::inAccModific) {
                    //echo "GestionEjemplares :: publicarEjemplar :: La acion es modificar la publicacion  \n ";
                } elseif ($psolicitud->getAccionComm() == GamesController::inAccElimina) {
                    //echo "GestionEjemplares :: publicarEjemplar :: La acion es eliminar la publicacion  \n ";
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

    
}
/*http://localhost/Ex4ReadBE/web/app_dev.php/ingreso

    
in    
{    "idsesion": {
        "idaccion": "4",
        "idtrx": "rvk4aat3k8x30mgvwxli2-xwcig3ha",
        "ipaddr": "200.000.000.000",
        "iddevice": "A4MACADDRESS",
        "marca": "LG",
        "modelo": "G2 Mini",
        "so": "KITKAT"
    },
    "idsolicitud": {
        "email": "A4alexviatela@gmail.com",
        "clave": "clave12345",
        "ultejemplar": "2"
    }
}    

out
{
    "idsesion": {
        "idaccion": "4",
        "idtrx": "",
        "ipaddr": "200.000.000.000",
        "iddevice": "A4MACADDRESS",
        "marca": "LG",
        "modelo": "G2 Mini",
        "so": "KITKAT"
    },
    "idrespuesta": {
        "respuesta": 1,
        "ejemplares": [
            [],
            {
                "idejemplar": 3,
                "idgenero": 1,
                "inejecantidad": 1,
                "dbavaluo": 0,
                "indueno": 810,
                "inlibro": 2,
                "txgenero": "Genero Prueba",
                "txlibro": "Libro Prueba 2",
                "txdueno": "A4alexviatela@gmail.com"
            },
            {
                "idejemplar": 4,
                "idgenero": 1,
                "inejecantidad": 3,
                "dbavaluo": 0,
                "indueno": 810,
                "inlibro": 1,
                "txgenero": "Genero Prueba",
                "txlibro": "Libro Prueba 1",
                "txdueno": "A4alexviatela@gmail.com"
            }
        ]
    }
}*/