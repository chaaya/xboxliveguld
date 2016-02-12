<?php
class ModelAccountSerialkeys extends Model {

    public function getSerialkeys($start = 0, $limit = 20) {
        if ($start < 0) {
            $start = 0;
        }
        $lang = $this->config->get('config_language_id');

    $conf_arr = $this->config->get('config_complete_status');
    $config_complete_status = "";
    $config_complete_status .= $conf_arr[0];
    foreach ( $conf_arr as $confs ) {
        if ( $confs != $conf_arr[0] ) {
            $config_complete_status .= "," . $confs;
        }
    }
			

        $sql = "SELECT o.order_id, o.date_added, od.order_serialkey_id, od.productname, od.serialkey,
		(SELECT downloadlink FROM `" . DB_PREFIX . "order_downloadlink` WHERE language_id = '".$lang."' AND order_product_id = od.order_product_id AND order_id= o.order_id) AS downloadlink,
		(SELECT article_id FROM `" . DB_PREFIX . "product_article` WHERE product_id = od.order_product_id) AS information_id,
		(SELECT dd.title FROM `" . DB_PREFIX . "product_article` pa LEFT JOIN `" . DB_PREFIX . "information_description` dd ON dd.information_id = pa.article_id WHERE pa.product_id = od.order_product_id  AND dd.language_id='".$lang."') AS information_title,
		(SELECT dd.keyword FROM `" . DB_PREFIX . "product_article` pa LEFT JOIN `" . DB_PREFIX . "url_alias` dd ON dd.query = CONCAT('information_id=',pa.article_id) WHERE pa.product_id = od.order_product_id ) AS information_link

		FROM "
            . DB_PREFIX . "order_serialkey od
			LEFT JOIN `" . DB_PREFIX . "order` o ON od.order_id = o.order_id
		WHERE
			
	o.customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id IN(" . $config_complete_status . ") ORDER BY o.date_added DESC LIMIT " . (int)$start . "," . (int)$limit;
			


        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function updateRemaining($order_download_id) {
        $this->db->query("UPDATE " . DB_PREFIX . "order_serialkey SET remaining = (remaining - 1) WHERE order_download_id = '" . (int)$order_download_id . "'");
    }

    public function getTotalSerialkeys() {
        $sql="SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_serialkey od LEFT JOIN `" . DB_PREFIX . "order` o ON (od.order_id = o.order_id) WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id = '" . $this->config->get('config_complete_status')[0] . "'";
        $query = $this->db->query($sql);

        return $query->row['total'];
    }
}
?>