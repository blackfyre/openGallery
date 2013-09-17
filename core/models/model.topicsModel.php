<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.09.10.
 * Time: 16:43
 */

class topicsModel extends modelsHandler {

    /**
     * @return array|bool
     */
    function getTopics() {
        $query = "SELECT * FROM content_articles_category WHERE active='1'";
        return $this->fetchAll($query);
    }

    /**
     * @param null $topicId
     * @return array|bool
     */
    function getTopicDataById($topicId = null) {
        $topicId = coreFunctions::cleanVar($topicId);
        $query = "SELECT * FROM content_articles_category WHERE cacId='$topicId'";
        return $this->fetchSingleRow($query);
    }

    /**
     * @param null $topicId
     * @return array|bool
     */
    function getArticleForTopic($topicId = null) {

        if (is_numeric($topicId)) {
            $query = "SELECT * FROM content_articles WHERE topicId='$topicId' AND active='1'";
            return $this->fetchAll($query);
        }

        return false;
    }

    /**
     * @param null $articleId
     * @return array|bool
     */
    function getArticleById($articleId = null) {
        if (is_numeric($articleId)) {
            $query = "SELECT * FROM content_articles WHERE id='$articleId'";
            return $this->fetchSingleRow($query);
        }

        return false;
    }

    /**
     * @param null $topicId
     *
     * @return array|bool
     */
    function getMenuItemsForTopic($topicId = null) {
        $query = "SELECT * FROM content_articles_category_menu LEFT JOIN content_articles ON content_articles.id=content_articles_category_menu.articleId WHERE cacId='$topicId' AND content_articles_category_menu.active='1' ORDER BY `order` ASC";

        return $this->fetchAll($query);
    }

    /**
     * @param null $topicId
     * @param null $excludeId
     *
     * @return array|bool
     */
    function getAvailableArticlesForTopic($topicId = null, $excludeId= null) {
        $query = "SELECT * FROM content_articles WHERE topicId='$topicId' AND ";

        if (is_numeric($excludeId)) {
            $query .= "(id NOT IN (SELECT articleId FROM content_articles_category_menu WHERE active='1') OR id='$excludeId')";
        } else {
            $query .= "id NOT IN (SELECT articleId FROM content_articles_category_menu WHERE active='1')";
        }

        return $this->fetchAll($query);
    }

    /**
     * @param null $menuId
     *
     * @return array|bool
     */
    function getMenuItem($menuId = null) {
        $query = "SELECT * FROM content_articles_category_menu WHERE menuId='$menuId'";
        return $this->fetchSingleRow($query);
    }

}