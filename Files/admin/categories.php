<?php
$cfg = [
  'title' => 'Categories', 'sub' => 'Groups used to filter places on the home page',
  'table' => 'categories', 'pk' => 'category_id',
  'fields' => ['category_name' => ['label' => 'Category name', 'required' => true, 'max' => 100]],
  'list_sql' => "SELECT c.category_id AS id, c.category_name AS name,
                   (SELECT COUNT(*) FROM attractions a WHERE a.category_id = c.category_id) AS places
                 FROM categories c ORDER BY c.category_name",
  'columns' => ['name' => 'Category', 'places' => 'Places'],
  'delete_check' => ['sql' => "SELECT COUNT(*) FROM attractions WHERE category_id = ?",
                     'msg' => "This category still has places. Move or delete those places first."],
];
require __DIR__ . '/../includes/crud.php';
