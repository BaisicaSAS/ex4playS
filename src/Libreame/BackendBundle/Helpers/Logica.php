<?php

namespace Libreame\BackendBundle\Helpers;
        
//use DateTime;
use Doctrine;
use Doctrine\ORM\EntityManager;
use Swift_Transport;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle;
use Libreame\BackendBundle\Controller\GamesController;
use Libreame\BackendBundle\Controller\EnviaMailController;
use Libreame\BackendBundle\Repository\ManejoDataRepository;
use Libreame\BackendBundle\Helpers\Respuesta;
use Libreame\BackendBundle\Helpers\Logica;
use Libreame\BackendBundle\Helpers\Login;
use Libreame\BackendBundle\Entity\Usuario;
use Libreame\BackendBundle\Entity\Actividadusuario;
use Libreame\BackendBundle\Entity\Detalleplan;
use Libreame\BackendBundle\Entity\Plansuscripcion;
use Libreame\BackendBundle\Entity\Planusuario;
use Libreame\BackendBundle\Entity\Lugar;
use Libreame\BackendBundle\Entity\Ejemplar;
use Libreame\BackendBundle\Entity\Ejemplarusuario;
use Libreame\BackendBundle\Entity\Videojuego;
use Libreame\BackendBundle\Entity\Consola;
use Libreame\BackendBundle\Entity\Fabricante;
use Libreame\BackendBundle\Entity\Trato;

class Logica {   


    const pos1mail = 2;
    const pos2mail = 4;
    const pos3mail = 6;

    const pos1pat = 3;
    const pos2pat = 5;
    const pos3pat = 7;

    /*
     * Esta funcion configurada como servicio se encarga de recibir la información del cliente
     * luego de que ha sido validada por el controlador GamesController. Luego de recibirla
     * Evalua la accion solicitada, ejecuta lo solicitado y retorna la respuesta al controlador.
     */
    
    //ex4plays :: Adicionado $em
    public function ejecutaAccion($solicitud, EntityManager $em)
    {
        try{
            //error_reporting(E_ERROR & ~E_STRICT & ~E_DEPRECATED & ~E_NOTICE);
            //error_reporting(E_ALL);
            $respuesta = GamesController::inFallido;
            
            $tmpSolicitud = $solicitud->getAccion();
            //echo "ejecutaAccion : ".$tmpSolicitud."-".GamesController::txAccRegistro." \n";
            switch ($tmpSolicitud){
                //accion de registro en el sistema
                case GamesController::txAccRegistro: {//Dato:1 : Registro en el sistema
                    //echo "<script>alert('Antes de entrar a Registro-".$solicitud->getEmail()."')</script>";
                    //ex4plays :: Adicionado $em
                    $respuesta = Logica::registroUsuario($solicitud, $em);
                    break;
                }    
                //accion de login en el sistema
                case GamesController::txAccIngresos: {//Dato:2 : Login
                    //echo "ejecutaAccion : Antes de entrar a Login-".$solicitud->getEmail()." \n";
                    //ex4plays :: Adicionado $em
                    //$login = new Login();
                    $respuesta = Login::loginUsuario($solicitud, $em);
                    break;
                } 
                //accion de recuperar datos y parametros de usuario
                case GamesController::txAccRecParam: {//Dato:3 : Recuperar datos de usuario (Propio)
                    //echo "<script>alert('Antes de entrar a Recuperar Parametros Usuario-".$solicitud->getEmail()."')</script>";
                    $respuesta = GestionUsuarios::obtenerParametros($solicitud, $em);
                    break;
                } 

                case GamesController::txAccRecFeeds: {//Dato:4 : Recuperar Feeds de ejemplares
                    //echo "<script>alert('Antes de entrar a Recuperar Parametros Usuario-".$solicitud->getEmail()."')</script>";
                   $respuesta = GestionEjemplares::recuperarFeedEjemplares($solicitud, $em);
                    break;
                } 

                case GamesController::txAccRecOpera: {//Dato:5 : Recuperar Mensajes
                    //echo "<script>alert('Antes de entrar a Recuperar Mensajes Usuario-".$solicitud->getEmail()."')</script>";
                    $respuesta = GestionUsuarios::recuperarMensajes($solicitud, $em);
                    break;
                } 

                case GamesController::txAccBusEjemp: {//Dato:7 : Buscar
                    //echo "<script>alert('Antes de entrar a Buscar Ejemplares Usuario-".$solicitud->getEmail()."')</script>";
                    $objGestEjemplares = new GestionEjemplares();
                    $respuesta = $objGestEjemplares::buscarEjemplares($solicitud, $em);
                    break;
                } 

                case GamesController::txAccRecOfert: {//Dato:8 : Recuperar oferta
                    //echo "<script>alert('Antes de entrar a Recuperar oferta Usuario-".$solicitud->getEmail()."')</script>";
                    $objGestEjemplares = $this->get('gest_ejemplares_service');
                    $respuesta = $objGestEjemplares::recuperarOferta($solicitud);
                    break;
                } 

                case GamesController::txAccRecUsuar: {//Dato:9 : Ver usuario otro
                    //echo "<script>alert('Antes de entrar a Ver Usuario Otro-".$solicitud->getEmail()."')</script>";
                    $objGestUsuarios = $this->get('gest_usuarios_service');
                    $respuesta = $objGestUsuarios::verUsuarioOtro($solicitud);
                    break;
                } 

                case GamesController::txAccCerraSes: {//Dato:10 : Cerrar Sesion
                    //echo "<script>alert('Antes de entrar a Logout-".$solicitud->getEmail()."')</script>";
                    /*$objLogin = $this->get('login_service');
                    $respuesta = $objLogin::logoutUsuario($solicitud);*/
                    //ex4plaS :: Nuevo llamado al servicio
                    $respuesta = Login::logoutUsuario($solicitud, $em);
                    break;
                } 

                case GamesController::txAccRecClave: {//Dato:29 : Cambio de clave
                    //echo "ejecutaAccion : Antes de entrar a Cambio de clave -".$solicitud->getEmail()." \n";
                    //$objGestUsuarios = $this->get('gest_usuarios_service');
                    $respuesta = GestionUsuarios::actualizarClaveUsuario($solicitud, $em);
                    break;
                } 
                
                case GamesController::txAccReaOfert: {//Dato:32 : Solicitar un ejemplar
                    //echo "ejecutaAccion : Antes de entrar a Cambio de clave -".$solicitud->getEmail()." \n";
                    //$objGestUsuarios = $this->get('gest_usuarios_service');
                    $respuesta = GestionEjemplares::solicitarEjemplar($solicitud, $em);
                    break;
                } 
                
                case GamesController::txAccActParam: {//Dato:12 : Actualizar datos parametros usuario
                    //echo "ejecutaAccion : Antes de entrar a Actualizar datos parametros usuario-".$solicitud->getEmail()." \n";
                    //$objGestUsuarios = $this->get('gest_usuarios_service');
                    $respuesta = GestionUsuarios::actualizarDatosUsuario($solicitud, $em);
                    break;
                } 

                case GamesController::txAccPubliEje: {//Dato:13 : Publicar ejemplar
                    //echo "ejecutaAccion : Antes de entrar a Publicar Ejemplar Usuario - [".$solicitud->getEmail()."] \n";
                    //$objGestEjemplares = $this->get('gest_ejemplares_service');
                    $respuesta = GestionEjemplares::publicarEjemplar($solicitud, $em);
                    break;
                } 

                case GamesController::txAccVisuaBib: {//Dato:16 : Visualizar Biblioteca
                    //echo "ejecutaAccion : Antes de entrar a Visualizar Biblioteca-".$solicitud->getEmail()." \n";
                    //$objGestEjemplares = $this->get('gest_ejemplares_service');
                    $respuesta = GestionEjemplares::visualizarBiblioteca($solicitud, $em);
                    break;
                } 

                case GamesController::txAccPubMensa: {//Dato:19 : Chatear
                    //echo "<script>alert('Antes de entrar a Chatear-".$solicitud->getEmail()."')</script>";
                    $objGestEjemplares = $this->get('gest_ejemplares_service');
                    $respuesta = $objGestEjemplares::enviarMensajeChat($solicitud);
                    break;
                } 

                case GamesController::txAccCaliTrat: {//Dato:22 : Calificar usuario trato
                    //echo "<script>alert('Antes de entrar a Chatear-".$solicitud->getEmail()."')</script>";
                    $objGestUsuarios = $this->get('gest_usuarios_service');
                    $respuesta = $objGestUsuarios::calificarUsuarioTrato($solicitud);
                    break;
                } 

                case GamesController::txAccMarcMens: {//Dato:36 : Marcar Mensaje
                    //echo "<script>alert('Antes de entrar a Marcar Mensajes Usuario-".$solicitud->getEmail()."')</script>";
                    $objGestUsuarios = $this->get('gest_usuarios_service');
                    $respuesta = $objGestUsuarios::marcarMensajes($solicitud);
                    break;
                } 

                case GamesController::txAccListaIdi: {//Dato:37 : Listar idiomas
                    //echo "<script>alert('Antes de entrar a Listar idiomas Usuario-".$solicitud->getEmail()."')</script>";
                    $objGestEjemplares = $this->get('gest_ejemplares_service');
                    $respuesta = $objGestEjemplares::listarIdiomas($solicitud);
                    //echo "Genera respuesta : ".$respuesta; 
                    break;
                } 

                case GamesController::txAccListaLug: {//Dato:38 : Listar lugares
                    //echo "<script>alert('Antes de entrar a Listar idiomas Usuario-".$solicitud->getEmail()."')</script>";
                    $objGestUsuarios = $this->get('gest_usuarios_service');
                    $respuesta = $objGestUsuarios::listarLugares($solicitud);
                    break;
                } 

                case GamesController::txAccMegEjemp: {//Dato:40 : Marcar megusta ejemplar
                    //echo "<script>alert('Antes de entrar a Me gusta ejemplar Usuario-".$solicitud->getEmail()."')</script>";
                    $objGestEjemplares = $this->get('gest_ejemplares_service');
                    $respuesta = $objGestEjemplares::megustaEjemplar($solicitud);
                    break;
                } 

                case GamesController::txAccVerUsMeg: {//Dato:41 : Ver usuarios a quienes les gusta un ejemplar
                    //echo "<script>alert('Antes de entrar a Ver usuarios Me gusta ejemplar Usuario-".$solicitud->getEmail()."')</script>";
                    $objGestEjemplares = $this->get('gest_ejemplares_service');
                    $respuesta = $objGestEjemplares::VerUsrgustaEjemplar($solicitud);
                    break;
                } 

                case GamesController::txAccCommEjem: {//Dato:42 : Realizar, borrar, editar comentario a ejemplar
                    //echo "<script>alert('Antes de entrar a COMENTARIO ejemplar Usuario-".$solicitud->getEmail()."')</script>";
                    $objGestEjemplares = $this->get('gest_ejemplares_service');
                    $respuesta = $objGestEjemplares::comentarEjemplar($solicitud);
                    break;
                } 
                
                case GamesController::txAccVerComEj: {//Dato:43 : Ver comentarios  ejemplar
                    //echo "<script>alert('Antes de entrar a COMENTARIO ejemplar Usuario-".$solicitud->getEmail()."')</script>";
                    $objGestEjemplares = $this->get('gest_ejemplares_service');
                    $respuesta = $objGestEjemplares::VerComentariosEjemplar($solicitud);
                    break;
                } 
                
                
                case GamesController::txAccListaEdi: {//Dato:50 : Listar editoriales
                    //echo "<script>alert('Antes de entrar a Listar idiomas Usuario-".$solicitud->getEmail()."')</script>";
                    $objGestEjemplares = $this->get('gest_ejemplares_service');
                    $respuesta = $objGestEjemplares::listarEditoriales($solicitud);
                    break;
                } 

                case GamesController::txAccListaAut: {//Dato:51 : Listar autores
                    //echo "<script>alert('Antes de entrar a Listar idiomas Usuario-".$solicitud->getEmail()."')</script>";
                    $objGestEjemplares = $this->get('gest_ejemplares_service');
                    $respuesta = $objGestEjemplares::listarAutores($solicitud);
                    break;
                } 

            }
            //echo "<script>alert('ejecuta Accion: ".$respuesta."')</script>";
            return $respuesta;
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }   
    
    public static function generaRespuesta(Respuesta $respuesta, Solicitud $pSolicitud, $parreglo, $em){

        try {
            //echo "<script>alert('ACCION Genera respuesta: ".$pSolicitud->getAccion()."')</script>";
            //echo "<script>alert('REPUESTA Genera respuesta: ".$respuesta->getRespuesta()."')</script>";

            switch($pSolicitud->getAccion()){

                //accion de registro en el sistema
                case GamesController::txAccRegistro:  //Dato: 1  
                    $JSONResp = Logica::respuestaRegistro($respuesta, $pSolicitud);
                    break;

                //accion de login en el sistema
                case GamesController::txAccIngresos:  //Dato: 2
                    //$vRespuesta
                    $JSONResp = Logica::respuestaLogin($respuesta, $pSolicitud);
                    break;

                //accion de recuperar datos y parametros de usuario
                case GamesController::txAccRecParam:  //Dato: 3
                    $JSONResp = Logica::respuestaDatosUsuario($respuesta, $pSolicitud, $parreglo, $em);
                    break;

                //accion de recuperar los feeds de publicaciones nuevas
                case GamesController::txAccRecFeeds:  //Dato: 4
                    $JSONResp = Logica::respuestaFeedEjemplares($respuesta, $pSolicitud, $parreglo, $em);
                    break;

                //accion de recuperar mensajes
                case GamesController::txAccRecOpera:  //Dato: 5
                    $JSONResp = Logica::respuestaRecuperarMensajes($respuesta, $pSolicitud, $parreglo, $em);
                    break;

                //accion de buscar ejemplares
                case GamesController::txAccBusEjemp:  //Dato: 7
                    $JSONResp = Logica::respuestaBuscarEjemplares($respuesta, $pSolicitud, $parreglo, $em);
                    break;

                //accion de recuperar oferta
                case GamesController::txAccRecOfert:  //Dato: 8
                    $JSONResp = Logica::respuestaRecuperarOferta($respuesta, $pSolicitud, $parreglo);
                    break;

                //accion de ver usuario otro
                case GamesController::txAccRecUsuar:  //Dato: 9
                    $JSONResp = Logica::respuestaVerUsuarioOtro($respuesta, $pSolicitud, $parreglo);
                    break;

                //accion de cerrar sesion de usuario
                case GamesController::txAccCerraSes:  //Dato: 10
                    $JSONResp = Logica::respuestaCerrarSesion($respuesta, $pSolicitud);
                    break;

                //accion de actualizar datos usuario
                case GamesController::txAccActParam: //Dato:12 : Actualizar datos usuario
                    $JSONResp = Logica::respuestaActualizarDatosUsuario($respuesta, $pSolicitud);
                    break;
                
                //accion de publicar un ejemplar
                case GamesController::txAccPubliEje:  //Dato: 13
                    $JSONResp = Logica::respuestaPublicarEjemplar($respuesta, $pSolicitud, $em);
                    break;

                //accion de Visualizar biblioteca
                case GamesController::txAccVisuaBib:  //Dato: 16
                    $JSONResp = Logica::respuestaVisualizarBiblioteca($respuesta, $pSolicitud, $parreglo, $em);
                    //echo "generaRespuesta :: Terminó el JSON de respuestaVisualizarBiblioteca ".print_r($JSONResp)." \n";
                    break;

                //accion de Chatear
                case GamesController::txAccPubMensa:  //Dato: 19
                    $JSONResp = Logica::respuestaEnviarMensajeChat($respuesta, $pSolicitud, $parreglo);
                    break;

                //accion de Calificar usuario trato
                case GamesController::txAccCaliTrat:  //Dato: 22
                    $JSONResp = Logica::respuestaCalificarUsuarioTrato($respuesta, $pSolicitud);
                    break;

                case GamesController::txAccRecClave: //Dato:29 : Cambio de clave
                    $JSONResp = Logica::respuestaCambiarClave($respuesta, $pSolicitud);
                    break;

                case GamesController::txAccReaOfert: //Dato:32 : Solicitar videojuego
                    $JSONResp = Logica::respuestaSolicitarEjemplar($respuesta, $pSolicitud);
                    break;

                case GamesController::txAccMarcMens: //Dato:36 : Marcar Mensaje
                    $JSONResp = Logica::respuestaMarcarMensaje($respuesta, $pSolicitud);
                    break;
                
                case GamesController::txAccListaIdi: //Dato:37 : Listar idiomas
                    $JSONResp = Logica::respuestaListaIdiomas($respuesta, $pSolicitud, $parreglo);
                    //print_r(array_values($JSONResp));
                    break;
                
                case GamesController::txAccListaLug: //Dato:38 : Listar Lugares
                    $JSONResp = Logica::respuestaListaLugares($respuesta, $pSolicitud, $parreglo);
                    break;
                
                case GamesController::txAccMegEjemp: //Dato:40 : Marcar Megusta ejemplar
                    $JSONResp = Logica::respuestaMegustaEjemplar($respuesta, $pSolicitud);
                    break;
                
                case GamesController::txAccVerUsMeg: //Dato:41 : Ver usuarios a quienes les gusta ejemplar
                    $JSONResp = Logica::respuestaVerUsuMegustaEjemplar($respuesta, $pSolicitud, $parreglo);
                    break;

                case GamesController::txAccCommEjem: //Dato:42 : Realizar comentario a un ejemplar
                    $JSONResp = Logica::respuestaComentarioEjemplar($respuesta, $pSolicitud);
                    break;
                
                case GamesController::txAccVerComEj: //Dato:43 : Ver comentarios ejemplar
                    $JSONResp = Logica::respuestaVerComentariosEjemplar($respuesta, $pSolicitud, $parreglo);
                    break;
                
                case GamesController::txAccListaEdi: //Dato:50 : Listar editoriales
                    $JSONResp = Logica::respuestaListaEditoriales($respuesta, $pSolicitud, $parreglo);
                    //print_r(array_values($JSONResp));
                    break;
                
                case GamesController::txAccListaAut: //Dato:51 : Listar autores
                    $JSONResp = Logica::respuestaListaAutores($respuesta, $pSolicitud, $parreglo);
                    //print_r(array_values($JSONResp));
                    break;
                
                
            }
            //echo " 1 La respuesta inicia";

            $respuestaGen = json_encode($JSONResp);
            
            //echo "2 La respuesta se imprimió - va a ".$respuestaGen;
            return $respuestaGen;
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        }  
    }

    
    /*
     * respuestaGenerica: 
     * Funcion que genera el JSON de respuesta cuando por calidad de datos no se ralizó ninguna operacion
     */
    public static function respuestaGenerica(Respuesta $respuesta, Solicitud $pSolicitud){
        try {
            return array('idsesion' => array ('idaccion' => $pSolicitud->getAccion(),
                            'idtrx' => $pSolicitud->getSession(), 'ipaddr'=> $pSolicitud->getIPaddr()), 
                            'idrespuesta' => (array('respuesta' => $respuesta->getRespuesta())));
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }    
    

    /*
     * respuestaRegistro: 
     * Funcion que genera el JSON de respuesta para la accion de registro :: GamesController::txAccRegistro
     */
    public static function respuestaRegistro(Respuesta $respuesta, Solicitud $pSolicitud){
        try {
            return array('idsesion' => array ('idaccion' => $pSolicitud->getAccion(),
                            'idtrx' => '', 'ipaddr'=> $pSolicitud->getIPaddr()), 
                            'idrespuesta' => (array('respuesta' => $respuesta->getRespuesta())));
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }    

    /*
     * respuestaLogin: 
     * Funcion que genera el JSON de respuesta para la accion de Login :: GamesController::txAccIngresos:
     */
    public static function respuestaLogin(Respuesta $respuesta, Solicitud $pSolicitud){
        try {
            //$usuario = new Usuario();
            $usuario = $respuesta->RespUsuarios[0];
            if ($usuario == NULL)  {
                $idusuario = NULL;
            } else {
                $idusuario = $usuario->getIdusuario();
            }
                
            return array('idsesion' => array ('idaccion' => $pSolicitud->getAccion(),
                            'idtrx' => '', 'ipaddr'=> $pSolicitud->getIPaddr()), 
                            'idrespuesta' => (array('respuesta' => $respuesta->getRespuesta(),
                            'idusuario' => $idusuario,
                            'idsesion' => $respuesta->getSession(), 
                            'cantmensajes' => $respuesta->getCantMensajes())));
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }    
    

    /* ex4plays :: Modificado $em
     * respuestaDatosUsuario: 
     * Funcion que genera el JSON de respuesta para la accion de Recuperar Datos de Usuario :: GamesController::txAccRecParam
     */
    public static function respuestaDatosUsuario(Respuesta $respuesta, Solicitud $pSolicitud, $parreglo, EntityManager $em){

        try {
            //Recupera el lugar, de la tabla de Lugares
            //$em = Logica::getDoctrine()->getManager();
            //echo "Va a generar respusta DatosUsuari0 \n";
            $usuario = new Usuario();
            $lugar = new Lugar();
            if ($respuesta->getRespuesta()== GamesController::inULogged){
                //echo "LUGAR ".$usuario->getUsuarioInlugar()->getinlugar();
                $usuario = ManejoDataRepository::getUsuarioByEmail($pSolicitud->getEmail(), $em);
                $lugar = ManejoDataRepository::getLugar($usuario->getUsuarioInlugar(), $em);
            }
            if (!is_null($usuario)){
                if (is_null($usuario->getFecreacionusuario())) {
                    $fecha = "";
                } else {
                    $fecha = $usuario->getFecreacionusuario()->format('Y-m-d H:i:s');
                }
            }
            //echo "genero fecha \n";

            return array('idsesion' => array ('idaccion' => $pSolicitud->getAccion(),
                    'idtrx' => '', 'ipaddr'=> $pSolicitud->getIPaddr()), 
                    'idrespuesta' => array('respuesta' => $respuesta->getRespuesta(),
                    'usuario' => array('idusuario' => $usuario->getIdusuario(), 
                        'nomusuario' => utf8_encode($usuario->getTxnomusuario()),
                        'nommostusuario' => utf8_encode($usuario->getTxnickname()), 
                        'email' => utf8_encode($usuario->getTxmailusuario()),
                        'usutelefono' => utf8_encode($usuario->getTxtelefono()), 
                        'usudireccion' => utf8_encode($usuario->getTxdireccion()), 
                        'usugenero' => $usuario->getInusuestado(),
                        //La siguiente línea debe habilitarse, e integrar el CAST de BLOB a TEXT??
                        //'usuimagen' => utf8_encode(base64_decode($respuesta->RespUsuarios[0]->getTxusuimagen())), 
                        'usuimagen' => utf8_encode($usuario->getTxusuimagen()), 
                        'usufecnac' => $fecha,
                        'usulugar' => $lugar->getinlugar(), 
                        'usunomlugar' => utf8_encode($lugar->gettxlugnombre()),
                        'usupromcalifica' => $respuesta->getPromCalificaciones(),
                        'bartsefectivos' => $respuesta->getPunUsuarioEf(),
                        'bartscredito' => $respuesta->getPunUsuarioCr(),
                        'bartscomprometidos' => $respuesta->getPunUsuarioCo(),
                        'comentariosreci' => $respuesta->getArrCalificacionesReci(),
                        'comentariosreali' => $respuesta->getArrCalificacionesReali(),
                        'planusuario' => $respuesta->getArrPlanUsuario(),
                        'resumen' => $respuesta->getArrResumenU(),
                        'preferencias' => $respuesta->getArrPreferenciasU()))
                );
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }    

    /* ex4plays :: Adicionado $em
     * respuestaBuscarEjemplares: 
     * Funcion que genera el JSON de respuesta para la accion de Buscar ejemplares :: GamesController::txAccBusEjem:
     */
    public static function respuestaBuscarEjemplares(Respuesta $respuesta, Solicitud $pSolicitud, $parreglo, EntityManager $em){
        try{
            $arrTmp = array();
            $ejemplarusuario = new Ejemplarusuario();
            $usuario = ManejoDataRepository::getUsuarioByEmail($pSolicitud->getEmail(), $em);
            //echo "Va a generar la respuestaBuscarEjemplares :: Logica.php [365] \n";
            foreach ($parreglo as $ejemplarusuario){
                //echo "id del ejemplar : ".$ejemplarusuario->getejemplarusuarioejemplar()->getidejemplar();
                $ejemplar = ManejoDataRepository::getEjemplarById($ejemplarusuario->getejemplarusuarioejemplar(), $em);
                if ($respuesta->getRespuesta()== GamesController::inULogged){
                    $videojuego = ManejoDataRepository::getVideojuego($ejemplar->getejemplarVideojuego(), $em);
                    $consola = ManejoDataRepository::getConsola($videojuego->getvideojuegoconsola(), $em);
                    //echo "\n consola [".utf8_encode($consola->gettxnombreconsola())."]";
                    $fabricante = ManejoDataRepository::getFabricante($consola->getconsolafabricante(), $em);
                    //echo "\n fabricante [".utf8_encode($fabricante->gettxnomfabricante())."]";
                
                    $titulo = utf8_encode($videojuego->gettxnomvideojuego());
                    //echo "\n titulo [".utf8_encode($titulo)."]";
                    $categoria = $videojuego->getincategvideojuego(); //Cantidad de puntos
                    $puntos = ManejoDataRepository::getPuntosCategoria($videojuego->getincategvideojuego()); //Cantidad de puntos
                    //echo "\n puntos [".utf8_encode($puntos)."]";
                    $imagen = utf8_encode($videojuego->gettximagen());
                    //echo "\n imagen [".utf8_encode($videojuego->gettximagen())."]";
                    $lugar = ManejoDataRepository::getLugar($usuario->getUsuarioInlugar(), $em);
                    //echo "\n lugar: ".$usuario->getUsuarioInlugar()->getinlugar();
                    //ex4play :: Implementar megusta
                    //$megusta = ManejoDataRepository::getMegustaEjemplar($ejemplar, $usuarioConsulta);
                    //echo "...megusta \n";
                    //$cantmegusta = ManejoDataRepository::getCantMegusta($ejemplar->getInejemplar());
                    //echo "...cantmegusta \n";
                    $cantresenas = ManejoDataRepository::getCantResenas($videojuego->getidvideojuego(), $em);
                    //echo "...cant_resenas \n";
                    $usuario = ManejoDataRepository::getUsuarioById($ejemplarusuario->getejemplarusuariousuario()->getIdusuario(), $em);
                    //echo "\n usuario: ".$usuario->getUsuarioInlugar()->getinlugar();
                    //echo "...usuario [".utf8_encode($usuario->getTxusunommostrar())."] \n";
                    $fecpublica = ManejoDataRepository::getFechaPublicacion($ejemplar, $usuario, $em);
                    //echo "\n fecha publicación... $fecpublica \n";
                    //echo "\n RECUPERO DATOS\n";
                    
                    //@Cambio ejemplarusuario
                    $arrTmp[] = array('idejemplar' => $ejemplar->getidejemplar(), 
                        'idejemusuario' => $ejemplarusuario->getidejemplarusuario(),
                        'videojuego' => $videojuego->getidvideojuego(), 
                        'titulo' => $titulo, 
                        'categoria' => $categoria, 
                        'puntos' => $puntos, 
                        'cant_resenas' => $cantresenas, 
                        'otra_informacion' => utf8_encode($videojuego->gettxurlinformacion()), 
                        'observaciones' => utf8_encode($videojuego->gettxobservaciones()), 
                        'imagen' => $imagen, 
                        //'megusta' => $megusta,
                        'fechapublica' => $fecpublica,
                        //'cantcomment' => $cantcomment,
                        //'desccondactual' => $desccondactual,
                        'lugar' => array('inlugar' => $lugar->getinlugar(), 'txlugnombre' => utf8_encode($lugar->gettxlugnombre())),
                        'consola' => array('idconsola' => $consola->getidconsola(), 'txnombreconsola' => utf8_encode($consola->gettxnombreconsola())),
                        'fabricante' => array('idfabricante' => $fabricante->getidfabricante(), 'txombrefabricante' => utf8_encode($fabricante->gettxnomfabricante())),
                        'generos' => utf8_encode($videojuego->gettxgenerovideojuego()),
                        'usrdueno' => array('inusuario' => $usuario->getIdusuario(),
                            'txusunommostrar' => utf8_encode($usuario->getTxnickname()),
                            'txusuimagen' => utf8_encode($usuario->getTxusuimagen()),
                            'calificacion' => '0' )
                    );
                }
            
            }
            
            //echo "\n Armó JSON \n";
            return array('idsesion' => array ('idaccion' => $pSolicitud->getAccion(),
                    'idtrx' => '', 'ipaddr'=> $pSolicitud->getIPaddr()), 
                    'idrespuesta' => array('respuesta' => $respuesta->getRespuesta(), 
                    'ejemplares' => $arrTmp ));

        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }    
    
    /* ex4play :: Adicioando $em
     * respuestaFeedEjemplares: 
     * Funcion que genera el JSON de respuesta para la accion de recuperar Feed de ejemplares :: GamesController::txAccRecFeeds:
     */
    public static function respuestaFeedEjemplares(Respuesta $respuesta, Solicitud $pSolicitud, $parreglo, EntityManager $em){
        try{

            $arrTmp = array();
            $ejemplarusuario = new Ejemplarusuario();
            $usuario = ManejoDataRepository::getUsuarioByEmail($pSolicitud->getEmail(), $em);
            //echo "Va a generar la respuestaBuscarEjemplares :: Logica.php [365] \n";
            foreach ($parreglo as $ejemplarusuario){
                //echo "id del ejemplar : ".$ejemplarusuario->getejemplarusuarioejemplar()->getidejemplar();
                $ejemplar = ManejoDataRepository::getEjemplarById($ejemplarusuario->getejemplarusuarioejemplar(), $em);
                if ($respuesta->getRespuesta()== GamesController::inULogged){
                    $videojuego = ManejoDataRepository::getVideojuego($ejemplar->getejemplarVideojuego(), $em);
                    $consola = ManejoDataRepository::getConsola($videojuego->getvideojuegoconsola(), $em);
                    //echo "\n consola [".utf8_encode($consola->gettxnombreconsola())."]";
                    $fabricante = ManejoDataRepository::getFabricante($consola->getconsolafabricante(), $em);
                    //echo "\n fabricante [".utf8_encode($fabricante->gettxnomfabricante())."]";
                
                    $titulo = utf8_encode($videojuego->gettxnomvideojuego());
                    //echo "\n titulo [".utf8_encode($titulo)."]";
                    $categoria = $videojuego->getincategvideojuego(); //Cantidad de puntos
                    $puntos = ManejoDataRepository::getPuntosCategoria($videojuego->getincategvideojuego()); //Cantidad de puntos
                    //echo "\n puntos [".utf8_encode($puntos)."]";
                    $imagen = utf8_encode($videojuego->gettximagen());
                    //echo "\n imagen [".utf8_encode($videojuego->gettximagen())."]";
                    $lugar = ManejoDataRepository::getLugar($usuario->getUsuarioInlugar(), $em);
                    //echo "\n lugar: ".$usuario->getUsuarioInlugar()->getinlugar();
                    //ex4play :: Implementar megusta
                    //$megusta = ManejoDataRepository::getMegustaEjemplar($ejemplar, $usuarioConsulta);
                    //echo "...megusta \n";
                    //$cantmegusta = ManejoDataRepository::getCantMegusta($ejemplar->getInejemplar());
                    //echo "...cantmegusta \n";
                    $cantresenas = ManejoDataRepository::getCantResenas($videojuego->getidvideojuego(), $em);
                    //echo "...cant_resenas \n";
                    $usuario = ManejoDataRepository::getUsuarioById($ejemplarusuario->getejemplarusuariousuario()->getIdusuario(), $em);
                    //echo "\n usuario: ".$usuario->getUsuarioInlugar()->getinlugar();
                    //echo "...usuario [".utf8_encode($usuario->getTxusunommostrar())."] \n";
                    $fecpublica = ManejoDataRepository::getFechaPublicacion($ejemplar, $usuario, $em);
                    //echo "\n fecha publicación... $fecpublica \n";
                    //echo "\n RECUPERO DATOS\n";
                
                    //@Cambio ejemplarusuario
                    $arrTmp[] = array('idejemplar' => $ejemplar->getidejemplar(), 
                        'idejemusuario' => $ejemplarusuario->getidejemplarusuario(),
                        'videojuego' => $videojuego->getidvideojuego(), 
                        'titulo' => $titulo, 
                        'categoria' => $categoria, 
                        'puntos' => $puntos, 
                        'cant_resenas' => $cantresenas, 
                        'otra_informacion' => utf8_encode($videojuego->gettxurlinformacion()), 
                        'observaciones' => utf8_encode($videojuego->gettxobservaciones()), 
                        'imagen' => $imagen, 
                        //'megusta' => $megusta,
                        'fechapublica' => $fecpublica,
                        //'cantcomment' => $cantcomment,
                        //'desccondactual' => $desccondactual,
                        'lugar' => array('inlugar' => $lugar->getinlugar(), 'txlugnombre' => utf8_encode($lugar->gettxlugnombre())),
                        'consola' => array('idconsola' => $consola->getidconsola(), 'txnombreconsola' => utf8_encode($consola->gettxnombreconsola())),
                        'fabricante' => array('idfabricante' => $fabricante->getidfabricante(), 'txombrefabricante' => utf8_encode($fabricante->gettxnomfabricante())),
                        'generos' => utf8_encode($videojuego->gettxgenerovideojuego()),
                        'usrdueno' => array('inusuario' => $usuario->getIdusuario(),
                            'txusunommostrar' => utf8_encode($usuario->getTxnickname()),
                            'txusuimagen' => utf8_encode($usuario->getTxusuimagen()),
                            'calificacion' => '0' )
                    );
                }
            
            }
            
            //echo "\n Armó JSON \n";
            return array('idsesion' => array ('idaccion' => $pSolicitud->getAccion(),
                    'idtrx' => '', 'ipaddr'=> $pSolicitud->getIPaddr()), 
                    'idrespuesta' => array('respuesta' => $respuesta->getRespuesta(), 
                    'ejemplares' => $arrTmp ));

        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }    
    
    
        /*
     * respuestaRecuperarMensajes: 
     * Funcion que genera el JSON de respuesta para la accion de recuperar mensajes:: GamesController::txAccRecOpera:
     */
    public static function respuestaRecuperarMensajes(Respuesta $respuesta, Solicitud $pSolicitud, $parreglo, $em){
        try{
            $arUsuario = array();
            $arrTmp = array();
            $trato = new Trato();
            
            $usrlogueado = ManejoDataRepository::getUsuarioByEmail($pSolicitud->getEmail(), $em);
            foreach ($parreglo as $trato){
                echo "...Generando respuesta \n";
                //Recupera los usuarios ID + Nombre
                if ($trato->gettratousrsolicita() != NULL)
                {
                    $usuario = ManejoDataRepository::getUsuarioById($trato->gettratousrsolicita()->getIdusuario());
                    $u1 = array('idusuario' => $usuario->getIdusuario(), 'nombre' => $usuario->getTxnickname());  
                    echo "...Usuario solicita [".$usuario->getTxnomusuario()."-".$usuario->getTxnickname()."] \n";
                } else {
                    $u1 = array('idusuario' => "", 'nombre' => "");                      
                    echo "...Usuario solicita NO ESPECIFICADO :: ERROR \n";
                } 
                    
                //echo "[ID_DESTINO: ".$mensaje->getInmenusuario()->getInusuario()."]\n";
                $usuario2 = ManejoDataRepository::getUsuarioById($trato->gettratousrdueno()->getIdusuario());
                $u2 = array('idusuario' => $usuario2->getIdusuario(), 'nombre' => $usuario2->getTxnickname());  
                echo "...Usuario dueño [".$usuario2->getTxnomusuario()."-".$usuario2->getTxnickname()."] \n";
                
                //Revisa si el usuario logueado es el solicitante o el dueño
                if (ManejoDataRepository::getUsuarioByEmail($pSolicitud->getEmail(), $em)==ManejoDataRepository::getUsuarioById($trato->gettratousrsolicita()->getIdusuario(), $em)) {
                   $logueadodueño = GamesController::inDatoCer;//Logueado es el solicitante
                   $tipotrx = GamesController::txEntrada;
                   echo $pSolicitud->getEmail()." logueado es el solicitante \n";
                } else {
                   $logueadodueño = GamesController::inDatoUno;//Logueado es el dueño
                   $tipotrx = GamesController::txSalida;
                   echo $pSolicitud->getEmail()." logueado es el dueño \n";
                }
                
                $cantalertas = ManejoDataRepository::getMensajesSinLeerUsuario($usrlogueado , $em);
                echo $cantalertas." alertas para el usuario ".$usrlogueado->getTxnickname()." \n";
                
                //Estado general del trato 0: Solicitado 1: Cancelado 2: Finalizado 
                if ($trato->getinestadotrato() == GamesController::inDatoCer) {
                    $sololectura = GamesController::inDatoCer;
                } else {
                    $sololectura = GamesController::inDatoUno;
                }
                /* ACCIONES DUEÑO 
                0. Ejemplar Solicitado: Cuando se ejecuta la solicitud
                1. Solicitud Cancelada
                2. Videojuego Recibido
                3. Queja impuesta
                4. Calificación realizada
                5. Conversación */
                $arrTmp[] = array('idtrato' => $trato->getidtrato(), 
                    'fecha' => $trato->getfefechatrato(),'estadotrato' => $trato->getinestadotrato(), 
                    'tipotransaccion' => $tipotrx, 'cantalertas' => $cantalertas,'dueno' => $u2, 'solicitante' => $u1, 
                    'sololectura' => $sololectura, 'accionsolicitante' => $trato->getintratoaccionsolicitante(),
                    'acciondueno' => $trato->getintratoacciondueno()
                ) ;
                
                //echo "ID Mensaje ".$mensaje->getInmensaje()."\n";
                
                unset($u1);
                unset($u2);

            }

            return array('idsesion' => array ('idaccion' => $pSolicitud->getAccion(),
                    'idtrx' => '', 'ipaddr'=> $pSolicitud->getIPaddr()), 
                    'idrespuesta' => array('respuesta' => $respuesta->getRespuesta(), 
                    'mensaje' => $arrTmp));
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }    
    

    /*
     * respuestaVerUsuarioOtro: 
     * Funcion que genera el JSON de respuesta para la accion de Recuperar Usuario Otro:: GamesController::txAccRecUsuar
     */
    public function respuestaVerUsuarioOtro(Respuesta $respuesta, Solicitud $pSolicitud, $parreglo){

        try {
            //$calificacion = new LbCalificausuarios();
            $usuario = new LbUsuarios();
            $arrTmp = array();
            $calificacion = new LbCalificausuarios();
            foreach ($respuesta->getArrCalificacionesReci() as $calificacion){
                //$usuario = ManejoDataRepository::getUsuarioById($calificacion->getIncalusucalifica()->getInusuario());
                
                $arrTmp[] = array('idcalifica'=>$calificacion->getInidcalifica(),
                    'usucalifica' => $calificacion->getIncalusucalifica()->getInusuario(), 
                    'califica' => $calificacion->getIncalcalificacion(),
                    'mensaje' => $calificacion->getTxcalcomentario(),
                    'ejemplar' => $calificacion->getIncalhisejemplar()->getInhistejemplar()
                ) ;
                
            }
            
            return array('idsesion' => array ('idaccion' => $pSolicitud->getAccion(),
                    'idtrx' => '', 'ipaddr'=> $pSolicitud->getIPaddr()), 
                    'idrespuesta' => array('respuesta' => $respuesta->getRespuesta(),
                    'usuario' => array('idusuario' => $respuesta->RespUsuarios[0]->getInusuario(), 
                        'nommostusuario' => $respuesta->RespUsuarios[0]->getTxusunommostrar(), 
                        'email' => $respuesta->RespUsuarios[0]->getTxusuemail(),
                        //La siguiente línea debe habilitarse, e integrar el CAST de BLOB a TEXT??
                        //'usuimagen' => $respuesta->RespUsuarios[0]->getTxusuimagen(), 
                        'usuimagen' => "DUMMY", "calificacion" => $respuesta->getPromCalificaciones(),
                        'comentarios' => $arrTmp))
                );
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }    
    
    /*
     * respuestaCerrarSesion: 
     * Funcion que genera el JSON de respuesta para la accion de Cerrar Sesion :: GamesController::txAccCerraSes
     */
    public static function respuestaCerrarSesion($respuesta, $pSolicitud){
        try {
            return array('idsesion' => array ('idaccion' => $pSolicitud->getAccion(),
                            'idtrx' => '', 'ipaddr'=> $pSolicitud->getIPaddr()), 
                            'idrespuesta' => (array('respuesta' => $respuesta->getRespuesta())));
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }    


    /*
        * respuestaActualizarDatosUsuario: 
     * Funcion que genera el JSON de respuesta para la accion de Actualizar datos de usuario:: GamesController::txAccActParam
     */
    public static function respuestaActualizarDatosUsuario(Respuesta $respuesta, Solicitud $pSolicitud){
        try {
            //echo "respuestaActualizarDatosUsuario : entra a la respuesta \n";
            return array('idsesion' => array ('idaccion' => $pSolicitud->getAccion(),
                            'idtrx' => '', 'ipaddr'=> $pSolicitud->getIPaddr()), 
                            'idrespuesta' => (array('respuesta' => $respuesta->getRespuesta())));
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }    
    
    /*
     * respuestaPublicaeEjemplar: 
     * Funcion que genera el JSON de respuesta para la accion de Publicar un ejemplar :: GamesController::txAccPubliEje:
     */
    
    public static function respuestaPublicarEjemplar(Respuesta $respuesta, Solicitud $pSolicitud, $em){
        try {
            return array('idsesion' => array ('idaccion' => $pSolicitud->getAccion(),
                    'idtrx' => '', 'ipaddr'=> $pSolicitud->getIPaddr()), 
                    'idrespuesta' => array('respuesta' => $respuesta->getRespuesta(), 
                    'ejemplar' => array('idejemplar' => $respuesta->getIdEjemplar(),'idejemusuario' => $respuesta->getIdEjemusuario(),
                    'titulo'=>$respuesta->getTitulo(), 'idvidjuego' => $respuesta->getIdvidjuego())));
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }    
    
    /*
     * respuestaCambiarClave: 
     * Funcion que genera el JSON de respuesta para la accion de Cambiar clave de usuario:: GamesController::txAccRecClave
     */
    public function respuestaCambiarClave(Respuesta $respuesta, Solicitud $pSolicitud){
        try {
            return array('idsesion' => array ('idaccion' => $pSolicitud->getAccion(),
                            'idtrx' => '', 'ipaddr'=> $pSolicitud->getIPaddr()), 
                            'idrespuesta' => (array('respuesta' => $respuesta->getRespuesta())));
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }    
    
    /*
     * respuestaSolicitarEjemplar: 
     * Funcion que genera el JSON de respuesta para la accion de solicitar ejemplar:: GamesController::txAccReaOfer
     */
    public function respuestaSolicitarEjemplar(Respuesta $respuesta, Solicitud $pSolicitud){
        try {
            return array('idsesion' => array ('idaccion' => $pSolicitud->getAccion(),
                            'idtrx' => '', 'ipaddr'=> $pSolicitud->getIPaddr()), 
                            'idrespuesta' => (array('respuesta' => $respuesta->getRespuesta())));
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }    
    

    
    /*
        * respuestaMarcarMensaje: 
     * Funcion que genera el JSON de respuesta para la accion de Marcar el mensaje como Leído o No leído:: GamesController::txAccMarcMens
     */
    public function respuestaMarcarMensaje(Respuesta $respuesta, Solicitud $pSolicitud){
        try {
            return array('idsesion' => array ('idaccion' => $pSolicitud->getAccion(),
                            'idtrx' => '', 'ipaddr'=> $pSolicitud->getIPaddr()), 
                            'idrespuesta' => (array('respuesta' => $respuesta->getRespuesta())));
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }    
    
    /*
        * respuestaListaIdiomas: 
     * Funcion que genera el JSON de respuesta para la accion de Listar Idiomas:: GamesController::inListarIdi

     */
    public function respuestaListaIdiomas(Respuesta $respuesta, Solicitud $pSolicitud, $parreglo){
        try {
            //echo "respuesta idiomas \n";
            //print_r(array_values($parreglo));
            return array('idsesion' => array ('idaccion' => $pSolicitud->getAccion(),
                            'idtrx' => '', 'ipaddr'=> $pSolicitud->getIPaddr()), 
                            'idrespuesta' => array('respuesta' => $respuesta->getRespuesta(), 
                            'idiomas' => $parreglo));
//                            'idrespuesta' => (array('respuesta' => $respuesta->getRespuesta(), 'idiomas' => array('ididioma'=>$parreglo[][0], 'nomidioma'=>$parreglo[][1]))));
            //echo "termino armar \n" ;
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }    
    
    /*
        * respuestaListaLugares: 
     * Funcion que genera el JSON de respuesta para la accion de Listar Lugares:: GamesController::inListarIdi

     */
    public function respuestaListaLugares(Respuesta $respuesta, Solicitud $pSolicitud, $parreglo){
        try {
            return array('idsesion' => array ('idaccion' => $pSolicitud->getAccion(),
                            'idtrx' => '', 'ipaddr'=> $pSolicitud->getIPaddr()), 
                            'idrespuesta' => (array('respuesta' => $respuesta->getRespuesta(), 'lugares' => $parreglo)));
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }    
    
    /*
        * respuestaMegustaEjemplar: 
     * Funcion que genera el JSON de respuesta para la accion de Dar Me gusta a ejemplar:: GamesController::txAccMegEjemp

     */
    public function respuestaMegustaEjemplar(Respuesta $respuesta, Solicitud $pSolicitud){
        try {
            return array('idsesion' => array ('idaccion' => $pSolicitud->getAccion(),
                            'idtrx' => '', 'ipaddr'=> $pSolicitud->getIPaddr()), 
                            'idrespuesta' => (array('respuesta' => $respuesta->getRespuesta(), 
                            'cantmegusta' => $respuesta->getCantMegusta(), 'cantcomenta' => $respuesta->getCantComenta())));
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }    
    
    /*
        * respuestaVerUsuariosMegustaEjemplar: 
     * Funcion que genera el JSON de respuesta para la accion de VEr usuarios Me gusta a ejemplar:: GamesController::txAccVerUsMeg

     */
    public function respuestaVerUsuMegustaEjemplar(Respuesta $respuesta, Solicitud $pSolicitud, $parreglo){
        try {
            return array('idsesion' => array ('idaccion' => $pSolicitud->getAccion(),
                            'idtrx' => '', 'ipaddr'=> $pSolicitud->getIPaddr()), 
                            'idrespuesta' => (array('respuesta' => $respuesta->getRespuesta(), 'usuarios' => $parreglo)));
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }    
    
    /*
        * respuestaComentarEjemplar: 
     * Funcion que genera el JSON de respuesta para la accion de comentar a ejemplar:: GamesController::txAccCommEjem

     */
    public function respuestaComentarioEjemplar(Respuesta $respuesta, Solicitud $pSolicitud){
        try {
            return array('idsesion' => array ('idaccion' => $pSolicitud->getAccion(),
                            'idtrx' => '', 'ipaddr'=> $pSolicitud->getIPaddr()), 
                            'idrespuesta' => (array('respuesta' => $respuesta->getRespuesta(), 
                            'cantmegusta' => $respuesta->getCantMegusta(), 'cantcomenta' => $respuesta->getCantComenta())));
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }    
    
    /*
        * respuestaEnviarMensajeChat: 
     * Funcion que genera el JSON de respuesta para la accion de enviarMensajeChat:: GamesController::txAccPublMens

     */
    public function respuestaEnviarMensajeChat(Respuesta $respuesta, Solicitud $pSolicitud, $parreglo){
        try {
            return array('idsesion' => array ('idaccion' => $pSolicitud->getAccion(),
                            'idtrx' => '', 'ipaddr'=> $pSolicitud->getIPaddr()), 
                            'idrespuesta' => (array('respuesta' => $respuesta->getRespuesta(), 'indacept' => $respuesta->getIndAcept(), 
                                'indotroacept' => $respuesta->getIndOtroAcept(), 'botonera' => $respuesta->getBotonesMostrar(), 'conversacion' => $parreglo )));
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }    

    /*
        * respuestaVerComentariosEjemplar: 
     * Funcion que genera el JSON de respuesta para la accion de comentar a ejemplar:: GamesController::txAccCommEjem

     */
    public function respuestaVerComentariosEjemplar(Respuesta $respuesta, Solicitud $pSolicitud, $parreglo){
        try {
            return array('idsesion' => array ('idaccion' => $pSolicitud->getAccion(),
                            'idtrx' => '', 'ipaddr'=> $pSolicitud->getIPaddr()), 
                            'idrespuesta' => (array('respuesta' => $respuesta->getRespuesta(), 'comentarios' => $parreglo)));
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }    
    
    // ex4play :: Adicioando $em
    public static function respuestaVisualizarBiblioteca(Respuesta $respuesta, Solicitud $pSolicitud, $parreglo, EntityManager $em){
        try{
            $arrTmp = array();
            $ejemplarUsuario = new Ejemplarusuario();
            $usuarioConsulta = ManejoDataRepository::getUsuarioByEmail($pSolicitud->getEmail(), $em);
            //echo "Va a generar la respuestaVisualizarBiblioteca \n";
            foreach ($parreglo as $ejemplarUsuario){
                //echo "respuestaVisualizarBiblioteca :: Para el ejemplarUsuario : ".$ejemplarUsuario->getidejemplarusuario(). " : \n"; 
                $ejemplar = $ejemplarUsuario->getejemplarusuarioejemplar();
                $consola = new Consola();
                $fabricante = new Fabricante();
                $videojuego = new Videojuego();
                $usuario = new Usuario();
                if ($respuesta->getRespuesta()== GamesController::inULogged){
                    $videojuego = ManejoDataRepository::getVideojuego($ejemplar->getejemplarVideojuego()->getidvideojuego(), $em);
                    //echo "videojuego: [".utf8_encode($videojuego->gettxnomvideojuego())."]\n";
                    //echo "ejemplar: [".$ejemplar->getInejemplar()."--".$ejemplar->getInejelibro()->getInlibro()."] \n";
                    $genero = utf8_encode($ejemplar->getejemplarVideojuego()->gettxgenerovideojuego());
                    //echo "...generos \n";
                    $consola = ManejoDataRepository::getConsola($ejemplar->getejemplarVideojuego()->getvideojuegoconsola(), $em);
                    //echo "...consola \n";
                    $fabricante = ManejoDataRepository::getFabricante($ejemplar->getejemplarVideojuego()->getvideojuegoconsola()->getconsolafabricante(), $em);
                    //echo "...fabricante \n";
                    //$arrHistEjemplar = ManejoDataRepository::getHistoriaEjemplarBiblioteca($ejemplar);
                    //echo "...histejemplar \n";
                    //$arrNegociacion = ManejoDataRepository::getNegociacionEjemplarBiblioteca($ejemplar, $usuarioConsulta);
                    //echo "...negociacion \n";
                    //$megusta = ManejoDataRepository::getMegustaEjemplar($ejemplar, $usuarioConsulta);
                    //echo "...megusta \n";
                    //$cantmegusta = ManejoDataRepository::getCantMegusta($ejemplar->getInejemplar());
                    //echo "...cantmegusta \n";
                    //$cantcomment = ManejoDataRepository::getCantComment($ejemplar->getidejemplar());
                    //echo "...cantcomment \n";
                    $usuario = ManejoDataRepository::getUsuarioById($ejemplarUsuario->getejemplarusuariousuario()->getIdusuario(), $em);
                    //echo "...usuario [".utf8_encode($usuario->getTxnickname())."] \n";
                    $promcalifica = ManejoDataRepository::getPromedioCalifica($usuario->getIdusuario(), $em);
                    //echo "...promcalifica \n";
                    $fecpublica = ManejoDataRepository::getFechaPublicacion($ejemplarUsuario, $usuario, $em);
                    //echo "...$fecpublica \n";
                    //echo "RECUPERO DATOS\n";
                
                    $arrConsola = array();
                    //echo "...consola [".utf8_encode($consola->gettxnombreconsola())."] \n";
                    $arrConsola[] = array('idonsola' => $consola->getidconsola(),
                        'txconnombre' => utf8_encode($consola->gettxnombreconsola()));
                    
                    $arrFabricante = array();
                    //echo "...fabricante [".utf8_encode($fabricante->gettxnomfabricante())."] \n";
                    $arrFabricante[] = array('idfabricante' => $fabricante->getidfabricante(),
                        'txfabnombre' => utf8_encode($fabricante->gettxnomfabricante()));

                    $titulo = utf8_encode($videojuego->gettxnomvideojuego());
                    //echo "...titulo [".utf8_encode($videojuego->gettxnomvideojuego())."] \n";
                    $barts = ManejoDataRepository::getPuntajeBarts($videojuego->getincategvideojuego()); //Cantidad de puntos
                    $publicado = $ejemplarUsuario->getinpublicado();
                    $ennegociacion = $ejemplarUsuario->getinnegociacion();
                    $bloqueado = $ejemplarUsuario->getinbloqueado();
                     
                    if (($ennegociacion == GamesController::inDatoCer)and($bloqueado == GamesController::inDatoCer)) {
                        $puedeeditar = GamesController::inDatoUno;
                    } else {
                        $puedeeditar = GamesController::inDatoCer;
                    }
                    
                    //echo "...barts [".$barts."] \n";
                    //$imagen = utf8_encode($ejemplar->getTxejeimagen());
                }
                //echo "...arma el arreglo [] \n";
                //@Cambio ejemplarusuario
                $arrTmp[] = array('idejemplar' => $ejemplar->getIdejemplar(), 
                    'idejemusuario' => $ejemplarUsuario->getidejemplarusuario(), 
                    'titulo' => $titulo, 
                    'barts' => $barts, 
                    //'imagen' => $imagen, 
                    'publicado' => $publicado,
                    'bloqueado' => $bloqueado,
                    'ennegociacion' => $ennegociacion,
                    'puedeeditar' => $puedeeditar,
                    'fechapublica' => $fecpublica,
                    //'megusta' => $megusta,
                    //'cantmegusta' => $cantmegusta,
                    'generos' => $genero,
                    'consola' => $arrConsola,
                    'fabricante' => $arrFabricante
                    //'histejemplar' => $arrHistEjemplar,
                    //'chats' => $arrNegociacion
                );
                //echo "...armado [] \n";
                
            }
           
            return array('idsesion' => array ('idaccion' => $pSolicitud->getAccion(),
                    'idtrx' => '', 'ipaddr'=> $pSolicitud->getIPaddr()), 
                    'idrespuesta' => array('respuesta' => $respuesta->getRespuesta(), 
                    'ejemplares' => $arrTmp ));

        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }    
    
    
    /*
        * respuestaCalificarUsuarioTrato: 
     * Funcion que genera el JSON de respuesta para la accion de CAlificarUsuarioTRato:: GamesController::txAccCaliTrat

     */
    public function respuestaCalificarUsuarioTrato(Respuesta $respuesta, Solicitud $pSolicitud){
        try {
            return array('idsesion' => array ('idaccion' => $pSolicitud->getAccion(),
                            'idtrx' => '', 'ipaddr'=> $pSolicitud->getIPaddr()), 
                            'idrespuesta' => (array('respuesta' => $respuesta->getRespuesta())));
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }    
    
    
    /*
     * generaCadenaURL 
     * Combina datos para entregar URL de Registro
     */
    public static function generaCadenaURL(Usuario $usuario)
    {   
        //Cantidad de caracteres del mail
        $caracEmail = strlen($usuario->getTxmailusuario());
        //Arreglo de caractéres email
        if ($caracEmail > 99)  {
            $arCarMail[0] = floor($caracEmail / 100);
        } else {
            $arCarMail[0] = 0;
        }
        if ($caracEmail > 99)  {
            $arCarMail[1] = floor(($caracEmail-($arCarMail[0] * 100)) / 10);
        } else {
            $arCarMail[1] = floor($caracEmail / 10);
        }
        if ($caracEmail > 9)  {
            $arCarMail[2] = floor($caracEmail-(($arCarMail[0].$arCarMail[1])*10));
        } else {
            $arCarMail[2] = $caracEmail;
        }
        //echo "\nCaracteres Mail: ".$caracEmail;

        //echo "\nCar Mail 0: ".$arCarMail[0];
        //echo "\nCar Mail 1: ".$arCarMail[1];
        //echo "\nCar Mail 2: ".$arCarMail[2];
        //email
        $email = $usuario->getTxmailusuario();
        //Inicializa la cadena
        $cadena = $usuario->getTxusuvalidacion();

        //echo 'arreglo: '.$arCarMail[0].'-'.$arCarMail[1].'-'.$arCarMail[2].'carac email: '.$caracEmail.'    -   valida: '.$cadena;
        //Obtener el patron de ocurrencia de datos
        $patron[0] = rand(1, 3);
        //echo "\nP1: ".$patron[0];
        $patron[1] = rand(1, 2);
        //echo "\nP2: ".$patron[1];
        $patron[2] = rand(1, 3);
        //echo "\nP3: ".$patron[2];

        $cadena = substr($cadena, 0, self::pos1mail).$arCarMail[0].$patron[0].$arCarMail[1].$patron[1]
                .$arCarMail[2].$patron[2].substr($cadena,2);

        //echo "\ncon patron: ".$cadena;

        /*for ($n=0;$n<$caracEmail;$n++) {
                $posClave[] = $patron[$pat]+7;
        }*/
        
        $pat = 0;
        for ($n=0;$n<$caracEmail;$n++) {
            if ($n==0) {
                $posClave[$n] = $patron[$pat]+8;
            } else {
                $posClave[$n] = $posClave[$n-1]+$patron[$pat];
            }
            
            //echo 'posicion: '.$posClave[$n];
            if ($pat==2) { $pat = 0; } else { $pat++; }
        }
        
        for($i=0;$i<$caracEmail;$i++) {
            $complem = substr($cadena, $posClave[$i]);
            //echo " \n ".$complem;
            $cadena = substr($cadena, 0, $posClave[$i]).substr($email,$i,1).$complem;
            //echo " \n ".$cadena;
        } 
        //echo "\n cadena_def: ".$cadena;
        return $cadena;
    }
    
    /*
     * generaRand: 
     * Funcion que genera un ID aleatorio de la cantidad solicitada en el parámetro
     */
    public function generaRand($tamano){

        $patron = "1234567890abcdefghijklmnopqrstuvwxyz+~*-"; 
        $key = "";
        
        for($i = 0; $i < $tamano; $i++) { 
            $key .= $patron{rand(0, 39)}; 
        } 
        //echo "<script>alert('Generó clave de ".$tamano.": ".$key."')</script>";
        return $key;         
    }
    
    public function validarRegistroGeneradoUsuario($usuario, $clave, $em)
    {
        try {
            //echo "logica:usr ".$usuario;
            //echo "logica:clave ".$clave;
            $vUsuario = new Usuario();
            $vUsuario = ManejoDataRepository::datosUsuarioValidos($usuario, $clave, $em);
            $respuesta = GamesController::inExitoso;
            if ($vUsuario == NULL) { $respuesta = GamesController::inFallido; }
            
            if ($respuesta==GamesController::inExitoso) {
                $respuesta = ManejoDataRepository::activarUsuarioRegistro($vUsuario, $em);
            }
            return $respuesta;
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }

    public function respuestaListaEditoriales(Respuesta $respuesta, Solicitud $pSolicitud, $parreglo){
        try {
            //echo "respuesta idiomas \n";
            //print_r(array_values($parreglo));
            return array('idsesion' => array ('idaccion' => $pSolicitud->getAccion(),
                            'idtrx' => '', 'ipaddr'=> $pSolicitud->getIPaddr()), 
                            'idrespuesta' => array('respuesta' => $respuesta->getRespuesta(), 
                            'editoriales' => $parreglo));
//                            'idrespuesta' => (array('respuesta' => $respuesta->getRespuesta(), 'idiomas' => array('ididioma'=>$parreglo[][0], 'nomidioma'=>$parreglo[][1]))));
            //echo "termino armar \n" ;
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }    
    
    public static function bin2text($bin_str) 
    { 
        $text_str = ''; 
        $chars = explode("\n", chunk_split(str_replace("\n", '', $bin_str), 8)); 
        $_I = count($chars); 
        for ($i = 0; $i < $_I; $i++) 
        { $text_str .= chr(bindec($chars[$i])); }
        return $text_str; 
    } 
     

    //ex4plays :: Modificado
    //Adicionado el $em
    public function registroUsuario($pSolicitud, $em)
    {   
        $respuesta = new Respuesta();

        $usuario = new Usuario();
        //$sesion = new LbSesiones();
        //$actsesion = new LbActsesion();
        
        //Lugar por default (Es el de ID = 1)
        $Lugar = ManejoDataRepository::getLugar(GamesController::inExitoso, $em);
        
        //Valida que el usuario no existe
        if (!ManejoDataRepository::getUsuarioByEmail($pSolicitud->getEmail(), $em)){
            try {
                //Guarda el usuario
                //echo "<script>alert('Usuario [".$pSolicitud->getEmail()." ] NO existe')</script>";
                $usuario=$usuario->creaUsuario($pSolicitud, $Lugar);

                ManejoDataRepository::persistEntidad($usuario, $em);
                //echo "<script>alert('Persiste usuario')</script>";
                
                //Selecciona el plan gratuito por ahora
                //$plan = new Plansuscripcion();
                $plan = ManejoDataRepository::getPlanGratuito($em);
                
                $planusuario = new Planusuario();
                $planusuario->setplanusuarioplan($plan);
                $planusuario->setdbvalsuscripcion($plan->getdbvalsuscripcion());
                $planusuario->setfevigencia($plan->getfevigencia());
                $planusuario->setplanusuariousuario($usuario);
                
                ManejoDataRepository::persistEntidad($planusuario, $em);
                
                //echo "\n registroUsuario :: Guardó usuario...va a generar sesion";
                setlocale (LC_TIME, "es_CO");
                $fecha = new \DateTime('c');
                //echo "<script>alert('fecha ')</script>";

                $sesion = ManejoDataRepository::generaSesion($usuario,GamesController::inDatoCer,$fecha,$fecha,$pSolicitud->getIPaddr(),$em);
                //echo "\n registroUsuario :: Generó sesion";
                //Guarda la actividad de la sesion:: Como finalizada
                ManejoDataRepository::generaActSesion($sesion,GamesController::inDatoUno,GamesController::txMensaje,$pSolicitud->getAccion(),$fecha,$fecha,$em);
                //echo "\n registroUsuario :: Generó actividad de sesion";

                //Envia email
                //$Logica = new Logica();
                
                //OJO ex4playS 15 Mayo 2018 : Se debe activar esta linea para que envíe correo de confirmación
                //echo "\n registroUsuario :: Va a enviar mail";
                
                error_reporting(E_ERROR);
                $transport = (new \Swift_SmtpTransport('p3plcpnl0478.prod.phx3.secureserver.net', 25))
                    ->setUsername('ex4play@baisica.co')
                    ->setPassword('eX.fouR.pl4y$');
                $mailer = new \Swift_Mailer($transport);
                EnviaMailController::enviaMailRegistroAction($usuario, $mailer);
                //echo "\n registroUsuario :: Envió mail";

                $respuesta->setRespuesta(GamesController::inExitoso);
                
                //echo "<script>alert('Respuesta de registro NORMAL ".$respuesta->getRespuesta()." Error')</script>";
                return Logica::generaRespuesta($respuesta, $pSolicitud, NULL, $em);

            } catch (Exception $ex) {
                //echo "<script>alert('Respuesta de registro ERROR ".$respuesta->getRespuesta()." Error')</script>";
                $respuesta->setRespuesta(GamesController::inPlatCai);
                return Logica::generaRespuesta($respuesta, $pSolicitud, NULL, $em);
            } 
        } else {
            //El usuario existe y no es posible registrarlo de nuevo:: el email.
            //echo "<script>alert('Usuario existe')</script>";
            $respuesta->setRespuesta(GamesController::inFallido);
            return Logica::generaRespuesta($respuesta, $pSolicitud, NULL, $em);
        }
            
    }

    
}
