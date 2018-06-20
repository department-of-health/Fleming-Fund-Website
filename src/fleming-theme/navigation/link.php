<?php

class Link
{
    private $title;
    private $target;
    private $isActive;
    private $external;
    private $childLinks;

    function __construct()
    {
        $this->isActive = false;
        $this->external = false;
        $this->childLinks = [];
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param string|null $target
     */
    public function setTarget($target): void
    {
        $this->target = $target;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return bool
     */
    public function isExternal(): bool
    {
        return $this->external;
    }

    /**
     * @param bool $external
     */
    public function setExternal(bool $external): void
    {
        $this->external = $external;
    }

    /**
     * @return array
     */
    public function getChildLinks()
    {
        return $this->childLinks;
    }

    /**
     * @param array $childLinks
     */
    public function setChildLinks($childLinks): void
    {
        $this->childLinks = $childLinks;
    }
}