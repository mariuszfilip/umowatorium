<?php
return array(
    'modules' => array(
        'Application',
        'Auth'
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            'apps',
            'vendor'
        ),
    ),
);
