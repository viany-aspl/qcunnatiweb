<?php
class ControllerCatalogQuantitymatch extends Controller {
	private $error = array();

	public function index() {
		

		$this->document->setTitle('Quantity Mismatch Check');

		$this->load->model('pos/inventory_update');

		
		$category_total = $this->model_pos_inventory_update->getandupdatequantity();

	}
	
}