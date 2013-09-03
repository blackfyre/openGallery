<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.07.22.
 * Time: 15:36
 */

class newsModel extends modelsHandler {

    /**
     *
     * Get the news
     *
     * @param null|string|array $lang isoCode for narrowing the result set for the given lang
     * @param int|null $published
     * @param null $count
     * @return null
     */
    function getNews($lang = null, $published = null, $count = null) {

        $query = "SELECT * FROM content_news";

        if (is_int($published)) {
            $query .= " WHERE published='$published'";
        }

        /*
         * If the $lang is a string than it's possible that only 1 lang was given
         */
        if (is_string($lang)) {
            $query .= ' ' . (!is_int($published)?'WHERE':' AND');
            $query .= " isoCode='$lang'";
        }

        /*
         * if $lang is an array then we want to expand the result set to all languages in the set
         */
        if (is_array($lang)) {
            $query .= ' ' . (!is_int($published)?'WHERE':' AND');

            $t = null;

            foreach ($lang AS $l) {
                $t[] = " isoCode='$l' ";
            }

            $query .= 'AND ' . implode(' AND ', $t);


        }

        if (is_numeric($count)) {
            $query .= " LIMIT $count";
        }

        return $this->fetchAll($query);
    }

    /**
     * Get a single article
     * @param null $articleId
     * @return array|bool|null
     */
    function getArticle($articleId = null) {

        if (is_numeric($articleId)) {
            $query = "SELECT * FROM content_news WHERE newsId='$articleId'";
            return $this->fetchSingleRow($query);
        }

        return null;
    }


}