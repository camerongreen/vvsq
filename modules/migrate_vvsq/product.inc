<?php

class VvsqProductsMigration extends Migration {
  const default_uid = 38; // Dark Horse
  public function __construct() {
    parent::__construct();

    $query = Database::getConnection('default', 'vvsq_import')
      ->select('Resources', 'r')
      ->fields('r', array(
        'Resource_ID',
        'Resouce_Title',
        'Resource_Description',
        'Resource_Add_Date',
        'resource_type',
        'picture',
      ));

    $this->source = new MigrateSourceSQL($query);
    $this->destination = new MigrateDestinationNode('product');
    $this->map = new MigrateSQLMap($this->machineName,
      array(
        'Resource_ID' => array(
          'type' => 'int',
          'unsigned' => TRUE,
          'not null' => TRUE,
        )
      ),
      MigrateDestinationNode::getKeySchema()
    );
    $this->addFieldMapping('title', 'Resouce_Title');
    $this->addFieldMapping('body', 'Resource_Description');
    $this->addFieldMapping('created', 'Resource_Add_Date')->callbacks(array($this, 'convertToTimestamp'));
    $this->addFieldMapping('updated', 'Resource_Add_Date')->callbacks(array($this, 'convertToTimestamp'));
    $this->addFieldMapping('taxonomy_catalog', 'resource_type');
    $this->addFieldMapping('uid')->defaultValue(self::default_uid);
  }

  protected function convertToTimestamp($str) {
    return strtotime($str);
  }
}