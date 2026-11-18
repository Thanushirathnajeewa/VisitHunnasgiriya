<?php
$cfg = [
  'title' => 'Travel & safety tips', 'sub' => 'Tips shown on each place page',
  'table' => 'travel_tips', 'pk' => 'tip_id',
  'fields' => [
    'attraction_id'   => ['label' => 'Place', 'type' => 'select', 'required' => true, 'sql' => "SELECT attraction_id, name FROM attractions ORDER BY name"],
    'tip_type'        => ['label' => 'Type', 'type' => 'select', 'required' => true, 'options' => ['Travel' => 'Travel', 'Safety' => 'Safety']],
    'tip_description' => ['label' => 'Tip', 'type' => 'textarea', 'required' => true],
  ],
  'list_sql' => "SELECT t.tip_id AS id, a.name AS place, t.tip_type AS type, t.tip_description AS tip
                 FROM travel_tips t JOIN attractions a ON a.attraction_id = t.attraction_id ORDER BY a.name, t.tip_type, t.tip_id",
  'columns' => ['place' => 'Place', 'type' => 'Type', 'tip' => 'Tip'],
];
require __DIR__ . '/../includes/crud.php';
