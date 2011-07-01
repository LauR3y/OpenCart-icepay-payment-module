<?php 
abstract class ModelPaymentAbstractIcepay extends Model {
	
	protected function setType($filename)
	{
		$matches = array();
		preg_match('/icepay_(.*)\.php/', $filename, $matches);
		$this->type = $matches[1];
	}
	
  	public function getMethod($address, $total) {
		$this->load->language("payment/icepay_{$this->type}");
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('icepay_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
	
		if ($this->config->get('icepay_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('icepay_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => "icepay_{$this->type}",
        		'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get("icepay_{$this->type}_sort_order")
      		);
    	}
   
    	return $method_data;
  	}
}
?>