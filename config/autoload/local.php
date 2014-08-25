<?php
/**
 * Local Configuration Override
 *
 * This configuration override file is for overriding environment-specific and
 * security-sensitive configuration information. Copy this file without the
 * .dist extension at the end and populate values as needed.
 *
 * @NOTE: This file is ignored from Git by default with the .gitignore included
 * in ZendSkeletonApplication. This is a good practice, as it prevents sensitive
 * credentials from accidentally being comitted into version control.
 */

return array(
    'db' => array(
        'driver' => 'Pdo',
        'dsn'            => 'mysql:dbname=anialuniewsk_1;host=sql.anialuniewsk.nazwa.pl',
        'username'       => 'anialuniewsk_1',
        'password'       => '9k9jR_BZ1',
        'port'           => '3307',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
);
