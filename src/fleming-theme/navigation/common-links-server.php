<?php

include_once 'link.php';

trait CommonLinksServer {

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

    private function getMenuRouteLink(string ...$menuRouteKeys)
    {
        $config = MenuLinksConfig::ALL[array_shift($menuRouteKeys)];
        while (count($menuRouteKeys) > 0) {
            $config = &$config['children'][array_shift($menuRouteKeys)];
            if (!isset($config)) {
                return null;
            }
        }
        return MenuLinksConfig::configToLink($config);
    }
}