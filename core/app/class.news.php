<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.07.22.
 * Time: 15:31
 */

class news {

    private $model = null;

    function __construct() {
        $this->model = new newsModel();
    }

    public function hir($newsSlug = null) {

        echo $newsSlug;

        $newsSlug = coreFunctions::cleanVar($newsSlug);

        $data = $this->model->getNewsBySlug($newsSlug);

        if (is_array($data)) {

                $r['metaTitle'] = $data['title_' . $_SESSION['lang']];
                $r['metaKeys'] = $data['metaKey_' . $_SESSION['lang']];
                $r['metaDesc'] = $data['metaDesc_' . $_SESSION['lang']];
                $r['content'] = $data['content_' . $_SESSION['lang']];


            return $r;

        } else {
            return null;
        }

    }
}