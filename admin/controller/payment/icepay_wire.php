<?php
require_once 'abstract/icepay.php';

class ControllerPaymentIcepayWire extends ControllerPaymentAbstractIcepay {
	public function __construct($registry)
	{
		parent::__construct($registry);
		$this->setType(basename(__FILE__));
	}
}