<?php
class HomeController extends BaseController
{
    public function handle($action, $params)
    {
        switch ($action) {
            case 'index':
            default:
                return $this->index();
        }
    }

    private function index()
    {
        $newsHome = new news_home();
        $latestNews = $newsHome->get_news();
        $server = new ServerInfo();

        return $this->render('home.twig', [
            'latestNews' => $latestNews,
            'server' => $server
        ]);
    }
}
