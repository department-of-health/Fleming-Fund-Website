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

    function withMenuRoute(...$menuRouteKeys)
    {
        $this->selectedRouteKeys = array_filter($menuRouteKeys, "is_string");
        return $this;
    }

    private function getPathForPost($postId = 0) {
        $permalink = get_permalink($postId);
        if ($permalink) {
            return MenuLinksConfig::findLink(wp_make_link_relative($permalink));
        }
        return false;
    }

    function withRouteFromPermalink($pageTitle)
    {
        $path = $this->getPathForPost();
        if ($path) {
            $this->selectedRouteKeys = $path;
        } else {
            // We didn't find this page on the menu. Try the parent page
            $parentId = wp_get_post_parent_id(null);
            if ($parentId) {
                $path = $this->getPathForPost($parentId);
                if ($path) {
                    // Found the parent; use that plus this page's title
                    $this->selectedRouteKeys = $path;
                    $this->additionalBreadcrumb = $pageTitle;
                }
            }
        }
        return $this;
    }

    function withDefaultRoute(array $defaultRoute, string $pageTitle)
    {
        if (!$this->selectedRouteKeys) {
            $this->selectedRouteKeys = $defaultRoute;
            $this->additionalBreadcrumb = $pageTitle;
        }
        return $this;
    }

    function withAdditionalBreadcrumb(string $pageTitle)
    {
        $this->additionalBreadcrumb = $pageTitle;
        return $this;
    }

    /*
     * Maps config to a Link with child Links.
     * Recursive
     */
    static function buildLinkWithChildren($menuLinkConfig) {
        $link = MenuLinksConfig::configToLink($menuLinkConfig);
        if (!empty($menuLinkConfig['children'])) {
            $childLinks = array_map('self::buildLinkWithChildren', $menuLinkConfig['children']);
            $link->setChildLinks($childLinks);
        }
        return $link;
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
                $nextLink = self::buildLinkWithChildren($menuLinkConfig);
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