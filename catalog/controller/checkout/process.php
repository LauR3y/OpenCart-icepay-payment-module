<?php
class ControllerCheckoutProcess extends Controller { 
	public function index() {
		
		$log = new Log('logControllerCheckoutProcess.txt');
		$log->write("PROCESS ORDER:BEGIN");
					
		$merchantID = $this->config->get('icepay_merchantID');
		$secretCode = $this->config->get('icepay_secret');
		
		include('system/library/icepay/icepay.php');
		
		$icepay = new ICEPAY($merchantID, $secretCode);
		if ( $icepay->OnPostback() )
		{
			$data = $icepay->GetPostback();
			$log->write(print_r($data, true));
			$order_id = $data->orderID;
			$log->write("orderID: $order_id");
			switch ( strtoupper($data->status) )
			{
				case "OK": // Successful payment, processed(15)
					$status_id = 15;
					break;
				case "OPEN": // Payment is not yet completed, processing(2)
					$status_id = 2;
					break;
				case "ERR": // Error happened, failed(10)
					$status_id = 10;
					break;
				case "REFUND": // Merchant did a refund, Refunded(11)
					$status_id = 11;
					break;
				case "CBACK": // Charge back by end-user, Chargeback(13)
					$status_id = 13;
					break;
			}
			$log->write("statusID: $status_id");
			$sql = "UPDATE `" . DB_PREFIX . "order` SET order_status_id = ".$status_id." WHERE order_id = " . (int) $order_id;

			$query = $this->db->query($sql);
			$log->write("PROCESS ORDER:END");
		}
		
	}
}