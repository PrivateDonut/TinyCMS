<?php
abstract class BaseController
{
    protected $twig;
    protected $global;
    protected $config;

    public function __construct($twig, $global, $config)
    {
        $this->twig = $twig;
        $this->global = $global;
        $this->config = $config;
    }

    abstract public function handle($action, $params);

    protected function render($template, $data = [])
    {
        return $this->twig->render($template, array_merge([
            'session' => $_SESSION,
            'global' => $this->global,
            'config' => $this->config
        ], $data));
    }
}
