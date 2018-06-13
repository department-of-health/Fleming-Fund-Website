<?php

include_once 'menu-links-config.php';
include_once 'model.php';

class NavigationModelBuilder
{
    private $selectedRouteKeys = [];
    private $additionalBreadcrumb = null;

    function __construct()
    {
        $this->selectedRouteKeys = [];
    }

    function withMenuRoute(string ...$menuRouteKeys)
    {
        $this->selectedRouteKeys = $menuRouteKeys;
        return $this;
    }

    function withAdditionalBreadcrumb(string $pageTitle)
    {
        $this->additionalBreadcrumb = $pageTitle;
        return $this;
    }

    function build()
    {
        $selectedItems = $this->selectedRouteKeys; // copy

        $navigationModel = new NavigationModel();

        $baseMenuConfig = MenuLinksConfig::getAll();

        do { // recurse through levels of the route config
            $activeLinkKey = array_shift($selectedItems) ?? null;

            $nextNavigationLevelLinks = [];
            foreach ($baseMenuConfig as $linkKey => $menuLinkConfig) {
                $nextLink = MenuLinksConfig::configToLink($menuLinkConfig);
                if ($activeLinkKey === $linkKey) {
                    $nextLink->setIsActive(true);
                    $navigationModel->addBreadcrumb($nextLink);
                }
                $nextNavigationLevelLinks[] = $nextLink;
            }
            $navigationModel->addMenuLevelWithLinks($nextNavigationLevelLinks);

            if (!isset($baseMenuConfig[$activeLinkKey]['children'])) {
                break; // no more descendant routes
            }
            $baseMenuConfig = &$baseMenuConfig[$activeLinkKey]['children'];
        } while (true);

        $this->addAdditionalBreadcrumbTo($navigationModel);

        return $navigationModel;
    }

    private function addAdditionalBreadcrumbTo(NavigationModel $model)
    {
        if (!is_null($this->additionalBreadcrumb)) {
            $additionalBreadcrumbItem = new Link();
            $additionalBreadcrumbItem->setTitle($this->additionalBreadcrumb);
            $model->addBreadcrumb($additionalBreadcrumbItem);
        }
    }
}