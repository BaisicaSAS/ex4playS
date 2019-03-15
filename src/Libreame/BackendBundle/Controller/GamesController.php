<?php

namespace Libreame\BackendBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swift_Mailer;
use Libreame\BackendBundle\Helpers\Logica;
use Libreame\BackendBundle\Helpers\Solicitud;
use Libreame\BackendBundle\Helpers\Respuesta;
use Libreame\BackendBundle\Entity\Usuario;

class GamesController extends Controller
{
    //// ************************* LO QUE SE USA **************************////
    //// ************************* LO QUE NO //SE USA **************************////
    //Tipo publicacion
    const inTPLibro =  0; //Tipo publicación : Libro
    const inTPRevista = 1; //Tipo publicación : Revista
    
    //Valor de cada punto en ex4read
    const txEntrada = "ENTRADA"; //Trx de entrada
    const txSalida = "SALIDA"; //Trx de Salida
    
    //Tratos de entrada o de salida (Para el dueño son de salida, para el otro es de entrada)
    const inDueAcepSol = 0; //Solicitud Aceptada: El Dueño Acepta la solicitud
    const inDueRechSol = 1; //Solicitud rechazada: El dueño no acepta la solicitud
    const inDueEntrega = 2; //Videojuego Entregado por el dueño
    const inDueEnQueja = 3; //Queja impuesta
    const inDueCalific = 4; //Calificación realizada por el dueño
    const inDueConvers = 5; //Dueño en Conversación
    const inDueIniTrat = 10;//Trato iniciado
        
    const inSolSolicit = 0; //Ejemplar Solicitado: Cuando se ejecuta la solicitud
    const inSolCancela = 1; //Solicitud Cancelada
    const inSolRecibid = 2; //Videojuego Recibido
    const inSolEnQueja = 3; //Queja impuesta
    const inSolCalific = 4; //Calificación realizada
    const inSolConvers = 5; // Solicitante en Conversación
            
    //Acciones de Actividadusuario 
    const inActSolicitar = 0; 
    const inActEscribir = 1; 
    const inActAceptar = 2; 
    const inActCancelar = 3; 
    const inActRechazar = 4; 
    const inActEntregar = 5; 
    const inActRecibir = 6; 
    const inActCalificar = 7; 
    
    const inContar = 1; 
    const inNoContar = 0; 
    
    //Constantes globales
    const inSuma_ =  1; //Proceso fallido
    const inResta = 2; //Proceso fallido por conexión de plataforma
    const inFallido =  0; //Proceso fallido
    const inDescone = -1; //Proceso fallido por conexión de plataforma
    const inExitoso =  1; //Proceso existoso
    const inDatoCer =  0; //Valor cero: Sirve para los datos Inactivo, Cerrado etc del modelo
    const inDatoUno =  1; //Valor Uno: Sirve para    los datos Activo, Abierto, etc del modelo
    const inDatoDos =  2; //Valor Uno: Sirve para los datos Activo, Abierto, etc del modelo
    const inDatoTre =  3; //Valor Uno: Sirve para los datos Activo, Abierto, etc del modelo
    const inDatoCua =  4; //Valor Uno: Sirve para los datos Activo, Abierto, etc del modelo
    const inDatoCin =  5; //Valor Uno: Sirve para los datos Activo, Abierto, etc del modelo
    const inGenSinE =  0; //Genero del usuario: Sin especificar
    const txGenSinE =  'Sin especificar'; //Genero del usuario: Sin especificar
    const inGenMasc =  1; //Genero del usuario: Masculino
    const txGenMasc =  'Masculino'; //Genero del usuario: Masculino
    const inGenFeme =  2; //Genero del usuario: Femenino
    const txGenFeme =  'Femenino'; //Genero del usuario: Femenino
    const inTamVali =  128; //Tamaño del ID para confirmacion del Registro
    const inTamSesi =  30; //Tamaño del id de sesion generado
    const inJsonInv = -10; //Datos inconsistentes
    const inDatosOb = -11; //Datos obligatorios no relacionados (Cuando no relaciona idejemplar, por ahora)
    const inEjemInv = -12; //Ejemplar relacionado es inválido o su estado es incorrecto
    const txMensaje =  'Solicitud de registro de usuario en Ex4Read'; //Mensaje estandar para el registro de usuario
    const txMenNoId =  'Sin identificar'; //Mensaje estandar para datos sin identificar
    const txMeNoIdS =  'Pendiente'; //Mensaje estandar para pendiente/Sin identificar, con campo Longitud menor a 10
    //Estados del usuario
    const inUsuConf =  0; //Usuario en proceso de confirmacion de registro
    const inUsuActi =  1; //Usuario Activo
    const inUsuCuar =  2; //Usuario en cuarentena
    const inInactiv =  3; //Usuario inactivo
    //Estados de sesion
    const inSesActi =  1; //Usuario en proceso de confiormacion de registro
    const inSesInac =  0; //Usuario Activo
    const txAnyData =  'ANY'; //String para indicar cualquier usuario
    const txSecret = '[fyxwwk-+ieekrz2';
   
    const inEsTrSol = 0;  //Solicitado

    const inEsTrCnD = 1;  //Cancelado dueño
    const inEsTrCnS = 2;  //Cancelado solicitante
    const inEsTrCnA = 3;  //Cancelado por ambos

    const inEsTrAcD = 1 ; //Aceptado por el dueño
    const inEsTrAcS = 2;  //Aceptado por el solicitante
    const inEsTrAcA = 3;  //Aceptado por ambos

    const inEsTrClD = 1;  //calificado por el dueño
    const inEsTrClS = 2;  //Calificado por el solicitante
    const inEsTrClA = 3;  //Calificado por ambos

    
    //Acciones de la plataforma
    const txAccRegistro =  '1'; //Registro en el sistema
    const txAccIngresos =  '2'; //Login  (Ingreso)
    const txAccRecParam =  '3'; //Recuperar datos y parámetros de usuario: incluye calificaciones
    const txAccRecFeeds =  '4'; //Recuperar Feed (Todas las publicaciones de solicitudes y publicaciones de usuarios)...Lleva una marca de Fecha y hora para recuperar los últimos tipo twitter
    const txAccRecOpera =  '5'; //Recuperar mis mensajes ...Lleva una marca de Fecha y hora para recuperar los últimos tipo twitter
    const txAccConfRegi =  '6'; //Confirmacion Registro en el sistema        
    const txAccBusEjemp =  '7'; //Buscar Ejemplares        
    const txAccRecOfert =  '8'; //Recuperar oferta
    const txAccRecUsuar =  '9'; //Ver/Recuperar usuario: Incluye su calificacion
    const txAccCerraSes =  '10'; //Logout / Cerrar sesion

    const txAccBajaSist =  '11'; //Dar de baja
    const txAccActParam =  '12'; //Actualizar parámetros sistema y datos usuario
    const txAccPubliEje =  '13'; //Publicar un ejemplar
    //DEPRECADO: const txAccModifEje =  '14'; //Modificar un ejemplar
    const txAccElimiPub =  '15'; //Eliminar una publicacion :: Despublicar
    const txAccVisuaBib =  '16'; //Visualizar Biblioteca
    const txAccModifOfe =  '17'; //Modificar una oferta
    const txAccElimiOfe =  '18'; //Eliminar una oferta
    const txAccPubMensa =  '19'; //CHATEAR :: Interactuar con oferta::Enviar un mensaje a una solicitud especifica / Publicar o Responder
    const txAccAceNegoc =  '20'; //Aceptar una negociación: Aceptar un usuario 
    const txAccEntRecVJ =  '21'; //Entrega o recibe videojuego (Debe tener un indicador de entrega / recibo en el json)
    const txAccCaliTrat =  '22'; //Calificar un trato
    //DEPRECADO: const txAccModCalTr =  '23'; //Modificar calificación trato
    const txAccEnviaPQR =  '24'; //Enviar una PQR
    const txAccModifPQR =  '25'; //Modificar una PQR
    const txAccElimiPQR =  '26'; //Eliminar una PQR
    
    const txAccSCISBNdb =  '27'; //Servicio de carga de libros desde ISBNdb
    const txAccRecLista =  '28'; //recuperar listas del sistema
    const txAccRecClave =  '29'; //Recuperar clave perdida
    const txAccSolLibro =  '30'; //Solicitar Libro:: Automático
    
    //const txAccLisTrato =  '31'; //Listar todos los tratos de un usuario
    const txAccReaOfert =  '32'; //Realizar oferta
    const txAccRecPubli =  '33'; //Recuperar publicacion
    const txAccRecTrato =  '34'; //Recuperar informacion Trato
    const txAccVerCalif =  '35'; //Ver comentarios-calificaciones
    const txAccMarcMens =  '36'; //Marcar mensaje como No leido / Leido
    const txAccListaIdi =  '37'; //Listar idiomas
    const txAccListaLug =  '38'; //Listar lugares

    //Nuevos con puntos
    const txAccMegEjemp =  '40'; //Marcar un ejemplar como megusta
    const txAccVerUsMeg =  '41'; //Ver usuarios a los que les gusta
    const txAccCommEjem =  '42'; //Realizar-editar-borrar Cometario ejemplar
    const txAccVerComEj =  '43'; //Ver comentarios ejemplar 
    const txAccVerTrato =  '44'; //Ver hilo de un trato
    
    const txAccListaEdi =  '50'; //Listar editoriales
    const txAccListaAut =  '51'; //Listar autores
    
    
    const txEjemplarPub =  'P'; //Indica que es el ejemplar a publicar de la solicitud
    const txEjemplarSol1 =  'S1'; //Indica que es el ejemplar a Solicitar de la solicitud
    const txEjemplarSol2 =  'S2'; //Indica que es el ejemplar a Solicitar de la solicitud

    //Constantes de la funcion Login
    const inUsClInv =  0;  //Usuario o clave inválidos
    const inULogged =  1;  //Usuario logeado exitosamente
    const inPlatCai = -1; //Proceso fallido por conexión de plataforma
    const inUSeActi = -2; //Usuario tiene sesion activa
    const inSosAtaq = -3; //Sesion sospechosa de ser ataque ::: AUN NO SE IMPLEMENTA
    const inUsInact = -4; //Usuario inactivo
    const inUsSeIna = -5; //Sesión inactiva
    //Constante funcion marcar mensaje
    const inMenNoEx = -6; //Mensaje no existe
    const inMenNoAc = -7; //Mensaje no activo - inactivo
    const inErrImag = -8; //Error en la carga de imágen
    const inUsClAcI = -9; //Clave actual inválida
    //Constante funcion Cancelar trato
    const inTraCance = -20; //El trato ya está cancelado: no se puede cancelar de nuevo
    const inTraFinal = -21; //El trato ya está finalizado: No se puede cancelar

    const inIdGeneral = 1; //Id General para datos basicos :: Genero, Lugar, Grupo
    //
    //Constantes para origen de mensajes:::
    /*1: Publicacion del ejemplar 2: Bloqueo del ejemplar (Lo hace el sistema, el usr que queda es el que debe), 
    * 3: Solicita ejemplar, 4: Entrega ejemplar: Puntos, 5: Recibe ejemplar: Puntos, 6: Activa - Ofrece, 7: Inactiva, 
    * 8: Comenta, 9: Me gusta, 10: No me gusta, 11: Cambia estado (mejora o empeora de 1 a 10), 
    * 12: Mejora contenido: Idioma, ISBN, Autor etc., 13: Baja del sistema, 
    * 14: Vista del ejemplar (Consulta del detalle), 15: Vendio ejemplar (trato cerrado), 
    * 16: Compro ejemplar(trato cerrado), 17: Acepta solicitud de ejemplar */
    const inMovPubEjem = 1;//1: Publicacion del ejemplar 
    const txMovPubEjem = "Ejemplar publicado";//Mensaje de publicación de ejemplar
    const inMovBlqEjSi = 2;//Mensaje de Bloqueo de ejemplar por el sistema
    const txMovBlqEjSi = "Ejemplar bloqueado";//Mensaje de Bloqueo de ejemplar por el sistema
    const inMovSoliEje = 3;//3: Solicita ejemplar
    const txMovSoliEje = "Ejemplar solicitado";//3: Solicita ejemplar
    const inMovEntrEje = 4;//4: Entrega ejemplar: Puntos
    const txMovEntrEje = "Ejemplar entregado : Cambio/venta";//4: Entrega ejemplar: Puntos
    const inMovReciEje = 5;//5: Recibe ejemplar: Puntos
    const txMovReciEje = "Ejemplar recibido : Cambio/venta";//5: Recibe ejemplar: Puntos
    const inMovActiEje = 6;//6: Activa - Ofrece ejemplar ?? Debe ser el 1 creo: Revisar bien
    const txMovActiEje = "Ejemplar publicado";//6: Activa - Ofrece ejemplar ?? Debe ser el 1 creo: Revisar bien
    const inMovInacEje = 7;//7: Inactiva ejemplar
    const txMovInacEje = "Ejemplar Inactivo / No publicado";//7: Inactiva ejemplar
    const inMovComeEje = 8;//8: Comenta
    const txMovComeEje = "Comentario realizado sobre ejemplar";//8: Comenta
    const inMovMeguEje = 9;//9: Me gusta
    const txMovMeguEje = "Me gusta sobre ejemplar";//9: Me gusta
    const inMovNMegEje = 10;//10: No me gusta
    const txMovNMegEje = "No me gusta sobre ejemplar";//10: No me gusta
    const inMovCamEEje = 11;//11: Cambia estado (mejora o empeora de 1 a 10)
    const txMovCamEEje = "Cambio de estado del Ejemplar";//11: Cambia estado (mejora o empeora de 1 a 10)
    const inMovContEje = 12;//12: Mejora contenido: Idioma, ISBN, Autor etc.
    const txMovContEje = "Mejora de contenido sobre libro";//12: Mejora contenido: Idioma, ISBN, Autor etc.
    const inMovBajaEje = 13;//13: Baja del sistema ejemplar
    const txMovBajaEje = "Sistema dado de baja en ex4read";//13: Baja del sistema ejemplar
    const inMovConsEje = 14;//14: Vista del ejemplar (Consulta del detalle)
    const txMovConsEje = "Consulta del detalle del Ejemplar";//14: Vista del ejemplar (Consulta del detalle)
    const inMovVendEje = 15;//15: Vendio ejemplar (trato cerrado)
    const txMovVendEje = "Ejemplar vendido";//15: Vendio ejemplar (trato cerrado)
    const inMovCompEje = 16;//16: Compro ejemplar(trato cerrado)
    const txMovCompEje = "Ejemplar comprado";//16: Compro ejemplar(trato cerrado)
    const inMovAcepEje = 17;//17: Acepta solicitud de ejemplar
    const txMovAcepEje = "Solicitud de ejemplar aceptada";//17: Acepta solicitud de ejemplar
    const inMovRechEje = 18;//18: Solicitud de ejemplar rechazada
    const txMovRechEje = "Solicitud de ejemplar rechazada";//18: Solicitud de ejemplar rechazada
    const inMovEjeDevu = 19;//19: Ejemplar devuelto por Calidad
    const txMovEjeDevu = "Ejemplar devuelto por Calidad";///19: Ejemplar devuelto por Calidad
    const inMovUsPCali = 20;//20: Usuario propietario Calificó
    const txMovUsPCali = "Usuario propietario Calificó";//20: Usuario propietario Calificó
    const inMovUsSCali = 21;//21: Usuario solicitante Calificó
    const txMovUsSCali = "Usuario solicitante Calificó";//21: Usuario solicitante Calificó
    //Modos de entrega
    const inModEntDomi = 0;//0: Modo entrega en el Domicilio
    const txModEntDomi = "Entrega en el domicilio";//0: Ofrece entregar el ejemplar en el domicilio
    
    const inAccPublica = 1;//1 Accion de pubblicar ejemplar
    const inAccDespubl = 2;//2 Accion de DES-pubblicar ejemplar
    // NO EXISTIRA LA EDICION const inAccModific = 3;//3 Accion de modificar ejemplar
    const inAccElimina = 3;//3 ACCION DE ELIMINAR EJEMPLAR : Lo que hace es esconderlo del todo
    
    //Tipo de tarea
    const inTipTarCali = 0;//0 - Calificar un usuario
    const inTipTarApru = 1;//1 - Aprobar valor ejemplar
    //Estado de la tarea 
    const inEstTarPend = 0;//0-Pendiente
    const inEstTarReal = 1;//1-Realizada
    const inEstTarExpi = 2;//2-Expirada
    
    //Direcciones para almacenamiento de imágenes
    const txCarpetaImgEjem = "/home/baisicasas/public_html/www.ex4read.co/exservices/web/img/p/";
    const txCarpWEMImgEjem = "http://ex4read.co/exservices/web/img/p/";
    const txIndCarpImgEjem = "E";

    const txCarpetaImgUsua = "/home/baisicasas/public_html/www.ex4read.co/exservices/web/img/u/";
    const txCarpWEMImgUsua = "http://ex4read.co/exservices/web/img/u/";
    const txIndCarpImgUsua = "U";

    //Constantes para Estado de negociación ejemplar
    const inConEjeNoNe = 0;//0: Ejemplar no está en negociacion
    const txConEjeNoNe = "Ejemplar no está en negociacion";//0: Ejemplar no está en negociacion
    const inConEjeSoli = 1;//1: Ejemplar solicitado
    const txConEjeSoli = "Ejemplar solicitado";//1: Ejemplar solicitado
    const inConEjePrAp = 2;//2: En proceso de aprobación del negocio
    const txConEjePrAp = "En proceso de aprobación del negocio";//2: En proceso de aprobación del negocio
    const inConEjeApNe = 3;//3: Aprobado negocio por ambas partes
    const txConEjeApNe = "Aprobado negocio por ambas partes";//3: Aprobado negocio por ambas partes
    const inConEjePrEn = 4;//4: En proceso de entrega
    const txConEjePrEn = "En proceso de entrega";//4: En proceso de entrega
    const inConEjeEntr = 5;//5: Entregado
    const txConEjeEntr = "Entregado";//5: Entregado
    const inConEjeReci = 6;//6: Recibido
    const txConEjeReci = "Recibido";//6: Recibido
    
    //Constantes: Mensajes Discriminados según el usuario que lo lee
    const txMsgRechazoTr = " :( Otra vez será. %usuario no aceptó el trato";
    const txMsgAceptaTr = " :) Que bien !!! El trato fue aceptado por %usuario";
    const txMsgHaceOferta = "  %usuario, ofrece %cantidad %unidadvalor ";
    const txMsgContraoferta = "  %usuario, contraoferta: %cantidad %unidadvalor ";
    const txMsgEntregaEjem = "  %usuario, reporta entrega del ejemplar! ";
    const txMsgRecibeEjem = "  %usuario, reporta recibo del ejemplar! ";
    const txMsgCalificacion = "  %usuario, realizó calificación del trato! "; //Sirve para ambos
    const txMsgFinalizacion = "  El trato finalizó satisfactoriamente "; //Sirve para ambos
    
    /****** ACCIONES DE LA NEGOCIACION  ****** 
    **[-1]**	S, D	Mensaje de texto normal (El que existe actualente para enviar un mensaje en el chat)*
    **[0]**	S, D	Cancelar el trato actual
    **[1]**	S, D	Aceptar el trato
    **[2]**	S	Ofertar un valor por un ejemplar
    **[3]**	D	Contraofertar una oferta realizada por el Solicitante
    **[4]**	D	Entregar el ejemplar (Se informa a la plataforma que físicamente se entrego)
    **[5]**	S	Recibir el ejemplar (Se informa a la plataforma que físicamente se recibió)
    **[6]**	S	Calificación (El solicitante califica)
    **[7]**	D	Calificación (El dueño califica)
    **[10]**	S, D	TRATO FINALIZADO */
    const inAccMsgNormal = -1;
    const inAccMsgCancel = 0;
    const inAccMsgAcepta = 1;
    const inAccMsgOferta = 2;
    const inAccMsgContra = 3;
    const inAccMsgEntreg = 4;
    const inAccMsgRecibe = 5;
    const inAccMsgSCalif = 6;
    const inAccMsgDCalif = 7;
    const inAccMsgFinali = 10;

    var $objSolicitud;
    /*
     * IngresarSistema es la UNICA funcion que recibe la información desde el cliente, para revisar y despachar
     * Recibe un JSON, con la estructura definida como default mas los datos especificos de cada opcion.
     * 
     * @TODO: Es la mas importante para la integración con otros sistemas:: 
     * Cualquier aplicación -por lo pronto las nuestras- solo acceden por esta funcion, el resto de 
     * funciones del sistema son privadas

     * La funcion recibe los datos de la interacción con el cliente.
     * Su funcion es obtener la información de Usuario, Session y Opciones, validar que sean correctos o que 
     * no estén repetidos. Registra el intento de acceso, valida los datos adicionales con respecto
     * a la accion solicitada y en caso de estar todo en orden enviar la información a la Clase Logica para 
     * que realice los solicitado y emita las respuestas al cliente
     * Tambien es la responsable de generar todas las bitacoras de la aplicación
     */
    public function servicioAction(Request $request)
    {
        //error_reporting(E_ALL);
        //error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED & ~E_NOTICE );
        $em = $this->getDoctrine()->getManager();
        //echo "servicioAction : IngresarSistema \n";
        //$request = $this->getRequest();
        $content = $request->getContent();
        $datos = json_decode($content, true);
        $em = $this->getDoctrine()->getManager();
        
        $respuesta = 0;
        /*setlocale (LC_TIME, "es_CO");
        $fecha = new \DateTime;
        $texto = $fecha->format('YmdHis');*/
        //Aquí iniciaría el código en producción, el bloque anterior solo funciona para TEST
        //Se evalúa si se logró obtener la información de sesion desde el JSON
        $jsonValido = $this->descomponerJson($datos);
        try {
            //echo "servicioAction : json válido : ".$jsonValido." \n"; 
            if ($jsonValido != self::inJsonInv) {
                //echo "servicioAction : Json es válido...Inicia \n"; 
                $objLogica = $this->container->get('logica_service');
                $respuesta = $objLogica->ejecutaAccion($this->objSolicitud, $em);
                //$respuesta = Logica::ejecutaAccion($this->objSolicitud, $em);
                //echo "servicioAction : Json es válido ".$respuesta."\n"; 
                
            } else { //JSON INVALIDO RESPUESTA GENERAL : -10
                
                $jrespuesta = new Respuesta();
                $jrespuesta->setRespuesta($jsonValido);    
                $objLogica = $this->container->get('logica_service');
                $respuesta = json_encode($objLogica->respuestaGenerica($jrespuesta, $this->objSolicitud));
                //$respuesta = json_encode(Logica::respuestaGenerica($jrespuesta, $this->objSolicitud));
                //echo "servicioAction : Json es inválido \n"; 
                //@TODO: Debemos revisar que hacer cuando se detecta actividad sospechosa: Cierro sesion?. Bloqueo usuario e informo?
            }
            
            return new RESPONSE($respuesta);
                    
        } catch (Exception $ex) {
            return new RESPONSE($jsonValido);
        }    
    }
    
    /*
     * Descomponer: 
     * Funcion que extrae la informacion del JSON de ingresar
     * 1. Opción Solicitada
     * 2. Usuario
     * 3. Sesión
     * 4. IP
     * 5. Id del dispositivo: MAC
     * 6. Marca del dispositivo
     * 7. Modelo del dispositivo
     * 8. Sistema operativo del dispositivo 
     * {"idsesion":{["idaccion": "accion", "usuario": "usuario", "idtrx": "sesion", "ipaddr": "IP Address", 
     *              "iddevice": "MAC Dispositivo", "marca": "Marca Dispositivo", "modelo": "Modelo Dispositivo", 
     *              "so": "Sistema operativo Dispositivo"]},
     * 
     *  "idsolicitud":{[]}
     * }
     */
    private function descomponerJson($datos)
    {   
        try {
            //$json_datos = json_decode($datos, true);
            $json_datos = $datos;
            //echo "<script>alert('Inicia a decodificar-----".$json_datos['idsesion']['idtrx']."')</script>"; 
            $this->objSolicitud = new Solicitud();
            //echo "<script>alert('VALIDARA')</script>";
            $estrValida = $this->estructuraCorrecta($datos);
            //echo "<script>alert('VALIDADO COMO: ".$estrValida ? 'true' : 'false'."')</script>";

            if ($estrValida)
            {    
                //echo "<script>alert(':::TRANS: ".$json_datos['idsesion']['idtrx']."')</script>"; 
                //echo "<script>alert(':::TRANS: ')</script>"; 
                $resp = self::inExitoso;
                //$json_datos['idsolicitud']['email'] = strtolower($json_datos['idsolicitud']['email']);
                $this->objSolicitud->setAccion($json_datos['idsesion']['idaccion']);
                $this->objSolicitud->setSession($json_datos['idsesion']['idtrx']);
                $this->objSolicitud->setIPaddr($json_datos['idsesion']['ipaddr']);
                //Según la solicitud descompone el JSON
                $tmpSesion = $this->objSolicitud->getAccion();
                //echo "<script>alert('ult ejemplar ".$json_datos['idsolicitud']['ultejemplar']."')</script>";
                //echo "<script>alert('sesion ".$tmpSesion."')</script>";
                switch ($tmpSesion){
                    case self::txAccRegistro: { //Dato:1: Registro en el sistema
                        //echo "<script>alert('ENTRA POR REGISTRO')</script>";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        $this->objSolicitud->setTelefono($json_datos['idsolicitud']['telefono']);
                        break;
                    }
                    case self::txAccIngresos : { //Dato:2: Login
                        //echo "<script>alert('ENTRA POR LOGIN')</script>";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        break;
                    }
                    case self::txAccRecParam: { //Dato:3 : Recuperar datos de Usuario (Propios)
                        //echo "<script>alert('ENTRA POR OBT PARAM')</script>";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        break;
                    }
                    case self::txAccRecFeeds: { //Dato:4 : Recuperar Feed de ejemplares
                        //echo "<script>alert('ENTRA POR FEED')</script>";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        $this->objSolicitud->setUltEjemplar($json_datos['idsolicitud']['ultejemplar']);
                        break;
                    }
                    case self::txAccRecOpera: { //Dato:5 : RECUPERAR MENSAJES(NOTIFICACIONES)
                        //echo "<script>alert('ENTRA POR RECUPERAR MENSAJES')</script>";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        break;
                    }
                    case self::txAccBusEjemp: { //Dato:7 : Buscar Ejemplar
                        //echo "<script>alert('ENTRA POR BUSCAR EJEMPLAR')</script>";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        $this->objSolicitud->setTextoBuscar($json_datos['idsolicitud']['buscar']);
                        break;
                    }
                    case self::txAccRecOfert: { //Dato:8 : Recuperar Oferta
                        //echo "<script>alert('ENTRA POR RECUPERAR OFERTA')</script>";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                       $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        $this->objSolicitud->setIdOferta($json_datos['idsolicitud']['idoferta']);
                        break;
                    }
                    case self::txAccRecUsuar: { //Dato:9 : Recuperar Usuario Otro
                        //echo "<script>alert('ENTRA POR RECUPERAR USUARIO OTRO')</script>";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        $this->objSolicitud->setIdUsuarioVer($json_datos['idsolicitud']['idusuariover']);
                        break;
                    }
                    case self::txAccCerraSes: { //Dato:10 : Cerrar sesion
                        //echo "<script>alert('ENTRA POR CERRAR SESION')</script>";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        break;
                    }
                    
                    case self::txAccActParam: { //Dato:12 : Actualizar datos parametros usuario
                        //echo "descomponerJson : ENTRA POR ACTUALIZAR DATOS USUARIO \n";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        $this->objSolicitud->setTelefono($json_datos['idsolicitud']['telefono']);
                        $this->objSolicitud->setNomUsuario($json_datos['idsolicitud']['nomusuario']);
                        $this->objSolicitud->setNomMostUsuario($json_datos['idsolicitud']['nommostusuario']);
                        $this->objSolicitud->setUsuGenero($json_datos['idsolicitud']['usugenero']);
                        $this->objSolicitud->setUsuImagen($json_datos['idsolicitud']['usuimagen']);
                        $this->objSolicitud->setUsuFecNac($json_datos['idsolicitud']['usufecnac']);
                        $this->objSolicitud->setUsuLugar($json_datos['idsolicitud']['usulugar']);
                        break;
                    }
                    
                    case self::txAccPubliEje: { //Dato:13 : Publicar un ejemplar
                        //echo "descomponerJson :  ENTRA POR PUBLICAR \n";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        //$this->objSolicitud->setImageneje($json_datos['idsolicitud']['imagen']);
                        $this->objSolicitud->setAccionCom($json_datos['idsolicitud']['accion']);
                        $this->objSolicitud->setIdEjemusuario($json_datos['idsolicitud']['idejemusuario']);
                        $this->objSolicitud->setIdEjemplar($json_datos['idsolicitud']['idejemplar']);
                        $this->objSolicitud->setIdvidjuego($json_datos['idsolicitud']['idvidjuego']);
                        $this->objSolicitud->setTitulo($json_datos['idsolicitud']['titulo']);
                        $this->objSolicitud->setConsola($json_datos['idsolicitud']['consola']);
                        $this->objSolicitud->setFabricante($json_datos['idsolicitud']['fabricante']);
                        $this->objSolicitud->setRepetir($json_datos['idsolicitud']['repetir']);
                        break;
                    }
                    
                    case self::txAccElimiPub: {
                        //echo "descomponerJson :  ENTRA POR ELIMINAR PUBLICACION \n";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        //$this->objSolicitud->setImageneje($json_datos['idsolicitud']['imagen']);
                        $this->objSolicitud->setAccionCom($json_datos['idsolicitud']['accion']);
                        $this->objSolicitud->setIdEjemplar($json_datos['idsolicitud']['idejemplar']);
                        $this->objSolicitud->setIdvidjuego($json_datos['idsolicitud']['idvidjuego']);
                        $this->objSolicitud->setTitulo($json_datos['idsolicitud']['titulo']);
                        $this->objSolicitud->setConsola($json_datos['idsolicitud']['consola']);
                        $this->objSolicitud->setFabricante($json_datos['idsolicitud']['fabricante']);
                        $this->objSolicitud->setRepetir($json_datos['idsolicitud']['repetir']);
                        break;
                    }

                    case self::txAccVisuaBib: { //Dato:16 : Visualizar biblioteca
                        //descomponerJson : VISUALIZAR BIBLIOTECA \n";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        $this->objSolicitud->setFiltro($json_datos['idsolicitud']['filtro']);
                        break;
                    }
                    
                    case self::txAccElimiOfe: { //Dato:18 : Cancelar / Eliminar oferta
                        //echo "<script>alert('ENTRA POR Chatear')</script>";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        $this->objSolicitud->setIdTrato($json_datos['idsolicitud']['idtrato']);
                        break;
                    }
                    
                    case self::txAccPubMensa: { //Dato:19 : Chatear
                        //echo "<script>alert('ENTRA POR Chatear')</script>";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        $this->objSolicitud->setIdTrato($json_datos['idsolicitud']['idtrato']);
                        $this->objSolicitud->setIdusuariodes($json_datos['idsolicitud']['idusrdestino']);
                        $this->objSolicitud->setIdEjemplar($json_datos['idsolicitud']['idejemplar']);
                        $this->objSolicitud->setComentario($json_datos['idsolicitud']['txmensaje']);
                        break;
                    }
                    
                    case self::txAccCaliTrat: { //Dato:22 : Calificar usuario trato
                        //echo "<script>alert('ENTRA POR Chatear')</script>";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        $this->objSolicitud->setIdEjemplar($json_datos['idsolicitud']['inejemplar']);
                        $this->objSolicitud->setRegHisPublicacion($json_datos['idsolicitud']['reghisejemplar']);
                        $this->objSolicitud->setIdusuariodes($json_datos['idsolicitud']['idusuariocalificado']);
                        $this->objSolicitud->setCalificacion($json_datos['idsolicitud']['incalificacion']);
                        $this->objSolicitud->setComentario($json_datos['idsolicitud']['txcomentario']);
                        break;
                    }
                    
                    case self::txAccRecClave: { //Dato:29 : Cambiar clave usuario
                        //echo "<script>alert('ENTRA POR MARCAR MENSAJES')</script>";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['claveactual']);
                        $this->objSolicitud->setClaveNueva($json_datos['idsolicitud']['clavenueva']);
                        break;
                    }
                    
                    case self::txAccReaOfert: { //Dato:32 : Realizar la solicitud de un videojuego
                        //echo "<script>alert('ENTRA POR MARCAR MENSAJES')</script>";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setIdEjemusuario($json_datos['idsolicitud']['idejemusuario']);
                        $this->objSolicitud->setIdEjemplar($json_datos['idsolicitud']['idejemplar']);
                        break;
                    }
                    
                    

                    case self::txAccMarcMens: { //Dato:36 : Marcar mensaje / Leído - No leído
                        //echo "<script>alert('ENTRA POR MARCAR MENSAJES')</script>";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        $this->objSolicitud->setIdmensaje($json_datos['idsolicitud']['idmensaje']);
                        $this->objSolicitud->setMarcarcomo($json_datos['idsolicitud']['marcacomo']);
                        break;
                    }

                    case self::txAccListaIdi: { //Dato:37 : Listar Idiomas
                        //echo "<script>alert('ENTRA POR LISTAR DE IDIOMAS')</script>";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        break;
                    }

                    case self::txAccListaLug: { //Dato:38 : Listar Lugares
                        //echo "<script>alert('ENTRA POR LISTAR DE LUGARES')</script>";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        break;
                    }

                    case self::txAccMegEjemp: { //Dato:40 : Marcar Megusta a ejemplar
                        //echo "<script>alert('ENTRA POR LISTAR DE LUGARES')</script>";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        $this->objSolicitud->setIdEjemplar($json_datos['idsolicitud']['ejemplar']);
                        $this->objSolicitud->setMegusta($json_datos['idsolicitud']['megusta']);
                        break;
                    }
                    
                    case self::txAccVerUsMeg: { //Dato:41 : Ver usuarios a quienes les gusta el ejemplar 
                        //echo "<script>alert('ENTRA POR LISTAR DE LUGARES')</script>";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        $this->objSolicitud->setIdEjemplar($json_datos['idsolicitud']['ejemplar']);
                        break;
                    }
                    
                    case self::txAccCommEjem: { //Dato:42 : Realizar comentario ejemplar
                        //echo "<script>alert('ENTRA POR LISTAR DE LUGARES')</script>";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        $this->objSolicitud->setIdEjemplar($json_datos['idsolicitud']['ejemplar']);
                        $this->objSolicitud->setComentario($json_datos['idsolicitud']['comentario']);
                        $this->objSolicitud->setIdComPadre($json_datos['idsolicitud']['idcompadre']);
                        $this->objSolicitud->setIdComentario($json_datos['idsolicitud']['idcomentario']);
                        $this->objSolicitud->setAccionCom($json_datos['idsolicitud']['accioncom']);
                        break;
                    }
                    
                    case self::txAccVerComEj: { //Dato:43 : Ver comentarios ejemplar
                        //echo "<script>alert('ENTRA POR LISTAR DE LUGARES')</script>";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        $this->objSolicitud->setIdEjemplar($json_datos['idsolicitud']['ejemplar']);
                        break;
                    }
                    
                    case self::txAccVerTrato: { //Dato:44 : Ver detalle de un trato
                        //echo "<script>alert('ENTRA POR LISTAR DE LUGARES')</script>";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        $this->objSolicitud->setIdTrato($json_datos['idsolicitud']['idtrato']);
                        break;
                    }
                    
                    
                    case self::txAccListaEdi: { //Dato:50 : Listar Editoriales
                        //echo "<script>alert('ENTRA POR LISTAR DE EDITORIALES')</script>";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        break;
                    }

                    case self::txAccListaAut: { //Dato:51 : Listar Autores
                        //echo "<script>alert('ENTRA POR LISTAR DE AUTORES')</script>";
                        $this->objSolicitud->setEmail($json_datos['idsolicitud']['email']);
                        $this->objSolicitud->setClave($json_datos['idsolicitud']['clave']);
                        break;
                    }


                }
                //echo "<script>alert('SESION: ".$this->objSolicitud->getSession().": Finalizó')</script>"; 
                $resp = self::inExitoso;
            } else {
                $resp = self::inJsonInv;
            }   
                
            //echo "<script>alert('Decodificó e instació el objeto')</script>"; 
            return $resp;
        } catch (Exception $ex) {
            return self::inJsonInv;
        }    
    }
    
    private function estructuraCorrecta($datos) 
    {   
        $resp = TRUE;
        //Recupera el ID de la accion
        if (!isset($datos['idsesion']['idaccion'])) {
            //echo "<script>alert('FALTA IDACCION')</script>";
            $resp = FALSE;
        } else {
            //Evalúa todos los datos del ENCABEZADO
            $accion = $datos['idsesion']['idaccion'];
            //echo "<script>alert('ACCION: ".$accion."')</script>"; 
            if (!isset($datos['idsesion']['idtrx'])){ 
                //echo "<script>alert('FALTA IDTRANSACCION: Sesion')</script>";
                $resp = FALSE;
            } elseif (!isset($datos['idsesion']['ipaddr'])) {
                //echo "<script>alert('FALTA IPADDRES')</script>";
                $resp = FALSE;
            } else {
                //Si todos los datos del encabezado están seteados, evalúa según la acción
                switch ($accion){
                    case self::txAccRegistro: { //Dato:1 :  Registro en el sistema
                        //echo "<script>alert('VAL ENTRA POR REGISTRO')</script>";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['clave']) 
                                and isset($datos['idsolicitud']['telefono']));
                        //echo $resp.": fue la respuesta - [".$datos['idsolicitud']['clave']."]";
                        break;
                    }
                    case self::txAccIngresos : { //Dato:2 : Login
                        //echo "<script>alert('VAL ENTRA POR LOGIN')</script>";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['clave']));
                        break;
                    }
                    case self::txAccRecParam: { //Dato:3 : Recuperar datos de Usuario (Propios)
                        //echo "<script>alert('VAL ENTRA POR OBT PARAM')</script>";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['clave']));
                        break;
                    }
                    case self::txAccRecFeeds: { //Dato:4 : Recuperar Feed de ejemplares
                        //echo "<script>alert('VAL ENTRA POR FEED')</script>";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['clave']) 
                                and isset($datos['idsolicitud']['ultejemplar']));
                        break;
                    }
                    case self::txAccRecOpera: { //Dato:5 : RECUPERAR MENSAJES(NOTIFICACIONES)
                        //echo "<script>alert('ENTRA POR RECUPERAR MENSAJES')</script>";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['clave']));
                        break;
                    }
                    case self::txAccBusEjemp: { //Dato:7 : Buscar ejemplares
                        //echo "<script>alert('VAL ENTRA POR BUSCAR')</script>";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['clave']) 
                                and isset($datos['idsolicitud']['buscar']));
                        break;
                    }
                    case self::txAccRecOfert: { //Dato:8 : Recuperar Oferta
                        //echo "<script>alert('ENTRA POR RECUPERAR OFERTA')</script>";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['clave'])
                                 and isset($datos['idsolicitud']['idoferta']));
                        break;
                    }
                    case self::txAccRecUsuar: { //Dato:9 : Recuperar Usuario Otro
                        //echo "<script>alert('VAL ENTRA POR RECUPERAR USUARIO OTRO')</script>";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['clave'])
                                 and isset($datos['idsolicitud']['idusuariover']));
                        break;
                    }
                    case self::txAccCerraSes: { //Dato:10 : Cerrar Sesion
                        //echo "<script>alert('VAL ENTRA POR CERRAR SESION')</script>";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['clave']));
                        break;
                    }
                    case self::txAccActParam: { //Dato:12 : Actualizar datos parametros usuario
                        //echo "estructuraCorrecta : ENTRA POR ACTUALIZAR DATOS USUARIO \n";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['clave']) and 
                                isset($datos['idsolicitud']['telefono']) and 
                                isset($datos['idsolicitud']['nomusuario']) and  isset($datos['idsolicitud']['nommostusuario']) and 
                                isset($datos['idsolicitud']['usugenero']) and  isset($datos['idsolicitud']['usuimagen']) and 
                                isset($datos['idsolicitud']['usufecnac']) and  isset($datos['idsolicitud']['usulugar']));
                        break;
                    }
                    
                    case self::txAccPubliEje: { //Dato:13 : Publicar un Ejemplar
                        //echo "estructuraCorrecta :: VAL ENTRA POR PUBLICAR \n";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['clave']) and 
                                 /*isset($datos['idsolicitud']['imagen']) and  */isset($datos['idsolicitud']['accion']) and 
                                 isset($datos['idsolicitud']['idejemplar']) and isset($datos['idsolicitud']['idejemusuario']) and
                                 isset($datos['idsolicitud']['idvidjuego']) and  isset($datos['idsolicitud']['titulo']) and 
                                 isset($datos['idsolicitud']['consola']) and isset($datos['idsolicitud']['fabricante']) and 
                                 isset($datos['idsolicitud']['repetir']) );
                        break;
                    }
                    
                    case self::txAccVisuaBib: { //Dato:16 : Visualizar biblioteca
                        //echo "estructuraCorrecta :: VAL ENTRA VISUALIZAR BIBLIOTECA \n";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['clave'])
                                and isset($datos['idsolicitud']['filtro']));
                        break;
                    }

                    case self::txAccElimiOfe: { //Dato:18 : Cancelar / Eliminar oferta
                        //echo "<script>alert('VAL ENTRA CHATEAR')</script>";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['clave'])
                                and isset($datos['idsolicitud']['idtrato']));
                        break;
                    }
                    
                    case self::txAccPubMensa: { //Dato:19 : Chatear
                        //echo "<script>alert('VAL ENTRA CHATEAR')</script>";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['clave'])
                                and isset($datos['idsolicitud']['idtrato']) and isset($datos['idsolicitud']['idejemplar']) 
                                and isset($datos['idsolicitud']['txmensaje']) and isset($datos['idsolicitud']['idusrdestino']));
                        break;
                    }
                    
                    case self::txAccCaliTrat: { //Dato:22 : Califica usuario trato
                        //echo "<script>alert('VAL ENTRA CHATEAR')</script>";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['clave'])
                                and isset($datos['idsolicitud']['inejemplar']) and isset($datos['idsolicitud']['reghisejemplar'])
                                and isset($datos['idsolicitud']['idusuariocalificado']) and isset($datos['idsolicitud']['incalificacion'])
                                and isset($datos['idsolicitud']['txcomentario']));
                        break;
                    }
                    
                    case self::txAccRecClave: { //Dato:29 : Cambiar clave usuario
                        //echo "<script>alert('VAL ENTRA POR CAMBIAR CLAVE')</script>";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['claveactual']) and
                                isset($datos['idsolicitud']['clavenueva']));
                        break;
                    }

                    case self::txAccReaOfert: { //Dato:32 : Solicitar un videojuego
                        //echo "<script>alert('VAL ENTRA POR CAMBIAR CLAVE')</script>";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['idejemusuario']) and
                                isset($datos['idsolicitud']['idejemplar']));
                        break;
                    }

                    case self::txAccMarcMens: { //Dato:36 : Marcar mensaje / Leído - No leído
                        //echo "<script>alert('VAL ENTRA POR MARCAR MENSAJES')</script>";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['clave']) and
                                isset($datos['idsolicitud']['idmensaje']) and isset($datos['idsolicitud']['marcacomo']));
                        break;
                    }
                    case self::txAccListaIdi: { //Dato:37 : LISTAR IDIOMAS
                        //echo "<script>alert('ENTRA POR LISTAR IDIOMAS')</script>";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['clave']));
                        break;
                    }

                    case self::txAccListaLug: { //Dato:38 : LISTAR LUGARES
                        //echo "<script>alert('ENTRA POR LISTAR LUGARES')</script>";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['clave']));
                        break;
                    }

                    case self::txAccMegEjemp: { //Dato:40 : MARCAR MEGUSTA EJEMPLAR
                        //echo "<script>alert('ENTRA POR MARCAR ME GUSTA EJEMPLAR')</script>";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['clave']) and
                                isset($datos['idsolicitud']['ejemplar']) and isset($datos['idsolicitud']['megusta']));
                        break;
                    }
                    
                    case self::txAccVerUsMeg: { //Dato:41 : VER A QUIENES LES GUSTA EL EJEMPLAR
                        //echo "<script>alert('ENTRA POR MARCAR ME GUSTA EJEMPLAR')</script>";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['clave']) and
                                isset($datos['idsolicitud']['ejemplar']));
                        break;
                    }
                    
                    case self::txAccCommEjem: { //Dato:42 : manejo de cometario a ejemplar : Crear comentario, crear comentario hijo, editar comentario, borrar
                        //echo "<script>alert('ENTRA POR MANEJO COMENTARIOS EJEMPLAR')</script>";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['clave']) and
                                isset($datos['idsolicitud']['ejemplar']) and isset($datos['idsolicitud']['comentario']) and 
                                isset($datos['idsolicitud']['idcompadre']) and isset($datos['idsolicitud']['idcomentario']) and 
                                isset($datos['idsolicitud']['accioncom'])); // 0 Borrar - 1 Editar;
                        break;
                    }
                    
                    case self::txAccVerComEj: { //Dato:43 : Ver comentarios ejemplar
                        //echo "<script>alert('ENTRA POR VER COMENTARIOS EJEMPLAR')</script>";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['clave']) and
                                isset($datos['idsolicitud']['ejemplar']));
                        break;
                    }

                    case self::txAccVerTrato: { //Dato:44 : Ver detalle trato
                        //echo "<script>alert('ENTRA POR VER DETALLE TRATO')</script>";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['clave']) and
                                isset($datos['idsolicitud']['idtrato']));
                        break;
                    }

                    case self::txAccListaEdi: { //Dato:50 : LISTAR EDITORIALES
                        //echo "<script>alert('ENTRA POR LISTAR EDITORIALES')</script>";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['clave']));
                        break;
                    }

                    case self::txAccListaAut: { //Dato:51 : LISTAR AUTORES
                        //echo "<script>alert('ENTRA POR LISTAR AUTORES')</script>";
                        $resp = (isset($datos['idsolicitud']['email']) and isset($datos['idsolicitud']['clave']));
                        break;
                    }

                    
                }
            }
        }    
        //echo "<script>alert('VALIDADO COMO: ".$resp ? 'true' : 'false'."')</script>";
        return $resp;
    }

    // función de gestión de errores
    function myFunctionErrorHandler($errno, $errstr, $errfile, $errline)
    {
        /* Según el típo de error, lo procesamos */
        switch ($errno) {
           case E_WARNING:
                    //echo "Hay un WARNING.<br />\n";
                    //echo "El warning es: ". $errstr ."-linea [".$errline."] <br />\n";
                    //echo "El fichero donde se ha producido el warning es: ". $errfile ."<br />\n";
                    //echo "La línea donde se ha producido el warning es: ". $errline ."<br />\n";
                    /* No ejecutar el gestor de errores interno de PHP, hacemos que lo pueda procesar un try catch */
                    return true;
                    break;

                case E_NOTICE:
                    //echo "Hay un NOTICE:<br />\n";
                    /* No ejecutar el gestor de errores interno de PHP, hacemos que lo pueda procesar un try catch */
                    return true;
                    break;

                default:
                    /* Ejecuta el gestor de errores interno de PHP */
                    return false;
                    break;
        }
    }

    
}
