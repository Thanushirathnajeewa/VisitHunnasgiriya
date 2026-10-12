<?php
$cfg = [
  'title' => 'Emergency contacts', 'sub' => 'Numbers shown on the visitor Emergency page',
  'table' => 'emergency_contacts', 'pk' => 'contact_id',
  'fields' => [
    'service_name'   => ['label' => 'Service name', 'required' => true, 'max' => 150],
    'contact_number' => ['label' => 'Phone number(s)', 'required' => true, 'max' => 30],
    'description'    => ['label' => 'Description', 'type' => 'textarea'],
  ],
  'list_sql' => "SELECT contact_id AS id, service_name AS service, contact_number AS number FROM emergency_contacts ORDER BY service_name",
  'columns' => ['service' => 'Service', 'number' => 'Phone'],
];
require __DIR__ . '/../includes/crud.php';
