<?php
// Include on every admin page. Sends anyone who is not an admin to the login page.
require_once __DIR__ . '/functions.php';
function require_admin(): void {
    if (!is_admin()) redirect('../login.php');
}
