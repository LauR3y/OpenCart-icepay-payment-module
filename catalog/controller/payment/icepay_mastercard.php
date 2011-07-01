<?php
require_once "abstract/icepay.php";

class ControllerPaymentIcepayMastercard extends ControllerPaymentAbstractIcepay {
	

	public function __construct($registry)
	{
		parent::__construct($registry);
		$this->setType(basename(__FILE__));
	}
	
	protected function index($url = null) {
		$icepay = new ICEPAY_CC($this->merchant_id, $this->secretCode);
		$icepay->SetOrderID($this->order_id);
		$url = $icepay->pay("MASTER", $this->languageCode, $this->currencyCode, $this->total, $this->comment);
		
		parent::index($url);
	}
}
?>