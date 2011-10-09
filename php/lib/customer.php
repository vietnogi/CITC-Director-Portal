<?
class Customer {
	
	public $meta = array();
	public $id = NULL;
	
	public function __construct ($userid) {
		if (!is_numeric($userid)) {
			trigger_error('$userid is not numeric', E_USER_ERROR);
		}
		
		// set meta
		$query = 'SELECT customer.*
				  FROM customer
				  INNER JOIN user ON customer.user_id = user.user_id
				  WHERE user.user_id = :user_id';
		$values = array(':user_id' => $userid);
		$this->meta = $GLOBALS['mysql']->getSingle($query, $values);
		if (!empty($this->meta)) {
			$this->id = $this->meta['customer_id'];
		}
	}
	
	// get payment total
	public function paymentTotal () {
		$query = 'SELECT IFNULL(SUM(customer_payment.amount), 0) AS total
				  FROM customer_payment
				  WHERE customer_payment.customer_id = :customer_id AND customer_payment.status = "Completed"';
		
		$values = array(':customer_id' => $this->id);
		$paymentTotal = $GLOBALS['mysql']->getSingle($query, $values);
		
		return $paymentTotal['total'];
	}
	
	// get invoice total
	public function invoiceTotal () {
		$query = 'SELECT IFNULL(SUM(customer_invoice_item.amount), 0) AS total
				  FROM customer_invoice 
				  INNER JOIN customer_invoice_item ON customer_invoice.customer_invoice_id = customer_invoice_item.customer_invoice_id
				  WHERE customer_invoice.customer_id = :customer_id';
		
		$values = array(':customer_id' => $this->id);
		$invoiceTotal = $GLOBALS['mysql']->getSingle($query, $values);
		
		return $invoiceTotal['total'];	
	}
}