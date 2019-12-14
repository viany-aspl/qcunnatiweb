<?php
class ModelPosTax extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$log=new Log("tax.log");



		foreach ($taxes as $key => $value) {
					
			if ($value > 0) {
				
				$log->write($key);

				$total_data[] = array(
					'code'       => 'tax',
					'title'      => $this->tax->getRateName($key), 
					'text'       => $this->currency->format($value),
					'value'      => $value,
					'sort_order' => $this->config->get('tax_sort_order')
				);

				$total += $value;
			}
		}
	}
}
?>