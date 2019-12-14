<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">    
       <!-- <button type="submit" id="button-shipping" form="form-order" formaction="<?php echo $shipping; ?>" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-info"><i class="fa fa-truck"></i></button>
        <button type="submit" id="button-invoice" form="form-order" formaction="<?php echo $invoice; ?>" data-toggle="tooltip" title="<?php echo $button_invoice_print; ?>" class="btn btn-info"><i class="fa fa-print"></i></button>
        <a <?php if($group=="11") { echo "style='display:none;'"; } ?> href="<?php echo $insert; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
      -->
     </div>
      <h1>Village List</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">Village List</a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i>Village List</h3>
        
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">   
            
              <div class="col-sm-4"> 
             <div class="form-group">
                <label class="control-label" for="input-date-end">Select Store</label>
               
                      
                  <select name="filter_store" id="input-store" style="width: 100%;" class="select2 form-control">
                   <option selected="selected" value="">All Villages</option>
                     <?php foreach ($stores as $sst) { ?>
                   
                  <option value="<?php echo $sst['store_id']; ?>" <?php if($sst['store_id']==$filter_store) { echo 'selected'; } ?>><?php echo $sst['name']; ?></option>
                    
                  <?php } ?>
                </select>
                
                
              </div> <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Filter
              
                
       
            
               </div>
              
                
               
          </div>        </div>
        <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>               
                   <td class="text-left">Sno.</td>
                   <td class="text-left">Village Name</td>
                   <td class="text-left">Store Name</td>
                   <td class="text-left" style="display:none;">District</td>
                   <td class="text-left" style="display:none;">pincode</td>               
                </tr>
              </thead>
              <tbody>
                <?php if ($orders) { $a=1;?>
                <?php foreach ($orders as $order) { //print_r($order["products_info"]); ?>
                <tr>
                  <td class="text-right"><?php echo $a; ?></td>
                  <td class="text-left"><?php echo $order['village_name']; ?></td>
                  <td class="text-left"><?php echo $order['name']; ?></td>
                  <td class="text-left" style="display:none;"><?php echo $order['district']; ?></td>
                  <td class="text-left" style="display:none;"><?php echo $order['pincode']; ?></td>              
                
                  
              </tr>              <?php $a++; } ?>
              <?php } else { ?>
              <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>


  <script type="text/javascript">
$("#input-store").select2();
<!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=ase/asereports/getVillage&token=<?php echo $token; ?>';
	
		
        var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != '') {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
	       
	location = url;
});
//--></script> 
<script type="text/javascript"><!--
$('#button-download').on('click', function() {
	url = 'index.php?route=tag/order/download_excel&token=<?php echo $token; ?>';
	
	var filter_order_id = $('input[name=\'filter_order_id\']').val();
	
	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}
	
	var filter_customer = $('input[name=\'filter_customer\']').val();
	
	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}
	
	var filter_order_status = $('select[name=\'filter_order_status\']').val();
	
	if (filter_order_status != '*') {
		url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
	}	
              var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != '') {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
	var filter_total = $('input[name=\'filter_total\']').val();

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	}	
	
	var filter_date_added = $('input[name=\'filter_date_added\']').val();
	
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}
	
	var filter_date_potential = $('input[name=\'filter_date_potential\']').val();
	
	if (filter_date_potential) {
		url += '&filter_date_potential=' + encodeURIComponent(filter_date_potential);
	}
        
        
        var filter_unit = $('select[name=\'filter_unit\']').val();
	
	if (filter_unit != 0) {
		url += '&filter_unit=' + encodeURIComponent(filter_unit);
	}
       
	//alert(url);			
	//location = url;
         window.open(url, '_blank');
});
//--></script>
  <script type="text/javascript"><!--
$('input[name=\'filter_customer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['customer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_customer\']').val(item['label']);
	}	
});
//--></script> 
  <script type="text/javascript"><!--
$('input[name^=\'selected\']').on('change', function() {
	$('#button-shipping, #button-invoice').prop('disabled', true);
	
	var selected = $('input[name^=\'selected\']:checked');
	
	if (selected.length) {
		$('#button-invoice').prop('disabled', false);
	}
	
	for (i = 0; i < selected.length; i++) {
		if ($(selected[i]).parent().find('input[name^=\'shipping_code\']').val()) {
			$('#button-shipping').prop('disabled', false);
			
			break;
		}
	}
});

$('input[name^=\'selected\']:first').trigger('change');

$('a[id^=\'button-delete\']').on('click', function(e) {
	e.preventDefault();
	
	if (confirm('<?php echo $text_confirm; ?>')) {
		location = $(this).attr('href');
	}
});
//--></script> 
  <script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>