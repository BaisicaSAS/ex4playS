<?php

namespace Libreame\BackendBundle\Repository;

use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query;
use Libreame\BackendBundle\Controller\GamesController;
use Libreame\BackendBundle\Controller\EnviaMailController;
use Libreame\BackendBundle\Helpers\Respuesta;
use Libreame\BackendBundle\Helpers\Solicitud;
use Libreame\BackendBundle\Entity\Lugar;
use Libreame\BackendBundle\Entity\Usuario;
use Libreame\BackendBundle\Entity\Sesion;
use Libreame\BackendBundle\Entity\Actsesion;
use Libreame\BackendBundle\Helpers\Logica;
use Libreame\BackendBundle\Entity\Plansuscripcion;
use Libreame\BackendBundle\Entity\Planusuario;
use Libreame\BackendBundle\Entity\Detalleplan;
use Libreame\BackendBundle\Entity\Puntosusuario;
use Libreame\BackendBundle\Entity\Calificatrato; 
use Libreame\BackendBundle\Entity\Ejemplarusuario;
use Libreame\BackendBundle\Entity\Ejemplar;
use Libreame\BackendBundle\Entity\Videojuego;
use Libreame\BackendBundle\Entity\Consola;
use Libreame\BackendBundle\Entity\Fabricante;
use Libreame\BackendBundle\Entity\Actividadusuario;
use Libreame\BackendBundle\Entity\Trato;


/**
 * Description of ManejoDataRepository
 *
 * @author mramirez
 */
class ManejoDataRepository extends EntityRepository {

    var $inImagenValida;
     

    ///********************* LO QUE SE USA ********************************///
    //ex4plays :: Obtiene el nombre del genero de acuerdo con las constantes
    public static function getNomGenero($ingenero)
    {   
        $nomgenero = GamesController::txGenSinE;
        if($ingenero == GamesController::inGenSinE) {
            $nomgenero = GamesController::txGenSinE;
        } else if($ingenero == GamesController::inGenMasc) {
            $nomgenero = GamesController::txGenMasc;
        } else if($ingenero == GamesController::inGenFeme) {
            $nomgenero = GamesController::txGenFeme;
        } 
        return $nomgenero;
    }
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
        //5. Juegos de popularidad / demanda media (Sin importar la antiguedad) = 30 Barts
        //6. Juegos de popularidad / demanda baja (Sin importar la antiguedad) = 10 Barts
        $puntajeBARTs = ['1' => 50, '2' => 30, '3' => 10, '4' => 50, '5' => 30, '6' => 10];
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
                    //ex4play : Aunque el campo de clave se mantenga, sólo se valida en REGISTRO y LOGIN
                    
                    //Valida si la sesion está activa
                    //echo "validaSesionUsuario :: Verifica se la sesion está activa \n";
                    if (!ManejoDataRepository::usuarioSesionActiva($psolicitud, $psolicitud->getSession(), $em)){
                        //echo "validaSesionUsuario :: Sesion inactiva \n";
                        $respuesta = GamesController::inUsSeIna; //Sesion inactiva

                    } else {
                        $respuesta = GamesController::inULogged; //Sesion activa
                        //echo "validaSesionUsuario :: La sesion es VALIDA \n";
                        if (($psolicitud->getAccion()==GamesController::txAccRegistro)OR($psolicitud->getAccion()==GamesController::txAccIngresos)){
                            //if ($usuario->getTxclaveusuario() != $psolicitud->getClave()){
                            if (ManejoDataRepository::fnDecrypt($usuario->getTxclave(), GamesController::txSecret) != ManejoDataRepository::fnDecrypt($psolicitud->getClave(), GamesController::txSecret)){
                                //echo "validaSesionUsuario :: Clave invalida \n";
                                $respuesta = GamesController::inUsClInv; //Usuario o clave inválidos
                            }
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
                    findOneBy(array('idplansuscripcion' => $planusuario->getplanusuarioplan()));
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
                
    //Obtiene el detalle del plan del usuario
    public static function getDetallePlan(Usuario $usuario, $em)
    {   
        try{            
            $planus = $em->getRepository('LibreameBackendBundle:Planusuario')->
                    findOneBy(array('planusuariousuario' => $usuario));
  
            $detalleplan = $em->getRepository('LibreameBackendBundle:Detalleplan')->
                    findOneBy(array('detalleplanplan' => $planus->getplanusuarioplan()));
            return $detalleplan;
            
        } catch (Exception $ex) {
                //ECHO "ERROR PLANES";
                return new Detalleplan();
        } 
    }
                
    //ex4plays :: Obtiene un objeto trato
    public static function getTratoById($idtrato, $em)
    {   
        //error_reporting(E_ALL);
        //echo "\n getTratoById : Ingresa ";
        try{
            //echo "\n getUsuarioByEmail : Ingresa ".$txemail;
            return $em->getRepository('LibreameBackendBundle:Trato')->
                findOneBy(array('idtrato' => $idtrato));
        } catch (Exception $ex) {
                return new Trato();
        } 
    }

    //ex4plays :: Obtiene un objeto trato
    public static function getDetalleTratoById(Trato $trato, $em)
    {   
        //error_reporting(E_ALL);
        //echo "\n getDetalleTratoById : Ingresa ";
        try{
            //echo "\n getDetalleTratoById : Busca \n";
            $actusuario = $em->getRepository('LibreameBackendBundle:Actividadusuario')->
                findBy(array('actusuariotrato' => $trato));
            
            //echo "\n getDetalleTratoById : retorna ".count($actusuario)." registro \n ";
            return $actusuario;
            
        } catch (Exception $ex) {
                return new Actividadusuario();
        } 
    }

    //Obtiene la suma de puntos de usuario
    public static function getPuntosUsuario(Usuario $pUsuario, $em)
    {   
        try{
    
            $qpuS = $em->createQueryBuilder()
                ->select('COALESCE(SUM(a.inpuntaje), 0) AS inpuuscantpuntos')
                ->from('LibreameBackendBundle:Puntosusuario', 'a')
                ->Where('a.puntosusuariousuario = :pusuario')
                ->andWhere('a.insumaresta = :insuma')    
                ->setParameter('pusuario', $pUsuario)
                ->setParameter('insuma', GamesController::inSuma_)
                ->setMaxResults(1);
            
            $qpuR = $em->createQueryBuilder()
                ->select('COALESCE(SUM(a.inpuntaje), 0) AS inpuuscantpuntos')
                ->from('LibreameBackendBundle:Puntosusuario', 'a')
                ->Where('a.puntosusuariousuario = :pusuario')
                ->andWhere('a.insumaresta = :inresta')    
                ->setParameter('pusuario', $pUsuario)
                ->setParameter('inresta', GamesController::inResta)
                ->setMaxResults(1);
            
            $puntosPos = GamesController::inDatoCer;
            if($qpuS->getQuery()->getOneOrNullResult() == NULL){
                $puntosPos = GamesController::inDatoCer; //Si ho hay registros devuelve Puntos = 0
            } else {
                $puntosPos = (int)$qpuS->getQuery()->getSingleScalarResult();//Si hay registros devuelve lo que hay
            }    
            
            $puntosNeg = GamesController::inDatoCer;
            if($qpuR->getQuery()->getOneOrNullResult() == NULL){
                $puntosNeg = GamesController::inDatoCer; //Si ho hay registros devuelve Puntos = 0
            } else {
                $puntosNeg = (int)$qpuR->getQuery()->getSingleScalarResult();//Si hay registros devuelve lo que hay
            }    
            
            return $puntosPos - $puntosNeg;
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
                ->andWhere(' e.invigente = :vigente ')
                ->setParameter('vigente', GamesController::inDatoUno) // 0 es trato Cerrado
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
            

            $arrGeneros = array();
            //foreach ($generos as $gen){
            //    if (!in_array($gen, $arrGeneros)) {
            //        $arrGeneros[] = array("idgenero" => $gen['ingenero'],"nomgenero" => utf8_encode($gen['txgennombre']));
            //    }
            //}
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
                    ->andWhere(' e.inejemplarpublicado = :ppublicado')//Debe cambiar a solo los ejemplares publicados = 1
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
                ->andWhere(' e.inejemplarpublicado = :ppublicado')//Debe cambiar a solo los ejemplares publicados = 1
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
    
   //Obtiene el objeto Ejemplarusuario según el ejemplar y el usuario
    public static function getEjemplarusuarioByUsrEjemplar($idejemplar, $idusuario, $em)
    {   
        try{
            $ejemplarusuario = $em->getRepository('LibreameBackendBundle:Ejemplarusuario')->
                findOneBy(array("ejemplarusuariousuario"=>$idusuario, 
                    "ejemplarusuarioejemplar"=>$idejemplar));
            
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
    public static function getConsolaByNombre($nomconsola, $em)
    {   
        try{
            
            $consola = $em->getRepository('LibreameBackendBundle:Consola')->
                findOneBy(array("txnombreconsola"=>$nomconsola));
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
    

    //Obtiene el objeto Usuario según su ID 
    public static function getUsuarioById($idusuario, $em)
    {   
        try{
            $usuario = $em->getRepository('LibreameBackendBundle:Usuario')->
                findOneBy(array('idusuario' => $idusuario, 'inusuestado' => GamesController::inExitoso));
            
            return $usuario;
        } catch (Exception $ex) {
                return new Usuario();
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
            //echo "setCambiarClave : cambia clave \n";
            //echo "setCambiarClave : clave : [".$psolicitud->getClaveNueva()."] \n";
            $usuario->setTxclave($psolicitud->getClaveNueva());  
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
                return new Usuario();
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
    public static function fnEncrypt/*aes128Encrypt*/($data, $key) {
        if(16 !== strlen($key)) $key = hash('MD5', $key, true);
        $padding = 16 - (strlen($data) % 16);
        $data .= str_repeat(chr($padding), $padding);
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, str_repeat("\0", 16)));
    }
    
    public static function fnDecrypt/*aes128Decrypt*/($data, $key) {
        $data = base64_decode($data);
        if(16 !== strlen($key)) $key = hash('MD5', $key, true);
        $data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, str_repeat("\0", 16));
        $padding = ord($data[strlen($data) - 1]); 
        return substr($data, 0, -$padding); 
    }
    
    /*public static function fnEncrypt($sValue, $sSecretKey) {
        //echo "Valor [".$sValue."] - Secret [".$sSecretKey."]";
        //return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $sSecretKey, $sDecrypted, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
        //return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $sSecretKey, $sValue, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $sSecretKey, $sValue, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }

    public static function fnDecrypt($sValue, $sSecretKey) {
        //echo "Valor [".$sValue."* - Secret [".$sSecretKey."]";
        //return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $sSecretKey, base64_decode($sValue), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
        return trim(base64_encode(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $sSecretKey, $sValue, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    } */  
///********************* LO QUE NO SE USA ********************************///
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
                
    //Obtiene la cantidad de mensajes sin leer  (Alertas)
    public static function getMensajesSinLeerUsuario(Usuario $usuario, $em)
    {   
        try{            
            
            $qMensajes = $em->createQueryBuilder()
                ->select('COALESCE(COUNT(a.actusuarioleido), 0) AS mensajes')
                ->from('LibreameBackendBundle:Actividadusuario', 'a')
                ->Where('a.actusuariousuariolee = :usuario')
                ->andWhere('a.actusuarioleido = :leido')    
                ->setParameter('usuario', $usuario)
                ->setParameter('leido', GamesController::inDatoCer)
                ->setMaxResults(1);
            
            $mensajes = GamesController::inDatoCer;
            if($qMensajes->getQuery()->getOneOrNullResult() == NULL){
                $mensajes = GamesController::inDatoCer; //Si ho hay registros devuelve Puntos = 0
            } else {
                $mensajes = (int)$qMensajes->getQuery()->getSingleScalarResult();//Si hay registros devuelve lo que hay
            }    

            return $mensajes;

        } catch (Exception $ex) {
                return GamesController::inDatoCer;
        } 
    }
    
    //Obtiene la cantidad de mensajes sin leer  (Alertas)
    public static function getConversacionTrato(Trato $trato, $em)
    {   
        try{            
            
            $qMensajes = $em->createQueryBuilder()
                ->select('a')
                ->from('LibreameBackendBundle:Actividadusuario', 'a')
                ->Where('a.actusuariotrato = :idtrato')
                ->setParameter('idtrato', $trato);
            
            $actusuario = $qMensajes->getQuery()->getResult();
            
            $mensajes = array();
            foreach ( $actusuario as $actusr ) {
                $usenv = $actusr->getactusuariousuarioescribe();
                $usrec = $actusr->getactusuariousuariolee();
                //echo "usuarios \n";
                $mensajes[] = array('idmensaje' => $actusr->getidactividadusuario(), 'idenvia' => $usenv->getidusuario(),
                    'idrecibe' => $usrec->getidusuario(), 'fecha' => $actusr->getactusuariofecha(),
                    'mensaje' => utf8_encode($actusr->getactusuariomensaje()), 'leido' => $actusr->getactusuarioleido());
            }
            

            return $mensajes;

        } catch (Exception $ex) {
                return GamesController::inDatoCer;
        } 
    }
    


    //Obtiene los trato asociados a un usuario
    public function getTratosUsuario(Usuario $usuario, $em)
    {   
        try{              
            error_reporting(E_ALL);

            //echo "Traera tratos usuario  \n";
            $cantT = (int)$em->createQueryBuilder()
                ->select('COALESCE(COUNT(a.idtrato), 0) AS canttratos')
                ->from('LibreameBackendBundle:Trato', 'a')
                ->Where('a.tratousrdueno = :usuario')
                ->orWhere('a.tratousrsolicita = :usuario')    
                ->setParameter('usuario', $usuario)
                ->setMaxResults(1)
                ->getQuery()->getSingleScalarResult();
                //echo "Los trajo ... cuantos?? \n";

            if ($cantT > 0) {
                //echo "Encontró ".$cantT." tratos para el usuario ".$usuario->getTxmailusuario()." \n";
                $qTratos = $em->createQueryBuilder()
                    //->select('a.idtrato as idtrato, a.idtratotexto as idtratotexto, a.fefechatrato as fefechatrato, a.inestadotrato as inestadotrato, a.inestadoentrega as inestadoentrega, a.inestadocancela as inestadocancela, a.inestadocalifica as inestadocalifica, a.tratoejemplar as tratoejemplar, a.tratousrdueno as tratousrdueno, a.tratousrsolicita as tratousrsolicita, a.intratoacciondueno as intratoacciondueno, a.intratoaccionsolicitante as intratoaccionsolicitante' )  
                    //->select('a.idtrato, a.idtratotexto, a.fefechatrato, a.inestadotrato, a.inestadoentrega, a.inestadocancela, a.inestadocalifica, a.tratoejemplar, a.tratousrdueno, a.tratousrsolicita, a.intratoacciondueno, a.intratoaccionsolicitante ' )  
                    ->select('a.idtrato, a.idtratotexto, a.fefechatrato, a.inestadotrato')  
                    //->select('a')  
                    ->from('LibreameBackendBundle:Trato', 'a')
                    ->Where('a.tratousrdueno = :usuario')
                    ->orWhere('a.tratousrsolicita = :usuario')    
                    ->setParameter('usuario', $usuario)
                    ->setMaxResults(10000)
                    ->getQuery()->getResult();

                //echo "Hizo el query \n";
                //echo "devuelve trato \n";
                return $qTratos ;
            } else {
                return new Trato();
            }

        } catch (Exception $ex) {
                return new Trato();
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
    
    //Obtiene la cantidad de BARTs del usuario
    public static function obtenerSaldosBARTs(Usuario $usuario, &$efectivos, &$credito, &$comprometidos, $em)
    {   
        try{
            //echo "Aqui calcula los BARTs \n";
            //Puntos efectivos 1, Puntos NO efectivos 0, Puntos comprometidos 2
            //Puntos efectivos positivos
            $qpuSEf = $em->createQueryBuilder()
                ->select('COALESCE(SUM(a.inpuntaje), 0) AS inpuuscantpuntos')
                ->from('LibreameBackendBundle:Puntosusuario', 'a')
                ->Where('a.puntosusuariousuario = :pusuario')
                ->andWhere('a.insumaresta = :insuma')    
                ->andWhere('a.incontar = :contar')    
                ->andWhere('a.inefectivos = :efectivos')    
                ->setParameter('pusuario', $usuario)
                ->setParameter('insuma', GamesController::inSuma_)
                ->setParameter('contar', GamesController::inDatoUno)
                ->setParameter('efectivos', GamesController::inDatoUno)
                ->setMaxResults(1);
            
            //Puntos crédito (o No Efectivos)  positivos
            $qpuSCr = $em->createQueryBuilder()
                ->select('COALESCE(SUM(a.inpuntaje), 0) AS inpuuscantpuntos')
                ->from('LibreameBackendBundle:Puntosusuario', 'a')
                ->Where('a.puntosusuariousuario = :pusuario')
                ->andWhere('a.insumaresta = :insuma')    
                ->andWhere('a.incontar = :contar')    
                ->andWhere('a.inefectivos = :credito')    
                ->setParameter('pusuario', $usuario)
                ->setParameter('insuma', GamesController::inSuma_)
                ->setParameter('contar', GamesController::inDatoUno)
                ->setParameter('credito', GamesController::inDatoCer)
                ->setMaxResults(1);
            
            //Puntos comprometidos positivos
            $qpuSCo = $em->createQueryBuilder()
                ->select('COALESCE(SUM(a.inpuntaje), 0) AS inpuuscantpuntos')
                ->from('LibreameBackendBundle:Puntosusuario', 'a')
                ->Where('a.puntosusuariousuario = :pusuario')
                ->andWhere('a.insumaresta = :insuma')    
                ->andWhere('a.incontar = :contar')    
                ->andWhere('a.inefectivos = :comprometidos')    
                ->setParameter('pusuario', $usuario)
                ->setParameter('insuma', GamesController::inSuma_)
                ->setParameter('contar', GamesController::inDatoUno)
                ->setParameter('comprometidos', GamesController::inDatoDos)
                ->setMaxResults(1);
            
            //Puntos efectivos negativos
            $qpuREf = $em->createQueryBuilder()
                ->select('COALESCE(SUM(a.inpuntaje), 0) AS inpuuscantpuntos')
                ->from('LibreameBackendBundle:Puntosusuario', 'a')
                ->Where('a.puntosusuariousuario = :pusuario')
                ->andWhere('a.insumaresta = :inresta')    
                ->andWhere('a.incontar = :contar')    
                ->andWhere('a.inefectivos = :efectivos')    
                ->setParameter('pusuario', $usuario)
                ->setParameter('inresta', GamesController::inResta)
                ->setParameter('contar', GamesController::inDatoUno)
                ->setParameter('efectivos', GamesController::inDatoUno)
                ->setMaxResults(1);
            
            //Puntos crédito (o No Efectivos) negativos
            $qpuRCr = $em->createQueryBuilder()
                ->select('COALESCE(SUM(a.inpuntaje), 0) AS inpuuscantpuntos')
                ->from('LibreameBackendBundle:Puntosusuario', 'a')
                ->Where('a.puntosusuariousuario = :pusuario')
                ->andWhere('a.insumaresta = :inresta')    
                ->andWhere('a.incontar = :contar')    
                ->andWhere('a.inefectivos = :credito')    
                ->setParameter('pusuario', $usuario)
                ->setParameter('inresta', GamesController::inResta)
                ->setParameter('contar', GamesController::inDatoUno)
                ->setParameter('credito', GamesController::inDatoCer)
                ->setMaxResults(1);
            
            //Puntos comprometidos negativos
            $qpuRCo = $em->createQueryBuilder()
                ->select('COALESCE(SUM(a.inpuntaje), 0) AS inpuuscantpuntos')
                ->from('LibreameBackendBundle:Puntosusuario', 'a')
                ->Where('a.puntosusuariousuario = :pusuario')
                ->andWhere('a.insumaresta = :inresta')    
                ->andWhere('a.incontar = :contar')    
                ->andWhere('a.inefectivos = :comprometidos')    
                ->setParameter('pusuario', $usuario)
                ->setParameter('inresta', GamesController::inResta)
                ->setParameter('contar', GamesController::inDatoUno)
                ->setParameter('comprometidos', GamesController::inDatoDos)
                ->setMaxResults(1);
            
            $efectivos = GamesController::inDatoCer;
            if($qpuSEf->getQuery()->getOneOrNullResult() == NULL){
                $efectivos = GamesController::inDatoCer; //Si ho hay registros devuelve Puntos = 0
            } else {
                $efectivos = (int)$qpuSEf->getQuery()->getSingleScalarResult();//Si hay registros devuelve lo que hay
            }    
            //echo "Efectivos : ".$efectivos." \n";
            
            $efectivosNeg = GamesController::inDatoCer;
            if($qpuREf->getQuery()->getOneOrNullResult() == NULL){
                $efectivosNeg = GamesController::inDatoCer; //Si ho hay registros devuelve Puntos = 0
            } else {
                $efectivosNeg = (int)$qpuREf->getQuery()->getSingleScalarResult();//Si hay registros devuelve lo que hay
            }    
            //echo "Efectivos Neg : ".$efectivosNeg." \n";

            $credito = GamesController::inDatoCer;
            if($qpuSCr->getQuery()->getOneOrNullResult() == NULL){
                $credito = GamesController::inDatoCer; //Si ho hay registros devuelve Puntos = 0
            } else {
                $credito = (int)$qpuSCr->getQuery()->getSingleScalarResult();//Si hay registros devuelve lo que hay
            }    
            //echo "Credito : ".$credito." \n";
            
            $creditoNeg = GamesController::inDatoCer;
            if($qpuRCr->getQuery()->getOneOrNullResult() == NULL){
                $creditoNeg = GamesController::inDatoCer; //Si ho hay registros devuelve Puntos = 0
            } else {
                $creditoNeg = (int)$qpuRCr->getQuery()->getSingleScalarResult();//Si hay registros devuelve lo que hay
            }    
            //echo "Credito Neg: ".$creditoNeg." \n";

            $comprometidos = GamesController::inDatoCer;
            if($qpuSCo->getQuery()->getOneOrNullResult() == NULL){
                $comprometidos = GamesController::inDatoCer; //Si ho hay registros devuelve Puntos = 0
            } else {
                $comprometidos = (int)$qpuSCo->getQuery()->getSingleScalarResult();//Si hay registros devuelve lo que hay
            }    
            //echo "Comprometidos : ".$comprometidos." \n";
            
            $comprometidosNeg = GamesController::inDatoCer;
            if($qpuRCo->getQuery()->getOneOrNullResult() == NULL){
                $comprometidosNeg = GamesController::inDatoCer; //Si ho hay registros devuelve Puntos = 0
            } else {
                $comprometidosNeg = (int)$qpuRCo->getQuery()->getSingleScalarResult();//Si hay registros devuelve lo que hay
            }    
            //echo "Comprometidos Neg : ".$comprometidosNeg." \n";
            
            
            
            $efectivos = $efectivos - $efectivosNeg;
            $credito = $credito - $creditoNeg;
            $comprometidos = $comprometidos - $comprometidosNeg;
            
        } catch (Exception $ex) {
                return GamesController::inDatoCer;
        } 
    }
    
    
    //Obtiene los datos de saldos de BARTs y parametros según el plan que posee 
    public function obtenerDatosParamUsuario(Usuario $usuario, $em)
    {
        try{
            $efectivos = 0;
            $credito = 0;
            $comprometidos = 0;
            ManejoDataRepository::obtenerSaldosBARTs($usuario, $efectivos, $credito, $comprometidos, $em);
            $parametros['BARTsCR'] = $credito; //BARTs en crédito
            $parametros['BARTsEF'] = $efectivos; //BARTs efectivos
            $parametros['BARTsCO'] = $comprometidos; //BARTs comprometidos
            
            //Parametros de Usuario
            $planusuario = new Planusuario();
            $planusuario = ManejoDataRepository::getPlanUsuario($usuario, $em);
            $detalleplan = new Detalleplan();
            $detalleplan = ManejoDataRepository::getDetallePlan($usuario, $em);
            
            $parametros['CANTVJ_MES'] = ManejoDataRepository::getJuegosMesUsuario($usuario, $em); //Cantidad videojuegos usuario mes
            $parametros['PEND_CALIF'] = ManejoDataRepository::getCalificacionesPendientes($usuario, $em); //Cantidad de calificaciones pendientes
            $parametros['VENC_PLAN'] = $planusuario->getfevigencia(); //Fecha de vencimiento del plan del usuario 
            $parametros['CREDITO_PLAN'] = $detalleplan->getinvjcredito(); //Cantidad de videojuegos en crédito del plan -1 indefinido
            $parametros['CREDITO_CATEG'] = $detalleplan->getincatjuegoscredito(); //Categoría de videojuegos en credito para el plan (0 Menor categoria, 1 Mayor categoria)
            $parametros['CANTVJ_PLAN'] = $detalleplan->getincantidadcambios(); //Cantidad videojuegos usuario permitido plan

            return $parametros;
            
        } catch (Exception $ex) {
                return NULL;
        }    
    }
    
    //Obtiene los videojuegos que ha transado un usuario en un mes
     public function getJuegosMesUsuario($usuario, $em){
        
        try {
            
            $fechaIni = new DateTime();
            $fechaIni->modify('first day of this month');
            //echo $fechaIni->format('d/m/Y'); 
            $fechaFin = new DateTime();
            $fechaFin->modify('last day of this month');
            //echo $fechaFin->format('d/m/Y'); 
            $qTratosMes = $em->createQueryBuilder()
                ->select('COALESCE(COUNT(t.idtrato), 0) AS cantidad')
                ->from('LibreameBackendBundle:Trato', 't')
                ->Where('t.tratousrdueno = :pusuario')
                ->orWhere('t.tratousrsolicita = :pusuario')    
                ->andWhere('t.fefechatrato between :fini and :ffin')    
                ->andWhere('t.inestadotrato = :pestado')    
                ->setParameter('pusuario', $usuario)
                ->setParameter('fini', $fechaIni)
                ->setParameter('ffin', $fechaFin)
                ->setParameter('pestado', GamesController::inDatoDos)
                ->setMaxResults(1);
        } finally {
            return GamesController::inDatoCer;
        }
    }
             
    
    //Obtiene la cantidad de calficaciones pendientes de un usuario         
    public function getCalificacionesPendientes($usuario, $em){
         return 0;
    }
    
    //Activa un usuario en accion de Validacion de Registro
    // * ex4plays :: eliminada la variable DEVICE 
    public function solicitaEjemplarVideojuego(Usuario $usuario, Ejemplar $ejemplar, Ejemplarusuario &$ejemplarusuario, &$respuesta, $em)
    {
        try{
            //obtiene los datos del ejemplar, precio en BARTs, "saldos" de BARTs y demás parametros
            /*
            $parametros['BARTsCR'] = $credito; //BARTs en crédito
            $parametros['BARTsEF'] = $efectivos; //BARTs efectivos
            $parametros['BARTsCO'] = $comprometidos; //BARTs comprometidos
            $parametros['CANTVJ_MES'] = ManejoDataRepository::getJuegosMesUsuario($usuario, $em); //Cantidad videojuegos usuario mes
            $parametros['PEND_CALIF'] = ManejoDataRepository::getCalificacionesPendientes($usuario, $em); //Cantidad de calificaciones pendientes
            $parametros['VENC_PLAN'] = $planusuario->getfevigencia(); //Fecha de vencimiento del plan del usuario 
            $parametros['CREDITO_PLAN'] = $detalleplan->getinvjcredito(); //Cantidad de videojuegos en crédito del plan -1 indefinido
            $parametros['CREDITO_CATEG'] = $detalleplan->getincatjuegoscredito(); //Categoría de videojuegos en credito para el plan (0 Menor categoria, 1 Mayor categoria)
            $parametros['CANTVJ_PLAN'] = $detalleplan->getincantidadcambios(); //Cantidad videojuegos usuario permitido plan
            */
            //$parametros = ['BARTsCR' => 0,'BARTsEF' => 0,'CANTVJ_MES' => 0,'PEND_CALIF' => 0,'VENC_PLAN' => '','CREDITO_PLAN' => 0,'CANTVJ_PLAN' => 0];
            
            $respuestaProc =  GamesController::inFallido; 
            setlocale (LC_TIME, "es_CO");
            $fecha = new \DateTime;
            $em->getConnection()->beginTransaction();
            
            $parametros = ManejoDataRepository::obtenerDatosParamUsuario($usuario, $em);
            
            $inValidado = GamesController::inDatoUno;
            $VJMESUSR = $parametros['CANTVJ_MES']; 
            $VJMESPLAN = $parametros['CANTVJ_PLAN'];
            $BARTSVJ = ManejoDataRepository::getPuntajeBarts($ejemplar->getejemplarVideojuego()->getincategvideojuego());
            $BARTSUSR = $parametros['BARTsCR'] + $parametros['BARTsEF'] - $parametros['BARTsCO'];
            $PEND_CALIF = $parametros['PEND_CALIF'];
            $FECVENCPLAN = $parametros['VENC_PLAN'];
            if ($BARTSVJ > $BARTSUSR) {
                $inValidado = GamesController::inDatoCer;//Si los BARTs NO Alcanzan
                //echo "Los BARTs no alcanzan :: Requeridos = [".$BARTSVJ."] - Usuario = [".$BARTSUSR."] \n";
            }
            if ($PEND_CALIF == GamesController::inDatoUno){
                $inValidado = GamesController::inDatoDos;//Si tiene calificaciones pendientes por realizar
            }
            if ($fecha > $FECVENCPLAN) {
                $inValidado = GamesController::inDatoTre;//Si está vencido el plan
            }
            if ($VJMESUSR >= $VJMESPLAN) {
                $inValidado = GamesController::inDatoCua;//Videojuegos del plan superados
            }
              
            //echo "Validado = ".$inValidado." \n || Si es 0 : No alcazan BARTs \n || 2 : Calif Pendientes \n || 3 : Plan Vencido \n || 4 : Cantidad de videojuegos superado \n ";
            $respuesta->setRespuestaTrato($inValidado);
            if ($inValidado == GamesController::inDatoUno) { 
                $trato = new Trato();
                $actividadusuario = new Actividadusuario();
                $puntosusuario = new Puntosusuario();

                //echo "ManejoData : solicitaEjemplarVideojuego :: Crea el trato ";
                $idtratotexto = "D".$ejemplarusuario->getejemplarusuariousuario()->getIdusuario()
                        ."S".$usuario->getIdusuario()."EU".$ejemplarusuario->getidejemplarusuario()
                        ."E".$ejemplar->getidejemplar();

                $trato->setidtratotexto($idtratotexto);
                $trato->settratoejemplar($ejemplar);
                $trato->settratousrdueno($ejemplarusuario->getejemplarusuariousuario());
                $trato->settratousrsolicita($usuario);
                $trato->setfefechatrato($fecha);
                $em->persist($trato);
                $em->flush();

                //echo "ManejoData : solicitaEjemplarVideojuego  :: Marca ejemplar usuario : En negociacion ";
                $ejemplarusuario->setinnegociacion(GamesController::inDatoUno);    
                $em->persist($ejemplarusuario);

                $actividadusuario->setactusuariotrato($trato);
                $actividadusuario->setactusuarioejemplar($ejemplar);
                $actividadusuario->setactusuariousuarioescribe($usuario);
                $actividadusuario->setactusuariousuariolee($ejemplarusuario->getejemplarusuariousuario());
                $actividadusuario->setactusuarioleido(GamesController::inDatoCer);
                $actividadusuario->setactusuariofecha($fecha);
                $actividadusuario->setactusuariomensaje("El usuario ".$usuario->getTxnickname().", solicita tu juego ".$ejemplar->getejemplarVideojuego()->gettxnomvideojuego());

                $actividadusuario->setactusuariotipoaccion(GamesController::inActSolicitar);
                $em->persist($actividadusuario);

                //AQUI SE DEBE ENVIAR EMAIL DE NOTIFICACION PARA AMBOS
                error_reporting(E_ERROR);
                $transport = (new \Swift_SmtpTransport('p3plcpnl0478.prod.phx3.secureserver.net', 25))
                    ->setUsername('ex4play@baisica.co')
                    ->setPassword('eX.fouR.pl4y$');
                $mailer = new \Swift_Mailer($transport);
                EnviaMailController::enviaMailRegistroAction($usuario, $mailer, GamesController::inDatoUno);
                //echo "\n registroUsuario :: Envió mail";

                
                //
                //
                //Registro de puntos e Resta para el solicitante
                $puntosusuario->setpunusuarioidtrato($trato);
                $puntosusuario->setincontar(GamesController::inContar);
                $puntosusuario->setfefechapuntos($fecha);
                $puntosusuario->setinpuntaje(ManejoDataRepository::getPuntajeBarts($ejemplar->getejemplarVideojuego()->getincategvideojuego()));
                $puntosusuario->setinsumaresta(GamesController::inResta);
                $puntosusuario->setpuntosusuariousuario($usuario);
                $puntosusuario->setpunusuarioejemplar($ejemplar);
                $em->persist($puntosusuario);
                $em-x>flush();

                //$em->getConnection()->commit();
                $respuestaProc =  GamesController::inExitoso; 

                $em->getConnection()->commit();
            }
            return $respuestaProc;

        } catch (Exception $ex) {
                return  GamesController::inFallido;
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
            if ($psolicitud->getIdEjemusuario() == ""){
                //echo "ManejoDataRepository :: generarPublicacionEjemplar :: ID Ejemplarusuario es vacio: Entra a crear la publicación \n";
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
                $em->flush();

                //El objeto respuesta 
                $respuesta->setIdEjemplar($ejemplar->getidejemplar());
                $respuesta->setIdEjemusuario($ejeUsuario->getidejemplarusuario());
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
            
            } else {
                //echo "ManejoDataRepository :: generarPublicacionEjemplar :: ID Ejemplar usuario NO es vacio: Entra a editar la publicación \n";
                $ejemplarusuario = ManejoDataRepository::getEjemplarusuario($psolicitud->getIdEjemusuario(), $em);
                //echo "ManejoDataRepository :: generarPublicacionEjemplar :: Ejemplar bloqueado [".$ejemplarusuario->getinbloqueado()."] o en negociacion [".$ejemplarusuario->getinnegociacion()."] o no está vigente [".$ejemplarusuario->getinvigente()."] o no está publicado [".$ejemplarusuario->getinpublicado()."] \n";
                if (($ejemplarusuario->getinbloqueado()==GamesController::inDatoUno)or($ejemplarusuario->getinnegociacion()==GamesController::inDatoUno)or($ejemplarusuario->getinvigente()==GamesController::inDatoCer)or($ejemplarusuario->getinpublicado()==GamesController::inDatoUno)) {
                    //echo "ManejoDataRepository :: generarPublicacionEjemplar :: El ejemplar no puede ser publicado (en negociacion, publicado, no vidente o bloqueado \n";
                    $ejemplar = ManejoDataRepository::getEjemplarById($ejemplarusuario->getejemplarusuarioejemplar(), $em);
                    $videojuego = ManejoDataRepository::getVideojuego($ejemplar->getejemplarVideojuego(), $em);
                    $respuesta->setIdEjemusuario($ejemplarusuario->getidejemplarusuario());
                    $respuesta->setIdEjemplar($ejemplar->getidejemplar());
                    $respuesta->setTitulo($videojuego->gettxnomvideojuego());
                    $respuesta->setIdvidjuego($videojuego->getidvideojuego());
                    $em->flush();
                    $em->getConnection()->rollback();
                    $respuestaProc = GamesController::inEjemInv; 
                    return $respuestaProc;
                } else {
                    //echo "ManejoDataRepository :: generarPublicacionEjemplar :: ID Ejemplar usuario válido \n";
                    $ejemplarusuario->setinpublicado(GamesController::inDatoUno); //Publica el ejemplar
                    $em->persist($ejemplarusuario);
                    $ejemplar = ManejoDataRepository::getEjemplarById($ejemplarusuario->getejemplarusuarioejemplar(), $em);
                    $ejemplar->setinejemplarpublicado(GamesController::inDatoUno); //publica el ejemplar
                    $em->persist($ejemplar);
                    $videojuego = ManejoDataRepository::getVideojuego($ejemplar->getejemplarVideojuego(), $em);
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
                    $em->flush();

                    //El objeto respuesta 
                    $respuesta->setIdEjemusuario($ejemplarusuario->getidejemplarusuario());
                    $respuesta->setIdEjemplar($ejemplar->getidejemplar());
                    $respuesta->setTitulo($videojuego->gettxnomvideojuego());
                    $respuesta->setIdvidjuego($videojuego->getidvideojuego());
                }    
            }
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
            //Si existe el ejemplarusuario, en la base de datos, se recupera por el ID
            if (($psolicitud->getIdEjemusuario() != "")){
                //echo "ManejoDataRepository :: generarDESPublicacionEjemplar :: ID Videojuegono NO es vacio: Entra a recuperarlo \n";
                $vjuegoExiste = GamesController::inExitoso;
                //En el json de entrada, el ejemplar = ejemplarusuario
                $ejemplarusuario = ManejoDataRepository::getEjemplarusuario($psolicitud->getIdEjemusuario(), $em);
                if ($ejemplarusuario == NULL) {
                    //echo "ManejoDataRepository :: generarDESPublicacionEjemplar :: ID ejemplarusuario inválido \n";
                    $em->flush();
                    $em->getConnection()->rollback();
                    $respuestaProc = GamesController::inEjemInv; 
                    return $respuestaProc;
                } else {
                    //echo "ManejoDataRepository :: generarDESPublicacionEjemplar :: valida si el ejemplar está bloqueado o en negociacion no se puede desbloquear \n";
                    $ejemplar = ManejoDataRepository::getEjemplarById($ejemplarusuario->getejemplarusuarioejemplar(), $em);
                    if ($ejemplar == NULL) {
                        //echo "ManejoDataRepository :: generarDESPublicacionEjemplar :: ID ejemplar inválido : No existe \n";
                        $em->flush();
                        $em->getConnection()->rollback();
                        $respuestaProc = GamesController::inEjemInv; 
                        return $respuestaProc;
                    } else {
                        //echo "ManejoDataRepository :: generarDESPublicacionEjemplar :: ID ejemplarusuario valido : aquí debe despublicarlo \n";
                        //echo "ManejoDataRepository :: generarDESPublicacionEjemplar :: si no esta bloquaedo o en negociacion lo des-publica \n";
                        if (($ejemplarusuario->getinbloqueado()==GamesController::inDatoUno)or($ejemplarusuario->getinnegociacion()==GamesController::inDatoUno)or($ejemplarusuario->getinvigente()==GamesController::inDatoCer)or($ejemplarusuario->getinpublicado()==GamesController::inDatoCer)) {
                            //echo "ManejoDataRepository :: generarDESPublicacionEjemplar :: Ejemplar bloqueado [".$ejemplarusuario->getinbloqueado()."] o en negociacion [".$ejemplarusuario->getinnegociacion()."] o no está vigente [".$ejemplarusuario->getinvigente()."] o no está publicado [".$ejemplarusuario->getinpublicado()."] \n";
                            $em->flush();
                            $em->getConnection()->rollback();
                            //El objeto respuesta 
                            $ejemplar = ManejoDataRepository::getEjemplarById($ejemplarusuario->getejemplarusuarioejemplar(), $em);
                            $videojuego = ManejoDataRepository::getVideojuego($ejemplar->getejemplarVideojuego(), $em);
                            $respuesta->setIdEjemusuario($ejemplarusuario->getidejemplarusuario());
                            $respuesta->setIdEjemplar($ejemplar->getidejemplar());
                            $respuesta->setTitulo($videojuego->gettxnomvideojuego());
                            $respuesta->setIdvidjuego($videojuego->getidvideojuego());

                            $respuestaProc = GamesController::inEjemInv; 
                            return $respuestaProc;
                        } else {
                            //echo "ManejoDataRepository :: generarDESPublicacionEjemplar :: Marca ejemplar y ejemplar usuario como no publicado \n";
                            $ejemplarusuario->setinpublicado(GamesController::inDatoCer); //Despublica el ejemplar
                            $em->persist($ejemplarusuario);
                            $ejemplar->setinejemplarpublicado(GamesController::inDatoCer); //Despublica el ejemplar
                            $em->persist($ejemplar);
                            //echo "ManejoDataRepository :: generarDESPublicacionEjemplar :: Registra los puntos para el usuario \n";
                            $videojuego = ManejoDataRepository::getVideojuego($ejemplar->getejemplarVideojuego(), $em);
                            $punUsuario = new Puntosusuario();
                            $punUsuario->setfefechapuntos($fecha);
                            //$inpuntaje = ManejoDataRepository::getPuntajeBarts(GamesController::inDatoTre);
                            $inpuntaje = ManejoDataRepository::getPuntajeBarts($videojuego->getincategvideojuego());
                            //echo "ManejoDataRepository :: generarPublicacionEjemplar :: Puntaje [".$inpuntaje."] \n";
                            $punUsuario->setinpuntaje($inpuntaje);
                            $punUsuario->setinsumaresta(GamesController::inResta);
                            $punUsuario->setpuntosusuariousuario($usuario);
                            //$punUsuario->setpunusuarioResenavideojuego($punusuarioResenavideojuego);
                            //$punUsuario->setpunusuarioactiusuario();
                            $punUsuario->setpunusuarioejemplar($ejemplar);
                            $em->persist($punUsuario);
                            $respuestaProc = GamesController::inExitoso; 
                            $em->flush();
                            //El objeto respuesta 
                            $respuesta->setIdEjemusuario($ejemplarusuario->getidejemplarusuario());
                            $respuesta->setIdEjemplar($ejemplar->getidejemplar());
                            $respuesta->setTitulo($videojuego->gettxnomvideojuego());
                            $respuesta->setIdvidjuego($videojuego->getidvideojuego());

                            $em->getConnection()->commit();
                            return $respuestaProc;
                        }
                    }
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

 
//enviar mensaje
public function enviarMensaje(Usuario $usuario, Usuario $usuariodes, Trato $trato, Ejemplar $ejemplar, $mensaje, $em)
    {
        $respuestaProc =  GamesController::inFallido; 
        try{
            setlocale (LC_TIME, "es_CO");
            $fecha = new \DateTime;
            //$em->getConnection()->beginTransaction();
            
            $actividadusuario = new Actividadusuario();

            $actividadusuario->setactusuariotrato($trato);
            $actividadusuario->setactusuarioejemplar($ejemplar);
            $actividadusuario->setactusuariousuarioescribe($usuario);
            $actividadusuario->setactusuariousuariolee($usuariodes);
            $actividadusuario->setactusuarioleido(GamesController::inDatoCer);
            $actividadusuario->setactusuariofecha($fecha);
            $actividadusuario->setactusuariomensaje(utf8_decode($mensaje));

            $actividadusuario->setactusuariotipoaccion(GamesController::inActEscribir);
            $em->persist($actividadusuario);
            $em->flush();
            
            if ($trato->gettratousrdueno()==$usuario) {
                $trato->setintratoacciondueno(GamesController::inDueConvers);
            } else {
                $trato->setintratoaccionsolicitante(GamesController::inSolConvers);
            }
            $em->persist($trato);
            //echo "\n Guardo mensaje \n ".$mensaje;
            $respuestaProc =  GamesController::inExitoso; 

            //$em->getConnection()->commit();
            //echo "\n Commit mensaje \n ".$mensaje;

            return $respuestaProc;

        } catch (Exception $ex) {
                return  GamesController::inFallido;
        } 
    }    
    
    public static function actualizarTrato($trato, $acciongener, $acciondueno, $accionsolic,  $em) {
        try {
           $traUpd = ManejoDataRepository::getTratoById($trato->getidtrato(), $em);
           if ($acciongener != GamesController::inAccMsgNormal){
               $traUpd->setinestadotrato($acciongener);
           }
           if ($acciondueno != GamesController::inAccMsgNormal){
               $traUpd->setintratoacciondueno($acciondueno);
           }
           if ($accionsolic != GamesController::inAccMsgNormal){
               $traUpd->setintratoaccionsolicitante($accionsolic);
           }
           $em->persist($traUpd);
           $em->flush();
        } catch (Exception $ex) {
                return  GamesController::inFallido;
        }
    }
   
}