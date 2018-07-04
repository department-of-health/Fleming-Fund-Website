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
        return $this->getMenuRouteLink('about', 'overview');
    }

    function getWhatIsAMRLink()
    {
        $link = $this->getMenuRouteLink('about');
        $link->setTitle('What is AMR');
        return $link;
    }

    function getPublicationsLink()
    {
        return $this->getMenuRouteLink('knowledge');
    }

    function getAllPublicationTypeLinks()
    {
        return MenuLinksConfig::configsToLinks(MenuLinksConfig::getAllPublicationTypes());
    }

    function getCaseStudiesLink()
    {
        $link = new Link();
        $link->setTitle('Case Studies');
        $link->setTarget('/publication_types/case-study/');
        return $link;
    }

    function getGrantsPageLink()
    {
        return $this->getMenuRouteLink('grants');
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

    function getTwitterLink()
    {
        $homeLink = new Link();
        $homeLink->setTitle('Twitter');
        $homeLink->setTarget('https://twitter.com/FlemingFund');
        $homeLink->setExternal(true);
        return $homeLink;
    }

    // Can't find Facebook, LinkedIn or YouTube yet!

    private function getMenuRouteLink(string ...$menuRouteKeys)
    {
        return MenuLinksConfig::configToLink(MenuLinksConfig::getUnderRoute(...$menuRouteKeys));
    }
}