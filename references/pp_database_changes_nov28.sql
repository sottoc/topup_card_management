ALTER TABLE `tbl_participant` CHANGE `allow_preorder` `allow_canteen_order` TINYINT(3) NOT NULL DEFAULT '1' COMMENT '1- preorder , 0=self order';
ALTER TABLE `tbl_participant` CHANGE `allow_canteen_order` `allow_canteen_order` TINYINT(3) NOT NULL DEFAULT '1' COMMENT '1= allow canteen order , 0= not allow canteen order';


ALTER TABLE `tbl_participant_food_allergy` DROP FOREIGN KEY `tbl_participant_food_allergy_ibfk_1`;
ALTER TABLE `tbl_participant_food_allergy` ADD `predefine_participant_id` INT(11) NOT NULL AFTER `food_allergy_id`;
ALTER TABLE `tbl_participant_food_allergy` CHANGE `participant_id` `participant_id` INT(11) NULL;


ALTER TABLE `tbl_student_predefine` ADD `student_allergy_flag` TINYINT(3) NOT NULL AFTER `predefine_parent_name`, ADD `others_allergy_food_description` TEXT NOT NULL AFTER `student_allergy_flag`, ADD `student_description` TEXT NOT NULL AFTER `others_allergy_food_description`, ADD `student_gender_id` INT(11) NOT NULL AFTER `student_description`, ADD `allow_canteen_order` TINYINT(3) NOT NULL AFTER `student_gender_id`, ADD `created` DATETIME NOT NULL AFTER `allow_canteen_order`, ADD `modified` DATETIME NOT NULL AFTER `created`, ADD `upload_file` VARCHAR(100) NOT NULL AFTER `modified`;
ALTER TABLE `tbl_student_predefine` CHANGE `upload_file` `upload_file` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;

ALTER TABLE `tbl_participant` ADD `upload_file` VARCHAR(100) NULL AFTER `allow_canteen_order`;

ALTER TABLE `tbl_student_predefine` ADD `student_class` VARCHAR(50) NOT NULL AFTER `predefine_org_id`;