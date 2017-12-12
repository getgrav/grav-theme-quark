<?php
namespace Grav\Theme;

use Grav\Common\Theme;

class Quark extends Theme
{
    public static function getSubscribedEvents()
    {
        return [
            'onThemeInitialized' => ['onThemeInitialized', 0],
            'onTwigInitialized'  => ['onTwigInitialized', 0],
        ];
    }

    public function onThemeInitialized()
    {

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
    }
}
