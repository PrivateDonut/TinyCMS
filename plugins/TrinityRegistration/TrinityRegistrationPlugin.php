<?php
class TrinityRegistrationPlugin extends BasePlugin
{
    private $twig;
    private $database;
    private $session;

    public function register()
    {
        // Debug message, remove in production
        //echo "TrinityRegistrationPlugin register method called<br>";
        add_action('init', [
            $this, 'initPlugin'
        ]);
        add_filter('routes', [$this, 'addRoutes']);
        add_filter('twig_loader', [$this, 'addTwigPath']);
    }

    public function initPlugin()
    {
        // Debug message, remove in production
        //echo "TrinityRegistrationPlugin initPlugin method called<br>";
        $this->database = new Database();
        $this->session = new Symfony\Component\HttpFoundation\Session\Session();
        $this->twig = apply_filters('get_twig', null);
    }

    public function addRoutes($routes)
    {
        // Debug message, remove in production
        //echo "TrinityRegistrationPlugin addRoutes method called<br>";
        $routes['/register'] = [RegistrationController::class, 'index'];
        $routes['/register/submit'] = [RegistrationController::class, 'submit'];
        print_r($routes);
        return $routes;
    }

    public function addTwigPath($loader)
    {
        // Debug message, remove in production
        //echo "TrinityRegistrationPlugin addTwigPath method called<br>";
        $loader->addPath(__DIR__ . '/views', 'trinity_registration');
        return $loader;
    }
}
