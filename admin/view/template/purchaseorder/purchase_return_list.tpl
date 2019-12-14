<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
          <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo "Add New"; ?>" class="btn btn-primary">
              <i class="fa fa-plus"></i>
          </a>
        <!--<button type="button" data-toggle="tooltip" title="<?php echo "Delete"; ?>" class="btn btn-danger" onclick="confirm('<?php echo "Do you realy want to delete the order?"; ?>') ? $('#form-order').submit() : false;">
            <i class="fa fa-trash-o"></i>
        </button>-->
      </div>
      <h1><?php echo "Purchase Return/Credit Note"; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo "Purchase Return/Credit Note List"; ?></h3>
		
		<button class="btn btn-primary pull-right" id="button-download" style="margin-top: -10px;" type="button"><i class="fa fa-download"></i> Download Excel</button>
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
                    </span>
                </div>
            </div>
	</div>
	<div class="col-sm-6">
            <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo "To"; ?></label>
                <div class="input-group date">
                    <input onkeypress="return false;" type="text" name="to" value="<?php if(isset($filter_date_end)){ echo $filter_date_end; }?>" placeholder="End date" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                    </span>
                </div>
            </div>
        </div>
	<div class="col-sm-6">
            <div class="form-group">
            <label class="control-label" for="input-date-end"><?php echo "Supplier"; ?></label>
            <select  name="filter_supplier" id="input-supplier" required="required" style="width: 100%;" class="select2 form-control">
                              <option value="" >Select Supplier</option>
                  <?php foreach ($suppliers as $supplier) { ?>
                  <?php if ($supplier['id'] == $filter_supplier) { ?>
                  <option value="<?php echo $supplier['id']; ?>" selected="selected"><?php echo $supplier['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $supplier['id']; ?>"><?php echo $supplier['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
            </select>
            </div>
        </div>    
            
                    <button class="btn btn-primary pull-right" id="button-filter" style="margin-right:10px;" type="button"><i class="fa fa-search"></i> Filter</button>
        </div>
        
		  
        </div>
		
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-order">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                    <td class="text-left">Supplier Name</td>
						<td class="text-left">Create Date</td>
                    <td class="text-left">Invoice Number</td>
                    	<td class="text-left">Invoice Date</td>			  
                  <td class="text-left">Warehouse</td>  
                  <td class="text-left">Product Name</td>
					<td class="text-left">Rate</td>
                  <td class="text-left">Quantity</td>
					<td class="text-left">Unit</td>
					<td class="text-left">Discount</td>
					<td class="text-left">Amount</td>
					<td class="text-left">Rebate Amount</td>
					<td class="text-left">Total</td>
                 
                    
                </tr>
              </thead>
              <tbody>
                <?php 
                if($order_list)
                {
                    foreach($order_list as $order)
                    {
		?>
                    <tr>
	          <td class="text-left"><?php echo $order['supplier']; ?></td>
		<td class="text-left"><?php echo date('Y-m-d',strtotime($order['create_time'])); ?></td>	
	          <td class="text-left"><?php echo $order['invoice_no']; ?></td>
                        
                        
                        <td class="text-left"><?php echo $order['valid_date']; ?></td>
                        <td class="text-left"><?php echo $order['warehouse']; ?></td>
                        <td class="text-left"><?php echo $order['product_name']; ?></td>
						<td class="text-left"><?php echo $order['rate']; ?></td>
						<td class="text-left"><?php echo $order['quantity']; ?></td>
						<td class="text-left"><?php echo $order['unit']; ?></td>
						<td class="text-left"><?php echo $order['discount']; ?></td>
						<td class="text-left"><?php echo $order['amount']; ?></td>
						<td class="text-left"><?php echo $order['rebate']; ?></td>
						<td class="text-left"><?php echo $order['grand_total']; ?></td>
                        
                        
							
                    </tr>
		<?php
                    }
		}
                ?>
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
  </div>



  <script type="text/javascript">
function isNumber(evt)
       {
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }


  $("#input-supplier").select2();
  $('.date').datetimepicker({
		pickTime: false
	});
	
  
$('#button-filter').on('click', function() {
	url = 'index.php?route=purchaseorder/purchase_return&token=<?php echo $token; ?>';
	
        	
	
        var filter_date_start = $('#input-date-start').val();
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
        var filter_supplier = $('#input-supplier').val();
	if (filter_supplier) {
		url += '&filter_supplier=' + encodeURIComponent(filter_supplier);
	}
        var filter_date_end = $('#input-date-end').val();
        if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	location = url;
});
$('#button-download').on('click', function() {
	url = 'index.php?route=purchaseorder/purchase_return/download_excel&token=<?php echo $token; ?>';
	
        	
	
        var filter_date_start = $('#input-date-start').val();
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
        var filter_supplier = $('#input-supplier').val();
	if (filter_supplier) {
		url += '&filter_supplier=' + encodeURIComponent(filter_supplier);
	}
        var filter_date_end = $('#input-date-end').val();
        if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	location = url;
});
</script> 
  <script type="text/javascript">
	
	function reset_form()
	{
		$('[name=from]').val('');
		$('[name=to]').val('');
		$('[name=filter_id]').val('');
		$('[name=status]').prop('selectedIndex', 0);
	}
  </script>
<?php echo $footer; ?> 
