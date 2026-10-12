-- Sets the admin password to:  Admin@12345
-- Log in at login.php with admin@gmail.com, then change it under Admin > Password.
UPDATE `admins` SET `password` = '$2y$10$PUjAKUf8cILNskZEip8mAOuT4uUXKpnDgrxlYOX/HuXCjGeoMw9wW' WHERE `email` = 'admin@gmail.com';
