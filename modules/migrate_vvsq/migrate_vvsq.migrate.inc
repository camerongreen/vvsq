<?php

function migrate_vvsq_migrate_api() {
  return array(
    'api' => 2,
    'groups' => array(
      'vvsq' => array(
        'title' => t('VVSQ Migrations'),
        'default_format' => "full_html",
      ),
    ),
    'migrations' => array(
      'VvsqProducts' => array(
        'class_name' => 'VvsqProductsMigration',
        'group_name' => 'vvsq',
      ),
    ),
  );
}