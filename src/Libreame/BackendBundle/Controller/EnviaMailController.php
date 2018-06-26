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
            //echo "\n enviaMailRegistroAction :: cadena a enviar = "."http://baisica.co/ex4play/services/web/registro/".$cadena;
            $message = \Swift_Message::newInstance()
                ->setContentType('text/html')
                ->setSubject('Bienvenido a ex4play '.$usuario->getTxnomusuario())
                ->setFrom(['ex4play@baisica.co' => 'Registro de ex4play'])
                ->setBcc('ex4play@baisica.co')
                //->setFrom('baisicasas@gmail.com')
                //->setBcc('baisicasas@gmail.com')
                ->setTo($usuario->getTxmailusuario())
                ->setBody("<p><strong>Bienvenido ".$usuario->getTxnomusuario().",</strong></p>" 
                        . "<p><tr>Estas a un paso de finalizar tu registro.</tr><br/></p>"
                        . "<p><tr>Haz click en el enlace para confirmar tu cuenta. </tr></p>"
                        //. "<br/><tr><strong> ".$cadena." </tr><br/><br/><br/>"
                        //. "<p><br/><tr><strong> http://localhost/ex4playS/web/registro/".$cadena." </tr><br/><br/><br/></p>"
                        . "<p><br/><tr><strong><align='center'> <a href='http://baisica.co/ex4play/services/web/registro/".$cadena."'>...Click aquí para confirmar...</a> </tr><br/><br/></p>"
                        //. "<p><br/><tr><strong><align='center'> <a href='http://localhost/ex4playS/web/registro/".$cadena."'>...Click aquí para confirmar...</a> </tr><br/><br/></p>"
                        . "<p><tr><strong>El equipo de ex4play</strong></tr></p>"
                        . "<p><tr><strong>Colombia</strong></tr></p>", 'text/html');
                /*->setBody($this->renderView('LibreameBackendBundle:Registro:registro.html.twig',
                    array('usuario' => $usuario->getTxmailusuario(), 
                        'crurl' => "http://baisica.co/ex4play/services/web/registro/".$cadena)
                        //'crurl' => "http://www.ex4read.co/web/registro/".$cadena)
                        //'crurl' => "http://www.ex4read.co/web/registro/".Logica::generaCadenaURL($usuario))
                ),'text/html');*/

            //echo "\n enviaMailRegistr oAction :: se armó Mensaje, viene sendMail :: ".$message->getSubject();
            $mailer->send($message);
            //echo "\n enviaMailRegistroAction :: envió el Mensaje!!!";
        
            return GamesController::inExitoso;
        } catch (Exception $ex) {
                return GamesController::inPlatCai;
        } 
    }
    
}
