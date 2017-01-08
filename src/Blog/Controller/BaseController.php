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
     * @param bool   $pjax
     *
     * @return Response The rendered template
     *
     */
    public function render($name, array $context = array(), $pjax = false)
    {
        $response = new Response();
        if (!$pjax) {
            return $response->setContent($this->twig->render($name, $context));
        }

        $template = $this->twig->load($name);
        foreach ($template->getBlockNames($context) as $blockName) {
            $block = $template->renderBlock($blockName, $context);
            $response->setContent($response->getContent() . ($blockName == 'title' ? "<title>$block</title>" : $block) . PHP_EOL);
        }
        return $response;
    }
}