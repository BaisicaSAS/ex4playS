<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Libreame\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Libreame\BackendBundle\Helpers\Logica;
use Libreame\BackendBundle\Entity\Usuario;

/**
 * Description of EnviaMailController
 *
 * @author mramirez
 */
class EnviaMailController extends Controller{
    /*
     * enviaMailRegistro 
     * Se encarga de enviar el email con el que el usuario confirmara su registro
     */
    public static function enviaMailRegistroAction(Usuario $usuario, \Swift_Mailer $mailer)
    {   
        try{
            error_reporting(E_ALL);
            $cadena = Logica::generaCadenaURL($usuario);
            echo "\n enviaMailRegistroAction :: cadena a enviar = "."http://baisica.co/ex4play/services/web/registro/".$cadena;
            $message = \Swift_Message::newInstance()
                ->setContentType('text/html')
                ->setSubject('Bienvenido a ex4play '.$usuario->getTxnomusuario())
                ->setFrom('registro@ex4read.co')
                ->setBcc('registro@ex4read.co')
                //->setFrom('baisicasas@gmail.com')
                //->setBcc('baisicasas@gmail.com')
                ->setTo($usuario->getTxmailusuario())
                ->setBody("Bienvenido ".$usuario->getTxnomusuario().", "
                        . "/\n Estas a un paso de finalizar tu registro. /\n"
                        . "/\n Haz click en el enlace. /\n  http://baisica.co/ex4play/services/web/registro/".$cadena);
                /*->setBody($this->container->get('templating')->renderView(
                    'LibreameBackendBndle:Registro:registro.html.twig', array('content' => $content)));
                /*->setBody($this->renderView('LibreameBackendBundle:Registro:registro.html.twig',
                    array('usuario' => $usuario->getTxmailusuario(), 
                        'crurl' => "http://baisica.co/ex4play/services/web/registro/".$cadena)
                        //'crurl' => "http://www.ex4read.co/web/registro/".$cadena)
                        //'crurl' => "http://www.ex4read.co/web/registro/".Logica::generaCadenaURL($usuario))
                ),'text/html');*/

            echo "\n enviaMailRegistroAction :: se armó Mensaje, viene sendMail :: ".$message->getSubject();
            //$objmail = $this->container->get('mailer');
            $mailer->send($message);
            //$this->get('mailer')->send($message);
            echo "\n enviaMailRegistroAction :: envió el Mensaje!!!";
            //  $objmail = $this->get('mailer');
            //$objmail->sendEmail($message);    
        
            return GamesController::inExitoso;
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }
    
}
