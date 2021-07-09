<?php

class Products_model extends CI_Model
{
	const TABLE = 'products';

	public function get_all_products()
	{
		return $this->db->get(self::TABLE)->result_array();
	}
}
