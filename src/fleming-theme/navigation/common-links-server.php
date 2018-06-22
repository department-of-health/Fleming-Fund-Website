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

    function getContactUsLink()
    {
        $link = new Link();
        $link->setTitle('Contact Us');
        $link->setTarget('/contact-us/');
        return $link;
    }

    function getTermsOfUseLink()
    {
        $link = new Link();
        $link->setTitle('Terms of Use');
        $link->setTarget('/terms-of-use/');
        return $link;
    }

    function getPrivacyStatementLink()
    {
        $link = new Link();
        $link->setTitle('Privacy Statement');
        $link->setTarget('/privacy-statement/');
        return $link;
    }

    function getAllRegionLinks()
    {
        return MenuLinksConfig::configsToLinks(MenuLinksConfig::getAllRegions());
    }

    static function getFundCountryLinksWithinRegion(string $regionSlug)
    {
        return MenuLinksConfig::configsToLinks(MenuLinksConfig::getFundCountryLinkConfigsWithinRegion($regionSlug));
    }

    static function getPartnerCountryLinksWithinRegion(string $regionSlug)
    {
        return MenuLinksConfig::configsToLinks(MenuLinksConfig::getPartnerCountryLinkConfigsWithinRegion($regionSlug));
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
}