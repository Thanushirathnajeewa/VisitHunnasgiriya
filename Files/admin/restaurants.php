<?php
$cfg = [
  'title' => 'Restaurants & cafés', 'sub' => 'Places to eat near each attraction',
  'table' => 'restaurants', 'pk' => 'restaurant_id',
  'fields' => [
    'attraction_id'      => ['label' => 'Near place', 'type' => 'select', 'sql' => "SELECT attraction_id, name FROM attractions ORDER BY name"],
    'name'               => ['label' => 'Name', 'required' => true, 'max' => 150],
    'location'           => ['label' => 'Location', 'max' => 255],
    'estimated_spending' => ['label' => 'Estimated spending (Rs.)', 'type' => 'number'],
    'description'        => ['label' => 'Description / contact', 'type' => 'textarea'],
  ],
  'list_sql' => "SELECT r.restaurant_id AS id, r.name, COALESCE(a.name, '-') AS place, r.location, r.estimated_spending AS spending
                 FROM restaurants r LEFT JOIN attractions a ON a.attraction_id = r.attraction_id ORDER BY r.name",
  'columns' => ['name' => 'Name', 'place' => 'Near', 'location' => 'Location', 'spending' => 'Rs.'],
];
require __DIR__ . '/../includes/crud.php';
