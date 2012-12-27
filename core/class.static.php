<?php
class staticContent extends main
{

    private $textReplace = null;

    function __construct()
    {
        parent::__construct();
    }

    function __destruct()
    {
        parent::__destruct();
    }

    /**
     *
     * Gets the text values from the database to be converted into links
     *
     * @return array|bool Array on success, false on fail
     */
    function getTextReplace()
    {
        $query = "SELECT toReplace, link FROM _text_replace";

        $r = false;

        if ($result = $this->db->query($query)) {

            while ($row = $result->fetch_assoc()) {
                $r[] = $row;
            }

        }

        return $r;

    }

    /**
     *
     */
    function doTextReplace()
    {
        if (is_null($this->textReplace)) {
            $this->textReplace = $this->getTextReplace();
        }
    }

    /**
     *
     * This function gets the static page data (title, meta data, content, ...) from the DB
     *
     * @param string $slug The slug of the static page to get
     * @return array|bool Array of the page data or false upon failure
     */
    function getStaticPage($slug = null)
    {

        if ($slug != null OR $slug != '') {

            $slug = parent::clean_var($slug);

            $title = 'title_' . $this->siteLang;
            $metaKey = 'meta_key_' . $this->siteLang;
            $metaDesc = 'meta_desc_' . $this->siteLang;
            $content = 'content_' . $this->siteLang;

            $query = "SELECT slug, $title AS title, $metaKey AS metaKey, $metaDesc AS metaDesc, $content AS content FROM static_content WHERE slug='$slug'";

            if ($result = $this->db->query($query)) {

                return $result->fetch_assoc();

            } else {
                $this->errorMSG(gettext('Query failed') . ': ' . $this->db->error);
                return false;
            }

        } else {
            return false;
        }
    }

    /**
     *
     * This functions sets the smarty values based on date from art::getStaticPage($slug) and displays the page
     *
     * @param null $slug
     * @return bool
     */
    public function displayStaticPage($slug = null)
    {
        if (!is_null($slug) AND $slug != '') {

            $slug = $this->clean_var($slug);

            $data = $this->getStaticPage($slug);

            $this->smarty->assign('title', $data['title']);
            $this->smarty->assign('content', $data['content']);

            $this->smarty->display('page_static.tpl');

            return true;

        } else {
            return false;
        }
    }
}
