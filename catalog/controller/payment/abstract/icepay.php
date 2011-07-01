<?php
require_once('system/library/icepay/icepay.php');

abstract class ControllerPaymentAbstractIcepay extends Controller {
	protected $type;
	protected $order_id;
	protected $total;
	protected $countryCode;
	protected $languageCode;
	protected $currencyCode;
	protected $comment;
	protected $merchant_id;
	protected $secretCode;
	
	public function __construct($registry) {
		parent::__construct($registry);
		
    	$this->data['button_confirm'] = $this->language->get('button_confirm');
		
		$this->order_id = $this->session->data['order_id'];
		$order_info = $this->model_checkout_order->getOrder($this->order_id);
		$this->total = (int)($order_info['total']*100); // in cents
		$this->countryCode = $order_info['payment_iso_code_2'];
		$this->languageCode = $order_info['language_code'];
		$this->currencyCode = $order_info['currency_code'];
		$this->comment = $order_info['comment'];
		
		$this->merchant_id = $this->config->get('icepay_merchantID');
		$this->secretCode = $this->config->get('icepay_secret');
		
	}
	
	protected function setType($filename)
	{
		$matches = array();
		preg_match('/icepay_(.*)\.php/', $filename, $matches);
		$this->type = $matches[1];
	}
	
	protected function index($url)
	{
		$this->data['continue'] = $url;
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/icepay_wire.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/icepay_wire.tpl';
		} else {
			$this->template = 'default/template/payment/icepay_wire.tpl';
		}	
		
		$this->render();
	}
	
	public function confirm() {
		$this->load->model('checkout/order');
		
		print_r($this->config->get('icepay_order_status_id'));
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('icepay_order_status_id'));
	}
}
?>