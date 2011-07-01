<?php 
abstract class ControllerPaymentAbstractIcepay extends Controller {
	private $error = array();
	protected $type;
	
	public function setType($filename)
	{
		$matches = array();
		preg_match('/icepay_(.*)\.php/', $filename, $matches);
		$this->type = $matches[1];
	}
	
	public function index() {
		$this->load->language('payment/icepay');
		$this->load->language("payment/icepay_{$this->type}");

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting("icepay_{$this->type}", $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
				
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_merchantID'] = $this->language->get('entry_merchantID');
		$this->data['entry_secret'] = $this->language->get('entry_secret');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link("payment/icepay_{$this->type}", 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link("payment/icepay_{$this->type}", 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');	
		
		if (isset($this->request->post["icepay_total"])) {
			$this->data["icepay_total"] = $this->request->post["icepay_total"];
		} else {
			$this->data["icepay_total"] = $this->config->get("icepay_total"); 
		}
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post["icepay_geo_zone_id"])) {
			$this->data["icepay_geo_zone_id"] = $this->request->post["icepay_geo_zone_id"];
		} else {
			$this->data["icepay_geo_zone_id"] = $this->config->get("icepay_geo_zone_id"); 
		} 
		
		$this->load->model('localisation/geo_zone');						
		
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post["icepay_{$this->type}_status"])) {
			$this->data["icepay_{$this->type}_status"] = $this->request->post["icepay_{$this->type}_status"];
		} else {
			$this->data["icepay_{$this->type}_status"] = $this->config->get("icepay_{$this->type}_status");
		}
		
		if (isset($this->request->post["icepay_{$this->type}_sort_order"])) {
			$this->data["icepay_{$this->type}_sort_order"] = $this->request->post["icepay_{$this->type}_sort_order"];
		} else {
			$this->data["icepay_{$this->type}_sort_order"] = $this->config->get("icepay_{$this->type}_sort_order");
		}
		
		if (isset($this->request->post['icepay_merchantID'])) {
			$this->data['icepay_merchantID'] = $this->request->post['icepay_merchantID'];
		} else {
			$this->data['icepay_merchantID'] = $this->config->get('icepay_merchantID');
		}
		
		if (isset($this->request->post['icepay_secret'])) {
			$this->data['icepay_secret'] = $this->request->post['icepay_secret'];
		} else {
			$this->data['icepay_secret'] = $this->config->get('icepay_secret');
		}
		
		$this->data['type'] = $this->type;

		$this->template = "payment/icepay.tpl";
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}
	
	public function getStatus()
	{
		return $this->data["icepay_{$this->type}_status"];
	}
	
	public function getSortOrder()
	{
		return $this->data["icepay_{$this->type}_sort_order"];
	}
	
	public function getType()
	{
		return $this->type;
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', "payment/icepay_{$this->type}")) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
				
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>