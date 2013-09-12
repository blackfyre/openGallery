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
        $query = "SELECT * FROM content_articles_category";
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
            $query = "SELECT * FROM content_articles WHERE topicId='$topicId'";
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

}