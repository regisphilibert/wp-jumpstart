<?php
if(function_exists("register_field_group"))
{
    register_field_group(array (
        'id' => 'acf_rp-tools',
        'title' => 'RP Tools',
        'fields' => array (
            array (
                'key' => 'field_53237086dea7f',
                'label' => 'Production',
                'name' => 'rptools_production',
                'type' => 'true_false',
                'instructions' => 'Are we in production yet ?',
                'message' => 'Oh my god we are!',
                'default_value' => 0,
            ),
            array (
                'key' => 'field_532370b8dea80',
                'label' => 'Debug',
                'name' => '',
                'type' => 'tab',
            ),
            array (
                'key' => 'field_532370d4dea81',
                'label' => 'Debug users',
                'name' => 'rptools_debug_users',
                'type' => 'user',
                'instructions' => 'Those users will see the debug alert plus they will see the Jumpstart settings.',
                'role' => '',
                'allow_null' => 0,
                'multiple' => 1,
            ),
            array (
                'key' => 'field_53237105be125',
                'label' => 'Debug mode',
                'name' => 'rptools_display_debug',
                'type' => 'true_false',
                'instructions' => 'If checked, we\'ll had the .jumpstart-debug class to the body so that debug only styling can be applied.',
                'message' => 'Yes! Debug mode that sucker!',
                'default_value' => 0,
            ),
            array (
                'key' => 'field_5323712bbe126',
                'label' => 'Dependencies',
                'name' => '',
                'type' => 'tab',
            ),
            array (
                'key' => 'field_53237139be127',
                'label' => 'Load wordpress ajax library on front?',
                'name' => 'rptools_ajax_on',
                'type' => 'true_false',
                'instructions' => 'Ajax library is not loaded by default on frontend. It\'s just a simple head tag containing the url but we may not need it yet...',
                'message' => 'Yes, load wp ajax.',
                'default_value' => 0,
            ),
            array (
                'key' => 'field_53237188be128',
                'label' => 'Jumpstart\'s modernizr',
                'name' => 'rptools_modernizr_dev',
                'type' => 'true_false',
                'instructions' => 'Jumpstarts load by default the full Modernizr.dev.js. You will have to uncheck that box on delivery and load you own customized Modernizr.min.js... But in the mean time, you\'ve got every test possible.',
                'message' => 'I\'m still in production. Modernizr.dev.js is fine.',
                'default_value' => 1,
            ),
            array (
                'key' => 'field_532371ca6a2b1',
                'label' => 'Jumpstart Bootstrap',
                'name' => 'rptools_bootstrap_css',
                'type' => 'true_false',
                'instructions' => 'Jumpstart uses the Code and Glyphicon modules of Bootstrap. If you already load those modules with the main theme, uncheck that box.',
                'message' => 'Keep using Jumpstart\'s Bootstrap',
                'default_value' => 0,
            ),
            array (
                'key' => 'field_532371f36a2b2',
                'label' => 'ACF',
                'name' => '',
                'type' => 'tab',
            ),
            array (
                'key' => 'field_532372066a2b3',
                'label' => 'Hide ACF menu?',
                'name' => 'rptools_acf_menu',
                'type' => 'true_false',
                'instructions' => 'You don\'t want your end user to see and touch that. <br>(You\'re gonna have to leave this page for this option to initiate)',
                'message' => 'Yes please!',
                'default_value' => 0,
            ),
        ),
        'location' => array (
            array (
                array (
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'acf-options-jumpstart',
                    'order_no' => 0,
                    'group_no' => 0,
                ),
            ),
        ),
        'options' => array (
            'position' => 'normal',
            'layout' => 'no_box',
            'hide_on_screen' => array (
            ),
        ),
        'menu_order' => 0,
    ));
}

?>