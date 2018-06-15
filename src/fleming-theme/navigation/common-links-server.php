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

    function getAllPublicationTypeLinks()
    {
        return [
            $this->getCaseStudiesLink(),
            $this->getGuidesLink(),
            $this->getResourceLink(),
            $this->getProtocolLink(),
            $this->getResearchLink(),
        ];
    }

    function getCaseStudiesLink()
    {
        $link = new Link();
        $link->setTitle('Case Studies');
        $link->setTarget('/publication_types/case-study/');
        return $link;
    }

    function getGuidesLink()
    {
        $link = new Link();
        $link->setTitle('Guides');
        $link->setTarget('/publication_types/guide/');
        return $link;
    }

    function getResourceLink()
    {
        $link = new Link();
        $link->setTitle('Resource');
        $link->setTarget('/publication_types/resource/');
        return $link;
    }

    function getProtocolLink()
    {
        $link = new Link();
        $link->setTitle('Protocol');
        $link->setTarget('/publication_types/protocol/');
        return $link;
    }

    function getResearchLink()
    {
        $link = new Link();
        $link->setTitle('Research');
        $link->setTarget('/publication_types/research/');
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