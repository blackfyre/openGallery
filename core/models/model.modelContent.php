<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.07.24.
 * Time: 17:23
 */

/**
 * Class modelContent
 */
class modelContent extends modelsHandler {

    /**
     * @return array|bool
     */
    function getArticles() {
        $query = "SELECT * FROM content";

        return $this->fetchAll($query);
    }

    /**
     * @return array|bool
     */
    function getFixedContent() {
        $query = "SELECT * FROM content_fixed";

        return $this->fetchAll($query);
    }

    /**
     * @param int $fixedContentId
     * @return array|bool
     */
    function getFixedContentById($fixedContentId = null) {
        if (is_numeric($fixedContentId)) {
            $query = "SELECT * FROM content_fixed WHERE id='$fixedContentId'";

            return $this->fetchSingleRow($query);
        }

        return false;
    }

    /**
     * @param null $articleId
     * @return array|bool
     */
    function getArticle($articleId = null) {
        if (is_numeric($articleId)) {

            $query = "SELECT * FROM content WHERE id='$articleId'";

            return $this->fetchSingleRow($query);

        } else {
            return false;
        }
    }

    /**
     * @param null $lang
     * @param null $slug
     * @return array|bool
     */
    function getArticleBySlug($lang = null,$slug = null) {

        $colName = 'slug_' . coreFunctions::cleanVar($lang);
        $slug = coreFunctions::cleanVar($slug);

        $query = "SELECT * FROM content WHERE `$colName`='$slug'";

        return $this->fetchSingleRow($query);

    }
}