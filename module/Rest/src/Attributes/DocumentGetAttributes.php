<?php

namespace Rest\Attributes;


/**
 * Class DocumentGetAttributes
 * @package Rest\Attributes
 */
class DocumentGetAttributes
{

    /**
     * @var string
     */
    public $title = '';
    /**
     * @var string
     */
    public $content = '';
    /**
     * @var string
     */
    public $date_created = '';

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * @param string $date_created
     * @return $this
     */
    public function setDateCreated($date_created)
    {
        $this->date_created = $date_created;
        return $this;
    }

}