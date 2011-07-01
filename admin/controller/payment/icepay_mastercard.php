<?php
require_once 'abstract/icepay.php';

class ControllerPaymentIcepayMastercard extends ControllerPaymentAbstractIcepay {
	public function __construct($registry)
	{
		parent::__construct($registry);
		$this->setType(basename(__FILE__));
	}
}