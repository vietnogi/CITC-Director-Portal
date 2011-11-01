<?
class ParentClass {
	
	public $meta = array();
	public $id = NULL;
	
	public function __construct ($userid) {
		if (!is_numeric($userid)) {
			trigger_error('$userid is not numeric', E_USER_ERROR);
		}
		
		// set meta
		$query = 'SELECT parent.*
				  FROM parent
				  INNER JOIN user ON parent.user_id = user.user_id
				  WHERE user.user_id = :user_id';
		$values = array(':user_id' => $userid);
		$this->meta = $GLOBALS['mysql']->getSingle($query, $values);
		if (!empty($this->meta)) {
			$this->id = $this->meta['parent_id'];
		}
	}
	
	// get payment total
	public function paymentTotal () {
		$query = 'SELECT IFNULL(SUM(parent_payment.amount), 0) AS total
				  FROM parent_payment
				  WHERE parent_payment.parent_id = :parent_id AND parent_payment.status = "Completed"';
		
		$values = array(':parent_id' => $this->id);
		$paymentTotal = $GLOBALS['mysql']->getSingle($query, $values);
		
		return $paymentTotal['total'];
	}
	
	// get invoice total
	public function invoiceTotal () {
		$query = 'SELECT IFNULL(SUM(parent_invoice_item.amount), 0) AS total
				  FROM parent_invoice 
				  INNER JOIN parent_invoice_item ON parent_invoice.parent_invoice_id = parent_invoice_item.parent_invoice_id
				  WHERE parent_invoice.parent_id = :parent_id';
		
		$values = array(':parent_id' => $this->id);
		$invoiceTotal = $GLOBALS['mysql']->getSingle($query, $values);
		
		return $invoiceTotal['total'];	
	}
}
?>