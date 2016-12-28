<?php
class ModelCatalogUpgrade extends Model {

	public function upgrade() {


		/* Columns check */
		$db_check = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "banner_image LIKE 'sort_order'");
		if (!$db_check->num_rows) {
			// $this->db->query("ALTER TABLE `" . DB_PREFIX . "banner_image` ADD  `sort_order` int(3) NOT NULL DEFAULT '0' AFTER `image`;");
		}

		/* Table check */
		$db_check = $this->db->query("SELECT * FROM information_schema.tables WHERE table_schema = '" . DB_DATABASE . "' "
			. "AND table_name = '" . DB_PREFIX . "product_to_product' LIMIT 1");
		if (!$db_check->num_rows) {
			$this->db->query("CREATE TABLE `" . DB_PREFIX . "product_to_product` (
				`product_group_id` INT(55) NOT NULL AUTO_INCREMENT,
				`product_id` INT(55) NOT NULL,
				`default_id` TINYINT NOT NULL DEFAULT '0',
				PRIMARY KEY (`product_group_id`, `product_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
		}

		// Language settings to Code
		$this->db->query("update " . DB_PREFIX . "setting set `value` =  LEFT(`value`, 2) "
			. "where length(`value`) > 2 AND ( `key` = 'config_admin_language' "
			. "OR `key` = 'config_language')  ");
		return true;
	}

}