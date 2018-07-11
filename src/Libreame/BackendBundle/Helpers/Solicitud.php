<?php

namespace Libreame\BackendBundle\Helpers;

/**
 * Esta clase contiene la información recibida de la solicitud de un usuario::: 
 * Puede entenderse como la Clase que recibe la infoirmacion del JSON
 *
 * @author mramirez
 */
class Solicitud {
    //@TODO: Reorganizar el encabezado para que contenga solo IDs, y solo la información que identifica la Transaccion. 
    //Todo el resto debe ir ebn el detalle...ejemplo el telefono
    //Atributos para el encabezado: identificado en el tag IDSESION del JSON
    private $pAccion; //Accion solicitada
    private $pSession; //Sesion ???? 
    private $pIPaddr; //Direccion IP

    //Atributos para el detalle: identificado en el tag IDSOLICITUD del JSON
    //C01: Registro 
    private $pEmail; //Mail del usuario
    private $pTelefono; //Numero telefónico 
    //$pClave se utiliza tambien para C02: Login
    private $pClave; //Clave del usuario
    private $pClaveNueva; //Clave Nueva del usuario
    private $pNomUsuario; //Nombre de usuario
    private $pNomMostUsuario; //Nombre para mostrar de usuario
    private $pUsuGenero; //Genero del usuario
    private $pUsuImagen; //Imagen
    private $pUsuFecNac; //Fecha de nacimiento
    private $pUsuLugar; //Lugar del usuario
    //$pUltEjemplar
    private $pUltEjemplar;
    //Parametros para la creación de un Ejemplar, cargue o búsqueda del libro y de los generos
    private $pTipopublica; //Tipo de publicacion 0 / 1 / 2
    private $pIdOferta;
    private $pTitulo; //Titulo del videojuego ofrecido
    private $pIdvidjuego; //Id del videojuego ofrecido
    private $pIdioma; //Idioma
    private $pAvaluo; //Avalúa
    private $pValVenta; //Valor venta
    private $pTituloSol1; //Primer Titulo Solicitado
    private $pIdlibroSol1; //Primer id del libro Solicitado
    private $pValAdicSol1; //Valor adicional para el primer libro
    private $pTituloSol2; //Segundo Titulo Solicitado
    private $pIdlibroSol2; //Segundo id del libro Solicitado
    private $pValAdicSol2; //Valor adicional para el segundo libro
    private $pObservaSol; //Observaciones de la oferta
    private $pImagenEje; //Imagen del ejemplar
    //Parametros para la búsqueda
    private $pTextoBuscar; //Texto a buscar
    //Parametros para marcar mensaje como leído
    private $pIdMensaje; //Id del mensaje
    private $pMarcaComo; //Marcar usuario como : Leido = 1, No leido = 0
    //Parametros para recuperar usuario otro
    private $pIdUsuarioVer; //Id del mensaje
    //Id de un ejemplar
    private $pIdEjemplar;
    //Marca de megusta o no me gusta para un ejemplar
    private $pMegusta;
    //Comentario a realizar para un ejemplar
    private $pComentario; // Texto del comentario
    private $pIdComPadre; //Id del comentario padre / cuando es respuesta a un comentario
    private $pIdComentario; //Id del comentario - cuando se edita o borra
    private $pAccioncom; //Id del comentario - cuando se edita o borra
    private $pFiltro; //Id del comentario - cuando se edita o borra
    private $pIdUsuarioDes; //Id del usuario destinatario del mensaje
    private $pinCalificacion; //Calificacion dada por el usuario
    private $pinRegHisPublicacion; //Id del registro de publicacion del ejemplar
    
    private $pConsola; // Consola
    private $pFabricante; // Fabricante
    private $pEdicion; // Edicion
    private $pEstado; // Estado del ejemplar
    private $pModopublica; // Modo publicacion
    private $pRepetir; // Repetir Ejemplar en la publicacion
    
    
    /*  La variable tratoacep es realmente el indicador del significado del mensaje 
        -1	S, D	Mensaje de texto normal (El que existe actualente para enviar un mensaje en el chat)
        0	S, D	Cancelar el trato actual
        1	S, D	Aceptar el trato
        2	S	Ofertar un valor por un ejemplar
        3	D	Contraofertar una oferta realizada por el Solicitante
        4	D	Entregar el ejemplar (Se informa a la plataforma que físicamente se entrego)
        5	S	Recibir el ejemplar (Se informa a la plataforma que físicamente se recibió)
        6	S	Calificación (El solicitante califica)
        7	D	Calificación (El dueño califica)
        10	S, D	TRATO FINALIZADO
     */
    private $pTratoAcep; 
    private $pValor; // Valor que envía un usuario para oferta o contraoferta - 
                     //  Tambien se utiliza para enviar la calificación luego de completar la transaccion
    private $pUnidad; // Unidad Puntos / Pesos
    
    
    /*
     *  Bloque de getter para los atributos de la clase
     */
    
    public function getAccion() {
        return $this->pAccion;
    }

    public function getSession() {
        return $this->pSession;
    }

    public function getIPaddr() {
        return $this->pIPaddr;
    }

    public function getEmail() {
        return $this->pEmail;
    }
    
    public function getClave() {
        return $this->pClave;
    }

    public function getClaveNueva() {
        return $this->pClaveNueva;
    }

    public function getNomUsuario() {
        return $this->pNomUsuario;
    }

    public function getNomMostUsuario() {
        return $this->pNomMostUsuario;
    }

    public function getUsuGenero() {
        return $this->pUsuGenero;
    }

    public function getUsuImagen() {
        return $this->pUsuImagen;
    }

    public function getUsuFecNac() {
        return $this->pUsuFecNac;
    }

    public function getUsuLugar() {
        return $this->pUsuLugar;
    }

    public function getTelefono() {
        return $this->pTelefono;
    }

    public function getUltEjemplar() {
        return $this->pUltEjemplar;
    }
    
    public function getTipopublica() {
        return $this->pTipopublica;
    }
    
    public function getIdOferta() {
        return $this->pIdOferta;
    }

    public function getTitulo() {
        return $this->pTitulo;
    }
    
    public function getIdvidjuego() {
        return $this->pIdvidjuego;
    }
        
    public function getIdioma() {
        return $this->pIdioma;
    }
    
    public function getAvaluo() {
        return $this->pAvaluo;
    }
    
    public function getValventa() {
        return $this->pValVenta;
    }

    public function getTituloSol1() {
        return $this->pTituloSol1;
    }
    
    public function getIdLibroSol1() {
        return $this->pIdlibroSol1;
    }
   
    public function getValAdicSol1() {
        return $this->pValAdicSol1;
    }

    public function getTituloSol2() {
        return $this->pTituloSol2;
    }
    
    public function getIdLibroSol2() {
        return $this->pIdlibroSol2;
    }
   
    public function getValAdicSol2() {
        return $this->pValAdicSol2;
    }

    public function getObservaSol() {
        return $this->pObservaSol;
    }
    
    public function getTextoBuscar() {
        return $this->pTextoBuscar;
    }
    
    public function getIdmensaje() {
        return $this->pIdMensaje;
    }
    
    public function getMarcacomo() {
        return $this->pMarcaComo;
    }

    public function getIdusuariover() {
        return $this->pIdUsuarioVer;
    }
    
    public function getIdusuariodes() {
        return $this->pIdUsuarioDes;
    }
    
    public function getImageneje() {
        return $this->pImagenEje;
    }

    public function getIdEjemplar() {
        return $this->pIdEjemplar;
    }

    public function getMegusta() {
        return $this->pMegusta;
    }

    public function getComentario() {
        return $this->pComentario;
    }

    public function getIdComPadre() {
        return $this->pIdComPadre;
    }

    public function getIdComentario() {
        return $this->pIdComentario;
    }

    public function getAccionComm() {
        return $this->pAccioncom;
    }

    public function getFiltro() {
        return $this->pFiltro;
    }

    public function getInCalificacion() {
        return $this->pinCalificacion;
    }

    public function getInRegHisPublicacion() {
        return $this->pinRegHisPublicacion;
    }
    
    public function getConsola() {
        return $this->pConsola;
    }
    
    public function getFabricante() {
        return $this->pFabricante;
    }
    
    public function getEdicion() {
        return $this->pEdicion;
    }
    
    public function getEstado() {
        return $this->pEstado;
    }
    
    public function getModopublica() {
        return $this->pModopublica;
    }
    
    public function getTratoAcep() {
        return $this->pTratoAcep;
    }
    
    public function getValor() {
        return $this->pValor;
    }
    
    public function getUnidad() {
        return $this->pUnidad;
    }
    
    public function getRepetir() {
        return $this->pRepetir;
    }
    
    
    
    /*
     *  Bloque de setter para los atributos de la clase
     */
    public function setAccion($pAccion) {
        $this->pAccion = $pAccion;
        return $this;
    }

    public function setSession($pSesion) {
        $this->pSession = $pSesion;
        return $this;
    }

    public function setIPaddr($pIPaddr) {
        $this->pIPaddr = $pIPaddr;
        return $this;
    }

    /*public function setDeviceMAC($pDeviceMAC) {
        $this->pDeviceMAC = $pDeviceMAC;
        return $this;
    }

    public function setDeviceMarca($pDeviceMarca) {
        $this->pDeviceMarca = $pDeviceMarca;
        return $this;
    }

    public function setDeviceModelo($pDeviceModelo) {
        $this->pDeviceModelo = $pDeviceModelo;
        return $this;
    }

    public function setDeviceSO($pDeviceSO) {
        $this->pDeviceSO = $pDeviceSO;
        return $this;
    }
    */
    public function setEmail($pEmail) {
        $this->pEmail = $pEmail;
        return $this;
    }

    public function setClave($pClave) {
        $this->pClave = $pClave;
        return $this;
    }

    public function setClaveNueva($pClaveNueva) {
        $this->pClaveNueva = $pClaveNueva;
        return $this;
    }

    
    public function setNomUsuario($pNomUsuario) {
        $this->pNomUsuario = $pNomUsuario;
        return $this;
    }

    public function setNomMostUsuario($pNomMostUsuario) {
        $this->pNomMostUsuario= $pNomMostUsuario;
        return $this;
    }

    public function setUsuGenero($pUsugenero) {
        $this->pUsuGenero = $pUsugenero;
        return $this;
    }

    public function setUsuImagen($pUsuImagen) {
        $this->pUsuImagen = $pUsuImagen;
        return $this;
    }

    public function setUsuFecNac($pUsuFecNac) {
        $this->pUsuFecNac = $pUsuFecNac;
        return $this;
    }

    public function setUsuLugar($pUsuLugar) {
        $this->pUsuLugar = $pUsuLugar;
        return $this;
    }

    
    public function setTelefono($pTelefono) {
        $this->pTelefono = $pTelefono;
        return $this;
    }
    
    public function setUltEjemplar($pUltEjemplar) {
        if ($pUltEjemplar==""){
            $this->pUltEjemplar = 0;
        } else {
            $this->pUltEjemplar = $pUltEjemplar;
        }
        return $this;
    }

    public function setTipopublica($pTipopublica) {
        $this->pTipopublica = $pTipopublica;
        return $this;
    }
    
    public function setIdOferta($pIdOferta) {
        $this->pIdOferta = $pIdOferta;
        return $this;
    }

    public function setTitulo($pTitulo) {
        $this->pTitulo = $pTitulo;
        return $this;
    }
    
    public function setIdvidjuego($pIdvidjuego) {
        $this->pIdvidjuego = $pIdvidjuego;
        return $this;
    }
        
    public function setIdioma($pIdioma) {
        $this->pIdioma = $pIdioma;
        return $this;
    }
    
    public function setAvaluo($pAvaluo) {
        $this->pAvaluo = $pAvaluo;
        return $this;
    }
    
    public function setValventa($pValVenta) {
        $this->pValVenta = $pValVenta;
        return $this;
    }

    public function setTituloSol1($pTituloSol1) {
        $this->pTituloSol1 = $pTituloSol1;
        return $this;
    }
    
    public function setIdLibroSol1($pIdLibroSol1) {
        $this->pIdlibroSol1 = $pIdLibroSol1;
        return $this;
    }
   
    public function setValAdicSol1($pValAdicSol1) {
        $this->pValAdicSol1 = $pValAdicSol1;
        return $this;
    }

    public function setTituloSol2($pTituloSol2) {
        $this->pTituloSol2 = $pTituloSol2;
        return $this;
    }
    
    public function setIdLibroSol2($pIdLibroSol2) {
        $this->pIdlibroSol2 = $pIdLibroSol2;
        return $this;
    }
   
    public function setValAdicSol2($pValAdicSol2) {
        $this->pValAdicSol2 = $pValAdicSol2;
        return $this;
    }

    public function setObservaSol($pObservaSol) {
        $this->pObservaSol = $pObservaSol;
        return $this;
    }
    
    public function setTextoBuscar($pTextoBus) {
        $this->pTextoBuscar = $pTextoBus;
        return $this;
    }
    
    public function setIdmensaje($pIdMens) {
        $this->pIdMensaje = $pIdMens;
        return $this;
    }
    
    public function setMarcarcomo($pMarcar) {
        $this->pMarcaComo = $pMarcar;
        return $this;
    }
    
    public function setIdusuariover($pIdUsrVer) {
        $this->pIdUsuarioVer = $pIdUsrVer;
        return $this;
    }
    
    public function setIdusuariodes($pIdUsrDes) {
        $this->pIdUsuarioDes = $pIdUsrDes;
        return $this;
    }
    
    public function setImageneje($pimageneje) {
        $this->pImagenEje = $pimageneje;
        return $this;
    }
    
    public function setIdEjemplar($pidejemplar) {
        $this->pIdEjemplar = $pidejemplar;
        return $this;
    }
    
    public function setMegusta($pmegusta) {
        $this->pMegusta = $pmegusta;
        return $this;
    }
    
    public function setComentario($pcomentario) {
        $this->pComentario = $pcomentario;
        return $this;
    }
    
    public function setIdComPadre($pidcompadre) {
        $this->pIdComPadre = $pidcompadre;
        return $this;
    }
    
    public function setIdComentario($pidcomentario) {
        $this->pIdComentario = $pidcomentario;
        return $this;
    }
    
    public function setAccionCom($paccioncom) {
        $this->pAccioncom = $paccioncom;
        return $this;
    }
    
    public function setFiltro($pfiltro) {
        $this->pFiltro = $pfiltro;
        return $this;
    }
    
    public function setCalificacion($pcalificacion) {
        $this->pinCalificacion = $pcalificacion;
        return $this;
    }
    
    public function setRegHisPublicacion($preghispublicacion) {
        $this->pinRegHisPublicacion = $preghispublicacion;
        return $this;
    }
    
    public function setConsola($pconsola) {
        $this->pConsola = $pconsola;
        return $this;
    }
    
    public function setFabricante($pfabricante) {
        $this->pFabricante = $pfabricante;
        return $this;
    }
    
    public function setEdicion($pedicion) {
        $this->pEdicion = $pedicion;
        return $this;
    }
    
    public function setEstado($pestado) {
        $this->pEstado = $pestado;
        return $this;
    }
    
    public function setModopublica($pmodopublica) {
        $this->pModopublica = $pmodopublica;
        return $this;
    }
    
    public function setTratoAcep($ptratoacep) {
        $this->pTratoAcep = $ptratoacep;
        return $this;
    }
    
    public function setValor($pvalor) {
        $this->pValor = $pvalor;
        return $this;
    }
    
    public function setUnidad($punidad) {
        $this->pUnidad = $punidad;
        return $this;
    }
    
    public function setRepetir($prepetir) {
        $this->pRepetir = $prepetir;
        return $this;
    }
    
    
    
}
