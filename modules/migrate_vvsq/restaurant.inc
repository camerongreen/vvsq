<?php

class VvsqRestaurantsMigration extends Migration {
  const default_uid = 38; // Dark Horse
  public function __construct() {
    parent::__construct();

    $query = Database::getConnection('default', 'vvsq_import')
      ->select('restaurants', 'r')
      ->fields('r', array(
        'restaurantID',
        'name',
        'area',
        'address',
        'phone_number',
        'vegetarian',
        'alcohol',
        'some_vegetarian',
        'dateentered',
        'URL',
        'state_name',
        'approved',
      ));

    $this->source = new MigrateSourceSQL($query);
    $this->destination = new MigrateDestinationNode('restaurant');
    $this->map = new MigrateSQLMap($this->machineName,
      array(
        'restaurantID' => array(
          'type' => 'int',
          'unsigned' => TRUE,
          'not null' => TRUE,
        )
      ),
      MigrateDestinationNode::getKeySchema()
    );
    $this->addFieldMapping('title', 'name');
    $this->addFieldMapping('body', '');
    $this->addFieldMapping('created', 'dateentered')->callbacks(array($this, 'convertToTimestamp'));
    $this->addFieldMapping('field_area', 'area');
    $this->addFieldMapping('uid')->defaultValue(self::default_uid);
    $this->addFieldMapping('field_address', 'address');
    $this->addFieldMapping('field_phone_number', 'phone_number');
    $this->addFieldMapping('field_alcohol', 'alcohol');
    $this->addFieldMapping('field_vegetarian_vegan', 'vegetarian')->callbacks(array($this, 'setType'));
    $this->addFieldMapping('field_website', 'URL');
    $this->addFieldMapping('field_state', 'state_name');
    $this->addFieldMapping('field_approved', 'approved')->callbacks(array($this, 'setApproved'));
  }

  protected function convertToTimestamp($str) {
    return strtotime($str);
  }

  protected function setApproved($approved) {
    return $approved;
  }

  protected function setType($type) {
    if ($type == 1) {
      return "vegetarian";
    }

    return "mixed";
  }
}