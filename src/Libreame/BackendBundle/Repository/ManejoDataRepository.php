<?php

namespace Libreame\BackendBundle\Repository;

use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query;
use Libreame\BackendBundle\Controller\GamesController;
use Libreame\BackendBundle\Helpers\Respuesta;
use Libreame\BackendBundle\Helpers\Solicitud;
use Libreame\BackendBundle\Entity\Lugar;
use Libreame\BackendBundle\Entity\Usuario;
use Libreame\BackendBundle\Entity\Sesion;
use Libreame\BackendBundle\Entity\Actsesion;
use Libreame\BackendBundle\Helpers\Logica;
use Libreame\BackendBundle\Entity\Plansuscripcion;
use Libreame\BackendBundle\Entity\Planusuario;
use Libreame\BackendBundle\Entity\Puntosusuario;
use Libreame\BackendBundle\Entity\Calificatrato; 
use Libreame\BackendBundle\Entity\Ejemplarusuario;
use Libreame\BackendBundle\Entity\Ejemplar;
use Libreame\BackendBundle\Entity\Videojuego;
use Libreame\BackendBundle\Entity\Consola;
use Libreame\BackendBundle\Entity\Fabricante;


/**
 * Description of ManejoDataRepository
 *
 * @author mramirez
 */
class ManejoDataRepository extends EntityRepository {

    var $inImagenValida;
    //BARTs * Caegoría
    //var $puntajeBARTs = ['1' => 50, '2' => 30, '3' => 10];
    //private $em;
     

    ///********************* LO QUE SE USA ********************************///
    
    
    //ex4plays :: Obtiene la cantidd de puntos de una categoría
    public static function getPuntajeBarts($incategoria)
    {   
        //echo "\n getPuntajeBarts : Ingresa ";
        //BARTs * Caegoría
        //Las categorías serian:
        //1. Juegos con menos de 6 meses de publicado = 50 Barts
        //2. Juegos entre 6 meses y 1 año de liberados  = 30 Barts
        //3. Juegos de 1 año o mas   = 10 Barts
        //4. Juegos de popularidad / demanda alta (Sin importar la antiguedad) = 50 Barts
        //5. Juegos de popularidad / demanda baja (Sin importar la antiguedad) = 10 Barts
        $puntajeBARTs = ['1' => 50, '2' => 30, '3' => 10, '4' => 50, '5' => 10];
        try{
            //$barts = ManejoDataRepository::$puntajeBARTs[$incategoria];
            $barts = $puntajeBARTs[$incategoria];
            
            return $barts;
        } catch (Exception $ex) {
                return GamesController::inDatoCer;
        } 
    }
    //ex4plays :: Obtiene el objeto Usuario según su EMAIL
    public static function getUsuarioByEmail($txemail, $em)
    {   
        //error_reporting(E_ALL);
        //echo "\n getUsuarioByEmail : Ingresa ";
        try{
            //echo "\n getUsuarioByEmail : Ingresa ".$txemail;
            return $em->getRepository('LibreameBackendBundle:Usuario')->
                findOneBy(array('txmailusuario' => $txemail));
        } catch (Exception $ex) {
                return new Usuario();
        } 
    }
    //ex4plays :: Obtiene el objeto Usuario según su EMAIL
    public static function getUsuarioByMail($txemail, $em)
    {   //error_reporting(E_ALL);
        //echo "\n getUsuarioByEmail : Ingresa ";
        try{
            //echo "\n getUsuarioByEmail : Ingresa ".$txemail;
            return $em->getRepository('LibreameBackendBundle:Usuario')->
                findOneBy(array('txmailusuario' => $txemail));
        } catch (Exception $ex) {
                return new Usuario();
        } 
    }
    
    //ex4plays :: Obtiene el objeto Lugar según su ID 
    public static function getLugar($inlugar, EntityManager $em)
    {   
        try{
            return $em->getRepository('LibreameBackendBundle:Lugar')->
                    findOneBy(array('inlugar' => $inlugar));
        } catch (Exception $ex) {
                return new Lugar();
        } 
    }
    
    
    public static function validaSesionUsuario(Solicitud $psolicitud, $em)
    {   
        $respuesta = GamesController::inPlatCai;
        try{
            //Verifica que el usuario exista, que esté activo, que la clave coincida
            //que corresponda al dispositivo, y que la sesion esté activa

            //echo "validaSesionUsuario : Ingresa validar sesion :: ".$psolicitud->getEmail()." \n";
            $respuesta = GamesController::inUsSeIna; //Inicializa como sesion logueada

            $usuario = new Usuario();
            //echo "validaSesionUsuario :: Valida si existe el USUARIO \n";
            if (!$em->getRepository('LibreameBackendBundle:usuario')->
                        findOneBy(array('txmailusuario' => $psolicitud->getEmail()))){
                //echo "validaSesionUsuario :: No existe el USUARIO \n";
                $respuesta = GamesController::inUsClInv; //Usuario o clave inválidos
            } else {    
                //echo "validaSesionUsuario :: Recupera el usuario en una variable \n";
                $usuario = $em->getRepository('LibreameBackendBundle:usuario')->
                        findOneBy(array('txmailusuario' => $psolicitud->getEmail()));

                $estado = $usuario->getInusuestado();
                //echo "validaSesionUsuario :: encontro el usuario: estado : ".$estado." \n";

                //Si el usuario está INACTIVO
                if ($estado != GamesController::inUsuActi)
                {
                    //echo "validaSesionUsuario :: Usuario inactivo \n";
                    $respuesta = GamesController::inUsuConf; //Usuario Inactiva
                } else {
                    //Si la clave enviada es inválida
                    //echo "validaSesionUsuario :: revisa si la clave es correcta \n";
                    if ($usuario->getTxclaveusuario() != $psolicitud->getClave()){
                        //echo "validaSesionUsuario :: Clave invalida \n";
                        $respuesta = GamesController::inUsClInv; //Usuario o clave inválidos
                    } else {
                        //Valida si la sesion está activa
                        //echo "validaSesionUsuario :: Verifica se la sesion está activa \n";
                        if (!ManejoDataRepository::usuarioSesionActiva($psolicitud, $psolicitud->getSession(), $em)){
                            //echo "validaSesionUsuario :: Sesion inactiva \n";
                            $respuesta = GamesController::inUsSeIna; //Sesion inactiva

                        } else {
                            $respuesta = GamesController::inULogged; //Sesion activa
                            //echo "validaSesionUsuario :: La sesion es VALIDA \n";
                        }
                    }   
                }
            }

            //Flush al entity manager
            //echo "\n validaSesionUsuario :: va a generar respuesta ";
            $em->flush();
            //echo "\n validaSesionUsuario: RESPUESTA [".$respuesta."]";
            //echo "\n validaSesionUsuario :: Finaliza \n ";
            return ($respuesta);
        } catch (Exception $ex) {
            return ($respuesta);
        }    
    }

    /*
     * usuarioSesionActiva 
     * Indica si una sesion para un usuario esta activa
     * ex4plays :: Adiciona $em y ajusta con entidades del modelo 
     */
    public static function usuarioSesionActiva($psolicitud, $idsesion, $em)
    {   
        try {

            //echo "\n usuarioSesionActiva :: Inicia ".$idsesion;
            $usuario = ManejoDataRepository::getUsuarioByEmail($psolicitud->getEmail(), $em);
            //echo "<script>alert('EXISTE Sesion activa ".$device->getIndispusuario()."')</script>";
            //echo "\n usuarioSesionActiva :: Usuario : ".$usuario->getTxmailusuario();
            //$sesion = new Sesion();
            if ($idsesion == NULL)
            {
                //echo "\n usuarioSesionActiva :: ...la sesion Es null...";
                $sesion = NULL;
            } else {
                //echo "\n usuarioSesionActiva :: ...la sesion No es null... - [".$idsesion."]";
                $estadoSesion = GamesController::inDatoCer;
                //echo "\n usuarioSesionActiva :: va a recuperar el estado de la sesion - [".$idsesion."]";
                $sesion = ManejoDataRepository::recuperaEstadoSesionUsuario($usuario, $psolicitud, $em, $estadoSesion);
                if ($sesion == NULL) {
                   $txsesion =  "";
                } else {
                    $txsesion = utf8_decode($sesion->gettxsesnumero());
                }
                //echo "\n usuarioSesionActiva :: Estado para sesion = ".$estadoSesion;
                
                switch ($estadoSesion) {
                    case GamesController::inDatoCer: //Error, debe especificar sesion : El usuario tiene una sesion activa
                        //echo "\n usuarioSesionActiva :: Error - debe especificar la sesion válida";
                        break;
                    case GamesController::inDatoUno: //Logear
                        //echo "\n usuarioSesionActiva :: Logear";
                        break;
                    case GamesController::inDatoDos: //Logín valido : No cambia sesion, registra intento de relogeo
                        //echo "\n usuarioSesionActiva :: Sesion válida";
                        break;
                    case GamesController::inDatoTre: //Error, Sesion inválida :: Se da mensaje de sesion inactiva
                        //echo "\n usuarioSesionActiva :: Error sesion invalida ";
                        break;

                    default:
                        break;
                } 
                //echo "\n  usuarioSesionActiva :: Sesion = ... [".$txsesion."]";
            }

            //Flush al entity manager
            $em->flush(); 
            
            //echo "\n usuarioSesionActiva :: Finalizó \n";
            if ($sesion == NULL) {
                //echo " usuarioSesionActiva :: retorna FALSE";
                return FALSE;  
            } else if($estadoSesion == GamesController::inDatoDos) {
                //echo "\n  usuarioSesionActiva :: retorna TRUE";
                return TRUE;
            } else {
                //echo " usuarioSesionActiva :: retorna FALSE";
                return FALSE;  
            }
            
        } catch (Exception $ex) {
            //echo $ex->getMessage();
            return (FALSE);
        }    
            
    }
    
    /*
     * GeneraSesion 
     * Guarda en BD y Devuelve el ID de la sesion
     * Recibe una cadena con los datos del usuario
     * Usuario/Password{cifrado}/FechaHora{Esta se guarda en el dispositivo para que sirva como clave}
     * Id/nombre dispositivo
     * 
     * ex4plays :: eliminada la variable DEVICE 
     * Actualizado llamado al objeto lógica
     */
    public function generaSesion($usuario,$pEstado,$pFecIni,$pFecFin,$pIpAdd,$em)
    {
        //Guarda la sesion inactiva
        //echo "<script>alert('Ingresa a generar sesion".$pFecFin."-".$pFecIni."')</script>";
        try{
            //$objLogica = $this->get('logica_service');

            //if ($em == NULL) { $flEm = TRUE; } else  { $flEm = FALSE; }
            //if ($flEm) {$em = $this->getDoctrine()->getManager();}
            $sesion = new Sesion();
            $sesion->setsesionusuario($usuario);
            $sesion->setinsesactiva($pEstado);
            $sesion->settxsesnumero(Logica::generaRand(GamesController::inTamSesi));
            $sesion->setfesesfechaini($pFecIni);
            $sesion->setfesesfechafin($pFecFin);

            $sesion->settxipaddr($pIpAdd);
            $em->persist($sesion);
            //echo "<script>alert('Guardo sesion')</script>";
            //if ($flEm) {$em->flush();}
            $em->flush();
            //echo "<script>alert('Retorna".$sesion->getTxsesnumero()."')</script>";
            return $sesion;
            
        } catch (Exception $ex) {
               //echo "<script>alert('Error guardar sesion')</script>";
                return GamesController::inPlatCai;
        } 
    }
    /*
     * GeneraActSesion 
     * ex4play :: Ajustado al modelo
     */
    public static function generaActSesion(Sesion $pSesion,$pFinalizada,$pMensaje,$pAccion,$pFecIni,$pFecFin,$em)
    {
        //echo "<script>alert('Ingresa a generar actividad de sesion".$pFecFin."-".$pFecIni."')</script>";
        try{
            //echo "<script>alert('::::Actividad Sesion".$pFecFin."-".$pFecIni."')</script>";
            //echo "<script>alert('::::Actividad accion ".$pAccion."')</script>";
            $actsesion = new Actsesion();
            //$actsesion->setInactsesiondisus($pSesion->getInsesdispusuario());
            $actsesion->setactsesionInsesion($pSesion);
            $actsesion->setinactaccion($pAccion);
            $actsesion->setfeactfecha($pSesion->getfesesfechaini());
            $actsesion->setinactfinalizada($pFinalizada);
            $actsesion->settxactmensaje($pMensaje);
            //echo "<script>alert('::::Antes de persist act sesion')</script>";
            $em->persist($actsesion);
            //echo "<script>alert('::::antes de flush act sesion')</script>";
            $em->flush();
            //echo "<script>alert('::::despues de flush act sesion')</script>";
 
            return $actsesion;
            
        } catch (\Doctrine\DBAL\DBALException  $ex) {
                //echo "<script>alert('::::".$ex->getMessage()."')</script>";
                return GamesController::inPlatCai;
        } 
    }


    /*
     * NUEVA ex4plays: recuperaEstadoSesionUsuario
     * Si la sesion enviada es NULL, se recupera cualquier sesion activa del usuario
     * Si la sesin enviada no es NULL se busca directamente la sesion 
     * Valida los datos de la sesion verificando que sea veridica
     * ex4plays :: Adiciona $em y ajusta con entidades del modelo 
     */
    public static function recuperaEstadoSesionUsuario(Usuario $pusuario, Solicitud $psolicitud, $em, &$estadoSesion)
    {   //echo "\n recuperaEstadoSesionUsuario :: ****************************************** ";
        try{
            //echo "\n recuperaEstadoSesionUsuario :: ****************************************** ";
            //echo "\n recuperaEstadoSesionUsuario :: ENTRA A RECUPERAR SESION ".$txsesion;
            $txsesion = utf8_encode($psolicitud->getSession());
            //echo "\n recuperaEstadoSesionUsuario :: Busca sesion activa del usuario ";
            $respuesta = $em->getRepository('LibreameBackendBundle:Sesion')->findOneBy(array(
                            //'txsesnumero' => $txsesion,
                            'sesionusuario' => $pusuario,
                            'insesactiva' => GamesController::inSesActi));
            if ($respuesta == NULL) {
                $txsesion_encontrada = "";
            } else {
                $txsesion_encontrada = utf8_encode($respuesta->gettxsesnumero());
            }
            //echo "\n recuperaEstadoSesionUsuario :: Termina de Buscar sesion activa del usuario ".$txsesion;
            //Busca la sesion, si no esta asociado al usuario envia mensaje de sesion no existe
            if ($txsesion == NULL) {
                //echo "\n recuperaEstadoSesionUsuario :: 1.1 Sesion NULL ";
                
                if ($respuesta == NULL) {
                    //echo "\n recuperaEstadoSesionUsuario :: 1.1.2 No logueado NO EXISTE SESION";
                    $estadoSesion = GamesController::inDatoUno;
                    $respuesta = NULL;
                } else {
                    //echo "\n recuperaEstadoSesionUsuario :: 1.1.1 Logueado EXISTE SESION :: Mensaje de error para especificar la sesion";
                    $estadoSesion = GamesController::inDatoCer;
                }    
            } else { //La sesion no es NULL
                //echo "\n recuperaEstadoSesionUsuario :: 1.2 Sesion NO ES NULL ".utf8_encode($txsesion_encontrada);
                if ($txsesion == $txsesion_encontrada){
                    //echo "\n recuperaEstadoSesionUsuario :: 1.2.1 Valido :  La sesión es correcta ".$txsesion ." - ".$txsesion_encontrada;
                    $estadoSesion = GamesController::inDatoDos;
                    //$respuesta = NULL;
                } else {
                    //echo "\n recuperaEstadoSesionUsuario :: 1.2.2 NO Valido :  La sesión NO ES VALIDA ".$txsesion ." - ".$txsesion_encontrada;
                    $estadoSesion = GamesController::inDatoTre;
                    $respuesta = NULL;
                }
            }
            //Flush al entity manager
            $em->flush();

            //echo "\n recuperaEstadoSesionUsuario :: [ El numero de sesion : ".$txsesion_encontrada." ] -- [ El estado devuelto : ".$estadoSesion." ]";
            //echo "\n recuperaEstadoSesionUsuario :: Finalizó****************************************** \n";
            return ($respuesta);//Retorna objeto tipo Sesion
        } catch (Exception $ex) {
                //echo "\n 4. Error ";
                return new Sesion();
        } 
    }

    //Obtiene el plan gratuito básico :: el de ID = 1
    public function getPlanGratuito($em)
    {   
        try{
            //El de ID uno es el plan gratuito por ahora
            //$plan = new Plansuscripcion();
            $plan = $em->getRepository('LibreameBackendBundle:Plansuscripcion')->
                    findOneBy(array('idplansuscripcion' => GamesController::inDatoUno));
            
            return $plan;

        } catch (Exception $ex) {
                //ECHO "ERROR PLANES";
                return new Plansuscripcion();
        } 
    }
                
    //Obtiene el plan del usuario
    public static function getPlansuscripcion(Planusuario $planusuario, $em)
    {   
        try{            
            $plan = $em->getRepository('LibreameBackendBundle:Plansuscripcion')->
                    findOneBy(array('idplansuscripcion' => $planusuario->getidplanusuario()));
            return $plan;
            
        } catch (Exception $ex) {
                //ECHO "ERROR PLANES";
                return new Plansuscripcion();
        } 
    }

    //Obtiene el plan del usuario
    public static function getPlanUsuario(Usuario $usuario, $em)
    {   
        try{            
            $planus = new Planusuario();
            $planus = $em->getRepository('LibreameBackendBundle:Planusuario')->
                    findOneBy(array('planusuariousuario' => $usuario));
            
            return $planus;
            
        } catch (Exception $ex) {
                //ECHO "ERROR PLANES";
                return new Planusuario();
        } 
    }
                
    //Obtiene la suma de puntos de usuario
    public static function getPuntosUsuario(Usuario $pUsuario, $em)
    {   
        try{
    
            $qpu = $em->createQueryBuilder()
                ->select('COALESCE(SUM(a.inpuntaje), 0) AS inpuuscantpuntos')
                ->from('LibreameBackendBundle:Puntosusuario', 'a')
                ->Where('a.puntosusuariousuario = :pusuario')
                ->setParameter('pusuario', $pUsuario)
                ->setMaxResults(1);
            
            $puntos = GamesController::inDatoCer;
            if($qpu->getQuery()->getOneOrNullResult() == NULL){
                $puntos = GamesController::inDatoCer; //Si ho hay registros devuelve Puntos = 0
            } else {
                $puntos = (int)$qpu->getQuery()->getSingleScalarResult();//Si hay registros devuelve lo que hay
            }    
            
            return $puntos;
        } catch (Exception $ex) {
                return GamesController::inDatoCer;
        } 
    }

    //Obtiene las calificaciones RECIBIDAS por un usuario
    public static function getCalificaUsuarioRecibidas(Usuario $usuario, $em)
    {
        try{
            return $em->getRepository('LibreameBackendBundle:Calificatrato')->
                    findBy(array('calificatrUsrcalificado' => $usuario));
        } catch (Exception $ex) {
                //echo "error";
                return new Calificatrato();
        } 
    }
    
    //Obtiene las calificaciones REALIZADAS por un usuario
    public static function getCalificaUsuarioRealizadas(Usuario $usuario, $em)
    {
        try{
            return $em->getRepository('LibreameBackendBundle:Calificatrato')->
                    findBy(array('calificatrUsrcalifica' => $usuario));

        } catch (Exception $ex) {
                return new Calificatrato();
        } 
    }

    //Obtiene la cantidad de Comentarios del ejemplar : Condicion : Comentarios activos
    public static function getPromedioCalifica($inusuario, $em)
    {   
        try{
            //echo "getPromCalificaciones :: ".":: \n";

            $qs = $em->createQueryBuilder()
                ->select('sum(a.incalificacion)')
                ->from('LibreameBackendBundle:Calificatrato', 'a')
                ->Where('a.calificatrUsrcalificado = :pusuario')
                ->setParameter('pusuario', $inusuario);
            $suma = $qs->getQuery()->getSingleScalarResult();
            
            $qc = $em->createQueryBuilder()
                ->select('count(a)')
                ->from('LibreameBackendBundle:Calificatrato', 'a')
                ->Where('a.calificatrUsrcalificado = :pusuario')
                ->setParameter('pusuario', $inusuario);
            $cant = $qc->getQuery()->getSingleScalarResult();
            if($cant == 0)
                $promedio = 0;
            else    
                $promedio = $suma / $cant;
            //echo "\n promedio:".$promedio;
            return $promedio;
        } catch (Exception $ex) {
                return GamesController::inDatoCer;
        } 
    }
    
    //Obtiene el resumen de ejemplares del usuario
    public static function getResumenUsuario(Usuario $usuario, $em)
    {   
        try{
            //Arreglo para almacenar el resumen
            $arrResumen = array();
            //Cantidad de ejemplares de un usuario
            
            $qej = $em->createQueryBuilder()
                ->select('COALESCE(count(e), 0)')
                ->from('LibreameBackendBundle:ejemplarusuario', 'e')
                ->Where(' e.ejemplarusuariousuario = :usuario ')
                ->setParameter('usuario', $usuario)
                ->setMaxResults(1);
            $ejemplares = (Int)$qej->getQuery()->getSingleScalarResult();
            
            $qen = $em->createQueryBuilder()
                ->select('COALESCE(count(e), 0)')
                ->from('LibreameBackendBundle:trato', 'e')
                ->Where(' e.tratousrdueno = :usuario ')
                ->setParameter('usuario', $usuario)
                ->andWhere(' e.inestadotrato = :entregado ')
                ->setParameter('entregado', GamesController::inDatoCer) // 0 es trato Cerrado
                ->setMaxResults(1);
            $entregados = (Int)$qen->getQuery()->getSingleScalarResult();
            
            $qre = $em->createQueryBuilder()
                ->select('COALESCE(count(e), 0)')
                ->from('LibreameBackendBundle:trato', 'e')
                ->Where(' e.tratousrsolicita = :usuario ')
                ->setParameter('usuario', $usuario)
                ->andWhere(' e.inestadotrato = :recibido ')
                ->setParameter('recibido', GamesController::inDatoCer) // 0 es trato Cerrado
                ->setMaxResults(1);
            $recibidos = (Int)$qre->getQuery()->getSingleScalarResult();
            
            
            $arrResumen[] = array('ejemplares' => $ejemplares, 'entregados' => $entregados, 
                'recibidos' => $recibidos);

        return $arrResumen;
        } catch (Exception $ex) {
                //ECHO "ERROR PLANES";
                return array();
        } 
    }
                
    //Obtiene las preferencias del usuario
    public static function getPreferenciasUsuario(Usuario $usuario, $numpref, $em)
    {   
        //echo "Dentro de preferencias  \n";
        try{
            //Arreglo para almacenar el resumen
            $arrPreferencias = array();

            //Cantidad de ejemplares de un usuario
            
            //Ejemplar del usuario
            //$ejeusu  = new Ejemplarusuario();
            $ejeusu = $em->getRepository('LibreameBackendBundle:Ejemplarusuario')->
                    findBy(array('ejemplarusuariousuario' => $usuario));
            
            //$ejemplar = new Ejemplar();
            $ejemplar = $em->getRepository('LibreameBackendBundle:Ejemplar')->
                    findBy(array('idejemplar' => $ejeusu));
            
            //echo "Dentro de preferencias  \n";
            $videojuego = $em->getRepository('LibreameBackendBundle:Videojuego')->
                    findBy(array('idvideojuego' => $ejemplar));
            
            //generos
            /*$qg = $em->createQueryBuilder()
                ->select('g.ingenero, g.txgennombre, count(g.ingenero) as num')
                ->from('LibreameBackendBundle:LbGeneros', 'g')
                ->leftJoin('LibreameBackendBundle:LbGeneroslibros', 'gl', \Doctrine\ORM\Query\Expr\Join::WITH, 'gl.ingligenero = g.ingenero')
                ->Where(' gl.inglilibro in (:libro) ')
                ->setParameter('libro', $libusu)
                ->groupBy('g.ingenero')
                //->having(' count(g.ingenero) > 1')
                ->orderBy(' num ', 'DESC')
                ->setMaxResults($numpref);
            
            $generos = $qg->getQuery()->getResult();*/
            $arrGeneros = array();
            /*foreach ($generos as $gen){
                if (!in_array($gen, $arrGeneros)) {
                    $arrGeneros[] = array("idgenero" => $gen['ingenero'],"nomgenero" => utf8_encode($gen['txgennombre']));
                }
            }*/
            //echo "Cargó arreglo generos \n";
            
        
            //Consolas
            $qc = $em->createQueryBuilder()
                ->select('c.idconsola, c.txnombreconsola, count(c.idconsola) as num')
                ->from('LibreameBackendBundle:consola', 'c')
                ->leftJoin('LibreameBackendBundle:videojuego', 'cv', \Doctrine\ORM\Query\Expr\Join::WITH, 'cv.videojuegoConsola = c.idconsola')
                ->Where(' cv.idvideojuego in (:videojuego) ')
                ->setParameter('videojuego', $videojuego)
                ->groupBy('c.idconsola')
                ->orderBy(' num ', 'DESC')
                ->setMaxResults($numpref);
            $consolas = $qc->getQuery()->getResult();
            //echo "editoriales-[".count($editoriales)."]  \n";
            
            $arrConsolas = array();
            foreach ($consolas as $con){
                if (!in_array($con, $arrConsolas)) {
                    $arrConsolas[] = array("idconsola" => $con['idconsola'],"txnombreconsola" => utf8_encode($con['txnombreconsola']));
                }
            }
            //echo "Cargó arreglo editoriales  \n";
            
            $arrPreferencias[] = array('consolas' => $arrConsolas, 'generos' => $arrGeneros);
            //echo "Fin preferencias  \n";

    
            return $arrPreferencias;
        } catch (Exception $ex) {
                //ECHO "ERROR PREFERENCIAS ".$ex;
                return array();
        } 
    }
 
    
   //Obtiene todos los Ejemplares, que coincidan con el texto OFRECIDOS, o SOLICITADOS
    public static function getBuscarEjemplares(Usuario $usuario, $texto, $em)
    {   
        //14 DICIEMBRE DE 2016: CAMBIADO METODO DE BUSCAR POR FULLTEXT
        //Recuperar ejemplares por búsqueda full text a las tablas Libro y Autores
        try{
            //Si la palabra de búsqueda viene en cero, el resultset es los 30 ejemplares más recientes
            if ($texto == "") {
                $resejemplares = ManejoDataRepository::getEjemplaresDisponibles(GamesController::inDatoCer, $em);
                return $resejemplares;
            } else {

                setlocale (LC_TIME, "es_CO");
                $fecha = new \DateTime;
                $arVideojuegos =[];

                $rsm  = new ResultSetMapping();
                //$rsm->addEntityResult('LibreameBackendBundle:Videojuego', 'vj');
                $rsm->addEntityResult('LibreameBackendBundle:Videojuego', 'vj');
                $rsm->addFieldResult('vj', 'idvideojuego', 'idvideojuego', Videojuego::class);
                $rsm->addFieldResult('vj', 'txnomvideojuego', 'txnomvideojuego', Videojuego::class);
                $rsm->addFieldResult('vj', 'felanzamiento', 'felanzamiento', Videojuego::class);
                $rsm->addFieldResult('vj', 'incategvideojuego', 'incategvideojuego', Videojuego::class);
                $rsm->addFieldResult('vj', 'videojuegoConsola', 'videojuego_consola', Videojuego::class);
                $rsm->addFieldResult('vj', 'txurlinformacion', 'txurlinformacion', Videojuego::class);
                $rsm->addFieldResult('vj', 'txobservaciones', 'txobservaciones', Videojuego::class);
                $rsm->addFieldResult('vj', 'txgenerovideojuego', 'txgenerovideojuego', Videojuego::class);
                $rsm->addFieldResult('vj', 'tximagen', 'tximagen', Videojuego::class);
                $rsm->addJoinedEntityResult('LibreameBackendBundle:Consola' , 'c', 'vj', 'videojuegoConsola');
                $rsm->addFieldResult('c','idconsola','idconsola');
                $rsm->addFieldResult('c','txnombreconsola','txnombreconsola');                
                
                $txsql = "SELECT v.idvideojuego, v.txnomvideojuego, v.felanzamiento, v.incategvideojuego, "
                        . "v.videojuego_consola, v.txurlinformacion, v.txobservaciones, v.txgenerovideojuego,"
                        . "v.tximagen,c.idconsola,c.txnombreconsola "
                        . "FROM videojuego v join ejemplar e ON (v.idvideojuego = e.ejemplar_videojuego) "
                        . "join consola c ON (v.videojuego_consola = c.idconsola) "
                        ." WHERE MATCH(v.txnomvideojuego,v.txurlinformacion, " 
                        ." v.txobservaciones,v.txgenerovideojuego,v.tximagen) AGAINST ('".$texto."*' IN BOOLEAN MODE)";
                $query = $em->createNativeQuery( $txsql, $rsm); 
                $videojuegos = $query->getArrayResult();
                foreach ($videojuegos as $vj) {
                    //echo "ENTRO:"."\n";
                    $arVideojuegos[] = $vj['idvideojuego'];
                    $consola = ManejoDataRepository::getConsola($vj['videojuegoConsola']['idconsola'], $em);
                    //var_dump($vj['videojuegoConsola']);
                }

                $q = $em->createQueryBuilder()
                    ->select('eu')
                    ->from('LibreameBackendBundle:Ejemplarusuario', 'eu')
                    ->leftJoin('LibreameBackendBundle:Ejemplar', 'e', \Doctrine\ORM\Query\Expr\Join::WITH, 'eu.ejemplarusuarioejemplar = e.idejemplar')
                    ->leftJoin('LibreameBackendBundle:Usuario', 'u', \Doctrine\ORM\Query\Expr\Join::WITH, 'u.idusuario = eu.ejemplarusuariousuario')
                    ->leftJoin('LibreameBackendBundle:Trato', 't', \Doctrine\ORM\Query\Expr\Join::WITH, 't.tratoejemplar = e.idejemplar and t.tratousrdueno = u.idusuario')
                    ->where(' e.ejemplarVideojuego in (:pvideojuegos)')  
                    ->setParameter('pvideojuegos', $arVideojuegos)
                    ->andWhere(' u.inusuestado = :estado')//Solo los usuarios con estado 1
                    ->setParameter('estado', 1)//Solo los usuarios con estado 1
                    ->andWhere(' e.inejemplarpublicado <= :ppublicado')//Debe cambiar a solo los ejemplares publicados = 1
                    ->setParameter('ppublicado', 1)//Debe cambiar a solo los ejemplares publicados = 1                    
                    //->andWhere(' h.inhisejemovimiento = :pmovimiento')
                    //->setParameter('pmovimiento', 1)//Todos los ejemplares con registro de movimiento en historia ejemplar: publicados 
                    //->andWhere(' m.inmemgrupo in (:grupos) ')//Para los grupos del usuario
                    //->setParameter('grupos', $grupos)
                    ->setMaxResults(30)
                    ->orderBy(' t.fefechatrato ', 'DESC');
                
                //echo $q->getQuery()->getSql();

                $resejemplares = $q->getQuery()->getResult();  
                //echo "ACABO: ".count($resejemplares)."\n";
                //$em->flush();

                return $resejemplares;
            }
            
        } catch (Exception $ex) {
                return new Ejemplarusuario();
        } 
    }
                
        //Obtiene todos los Ejemplares, con ID mayor al parámetro
    public static function getEjemplaresDisponibles($inultejemplar, $em)
    {   
        try{
            //Recupera cada uno de los ejemplares con ID > al del parametro
            //Los ejemplares cuya membresías coincidan con las del usuario que solicita
            //El usuario debe estar activo
            
            //Si el ultimo ejemplar es 0, la lista es de los 30 más recientes, 
            //si es positivo la lista los 30 superiores al numero y si es negativo
            //lista los 30 anteriores
            if ($inultejemplar == 0){ //Si es cero, trae los 30 más recientes
                $limiteSup = ManejoDataRepository::getMaxEjemplar($em);
                $limiteInf = $limiteSup - 30;
            } else if($inultejemplar > 0) { //Si es Positivo trae los 30 siguientes al numero
                $limiteInf = $inultejemplar + 1;
                $limiteSup = $limiteInf + 30;
            } else if ($inultejemplar < 0) { //Si es negativo trae los 30 anteriores 
                $limiteSup = ($inultejemplar*-1) - 1;
                $limiteInf =  $limiteSup -30;
            }
            $q = $em->createQueryBuilder()
                ->select('eu')
                ->from('LibreameBackendBundle:Ejemplarusuario', 'eu')
                ->leftJoin('LibreameBackendBundle:Ejemplar', 'e', \Doctrine\ORM\Query\Expr\Join::WITH, 'eu.ejemplarusuarioejemplar = e.idejemplar')
                ->leftJoin('LibreameBackendBundle:Usuario', 'u', \Doctrine\ORM\Query\Expr\Join::WITH, 'u.idusuario = eu.ejemplarusuariousuario')
                ->leftJoin('LibreameBackendBundle:Trato', 't', \Doctrine\ORM\Query\Expr\Join::WITH, 't.tratoejemplar = e.idejemplar and t.tratousrdueno = u.idusuario')
                /*->where(' e.ejemplarVideojuego in (:pvideojuegos)')  
                ->setParameter('pvideojuegos', $arVideojuegos)*/
                ->andWhere(' e.idejemplar BETWEEN :pejemplar AND :pFejemplar')
                ->setParameter('pejemplar', $limiteInf)
                ->setParameter('pFejemplar', $limiteSup)
                ->andWhere(' u.inusuestado = :estado')//Solo los usuarios con estado 1
                ->setParameter('estado', 1)//Solo los usuarios con estado 1
                ->andWhere(' e.inejemplarpublicado <= :ppublicado')//Debe cambiar a solo los ejemplares publicados = 1
                ->setParameter('ppublicado', 1)//Debe cambiar a solo los ejemplares publicados = 1                    
                //->andWhere(' h.inhisejemovimiento = :pmovimiento')
                //->setParameter('pmovimiento', 1)//Todos los ejemplares con registro de movimiento en historia ejemplar: publicados 
                //->andWhere(' m.inmemgrupo in (:grupos) ')//Para los grupos del usuario
                //->setParameter('grupos', $grupos)
                ->setMaxResults(30)
                ->orderBy(' e.idejemplar ', 'ASC');
                
            $resejemplares = $q->getQuery()->getResult();
                

            return $resejemplares;
        } catch (Exception $ex) {
                //echo "retorna error";
                return new Ejemplar();
        } 
    }
                
    //Obtiene el máximo ID en ejemplares 
    public static function getMaxEjemplar($em)
    {  
        try{
            $qmx = $em->createQueryBuilder()
                ->select('MAX(e.idejemplar)')
                ->from('LibreameBackendBundle:Ejemplar', 'e');
            
            $max = (int)$qmx->getQuery()->getSingleScalarResult();//Si hay registro devuelve lo que hay
            
            return $max;
        } catch (Exception $ex) {
                return GamesController::inDatoCer;
        } 
    }

   //Obtiene el objeto Ejemplarusuario según su ID 
    public static function getEjemplarusuario($idejemplarusuario, $em)
    {   
        try{
            $ejemplarusuario = $em->getRepository('LibreameBackendBundle:Ejemplarusuario')->
                findOneBy(array("idejemplarusuario"=>$idejemplarusuario));
            
            //echo "\n ManejoDataRepository::getEjemplarusuario: idejemplarusuario: ".$ejemplarusuario->getidejemplarusuario();
            //echo "\n ManejoDataRepository::getEjemplarusuario: cuenta: ".count($ejemplarusuario);
            return $ejemplarusuario;
        } catch (Exception $ex) {
                return new Ejemplarusuario();
        } 
    }
    
   //Obtiene el objeto Videojuego según su ID 
    public static function getVideojuego($idvideojuego, $em)
    {   
        try{
            $videojuego = $em->getRepository('LibreameBackendBundle:Videojuego')->
                findOneBy(array("idvideojuego"=>$idvideojuego));
                //findOneByIdvideojuego($idvideojuego);
            
            //echo "\n ManejoDataRepository::getVideojuego: idVideojuego: ".$idvideojuego->getidvideojuego();
            //echo "\n ManejoDataRepository::getVideojuego: cuenta: ".count($videojuego);
            return $videojuego;
        } catch (Exception $ex) {
                return new Videojuego();
        } 
    }
    
   //Obtiene el objeto Videojuego según su ID 
    public static function getVideojuegoByNombre($nomvideojuego, $em)
    {   
        //echo "ManejoDataRepository::getVideojuegoByNombre: ENTRA A BUSCAR [".$nomvideojuego."] \n";
        try{
            $videojuego = $em->getRepository('LibreameBackendBundle:Videojuego')->
                findOneBy(array("txnomvideojuego"=>$nomvideojuego));
                //findOneByIdvideojuego($idvideojuego);
            
            //echo "ManejoDataRepository::getVideojuegoByNombre: Videojuego: ".$videojuego->gettxnomvideojuego()." \n ";
            return $videojuego;
        } catch (Exception $ex) {
                return new Videojuego();
        } 
    }
    
   //Obtiene la consola por su Id
    public static function getConsola($idconsola, $em)
    {   
        try{
            
            $consola = $em->getRepository('LibreameBackendBundle:Consola')->
                findOneBy(array("idconsola"=>$idconsola));
                //findOneByidconsola($videojuego->getvideojuegoConsola());
            
            return $consola;
        } catch (Exception $ex) {
                return new Consola();
        } 
    }
    
   //Obtiene el objeto Fabricante según su ID 
    public static function getFabricante($idfabricante, $em)
    {   
        try{
            $fabricante = $em->getRepository('LibreameBackendBundle:Fabricante')->
                findOneBy(array("idfabricante"=>$idfabricante));
            return $fabricante;
        } catch (Exception $ex) {
                return new Fabricante();
        } 
    }

   //Obtiene la consola por su nombre
    public static function getConsolaByNombre($consola, $em)
    {   
        try{
            
            $consola = $em->getRepository('LibreameBackendBundle:Consola')->
                findOneBy(array("txnombreconsola"=>$consola));
                //findOneByidconsola($videojuego->getvideojuegoConsola());
            
            return $consola;
        } catch (Exception $ex) {
                return new Consola();
        } 
    }
    
   //Obtiene la consola generica
    public static function getConsolaGenerica($em)
    {   
        try{
            
            $consola = $em->getRepository('LibreameBackendBundle:Consola')->
                findOneBy(array("idconsola"=> GamesController::inIdGeneral));
                //findOneByidconsola($videojuego->getvideojuegoConsola());
            
            return $consola;
        } catch (Exception $ex) {
                return new Consola();
        } 
    }
    
   //Obtiene el objeto Fabricante generico
    public static function getFabricanteGenerico($em)
    {   
        try{
            $fabricante = $em->getRepository('LibreameBackendBundle:Fabricante')->
                findOneBy(array("idfabricante"=>GamesController::inIdGeneral));
            return $fabricante;
        } catch (Exception $ex) {
                return new Fabricante();
        } 
    }

   //Obtiene el objeto Fabricante según su nombre 
    public static function getFabricanteByNombre($fabricante, $em)
    {   
        try{
            $fabricante = $em->getRepository('LibreameBackendBundle:Fabricante')->
                findOneBy(array("txnomfabricante"=>$fabricante));
            return $fabricante;
        } catch (Exception $ex) {
                return new Fabricante();
        } 
    }

    //Obtiene la cantidad de reseñas del videojuego
    public static function getCantResenas($idvideojuego, $em)
    {   
        try{
            $q = $em->createQueryBuilder()
                ->select('count(r)')
                ->from('LibreameBackendBundle:Resenavideojuego', 'r')
                ->Where('r.resenaVideojuego = :pvideojuego')
                ->setParameter('pvideojuego', $idvideojuego);
            $resenas = $q->getQuery()->getSingleScalarResult() * 1;
            return $resenas;
        } catch (Exception $ex) {
                return GamesController::inDatoCer;
        } 
    }
    
    //Obtiene la cantidad de puntos por categoria: TODO: hacer una tabla
    public static function getPuntosCategoria($categoria)
    {   
        try{
            return $categoria * 30;
        } catch (Exception $ex) {
                return GamesController::inDatoCer;
        } 
    }
    
    

    //Obtiene el objeto Usuario según su ID 
    public static function getUsuarioById($inusuario, $em)
    {   
        try{
            return $em->getRepository('LibreameBackendBundle:Usuario')->
                findOneBy(array('idusuario' => $inusuario, 'inusuestado' => GamesController::inExitoso));
        } catch (Exception $ex) {
                return new Usuario();
        } 
    }
    
    //Obtiene todos los Ids de las membresias del usuario
    public static function getMembresiasUsuario(LbUsuarios $usuario)
    {   
        try{
            $em = $this->getDoctrine()->getManager();
            return $em->getRepository('LibreameBackendBundle:LbMembresias')->
                    findBy(array('inmemusuario' => $usuario));
        } catch (Exception $ex) {
                return new LbMembresias();
        } 
    }
        
    public static function getEjemplarById($ejemplar, $em)
    {   
        try{
            return $em->getRepository('LibreameBackendBundle:Ejemplar')->
                    findOneBy(array('idejemplar' => $ejemplar));
        } catch (Exception $ex) {
                return new Ejemplar();
        } 
    }

    //Obtiene la fecha en que el usuario publicó el ejemplar
    public static function getFechaPublicacion($pejemplarusuario, $pusuario, $em)
    {   
        try{
            $sql = "SELECT max(eu.fepublicacion) AS fecha FROM LibreameBackendBundle:ejemplarusuario eu"
                    ." WHERE eu.idejemplarusuario = :ejemplar AND eu.ejemplarusuariousuario = :usuario";
            $query = $em->createQuery($sql)->setParameters(array('ejemplar'=>$pejemplarusuario, 'usuario'=> $pusuario));
            
            $fecha = $query->getOneOrNullResult();
            //echo "\n fecha : ".$fecha['fecha'];
            return $fecha['fecha'];
        } catch (Exception $ex) {
                return $fecha;
        } 
    }
                
 
    //Actualiza clave de usuario
    public static function setCambiarClave(Solicitud $psolicitud, $em)
    {   
        try{
            $resp = GamesController::inFallido;
            //echo "setCambiarClave : Inicia : usuario FALLIDO ".$resp." \n";
            
            $usuario = ManejoDataRepository::getUsuarioByEmail($psolicitud->getEmail(), $em);
            //echo "setCambiarClave : Recuperó el usuario ".$usuario->getTxnickname()." \n";
            //echo 'usuario FALLIDO '.$resp;
            //if ($psolicitud->getUsuFecNac() != ""){
            //    $d = new DateTime($psolicitud->getUsuFecNac());
            //}
            
            $usuario->setTxclaveusuario($psolicitud->getClaveNueva());
            //echo "setCambiarClave : cambia clave \n";
            //echo "setCambiarClave : clave : [".$psolicitud->getClaveNueva()."] \n";
            //echo "setCambiarClave : Validacion : [".$usuario->getTxusuvalidacion()."] \n";
            //$usuario->setTxclave(ManejoDataRepository::fnEncrypt($psolicitud->getClaveNueva(), $usuario->getTxusuvalidacion()));  
            $usuario->setTxclave(ManejoDataRepository::fnEncrypt($psolicitud->getClaveNueva(), GamesController::txSecret));  
            //echo "setCambiarClave : encripta clave \n";
            $em->persist($usuario);
            $em->flush();
            $resp = GamesController::inExitoso;
            //echo "setCambiarClave : Respuesta = ".$resp." \n";
            
            return $resp;
        } catch (Exception $ex) {
                return  GamesController::inFallido;
        } 
    }
                

    //Actualiza datos de usuario
    public function setActualizaUsuario(Solicitud $psolicitud, $em)
    {   
        try{
            $usuario = ManejoDataRepository::getUsuarioByEmail($psolicitud->getEmail(), $em);
            $lugar = ManejoDataRepository::getLugar($psolicitud->getUsuLugar(), $em);
            
            if ($psolicitud->getUsuFecNac() != ""){
                $d = new DateTime($psolicitud->getUsuFecNac());
            }
            
            if ($psolicitud->getTelefono() == "")
                $usuario->setTxtelefono($psolicitud->getEmail());
            else
                $usuario->setTxtelefono($psolicitud->getTelefono());
            
            $usuario->setUsuarioInlugar($lugar);
            $usuario->setinusugenero($psolicitud->getUsuGenero());
            //Cargar imágen usuario
            $usuario->setTxusuimagen(GamesController::txMeNoIdS);
            //$usuario->setTxusuimagen($psolicitud->getUsuImagen());
            $usuario->setTxnomusuario($psolicitud->getNomUsuario());
            $usuario->setTxnickname($psolicitud->getNomMostUsuario());
            if ($psolicitud->getUsuFecNac() != ""){
                $usuario->setfeusunacimiento($d);
            }
           
            $em->persist($usuario);
            $em->flush();
            //Cargar imágen usuario
            if ($psolicitud->getUsuImagen() != "") {
                //echo "setActualizaUsuario: Calcula imágen  \n" ;
                $usuario->setTxusuimagen(ManejoDataRepository::getImportarImagenB64($psolicitud->getUsuImagen(), $usuario->getIdusuario(), GamesController::txIndCarpImgUsua));
                $this->inImagenValida = GamesController::inDatoUno;
            } else {
                //echo "setActualizaUsuario: La imágen viene vacia \n" ;
                $this->inImagenValida = GamesController::inDatoUno;
            }
            
            if ($this->inImagenValida == GamesController::inDatoUno){
                $em->persist($usuario);
                $em->flush();
                $resp = GamesController::inExitoso;
                //echo "setActualizaUsuario: La imágen es válida [".$resp."]\n" ;
            } else {
                $resp = GamesController::inErrImag;
                //echo "setActualizaUsuario: La imágen NO es válida [".$resp."] \n" ;
            }
            
            return $resp;
        } catch (Exception $ex) {
                return new LbUsuarios();
        } 
    }
    
    
/*I found an example for en/decoding strings in PHP. At first it looks very good but it wont work :-(

Does anyone know what the problem is?

$Pass = "Passwort";
$Clear = "Klartext";

$crypted = fnEncrypt($Clear, $Pass);
echo "Encrypted: ".$crypted."</br>";

$newClear = fnDecrypt($crypted, $Pass);
echo "Decrypted: ".$newClear."</br>";
*/
    public static function fnEncrypt($sValue, $sSecretKey) {
        //echo "Valor [".$sValue."] - Secret [".$sSecretKey."]";
        //return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $sSecretKey, $sDecrypted, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $sSecretKey, $sValue, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }

    public static function fnDecrypt($sValue, $sSecretKey) {
        //echo "Valor [".$sValue."* - Secret [".$sSecretKey."]";
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $sSecretKey, base64_decode($sValue), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }   
///********************* LO QUE NO SE USA ********************************///
    
    
    //Obtiene varios objetos Editorial según el ID del libro 
    public function getEditorialesLibro($inlibro)
    {   
        try{
            $em = $this->getDoctrine()->getManager();
            $q = $em->createQueryBuilder()
                ->select('e')
                ->from('LibreameBackendBundle:LbEditoriales', 'e')
                ->leftJoin('LibreameBackendBundle:LbEditorialeslibros', 'el', \Doctrine\ORM\Query\Expr\Join::WITH, 'e.inideditorial = el.inedilibroeditorial ')
                ->Where(' el.inediliblibro = :plibro ')
                ->setParameter('plibro', $inlibro);
            return $q->getQuery()->getResult();
        } catch (Exception $ex) {
                return new LbEditoriales();
        } 
    }
    
    //Obtiene el registro de Megusta del ejemplar
    public function getRegMegustaEjemplar(LbEjemplares $pEjemplar, LbUsuarios $pUsuario)
    {   
        try{
            $em = $this->getDoctrine()->getManager();
            return $em->getRepository('LibreameBackendBundle:LbMegusta')->
                findOneBy(array('inmegejemplar' => $pEjemplar, 'inmegusuario' => $pUsuario));
        } catch (Exception $ex) { 
                return new LbMegusta();
        } 
    }

    
    //Obtiene la cantidad de Megusta del ejemplar : Condicion megusta - nomegusta 
    public function getMegustaEjemplar(LbEjemplares $pEjemplar, LbUsuarios $pUsuario)
    {   
        try{
            $em = $this->getDoctrine()->getManager();
            $qmg = $em->createQueryBuilder()
                ->select('COALESCE(a.inmegmegusta, 0)')
                ->from('LibreameBackendBundle:LbMegusta', 'a')
                ->Where('a.inmegejemplar = :pejemplar')
                ->setParameter('pejemplar', $pEjemplar)
                ->andWhere('a.inmegusuario = :pusuario')
                ->setParameter('pusuario', $pUsuario)
                ->setMaxResults(1)
                ->orderBy('a.femegmegusta', 'DESC');
            
            $meg = GamesController::inDatoCer;
            if($qmg->getQuery()->getOneOrNullResult() == NULL){
                $meg = GamesController::inDatoCer; //Si ho hay registro devuelve no me gusta (0)
            } else {
                $meg = (int)$qmg->getQuery()->getSingleScalarResult();//Si hay registro devuelve lo que hay
            }    
            
            //echo "megusta ".$meg;
            return $meg;
        } catch (Exception $ex) {
                return GamesController::inDatoCer;
        } 
    }

    //Obtiene la descripcion de la condicion actual del ejemplar
    // 0 - No en negociacion,1 - Solicitado por usuario, 2 - En proceso de aprobación del negocio, 
    // 3 - Aprobado negocio por Ambos actores, 4 - En proceso de entrega
    // 5 - Entregado, 6 - Recibido
    public function getDescCondicionActualEjemplar($condactual)
    {   
        try{
            $desCondactual = "Ejemplar no está en negociación";
            switch($condactual){
                case (GamesController::inConEjeNoNe): $desCondactual = GamesController::txConEjeNoNe; break;
                case (GamesController::inConEjeSoli): $desCondactual = GamesController::txConEjeSoli; break;
                case (GamesController::inConEjePrAp): $desCondactual = GamesController::txConEjePrAp; break;
                case (GamesController::inConEjeApNe): $desCondactual = GamesController::txConEjeApNe; break;
                case (GamesController::inConEjePrEn): $desCondactual = GamesController::txConEjePrEn; break;
                case (GamesController::inConEjeEntr): $desCondactual = GamesController::txConEjeEntr; break;
                case (GamesController::inConEjeReci): $desCondactual = GamesController::txConEjeReci; break;
            }

            return utf8_encode($desCondactual);
        } catch (Exception $ex) {
            return utf8_encode($desCondactual);
        } 
    }
    
    public function getUsrMegustaEjemplar(Solicitud $psolicitud)
    {   
        try{
            $em = $this->getDoctrine()->getManager();
            $Megusta = $em->getRepository('LibreameBackendBundle:LbMegusta')->findBy(array('inmegejemplar' => $psolicitud->getIdEjemplar())/*, 
                    array('femegmegusta', 'ASC')*/);
            $arUsuar = [];
            $usr = new LbUsuarios();
            foreach($Megusta as $mg){
               $usr = ManejoDataRepository::getUsuarioById($mg->getInMegUsuario()->getInUsuario());
               if  ($mg->getInmegmegusta() == GamesController::inDatoUno){
                 $arUsuar[] = array("inusuario" => $usr->getInUsuario(), "txusunommostrar" => utf8_encode($usr->getTxusunommostrar()), 
                     "txusuimagen" => utf8_encode($usr->getTxusuimagen()), );
               } else {   
                    if (in_array(strtolower($mg->getInMegUsuario()->getInUsuario()), $arUsuar)){
                        unset($arUsuar[$mg->getInMegUsuario()->getInUsuario()]);
                    }
               }
            }
            
            return $arUsuar;
        } catch (Exception $ex) {
                return $arUsuar;
        } 
    }
    
    
    public function getNegociacionEjemplarBiblioteca(LbEjemplares $pejemplar, LbUsuarios $pusuario)
    {   
        try{
            $em = $this->getDoctrine()->getManager();
            $qneg = $em->createQueryBuilder()
                ->select('DISTINCT a.txnegidconversacion')
                ->from('LibreameBackendBundle:LbNegociacion', 'a')
                ->Where('a.innegejemplar = :pejemplar')
                ->setParameter('pejemplar', $pejemplar)
                ->andWhere('a.innegusuduenho = :pusuario')
                ->setParameter('pusuario', $pusuario);
            
            $arrNegociacion = array();
            $idneg = $qneg->getQuery()->getResult();
            foreach($idneg as $idconversacion){
                //echo "conversa ".$idconversacion;
                
                $negociacion = $em->getRepository('LibreameBackendBundle:LbNegociacion')->
                        findBy(array('txnegidconversacion' => $idconversacion,
                                    'innegmenseliminado' => GamesController::inDatoCer), 
                                    array('fenegfechamens' => 'asc'));
                $negoc = new LbNegociacion();
                $arrConversacion = array();
                foreach ($negociacion as $negoc) {
                    $usuaNeg = ManejoDataRepository::getUsuarioById($negoc->getInnegusuduenho()->getInusuario());
                    $usuaEsc = ManejoDataRepository::getUsuarioById($negoc->getInnegusuescribe()->getInusuario());
                    $usuaSol = ManejoDataRepository::getUsuarioById($negoc->getInnegususolicita()->getInusuario());
                    $promcalUsNeg = ManejoDataRepository::getPromedioCalifica($usuaNeg->getInusuario());
                    $promcalUsEsc = ManejoDataRepository::getPromedioCalifica($usuaEsc->getInusuario());
                    $promcalUsSol = ManejoDataRepository::getPromedioCalifica($usuaSol->getInusuario());
                    if($pusuario == $negoc->getInnegusuduenho()){
                        $leido = $negoc->getInnegmensleidodue();
                    } else {
                        $leido = $negoc->getInnegmensleidosol();
                    }
                    
                    $arrConversacion[] = array('inidnegociacion' => $negoc->getInidnegociacion(),
                        'innegmensleido' => $leido,
                        'fenegfechamens' => $negoc->getFenegfechamens()->format("Y-m-d H:i:s"),
                        'txnegmensaje' => utf8_encode($negoc->getTxnegmensaje()),
                        'usrescribe' =>  $usuaEsc->getInusuario(),
                        'tratoacep' => $negoc->getInnegtratoacep(),
                    );
                }
                $arrNegociacion[] = array('txnegidconversacion' => $idconversacion, 'usrsolicita' =>  array('inusuario' => $usuaSol->getInusuario(),
                                'txusunommostrar' => utf8_encode($usuaSol->getTxusunommostrar()),
                                'txusuimagen' => utf8_encode($usuaSol->getTxusuimagen()),'calificacion' => $promcalUsSol),
                                'usrdueno' => array('inusuario' => $usuaNeg->getInusuario(),
                                'txusunommostrar' => utf8_encode($usuaNeg->getTxusunommostrar()),
                                'txusuimagen' => utf8_encode($usuaNeg->getTxusuimagen()),'calificacion' => $promcalUsNeg), 
                                "conversacion" => array($arrConversacion));
                unset($arrConversacion);
                
            }
            
            return $arrNegociacion;
        } catch (Exception $ex) {
                return new LbNegociacion();
        } 
    }

    public function getHistoriaEjemplarBiblioteca(LbEjemplares $pejemplar){   
        try{
            $em = $this->getDoctrine()->getManager();
            //Primero busca todos los que tengan hijos
            $histEjemplar = $em->createQueryBuilder()
                ->select('a')
                ->from('LibreameBackendBundle:LbHistejemplar', 'a')
                ->Where('a.inhisejeejemplar = :pejemplar')
                ->setParameter('pejemplar', $pejemplar)
                //->andWhere('a.inhisejeusuario = :pusuario')
                //->setParameter('pusuario', $pusuario)
                //->andWhere('a.inhisejepadre  IS NOT NULL')
                ->orderBy('a.fehisejeregistro', 'ASC')->getQuery()->getResult();
            $arrHistEjemplar = array();

            $hisEje = new LbHistejemplar();
            foreach ($histEjemplar as $hisEje) {
                //Para cada uno busca sus hijos
                $hijo = $hisEje->getInhistejemplar();
                $padre = null;
                if($hisEje->getInhisejepadre() != NULL)
                    $padre = $hisEje->getInhisejepadre()->getInhistejemplar();
                //$punteros[]['padre'] = $padre;
                //$punteros[]['hijo'] = $hijo;
                //echo "P:".$padre." -> H:".$hijo." \n";
                
                $histHijo = new LbHistejemplar();
                //$rpadre = $registro['padre'];
                $histHijo = $em->createQueryBuilder()
                    ->select('a')
                    ->from('LibreameBackendBundle:LbHistejemplar', 'a')
                    ->Where('a.inhisejeejemplar = :pejemplar')
                    ->setParameter('pejemplar', $pejemplar)
                    //->andWhere('a.inhisejeusuario = :pusuario')
                    //->setParameter('pusuario', $pusuario)
                    ->andWhere('a.inhistejemplar = :idhijo')
                    ->setParameter('idhijo', $hijo)
                    ->orWhere('a.inhistejemplar = :idpadre')
                    ->setParameter('idpadre', $padre)
                    ->orderBy('a.fehisejeregistro', 'ASC')->getQuery()->getResult();
                
                foreach ($histHijo as $hisEjePH) {
                    $idusuario = $hisEjePH->getInhisejeusuario()->getInusuario();
                    $usuaHist = ManejoDataRepository::getUsuarioById($idusuario);
                    $promcalUsHist = ManejoDataRepository::getPromedioCalifica($idusuario);
                    $descMovimiento = "";
                    switch ($hisEjePH->getInhisejemovimiento()){
                        case GamesController::inMovPubEjem: $descMovimiento = GamesController::txMovPubEjem; break;
                        case GamesController::inMovBlqEjSi: $descMovimiento = GamesController::txMovBlqEjSi; break;
                        case GamesController::inMovSoliEje: $descMovimiento = GamesController::txMovSoliEje; break;
                        case GamesController::inMovEntrEje: $descMovimiento = GamesController::txMovEntrEje; break;
                        case GamesController::inMovReciEje: $descMovimiento = GamesController::txMovReciEje; break;
                        case GamesController::inMovActiEje: $descMovimiento = GamesController::txMovActiEje; break;
                        case GamesController::inMovInacEje: $descMovimiento = GamesController::txMovInacEje; break;
                        case GamesController::inMovComeEje: $descMovimiento = GamesController::txMovComeEje; break;
                        case GamesController::inMovMeguEje: $descMovimiento = GamesController::txMovMeguEje; break;
                        case GamesController::inMovNMegEje: $descMovimiento = GamesController::txMovNMegEje; break;
                        case GamesController::inMovCamEEje: $descMovimiento = GamesController::txMovCamEEje; break;
                        case GamesController::inMovContEje: $descMovimiento = GamesController::txMovContEje; break;
                        case GamesController::inMovBajaEje: $descMovimiento = GamesController::txMovBajaEje; break;
                        case GamesController::inMovConsEje: $descMovimiento = GamesController::txMovConsEje; break;
                        case GamesController::inMovVendEje: $descMovimiento = GamesController::txMovVendEje; break;
                        case GamesController::inMovCompEje: $descMovimiento = GamesController::txMovCompEje; break;
                        case GamesController::inMovAcepEje: $descMovimiento = GamesController::txMovAcepEje; break;
                        case GamesController::inMovRechEje: $descMovimiento = GamesController::txMovRechEje; break;
                        case GamesController::inMovEjeDevu: $descMovimiento = GamesController::txMovEjeDevu; break;
                        case GamesController::inMovUsPCali: $descMovimiento = GamesController::txMovUsPCali; break;
                        case GamesController::inMovUsSCali: $descMovimiento = GamesController::txMovUsSCali; break;
                    }
                    $arrHistEjemplar[] = array('fehisejeregistro' => $hisEjePH->getFehisejeregistro()->format("Y-m-d H:i:s"),
                        'inhisejemodoentrega' => $hisEjePH->getInhisejemodoentrega(), /*0: En el domicilio, 1: Encontrandose, 3. Courrier local, 4: Courrier Nacional, 5: Courrier internacional*/
                        'inhisejemovimiento' => $hisEjePH->getInhisejemovimiento(),
                        'txhisejedescmovimiento' => utf8_encode($descMovimiento),
                        'inhisejeejemplar' => $hisEjePH->getInhisejeejemplar(),
                        'inhisejepadre' => $hisEjePH->getInhisejepadre(),
                        'usrtrx' => array('inusuario' => $usuaHist->getInusuario(),
                                'txusunommostrar' => utf8_encode($usuaHist->getTxusunommostrar()),
                                'txusuimagen' => utf8_encode($usuaHist->getTxusuimagen()),
                                'calificacion' => $promcalUsHist)
                    );
                }    
            }    
            return $arrHistEjemplar;
        
        } catch (Exception $ex) {
                return LbHistejemplar();
        } 
    }

    public function getComentariosEjemplar(Solicitud $psolicitud)
    {   
        try{
            $em = $this->getDoctrine()->getManager();
            $comentarios = $em->getRepository('LibreameBackendBundle:LbComentarios')->
                    findBy(array('incomejemplar' => $psolicitud->getIdEjemplar(), 'incomactivo' => '1'), 
                           array('fecomfeccomentario' => 'desc'));
            $com = new LbComentarios();
            $arComme = [];
            $usr = new LbUsuarios();
            foreach($comentarios as $com){
               $usr = ManejoDataRepository::getUsuarioById($com->getIncomusuario()->getInusuario());
                $arUsuar = [];
               $arUsuar[] = array("inusuario" => $usr->getInUsuario(), "txusunommostrar" => utf8_encode($usr->getTxusunommostrar()), 
                   "txusuimagen" => utf8_encode($usr->getTxusuimagen()), );
               if($com->getIncomcompadre()!=NULL){ //Si el cometario PADRE está inactivo, el hijo tambien
                    if($com->getIncomcompadre()->getIncomactivo()==GamesController::inDatoUno){ //Si el cometario PADRE está inactivo, el hijo tambien
                         $arComme[] = array("inidcomentario" => $com->getInidcomentario(), "fecomfeccomentario" => $com->getFecomfeccomentario()->format("Y-m-d H:i:s"),
                             "incompadre" => $com->getIncomcompadre()->getInidcomentario(), "txcomentario" => utf8_encode($com->getTxcomcomentario()),
                             "usuario" => $arUsuar);
                    }
               } else {
                         $arComme[] = array("inidcomentario" => $com->getInidcomentario(), "fecomfeccomentario" => $com->getFecomfeccomentario()->format("Y-m-d H:i:s"),
                             "incompadre" => "", "txcomentario" => utf8_encode($com->getTxcomcomentario()),"usuario" => $arUsuar);
               }
            }
            
            return $arComme;
        } catch (Exception $ex) {
                return $arComme;
        } 
    }
    
    
    //Obtiene la cantidad de Megusta del ejemplar : Condicion megusta - nomegusta 
    public function getCantMegusta($inejemplar)
    {   
        try{
            $em = $this->getDoctrine()->getManager();
            $qmg = $em->createQueryBuilder()
                ->select('count(a)')
                ->from('LibreameBackendBundle:LbMegusta', 'a')
                ->Where('a.inmegejemplar = :pejemplar')
                ->setParameter('pejemplar', $inejemplar)
                ->andWhere('a.inmegmegusta = :pmeg')
                ->setParameter('pmeg', 1);
                
            /*$qnmg = $em->createQueryBuilder()
                ->select('count(a)')
                ->from('LibreameBackendBundle:LbMegusta', 'a')
                ->Where('a.inmegejemplar = :pejemplar')
                ->setParameter('pejemplar', $inejemplar)
                ->andWhere('a.inmegmegusta = :pnomeg')
                ->setParameter('pnomeg', 0);
            */
            $meg = $qmg->getQuery()->getSingleScalarResult();
            //$nomeg = $qnmg->getQuery()->getSingleScalarResult();
            
            //echo "megusta ".$meg." - nomegusta ".$nomeg;
            //return $meg - $nomeg;
            return $meg;
        } catch (Exception $ex) {
                return GamesController::inDatoCer;
        } 
    }
    
    //Obtiene el indicador de si el usuario ha aceptado 1, rechazado 0, o no ha indicado el trato -1
    public function getUsAceptTrato($usrescribe, $idconversa)
    {
        try{
            $indicador = -1;
            $em = $this->getDoctrine()->getManager();
            $qs = $em->createQueryBuilder()
                ->select('count(n)')
                ->from('LibreameBackendBundle:LbNegociacion', 'n')
                ->Where('n.txnegidconversacion = :idconv')
                ->andWhere('n.innegusuescribe = :idusr')
                ->andWhere('n.innegtratoacep = :acep')
                ->setParameter('idconv', $idconversa)
                ->setParameter('idusr', $usrescribe)
                ->setParameter('acep', 0); //Busca rechazo
            $indrechazo = $qs->getQuery()->getSingleScalarResult();
            if ($indrechazo > 0) {
                $indicador = $indrechazo;
            } else {
                $qt = $em->createQueryBuilder()
                    ->select('count(n)')
                    ->from('LibreameBackendBundle:LbNegociacion', 'n')
                    ->Where('n.txnegidconversacion = :idconv')
                    ->andWhere('n.innegusuescribe = :idusr')
                    ->andWhere('n.innegtratoacep = :acep')
                    ->setParameter('idconv', $idconversa)
                    ->setParameter('idusr', $usrescribe)
                    ->setParameter('acep', 1); //Busca aceptacion
                $indacepta = $qt->getQuery()->getSingleScalarResult();
                if ($indacepta > 0) {
                    $indicador = $indacepta;
                }
            }
            
            return $indicador;
        } catch (Exception $ex) {
                return GamesController::inDatoCer;
        } 
    }
    
    
    //Obtiene todo el chat por su id 
    public function getChatNegociacionById($idconversa)
    {   
        try{
            //echo "busca conversación ".$idconversa;
            $em = $this->getDoctrine()->getManager();
            $qs = $em->createQueryBuilder()
                ->select('n')
                ->from('LibreameBackendBundle:LbNegociacion', 'n')
                ->Where('n.txnegidconversacion = :idconv')
                ->setParameter('idconv', $idconversa)
                ->orderBy('n.fenegfechamens', 'ASC');
            $conversacion = $qs->getQuery()->getResult();
            
            return $conversacion;
        } catch (Exception $ex) {
                return GamesController::inDatoCer;
        } 
    }
    
    //Obtiene un Registro histórico por su ID
    public function getRegHisById($idregistro)
    {   
        try{
            $em = $this->getDoctrine()->getManager();
            $registro = $em->getRepository('LibreameBackendBundle:LbHistejemplar')->
                findOneBy(array('inhistejemplar' => $idregistro));

            return $registro;
        } catch (Exception $ex) {
                return new LbHistejemplar();
        } 
    }
    
    //Obtiene el dato del genero según el Objeto
    public function getGenero($genero)
    {   
        try{
            $em = $this->getDoctrine()->getManager();
            return $em->getRepository('LibreameBackendBundle:LbGeneros')->
                findOneBy(array('ingenero' => $genero));
        } catch (Exception $ex) {
                return new LbGeneros();
        }   
    }
    
    //Obtiene el objeto Libro según su nombre 
    public function getLibroByTitulo($titulo)
    {   
        try{
            //echo "El libro solicitado: -[".$inlibro."]- \n";
            $em = $this->getDoctrine()->getManager();
            //$libro = new LbLibros();
            $libro = $em->getRepository('LibreameBackendBundle:LbLibros')->
                //findOneBy(array("inlibro"=>$inlibro));
                findOneBy(array("txlibtitulo" => $titulo));
  
            //echo "Recuperó el libro ".$libro->getInlibro()."-".$libro->getTxlibtitulo()."\n";
            return $libro;
        } catch (Exception $ex) {
                return new LbLibros();
        } 
    }
    
    //Obtiene los datos de un autor por su ID
    public function getAutorById($idautor)
    {   
        try{
            $em = $this->getDoctrine()->getManager();
            return $em->getRepository('LibreameBackendBundle:LbAutores')->
                    findOneBy(array('inidautor' => $idautor));
        } catch (Exception $ex) {
                return new LbAutores();
        } 
    }
                
    //Obtiene una editorial por su ID
    public function getEditorialById($ideditorial)
    {   
        try{
            $em = $this->getDoctrine()->getManager();
            return $em->getRepository('LibreameBackendBundle:LbEditoriales')->
                    findOneBy(array('inideditorial' => $ideditorial));
        } catch (Exception $ex) {
                return new LbEditoriales();
        } 
    }
                
    //Obtiene todos los libros de un autor
    public function getLibrosByAutor($autor)
    {   
        try{
            $em = $this->getDoctrine()->getManager();
            return $em->getRepository('LibreameBackendBundle:LbAutoreslibros')->
                    findBy(array('inautlidautor' => $autor));
        } catch (Exception $ex) {
                return new LbAutoreslibros();
        } 
    }
                
    //Obtiene todos los libros de una editorial
    public function getLibrosByEditorial($editorial)
    {   
        try{
            $em = $this->getDoctrine()->getManager();
            return $em->getRepository('LibreameBackendBundle:LbEditorialeslibros')->
                    findBy(array('inedilibroeditorial' => $editorial));
        } catch (Exception $ex) {
                return new LbEditorialeslibros();
        } 
    }
                
    //Obtiene todos los Ejemplares, de un usuario
    //1: Todos, 2: En negociación, 3: Publicados, 4: No publicados, 5: Bloqueados
    public static function getVisualizarBiblioteca(Usuario $usuario, $filtro, $em)
    {   
        try{
            //Recupera cada uno de los ejemplares con ID > al del parametro
            //Los ejemplares cuya membresías coincidan con las del usuario que solicita
            //El usuario debe estar activo
            //Estado de la negocuación actual : 0 - No en negociacion,1 - Solicitado por usuario, 2 - En proceso de aprobación del negocio, 
            //3 - Aprobado negocio por Ambos actores, 4 - En proceso de entrega 5 - Entregado, 6 - Recibido
            switch($filtro){
                case GamesController::inDatoUno : //Todos
                    $q = $em->createQueryBuilder()
                        ->select('eu')
                        ->from('LibreameBackendBundle:Ejemplarusuario', 'eu')
                        ->leftJoin('LibreameBackendBundle:Ejemplar', 'e', \Doctrine\ORM\Query\Expr\Join::WITH, 'eu.ejemplarusuarioejemplar = e.idejemplar')
                        ->leftJoin('LibreameBackendBundle:Usuario', 'u', \Doctrine\ORM\Query\Expr\Join::WITH, 'u.idusuario = eu.ejemplarusuariousuario')
                        ->leftJoin('LibreameBackendBundle:Trato', 't', \Doctrine\ORM\Query\Expr\Join::WITH, 't.tratoejemplar = e.idejemplar and t.tratousrdueno = u.idusuario')
                        ->andWhere(' eu.invigente = :pvigente')//Publicacion vigente
                        ->setParameter('pvigente', GamesController::inDatoUno)//Debe cambiar a solo los ejemplares publicados = 1                    
                        ->andWhere(' u.idusuario = :idusuario')//Para el usuario logeado
                        ->setParameter('idusuario', $usuario)//Para el usuario logeado
                        ->orderBy(' e.idejemplar ', 'ASC');

                    $resejemplares = $q->getQuery()->getResult();
                    break;
                case GamesController::inDatoDos :  //En negociación
                    $q = $em->createQueryBuilder()
                        ->select('eu')
                        ->from('LibreameBackendBundle:Ejemplarusuario', 'eu')
                        ->leftJoin('LibreameBackendBundle:Ejemplar', 'e', \Doctrine\ORM\Query\Expr\Join::WITH, 'eu.ejemplarusuarioejemplar = e.idejemplar')
                        ->leftJoin('LibreameBackendBundle:Usuario', 'u', \Doctrine\ORM\Query\Expr\Join::WITH, 'u.idusuario = eu.ejemplarusuariousuario')
                        ->leftJoin('LibreameBackendBundle:Trato', 't', \Doctrine\ORM\Query\Expr\Join::WITH, 't.tratoejemplar = e.idejemplar and t.tratousrdueno = u.idusuario')
                        ->andWhere(' eu.innegociacion = :pnegociacion')//Publicacion en negociacion
                        ->setParameter('pnegociacion', GamesController::inDatoUno)//
                        ->andWhere(' u.idusuario = :idusuario')//Para el usuario logeado
                        ->setParameter('idusuario', $usuario)//Para el usuario logeado
                        ->orderBy(' e.idejemplar ', 'ASC');

                    $resejemplares = $q->getQuery()->getResult();
                    break;
                case GamesController::inDatoTre :  //Publicados
                    $q = $em->createQueryBuilder()
                        ->select('eu')
                        ->from('LibreameBackendBundle:Ejemplarusuario', 'eu')
                        ->leftJoin('LibreameBackendBundle:Ejemplar', 'e', \Doctrine\ORM\Query\Expr\Join::WITH, 'eu.ejemplarusuarioejemplar = e.idejemplar')
                        ->leftJoin('LibreameBackendBundle:Usuario', 'u', \Doctrine\ORM\Query\Expr\Join::WITH, 'u.idusuario = eu.ejemplarusuariousuario')
                        ->leftJoin('LibreameBackendBundle:Trato', 't', \Doctrine\ORM\Query\Expr\Join::WITH, 't.tratoejemplar = e.idejemplar and t.tratousrdueno = u.idusuario')
                        ->andWhere(' eu.invigente = :pvigente')//Publicacion vigente
                        ->setParameter('pvigente', GamesController::inDatoUno)//vigentes                    
                        ->andWhere(' u.idusuario = :idusuario')//Para el usuario logeado
                        ->setParameter('idusuario', $usuario)//Para el usuario logeado
                        ->andWhere(' eu.inpublicado = :ppublicado')//Publicado
                        ->setParameter('ppublicado', GamesController::inDatoUno)//Debe cambiar a solo los ejemplares publicados = 1                    
                        ->orderBy(' e.idejemplar ', 'ASC');

                    $resejemplares = $q->getQuery()->getResult();
                    break;
                case GamesController::inDatoCua :  //No Publicados
                    $q = $em->createQueryBuilder()
                        ->select('eu')
                        ->from('LibreameBackendBundle:Ejemplarusuario', 'eu')
                        ->leftJoin('LibreameBackendBundle:Ejemplar', 'e', \Doctrine\ORM\Query\Expr\Join::WITH, 'eu.ejemplarusuarioejemplar = e.idejemplar')
                        ->leftJoin('LibreameBackendBundle:Usuario', 'u', \Doctrine\ORM\Query\Expr\Join::WITH, 'u.idusuario = eu.ejemplarusuariousuario')
                        ->leftJoin('LibreameBackendBundle:Trato', 't', \Doctrine\ORM\Query\Expr\Join::WITH, 't.tratoejemplar = e.idejemplar and t.tratousrdueno = u.idusuario')
                        ->andWhere(' eu.invigente = :pvigente')//Publicacion vigente
                        ->setParameter('pvigente', GamesController::inDatoUno)//vigentes                    
                        ->andWhere(' u.idusuario = :idusuario')//Para el usuario logeado
                        ->setParameter('idusuario', $usuario)//Para el usuario logeado
                        ->andWhere(' eu.inpublicado = :ppublicado')//No Publicado
                        ->setParameter('ppublicado', GamesController::inDatoCer)//Ejemplares no publicados                    
                        ->orderBy(' e.idejemplar ', 'ASC');

                    $resejemplares = $q->getQuery()->getResult();
                    break;
                case GamesController::inDatoCin:  //Bloqueados
                    $q = $em->createQueryBuilder()
                        ->select('eu')
                        ->from('LibreameBackendBundle:Ejemplarusuario', 'eu')
                        ->leftJoin('LibreameBackendBundle:Ejemplar', 'e', \Doctrine\ORM\Query\Expr\Join::WITH, 'eu.ejemplarusuarioejemplar = e.idejemplar')
                        ->leftJoin('LibreameBackendBundle:Usuario', 'u', \Doctrine\ORM\Query\Expr\Join::WITH, 'u.idusuario = eu.ejemplarusuariousuario')
                        ->leftJoin('LibreameBackendBundle:Trato', 't', \Doctrine\ORM\Query\Expr\Join::WITH, 't.tratoejemplar = e.idejemplar and t.tratousrdueno = u.idusuario')
                        ->andWhere(' eu.invigente = :pvigente')//Publicacion vigente
                        ->setParameter('pvigente', GamesController::inDatoUno)//vigentes                    
                        ->andWhere(' u.idusuario = :idusuario')//Para el usuario logeado
                        ->setParameter('idusuario', $usuario)//Para el usuario logeado
                        ->andWhere(' eu.inpublicado = :ppublicado')//No Publicado
                        ->setParameter('ppublicado', GamesController::inDatoCer)//Ejemplares no publicados                    
                        ->andWhere(' eu.inbloqueado = :pbloqueado')//Bloqueado
                        ->setParameter('pbloqueado', GamesController::inDatoUno)//Ejemplares bloqueados                    
                        ->orderBy(' e.idejemplar ', 'ASC');

                    $resejemplares = $q->getQuery()->getResult();
                    break;
            }    

            return $q->getQuery()->getResult();
            //return $q->getArrayResult();
        } catch (Exception $ex) {
                //echo "retorna error";
                return new Ejemplar();
        } 
    }
                
    //Publica un mensaje
    public function publicaMensajes(LbMensajes $mensaje)
    {   
        try{
            $em = $this->getDoctrine()->getManager();
            
            //Verifica el tipo de mensaje para determinar si tiene que enviar a más 
            //destinatarios tambien envía correos.
            $tipomensaje = $mensaje->getInmenorigen();
            switch ($tipomensaje) {
                case GamesController::inMsPubEjem:
                    
                
            }
            
            return $query->getResult();

        } catch (Exception $ex) {
                return new LbMensajes();
        } 
    }
    

    //Obtiene los mensajes asociados a un usuario
    public function getMensajesUsuario(LbUsuarios $usuario)
    {   
        try{
            $em = $this->getDoctrine()->getManager();
            
            //echo "[id: ".$usuario->getInusuario()."]\n";
            //echo "[USUARIO: ".$usuario->getTxusuemail()."]\n";
            
            $sql = "SELECT e FROM LibreameBackendBundle:LbMensajes e "
                    . " WHERE e.inmenusuario = :usr"
                    . " OR e.inmenusuarioorigen = :usr";

            $query = $em->createQuery($sql)->setParameter('usr', $usuario);
            //echo $sql;ge
            return $query->getResult();

        } catch (Exception $ex) {
                return new LbMensajes();
        } 
    }
    
    //Marca un mensaje como Leído / No leído
    public function setMarcaMensaje($psolicitud)
    {   
        try{
            $em = $this->getDoctrine()->getManager();
            
            $usuario = ManejoDataRepository::getUsuarioByEmail($psolicitud->getEmail());
            $negociacion = new LbNegociacion();
            $negociacion = $em->getRepository('LibreameBackendBundle:LbNegociacion')->
                    findOneBy(array('inidnegociacion' => $psolicitud->getIdmensaje()));
            
            if ($negociacion != NULL) {
                if($negociacion->getInnegusuduenho()==$usuario) {
                    $negociacion->setInnegmensleidodue($psolicitud->getMarcacomo());
                } else {
                    $negociacion->setInnegmensleidosol($psolicitud->getMarcacomo());
                }
                $em->persist($negociacion);
                $em->flush();
                $resp = GamesController::inExitoso;
            } else {
                $resp = GamesController::inMenNoEx;
            }
            
            return $resp;
        } catch (Exception $ex) {
                return new LbMensajes();
        } 
    }
    
    //Guarda CUALQUIER ENTIDAD del parametro
    //ex4plays::Adicionado $em
    public function persistEntidad($entidad, EntityManager $em)
    {   
        try{
            //echo "<script>alert('1Persiste usuario')</script>";
            $em->persist($entidad);
            $em->flush();
            //echo "<script>alert('2Persiste usuario')</script>";
        } catch (Exception $ex) {
                return null;
        } 
    }
   
    /*
     * Recupera un comentario, con su Id numerico
     */
    public function getComentarioById($idcomentario){
        try{
            $em = $this->getDoctrine()->getManager();
            return $em->getRepository('LibreameBackendBundle:LbComentarios')->
                    findOneBy(array('inidcomentario' => $idcomentario));

        } catch (Exception $ex) {
                return new LbComentarios();
        } 
    }

    /*
     * Crea un ejemplar a partir de una solicitud y el libro que representa
     */
    public function crearEjemplar(Solicitud $psolicitud, LbLibros $libro, LbUsuarios $usuario)
    {
        $imagen = base64_encode($psolicitud->getImageneje());
        $ejemplar = new LbEjemplares();
        try {
            //$em = $this->getDoctrine()->getManager();
            $ejemplar->setInejecantidad(GamesController::inIdGeneral);//Se utiliza esta constante porque representa el # 1  
            $ejemplar->setDbejeavaluo($psolicitud->getAvaluo());  
            $ejemplar->setInejelibro($libro);  
            $ejemplar->setInejeusudueno($usuario);  
            $ejemplar->setTxejeimagen($imagen);  
     
            //$em->persist($ejemplar);
            //$em->flush();    
            
            return $ejemplar;
        } catch (Exception $ex)  {    
            return $ejemplar;
        }
    }
    
    //Cierra la sesion de un usuario 
    // ex4playS :: $em y modicficaciones al modelo
    public static function cerrarSesionUsuario(Sesion $sesion, $em)
    {   
        try{
            setlocale (LC_TIME, "es_CO");
            $fecha = new \DateTime;
            
            $sesion->setfesesfechafin($fecha);
            $sesion->setinsesactiva(GamesController::inDatoCer);
            
            $em->persist($sesion);
            
            $em->flush();
            
            return $sesion;
        } catch (Exception $ex) {
             return new Sesion();
        } 
    }

    //Función que retorna la cantidad de mensajes que un usuario tiene sin leer en la plataforma
    public static function cantMsgUsr($usuario)
    {
        try{
            /*$em = $this->getDoctrine()->getManager();
            $sql = "SELECT COUNT(m) FROM LibreameBackendBundle:LbMensajes m"
                    ." WHERE m.inmenusuario = :usuario";
            $query = $em->createQuery($sql)->setParameter('usuario', $usuario);
            $cantmensajes = $query->getSingleScalarResult();
            $em->flush();*/
        
            $cantmensajes = 5;
            return $cantmensajes;
        } catch (Exception $ex) {
                return GamesController::inDatoCer;
        } 
    }
    
    //Adiciona todo el texto de un libro, al indice 
    public function indexar(LbLibros $libro, $texto, $em)
    {
        try{
            //echo "FULL: ".$texto."\n";
            $arPalDescartar = array('a', 'ante', 'bajo', 'con', 'contra', 'de', 'desde', 
                'en', 'entre', 'hacia', 'hasta', 'para', 'por', 'segun', 'sin', 'so', 
                'sobre', 'tras', 'yo', 'tu', 'usted', 'el', 'nosotros', 'vosotros', 
                'ellos', 'ellas', 'ella', 'la', 'los', 'la', 'un', 'una', 'unos', 
                'unas', 'es', 'del', 'de', 'mi', 'mis', 'su', 'sus', 'lo', 'le', 'se', 
                'si', 'lo', 'identificar', 'no', 'al', 'que', '1', '2', '3', '4', '5', 
                '6', '7', '8', '9', '0', '(', ',', '.', ')', '"', '&', '/', '-', '=', 
                'y', 'o', '¡', '¿', '?', ':'); 
            if ($em == NULL) { $flEm = TRUE; } else  { $flEm = FALSE; }
            
            if ($flEm) {$em = $this->getDoctrine()->getManager();}
            //echo $texto."\n ----------------------"; 
            $texto = str_replace('(', '', $texto); 
            $texto = str_replace('¡', '', $texto);
            $texto = str_replace('?', '', $texto);
            $texto = str_replace('-', '', $texto);
            $texto = str_replace('/', '', $texto);
            $texto = str_replace('=', '', $texto);
            $texto = str_replace('&', '', $texto);
            $texto = str_replace(',', '', $texto);
            $texto = str_replace('.', '', $texto);
            $texto = str_replace(')', '', $texto);
            $texto = str_replace('"', '', $texto);
            $texto = str_replace(':', '', $texto);
            //echo $texto."\n ----------------------"; 

            $palabras = explode(" ", $texto);
            $repetidos = [];
            
            foreach ($palabras as $palabra)
            {   
                //echo "... ".$palabra."\n";
                if(!in_array(strtolower($palabra), $arPalDescartar) and 
                        !in_array(strtolower($palabra), $repetidos) and $palabra != "")
                {
                    if (!$em->getRepository('LibreameBackendBundle:LbIndicepalabra')->
                        findOneBy(array('lbindpalpalabra' => $palabra, 'lbindpallibro' => $libro)))
                    {    
                        //echo "   SI   \n";
                        $indice = new LbIndicepalabra();
                        $indice->setLbindpallibro($libro);
                        $dioma = $libro->getInlibidioma();
                        if ($dioma == NULL)
                            $indice->setLbindpalidioma("Sin especificar");
                        else
                            $indice->setLbindpalidioma(utf8_encode($idioma()->getTxidinombre()));
                        $indice->setLbindpalpalabra(strtolower($palabra));
                        $em->persist($indice);
                        $repetidos[] = $palabra; 
                    }
                }
            }
            
            if ($flEm) {$em->flush();}
        } catch (Exception $ex) {
                return GamesController::inDatoCer;
        } 
    }    
    
    //Obtiene la lista de idiomas
    public function getListaIdiomas()
    {   
        try{
            $em = $this->getDoctrine()->getManager();
            
            $sql = "SELECT i FROM LibreameBackendBundle:LbIdiomas i ";
            $query = $em->createQuery($sql);
            foreach ($query->getResult() as $regidioma){
                $idiomas[] = $regidioma->getTxidinombre();
                //echo $idiomas;
                //echo $regidioma->getInididioma().' '.$regidioma->getTxidinombre();
            }            
            //echo $query->getResult(); 
            //echo "Acabo"; 
            return $query->getResult();
            //return $idiomas;

        } catch (Exception $ex) {
                return new LbIdiomas();
        } 
    }
    
    //Obtiene la lista de editoriales
    public function getListaEditoriales()
    {   
        try{
            $em = $this->getDoctrine()->getManager();
            
            $sql = "SELECT e FROM LibreameBackendBundle:LbEditoriales e ";
            $query = $em->createQuery($sql);
            
            return $query->getResult();

        } catch (Exception $ex) {
                return new LbEditoriales();
        } 
    }
    
    //Obtiene la lista de autores
    public function getListaAutores()
    {   
        try{
            $em = $this->getDoctrine()->getManager();
            
            $sql = "SELECT a FROM LibreameBackendBundle:LbAutores a ";
            $query = $em->createQuery($sql);
            
            return $query->getResult();
            //return $idiomas;

        } catch (Exception $ex) {
                return new LbAutores();
        } 
    }
    
   //Marca un Megusta en un ejemplar
    public function setMegustaEjemplar($ejemplar, $megusta, $usuario)
    {   
        try{
            setlocale (LC_TIME, "es_CO");
            $fecha = new \DateTime;
            $em = $this->getDoctrine()->getManager();
            $ObjEjemplar = ManejoDataRepository::getEjemplarById($ejemplar);
            $ObjUsuario = ManejoDataRepository::getUsuarioByEmail($usuario);
            
            $megustaEjemplar = ManejoDataRepository::getRegMegustaEjemplar($ObjEjemplar, $ObjUsuario);
            
            if ($megustaEjemplar == NULL) {
                $megustaEjemplar = new LbMegusta();
                $megustaEjemplar->setInmegejemplar($ObjEjemplar);
                $megustaEjemplar->setInmegusuario($ObjUsuario);
                $megustaEjemplar->setInmegmegusta($megusta);
                $megustaEjemplar->setFemegmegusta($fecha);
            } else {
                $megustaEjemplar->setInmegmegusta($megusta);
                $megustaEjemplar->setFemegmegusta($fecha);
            }
            $em->persist($megustaEjemplar);

            $em->flush();
            return GamesController::inDatoUno;

        } catch (Exception $ex) {
                return GamesController::inDatoCer;
        } 
    }
    
   //Envia un comentario a un ejemplar
    public function setComentarioEjemplar(Solicitud $psolicitud)
    {   
        try{
            setlocale (LC_TIME, "es_CO");
            $fecha = new \DateTime;
            $em = $this->getDoctrine()->getManager();
            $ObjEjemplar = ManejoDataRepository::getEjemplarById($psolicitud->getIdEjemplar());
            $ObjUsuario = ManejoDataRepository::getUsuarioByEmail($psolicitud->getEmail());
            if($psolicitud->getIdComentario() == ""){//Si no viene el Id del cometario, es porque es nuevo
                $comentarioEjemplar = new LbComentarios();
                $comentarioEjemplar->setFecomfeccomentario($fecha);
                $comentarioEjemplar->setIncomactivo(GamesController::inDatoUno);
                if($psolicitud->getIdComPadre() != ""){ 
                    $compadre = ManejoDataRepository::getComentarioById($psolicitud->getIdComPadre());
                    $comentarioEjemplar->setIncomcompadre($compadre);
                }
                $comentarioEjemplar->setIncomejemplar($ObjEjemplar);
                $comentarioEjemplar->setIncomusuario($ObjUsuario);
                $comentarioEjemplar->setTxcomcomentario(utf8_encode($psolicitud->getComentario()));
            } else { //Si no viene, puede ser Edicion o borrado
                $comentarioEjemplar = new LbComentarios();
                $comentarioEjemplar = ManejoDataRepository::getComentarioById($psolicitud->getIdComentario());
                if($psolicitud->getAccionComm()=="0"){ //Si es 0: borrado
                    $comentarioEjemplar->setIncomactivo(GamesController::inDatoCer);
                } else if($psolicitud->getAccionComm()=="1")  { //Si es 1: edicion, modifica la fecha y el texto
                    $comentarioEjemplar->setFecomfeccomentario($fecha);
                    $comentarioEjemplar->setTxcomcomentario(utf8_encode($psolicitud->getComentario()));
                }
            }

            $em->persist($comentarioEjemplar);

            $em->flush();
            return GamesController::inDatoUno;

        } catch (Exception $ex) {
                return GamesController::inDatoCer;
        } 
    }
 
   //Envia un mensaje en el chat de negociacion por un ejemplar
    public function setMensajeChat(Solicitud $psolicitud)
    {   
        try{
            //Si el mensaje viene en blanco, no se genera nada
            $respuesta = NULL;
            setlocale (LC_TIME, "es_CO");
            $fecha = new \DateTime;
            $em = $this->getDoctrine()->getManager();
            $objEjemplar = ManejoDataRepository::getEjemplarById($psolicitud->getIdEjemplar());
            $usrEscribe = ManejoDataRepository::getUsuarioByEmail($psolicitud->getEmail());
            $usrDestino = ManejoDataRepository::getUsuarioById($psolicitud->getIdusuariodes());
            $usrPropiet = $objEjemplar->getInejeusudueno();
            if ($usrEscribe == $usrPropiet) { //Si el usuario que escribe es el propietario, solicitante = destinatario; si son diferentes, solicitante = escribe
                $usrSolicit = $usrDestino;
            } else {
                $usrSolicit = $usrEscribe;
            } 
            $mensaje = $psolicitud->getComentario();
            $negIdConver = "D".$usrPropiet->getInusuario()."S".$usrSolicit->getInusuario()."E".$objEjemplar->getInejemplar();
            //echo "tratoacep : ".$psolicitud->getTratoAcep();
            // Si el registro es de 1 Aceptacion o 0: Rechazo, el mensaje es personalizado con una constante
            
            switch ($psolicitud->getTratoAcep()) {
                case GamesController::inAccMsgNormal: //si es = -1
                    //echo "entro a tratoacep : -1";
                    $mensaje = $psolicitud->getComentario();
                    break;
                case GamesController::inAccMsgCancel: //si es = 0
                    //echo "entro a tratoacep : 0";
                    $mensaje = str_replace("%usuario", $usrEscribe->getTxusunommostrar(), GamesController::txMsgRechazoTr);
                    break;
                case GamesController::inAccMsgAcepta: //si es = 1
                    //echo "entro a tratoacep : 1";
                    $mensaje = str_replace("%usuario", $usrEscribe->getTxusunommostrar(), GamesController::txMsgAceptaTr);
                    break;
                case GamesController::inAccMsgOferta: //si es = 2
                    //echo "entro a tratoacep : 2";
                    $mensaje = str_replace("%usuario", $usrEscribe->getTxusunommostrar(), GamesController::txMsgHaceOferta);
                    break;
                case GamesController::inAccMsgContra: //si es = 3
                    //echo "entro a tratoacep : 3";
                    $mensaje = str_replace("%usuario", $usrEscribe->getTxusunommostrar(), GamesController::txMsgContraoferta);
                    break;
                case GamesController::inAccMsgEntreg: //si es = 4
                    //echo "entro a tratoacep : 4";
                    $mensaje = str_replace("%usuario", $usrEscribe->getTxusunommostrar(), GamesController::txMsgEntregaEjem);
                    break;
                case GamesController::inAccMsgRecibe: //si es = 5
                    //echo "entro a tratoacep : 5";
                    $mensaje = str_replace("%usuario", $usrEscribe->getTxusunommostrar(), GamesController::txMsgRecibeEjem);
                    break;
                case GamesController::inAccMsgSCalif: //si es = 6
                    //echo "entro a tratoacep : 6";
                    $mensaje = str_replace("%usuario", $usrEscribe->getTxusunommostrar(), GamesController::txMsgCalificacion);
                    break;
                case GamesController::inAccMsgDCalif: //si es = 7
                    //echo "entro a tratoacep : 7";
                    $mensaje = str_replace("%usuario", $usrEscribe->getTxusunommostrar(), GamesController::txMsgCalificacion);
                    break;
                case GamesController::inAccMsgFinali: //si es = 10
                    //echo "entro a tratoacep : 10";
                    $mensaje = GamesController::txMsgFinalizacion;
                    break;
            }
            if ($mensaje != "")
            {
                $chatNegociacion = new LbNegociacion();
                $chatNegociacion->setFenegfechamens($fecha);
                $chatNegociacion->setInnegmensleidodue(GamesController::inDatoCer);
                $chatNegociacion->setInnegmensleidosol(GamesController::inDatoCer);
                $chatNegociacion->setInnegmenseliminado(GamesController::inDatoCer);
                $chatNegociacion->setInnegejemplar($objEjemplar);
                $chatNegociacion->setInnegusuduenho($usrPropiet);
                $chatNegociacion->setInnegusuescribe($usrEscribe);
                $chatNegociacion->setInnegususolicita($usrSolicit);
                $chatNegociacion->setTxnegmensaje(utf8_encode($mensaje));
                $chatNegociacion->setTxnegidconversacion($negIdConver);
                $chatNegociacion->setInnegtratoacep($psolicitud->getTratoAcep());

                $em->persist($chatNegociacion);

                $em->flush();
            }
            $respuesta = $negIdConver;
            
            //echo "Conversacion : ".$negIdConver;
                
            return $respuesta;

        } catch (Exception $ex) {
                return GamesController::inDatoCer;
        } 
    }
 
    
   //Envia un registro de calificacion a un usuario y un registro historico del ejemplar:: Cierra el ciclo de negociacion
    public function setCalificaUsuarioTrato(Solicitud $psolicitud)
    {   
        try{
            $respuesta = NULL;
            setlocale (LC_TIME, "es_CO");
            $fecha = new \DateTime;
            $em = $this->getDoctrine()->getManager();
            //Obtiene : Ejemplar, usuario que califica y usuario calificado + Registro HistEjemplar de entrega o recibo. Hasta que no s
            $objEjemplar = ManejoDataRepository::getEjemplarById($psolicitud->getIdEjemplar());
            $usrCalifica = ManejoDataRepository::getUsuarioByEmail($psolicitud->getEmail());
            $usrCalificado = ManejoDataRepository::getUsuarioById($psolicitud->getIdusuariodes());
            //Registro historico de entrega o recibo
            $regHisRecEntr = ManejoDataRepository::getRegHisById($psolicitud->getInRegHisPublicacion());
            
            //Crea el registro histórico
            $regHisCalifica = new LbHistejemplar();
            $regHisCalifica->setFehisejeregistro($fecha);
            $regHisCalifica->setInhisejeejemplar($objEjemplar);
            $regHisCalifica->setInhisejeestado($fecha);
            $regHisCalifica->setInhisejemodoentrega($regHisRecEntr->getInhisejemodoentrega());
            $regHisCalifica->setInhisejepadre($regHisRecEntr);
            $regHisCalifica->setInhisejeusuario($usrCalifica);
            //Determinar cual es el usuario que califica GamesController::txMovUsPCali
            //Si el registro padre es de Recibo = txMovReciEje, quien califica es el Solicitante  inMovUsSCali
            //Si el registro padre es de Entrega = txMovEntrEje, quien califica es el Dueño inMovUsPCali
            $fallido = GamesController::inFallido;
            if ($regHisRecEntr->getInhisejemovimiento() == GamesController::inMovEntrEje){
                $regHisCalifica->setInhisejemovimiento(GamesController::inMovUsPCali);
                $fallido  = GamesController::inExitoso; 
            } elseif ($regHisRecEntr->getInhisejemovimiento() == GamesController::inMovReciEje) {
                $regHisCalifica->setInhisejemovimiento(GamesController::inMovUsSCali);
                $fallido = GamesController::inExitoso; 
            }
            
            if ($fallido  == GamesController::inExitoso){
                //Crea el registro de calificaicon
                $regCalifica = new LbCalificausuarios();
                $regCalifica->setFecalfecha($fecha);
                $regCalifica->setIncalcalificacion($psolicitud->getInCalificacion());
                $regCalifica->setIncalhisejemplar($objEjemplar);
                $regCalifica->setIncalusucalifica($usrCalifica);
                $regCalifica->setIncalusucalificado($usrCalificado);
                $regCalifica->setTxcalcomentario($psolicitud->getComentario());

                $em->persist($regHisCalifica);
                $em->persist($regCalifica);
                $em->flush();
            }
            
            $respuesta = $fallido;

            return $respuesta;

        } catch (Exception $ex) {
                return GamesController::inFallido;
        } 
    }
    
    //Valida datos de registro de    un usuario
    public function datosUsuarioValidos($usuario, $clave, $em)
    {
        try{
           //echo "manejodarepo:usr ".$usuario;
            //echo "manejodarepo:clave ".$clave;
            $vUsuario = new Usuario();
            $vUsuario = $em->getRepository('LibreameBackendBundle:usuario')->
                    findOneBy(array('txmailusuario' => $usuario, 
                        'txusuvalidacion' => $clave, 
                        'inusuestado' => GamesController::inDatoCer));
            
            //echo "mail ".$vUsuario->getTxusuemail();
            $em->flush();
            
            return $vUsuario;

        } catch (Exception $ex) {
                return NULL;
        } 
    }
    
    //Activa un usuario en accion de Validacion de Registro
    // * ex4plays :: eliminada la variable DEVICE 
    public function activarUsuarioRegistro(Usuario $usuario, $em)
    {
        try{
            /*  3. Marcar el usuario como activo
                4. Cambiar en la BD el ID. 
                5. Crear los registros en movimientos y bitacoras.
                6. Finalizar y mostrar web de confirmación.*/
            $respuesta=  GamesController::inFallido; 
            setlocale (LC_TIME, "es_CO");
            $fecha = new \DateTime;
            
            //$em = $this->getDoctrine()->getManager();
            $em->getConnection()->beginTransaction();


            $usuario->setInusuestado(GamesController::inDatoUno);
            $usuario->setTxusuvalidacion($usuario->getTxusuvalidacion().'OK');

            //Genera la sesion:: $pEstado,$pFecIni,$pFecFin,$pDevice,$pIpAdd
            $sesion = ManejoDataRepository::generaSesion($usuario,GamesController::inSesInac, $fecha, $fecha, GamesController::txMeNoIdS, $em);
            //Guarda la actividad de la sesion:: 
            ManejoDataRepository::generaActSesion($sesion,GamesController::inDatoUno,'Registro confirmado para usuario '.$usuario->getTxmailusuario(), GamesController::txAccConfRegi, $fecha, $fecha, $em);
            
            $em->persist($usuario);
            
            $em->flush();
            $em->getConnection()->commit();
            $respuesta=  GamesController::inExitoso; 
            
            return $respuesta;

        } catch (Exception $ex) {
                return  GamesController::inFallido;
        } 
    }    
    
    //Genera la carga y publicación de un ejemplar a la plataforma
    public static function generarPublicacionEjemplar(Solicitud $psolicitud, $em, &$respuesta){
        
        //Para publicar un ejemplar
        //1. Validar en front end el Libro y autocompletar si es necesario,  
        //   @TODO:  está funcion debe realizarse como servicio
        //Cuando llega a este punto ya ha validado todas las condiciones del usuario, 
        //planes, restricciones, penalizaciones, etc...DEFINIR BIEN
        try{
            //echo "ManejoDataRepository :: generarPublicacionEjemplar :: Inicia a generar la publicacion !!! \n";
            //echo utf8_encode($psolicitud->getTitulo())."\n";
            //echo "ManejoDataRepository :: generarPublicacionEjemplar :: ".$psolicitud->getTitulo()." \n";
            //echo utf8_decode($psolicitud->getTitulo())."\n";*/
            
            //error_reporting(E_ALL);
            $respuestaProc =  GamesController::inFallido; 
            $fecha = new \DateTime;
            
            $em->getConnection()->beginTransaction();
            
            //Recupera todas las variables de la solicitud
            $usuario = ManejoDataRepository::getUsuarioByEmail($psolicitud->getEmail(), $em);
            $videojuego = new Videojuego();
            $consola = new Consola();
            $fabricante = new Fabricante();
            //$imgbase64 = $psolicitud->getImageneje();
            $vjuegoExiste = GamesController::inFallido;
            //Si existe el videojuego, en la base de datos, se recupera por el ID
            if ($psolicitud->getIdvidjuego() != ""){
                //echo "ManejoDataRepository :: generarPublicacionEjemplar :: ID Videojuegono NO es vacio: Entra a recuperarlo \n";
                $vjuegoExiste = GamesController::inExitoso;
                $videojuego = ManejoDataRepository::getVideojuego($psolicitud->getIdvidjuego(), $em);
                $asocConsola = GamesController::inFallido;
                if ($psolicitud->getConsola() != "") {
                    $consola = ManejoDataRepository::getConsolaByNombre($psolicitud->getConsola(), $em);
                    $asocConsola = GamesController::inExitoso;
                } else {
                    $consola = ManejoDataRepository::getConsolaGenerica($em);
                    $asocConsola = GamesController::inExitoso;
                }
                $asocFabricante = GamesController::inFallido;
                if ($psolicitud->getFabricante() != "") {
                    $fabricante = ManejoDataRepository::getFabricanteByNombre($psolicitud->getFabricante(), $em);
                    $asocFabricante = GamesController::inExitoso;
                } else {
                    $fabricante = ManejoDataRepository::getFabricanteGenerico($em);
                    $asocFabricante = GamesController::inExitoso;
                }
            } else {
                //echo "ManejoDataRepository :: generarPublicacionEjemplar :: ID Videojuego = NULL \n";
                
                $asocFabricante = GamesController::inFallido;
                if ($psolicitud->getFabricante() != "") {
                    $fabricante = ManejoDataRepository::getFabricanteByNombre($psolicitud->getFabricante(), $em);
                    if ($fabricante == NULL) {
                        //echo "ManejoDataRepository :: generarPublicacionEjemplar :: Asigna fabricante ".$psolicitud->getFabricante()."\n";
                        $fabricante = new Fabricante();
                        $fabricante->settxnomfabricante($psolicitud->getFabricante());
                        $fabricante->settxpaisfabricante(GamesController::txMeNoIdS);
                        $em->persist($fabricante);
                        $em->flush();
                        $asocFabricante = GamesController::inExitoso;
                    } else {
                        $asocFabricante = GamesController::inExitoso;
                        $fabricante = ManejoDataRepository::getFabricanteGenerico($em);
                    }
                } else {
                    $asocFabricante = GamesController::inExitoso;
                    $fabricante = ManejoDataRepository::getFabricanteGenerico($em);
                }   

                $asocConsola = GamesController::inFallido;
                if ($psolicitud->getConsola() != "") {
                    $consola = ManejoDataRepository::getConsolaByNombre($psolicitud->getConsola(), $em);
                    if ($consola == NULL) {
                        //echo "ManejoDataRepository :: generarPublicacionEjemplar :: Asigna la consola al objeto ".$psolicitud->getConsola()."\n";
                        $consola = new Consola();
                        $consola->settxnombreconsola($psolicitud->getConsola());
                        $consola->setfelanzamiento($fecha);
                        $consola->setconsolafabricante($fabricante);
                        $em->persist($consola);
                        $em->flush();
                        $asocConsola = GamesController::inExitoso;
                    } else {
                        $asocConsola = GamesController::inExitoso;
                        $consola = ManejoDataRepository::getConsolaGenerica($em);
                    }
                }  else {
                    $asocConsola = GamesController::inExitoso;
                    $consola = ManejoDataRepository::getConsolaGenerica($em);
                }  
                
                //echo "ManejoDataRepository :: generarPublicacionEjemplar :: Busca el videojuego por el nombre \n";
                $videojuego = ManejoDataRepository::getVideojuegoByNombre($psolicitud->getTitulo(), $em);
                if ($videojuego == NULL) {
                    //echo "ManejoDataRepository :: generarPublicacionEjemplar :: No existe el videojuego [".$psolicitud->getTitulo()."] \n";
                    //echo "ManejoDataRepository :: generarPublicacionEjemplar :: Entra a crearlo ".$psolicitud->getConsola()." - ".$psolicitud->getFabricante()."\n";
                    $videojuego = new Videojuego();
                    $videojuego->setfelanzamiento($fecha);
                    $videojuego->setincategvideojuego(GamesController::inDatoTre); //L más baja mientras se recategoriza
                    $videojuego->settxgenerovideojuego(GamesController::txMenNoId);
                    $videojuego->settximagen(GamesController::txMenNoId);
                    $videojuego->settxnomvideojuego($psolicitud->getTitulo());
                    $videojuego->settxobservaciones(GamesController::txMenNoId);
                    $videojuego->settxurlinformacion(GamesController::txMenNoId);
                    $videojuego->setvideojuegoConsola($consola);
                    $em->persist($videojuego);
                    $em->flush();
                    /*@TODO Que hcer con la imágen? 
                     * $videojuego->settximagen(ManejoDataRepository::getImportarImagenB64($psolicitud->getImageneje(), $videojuego->getidvideojuego(), GamesController::txIndCarpImgEjem));
                     * $em->persist($videojuego);
                     */
                } 
                
            }
                    
            //$avaluo = (Double)$psolicitud->getAvaluo();
            //$puntos = (Integer)$avaluo/ GamesController::inValPunto;
            
            //echo "ManejoDataRepository :: generarPublicacionEjemplar :: Listo el Videojuego - ahora Creará el ejemplar \n";
            $ejemplar = new Ejemplar();
            $ejemplar->setejemplarVideojuego($videojuego);
            $ejemplar->setfecargue($fecha);
            $ejemplar->setinejemplarpublicado(GamesController::inExitoso);
            //@TODO : Puntos
            //$ejemplar->setInejepuntos($puntos);
            $em->persist($ejemplar);
            $em->flush();

            //echo "ManejoDataRepository :: generarPublicacionEjemplar :: Ahora genera el vínculo ejemplar - usuario \n";
            //Genera asociacion ejemplar usuario
            $ejeUsuario = new Ejemplarusuario();
            $ejeUsuario->setejemplarusuarioejemplar($ejemplar);
            $ejeUsuario->setejemplarusuariousuario($usuario);
            $ejeUsuario->setfeduenodesde($fecha);//Movimiento de publicación
            $ejeUsuario->setfepublicacion($fecha);
            $ejeUsuario->setinpublicado(GamesController::inExitoso); //Publicado = 1
            $ejeUsuario->setinvigente(GamesController::inExitoso);
            $em->persist($ejeUsuario);
            //$em->flush();
            
            //echo "ManejoDataRepository :: generarPublicacionEjemplar :: Registra los puntos para el usuario \n";
            $punUsuario = new Puntosusuario();
            $punUsuario->setfefechapuntos($fecha);
            //$inpuntaje = ManejoDataRepository::getPuntajeBarts(GamesController::inDatoTre);
            $inpuntaje = ManejoDataRepository::getPuntajeBarts($videojuego->getincategvideojuego());
            //echo "ManejoDataRepository :: generarPublicacionEjemplar :: Puntaje [".$inpuntaje."] \n";
            $punUsuario->setinpuntaje($inpuntaje);
            $punUsuario->setinsumaresta(GamesController::inSuma_);
            $punUsuario->setpuntosusuariousuario($usuario);
            //$punUsuario->setpunusuarioResenavideojuego($punusuarioResenavideojuego);
            //$punUsuario->setpunusuarioactiusuario();
            $punUsuario->setpunusuarioejemplar($ejemplar);
            $em->persist($punUsuario);
            
            //El objeto respuesta 
            $respuesta->setIdEjemplar($ejemplar->getidejemplar());
            $respuesta->setTitulo($videojuego->gettxnomvideojuego());
            $respuesta->setIdvidjuego($videojuego->getidvideojuego());
            
            //echo "ManejoDataRepository :: generarPublicacionEjemplar :: FINALIZÓ \n";
            
            ///PROXIMO PASO : CREAR LA TAREA PARA LOS USUARIOS QUE DARÁN VISTO BUENO AL PRECIO DEL LIBRO
            //Se buscan 10 usuarios de manera aleatoria para generarle una tarea
            /*$cont = 0;
            $arrUsers = [];
            $cantUsuarios = ManejoDataRepository::getCantidadUsuarios();
            while ($cont < 10) {
               $idRand = rand(1, $cantUsuarios);
               $usuConsulta = ManejoDataRepository::getUsuarioById($idRand);
               if ($usuConsulta != NULL){
                    if (($usuConsulta->getInusuestado() == GamesController::inUsuActi) && (!in_array($idRand, $arrUsers))){
                       $arrUsers[] = $idRand;
                       $cont++;
                    }
               }
            }
            
            for ($i=0;$i<10;$i++){
               $usuConsulta = ManejoDataRepository::getUsuarioById($arrUsers[$i]);
               $tarea = new LbTareas();
               $tarea->setFefechatarea($fecha);
               $tarea->setInusuariotareaasi($usuConsulta);
               $tarea->setInusuariotareades($usuario);
               $tarea->setDbvalorejesugerido($avaluo);
               $tarea->setInejemplartareades($ejemplar);
               $tarea->setInaprobadovaloreje(GamesController::inDatoCer);
               $tarea->setIntipotarea(GamesController::inTipTarApru);
               $tarea->setInestadotarea(GamesController::inEstTarPend);
               
               $em->persist($tarea);
            }*/
            
            
            $em->flush();
            $em->getConnection()->commit();
            $respuestaProc = GamesController::inExitoso; 
            return $respuestaProc;

        } catch (Exception $ex) {
                $em->getConnection()->rollback();
                return  GamesController::inFallido;
        } 
        //2. Revisa si el libro existe, si no existe lo crea, de Igual manra la 
        //   editorial y el / los autores y el género
        //   De igual manera debe crearse el TITULO, el IDIOMA, el Pais de la 
        //   editorial como del Autor
        //3. Elige 10 usuarios activos para que califiquen el precio del libro, 
        //   el libro queda pendiente por publicacion, 
        //   envía correos y se crean tareas para los usuarios
        //7. En este momento los puntos del usuario no le aparecen, hasta que 
        //   no haya sido validado
        //8. Se crea un registro de historialejemplar, hay que revisar con que 
        //   tipo de Movimiento, porque debe adicionarse
        
    }

    //Genera la carga y publicación de un ejemplar a la plataforma
    public static function generarDESPublicacionEjemplar(Solicitud $psolicitud, $em, &$respuesta){
        
        //Para des-publicar un ejemplar
        //1. Validar en front end el Libro y autocompletar si es necesario,  
        //   @TODO:  está funcion debe realizarse como servicio
        //Cuando llega a este punto ya ha validado todas las condiciones del usuario, 
        //planes, restricciones, penalizaciones, etc...DEFINIR BIEN
        try{
            //echo "ManejoDataRepository :: generarDESPublicacionEjemplar :: Inicia a generar la des-publicacion !!! \n";
            //echo utf8_encode($psolicitud->getTitulo())."\n";
            //echo "ManejoDataRepository :: generarDESPublicacionEjemplar :: ".$psolicitud->getTitulo()." \n";
            //echo utf8_decode($psolicitud->getTitulo())."\n";*/
            
            //error_reporting(E_ALL);
            $respuestaProc =  GamesController::inFallido; 
            $fecha = new \DateTime;
            
            $em->getConnection()->beginTransaction();
            
            //Recupera todas las variables de la solicitud
            $usuario = ManejoDataRepository::getUsuarioByEmail($psolicitud->getEmail(), $em);
            $ejemplarusuario = new Ejemplarusuario();
            $videojuego = new Videojuego();
            //$imgbase64 = $psolicitud->getImageneje();
            $vjuegoExiste = GamesController::inFallido;
            //Si existe el videojuego, en la base de datos, se recupera por el ID
            if (($psolicitud->getIdEjemplar() != "")){
                //echo "ManejoDataRepository :: generarPublicacionEjemplar :: ID Videojuegono NO es vacio: Entra a recuperarlo \n";
                $vjuegoExiste = GamesController::inExitoso;
                //En el json de entrada, el ejemplar = ejemplarusuario
                $ejemplarusuario = ManejoDataRepository::getEjemplarusuario($psolicitud->getIdEjemplar(), $em);
                if ($ejemplarusuario == NULL) {
                    //echo "ManejoDataRepository :: generarDESPublicacionEjemplar :: ID ejemplarusuario inválido \n";
                    $em->flush();
                    $em->getConnection()->rollback();
                    $respuestaProc = GamesController::inEjemInv; 
                    return $respuestaProc;
                } else {
                    $videojuego = ManejoDataRepository::getVideojuego($psolicitud->getIdvidjuego(), $em);
                }
            } else {
                //echo "ManejoDataRepository :: generarDESPublicacionEjemplar :: ID ejemplarusuario = NULL \n";
                $em->flush();
                $em->getConnection()->rollback();
                $respuestaProc = GamesController::inDatosOb; 
                return $respuestaProc;
            }
                    

        } catch (Exception $ex) {
                $em->getConnection()->rollback();
                return  GamesController::inFallido;
        } 
    }

    public function getCantidadUsuarios() {
        try{
            $em = $this->getDoctrine()->getManager();
            $qs = $em->createQueryBuilder()
                ->select('count(u.inusuario)')
                ->from('LibreameBackendBundle:LbUsuarios', 'u');
            $cuenta = $qs->getQuery()->getSingleScalarResult();
            
            return $cuenta;
        } catch (Exception $ex) {
                return GamesController::inDatoCer;
        } 
        
    }
    

    //El parámetro $idElemento indica el ID del objeto, bien sea Usuario o Ejemplar
    //El parámetro $blEjemUsuario, indica "E", si es un ejemplar o "U" si es un usuario
    public static function getImportarImagenB64($txImagenB64, $idElemento, $blEjemUsuario) {
        
        //set_error_handler("myFunctionErrorHandler", E_NOTICE);    
        $elem = (String)$idElemento;
        //echo "La cadena ".$ejem." \n";
        
        $chars = preg_split('//', $elem, -1, PREG_SPLIT_NO_EMPTY);
        //WIN \  LINUX /
        //Pregunta si es imagen de ejemplar, o de usuario
        if ($blEjemUsuario == GamesController::txIndCarpImgEjem) {
            $carpeta = GamesController::txCarpetaImgEjem;
            $carpetaWEB = GamesController::txCarpWEMImgEjem;
        } else {
            $carpeta = GamesController::txCarpetaImgUsua;
            $carpetaWEB = GamesController::txCarpWEMImgUsua;
        }
        //Para el pruebas
        ////ex4play
        //$carpeta = "C:/xampp/htdocs/ex4playS/web/img/p/";
        ////ex4read
        //$carpeta = "C:/xampp/htdocs/Ex4readBE/web/img/p/";
        //echo "Carpeta [".$carpeta."] \n";
        foreach ($chars as $digito) {
            $carpeta = $carpeta.$digito."/";
            $carpetaWEB = $carpetaWEB.$digito."/";
            //echo "Buscará si existe [".$carpeta."] \n";
            if (!file_exists($carpeta)) { 
                //echo "No existe directory [".$carpeta."], se creará ahora \n";
                mkdir($carpeta, 0755, true);
            } else {
                //echo "Si existe directory [".$carpeta."] \n";
            }
        }
        $base_to_php = explode(',', $txImagenB64);
        // El segundo item del array base_to_php contiene la información que necesitamos (base64 plano)
        // y usar base64_decode para obtener la información binaria de la imagen
        $data = base64_decode($base_to_php[1]);// BBBFBfj42Pj4....
        //echo "DATA DECODE ".$data;
        //echo "DATA : ".$data;
        
        $archivoOri = $carpeta.$elem."FULL.jpg"; // or image.jpg
        //echo $archivoOri." \n";
        $archivoOpt = $carpeta.$elem.".jpg"; // or image.jpg
        //echo $archivoOpt." \n";
        $archivoWEB = $carpetaWEB.$elem.".jpg"; // or image.jpg
        //echo $archivoWEB." \n";
        //echo "getImportarImagenB64: Borra archivos anteriores\n" ;
        if (file_exists($archivoOri)) {
            //echo "getImportarImagenB64: Existe ". $archivoOri ."\n" ;
            unlink($archivoOri);
        }
        if (file_exists($archivoOpt)) {
            //echo "getImportarImagenB64: Existe ". $archivoOpt ."\n" ;
            unlink($archivoOpt);
        }
        
        //echo "getImportarImagenB64: El archivo se poblará con datos". $archivoOri ."\n" ;
        file_put_contents($archivoOri, $data);  
        //set_error_handler(call_user_func(array($this,'myFunctionErrorHandler')), E_WARNING);
        set_error_handler(array($this,'myFunctionErrorHandler'), E_WARNING);
        
        try{
            $inTipoImg = exif_imagetype($archivoOri); 
            switch ($inTipoImg) {
                case IMAGETYPE_JPEG: {
                    //echo "getImportarImagenB64: JPEG \n" ;
                    $imagen = imagecreatefromjpeg($archivoOri);
                    break;
                }
                case IMAGETYPE_PNG: {
                    //echo "getImportarImagenB64: PNG \n" ;
                    $imagen = imagecreatefrompng($archivoOri);
                    break;
                }
                default: {
                    //echo "getImportarImagenB64: PNG \n" ;
                    $imagen = NULL;
                    break;
                }
            }
            
            if ($imagen != NULL) {
                //echo "getImportarImagenB64: Imágen valida \n" ;
                $this->inImagenValida = GamesController::inDatoUno;
                //echo "Dato uno";
            } else {
                //echo "getImportarImagenB64: Imágen NO valida \n" ;
                $this->inImagenValida = GamesController::inErrImag;
                unlink($archivoOri);
                //echo "Dato Cero";
            }
        } catch (Exception $e) {
            //imagejpeg($imagen, $archivo, 50);        
        }
        
        if ($this->inImagenValida == GamesController::inDatoUno)
        {
            //echo "getImportarImagenB64: Si es válida : Almacena imagen \n" ;
            switch ($inTipoImg) {
                case IMAGETYPE_JPEG: imagejpeg($imagen, $archivoOpt);
                case IMAGETYPE_PNG: imagepng($imagen, $archivoOpt);;
            }
               
            unlink($archivoOri);
            //echo "Se creo el archivo: ".$archivoOri." [".$archivoWEB."] \n";
            return $archivoWEB;
        } else {
            //echo "--NO Se creo el archivo: ".$archivoOri." [".$archivoWEB."] \n";
            return GamesController::txMeNoIdS;
        }
        
        restore_error_handler();
            
        //echo "El arhivo a crear : ".$archivo." \n";
        // Finalmente guarda la imágen en el directorio especificado y con la informacion dada
        //echo "Se creo el archivo: ".$archivo." [".$archivoWEB."], con los datos [".$txImagenB64."] \n";
        //echo "Se creo el archivo: ".$archivoOri." [".$archivoWEB."] \n";
        //   return "PENDIENTE;";
    }
    
    //Buscar los botones a mostrar dependiendo del usuario que está escribiendo
    //del estado de la negociacion y de la última acion realizada por el usuario
    public function getBotonesMostrar($conversa, $usuarioDueno, $ultAccion)
    {
        try {
            
            //Identifica si el usuario que esta escribiendo es el dueño o el solicitante
            if ($usuarioDueno == GamesController::inDatoUno) //Es el dueño
            {
                //AQUI SE DEBE REVISAR NO SOLO LA ULTIMA ACCION, SINO EL ESTADO DE LA NEGOCIACION REVISAR BIEN EL TEMA
                switch ($ultAccion) {
                   case GamesController::inAccMsgNormal: //si es = -1
                       //echo "entro a tratoacep : -1";
                       $mensaje = $psolicitud->getComentario();
                       break;
                   case GamesController::inAccMsgCancel: //si es = 0
                       //echo "entro a tratoacep : 0";
                       $mensaje = str_replace("%usuario", $usrEscribe->getTxusunommostrar(), GamesController::txMsgRechazoTr);
                       break;
                   case GamesController::inAccMsgAcepta: //si es = 1
                       //echo "entro a tratoacep : 1";
                       $mensaje = str_replace("%usuario", $usrEscribe->getTxusunommostrar(), GamesController::txMsgAceptaTr);
                       break;
                   case GamesController::inAccMsgContra: //si es = 3
                       //echo "entro a tratoacep : 3";
                       $mensaje = str_replace("%usuario", $usrEscribe->getTxusunommostrar(), GamesController::txMsgContraoferta);
                       break;
                   case GamesController::inAccMsgEntreg: //si es = 4
                       //echo "entro a tratoacep : 4";
                       $mensaje = str_replace("%usuario", $usrEscribe->getTxusunommostrar(), GamesController::txMsgEntregaEjem);
                       break;
                   case GamesController::inAccMsgDCalif: //si es = 7
                       //echo "entro a tratoacep : 7";
                       $mensaje = str_replace("%usuario", $usrEscribe->getTxusunommostrar(), GamesController::txMsgCalificacion);
                       break;
                   case GamesController::inAccMsgFinali: //si es = 10
                       //echo "entro a tratoacep : 10";
                       $mensaje = GamesController::txMsgFinalizacion;
                       break;
               }               

            } else {  // No es el dueño, es el solicitante
                switch ($ultAccion) {
                   case GamesController::inAccMsgNormal: //si es = -1
                       //echo "entro a tratoacep : -1";
                       $mensaje = $psolicitud->getComentario();
                       break;
                   case GamesController::inAccMsgCancel: //si es = 0
                       //echo "entro a tratoacep : 0";
                       $mensaje = str_replace("%usuario", $usrEscribe->getTxusunommostrar(), GamesController::txMsgRechazoTr);
                       break;
                   case GamesController::inAccMsgAcepta: //si es = 1
                       //echo "entro a tratoacep : 1";
                       $mensaje = str_replace("%usuario", $usrEscribe->getTxusunommostrar(), GamesController::txMsgAceptaTr);
                       break;
                   case GamesController::inAccMsgOferta: //si es = 2
                       //echo "entro a tratoacep : 2";
                       $mensaje = str_replace("%usuario", $usrEscribe->getTxusunommostrar(), GamesController::txMsgHaceOferta);
                       break;
                   case GamesController::inAccMsgRecibe: //si es = 5
                       //echo "entro a tratoacep : 5";
                       $mensaje = str_replace("%usuario", $usrEscribe->getTxusunommostrar(), GamesController::txMsgRecibeEjem);
                       break;
                   case GamesController::inAccMsgSCalif: //si es = 6
                       //echo "entro a tratoacep : 6";
                       $mensaje = str_replace("%usuario", $usrEscribe->getTxusunommostrar(), GamesController::txMsgCalificacion);
                       break;
                   case GamesController::inAccMsgFinali: //si es = 10
                       //echo "entro a tratoacep : 10";
                       $mensaje = GamesController::txMsgFinalizacion;
                       break;
               }               
                
            }   
            $botones = "-1,0,1";
            return $botones;
 
            } catch (\Doctrine\DBAL\DBALException  $ex) {
                //echo "<script>alert('::::".$ex->getMessage()."')</script>";
                return GamesController::inPlatCai;
        } 
    }

 
    
}