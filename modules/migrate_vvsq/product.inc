<?php

class VvsqProductsMigration extends Migration {
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
    $this->addFieldMapping('Catalog', 'resource_type')->arguments(array("source_type" => "tid"));
  }

  protected function convertToTimestamp($str) {
    return strtotime($str);
  }
}