<?php
namespace Grav\Theme;

use Grav\Common\Theme;
use Grav\Common\Utils;

class Quark extends Theme
{
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
        $this->grav['twig']->addPath(__DIR__ . '/images', 'images');
    }

    public function onTwigInitialized()
    {
        $twig = $this->grav['twig'];

        $form_class_variables = [
            'form_outer_classes' => 'form-horizontal',
            'form_button_outer_classes' => '',
            'form_button_classes' => 'btn',
            'form_errors_classes' => '',
            'form_field_outer_classes' => 'form-group',
            'form_field_outer_label_classes' => 'col-3',
            'form_field_label_classes' => 'form-label',
            'form_field_outer_data_classes' => 'col-9',
            'form_field_data_classes' => '',
            'form_errors_classes' => '',
        ];

        $twig->twig_vars = array_merge($twig->twig_vars, $form_class_variables);

        $this->grav['twig']->twig()->addFunction(
            new \Twig_SimpleFunction('bodyclass', [$this, 'getBodyClass'])
        );
        $this->grav['twig']->twig()->addFunction(
            new \Twig_SimpleFunction('themevar', [$this, 'getThemeVar'])
        );
    }

    public function getThemeVar($var)
    {
        return $this->config->get('theme.' . $var, false) ?: '';
    }

    public function getBodyClass($classes)
    {

        $header = $this->grav['page']->header();
        $body_classes = isset($header->body_classes) ? $header->body_classes : '';

        foreach ((array)$classes as $class) {
            if (!empty($body_classes) && Utils::contains($body_classes, $class)) {
                continue;
            } else {
                $val = $this->config->get('theme.' . $class, false) ? $class : false;
                $body_classes .= $val ? ' ' . $val : '';
            }
        }

        return $body_classes;
    }
}
