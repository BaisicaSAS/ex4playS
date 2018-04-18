<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appProdProjectContainerUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($rawPathinfo)
    {
        $allow = array();
        $pathinfo = rawurldecode($rawPathinfo);
        $context = $this->context;
        $request = $this->request ?: $this->createRequest($pathinfo);

        // ex4read_ingresarSistema
        if ('/ingreso' === $pathinfo) {
            if ($this->context->getMethod() != 'POST') {
                $allow[] = 'POST';
                goto not_ex4read_ingresarSistema;
            }

            return array (  '_controller' => 'Libreame\\BackendBundle\\Controller\\GamesController::servicioAction',  '_format' => 'json',  '_route' => 'ex4read_ingresarSistema',);
        }
        not_ex4read_ingresarSistema:

        // ex4read_confirmarRegistro
        if (0 === strpos($pathinfo, '/registro') && preg_match('#^/registro/(?P<id>[^/]++)$#sD', $pathinfo, $matches)) {
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
