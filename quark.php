<?php
namespace Grav\Theme;

use Grav\Common\Grav;
use Grav\Common\Page\Interfaces\PageInterface;
use Grav\Common\Theme;
use Grav\Common\Utils;

class Quark extends Theme
{

    protected $secondary_root;

    public static function getSubscribedEvents()
    {
        return [
            'onThemeInitialized'    => ['onThemeInitialized', 0],
            'onTwigLoader'          => ['onTwigLoader', 0],
            'onTwigInitialized'     => ['onTwigInitialized', 0],
        ];
    }

    public function onThemeInitialized()
    {

    }

    // Add images to twig template paths to allow inclusion of SVG files
    public function onTwigLoader()
    {
        $theme_paths = Grav::instance()['locator']->findResources('theme://images');
        foreach($theme_paths as $images_path) {
            $this->grav['twig']->addPath($images_path, 'images');
        }
    }

    public function onTwigInitialized()
    {
        $twig = $this->grav['twig'];

        // Merge custom form classes into Twig variables
        $form_class_variables = [
//            'form_outer_classes' => 'form-horizontal',
            'form_button_outer_classes' => 'button-wrapper',
            'form_button_classes' => 'btn',
            'form_errors_classes' => '',
            'form_field_outer_classes' => 'form-group',
            'form_field_outer_label_classes' => 'form-label-wrapper',
            'form_field_label_classes' => 'form-label',
//            'form_field_outer_data_classes' => 'col-9',
            'form_field_input_classes' => 'form-input',
            'form_field_textarea_classes' => 'form-input',
            'form_field_select_classes' => 'form-select',
            'form_field_radio_classes' => 'form-radio',
            'form_field_checkbox_classes' => 'form-checkbox',
        ];

        $twig->twig_vars = array_merge($twig->twig_vars, $form_class_variables);

        // Add functions to process Menus
        $twig->twig()->addFunction(
            new \Twig\TwigFunction('process_primary_menu', [$this, 'getPrimaryMenu'])
        );
        $twig->twig()->addFunction(
            new \Twig\TwigFunction('process_secondary_menu', [$this, 'getSecondaryMenu'])
        );
    }

    public function getPrimaryMenu()
    {
        /** @var Pages $pages */
        $pages = $this->grav['pages'];

        /** @var PageInterface $root */
        $root = $pages->root()->children()->visible();

        // Loop through top-level menu items
        $links = [];
        foreach ($root as $page) {
            $links[] = $this->buildLinkNode($page, 1, 10, 'primary');
        }

        return $links;
    }

    public function getSecondaryMenu()
    {
        /** @var PageInterface $nav */
        $nav = $this->secondary_root;

        $links = [];
        if ($nav) {

            foreach ($nav->children()->visible() as $child) {
                $links[] = $this->buildLinkNode($child, 1, 4, 'secondary');
            }
        }

        return $links;
    }

        /**
     * Builds nested notes from page structure
     *
     * @param PageInterface $page
     * @param int $level
     * @param int $max_levels
     *
     * @return array
     */
    protected function buildLinkNode(PageInterface $page, $level, $max_levels = 100, $nav = 'primary')
    {
        $active = $page->active();
        $active_child = $page->activeChild();
        $children = $page->children()->visible();
        $has_children = $children->count() > 0;
        $secondary_children = $page->header()->secondary_children ?? false;

        $link['active'] = $active || $active_child;
        $link['href'] = $page->url();
        $link['text'] = $page->menu();
        $link['id'] = $page->slug();
        $link['level'] = $level;

        // Handle Children
        if ($has_children) {
            // See if this is a page that should be stored in sidenav_root
            if ($nav == 'primary' && ($active || $active_child) && (($level >= $max_levels) || $secondary_children)) {
                $this->secondary_root = $page;
            } elseif (!$secondary_children) {
                $child_links = [];
                foreach ($children as $child) {
                    $child_links[] = $this->buildLinkNode($child, $level + 1, $max_levels, $nav);
                }
                $link['links'] = $child_links;
            }
        }

        return $link;
    }

}
