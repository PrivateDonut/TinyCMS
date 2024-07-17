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

use DonutCMS\Models\Database;

class NewsAdmin
{
    private $websiteConnection;

    public function __construct()
    {
        $database = new Database();
        $this->websiteConnection = $database->getConnection('website');
    }

    public function getNews()
    {
        $news = $this->websiteConnection->select('news', [
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

    public function getNewsById($id)
    {
        $news = $this->websiteConnection->get("news", "*", ["id" => $id]);
        return $news ? $news : null;
    }

    public function addNews($title, $content, $author, $thumbnail)
    {
        $this->websiteConnection->insert("news", [
            "title" => $title,
            "content" => $content,
            "author" => $author,
            "thumbnail" => $thumbnail,
            "created_at" => date('Y-m-d H:i:s')
        ]);
    }

    public function updateNews($id, $title, $content, $author, $thumbnail)
    {
        $this->websiteConnection->update("news", [
            "title" => $title,
            "content" => $content,
            "author" => $author,
            "thumbnail" => $thumbnail
        ], [
            "id" => $id
        ]);
    }

    public function deleteNews($id)
    {
        $this->websiteConnection->delete("news", ["id" => $id]);
    }
}
