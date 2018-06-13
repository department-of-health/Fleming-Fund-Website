<?php

include_once 'common-links-server.php';
include_once 'link.php';

class NavigationModel
{
    use CommonLinksServer;

    private $menuLinksByLevel;
    private $breadcrumbLinks;

    function __construct()
    {
        $this->menuLinksByLevel = [];
        $this->breadcrumbLinks = [];
    }

    /**
     * @param Link[] $links
     */
    function addMenuLevelWithLinks(array $links)
    {
        $this->menuLinksByLevel[] = $links;
    }

    function addBreadcrumb(Link $link)
    {
        $this->breadcrumbLinks[] = $link;
    }

    function getMenuLinksAtLevel(int $level)
    {
        return $this->menuLinksByLevel[$level] ?? [];
    }

    function getGlobalMenuLinks()
    {
        return $this->getMenuLinksAtLevel(0);
    }

    function getSubGlobalMenuLinks()
    {
        return $this->getMenuLinksAtLevel(1);
    }

    function getTertiaryMenuLinks()
    {
        return $this->getMenuLinksAtLevel(2);
    }

    function getBreadcrumbLinks()
    {
        return $this->breadcrumbLinks;
    }
}