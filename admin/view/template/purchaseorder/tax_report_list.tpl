<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
         <!-- <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo "Add New"; ?>" class="btn btn-primary">
              <i class="fa fa-plus"></i>
          </a>
        <button type="button" data-toggle="tooltip" title="<?php echo "Delete"; ?>" class="btn btn-danger" onclick="confirm('<?php echo "Do you realy want to delete the order?"; ?>') ? $('#form-order').submit() : false;">
            <i class="fa fa-trash-o"></i>
        </button>-->
      </div>
      <h1><?php echo "Tax Report"; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if (!empty($error_warning)) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if (!empty($success)) {		?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> </h3>
	<button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px;"> Download</button>
      </div>
      <div class="panel-body">
	  
        <div class="well">
         
		  <div class="row">
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo "From"; ?></label>
                <div class="input-group date">
                  <input onkeypress="return false" type="text" name="from" value="<?php if(isset($filter_date_start)){ echo $filter_date_start; }?>" placeholder="Start date" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
			</div>
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo "To"; ?></label>
                <div class="input-group date">
                  <input onkeypress="return false;" type="text" name="to" value="<?php if(isset($filter_date_end)){ echo $filter_date_end; }?>" placeholder="End date" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
		  </div>
            
		  <div class="row">
                        <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end">Supplier</label>
                
                 
                      
                  <select name="filter_supplier" id="input-supplier" style="width: 100%;" class="select2 form-control">
		<option value="" selected="selected">SELECT</option>
                  <?php foreach ($suppliers as $suplier) { ?>
                  <?php if ($suplier['id'] == $filter_supplier) { ?>
                  <option value="<?php echo $suplier['id']; ?>" selected="selected"><?php echo $suplier['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $suplier['id']; ?>"><?php echo $suplier['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                  
              </div>
			</div>
				<div class="col-sm-6">
					<button class="btn btn-primary pull-right" id="clear-filter" onclick="reset_form();" type="button"> Clear</button>
					<button class="btn btn-primary pull-right" id="button-filter" style="margin-right:10px;" type="button"><i class="fa fa-search"></i> Filter</button>
				</div>
		  </div>
        </div>
		
        
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>                  
                                  <td class="text-left">Invoice Date</td>
		      <td class="text-left">Supplier Name</td>
		      			  
                                  <td class="text-left">Supplier GSTN</td>
			<td class="text-left">Delivery Address</td>				  
                                  <td class="text-left">Product Name</td>
		<td class="text-left">Quantity</td>
		<td class="text-left">Unit</td>
		<td class="text-left">Rate (without tax)</td>
		
		<td class="text-left">Sub Total</td>
		<td class="text-left">Discount</td>
		<td class="text-left">Tax title</td>
		<td class="text-left">Tax rate</td>
		<td class="text-left">Total Tax</td>
		<td class="text-left">Rebate & Discount / Freight Charge </td>
		<td class="text-left">Invoice Amount</td>
		<td class="text-left">Invoice Number</td>
		<td class="text-left">Purchase Order / Reference ID</td>
                </tr>
              </thead>
              <tbody>
                <?php if($order_list){
		        foreach($order_list as $order)
			{ $tax_type1=explode('@',$order['tax_type']); 
				$tax_type2=explode('%',$tax_type1[1]); 
				$total_tax_rate=($tax_type2[0]*2);
				//print_r($tax_type2);
		?>
			    <tr>
				
				<td class="text-left"><?php echo $order['invoice_date']; ?></td>
				<td class="text-left"><?php echo $order['supplier']; ?></td>
				<td class="text-left"><?php echo $order['supplier_gst']; ?></td>
				<td class="text-left"><?php echo $order['delivery_address']; ?></td>
				<td class="text-left"><?php echo $order['product']; ?></td>
				<td class="text-left"><?php echo $order['Quantity']; ?></td>
				<td class="text-left"><?php echo $order['unit']; ?></td>
				<td class="text-left"><?php echo $order['rate']; ?></td>
				
				<td class="text-left"><?php echo $order['sub_total']; ?></td>
				<td class="text-left"><?php echo $order['discount']; ?></td>
				<td class="text-left"><?php echo 'GST @'.$total_tax_rate.'%'; ?></td>
				<td class="text-left"><?php echo $total_tax_rate; ?></td>
				<td class="text-left"><?php echo ($order['cgst']*2); ?></td>
				<td class="text-left"><?php echo $order['transport_charges']; ?></td>
				<td class="text-left"><?php echo $order['grand_total']; ?></td>
				<td class="text-left"><?php echo $order['invoice_no']; ?></td>
				<td class="text-left"><?php echo $order['id_prefix'].$order['po_no']; ?></td>
			    </tr>
				<?php
					}
				}?>
              </tbody>
            </table>
          </div>
        
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  </div>
  <script type="text/javascript">
$("#input-supplier").select2();
$('#button-filter').on('click', function() {
	url = 'index.php?route=purchaseorder/report/tax_report&token=<?php echo $token; ?>';
	
        var filter_supplier = $('#input-supplier').val();
        if (filter_supplier) {
		url += '&filter_supplier=' + encodeURIComponent(filter_supplier);
	}
		
	var filter_status = $('select[name=\'status\']').val();
	
	if (filter_status) {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
        var filter_date_start = $('#input-date-start').val();
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
        var filter_date_end = $('#input-date-end').val();
        if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
       
	location = url;
});
</script> 

<script type="text/javascript">
$('#button-download').on('click', function() {
	url = 'index.php?route=purchaseorder/report/download_tax_report&token=<?php echo $token; ?>';
	
        
		
	var filter_status = $('select[name=\'status\']').val();
	
	if (filter_status) {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
        var filter_date_start = $('#input-date-start').val();
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
        var filter_date_end = $('#input-date-end').val();
        if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	var filter_supplier = $('#input-supplier').val();
        if (filter_supplier) {
		url += '&filter_supplier=' + encodeURIComponent(filter_supplier);
	}
       	
	window.open(url, '_blank');
});
</script> 

  <script type="text/javascript">
	$('.date').datetimepicker({
		pickTime: false
	});
	
	function reset_form()
	{
		$('[name=from]').val('');
		$('[name=to]').val('');
		$('[name=filter_id]').val('');
		$('[name=status]').prop('selectedIndex', 0);
	}
  </script>
<?php echo $footer; ?> 
