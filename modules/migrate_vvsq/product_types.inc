<?php

class VvsqProductTypesMigration extends Migration {
  public function __construct() {
    parent::__construct();

    $query = Database::getConnection('default', 'vvsq_import')
      ->select('Resources', 'r')
      ->fields('r', array(
        'resource_type'
      ))
      ->distinct();

    $this->source = new MigrateSourceSQL($query);
    $this->destination = new MigrateDestinationTerm('catalog');
    $this->map = new MigrateSQLMap($this->machineName,
      array(
        'resource_type' => array(
          'type' => 'varchar',
          'length' => 255,
        )
      ),
      MigrateDestinationNode::getKeySchema()
    );
    $this->addFieldMapping('name', 'resource_type');
  }
}