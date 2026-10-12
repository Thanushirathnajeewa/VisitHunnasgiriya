-- Lets visitors save day plans under their own account.
-- Run once in phpMyAdmin: database `visithunnasgiriya` > SQL tab > Go.
ALTER TABLE `itineraries`
  ADD COLUMN `user_id` INT NULL AFTER `itinerary_id`,
  ADD KEY `user_id` (`user_id`);
