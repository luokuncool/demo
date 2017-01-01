<?php
namespace Blog\Controller;

use DI\Annotation\Inject;
use Doctrine\DBAL\Connection;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

abstract class Controller
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
        return $this->twig->render($name, $context);
    }

    /**
     * response with json string
     *
     * @param $data
     */
    public function json($data)
    {
        header('Content-Type:application/json', true);
        echo json_encode($data);
    }
}