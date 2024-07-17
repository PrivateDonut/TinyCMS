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
            'id',
            'title',
            'content',
            'author',
            'created_at',
            'thumbnail'
        ], [
            'ORDER' => ['created_at' => 'DESC'],
            'LIMIT' => 4
        ]);

        foreach ($news as &$item) {
            $item['summary'] = $this->generateSummary($item['content']);
        }

        return $news;
    }

    private function generateSummary($content, $length = 200)
    {
        $summary = strip_tags($content);
        if (strlen($summary) > $length) {
            $summary = substr($summary, 0, $length) . '...';
        }
        return $summary;
    }

    public function get_news_by_id($id)
    {
        $news = $this->website_connection->get("news", "*", ["id" => $id]);
        return $news ? $news : null;
    }
}
