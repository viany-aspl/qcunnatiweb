<?php
class ControllerCommonMenu extends Controller 
{
	public function index() 
	{
		$this->load->language('common/menu');
		
		
		$this->load->model('user/user');
	
			$this->load->model('tool/image');
	

			$user_info = $this->model_user_user->getUser($this->user->getId());
	
			if ($user_info) {
				$data['firstname'] = $user_info['firstname'];
				$data['lastname'] = $user_info['lastname'];
	
				$data['user_group'] = $user_info['user_group'];
	
				if (is_file(DIR_IMAGE . $user_info['image'])) {
					$data['image'] = $this->model_tool_image->resize($user_info['image'], 45, 45);
				} else {
					$data['image'] = '';
				}
			} else {
				$data['firstname'] = '';
				$data['lastname'] = '';
				$data['user_group'] = '';
				$data['image'] = '';
			}			

			// Create a 3 level menu array
			// Level 2 can not have children
			
			$data['token'] = $this->session->data['token'];
			// Menu

				if($data['user_group']=='Administrator'){

			$data['menus'][] = array(
				'id'       => 'menu-dashboard',
				'icon'	   => 'fa-dashboard',
				'name'	   => $this->language->get('text_dashboard'),
				'href'     => $this->url->link('common/dashboard', 'token=' . $this->session->data['token']."&a=1", true),
				'children' => array()
			);

}else{

			if ($this->user->hasPermission('access', 'common/dashboard')) {
			$data['menus'][] = array(
				'id'       => 'menu-dashboard',
				'icon'	   => 'fa-dashboard',
				'name'	   => $this->language->get('text_dashboard'),
				'href'     => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
				'children' => array()
			);
			}
}

//////////////////////////////////
$tagpos=array();
if ($this->user->hasPermission('access', 'tagpos/tagpos')) {
$tagpos[] = array(
'name' => 'Tag POS',
'href' => $this->url->link('tagpos/tagpos', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}

if ($this->user->hasPermission('access', 'tagpos/fmdelivery')) {
$tagpos[] = array(
'name' => 'FM Delivery',
'href' => $this->url->link('tagpos/fmdelivery', 'token=' . $this->session->data['token'], true),
'children' => array()
);

}
if ($this->user->hasPermission('access', 'tagpos/fmdelivery')) {
$tagpos[] = array(
'name' => 'Product Sale Fm Wise',
'href' => $this->url->link('tagpos/fmdelivery/productsalefmwise', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}

if ($this->user->hasPermission('access', 'tagpos/fmdelivery')) {
$tagpos[] = array(
'name' => 'Product Sale Fm Wise (Cash)',
'href' => $this->url->link('tagpos/fmdelivery/productsalefmwisecash', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}

if ($this->user->hasPermission('access', 'report/sale_summary')) {
$tagpos[] = array(
'name' => 'FM Cash Sale',
'href' => $this->url->link('report/sale_summary/fm_cash_sale_report', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}
if ($this->user->hasPermission('access', 'tagpos/tagpos')) {
$tagpos[] = array(
'name' => 'Batch',
'href' => $this->url->link('tagpos/fmdelivery/getbatch', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}
if ($tagpos) {
$data['menus'][] = array(
'id' => 'menu-catalog',
'icon' => 'fa-book',
'name' => 'Tag POS',
'href' => '',
'children' => $tagpos
);
}

// Card
$card = array();
if ($this->user->hasPermission('access', 'farmerrequest/farmerrequest')) {
$card[] = array(
'name' => 'Card Request',
'href' => $this->url->link('farmerrequest/farmerrequest', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}

if ($this->user->hasPermission('access', 'farmerrequest/reviewform')) {
$card[] = array(
'name' => 'Card Review',
'href' => $this->url->link('farmerrequest/reviewform', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}
if ($this->user->hasPermission('access', 'farmerrequest/approveform')) {
$card[] = array(
'name' => 'Card Approval',
'href' => $this->url->link('farmerrequest/approveform', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}
if ($this->user->hasPermission('access', 'farmerrequest/cardprint')) {
$card[] = array(
'name' => 'Card Print',
'href' => $this->url->link('farmerrequest/cardprint', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}
if ($this->user->hasPermission('access', 'farmerrequest/managercardprint')) {
$card[] = array(
'name' => 'Card Re-Print',
'href' => $this->url->link('farmerrequest/managercardprint', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}
if ($this->user->hasPermission('access', 'farmerrequest/cardprinted')) {
$card[] = array(
'name' => 'Card Printed',
'href' => $this->url->link('farmerrequest/cardprinted', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}
if ($this->user->hasPermission('access', 'farmerrequest/cardprint')) {
$card[] = array(
'name' => 'Re-issue Card',
'href' => $this->url->link('farmerrequest/reissuecard', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}
if ($this->user->hasPermission('access', 'farmerrequest/cardstatus')) {
$card[] = array(
'name' => 'Card Check',
'href' => $this->url->link('farmerrequest/cardstatus', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}
if ($this->user->hasPermission('access', 'farmerrequest/carddetail')) {
$card[] = array(
'name' => 'Card Detail',
'href' => $this->url->link('farmerrequest/carddetail', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}
if ($this->user->hasPermission('access', 'farmerrequest/dsclcardsummaryreport')) {
$card[] = array(
'name' => 'DSCL Card Summary',
'href' => $this->url->link('farmerrequest/dsclcardsummaryreport', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}
if ($this->user->hasPermission('access', 'farmerrequest/cardsummaryreport')) {
$card[] = array(
'name' => 'Card Summary',
'href' => $this->url->link('farmerrequest/cardsummaryreport', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}
if ($this->user->hasPermission('access', 'report/cardhistory')) {
$card[] = array(
'name' => "Card's Order History",
'href' => $this->url->link('report/cardhistory', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}

if ($card) {
$data['menus'][] = array(
'id' => 'menu-catalog',
'icon' => 'fa-credit-card',
'name' => 'Card',
'href' => '',
'children' => $card
);
}

//end card







			// Catalog
			$catalog = array();
			
			if ($this->user->hasPermission('access', 'catalog/category')) {
				$catalog[] = array(
					'name'	   => $this->language->get('text_category'),
					'href'     => $this->url->link('catalog/category', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			if ($this->user->hasPermission('access', 'catalog/sub_category')) {
				$catalog[] = array(
					'name'	   => 'Sub Categories', 
					'href'     => $this->url->link('catalog/sub_category', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			if(($data['user_group']!="Customer_care") && ($data['user_group']!="BCML Reports") && ($data['user_group']!="BCML Reconciliation"))
            {
			if ($this->user->hasPermission('access', 'catalog/product')) {
				$catalog[] = array(
					'name'	   => $this->language->get('text_product'),
					'href'     => $this->url->link('catalog/product', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			/*
			if ($this->user->hasPermission('access', 'catalog/product')) {
				$catalog[] = array(
					'name'	   => 'Products Barred',
					'href'     => $this->url->link('catalog/product/productbarred', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			*/
			if ($this->user->hasPermission('access', 'isec/isecproduct')) {
				$catalog[] = array(
					'name'	   => 'ISEC Products',
					'href'     => $this->url->link('isec/isecproduct', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			if ($this->user->hasPermission('access', 'catalog/hsn')) {
				$catalog[] = array(
					'name'	   => 'Products HSN',
					'href'     => $this->url->link('catalog/hsn', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			}
			if ($this->user->hasPermission('access', 'catalog/recurring')) {
				$catalog[] = array(
					'name'	   => $this->language->get('text_recurring'),
					'href'     => $this->url->link('catalog/recurring', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'catalog/filter')) {
				$catalog[] = array(
					'name'	   => $this->language->get('text_filter'),
					'href'     => $this->url->link('catalog/filter', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			// Attributes
			$attribute = array();
			
			if ($this->user->hasPermission('access', 'catalog/attribute')) {
				$attribute[] = array(
					'name'     => $this->language->get('text_attribute'),
					'href'     => $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
			
			if ($this->user->hasPermission('access', 'catalog/attribute_group')) {
				$attribute[] = array(
					'name'	   => $this->language->get('text_attribute_group'),
					'href'     => $this->url->link('catalog/attribute_group', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($attribute) {
				$catalog[] = array(
					'name'	   => $this->language->get('text_attribute'),
					'href'     => '',
					'children' => $attribute
				);
			}
			
			if ($this->user->hasPermission('access', 'catalog/option')) {
				$catalog[] = array(
					'name'	   => $this->language->get('text_option'),
					'href'     => $this->url->link('catalog/option', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'catalog/manufacturer')) {
				$catalog[] = array(
					'name'	   => $this->language->get('text_manufacturer'),
					'href'     => $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'catalog/download')) {
				$catalog[] = array(
					'name'	   => $this->language->get('text_download'),
					'href'     => $this->url->link('catalog/download', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'catalog/review')) {		
				$catalog[] = array(
					'name'	   => $this->language->get('text_review'),
					'href'     => $this->url->link('catalog/review', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);		
			}
			
			if ($this->user->hasPermission('access', 'catalog/information')) {		
				$catalog[] = array(
					'name'	   => $this->language->get('text_information'),
					'href'     => $this->url->link('catalog/information', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);					
			}
			if($user_info['user_group']!='Regional Manager')
			{
			if ($catalog) {
				$data['menus'][] = array(
					'id'       => 'menu-catalog',
					'icon'	   => 'fa-tags', 
					'name'	   => $this->language->get('text_catalog'),
					'href'     => '',
					'children' => $catalog
				);		
			}
			}
	
			// Extension
			$extension = array();
			/*
			if ($this->user->hasPermission('access', 'extension/store')) {		
				$extension[] = array(
					'name'	   => $this->language->get('text_store'),
					'href'     => $this->url->link('extension/store', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);					
			}
			*/
			if ($this->user->hasPermission('access', 'extension/installer')) {		
				$extension[] = array(
					'name'	   => $this->language->get('text_installer'),
					'href'     => $this->url->link('extension/installer', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);					
			}	
			
			
					
			if ($this->user->hasPermission('access', 'extension/modification')) {
				$extension[] = array(
					'name'	   => $this->language->get('text_modification'),
					'href'     => $this->url->link('extension/modification', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
if ($this->user->hasPermission('access', 'extension/module')) {		
				$extension[] = array(
					'name'	   => $this->language->get('text_module'),
					'href'     => $this->url->link('extension/module', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'extension/shipping')) {
				$extension[] = array(
					'name'	   => $this->language->get('text_shipping'),
					'href'     => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
if ($this->user->hasPermission('access', 'extension/payment')) {
				$extension[] = array(
					'name'	   => $this->language->get('text_payment'),
					'href'     => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
if ($this->user->hasPermission('access', 'extension/total')) {
				$extension[] = array(
					'name'	   => $this->language->get('text_total'),
					'href'     => $this->url->link('extension/total', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}

if ($this->user->hasPermission('access', 'extension/feed')) {
				$extension[] = array(
					'name'	   => $this->language->get('text_feed'),
					'href'     => $this->url->link('extension/feed', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			/*		
			if ($extension) {					
				$data['menus'][] = array(
					'id'       => 'menu-extension',
					'icon'	   => 'fa-puzzle-piece', 
					'name'	   => $this->language->get('text_extension'),
					'href'     => '',
					'children' => $extension
				);		
			}
			*/
/*store inventory request*/			

		/*	$storeinventory=array();
			
			if ($this->user->hasPermission('access', 'inventory/purchase_order')) {
				$storeinventory[] = array(
					'name'	   => $this->language->get('text_purchase_order_inv'),
					'href'     => $this->url->link('inventory/purchase_order', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}

if ($storeinventory) {
				$data['menus'][] = array(
					'id'       => 'storeinventory',
					'icon'	   => 'fa fa-pencil-square-o  fa-fw', 
					'name'	   => 'Center Inventory',
					'href'     => '',
					'children' => $storeinventory
				);
			}*/



/*stock*/
			/*$stockinventory=array();
			
			if ($this->user->hasPermission('access', 'stock/purchase_order')) {
				$stockinventory[] = array(
					'name'	   => 'Stock',
					'href'     => $this->url->link('stock/purchase_order', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}

	if ($stockinventory) {
				$data['menus'][] = array(
					'id'       => 'stockinventory',
					'icon'	   => 'fa fa-pencil-square-o  fa-fw', 
					'name'	   => 'Stock Transfer',
					'href'     => '',
					'children' => $stockinventory
				);
			}*/
/*stock end*/

 $factory = array();
   
   if ($this->user->hasPermission('access', 'factory/paymentdtl')) {
    $factory[] = array(
     'name'    => 'Factory Payment',
     'href'     => $this->url->link('factory/paymentdtl/paymentlist', 'token=' . $this->session->data['token'], true),
     'children' => array()  
    );
   }
   if ($this->user->hasPermission('access', 'factory/adjustment')) {
    $factory[] = array(
     'name'    => 'Bill Submission and Adjustment',
     'href'     => $this->url->link('factory/adjustment', 'token=' . $this->session->data['token'], true),
     'children' => array()  
    );
   }
   if ($factory) {
    $data['menus'][] = array(
     'id'       => 'menu-catalog',
     'icon'    => 'fa-building',
     'name'    => 'Factory',
     'href'     => '',
     'children' => $factory
    );  
   }


			/*Inventory*/
			/*
		
		$data['sale_offer'] = $this->url->link('purchase/sale_offer', 'token=' . $this->session->data['token'],true);*/
			$inventory=array();
			
			
			
			if ($this->user->hasPermission('access', 'purchase/return_orders')) {
				$inventory[] = array(
					'name'	   => 'Material Reversal',
					'href'     => $this->url->link('purchase/return_orders', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			
			/*if ($this->user->hasPermission('access', 'purchase/sale_offer')) {
				$inventory[] = array(
					'name'	   => $this->language->get('sale_offer_text'),
					'href'     => $this->url->link('purchase/sale_offer', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}*/
			
			$supplier=array();
			
			if ($this->user->hasPermission('access', 'purchase/supplier')) {
				$supplier[] = array(
					'name'	   => 'Supplier',
					'href'     => $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			
			if ($this->user->hasPermission('access', 'purchase/supplier_group')) {
				$supplier[] = array(
					'name'	   => 'Supplier Group',
					'href'     => $this->url->link('purchase/supplier_group', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if($supplier)
			{
			$inventory[] = array(
					'name'	   =>'Supplier' ,
					'href'     => '',
					'children' => $supplier
				);	
			}
			$sreport=array();
			
			if ($this->user->hasPermission('access', 'purchase/received_orders')) {
				$sreport[] = array(
					'name'	   => $this->language->get('received_orders'),
					'href'     => $this->url->link('purchase/received_orders', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($this->user->hasPermission('access', 'purchase/pending_orders')) {
				$sreport[] = array(
					'name'	   => $this->language->get('pending_orders'),
					'href'     => $this->url->link('purchase/pending_orders', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			
if ($this->user->hasPermission('access', 'purchase/dead_chart')) {
				$sreport[] = array(
					'name'	   => $this->language->get('dead_chart_text'),
					'href'     => $this->url->link('purchase/dead_chart', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}							
			if ($this->user->hasPermission('access', 'purchase/stock_report')) {
				$sreport[] = array(
					'name'	   => $this->language->get('stock_report_text'),
					'href'     => $this->url->link('purchase/stock_report', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
		
			if ($this->user->hasPermission('access', 'purchase/stock_report')) {
				$sreport[] = array(
					'name'	   => $this->language->get('stock_inout_text'),
					'href'     => $this->url->link('purchase/stock_report/stock_inout', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($this->user->hasPermission('access', 'purchase/stock_report/dead_products')) {
				$sreport[] = array(
					'name'	   => $this->language->get('dead_products_text'),
					'href'     => $this->url->link('purchase/stock_report/dead_products', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
		if ($this->user->hasPermission('access', 'purchase/stock_report/best_products')) {
				$sreport[] = array(
					'name'	   => $this->language->get('best_products_text'),
					'href'     => $this->url->link('purchase/stock_report/best_products', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			/*if($sreport)
			{
			$inventory[] = array(
					'name'	   =>'Report' ,
					'href'     => '',
					'children' => $sreport
				);	
			}*/
			
			
			//								
			$chart=array();
			
			if ($this->user->hasPermission('access', 'purchase/chart')) {
				$chart[] = array(
					'name'	   => $this->language->get('purchase_chart_text'),
					'href'     => $this->url->link('purchase/chart/purchase_chart', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($this->user->hasPermission('access', 'purchase/chart')) {
				$chart[] = array(
					'name'	   => $this->language->get('sale_chart_text'),
					'href'     => $this->url->link('purchase/chart', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			/*if($chart)
			{
			$inventory[] = array(
					'name'	   =>'Chart' ,
					'href'     => '',
					'children' => $chart
				);	
			}*/
		
		
		
			
			
			if ($inventory) {
				$data['menus'][] = array(
					'id'       => 'inventory',
					'icon'	   => 'fa fa-archive  fa-fw', 
					'name'	   => 'Inventory',
					'href'     => '',
					'children' => $inventory
				);
			}
			$supplierpo=array();
		if ($this->user->hasPermission('access', 'purchaseorder/suppliercreditposting')) {
				$supplierpo[] = array(
					'name'	   => 'Bulk Posting',
					'href'     => $this->url->link('purchaseorder/suppliercreditposting/getlist', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($this->user->hasPermission('access', 'purchaseorder/purchase_order')) {
				$supplierpo[] = array(
					'name'	   => 'Create PO',
					'href'     => $this->url->link('purchaseorder/purchase_order', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if(($user_info['user_group_id']==1) || ($user_info['user_group_id']==30) || ($user_info['user_group_id']==32))
			{
			if ($this->user->hasPermission('access', 'purchaseorder/purchase_order')) 
			{
				$supplierpo[] = array(
					'name'	   => 'Approve PO',
					'href'     => $this->url->link('purchaseorder/purchase_order/approve_po', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			}
			if ($this->user->hasPermission('access', 'purchaseorder/purchase_order')) {
				$supplierpo[] = array(
					'name'	   => 'Update Invoice',
					'href'     => $this->url->link('purchaseorder/purchase_order/purchase_invoice', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($this->user->hasPermission('access', 'purchaseorder/purchase_order')) {
				$supplierpo[] = array(
					'name'	   => 'Update Payment',
					'href'     => $this->url->link('purchaseorder/purchase_order/purchase_payment', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($this->user->hasPermission('access', 'purchaseorder/purchase_order')) {
				$supplierpo[] = array(
					'name'	   => 'Supplier OutStanding',
					'href'     => $this->url->link('purchaseorder/purchase_order/supplier_outstanding', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($this->user->hasPermission('access', 'purchaseorder/report')) {
				$supplierpo[] = array(
					'name'	   => 'Supplier PO Reports',
					'href'     => $this->url->link('purchaseorder/report', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($this->user->hasPermission('access', 'purchaseorder/report')) {
				$supplierpo[] = array(
					'name'	   => 'Supplier Tax Reports',
					'href'     => $this->url->link('purchaseorder/report/tax_report', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($this->user->hasPermission('access', 'purchaseorder/purchase_return')) {
				$supplierpo[] = array(
					'name'	   => 'Purchase Return',
					'href'     => $this->url->link('purchaseorder/purchase_return', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($this->user->hasPermission('access', 'purchase/credit_note')) {
				$supplierpo[] = array(
					'name'	   => 'Credit Note',
					'href'     => $this->url->link('purchase/credit_note/getlist', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($supplierpo) {
				$data['menus'][] = array(
					'id'       => 'New PO MGMT',
					'icon'	   => 'fa fa-archive  fa-fw', 
					'name'	   => 'Supplier PO',
					'href'     => '',
					'children' => $supplierpo
				);
			}

			/////////////partner start here//////////////
			$partnerinventory=array();
			if ($this->user->hasPermission('access', 'purchase/purchase_order')) {
				$partnerinventory[]= array(
					'name'	   => '<span style="font-size: 13px !important;">Unnati Krishi Kendra Requisition</span>',  
					'href'     => $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
                        if ($this->user->hasPermission('access', 'procurement/purchase_order')) 
			{
				
					$partnerinventory[]= array(
					
							'name'	   => 'Purchase Order (Ware House)',
					
							'href'     => $this->url->link('procurement/purchase_order', 'token=' . $this->session->data['token'], true),
					
							'children' => array()		
				
							);	
			
			}
                        
                        //Material Recived Acknowledgement
                        if ($this->user->hasPermission('access', 'procurement/purchase_order')) 
			{
				
					$partnerinventory[]= array(
					
							'name'	   => 'Purchase Request Acknowledgement',
					
							'href'     => $this->url->link('material_received/purchase_order', 'token=' . $this->session->data['token'], true),
					
							'children' => array()		
				
							);	
			
			}
                        

                         if ($this->user->hasPermission('access', 'isec/purchase_order')) 
			{
				
					$partnerinventory[]= array(
					
							'name'	   => 'ISEC Requisition',
					
							'href'     => $this->url->link('isec/purchase_order', 'token=' . $this->session->data['token'], true),
					
							'children' => array()		
				
							);	
			
			}
			
			
			if ($this->user->hasPermission('access', 'partner/purchase_order')) {
				
				$partnerinventory[] = array(
					
							'name'	   => 'Purchase Order Partner',
					
							'href'     => $this->url->link('partner/purchase_order', 'token=' . $this->session->data['token'], true),
					
							'children' => array()		
				
							);	
			
			}
			if ($this->user->hasPermission('access', 'invoice/adjustment')) {
				
				$partnerinventory[] = array(
					
							'name'	   => 'Partner Invoice Adjustment',
					
							'href'     => $this->url->link('invoice/adjustment', 'token=' . $this->session->data['token'], true),
					
							'children' => array()		
				
							);	
			
			}
			if ($this->user->hasPermission('access', 'margin/margin')) {
				
				$partnerinventory[] = array(
					
							'name'	   => 'Partner Margin',
					
							'href'     => $this->url->link('margin/margin/margingetList', 'token=' . $this->session->data['token'], true),
					
							'children' => array()		
				
							);	
			
			}
if ($this->user->hasPermission('access', 'creditnote/creditnote')) {
    
    $partnerinventory[] = array(
     
      'name'    => 'Credit Note',
     
      'href'     => $this->url->link('creditnote/creditnote', 'token=' . $this->session->data['token'], true),
     
      'children' => array()  
    
      ); 
   
   }
   if ($this->user->hasPermission('access', 'salereturn/purchase_order')) {
    
    $partnerinventory[] = array(
     
      'name'    => 'Sale Return',
     
      'href'     => $this->url->link('salereturn/purchase_order', 'token=' . $this->session->data['token'], true),
     
      'children' => array()  
    
      ); 
   
   }
			/*
			if ($this->user->hasPermission('access', 'invoice/purchase_order')) {
				
				$partnerinventory[] = array(
					
							'name'	   => 'Partner Invoice',
					
							'href'     => $this->url->link('invoice/purchase_order', 'token=' . $this->session->data['token'], true),
					
							'children' => array()		
				
							);	
			
			}
			*/
			if ($partnerinventory) {
				
						$data['menus'][] = array(
					
							'id'       => 'inventory',
					
							'icon'	   => 'fa fa-truck  fa-fw', 
					
							'name'	   => '<span style="font-size: 13px !important;">Purchase order/indent</span>', 
					
							'href'     => '',
					
							'children' => $partnerinventory
				
			);
			
			}
			
			$b2binventory=array();
			if ($this->user->hasPermission('access', 'invoice/purchase_order')) {
				
				$b2binventory[] = array(
					
							'name'	   => 'B2B Invoice',
					
							'href'     => $this->url->link('invoice/purchase_order/b2b', 'token=' . $this->session->data['token'], true),
					
							'children' => array()		
				
							);	
			
			}
			if ($this->user->hasPermission('access', 'b2bpartner/b2bpartner')) {
				
				$b2binventory[] = array(
					
							'name'	   => 'B2B Partners',
					
							'href'     => $this->url->link('b2bpartner/b2bpartner', 'token=' . $this->session->data['token'], true),
					
							'children' => array()		
				
							);	
			
			}
			if ($b2binventory) {
				
						$data['menus'][] = array(
					
							'id'       => 'inventory',
					
							'icon'	   => 'fa-sitemap  fa-fw', 
					
							'name'	   => '<span style="font-size: 13px !important;">B2B</span>', 
					
							'href'     => '',
					
							'children' => $b2binventory
				
			);
			
			}
			$procurementinventory=array();
			
			
			
			
			
			if ($procurementinventory) 
			{
				
					$data['menus'][] = array(
					
							'id'       => 'inventory',
					
							'icon'	   => 'fa fa-truck  fa-fw', 
					
							'name'	   => 'Procurement (Ware house)',
					
							'href'     => '',
					
							'children' => $procurementinventory
				
							);
			
			}        



			//pos
			/*$pos=array();
			if ($this->user->hasPermission('access', 'pos/pos')) {
				$pos[] = array(
					'name'	   => $this->language->get('text_pos'),
					'href'     => $this->url->link('pos/pos', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
if ($this->user->hasPermission('access', 'pos/dashboard')) {	
				$pos[] = array(
					'name'	   => $this->language->get('text_pos_dash'),
					'href'     => $this->url->link('pos/dashboard', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
		

if ($pos) {
				$data['menus'][] = array(
					'id'       => 'pos',
					'icon'	   => 'fa-shopping-cart', 
					'name'	   => $this->language->get('text_pos_main'),
					'href'     => '',
					'children' => $pos
				);
			}

*/
			
			// Sales
			$sale = array();
			
			if ($this->user->hasPermission('access', 'sale/order')) {
				$sale[] = array(
					'name'	   => $this->language->get('text_order'),
					'href'     => $this->url->link('sale/order', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($this->user->hasPermission('access', 'sale/ordercancel')) {
				$sale[] = array(
					'name'	   => 'Order cancellation',
					'href'     => $this->url->link('sale/ordercancel', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($this->user->hasPermission('access', 'sale/ordersuccess')) {
				$sale[] = array(
					'name'	   => 'Complete an Order (Success)',
					'href'     => $this->url->link('sale/ordersuccess', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($this->user->hasPermission('access', 'lead/orderleads')) {
				$sale[] = array(
					'name'	   => $this->language->get('text_order')." Leads",
					'href'     => $this->url->link('lead/orderleads', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}

			
			if ($this->user->hasPermission('access', 'sale/recurring')) {	
				$sale[] = array(
					'name'	   => $this->language->get('text_recurring'),
					'href'     => $this->url->link('sale/recurring', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'sale/return')) {
				$sale[] = array(
					'name'	   => $this->language->get('text_return'),
					'href'     => $this->url->link('sale/return', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			// Voucher
			$voucher = array();
			
			if ($this->user->hasPermission('access', 'sale/voucher')) {
				$voucher[] = array(
					'name'	   => $this->language->get('text_voucher'),
					'href'     => $this->url->link('sale/voucher', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'sale/voucher_theme')) {
				$voucher[] = array(
					'name'	   => $this->language->get('text_voucher_theme'),
					'href'     => $this->url->link('sale/voucher_theme', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($voucher) {
				$sale[] = array(
					'name'	   => $this->language->get('text_voucher'),
					'href'     => '',
					'children' => $voucher		
				);		
			}
			
			
			
			// Customer
			$customer = array();
			
			if ($this->user->hasPermission('access', 'sale/customer')) {
				$customer[] = array(
					'name'	   => $this->language->get('text_customer'),
					'href'     => $this->url->link('sale/customer', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'sale/customer_group')) {
				$customer[] = array(
					'name'	   => $this->language->get('text_customer_group'),
					'href'     => $this->url->link('sale/customer_group', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'sale/custom_field')) {		
				$customer[] = array(
					'name'	   => $this->language->get('text_custom_field'),
					'href'     => $this->url->link('sale/custom_field', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
if ($this->user->hasPermission('access', 'sale/customer_ban_ip')) {		
				$customer[] = array(
					'name'	   => $this->language->get('text_customer_ban_ip'),
					'href'     => $this->url->link('sale/customer_ban_ip', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			
			if ($customer) {
	$sale[] = array(
					'name'	   => $this->language->get('text_customer'),
					'href'     => '',
					'children' => $customer		
				);		
}


if ($sale) {
				$data['menus'][] = array(
					'id'       => 'menu-sale',
					'icon'	   => 'fa fa-shopping-cart  fa-fw', 
					'name'	   => $this->language->get('text_sale'),
					'href'     => '',
					'children' => $sale
				);
			}
			
			// Marketing
			$marketing = array();
			
			if ($this->user->hasPermission('access', 'marketing/marketing')) {
				$marketing[] = array(
					'name'	   => $this->language->get('text_marketing'),
					'href'     => $this->url->link('marketing/marketing', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'marketing/affiliate')) {
				$marketing[] = array(
					'name'	   => $this->language->get('text_affiliate'),
					'href'     => $this->url->link('marketing/affiliate', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'marketing/coupon')) {	
				$marketing[] = array(
					'name'	   => $this->language->get('text_coupon'),
					'href'     => $this->url->link('marketing/coupon', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			/*if ($this->user->hasPermission('access', 'marketing/contact')) {
				$marketing[] = array(
					'name'	   => $this->language->get('text_contact'),
					'href'     => $this->url->link('marketing/contact', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}*/
			/*
			if ($marketing) {
				$data['menus'][] = array(
					'id'       => 'menu-marketing',
					'icon'	   => 'fa-share-alt', 
					'name'	   => $this->language->get('text_marketing'),
					'href'     => '',
					'children' => $marketing
				);	
			}
			*/
			// News
			$news = array();
			
			if ($this->user->hasPermission('access', 'news/contact')) {
				$news[] = array(
					'name'	   => 'Create News',
					'href'     => $this->url->link('news/contact', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			/*
			
			if ($news) {
				$data['menus'][] = array(
					'id'       => 'menu-news',
					'icon'	   => 'fa-newspaper-o', 
					'name'	   => 'News',
					'href'     => '',
					'children' => $news
				);	
			}*/
			
			
			
			// Report
			$report = array();
			
			// Report Sales
			$report_sale = array();	
  /*
	               if ($this->user->hasPermission('access', 'report/sale_summary')) {
				$report_sale[] = array(
					'name'	   => 'Sale Summary',
					'href'     => $this->url->link('report/sale_summary', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
            
	 if ($this->user->hasPermission('access', 'report/sale_summary')) {
				$report_sale[] = array(
					'name'	   => 'Sale Summary (Tagged category)',
					'href'     => $this->url->link('report/sale_summary/category', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
                       if ($this->user->hasPermission('access', 'report/sale_summary')) {
				$report_sale[] = array(
					'name'	   => 'Sale Summary (Subsidy)',
					'href'     => $this->url->link('report/sale_summary/subsidy', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
*/ 
		 if ($this->user->hasPermission('access', 'report/sale_summary')) {
				$report_sale[] = array(
					'name'	   => 'Sale Summary',
					'href'     => $this->url->link('report/sale_summary/sale_summary', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
/*
if ($user_info) {
					 if($user_info['user_group']!='Unit Office')
						{
	if ($this->user->hasPermission('access', 'report/sale_summary')) {
				$report_sale[] = array(
					'name'	   => 'Payment wise sales',
					'href'     => $this->url->link('report/sale_summary/product_payment_method', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}


}}
*/
               if ($this->user->hasPermission('access', 'tag/sale_summary')) {
				$report_sale[] = array(
					'name'	   => 'Tagged Summary ',
					'href'     => $this->url->link('tag/sale_summary', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
					
			/*
			if ($this->user->hasPermission('access', 'report/sale_order')) {
				$report_sale[] = array(
					'name'	   => $this->language->get('text_report_sale_order'),
					'href'     => $this->url->link('report/sale_order', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			*/
			
			if ($this->user->hasPermission('access', 'tag/order')) {
				$report_sale[] = array(
					'name'	   => 'Tagged Report',
					'href'     => $this->url->link('tag/order', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'report/reconciliation')) {
				$report_sale[] = array(
					'name'	   => 'Reconciliation Report',
					'href'     => $this->url->link('report/reconciliation', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
					/*urity
				   
	
                      if ($this->user->hasPermission('access', 'report/reconciliationspray')) {
				$report_sale[] = array(
					'name'	   => 'Reconciliation Report (Spray)',
					'href'     => $this->url->link('report/reconciliationspray', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}

		 if ($this->user->hasPermission('access', 'report/reconciliationsubsidy')) {
				$report_sale[] = array(
					'name'	   => 'Subsidy Reconciliation Report',
					'href'     => $this->url->link('report/reconciliationsubsidy', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			*/
/*
			if ($this->user->hasPermission('access', 'report/sale_tax')) {
				$report_sale[] = array(
					'name'	   => $this->language->get('text_report_sale_tax'),
					'href'     => $this->url->link('report/sale_tax', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}*/
/*
                        if ($this->user->hasPermission('access', 'report/tax_report')) {
				$report_sale[] = array(
					'name'	   => $this->language->get('text_report_sale_tax'),
					'href'     => $this->url->link('report/tax_report', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
			
			
			if ($this->user->hasPermission('access', 'report/sale_shipping')) {
				$report_sale[] = array(
					'name'	   => $this->language->get('text_report_sale_shipping'),
					'href'     => $this->url->link('report/sale_shipping', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
			
			if ($this->user->hasPermission('access', 'report/sale_return')) {	
				$report_sale[] = array(
					'name'	   => $this->language->get('text_report_sale_return'),
					'href'     => $this->url->link('report/sale_return', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);	
			}
			
			if ($this->user->hasPermission('access', 'report/sale_coupon')) {		
				$report_sale[] = array(
					'name'	   => $this->language->get('text_report_sale_coupon'),
					'href'     => $this->url->link('report/sale_coupon', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			*/
			if ($report_sale) {
				$report[] = array(
					'name'	   => $this->language->get('text_sale'),
					'href'     => '',
					'children' => $report_sale
				);			
			}
			$docreport = array();	
				if ($this->user->hasPermission('access', 'report/document_upload')) {
				$docreport[] = array(
					'name'	   => 'Document Upload Report',
					'href'     => $this->url->link('report/document_upload', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($docreport) {
				$report[] = array(
					'name'	   => 'Document Upload',
					'href'     => '',
					'children' => $docreport
				);			
			}
			// Report Products			
			$report_product = array();	
			/*
			if ($this->user->hasPermission('access', 'report/product_viewed')) {
				$report_product[] = array(
					'name'	   => $this->language->get('text_report_product_viewed'),
					'href'     => $this->url->link('report/product_viewed', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
			*/
			if ($this->user->hasPermission('access', 'report/product_purchased')) {
				$report_product[] = array(
					'name'	   => $this->language->get('text_report_product_purchased'),
					'href'     => $this->url->link('report/product_purchased', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}

			if ($this->user->hasPermission('access', 'report/product_sales')) {
				$report_product[] = array(
					'name'	   => 'Product wise order count',
					'href'     => $this->url->link('report/product_sales', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
                                          if ($this->user->hasPermission('access', 'report/product_storewisesales')) {
				$report_product[] = array(
					'name'	   => 'Product  sales quantity',
					'href'     => $this->url->link('report/product_storewisesales', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
                                          if ($this->user->hasPermission('access', 'report/product_storewisesales')) {
				$report_product[] = array(
					'name'	   => 'Sales quantity (Payment wise)',
					'href'     => $this->url->link('report/product_storewisesales/salescount', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
			 if ($this->user->hasPermission('access', 'report/product_cat_sales')) {
				$report_product[] = array(
					'name'	   => 'Sales quantity (Sub Category wise)',
					'href'     => $this->url->link('report/product_cat_sales', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
			if ($this->user->hasPermission('access', 'report/productreconciliation')) {
				$report_product[] = array(
					'name'	   => 'Product  Reconciliation',
					'href'     => $this->url->link('report/productreconciliation', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
			if ($this->user->hasPermission('access', 'report/tax_report')) {
				$report_product[] = array(
					'name'	   => 'Product  Tax Report',
					'href'     => $this->url->link('report/tax_report', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
			if ($report_product) {	
				$report[] = array(
					'name'	   => $this->language->get('text_product'),
					'href'     => '',
					'children' => $report_product	
				);		
			}
			
			// Report Customers				
			$report_customer = array();
			/*
			if ($this->user->hasPermission('access', 'report/customer_online')) {	
				$report_customer[] = array(
					'name'	   => $this->language->get('text_report_customer_online'),
					'href'     => $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'report/customer_activity')) {
				$report_customer[] = array(
					'name'	   => $this->language->get('text_report_customer_activity'),
					'href'     => $this->url->link('report/customer_activity', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
     */
			if ($this->user->hasPermission('access', 'report/customer_search')) {
				$report_customer[] = array(
					'name'	   => $this->language->get('text_report_customer_search'),
					'href'     => $this->url->link('report/customer_search', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}

			/*if ($this->user->hasPermission('access', 'report/customer_order')) {	
				$report_customer[] = array(
					'name'	   => $this->language->get('text_report_customer_order'),
					'href'     => $this->url->link('report/customer_order', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'report/customer_order_report')) {	
				$report_customer[] = array(
					'name'	   => 'Customer Orders',
					'href'     => $this->url->link('report/customer_order_report', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			*/
			if ($this->user->hasPermission('access', 'crop/cropfarmer')) {	
				$report_customer[] = array(
					'name'	   => 'Register Customer ', 
					'href'     => $this->url->link('crop/cropfarmer', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'report/farmer')) {	
				$report_customer[] = array(
					'name'	   => 'Mela Farmers',  
					'href'     => $this->url->link('report/farmer', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}

			/*
			if ($this->user->hasPermission('access', 'report/customer_reward')) {
				$report_customer[] = array(
					'name'	   => $this->language->get('text_report_customer_reward'),
					'href'     => $this->url->link('report/customer_reward', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'report/customer_credit')) {
				$report_customer[] = array(
					'name'	   => $this->language->get('text_report_customer_credit'),
					'href'     => $this->url->link('report/customer_credit', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			*/
			if ($report_customer) {	
				$report[] = array(
					'name'	   => $this->language->get('text_customer'),
					'href'     => '',
					'children' => $report_customer	
				);
			}
			
			// Report Marketing			
			$report_marketing = array();			
			/*
			if ($this->user->hasPermission('access', 'report/marketing')) {
				$report_marketing[] = array(
					'name'	   => $this->language->get('text_marketing'),
					'href'     => $this->url->link('report/marketing', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			*/
			if ($this->user->hasPermission('access', 'report/affiliate')) {
				$report_marketing[] = array(
					'name'	   => $this->language->get('text_report_affiliate'),
					'href'     => $this->url->link('report/affiliate', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);		
			}
			
			if ($this->user->hasPermission('access', 'report/affiliate_activity')) {
				$report_marketing[] = array(
					'name'	   => $this->language->get('text_report_affiliate_activity'),
					'href'     => $this->url->link('report/affiliate_activity', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);		
			}
			
			if ($report_marketing) {	
				$report[] = array(
					'name'	   => $this->language->get('text_marketing'),
					'href'     => '',
					'children' => $report_marketing	
				);		
			}
			
						

		//cash report
					$cash_report = array();
			if ($this->user->hasPermission('access', 'report/cash_report')) {
				$cash_report[] = array(
					'name'	   => 'Cash from Center',
					'href'     => $this->url->link('report/cash_report', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			/*
			if ($this->user->hasPermission('access', 'report/cash_report')) {
				$cash_report[] = array(
					'name'	   => 'Cash Deposited by CE Report',
					'href'     => $this->url->link('report/cash_report/runner', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			*/
                                          if ($this->user->hasPermission('access', 'report/ce')) {
				$cash_report[] = array(
					'name'	   => 'CE Report (Merged)',
					'href'     => $this->url->link('report/ce', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'report/cash_report')) {
				$cash_report[] = array(
					'name'	   => 'EOD Cash Position (In-hand cash)',
					'href'     => $this->url->link('report/cash_report/cash_position', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'report/cash_report')) {
				$cash_report[] = array(
					'name'	   => 'CE Current Balance',
					'href'     => $this->url->link('report/cash_report/runner_cash_position', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'report/users_cash_leadger')) {
				$cash_report[] = array(
					'name'	   => "User's Cash Leadger",
					'href'     => $this->url->link('report/users_cash_leadger', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'report/runner_cash_leadger')) {
				$cash_report[] = array(
					'name'	   => "Runner's Cash Leadger",
					'href'     => $this->url->link('report/runner_cash_leadger', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			/*
			if ($this->user->hasPermission('access', 'cash/verify')) {
				$cash_report[] = array(
					'name'	   => 'Cash Verify',
					'href'     => $this->url->link('cash/verify/verify', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'cash/verify')) {
				$cash_report[] = array(
					'name'	   => 'Cash Verified List',
					'href'     => $this->url->link('cash/verify/verify_list', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} */
			if ($cash_report) {	
				$report[] = array(
					'name'	   => 'Cash',
					'href'     => '',
					'children' => $cash_report
				);		
			}
			
		//inventory report
					$inv_report = array();
			if ($this->user->hasPermission('access', 'report/inventory_report')) {
				$inv_report[] = array(
					'name'	   => 'Center Inventory Report',
					'href'     => $this->url->link('report/inventory_report', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
                        if ($this->user->hasPermission('access', 'report/inventory_report')) {
				$inv_report[] = array(
					'name'	   => 'Center Inventory Report (Product Wise)',
					'href'     => $this->url->link('report/inventory_report/product_wise', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
				if ($this->user->hasPermission('access', 'report/inventory_report')) {
				$inv_report[] = array(
					'name'	   => 'Physical Inventory Report',
					'href'     => $this->url->link('report/inventory_report/field_inventory_report', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
 if($user_info['user_group']!='Customer_care')
						{
 if ($this->user->hasPermission('access', 'report/inventory_report')) {
				$inv_report[] = array(
					'name'	   => 'Center Inventory (Prev. days report)',
					'href'     => $this->url->link('report/inventory_report/old_report', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
}

if ($user_info) {
					 if(($user_info['user_group']!='Unit Office') && ($user_info['user_group']!='Customer_care'))
						{

			if ($this->user->hasPermission('access', 'report/inventory_report')) {
				$inv_report[] = array(
					'name'	   => 'Center Inventory (Linked product)',
					'href'     => $this->url->link('report/inventory_report/linked_product', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
}}
			if ($this->user->hasPermission('access', 'report/inventory_ledger')) {
				$inv_report[] = array(
					'name'	   => 'Product Ledger',
					'href'     => $this->url->link('report/inventory_ledger', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} 


			if ($inv_report) {	
				$report[] = array(
					'name'	   => 'Inventory',
					'href'     => '',
					'children' => $inv_report
				);		
			}			
			//Contractor report
					$contractor_report = array();
			if ($this->user->hasPermission('access', 'report/contractor_report')) {
				$contractor_report[] = array(
					'name'	   => 'Contractor Limit',
					'href'     => $this->url->link('report/contractor_report/get_creditlimit', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
                        if ($this->user->hasPermission('access', 'report/contractor_report')) {
				$contractor_report[] = array(
					'name'	   => 'Contractor transactions',
					'href'     => $this->url->link('report/contractor_report/get_contractor_trans', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);  
			}
                        if ($this->user->hasPermission('access', 'report/contractor_report')) {
				$contractor_report[] = array(
					'name'	   => 'Contractor Inventory',
					'href'     => $this->url->link('report/contractor_report', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($contractor_report) {	
				$report[] = array(
					'name'	   => 'Contractor',
					'href'     => '',
					'children' => $contractor_report
				);		
			}			
//////////////////////////////////////////////////////
			
			
			if ($this->user->hasPermission('access', 'tag/billsubmission')) {
				$tagged_bill_report[] = array(
					'name'	   => 'Bill submission report',
					'href'     => $this->url->link('tag/billsubmission/getlist', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($tagged_bill_report) {	
				$report[] = array(
					'name'	   => 'Bill submission to unit',
					'href'     => '',
					'children' => $tagged_bill_report
				);		
			} 
			if ($this->user->hasPermission('access', 'report/stores')) {
				$stores_users_report[] = array(
					'name'	   => "Center's users",
					'href'     => $this->url->link('report/stores', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($stores_users_report) {	
				$report[] = array(
					'name'	   => "Center's users",
					'href'     => '',
					'children' => $stores_users_report
				);		
			} 
			if ($this->user->hasPermission('access', 'report/stock')) {
				$Stock_report[] = array(
					'name'	   => 'Stock Transfer',
					'href'     => $this->url->link('report/stock/transfer', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'report/stock')) {
				$Stock_report[] = array(
					'name'	   => 'Stock Recived',
					'href'     => $this->url->link('report/stock/recived', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'report/stock')) {
				$Stock_report[] = array(
					'name'	   => 'Stock under transit',
					'href'     => $this->url->link('report/stock/transit', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'report/stock')) {
				$Stock_report[] = array(
					'name'	   => 'PO under transit',
					'href'     => $this->url->link('report/stock/transit_po', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'report/stock')) {
				$Stock_report[] = array(
					'name'	   => 'Unlinked PO',
					'href'     => $this->url->link('report/stock/po_unlink', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}

		 /*
                        if ($this->user->hasPermission('access', 'report/stock')) { 
				$Stock_report[] = array(
					'name'	   => 'Material transfer report',
					'href'     => $this->url->link('report/stock/meterial', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} */
			if ($Stock_report) {	
				$report[] = array(
					'name'	   => 'Stock',
					'href'     => '',
					'children' => $Stock_report
				);		
			}
                         
                        if ($this->user->hasPermission('access', 'storewisereport/storereport')) {
				$Store_report[] = array(
					'name'	   => 'Store report',
					'href'     => $this->url->link('storewisereport/storereport', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} 
			if ($this->user->hasPermission('access', 'report/leadger')) {
				$Store_report[] = array(
					'name'	   => 'Store Ledger',
					'href'     => $this->url->link('report/leadger', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} 
			
			if ($Store_report) {	
				$report[] = array(
					'name'	   => 'Store report',
					'href'     => '',
					'children' => $Store_report
				);		
			}
/////////////////////
//ccare reports

			if ($this->user->hasPermission('access', 'report/incommingcall_report')) {
				$app_cc_report[] = array(
					'name'	   => 'Call Summary (Incoming)',
					'href'     => $this->url->link('report/incommingcall_report', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} 
			/*
			if ($this->user->hasPermission('access', 'ccare/incommingcall')) {
				$app_cc_report[] = array(
					'name'	   => 'Call Details (Incoming)',
					'href'     => $this->url->link('ccare/incommingcall/report', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} 
			 if ($this->user->hasPermission('access', 'ccare/ccare')) {
				$app_cc_report[] = array(
					'name'	   => 'Get Reports (Pending)',
					'href'     => $this->url->link('ccare/ccare/get_reports_pending', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
                                       if ($this->user->hasPermission('access', 'ccare/ccare')) {
				$app_cc_report[] = array(
					'name'	   => 'Get Reports (Completed)',
					'href'     => $this->url->link('ccare/ccare/get_reports_completed', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			*/
			if ($app_cc_report) {	
				$report[] = array(
					'name'	   => 'Call Center',
					'href'     => '',
					'children' => $app_cc_report
				);		
			}
	
//////
if ($this->user->hasPermission('access', 'report/customer_download')) {
				$app_report[] = array(
					'name'	   => 'App Download',
					'href'     => $this->url->link('report/customer_download', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} 
			if ($app_report) {	
				$report[] = array(
					'name'	   => 'App Download',
					'href'     => '',
					'children' => $app_report
				);		
			}
////////////recharge report///////////
if ($this->user->hasPermission('access', 'report/rechargereport')) {
				$recharge_report[] = array(
					'name'	   => 'Recharge report',
					'href'     => $this->url->link('report/rechargereport', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} 
			if ($recharge_report) {	
				$report[] = array(
					'name'	   => 'Recharge report',
					'href'     => '',
					'children' => $recharge_report
				);		
			}


////////////Expense & Waiver///////////
			if ($this->user->hasPermission('access', 'setting/debitstore')) {
				$Expense_Waiver[] = array(
					'name'	   => 'Expense Report',
					'href'     => $this->url->link('setting/debitstore/getWaveoffdata', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} 
			if ($this->user->hasPermission('access', 'setting/debitstore')) {
				$Expense_Waiver[] = array(
					'name'	   => 'Waiver Report (Own Store)',
					'href'     => $this->url->link('setting/debitstore/waiver_report', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} 
			if ($Expense_Waiver) {	
				$report[] = array(
					'name'	   => 'Expense & Waiver',
					'href'     => '',
					'children' => $Expense_Waiver
				);		
			}
			if ($this->user->hasPermission('access', 'report/coupon')) {
				$report[] = array(
					'name'	   => 'Coupon History',
					'href'     => $this->url->link('report/coupon/history', 'token=' . $this->session->data['token'], true),
					'children' => ''
				);		
			}
			if ($this->user->hasPermission('access', 'attendance/attendance')) {
				$report_attendance[] = array(
					'name'	   => 'Attendance Report',
					'href'     => $this->url->link('attendance/attendance', 'token=' . $this->session->data['token'], true),
					'children' => ''
				);		
			}
			if ($this->user->hasPermission('access', 'attendance/attendance')) {
				$report_attendance[] = array(
					'name'	   => 'Attendance Report-Map',
					'href'     => $this->url->link('attendance/attendance/map', 'token=' . $this->session->data['token'], true),
					'children' => ''
				);		
			}
			
			if ($report_attendance) {	
				$report[] = array(
					'name'	   => 'Attendance',
					'href'     => '',
					'children' => $report_attendance
				);		
			}
			
			if ($this->user->hasPermission('access', 'report/partner')) {
				$report_partner[] = array(
					'name'	   => 'Partner Collection Report', 
					'href'     => $this->url->link('report/partner/collection_report', 'token=' . $this->session->data['token'], true),
					'children' => ''
				);		
			}
			if ($this->user->hasPermission('access', 'report/partner')) {
				$report_partner[] = array(
					'name'	   => 'Partner Ledger', 
					'href'     => $this->url->link('report/partner', 'token=' . $this->session->data['token'], true),
					'children' => ''
				);		
			}
			if ($this->user->hasPermission('access', 'report/partner')) {
				$report_partner[] = array(
					'name'	   => 'Partner Billing', 
					'href'     => $this->url->link('report/partner/partner_billing', 'token=' . $this->session->data['token'], true),
					'children' => ''
				);		
			}
			if ($this->user->hasPermission('access', 'report/partner')) {
				$report_partner[] = array(
					'name'	   => 'Partner Payment Received', 
					'href'     => $this->url->link('report/partner/payment_received', 'token=' . $this->session->data['token'], true),
					'children' => ''
				);		
			}
			if ($report_partner) {	
				$report[] = array(
					'name'	   => 'Partner',
					'href'     => '',
					'children' => $report_partner
				);		
			}
			if ($report) {	
				$data['menus'][] = array(
					'id'       => 'menu-report',
					'icon'	   => 'fa-bar-chart-o', 
					'name'	   => $this->language->get('text_reports'),
					'href'     => '',
					'children' => $report
				);	
			}


///////////////dscl reports start here////////////////////////////////

 //dscl report
                        $reportdscl = array();
                        // Report Sales
			$report_sale_dscl = array();	
			
            if ($this->user->hasPermission('access', 'reportdscl/report')) {
				$report_sale_dscl[] = array(
					'name'	   => 'DSCL Sales Report',
					'href'     => $this->url->link('reportdscl/report', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} 

     
			// 
			 if ($this->user->hasPermission('access', 'reportdscl/report')) {
				$report_sale_dscl[] = array(
					'name'	   => 'Item Report',
					'href'     => $this->url->link('reportdscl/report/item_wise_product_sold', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} 
 
		 if ($this->user->hasPermission('access', 'reportdscl/sale_summary')) {
				$report_sale_dscl[] = array(
					'name'	   => 'Sale Summary',
					'href'     => $this->url->link('reportdscl/sale_summary/sale_summary', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}

               		if ($this->user->hasPermission('access', 'reportdscl/sale_summary')) {
				$report_sale_dscl[] = array(
					'name'	   => 'Tagged Summary ',
					'href'     => $this->url->link('reportdscl/sale_summary_tagged', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
                   		
			
			if ($this->user->hasPermission('access', 'reportdscl/order_tagged')) {
				$report_sale_dscl[] = array(
					'name'	   => 'Tagged Report',
					'href'     => $this->url->link('reportdscl/order_tagged', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
                        		if ($this->user->hasPermission('access', 'reportdscl/reconciliation')) {
				$report_sale_dscl[] = array(
					'name'	   => 'Reconciliation Report',
					'href'     => $this->url->link('reportdscl/reconciliation', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
				/*
                      		if ($this->user->hasPermission('access', 'reportdscl/reconciliationspray')) {
				$report_sale_dscl[] = array(
					'name'	   => 'Reconciliation Report (Spray)',
					'href'     => $this->url->link('reportdscl/reconciliationspray', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			*/
			/*
			if ($this->user->hasPermission('access', 'reportdscl/reconciliationsubsidy')) {
				$report_sale_dscl[] = array(
					'name'	   => 'Reconciliation Report (Tagged Subsidy)',
					'href'     => $this->url->link('reportdscl/reconciliationsubsidy', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			*/
			if ($this->user->hasPermission('access', 'reportdscl/reconciliationsubsidy')) {
				$report_sale_dscl[] = array(
					'name'	   => 'Reconciliation Report (Subsidy -Pink -Purchy)',
					'href'     => $this->url->link('reportdscl/reconciliationsubsidy/subsidy', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'reportdscl/getDataFromDscl')) {
				$report_sale_dscl[] = array(
					'name'	   => 'Dscl order detail',
					'href'     => $this->url->link('reportdscl/getDataFromDscl', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
 if ($this->user->hasPermission('access', 'reportdscl/report')) {
				$report_sale_dscl[] = array(
					'name'	   => 'DSCL Order Count',
					'href'     => $this->url->link('reportdscl/dsclsalesummaryreport', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} 
 if ($this->user->hasPermission('access', 'reportdscl/report')) {
				$report_sale_dscl[] = array(
					'name'	   => 'DSCL Order Amount',
					'href'     => $this->url->link('reportdscl/dscltotalsalesummaryreport', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'reportdscl/report')) {
				$report_sale_dscl[] = array(
					'name'	   => 'Tagged Amount',
					'href'     => $this->url->link('reportdscl/report/tagged_amount', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'reportdscl/coupon')) {
				$report_sale_dscl[] = array(
					'name'	   => 'Subsidy Coupon Check',
					'href'     => $this->url->link('reportdscl/coupon', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($report_sale_dscl) {
				$reportdscl[] = array(
					'name'	   => $this->language->get('text_sale'),
					'href'     => '',
					'children' => $report_sale_dscl
				);			
			}
                        
                        		$report_product_dscl = array();	
			
			/*
			if ($this->user->hasPermission('access', 'reportdscl/product_purchased')) {
				$report_product_dscl[] = array(
					'name'	   => $this->language->get('text_report_product_purchased'),
					'href'     => $this->url->link('reportdscl/product_purchased', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
			*/
			if ($this->user->hasPermission('access', 'reportdscl/product_sales')) {
				$report_product_dscl[] = array(
					'name'	   => 'Product Sales Report',
					'href'     => $this->url->link('reportdscl/product_sales', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
                                          if ($this->user->hasPermission('access', 'reportdscl/product_storewisesales')) {
				$report_product_dscl[] = array(
					'name'	   => 'Product Sales quantity (Store wise)',
					'href'     => $this->url->link('reportdscl/product_storewisesales', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
                                          if ($this->user->hasPermission('access', 'reportdscl/product_storewisesales')) {
				$report_product_dscl[] = array(
					'name'	   => 'Product Sales quantity (Payment wise)',
					'href'     => $this->url->link('reportdscl/product_storewisesales/salescount', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
			
			if ($report_product_dscl) {	
				$reportdscl[] = array(
					'name'	   => $this->language->get('text_product'),
					'href'     => '',
					'children' => $report_product_dscl	
				);		
			}
                        		
                        //inventory report
                        
		$inv_report_dscl = array();
                
			if ($this->user->hasPermission('access', 'reportdscl/inventory_report')) {
				$inv_report_dscl[] = array(
					'name'	   => 'Center Inventory Report',
					'href'     => $this->url->link('reportdscl/inventory_report', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}

			if($user_info['user_group']!='DSCL Reports')
			{ 

                        if ($this->user->hasPermission('access', 'reportdscl/inventory_report')) {
				$inv_report_dscl[] = array(
					'name'	   => 'Center Inventory Report (Product Wise)',
					'href'     => $this->url->link('reportdscl/inventory_report/product_wise', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}	
			}
			if ($this->user->hasPermission('access', 'reportdscl/inventory_report')) {
				$inv_report_dscl[] = array(
					'name'	   => 'Physical Inventory Report',
					'href'     => $this->url->link('reportdscl/inventory_report/field_inventory_report', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($inv_report_dscl) {	
				$reportdscl[] = array(
					'name'	   => 'Inventory',
					'href'     => '',
					'children' => $inv_report_dscl
				);		
			}
	
       //cash report //////////////////
                    $cash_report_dscl = array();
			/*
			if ($this->user->hasPermission('access', 'reportdscl/cash_report')) {
				$cash_report_dscl[] = array(
					'name'	   => 'Cash from Center',
					'href'     => $this->url->link('reportdscl/cash_report', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
                                          if ($this->user->hasPermission('access', 'reportdscl/ce')) {
				$cash_report_dscl[] = array(
					'name'	   => 'CE Report (Merged)',
					'href'     => $this->url->link('reportdscl/ce', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			*/
			if ($this->user->hasPermission('access', 'reportdscl/cash_report')) {
				$cash_report_dscl[] = array(
					'name'	   => 'EOD Cash Position (In-hand cash)',
					'href'     => $this->url->link('reportdscl/cash_report/cash_position', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'reportdscl/cash_report')) {
				$cash_report_dscl[] = array(
					'name'	   => 'CE Current Balance',
					'href'     => $this->url->link('reportdscl/cash_report/runner_cash_position', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($cash_report_dscl) {	
				$reportdscl[] = array(
					'name'	   => 'Cash',
					'href'     => '',
					'children' => $cash_report_dscl
				);		
			}
  
			if ($this->user->hasPermission('access', 'reportdscl/billsubmission')) {
				$tagged_bill_reportdscl[] = array(
					'name'	   => 'Bill submission report',
					'href'     => $this->url->link('reportdscl/billsubmission/getlist', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($tagged_bill_reportdscl) {	
				$reportdscl[] = array(
					'name'	   => 'Bill submission to unit',
					'href'     => '',
					'children' => $tagged_bill_reportdscl
				);		
			} 
			if ($this->user->hasPermission('access', 'reportdscl/stores')) {
				$stores_users_reportdscl[] = array(
					'name'	   => "Center's users",
					'href'     => $this->url->link('reportdscl/stores', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($stores_users_reportdscl) {	
				$reportdscl[] = array(
					'name'	   => "Center's users",
					'href'     => '',
					'children' => $stores_users_reportdscl
				);		
			} 
                         
			if ($this->user->hasPermission('access', 'reportdscl/stock')) {
				$Stock_report_dscl[] = array(
					'name'	   => 'Stock Transfer',
					'href'     => $this->url->link('reportdscl/stock/transfer', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'reportdscl/stock')) {
				$Stock_report_dscl[] = array(
					'name'	   => 'Stock Recived',
					'href'     => $this->url->link('reportdscl/stock/recived', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'reportdscl/stock')) {
				$Stock_report_dscl[] = array(
					'name'	   => 'Stock under transit',
					'href'     => $this->url->link('reportdscl/stock/transit', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'reportdscl/stock')) {
				$Stock_report_dscl[] = array(
					'name'	   => 'PO under transit',
					'href'     => $this->url->link('reportdscl/stock/transit_po', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
                        //Material Request (Warehouse) IN Report DSCL - under stock
                        if ($this->user->hasPermission('access', 'reportdscl/stock')) {
				$Stock_report_dscl[] = array(
					'name'	   => 'Material Request (Warehouse)',
					//'href'     => $this->url->link('reportdscl/stock/transit_po', 'token=' . $this->session->data['token'], true),
                                        'href'     => $this->url->link('procurement/storereport', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
		 
			if ($Stock_report_dscl) {	
				$reportdscl[] = array(
					'name'	   => 'Stock',
					'href'     => '',
					'children' => $Stock_report_dscl
				);		
			}
			
		
	
			
			 if ($this->user->hasPermission('access', 'reportdscl/storereport')) {
				$Store_report_dscl[] = array(
					'name'	   => 'Store report',
					'href'     => $this->url->link('reportdscl/storereport', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} 
			if ($this->user->hasPermission('access', 'reportdscl/leadger')) {
				$Store_report_dscl[] = array(
					'name'	   => 'Store Ledger',
					'href'     => $this->url->link('reportdscl/leadger', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} 
			if ($Store_report_dscl) {	
				$reportdscl[] = array(
					'name'	   => 'Store report',
					'href'     => '',
					'children' => $Store_report_dscl
				);		
			}
			
			////////////Expense & Waiver///////////
			if ($this->user->hasPermission('access', 'reportdscl/debitstore')) {
				$Expense_Waiverdscl[] = array(
					'name'	   => 'Expense Report',
					'href'     => $this->url->link('reportdscl/debitstore/getWaveoffdata', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} 
			if ($this->user->hasPermission('access', 'reportdscl/debitstore')) {
				$Expense_Waiverdscl[] = array(
					'name'	   => 'Waiver Report (Own Store)',
					'href'     => $this->url->link('reportdscl/debitstore/waiver_report', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} 
			if ($Expense_Waiverdscl) {	
				$reportdscl[] = array(
					'name'	   => 'Expense & Waiver',
					'href'     => '',
					'children' => $Expense_Waiverdscl
				);		
			}
			if ($this->user->hasPermission('access', 'report/subuser')) 
			{

				$subuser[] = array(

					'name' => 'Material Summary',

					'href' => $this->url->link('report/subuser', 'token=' . $this->session->data['token'], true),

					'children' => array()

				);

			}
			if ($this->user->hasPermission('access', 'report/subuser')) 
			{

				$subuser[] = array(

					'name' => 'Material Issue/Sales Deatils',

					'href' => $this->url->link('report/subuser/issued_and_sale', 'token=' . $this->session->data['token'], true),

					'children' => array()

				);

			}
			if ($this->user->hasPermission('access', 'report/subuser')) 
			{
				$subuser[] = array(
				'name' => 'Material Detail',
				'href' => $this->url->link('report/subuser/material_detail', 'token=' . $this->session->data['token'], true),
				'children' => array()
				);

			}
			if ($this->user->hasPermission('access', 'report/subuser')) 
			{
				$subuser[] = array(
				'name' => 'Order Detail',
				'href' => $this->url->link('report/subuser/SubUserOrderDetail', 'token=' . $this->session->data['token'], true),
				'children' => array()

				);
			}

			if ($this->user->hasPermission('access', 'report/subuser')) 
			{
				$subuser[] = array(
				'name' => 'Sale/Cash Summary',
				'href' => $this->url->link('report/subuser/SubUserSaleCash', 'token=' . $this->session->data['token'], true),
				'children' => array()
				);
			}

			if ($subuser) 
			{
				$reportdscl[] = array(
				'name' => 'Subuser Report',
				'href' => '',
				'children' => $subuser
				);

			}
			if ($reportdscl) 
			{
				$data['menus'][] = array(
					'id'       => 'Report DSCL',
					'icon'	   => 'fa fa-line-chart  fa-fw', 
					'name'	   => 'Report DSCL',
					
					'children' => $reportdscl
				);
			}
			if ($this->user->hasPermission('access', 'generatepin/pin')) {
				$subsuermpin[] = array(
					'name' => 'Reset MPIN',

'href' => $this->url->link('generatepin/pin', 'token=' . $this->session->data['token'], true),

'children' => array()
				);			
			}
			if ($subsuermpin) {
				$data['menus'][] = array(
					'id'       => 'Reetmpin',
					'icon'	   => 'fa fa-line-chart  fa-fw', 
					'name'	   => 'Reset MPIN',
					
					'children' => $subsuermpin
				);
			}
/////////////dscl reports end here//////////////////////////////////////
	
///////////////bcml reports start here////////////////////////////////

 //bcml report
                        $reportbcml = array();
		$report_revenue_bcml = array();	
                      
 		if ($this->user->hasPermission('access', 'reportbcml/order')) {
				$report_revenue_bcml[] = array(
					'name'	   => 'BCML Sales Order',
					'href'     => $this->url->link('reportbcml/order', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}  
		 if ($this->user->hasPermission('access', 'reportbcml/revenue_assurance')) {
				$report_revenue_bcml[] = array(
					'name'	   => 'Revenue Assurance',
					'href'     => $this->url->link('reportbcml/revenue_assurance', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}

		if ($report_revenue_bcml) {
				$reportbcml[] = array(
					'name'	   => 'Revenue Assurance',
					'href'     => '',
					'children' => $report_revenue_bcml
				);			
			}
                        // Report Sales
			$report_sale_bcml = array();	
                      
 		if ($this->user->hasPermission('access', 'reportbcml/report')) {
				$report_sale_bcml[] = array(
					'name'	   => 'BCML Sales Report',
					'href'     => $this->url->link('reportbcml/report', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}  
		 if ($this->user->hasPermission('access', 'reportbcml/sale_summary')) {
				$report_sale_bcml[] = array(
					'name'	   => 'Sale Summary',
					'href'     => $this->url->link('reportbcml/sale_summary/sale_summary', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}

               		if ($this->user->hasPermission('access', 'reportbcml/sale_summary_tagged')) {
				$report_sale_bcml[] = array(
					'name'	   => 'Tagged Summary ',
					'href'     => $this->url->link('reportbcml/sale_summary_tagged', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
                   		
			
			if ($this->user->hasPermission('access', 'reportbcml/order_tagged')) {
				$report_sale_bcml[] = array(
					'name'	   => 'Tagged Report',
					'href'     => $this->url->link('reportbcml/order_tagged', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
                        		if ($this->user->hasPermission('access', 'reportbcml/reconciliation')) {
				$report_sale_bcml[] = array(
					'name'	   => 'Reconciliation Report',
					'href'     => $this->url->link('reportbcml/reconciliation', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
                      		if ($this->user->hasPermission('access', 'reportbcml/report_fm')) {
				$report_sale_bcml[] = array(
					'name'	   => 'FM Item Wise Summary',
					'href'     => $this->url->link('reportbcml/report_fm', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if(($user_info['user_group_id']!=11) && ($user_info['user_group_id']!=38) )
			{
			if ($this->user->hasPermission('access', 'reportbcml/reconciliation')) {
				$report_sale_bcml[] = array(
					'name'	   => 'Get Debit Note Detail',
					'href'     => $this->url->link('reportbcml/reconciliation/getdebitnotedetail', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'reportbcml/reconciliation')) {
				$report_sale_bcml[] = array(
					'name'	   => 'Get Invoices Detail',
					'href'     => $this->url->link('reportbcml/reconciliation/getinvoicedetail', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'reportbcml/reconciliation')) {
				$report_sale_bcml[] = array(
					'name'	   => 'Get Order data from BCML',
					'href'     => $this->url->link('reportbcml/reconciliation/getorderdata', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'reportbcml/bcml_cash')) {
				$report_sale_bcml[] = array(
					'name'	   => 'Send Cash Data To BCML',
					'href'     => $this->url->link('reportbcml/bcml_cash', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
				
				        //##################################### added on 26 Oct 2019 by Shashi bhushan Rai #############################################//
                            $report_sale_bcml[] = array(
                                'name' => 'Material Delivery Notification',
                                'href' => $this->url->link('reportbcml/order/deliveryNotification', 'token=' . $this->session->data['token'], true),
                                'children' => array()
                            );
			}
                        if ($this->user->hasPermission('access', 'reportbcml/reconciliation')) {
				$report_sale_bcml[] = array(
					'name'	   => 'FM Code Update',
					'href'     => $this->url->link('reportbcml/updatefm/updatefmcode', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			}
			if ($report_sale_bcml) {
				$reportbcml[] = array(
					'name'	   => $this->language->get('text_sale'),
					'href'     => '',
					'children' => $report_sale_bcml
				);			
			}
			
                        
                        		$report_product_bcml = array();	
			
			
			if ($this->user->hasPermission('access', 'reportbcml/product_purchased')) {
				$report_product_bcml[] = array(
					'name'	   => $this->language->get('text_report_product_purchased'),
					'href'     => $this->url->link('reportbcml/product_purchased', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}

			if ($this->user->hasPermission('access', 'reportbcml/product_sales')) {
				$report_product_bcml[] = array(
					'name'	   => 'Product wise order count',
					'href'     => $this->url->link('reportbcml/product_sales', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
                                          if ($this->user->hasPermission('access', 'reportbcml/product_storewisesales')) {
				$report_product_bcml[] = array(
					'name'	   => 'Product  sales quantity',
					'href'     => $this->url->link('reportbcml/product_storewisesales', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
			
			if ($user_info) {
					 if($user_info['user_group']!='BCML Reports')
						{
                                          if ($this->user->hasPermission('access', 'reportbcml/product_storewisesales')) {
				$report_product_bcml[] = array(
					'name'	   => 'Sales quantity (Payment wise)',
					'href'     => $this->url->link('reportbcml/product_storewisesales/salescount', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
			}}
			
			if ($report_product_bcml) {	
				$reportbcml[] = array(
					'name'	   => $this->language->get('text_product'),
					'href'     => '',
					'children' => $report_product_bcml	
				);		
			}
                        		
                        //inventory report
                        
		$inv_report_bcml = array();
                
			if ($this->user->hasPermission('access', 'reportbcml/inventory_report')) {
				$inv_report_bcml[] = array(
					'name'	   => 'Center Inventory Report',
					'href'     => $this->url->link('reportbcml/inventory_report', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
                        if ($this->user->hasPermission('access', 'reportbcml/inventory_report')) {
				$inv_report_bcml[] = array(
					'name'	   => 'Center Inventory Report (Product Wise)',
					'href'     => $this->url->link('reportbcml/inventory_report/product_wise', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'reportbcml/inventory_report')) {
				$inv_report_bcml[] = array(
					'name'	   => 'Physical Inventory Report',
					'href'     => $this->url->link('reportbcml/inventory_report/field_inventory_report', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}


		

			if ($inv_report_bcml) {	
				$reportbcml[] = array(
					'name'	   => 'Inventory',
					'href'     => '',
					'children' => $inv_report_bcml
				);		
			}
	
       //cash report //////////////////
                    $cash_report_bcml = array();
			if ($user_info) {
					 if($user_info['user_group']!='BCML Reports')
						{
			/*
			if ($this->user->hasPermission('access', 'reportbcml/cash_report')) {
				$cash_report_bcml[] = array(
					'name'	   => 'Cash from Center',
					'href'     => $this->url->link('reportbcml/cash_report', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			*/
			if ($this->user->hasPermission('access', 'reportbcml/cash_report')) {
				$cash_report_bcml[] = array(
					'name'	   => 'CE Current Balance',
					'href'     => $this->url->link('reportbcml/cash_report/runner_cash_position', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			}}
			/*
                                          if ($this->user->hasPermission('access', 'reportbcml/ce')) {
				$cash_report_bcml[] = array(
					'name'	   => 'CE Report (Merged)',
					'href'     => $this->url->link('reportbcml/ce', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			*/
			if ($this->user->hasPermission('access', 'reportbcml/cash_report')) {
				$cash_report_bcml[] = array(
					'name'	   => 'EOD Cash Position (In-hand cash)',
					'href'     => $this->url->link('reportbcml/cash_report/cash_position', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($cash_report_bcml) {	
				$reportbcml[] = array(
					'name'	   => 'Cash',
					'href'     => '',
					'children' => $cash_report_bcml
				);		
			}
  
			if ($this->user->hasPermission('access', 'reportbcml/billsubmission')) {
				$tagged_bill_reportbcml[] = array(
					'name'	   => 'Bill submission report',
					'href'     => $this->url->link('reportbcml/billsubmission/getlist', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($tagged_bill_reportbcml) {	
				$reportbcml[] = array(
					'name'	   => 'Bill submission to unit',
					'href'     => '',
					'children' => $tagged_bill_reportbcml
				);		
			} 
			if ($this->user->hasPermission('access', 'reportbcml/stores')) {
				$stores_users_reportbcml[] = array(
					'name'	   => "Center's users",
					'href'     => $this->url->link('reportbcml/stores', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($stores_users_reportbcml) {	
				$reportbcml[] = array(
					'name'	   => "Center's users",
					'href'     => '',
					'children' => $stores_users_reportbcml
				);		
			} 
                         
			if ($this->user->hasPermission('access', 'reportbcml/stock')) {
				$Stock_report_bcml[] = array(
					'name'	   => 'Stock Transfer',
					'href'     => $this->url->link('reportbcml/stock/transfer', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'reportbcml/stock')) {
				$Stock_report_bcml[] = array(
					'name'	   => 'Stock Recived',
					'href'     => $this->url->link('reportbcml/stock/recived', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'reportbcml/stock')) {
				$Stock_report_bcml[] = array(
					'name'	   => 'Stock under transit',
					'href'     => $this->url->link('reportbcml/stock/transit', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'reportbcml/stock')) {
				$Stock_report_bcml[] = array(
					'name'	   => 'PO under transit',
					'href'     => $this->url->link('reportbcml/stock/transit_po', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}

		 
			if ($Stock_report_bcml) {	
				$reportbcml[] = array(
					'name'	   => 'Stock',
					'href'     => '',
					'children' => $Stock_report_bcml
				);		
			}
			
			 if ($this->user->hasPermission('access', 'reportbcml/storereport')) {
				$Store_report_bcml[] = array(
					'name'	   => 'Store report',
					'href'     => $this->url->link('reportbcml/storereport', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} 
			if ($this->user->hasPermission('access', 'reportbcml/leadger')) {
				$Store_report_bcml[] = array(
					'name'	   => 'Store Ledger',
					'href'     => $this->url->link('reportbcml/leadger', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} 
			if ($Store_report_bcml) {	
				$reportbcml[] = array(
					'name'	   => 'Store report',
					'href'     => '',
					'children' => $Store_report_bcml
				);		
			}
			
			////////////Expense & Waiver///////////
			if ($this->user->hasPermission('access', 'reportbcml/debitstore')) {
				$Expense_Waiverbcml[] = array(
					'name'	   => 'Expense Report',
					'href'     => $this->url->link('reportbcml/debitstore/getWaveoffdata', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} 
			if ($this->user->hasPermission('access', 'reportbcml/debitstore')) {
				$Expense_Waiverbcml[] = array(
					'name'	   => 'Waiver Report (Own Store)',
					'href'     => $this->url->link('reportbcml/debitstore/waiver_report', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} 
			if ($Expense_Waiverbcml) {	
				$reportbcml[] = array(
					'name'	   => 'Expense & Waiver',
					'href'     => '',
					'children' => $Expense_Waiverbcml
				);		
			}
			
			if ($reportbcml) {
				$data['menus'][] = array(
					'id'       => 'Report BCML',
					'icon'	   => 'fa fa-line-chart  fa-fw', 
					'name'	   => 'Report BCML',
					
					'children' => $reportbcml
				);
			}
//////////////bcml reports end here//////////////////////////////////////


///////////////isec reports start here////////////////////////////////

 //isec report
                        $reportisec = array();
                        // Report Sales
			$report_sale_isec = array();	
                      
 
		 if ($this->user->hasPermission('access', 'reportisec/sale_summary')) {
				$report_sale_isec[] = array(
					'name'	   => 'Sale Summary',
					'href'     => $this->url->link('reportisec/sale_summary/sale_summary', 'token=' . $this->session->data['token'], true), 
					'children' => array()
				);
			}
			/*
               		if ($this->user->hasPermission('access', 'reportisec/sale_summary')) {
				$report_sale_isec[] = array(
					'name'	   => 'Tagged Summary ',
					'href'     => $this->url->link('reportisec/sale_summary_tagged', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
                   		
			
			if ($this->user->hasPermission('access', 'reportisec/order_tagged')) {
				$report_sale_isec[] = array(
					'name'	   => 'Tagged Report',
					'href'     => $this->url->link('reportisec/order_tagged', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
                        		if ($this->user->hasPermission('access', 'reportisec/reconciliation')) {
				$report_sale_isec[] = array(
					'name'	   => 'Reconciliation Report',
					'href'     => $this->url->link('reportisec/reconciliation', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
                      		if ($this->user->hasPermission('access', 'reportisec/reconciliationspray')) {
				$report_sale_isec[] = array(
					'name'	   => 'Reconciliation Report (Spray)',
					'href'     => $this->url->link('reportisec/reconciliationspray', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			*/

			if ($report_sale_isec) {
				$reportisec[] = array(
					'name'	   => $this->language->get('text_sale'),
					'href'     => '',
					'children' => $report_sale_isec
				);			
			}
                        
                        		$report_product_isec = array();	
			
			
			if ($this->user->hasPermission('access', 'reportisec/product_purchased')) {
				$report_product_isec[] = array(
					'name'	   => $this->language->get('text_report_product_purchased'),
					'href'     => $this->url->link('reportisec/product_purchased', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}

			if ($this->user->hasPermission('access', 'reportisec/product_sales')) {
				$report_product_isec[] = array(
					'name'	   => 'Product wise order count',
					'href'     => $this->url->link('reportisec/product_sales', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
                                          if ($this->user->hasPermission('access', 'reportisec/product_storewisesales')) {
				$report_product_isec[] = array(
					'name'	   => 'Product  sales quantity',
					'href'     => $this->url->link('reportisec/product_storewisesales', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
                                          if ($this->user->hasPermission('access', 'reportisec/product_storewisesales')) {
				$report_product_isec[] = array(
					'name'	   => 'Sales quantity (Payment wise)',
					'href'     => $this->url->link('reportisec/product_storewisesales/salescount', 'token=' . $this->session->data['token'], true),
					'children' => array()	
				);
			}
			
			if ($report_product_isec) {	
				$reportisec[] = array(
					'name'	   => $this->language->get('text_product'),
					'href'     => '',
					'children' => $report_product_isec	
				);		
			}
                        		
                        //inventory report
                        
		$inv_report_isec = array();
                
			if ($this->user->hasPermission('access', 'reportisec/inventory_report')) {
				$inv_report_isec[] = array(
					'name'	   => 'Center Inventory Report',
					'href'     => $this->url->link('reportisec/inventory_report', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
                        if ($this->user->hasPermission('access', 'reportisec/inventory_report')) {
				$inv_report_isec[] = array(
					'name'	   => 'Center Inventory Report (Product Wise)',
					'href'     => $this->url->link('reportisec/inventory_report/product_wise', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}


if ($user_info) {
					 if(($user_info['user_group']!='Unit Office') && ($user_info['user_group']!='Customer_care'))
						{

			if ($this->user->hasPermission('access', 'reportisec/inventory_report')) {
				$inv_report_isec[] = array(
					'name'	   => 'Center Inventory (Linked product)',
					'href'     => $this->url->link('reportisec/inventory_report/linked_product', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
}}   
		

			if ($inv_report_isec) {	
				$reportisec[] = array(
					'name'	   => 'Inventory',
					'href'     => '',
					'children' => $inv_report_isec
				);		
			}
	
       //cash report //////////////////
                    $cash_report_isec = array();
			if ($this->user->hasPermission('access', 'reportisec/cash_report')) {
				$cash_report_isec[] = array(
					'name'	   => 'Cash from Center',
					'href'     => $this->url->link('reportisec/cash_report', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
                                          if ($this->user->hasPermission('access', 'reportisec/ce')) {
				$cash_report_isec[] = array(
					'name'	   => 'CE Report (Merged)',
					'href'     => $this->url->link('reportisec/ce', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'reportisec/cash_report')) {
				$cash_report_isec[] = array(
					'name'	   => 'EOD Cash Position (In-hand cash)',
					'href'     => $this->url->link('reportisec/cash_report/cash_position', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'reportisec/cash_report')) {
				$cash_report_isec[] = array(
					'name'	   => 'CE Current Balance',
					'href'     => $this->url->link('reportisec/cash_report/runner_cash_position', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			/*
			if ($cash_report_isec) {	
				$reportisec[] = array(
					'name'	   => 'Cash',
					'href'     => '',
					'children' => $cash_report_isec
				);		
			}
			*/
  			/*
			if ($this->user->hasPermission('access', 'reportisec/billsubmission')) {
				$tagged_bill_reportisec[] = array(
					'name'	   => 'Bill submission report',
					'href'     => $this->url->link('reportisec/billsubmission/getlist', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($tagged_bill_reportisec) {	
				$reportisec[] = array(
					'name'	   => 'Bill submission to unit',
					'href'     => '',
					'children' => $tagged_bill_reportisec
				);		
			} 
			*/
			if ($this->user->hasPermission('access', 'reportisec/stores')) {
				$stores_users_reportisec[] = array(
					'name'	   => "Center's users",
					'href'     => $this->url->link('reportisec/stores', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			if ($stores_users_reportisec) {	
				$reportisec[] = array(
					'name'	   => "Center's users",
					'href'     => '',
					'children' => $stores_users_reportisec
				);		
			} 
                         		/*
			if ($this->user->hasPermission('access', 'reportisec/stock')) {
				$Stock_report_isec[] = array(
					'name'	   => 'Stock Transfer',
					'href'     => $this->url->link('reportisec/stock/transfer', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'reportisec/stock')) {
				$Stock_report_isec[] = array(
					'name'	   => 'Stock Recived',
					'href'     => $this->url->link('reportisec/stock/recived', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'reportisec/stock')) {
				$Stock_report_isec[] = array(
					'name'	   => 'Stock under transit',
					'href'     => $this->url->link('reportisec/stock/transit', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'reportisec/stock')) {
				$Stock_report_isec[] = array(
					'name'	   => 'PO under transit',
					'href'     => $this->url->link('reportisec/stock/transit_po', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			*/
		 
			if ($Stock_report_isec) {	
				$reportisec[] = array(
					'name'	   => 'Stock',
					'href'     => '',
					'children' => $Stock_report_isec
				);		
			}
			
			 if ($this->user->hasPermission('access', 'reportisec/storereport')) {
				$Store_report_isec[] = array(
					'name'	   => 'Store report',
					'href'     => $this->url->link('reportisec/storereport', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} 
			if ($this->user->hasPermission('access', 'reportisec/leadger')) {
				$Store_report_isec[] = array(
					'name'	   => 'Store Ledger',
					'href'     => $this->url->link('reportisec/leadger', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} 
			if ($Store_report_isec) {	
				$reportisec[] = array(
					'name'	   => 'Store report',
					'href'     => '',
					'children' => $Store_report_isec
				);		
			}
			/*
			////////////Expense & Waiver///////////
			if ($this->user->hasPermission('access', 'reportisec/debitstore')) {
				$Expense_Waiverisec[] = array(
					'name'	   => 'Expense Report',
					'href'     => $this->url->link('reportisec/debitstore/getWaveoffdata', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} 
			if ($this->user->hasPermission('access', 'reportisec/debitstore')) {
				$Expense_Waiverisec[] = array(
					'name'	   => 'Waiver Report (Own Store)',
					'href'     => $this->url->link('reportisec/debitstore/waiver_report', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} 
			if ($Expense_Waiverisec) {	
				$reportisec[] = array(
					'name'	   => 'Expense & Waiver',
					'href'     => '',
					'children' => $Expense_Waiverisec
				);		
			}
			*/
			if ($reportisec) {
				$data['menus'][] = array(
					'id'       => 'Report ISEC',
					'icon'	   => 'fa fa-line-chart  fa-fw', 
					'name'	   => 'Report ISEC',
					
					'children' => $reportisec
				);
			}
//////////////isec reports end here//////////////////////////////////////

			/*store's target*/			

			$storetargert=array();
			
			if ($this->user->hasPermission('access', 'target/target')) {
				$storetargert[] = array(
					'name'	   => 'Set target',
					'href'     => $this->url->link('target/target', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
                                    if ($this->user->hasPermission('access', 'target/target')) {
				$storetargert[] = array(
					'name'	   => 'View target',
					'href'     => $this->url->link('target/target/view', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			/*

                                    if ($storetargert) {
				$data['menus'][] = array(
					'id'       => 'storetargert',
					'icon'	   => 'fa fa-dot-circle-o  fa-fw', 
					'name'	   => 'Center target',
					'href'     => '',
					'children' => $storetargert
				);
			}
			*/
			/*store's subsidy*/			

			$storesubsidy=array();
		        if($data['user_group']!="Regional Manager")
		        {
			if ($this->user->hasPermission('access', 'subsidy/subsidy')) {
				$storesubsidy[] = array(
					'name'	   => 'Set subsidy',
					'href'     => $this->url->link('subsidy/subsidy', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
		        }
                                    if ($this->user->hasPermission('access', 'subsidy/subsidy')) {
				$storesubsidy[] = array(
					'name'	   => 'View subsidy',
					'href'     => $this->url->link('subsidy/subsidy/view', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($this->user->hasPermission('access', 'category/category')) {
				$storesubsidy[] = array(
					'name'	   => 'Category subsidy',
					'href'     => $this->url->link('category/category', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}

                                    if ($storesubsidy) {
				$data['menus'][] = array(
					'id'       => 'storetargert',
					'icon'	   => 'fa fa-star-half-o  fa-fw', 
					'name'	   => 'Subsidy',
					'href'     => '',
					'children' => $storesubsidy
				);
			}

			/*runner*/			

			$runnermenu=array();
			
			if ($this->user->hasPermission('access', 'runner/cash_report')) {
				$runnermenu[] = array(
					'name'	   => 'Cash Received Verification',
					'href'     => $this->url->link('runner/cash_report', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
                                    if ($this->user->hasPermission('access', 'runner/cash_report')) {
				$runnermenu[] = array(
					'name'	   => 'Cash Deposit Form(Bank)',
					'href'     => $this->url->link('runner/cash_report/cash_deposit', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($this->user->hasPermission('access', 'tag/billsubmission')) {
				$runnermenu[] = array(
					'name'	   => 'Bill submission form(Unit)',
					'href'     => $this->url->link('tag/billsubmission', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
                                          if ($this->user->hasPermission('access', 'expense/expense')) {
				$runnermenu[] = array(
					'name'	   => 'Expense Bill submission form',
					'href'     => $this->url->link('expense/expense', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
/*
                                    if ($runnermenu) {
				$data['menus'][] = array(
					'id'       => 'runnerid',
					'icon'	   => 'fa fa-briefcase  fa-fw', 
					'name'	   => 'Collection Executive',
					'href'     => '',
					'children' => $runnermenu
				);
			}
	*/
/*ase*/			

			$asemenu=array();
			
			if ($this->user->hasPermission('access', 'ase/ase')) {
				$asemenu[] = array(
					'name'	   => 'Create Village',
					'href'     => $this->url->link('ase/ase/create_village', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($this->user->hasPermission('access', 'ase/asereports')) {
				$asemenu[] = array(
					'name'	   => 'View Village',
					'href'     => $this->url->link('ase/asereports/getVillage', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}

			if ($this->user->hasPermission('access', 'ase/asereports')) {
				$asemenu[] = array(
					'name'	   => 'ASE Summary',
					'href'     => $this->url->link('ase/asereports/summary', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
                                         if ($this->user->hasPermission('access', 'ase/asereports')) {
				$asemenu[] = array(
					'name'	   => 'Village Visit Report',
					'href'     => $this->url->link('ase/asereports/village_visit', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($this->user->hasPermission('access', 'ase/asereports')) {
				$asemenu[] = array(
					'name'	   => 'View All ASE',
					'href'     => $this->url->link('ase/asereports/aselist', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
                                          if ($this->user->hasPermission('access', 'ase/asereports')) {
				$asemenu[] = array(
					'name'	   => 'Get My farmers',
					'href'     => $this->url->link('ase/asereports', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
                                          if ($this->user->hasPermission('access', 'lead/orderleads')) {
				$asemenu[] = array(
					'name'	   => 'Order Booked by ASE',
					'href'     => $this->url->link('lead/orderleads&filter_order_status=1&filter_addeddby=26', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
                                          if ($this->user->hasPermission('access', 'lead/orderleads')) {
				$asemenu[] = array(
					'name'	   => 'Order Converted',
					'href'     => $this->url->link('lead/orderleads&filter_order_status=5&filter_addeddby=26', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			/*

                                    if ($asemenu) {
				$data['menus'][] = array(
					'id'       => 'asemenu',
					'icon'	   => 'fa fa-road  fa-fw', 
					'name'	   => 'ASE',
					'href'     => '',
					'children' => $asemenu
				);
			}
			*/
                                           /*accountant*/			

			$acountmenu=array();
			 
			if ($this->user->hasPermission('access', 'cash/verify')) {
				$acountmenu[] = array(
					'name'	   => 'Cash Deposited by CE Verification',
					'href'     => $this->url->link('cash/verify/verify_runner_deposit', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
		                                   	
		if ($this->user->hasPermission('access', 'tag/bills')) {
				$acountmenu[] = array(
					'name'	   => 'Bill approved by account',
					'href'     => $this->url->link('tag/bills/getlist', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			/* 
			if ($this->user->hasPermission('access', 'payout/payoutdtl')) {
				
				$acountmenu[] = array(
					
						'name'	   => 'Credit Posting',
					
						'href'     => $this->url->link('payout/payoutdtl/payoutlist', 'token=' . $this->session->data['token'], true),
					
						'children' => array()		
				
						);	
			
			}
			*/
			
			if ($this->user->hasPermission('access', 'partner/bank_payment')) {
				
				$acountmenu[] = array(
					
						'name'	   => 'Credit Posting',
					
						'href'     => $this->url->link('partner/bank_payment/getlist', 'token=' . $this->session->data['token'], true),
					
						'children' => array()		
				
						);	
			
			}
			if ($this->user->hasPermission('access', 'partner/cash_adjustment')) {
				
				$acountmenu[] = array(
					
						'name'	   => 'Partner Cash In-Hand adjustment ',
					
						'href'     => $this->url->link('partner/cash_adjustment', 'token=' . $this->session->data['token'], true),
					
						'children' => array()		
				
						);	
			
			}
			
			if ($this->user->hasPermission('access', 'material/discard')) 
			{
				
				$acountmenu[] = array(
					
						'name'	   => 'Material Discard',
					
						'href'     => $this->url->link('material/discard', 'token=' . $this->session->data['token'], true),
					
						'children' => array()		
				
						);	
			
			}
			if ($this->user->hasPermission('access', 'secuirity/secuirity')) 
			{
				$acountmenu[] = array(
					
						'name'	   => 'Security Deposit',
					
						'href'     => $this->url->link('secuirity/secuirity', 'token=' . $this->session->data['token'], true),
					
						'children' => array()		
				
						);	
			
			}
            if ($this->user->hasPermission('access', 'margin/margin')) 
			{
				$acountmenu[] = array(
					
						'name'	   => 'Partner Margin',
					
						'href'     => $this->url->link('margin/margin', 'token=' . $this->session->data['token'], true),
					
						'children' => array()		
				
						);	
			
			}                        if ($acountmenu) {
				$data['menus'][] = array(
					'id'       => 'accountid',
					'icon'	   => 'fa fa-university  fa-fw', 
					'name'	   => 'Account',
					'href'     => '',
					'children' => $acountmenu
				);
			}
  /*hr*/			

			$hrmenu=array();
			
			if ($this->user->hasPermission('access', 'hr/expense')) {
				$hrmenu[] = array(
					'name'	   => 'Expense Book',
					'href'     => $this->url->link('hr/expense', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
                                    		if ($this->user->hasPermission('access', 'hr/expenseapprove')) {
				$hrmenu[] = array(
					'name'	   => 'Expense Approve',
					'href'     => $this->url->link('hr/expenseapprove', 'token=' . $this->session->data['token'], true),
					'children' => array()	 	
				);	
			}
                                          if ($this->user->hasPermission('access', 'hr/expenseview')) {
				$hrmenu[] = array(
					'name'	   => 'Expense View',
					'href'     => $this->url->link('hr/expenseview', 'token=' . $this->session->data['token'], true),
					'children' => array()	 	
				);	
			}
                                    if ($hrmenu) {
				$data['menus'][] = array(
					'id'       => 'hrid',
					'icon'	   => 'fa fa-inr  fa-fw', 
					'name'	   => 'Store Expense',
					'href'     => '',
					'children' => $hrmenu
				);
			}

 			$expensemenu=array();
			//if($data['user_group']=="Administrator")
                                          //{
			if ($this->user->hasPermission('access', 'hr/expenseapp')) {
				$expensemenu[] = array(
					'name'	   => 'Expense Bill',
					'href'     => $this->url->link('hr/expenseapp', 'token=' . $this->session->data['token'], true),
					'children' => array()	 	
				);	
			}
                            	//} 
			if ($this->user->hasPermission('access', 'hr/expenseappview')) {
				$expensemenu[] = array(
					'name'	   => 'Expense Bill (View)',
					'href'     => $this->url->link('hr/expenseappview', 'token=' . $this->session->data['token'], true),
					'children' => array()	 	
				);	
			}      	
			if ($this->user->hasPermission('access', 'hr/expenseapp')) {
				$expensemenu[] = array(
					'name'	   => 'Reimbursement Bill List',
					'href'     => $this->url->link('hr/expenseapp/reimbursement', 'token=' . $this->session->data['token'], true),
					'children' => array()	 	
				);	
			}
                               
			if ($this->user->hasPermission('access', 'expense/expensebalance')) {
				$expensemenu[] = array(
					'name'	   => 'Expense balance',
					'href'     => $this->url->link('expense/expensebalance/expenselist', 'token=' . $this->session->data['token'], true),
					'children' => array()	 	
				);	
			}
           
                                    if ($expensemenu) {
				$data['menus'][] = array(
					'id'       => 'hrid',
					'icon'	   => 'fa fa-money  fa-fw', 
					'name'	   => 'Expense',
					'href'     => '',
					'children' => $expensemenu
				);
			}

			/*rquantity reversal*/			

			$reversalmenu=array();
			
			
                                    if ($this->user->hasPermission('access', 'reversal/reversal')) {
				$reversalmenu[] = array(
					'name'	   => 'Quantity Reversal',
					'href'     => $this->url->link('reversal/reversal/trans', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}

                                    if ($reversalmenu) {
				$data['menus'][] = array(
					'id'       => 'reversalid',
					'icon'	   => 'fa fa-reply',   
					'name'	   => 'Quantity reversal',
					'href'     => '',
					'children' => $reversalmenu
				);
			}
			/*ccare*/			

			$ccare=array();
			if ($this->user->hasPermission('access', 'ccare/incommingcall')) {
				$ccare[] = array(
					'name'	   => 'Incoming Call',
					'href'     => $this->url->link('ccare/incommingcall', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			/*
			if ($this->user->hasPermission('access', 'ccare/ccare')) {
				$ccare[] = array(
					'name'	   => 'Recharge Call',
					'href'     => $this->url->link('ccare/ccare/rechargerecord', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			if ($this->user->hasPermission('access', 'ccare/ccare')) {
				$ccare[] = array(
					'name'	   => 'Customer care (Pending)',
					'href'     => $this->url->link('ccare/ccare', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
                        
                                        if ($this->user->hasPermission('access', 'ccare/ccare')) {
				$ccare[] = array(
					'name'	   => 'Customer care (Completed)',
					'href'     => $this->url->link('ccare/ccare/completed', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
 			if ($this->user->hasPermission('access', 'ccare/ccare')) {
				$ccare[] = array(
					'name'	   => 'Customer care (Farmers)',
					'href'     => $this->url->link('ccare/ccare/customer', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			*/
                        if ($ccare) {
				$data['menus'][] = array(
					'id'       => 'ccare',
					'icon'	   => 'fa fa-phone', 
					'name'	   => 'Customer care',
					'href'     => '',
					'children' => $ccare
				);
			}

            //////////////////////////////////
$tagpos=array();
if ($this->user->hasPermission('access', 'tagpos/tagpos')) {
$tagpos[] = array(
'name' => 'Tag POS',
'href' => $this->url->link('tagpos/tagpos', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}

if ($this->user->hasPermission('access', 'tagpos/fmdelivery')) {
$tagpos[] = array(
'name' => 'FM Delivery',
'href' => $this->url->link('tagpos/fmdelivery', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}


if ($this->user->hasPermission('access', 'tagpos/fmdelivery')) {
$tagpos[] = array(
'name' => 'Add FM Loan Inventory',
'href' => $this->url->link('tagpos/loan_inventory', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}

if ($this->user->hasPermission('access', 'tagpos/fmdelivery')) {
$tagpos[] = array(
'name' => 'FM Issue Transaction',
'href' => $this->url->link('tagpos/loan_inventory/fm_loan_inventory_isseu_trans', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}


if ($this->user->hasPermission('access', 'tagpos/fmdelivery')) {
$tagpos[] = array(
'name' => 'Report FM Loan Inventory',
'href' => $this->url->link('tagpos/loan_inventory/fm_loan_inventory', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}



if ($this->user->hasPermission('access', 'tagpos/fmdelivery')) {
$tagpos[] = array(
'name' => 'Product Sale FM Wise',
'href' => $this->url->link('tagpos/fmdelivery/productsalefmwise', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}

if ($this->user->hasPermission('access', 'tagpos/fmdelivery')) {
$tagpos[] = array(
'name' => 'Product Sale FM Wise (Cash)',
'href' => $this->url->link('tagpos/fmdelivery/productsalefmwisecash', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}

if ($this->user->hasPermission('access', 'tagpos/upload')) {
$tagpos[] = array(
'name' => 'Upload Excel',
'href' => $this->url->link('tagpos/upload', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}

if ($this->user->hasPermission('access', 'tagpos/tagpos')) {
$tagpos[] = array(
'name' => 'FM Cash Sale',
'href' => $this->url->link('report/sale_summary/fm_cash_sale_report', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}
if ($this->user->hasPermission('access', 'tagpos/tagpos')) {
$tagpos[] = array(
'name' => 'Batch',
'href' => $this->url->link('tagpos/fmdelivery/getbatch', 'token=' . $this->session->data['token'], true),
'children' => array()
);
}
if ($tagpos) {
$data['menus'][] = array(
'id' => 'menu-catalog',
'icon' => 'fa-book',
'name' => 'Tag POS',
'href' => '',
'children' => $tagpos
);
}   
			// System
			$system = array();
			
			if ($this->user->hasPermission('access', 'setting/setting')) {
				$system[] = array(
					'name'	   => $this->language->get('text_setting'),
					'href'     => $this->url->link('setting/store', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			/*
			if ($this->user->hasPermission('access', 'setting/auditstore')) {
				$system[] = array(
					'name'	   => 'Update Store Audit  Amount',
					'href'     => $this->url->link('setting/auditstore', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'setting/debitstore')) {
				
				$system[] = array(
					
						'name'	   => 'Store Write Off',
					
						'href'     => $this->url->link('setting/debitstore', 'token=' . $this->session->data['token'], true),
					
						'children' => array()		
				
						);	
			
			}
			if ($this->user->hasPermission('access', 'setting/debitstore')) {
				
				$system[] = array(
					
						'name'	   => 'Expense Waiver off',
					
						'href'     => $this->url->link('setting/debitstore/getWaveoffdata', 'token=' . $this->session->data['token'], true),
					
						'children' => array()		
				
						);	
			
			}
			*/
			
			if ($this->user->hasPermission('access', 'company/company')) { 
				
				$system[] = array(
					
						'name'	   => 'Companies',
					
						'href'     => $this->url->link('company/company', 'token=' . $this->session->data['token'], true),
					
						'children' => array()		
				
						);	
			
			}
			if ($this->user->hasPermission('access', 'unit/unit')) {
				
				$system[] = array(
					
						'name'	   => 'Factory Units',
					
						'href'     => $this->url->link('unit/unit', 'token=' . $this->session->data['token'], true),
					
						'children' => array()		
				
						);	
			
			}
			if ($this->user->hasPermission('access', 'catalog/storemenu')) 
			{
				
				$system[] = array(
					
						'name'	   => 'Store App Menu',
					
						'href'     => $this->url->link('catalog/storemenu', 'token=' . $this->session->data['token'], true),
					
						'children' => array()		
				
						);	
			
			}
		
			if ($this->user->hasPermission('access', 'setting/billcontrol')) 
			{
				
				$system[] = array(
					
						'name'	   => 'Billing Control',
					
						'href'     => $this->url->link('setting/billcontrol', 'token=' . $this->session->data['token'], true),
					
						'children' => array()		
				
						);	
			
			}
			
			 if ($this->user->hasPermission('access', 'sms/smslist')) {
				$system[] = array(
					'name'	   => 'SMS Sender',
					'href'     => $this->url->link('sms/smslist', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			} 

			// Users
			$user = array();
			
			if ($this->user->hasPermission('access', 'user/user')) {
				$user[] = array(
					'name'	   => $this->language->get('text_users'),
					'href'     => $this->url->link('user/user', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'user/user_permission')) {	
				$user[] = array(
					'name'	   => $this->language->get('text_user_group'),
					'href'     => $this->url->link('user/user_permission', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'user/api')) {		
				$user[] = array(
					'name'	   => $this->language->get('text_api'),
					'href'     => $this->url->link('user/api', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($user) {
				$system[] = array(
					'name'	   => $this->language->get('text_users'),
					'href'     => '',
					'children' => $user		
				);
			}
// Design
			$design = array();
			
			if ($this->user->hasPermission('access', 'design/layout')) {
				$design[] = array(
					'name'	   => $this->language->get('text_layout'),
					'href'     => $this->url->link('design/layout', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			/*
			if ($this->user->hasPermission('access', 'design/menu')) {
				$design[] = array(
					'name'	   => $this->language->get('text_menu'),
					'href'     => $this->url->link('design/menu', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			*/	
			/*	
			if ($this->user->hasPermission('access', 'design/theme')) {	
				$design[] = array(
					'name'	   => $this->language->get('text_theme'),
					'href'     => $this->url->link('design/theme', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'design/language')) {
				$design[] = array(
					'name'	   => $this->language->get('text_translation'),
					'href'     => $this->url->link('design/language', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			*/	
			if ($this->user->hasPermission('access', 'design/banner')) {
				$design[] = array(
					'name'	   => $this->language->get('text_banner'),
					'href'     => $this->url->link('design/banner', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			

						if ($design) {																
				$system[] = array(
					'name'	   => $this->language->get('text_design'),
					'href'     => '',
					'children' => $design
				);
			}

			// Localisation
			$localisation = array();
			
			if ($this->user->hasPermission('access', 'localisation/location')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_location'),
					'href'     => $this->url->link('localisation/location', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'localisation/language')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_language'),
					'href'     => $this->url->link('localisation/language', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/currency')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_currency'),
					'href'     => $this->url->link('localisation/currency', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/stock_status')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_stock_status'),
					'href'     => $this->url->link('localisation/stock_status', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/order_status')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_order_status'),
					'href'     => $this->url->link('localisation/order_status', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			// Returns
			$return = array();
			
			if ($this->user->hasPermission('access', 'localisation/return_status')) {
				$return[] = array(
					'name'	   => $this->language->get('text_return_status'),
					'href'     => $this->url->link('localisation/return_status', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/return_action')) {
				$return[] = array(
					'name'	   => $this->language->get('text_return_action'),
					'href'     => $this->url->link('localisation/return_action', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);		
			}
			
			if ($this->user->hasPermission('access', 'localisation/return_reason')) {
				$return[] = array(
					'name'	   => $this->language->get('text_return_reason'),
					'href'     => $this->url->link('localisation/return_reason', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($return) {	
				$localisation[] = array(
					'name'	   => $this->language->get('text_return'),
					'href'     => '',
					'children' => $return		
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/country')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_country'),
					'href'     => $this->url->link('localisation/country', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/zone')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_zone'),
					'href'     => $this->url->link('localisation/zone', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/geo_zone')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_geo_zone'),
					'href'     => $this->url->link('localisation/geo_zone', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			// Tax		
			$tax = array();
			
			if ($this->user->hasPermission('access', 'localisation/tax_class')) {
				$tax[] = array(
					'name'	   => $this->language->get('text_tax_class'),
					'href'     => $this->url->link('localisation/tax_class', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/tax_rate')) {
				$tax[] = array(
					'name'	   => $this->language->get('text_tax_rate'),
					'href'     => $this->url->link('localisation/tax_rate', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($tax) {	
				$localisation[] = array(
					'name'	   => $this->language->get('text_tax'),
					'href'     => '',
					'children' => $tax		
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/length_class')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_length_class'),
					'href'     => $this->url->link('localisation/length_class', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'localisation/weight_class')) {
				$localisation[] = array(
					'name'	   => $this->language->get('text_weight_class'),
					'href'     => $this->url->link('localisation/weight_class', 'token=' . $this->session->data['token'], true),
					'children' => array()
				);
			}
			
			if ($localisation) {																
				$system[] = array(
					'name'	   => $this->language->get('text_localisation'),
					'href'     => '',
					'children' => $localisation	
				);
			}
			
			// Tools	
			$tool = array();
			if ($this->user->hasPermission('access', 'tool/logfile')) 
			{
				$tool[] = array(
					'name'	   => 'Log Files',
					'href'     => $this->url->link('tool/logfile', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			if ($this->user->hasPermission('access', 'tool/upload')) {
				$tool[] = array(
					'name'	   => $this->language->get('text_upload'),
					'href'     => $this->url->link('tool/upload', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);	
			}
			
			if ($this->user->hasPermission('access', 'tool/backup')) {
				$tool[] = array(
					'name'	   => $this->language->get('text_backup'),
					'href'     => $this->url->link('tool/backup', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
			
			if ($this->user->hasPermission('access', 'tool/error_log')) {
				$tool[] = array(
					'name'	   => $this->language->get('text_error_log'),
					'href'     => $this->url->link('tool/error_log', 'token=' . $this->session->data['token'], true),
					'children' => array()		
				);
			}
		
			
			
			if ($tool) {
				$system[] = array(
					'name'	   => $this->language->get('text_tools'),
					'href'     => '',
					'children' => $tool	
				);
			}
			
			if ($system) {
				$data['menus'][] = array(
					'id'       => 'menu-system',
					'icon'	   => 'fa-cog', 
					'name'	   => $this->language->get('text_system'),
					'href'     => '',
					'children' => $system
				);
			}
			 


			// Stats
			$data['text_complete_status'] = $this->language->get('text_complete_status');
			$data['text_processing_status'] = $this->language->get('text_processing_status');
			$data['text_other_status'] = $this->language->get('text_other_status');
	
			$this->load->model('sale/order');
	
			$order_total = array();//$this->model_sale_order->getTotalOrders();
	
			$complete_total = array();//$this->model_sale_order->getTotalOrders(array('filter_order_status' => implode(',', $this->config->get('config_complete_status'))));
			
			if ($complete_total) {
				$data['complete_status'] = round(($complete_total / $order_total) * 100);
			} else {
				$data['complete_status'] = 0;
			}
	
			$processing_total =array();// $this->model_sale_order->getTotalOrders(array('filter_order_status' => implode(',', $this->config->get('config_processing_status'))));
	
			if ($processing_total) {
				$data['processing_status'] = round(($processing_total / $order_total) * 100);
			} else {
				$data['processing_status'] = 0;
			}
	
			$this->load->model('localisation/order_status');
	
			$order_status_data = array();
	
			$results =array();// $this->model_localisation_order_status->getOrderStatuses();
	
			foreach ($results as $result) {
				if (!in_array($result['order_status_id'], array_merge($this->config->get('config_complete_status'), $this->config->get('config_processing_status')))) {
					$order_status_data[] = $result['order_status_id'];
				}
			}
	
			$other_total = array();//$this->model_sale_order->getTotalOrders(array('filter_order_status' => implode(',', $order_status_data)));
	
			if ($other_total) {
				$data['other_status'] = round(($other_total / $order_total) * 100);
			} else {
				$data['other_status'] = 0;
			}
		/*Inventory*/
/*		$data['reports_text'] = $this->language->get('reports');
		$data['received_orders_text'] = $this->language->get('received_orders');
		$data['pending_orders_text'] = $this->language->get('pending_orders');
		$data['returns_text'] = $this->language->get('returns_text');
		$data['chart_text'] = $this->language->get('chart_text');
		$data['purchase_chart_text'] = $this->language->get('purchase_chart_text');
		$data['sale_chart_text'] = $this->language->get('sale_chart_text');
		$data['dead_chart_text'] = $this->language->get('dead_chart_text');
		$data['stock_report_text'] = $this->language->get('stock_report_text');
		$data['stock_inout_text'] = $this->language->get('stock_inout_text');
		$data['dead_products_text'] = $this->language->get('dead_products_text');
		$data['best_products_text'] = $this->language->get('best_products_text');
		$data['sale_offer_text'] = $this->language->get('sale_offer_text');
		/*Inventory*/
	/*	$data['text_analytics'] = $this->language->get('text_analytics');
		$data['token'] = $this->session->data['token'];
		$data['text_affiliate'] = $this->language->get('text_affiliate');
		$data['text_api'] = $this->language->get('text_api');
		$data['text_attribute'] = $this->language->get('text_attribute');
		$data['text_attribute_group'] = $this->language->get('text_attribute_group');
		$data['text_backup'] = $this->language->get('text_backup');
		$data['text_banner'] = $this->language->get('text_banner');
		$data['text_captcha'] = $this->language->get('text_captcha');
		$data['text_catalog'] = $this->language->get('text_catalog');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_contact'] = $this->language->get('text_contact');
		$data['text_country'] = $this->language->get('text_country');
		$data['text_coupon'] = $this->language->get('text_coupon');
		$data['text_currency'] = $this->language->get('text_currency');
		$data['text_customer'] = $this->language->get('text_customer');
		$data['text_customer_group'] = $this->language->get('text_customer_group');
		$data['text_customer_field'] = $this->language->get('text_customer_field');
		$data['text_custom_field'] = $this->language->get('text_custom_field');
		$data['text_sale'] = $this->language->get('text_sale');
		$data['text_paypal'] = $this->language->get('text_paypal');
		$data['text_paypal_search'] = $this->language->get('text_paypal_search');
		$data['text_design'] = $this->language->get('text_design');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_error_log'] = $this->language->get('text_error_log');
		$data['text_extension'] = $this->language->get('text_extension');
		$data['text_feed'] = $this->language->get('text_feed');
		$data['text_fraud'] = $this->language->get('text_fraud');
		$data['text_filter'] = $this->language->get('text_filter');
		$data['text_geo_zone'] = $this->language->get('text_geo_zone');
		$data['text_dashboard'] = $this->language->get('text_dashboard');
		$data['text_help'] = $this->language->get('text_help');
		$data['text_information'] = $this->language->get('text_information');
		$data['text_installer'] = $this->language->get('text_installer');
		$data['text_language'] = $this->language->get('text_language');
		$data['text_layout'] = $this->language->get('text_layout');
		$data['text_localisation'] = $this->language->get('text_localisation');
		$data['text_location'] = $this->language->get('text_location');
		$data['text_marketing'] = $this->language->get('text_marketing');
		$data['text_modification'] = $this->language->get('text_modification');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_module'] = $this->language->get('text_module');
		$data['text_option'] = $this->language->get('text_option');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_order_status'] = $this->language->get('text_order_status');
		$data['text_opencart'] = $this->language->get('text_opencart');
		$data['text_payment'] = $this->language->get('text_payment');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_reports'] = $this->language->get('text_reports');
		$data['text_report_sale_order'] = $this->language->get('text_report_sale_order');
		$data['text_report_sale_tax'] = $this->language->get('text_report_sale_tax');
		$data['text_report_sale_shipping'] = $this->language->get('text_report_sale_shipping');
		$data['text_report_sale_return'] = $this->language->get('text_report_sale_return');
		$data['text_report_sale_coupon'] = $this->language->get('text_report_sale_coupon');
		$data['text_report_sale_return'] = $this->language->get('text_report_sale_return');
		$data['text_report_product_viewed'] = $this->language->get('text_report_product_viewed');
		$data['text_report_product_purchased'] = $this->language->get('text_report_product_purchased');
		$data['text_report_customer_activity'] = $this->language->get('text_report_customer_activity');
		$data['text_report_customer_online'] = $this->language->get('text_report_customer_online');
		$data['text_report_customer_order'] = $this->language->get('text_report_customer_order');
		$data['text_report_customer_reward'] = $this->language->get('text_report_customer_reward');
		$data['text_report_customer_credit'] = $this->language->get('text_report_customer_credit');
		$data['text_report_customer_order'] = $this->language->get('text_report_customer_order');
		$data['text_report_affiliate'] = $this->language->get('text_report_affiliate');
		$data['text_report_affiliate_activity'] = $this->language->get('text_report_affiliate_activity');
		$data['text_review'] = $this->language->get('text_review');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_return_action'] = $this->language->get('text_return_action');
		$data['text_return_reason'] = $this->language->get('text_return_reason');
		$data['text_return_status'] = $this->language->get('text_return_status');
		$data['text_shipping'] = $this->language->get('text_shipping');
		$data['text_setting'] = $this->language->get('text_setting');
		$data['text_stock_status'] = $this->language->get('text_stock_status');
		$data['text_system'] = $this->language->get('text_system');
		$data['text_tax'] = $this->language->get('text_tax');
		$data['text_tax_class'] = $this->language->get('text_tax_class');
		$data['text_tax_rate'] = $this->language->get('text_tax_rate');
		$data['text_theme'] = $this->language->get('text_theme');
		$data['text_tools'] = $this->language->get('text_tools');
		$data['text_total'] = $this->language->get('text_total');
		$data['text_upload'] = $this->language->get('text_upload');
		$data['text_tracking'] = $this->language->get('text_tracking');
		$data['text_user'] = $this->language->get('text_user');
		$data['text_user_group'] = $this->language->get('text_user_group');
		$data['text_users'] = $this->language->get('text_users');
		$data['text_voucher'] = $this->language->get('text_voucher');
		$data['text_voucher_theme'] = $this->language->get('text_voucher_theme');
		$data['text_weight_class'] = $this->language->get('text_weight_class');
		$data['text_length_class'] = $this->language->get('text_length_class');
		$data['text_zone'] = $this->language->get('text_zone');
		$data['text_recurring'] = $this->language->get('text_recurring');
		$data['text_order_recurring'] = $this->language->get('text_order_recurring');
		$data['text_openbay_extension'] = $this->language->get('text_openbay_extension');
		$data['text_openbay_dashboard'] = $this->language->get('text_openbay_dashboard');
		$data['text_openbay_orders'] = $this->language->get('text_openbay_orders');
		$data['text_openbay_items'] = $this->language->get('text_openbay_items');
		$data['text_openbay_ebay'] = $this->language->get('text_openbay_ebay');
		$data['text_openbay_etsy'] = $this->language->get('text_openbay_etsy');
		$data['text_openbay_amazon'] = $this->language->get('text_openbay_amazon');
		$data['text_openbay_amazonus'] = $this->language->get('text_openbay_amazonus');
		$data['text_openbay_settings'] = $this->language->get('text_openbay_settings');
		$data['text_openbay_links'] = $this->language->get('text_openbay_links');
		$data['text_openbay_report_price'] = $this->language->get('text_openbay_report_price');
		$data['text_openbay_order_import'] = $this->language->get('text_openbay_order_import');

		$data['analytics'] = $this->url->link('extension/analytics', 'token=' . $this->session->data['token'], true);
		$data['home'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true);
		$data['affiliate'] = $this->url->link('marketing/affiliate', 'token=' . $this->session->data['token'], true);
		$data['api'] = $this->url->link('user/api', 'token=' . $this->session->data['token'], true);
		$data['attribute'] = $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'], true);
		$data['attribute_group'] = $this->url->link('catalog/attribute_group', 'token=' . $this->session->data['token'], true);
		$data['backup'] = $this->url->link('tool/backup', 'token=' . $this->session->data['token'], true);
		$data['banner'] = $this->url->link('design/banner', 'token=' . $this->session->data['token'], true);
		$data['captcha'] = $this->url->link('extension/captcha', 'token=' . $this->session->data['token'], true);
		$data['category'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'], true);
		$data['country'] = $this->url->link('localisation/country', 'token=' . $this->session->data['token'], true);
		$data['contact'] = $this->url->link('marketing/contact', 'token=' . $this->session->data['token'], true);
		$data['coupon'] = $this->url->link('marketing/coupon', 'token=' . $this->session->data['token'], true);
		$data['currency'] = $this->url->link('localisation/currency', 'token=' . $this->session->data['token'], true);
		$data['customer'] = $this->url->link('customer/customer', 'token=' . $this->session->data['token'], true);
		$data['customer_fields'] = $this->url->link('customer/customer_field', 'token=' . $this->session->data['token'], true);
		$data['customer_group'] = $this->url->link('customer/customer_group', 'token=' . $this->session->data['token'], true);
		$data['custom_field'] = $this->url->link('customer/custom_field', 'token=' . $this->session->data['token'], true);
		$data['download'] = $this->url->link('catalog/download', 'token=' . $this->session->data['token'], true);
		$data['error_log'] = $this->url->link('tool/error_log', 'token=' . $this->session->data['token'], true);
		$data['feed'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], true);
		$data['filter'] = $this->url->link('catalog/filter', 'token=' . $this->session->data['token'], true);
		$data['fraud'] = $this->url->link('extension/fraud', 'token=' . $this->session->data['token'], true);
		$data['geo_zone'] = $this->url->link('localisation/geo_zone', 'token=' . $this->session->data['token'], true);
		$data['information'] = $this->url->link('catalog/information', 'token=' . $this->session->data['token'], true);
		$data['installer'] = $this->url->link('extension/installer', 'token=' . $this->session->data['token'], true);
		$data['language'] = $this->url->link('localisation/language', 'token=' . $this->session->data['token'], true);
		$data['layout'] = $this->url->link('design/layout', 'token=' . $this->session->data['token'], true);
		$data['location'] = $this->url->link('localisation/location', 'token=' . $this->session->data['token'], true);
		$data['modification'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'], true);
		$data['manufacturer'] = $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'], true);
		$data['marketing'] = $this->url->link('marketing/marketing', 'token=' . $this->session->data['token'], true);
		$data['module'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], true);
		$data['option'] = $this->url->link('catalog/option', 'token=' . $this->session->data['token'], true);
		$data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], true);
		$data['order_status'] = $this->url->link('localisation/order_status', 'token=' . $this->session->data['token'], true);
		$data['payment'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true);
		$data['paypal_search'] = $this->url->link('payment/pp_express/search', 'token=' . $this->session->data['token'], true);
		$data['product'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'], true);
		$data['report_sale_order'] = $this->url->link('report/sale_order', 'token=' . $this->session->data['token'], true);
		$data['report_sale_tax'] = $this->url->link('report/sale_tax', 'token=' . $this->session->data['token'], true);
		$data['report_sale_shipping'] = $this->url->link('report/sale_shipping', 'token=' . $this->session->data['token'], true);
		$data['report_sale_return'] = $this->url->link('report/sale_return', 'token=' . $this->session->data['token'], true);
		$data['report_sale_coupon'] = $this->url->link('report/sale_coupon', 'token=' . $this->session->data['token'], true);
		$data['report_product_viewed'] = $this->url->link('report/product_viewed', 'token=' . $this->session->data['token'], true);
		$data['report_product_purchased'] = $this->url->link('report/product_purchased', 'token=' . $this->session->data['token'], true);
		$data['report_customer_activity'] = $this->url->link('report/customer_activity', 'token=' . $this->session->data['token'], true);
		$data['report_customer_online'] = $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], true);
		$data['report_customer_order'] = $this->url->link('report/customer_order', 'token=' . $this->session->data['token'], true);
		$data['report_customer_reward'] = $this->url->link('report/customer_reward', 'token=' . $this->session->data['token'], true);
		$data['report_customer_credit'] = $this->url->link('report/customer_credit', 'token=' . $this->session->data['token'], true);
		$data['report_marketing'] = $this->url->link('report/marketing', 'token=' . $this->session->data['token'], true);
		$data['report_affiliate'] = $this->url->link('report/affiliate', 'token=' . $this->session->data['token'], true);
		$data['report_affiliate_activity'] = $this->url->link('report/affiliate_activity', 'token=' . $this->session->data['token'], true);
		$data['review'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'], true);
		$data['return'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'], true);
		$data['return_action'] = $this->url->link('localisation/return_action', 'token=' . $this->session->data['token'], true);
		$data['return_reason'] = $this->url->link('localisation/return_reason', 'token=' . $this->session->data['token'], true);
		$data['return_status'] = $this->url->link('localisation/return_status', 'token=' . $this->session->data['token'], true);
		$data['shipping'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], true);
		$data['setting'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], true);
		$data['stock_status'] = $this->url->link('localisation/stock_status', 'token=' . $this->session->data['token'], true);
		$data['tax_class'] = $this->url->link('localisation/tax_class', 'token=' . $this->session->data['token'], true);
		$data['tax_rate'] = $this->url->link('localisation/tax_rate', 'token=' . $this->session->data['token'], true);
		$data['theme'] = $this->url->link('extension/theme', 'token=' . $this->session->data['token'], true);
		$data['total'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], true);
		$data['upload'] = $this->url->link('tool/upload', 'token=' . $this->session->data['token'], true);
		$data['user'] = $this->url->link('user/user', 'token=' . $this->session->data['token'], true);
		$data['user_group'] = $this->url->link('user/user_permission', 'token=' . $this->session->data['token'], true);
		$data['voucher'] = $this->url->link('sale/voucher', 'token=' . $this->session->data['token'], true);
		$data['voucher_theme'] = $this->url->link('sale/voucher_theme', 'token=' . $this->session->data['token'], true);
		$data['weight_class'] = $this->url->link('localisation/weight_class', 'token=' . $this->session->data['token'], true);
		$data['length_class'] = $this->url->link('localisation/length_class', 'token=' . $this->session->data['token'], true);
		$data['zone'] = $this->url->link('localisation/zone', 'token=' . $this->session->data['token'], true);
		$data['recurring'] = $this->url->link('catalog/recurring', 'token=' . $this->session->data['token'], true);
		$data['order_recurring'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'], true);
		/*Inventory link*/
		
/*		$data['purchase_order'] = $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'],true);
		$data['supplier'] = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'],true);
		$data['supplier_group'] = $this->url->link('purchase/supplier_group', 'token=' . $this->session->data['token'],true);
		$data['received_orders'] = $this->url->link('purchase/received_orders','token=' . $this->session->data['token'],true);
		$data['pending_orders'] = $this->url->link('purchase/pending_orders','token=' . $this->session->data['token'],true);
		$data['return_orders'] = $this->url->link('purchase/return_orders','token=' . $this->session->data['token'],true);
		$data['purchase_chart'] = $this->url->link('purchase/chart/purchase_chart', 'token=' . $this->session->data['token'],true);
		$data['sale_chart'] = $this->url->link('purchase/chart', 'token=' . $this->session->data['token'],true);
		$data['dead_chart'] = $this->url->link('purchase/chart/dead_chart', 'token=' . $this->session->data['token'],true);
		
		$data['stock_report'] = $this->url->link('purchase/stock_report', 'token=' . $this->session->data['token'],true);
		$data['stock_inout'] = $this->url->link('purchase/stock_report/stock_inout', 'token=' . $this->session->data['token'],true);
		$data['dead_products'] = $this->url->link('purchase/stock_report/dead_products', 'token=' . $this->session->data['token'],true);
		$data['best_products'] = $this->url->link('purchase/stock_report/best_products', 'token=' . $this->session->data['token'],true);
		
		$data['sale_offer'] = $this->url->link('purchase/sale_offer', 'token=' . $this->session->data['token'],true);
		
		
		/*Inventory link*/
	/*	$data['openbay_show_menu'] = $this->config->get('openbaypro_menu');
		$data['openbay_link_extension'] = $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], true);
		$data['openbay_link_orders'] = $this->url->link('extension/openbay/orderlist', 'token=' . $this->session->data['token'], true);
		$data['openbay_link_items'] = $this->url->link('extension/openbay/items', 'token=' . $this->session->data['token'], true);
		$data['openbay_link_ebay'] = $this->url->link('openbay/ebay', 'token=' . $this->session->data['token'], true);
		$data['openbay_link_ebay_settings'] = $this->url->link('openbay/ebay/settings', 'token=' . $this->session->data['token'], true);
		$data['openbay_link_ebay_links'] = $this->url->link('openbay/ebay/viewitemlinks', 'token=' . $this->session->data['token'], true);
		$data['openbay_link_etsy'] = $this->url->link('openbay/etsy', 'token=' . $this->session->data['token'], true);
		$data['openbay_link_etsy_settings'] = $this->url->link('openbay/etsy/settings', 'token=' . $this->session->data['token'], true);
		$data['openbay_link_etsy_links'] = $this->url->link('openbay/etsy_product/links', 'token=' . $this->session->data['token'], true);
		$data['openbay_link_ebay_orderimport'] = $this->url->link('openbay/ebay/vieworderimport', 'token=' . $this->session->data['token'], true);
		$data['openbay_link_amazon'] = $this->url->link('openbay/amazon', 'token=' . $this->session->data['token'], true);
		$data['openbay_link_amazon_settings'] = $this->url->link('openbay/amazon/settings', 'token=' . $this->session->data['token'], true);
		$data['openbay_link_amazon_links'] = $this->url->link('openbay/amazon/itemlinks', 'token=' . $this->session->data['token'], true);
		$data['openbay_link_amazonus'] = $this->url->link('openbay/amazonus', 'token=' . $this->session->data['token'], true);
		$data['openbay_link_amazonus_settings'] = $this->url->link('openbay/amazonus/settings', 'token=' . $this->session->data['token'], true);
		$data['openbay_link_amazonus_links'] = $this->url->link('openbay/amazonus/itemlinks', 'token=' . $this->session->data['token'], true);
		$data['openbay_markets'] = array(
			'ebay' => $this->config->get('ebay_status'),
			'amazon' => $this->config->get('openbay_amazon_status'),
			'amazonus' => $this->config->get('openbay_amazonus_status'),
			'etsy' => $this->config->get('etsy_status'),
		);

		  $data['ics_status'] = $this->config->get('ics_status');
            $data['manage_inventory'] = $this->url->link('ics/ics/index', 'token='.$this->session->data['token']); 
            $data['low_stock_products'] = $this->url->link('ics/ics/products', 'token='.$this->session->data['token']);
            $data['barcode_generator'] = $this->url->link('ics/ics/barcode','token='.$this->session->data['token']);
            $data['purchase_orders'] = $this->url->link('ics/dashboard/orders','token='.$this->session->data['token']);
            $data['ics_dashboard'] = $this->url->link('ics/dashboard', 'token='.$this->session->data['token']);
            $data['ics_history'] = $this->url->link('ics/dashboard/history', 'token='.$this->session->data['token']);*/
			
		return $this->load->view('common/menu.tpl', $data); 
	}
}
