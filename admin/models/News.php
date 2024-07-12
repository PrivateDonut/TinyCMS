<?php

/*********************************************************************************
 * DonutCMS is free software: you can redistribute it and/or modify              *        
 * it under the terms of the GNU General Public License as published by          *      
 * the Free Software Foundation, either version 3 of the License, or             *
 * (at your option) any later version.                                           *
 *                                                                               *
 * DonutCMS is distributed in the hope that it will be useful,                   *
 * but WITHOUT ANY WARRANTY; without even the implied warranty of                *
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the                  *
 * GNU General Public License for more details.                                  *
 *                                                                               *
 * You should have received a copy of the GNU General Public License             *
 * along with DonutCMS. If not, see <https://www.gnu.org/licenses/>.             *
 * *******************************************************************************/

class news_admin
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
            'ORDER' => ['id' => 'DESC']
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

    public function add_news($title, $content, $author, $thumbnail)
    {
        $this->website_connection->insert("news", [
            "title" => $title,
            "content" => $content,
            "author" => $author,
            "thumbnail" => $thumbnail,
            "created_at" => date('Y-m-d H:i:s')
        ]);
    }

    public function update_news($id, $title, $content, $author, $thumbnail)
    {
        $this->website_connection->update("news", [
            "title" => $title,
            "content" => $content,
            "author" => $author,
            "thumbnail" => $thumbnail
        ], [
            "id" => $id
        ]);
    }

    public function delete_news($id)
    {
        $this->website_connection->delete("news", ["id" => $id]);
    }
}
