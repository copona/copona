--
-- Table structure for table `oc_product_to_product`
--
CREATE TABLE `oc_product_to_product` (
`product_group_id` INT(55) NOT NULL AUTO_INCREMENT,
`product_id` INT(55) NOT NULL,
`default_id` TINYINT NOT NULL DEFAULT '0',
PRIMARY KEY (`product_group_id`, `product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;