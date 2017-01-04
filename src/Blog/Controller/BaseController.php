<?php
namespace Blog\Controller;

use DI\Annotation\Inject;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

abstract class BaseController
{
    /**
     * @Inject("twig")
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @Inject("db")
     * @var Connection
     */
    protected $db;

    /**
     * @Inject("predis")
     * @var \Predis\Client
     */
    protected $predis;

    /**
     * Renders a template.
     *
     * @param string $name    The template name
     * @param array  $context An array of parameters to pass to the template
     *
     * @return string The rendered template
     *
     * @throws Twig_Error_Loader  When the template cannot be found
     * @throws Twig_Error_Syntax  When an error occurred during compilation
     * @throws Twig_Error_Runtime When an error occurred during rendering
     */
    public function render($name, array $context = array())
    {
        return Response::create($this->twig->render($name, $context));
    }
}