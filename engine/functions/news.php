<?php

class news_home
{
    private $website_connection;

    public function __construct()
    {
        $database = new Database();
        $this->website_connection = $database->getConnection('website');
    }

    public function get_news()
    {
        $news = $this->website_connection->select('news', [
            'id', 'title', 'content', 'author', 'created_at', 'thumbnail'
        ], [
            'ORDER' => ['id' => 'DESC'],
            'LIMIT' => 4
        ]);

        // Optionally format the date if needed
        foreach ($news as &$item) {
            $item['date'] = $item['created_at']; // If you need to format the date, you can do it here
        }

        return $news;
    }

    public function get_news_by_id($id)
    {
        $news = $this->website_connection->get("news", "*", ["id" => $id]);
        return $news ? $news : null;
    }
}
