<?php

include_once 'link.php';

trait CommonLinksServer
{

    function getHomeLink()
    {
        $homeLink = new Link();
        $homeLink->setTitle('Home');
        $homeLink->setTarget('/');
        return $homeLink;
    }

    function getAboutUsLink()
    {
        return $this->getMenuRouteLink('about');
    }

    function getOurAimsLink()
    {
        return $this->getMenuRouteLink('about', 'aims');
    }

    function getWhatIsAMRLink()
    {
        $link = $this->getMenuRouteLink('about');
        $link->setTitle('What is AMR');
        return $link;
    }

    function getAllRegionLinks()
    {
        return MenuLinksConfig::configsToLinks(MenuLinksConfig::getAllRegions());
    }

    function getCountryLinksWithinRegion(string $regionSlug)
    {
        return $this->getMenuRouteLinkChildren('regions', $regionSlug);
    }

    function getTwitterLink() {
        $homeLink = new Link();
        $homeLink->setTitle('Twitter');
        $homeLink->setTarget('https://twitter.com/FlemingFund');
        $homeLink->setExternal(true);
        return $homeLink;

    }

    private function getMenuRouteLink(string ...$menuRouteKeys)
    {
        return MenuLinksConfig::configToLink(MenuLinksConfig::getUnderRoute(...$menuRouteKeys));
    }

    private function getMenuRouteLinkChildren(string ...$menuRouteKeys)
    {
        $childLinkConfigs = MenuLinksConfig::getUnderRoute(...$menuRouteKeys)['children'] ?? [];
        return array_map('MenuLinksConfig::configToLink', $childLinkConfigs);
    }
}