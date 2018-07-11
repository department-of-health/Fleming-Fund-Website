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

    function getImportanceOfDataLink()
    {
        $link = $this->getMenuRouteLink('about', 'importance_of_data');
        $link->setTitle('The Importance of Data');
        return $link;
    }

    function getWhatIsAMRLink()
    {
        return $this->getMenuRouteLink('about', 'about_amr');
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

    function getAllGrantsPageLink()
    {
        $link = new Link();
        $link->setTitle('Grants');
        $link->setTarget('/grants/');
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
        $twitterLink = new Link();
        $twitterLink->setTitle('Twitter');
        $twitterLink->setTarget('https://twitter.com/FlemingFund');
        $twitterLink->setExternal(true);
        return $twitterLink;
    }

    function getYouTubeLink()
    {
        // This is the DHSC YouTube channel: I can't find a specific Fleming Fund one.
        $youtubeLink = new Link();
        $youtubeLink->setTitle('YouTube');
        $youtubeLink->setTarget('https://www.youtube.com/channel/UCXmtnbNO7_no7RekfUIqVcw');
        $youtubeLink->setExternal(true);
        return $youtubeLink;
    }

    function getHowToApplyLink()
    {
        $link = new Link();
        $link->setTitle('How to Apply');
        $link->setTarget('/application-process/');
        return $link;
    }

    private function getMenuRouteLink(string ...$menuRouteKeys)
    {
        return MenuLinksConfig::configToLink(MenuLinksConfig::getUnderRoute(...$menuRouteKeys));
    }
}