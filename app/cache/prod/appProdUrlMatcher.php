<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * appProdUrlMatcher
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appProdUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    /**
     * Constructor.
     */
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = array();
        $pathinfo = rawurldecode($pathinfo);
        $context = $this->context;
        $request = $this->request;

        // ex4read_ingresarSistema
        if ($pathinfo === '/ingreso') {
            if ($this->context->getMethod() != 'POST') {
                $allow[] = 'POST';
                goto not_ex4read_ingresarSistema;
            }

            return array (  '_controller' => 'Libreame\\BackendBundle\\Controller\\GamesController::servicioAction',  '_format' => 'json',  '_route' => 'ex4read_ingresarSistema',);
        }
        not_ex4read_ingresarSistema:

        // ex4read_confirmarRegistro
        if (0 === strpos($pathinfo, '/registro') && preg_match('#^/registro/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                $allow = array_merge($allow, array('GET', 'HEAD'));
                goto not_ex4read_confirmarRegistro;
            }

            return $this->mergeDefaults(array_replace($matches, array('_route' => 'ex4read_confirmarRegistro')), array (  '_controller' => 'Libreame\\BackendBundle\\Controller\\RegistroController::confirmarRegistroAction',));
        }
        not_ex4read_confirmarRegistro:

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}
