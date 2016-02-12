<?php class ModelSaleSerialkeys extends Model {
	public function getSerialkeys($order_id) {
		
		$query = $this->db->query("SELECT o.order_id, o.date_added, od.order_serialkey_id, od.productname, od.serialkey FROM " . DB_PREFIX . "order_serialkey od LEFT JOIN `" . DB_PREFIX . "order` o ON od.order_id = o.order_id WHERE o.order_id = '" . (int)$order_id . "'  ORDER BY od.order_serialkey_id ");
	
		return $query->rows;
	}
	
	public function updateRemaining($order_download_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "order_serialkey SET remaining = (remaining - 1) WHERE order_download_id = '" . (int)$order_download_id . "'");
	}
	
	public function getTotalSerialkeys() {
		$complete_status = $this->config->get('config_complete_status');
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_serialkey od LEFT JOIN `" . DB_PREFIX . "order` o ON (od.order_id = o.order_id) WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id > '0' AND o.order_status_id ='" . $complete_status['0'] . "'");
		
		return $query->row['total'];
	}	

}
?>