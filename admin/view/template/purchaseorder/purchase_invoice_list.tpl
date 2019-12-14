<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
<!--      <div class="pull-right">
          <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo "Add New"; ?>" class="btn btn-primary">
              <i class="fa fa-plus"></i>
          </a>
        <button type="button" data-toggle="tooltip" title="<?php echo "Delete"; ?>" class="btn btn-danger" onclick="confirm('<?php echo "Do you realy want to delete the order?"; ?>') ? $('#form-order').submit() : false;">
            <i class="fa fa-trash-o"></i>
        </button>
      </div>-->
      <h1><?php echo "Invoice Update"; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo "Purchase order List"; ?></h3>
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
         <div class="col-sm-6">
			<div class="form-group">
            <label class="control-label" for="input-date-end">PO Number (ASPL/PO/)</label>
            <input name="filter_po" id="input-filter_po" value="<?php echo $filter_po; ?>" required="required" style="width: 100%;" class="form-control" placeholder="PO NUmber" />

            </div>
                    <button class="btn btn-primary pull-right" id="button-filter" style="margin-right:10px;" type="button"><i class="fa fa-search"></i> Filter</button>
        </div></div>
        
		  
        </div>
		
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-order">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <!--<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>-->
		<td class="text-left">Supplier Name</td>
		<td class="text-left">PO Date</td>
                    <td class="text-left">PO Number</td>
                    
                    				  
                    
                     <td class="text-left">Product Name</td> 
                      <td class="text-left">Quantity</td>
                    <td class="text-left">Delivery Address</td>
                    <td class="text-left">Status</td>
					<td class="text-left">Created By </td>
						<td class="text-left">Approved Status</td>
						<td class="text-left">Approved By</td>
						<td class="text-left">Approved Date</td>
                    <td class="text-left" style="max-width: 100px;">Action</td>
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
			 <td class="text-left"><?php echo date('d-m-Y',strtotime($order['create_date'])); ?></td>
			<td class="text-left"><?php echo $order['id_prefix'].$order['sid']; ?></td>
                        
                       
                        
                        <td class="text-left"><?php echo $order['product']; ?></td>
                        <td class="text-left"><?php echo $order['Quantity']; ?></td>
                        <td class="text-left"><?php echo $order['delivery_address']; ?></td>
                        <td class="text-left">
                            <?php  if($order['status']=='0') 
                                   {
                                     echo "PO Raised";
                                   }
                                   else if($order['status']=='1') 
                                   {
                                    echo "Invoice Done";
                                   }
                                   else if($order['status']=='2') 
                                   {
                                    echo "Payment Done";
                                   }
                            
                            ?>
                        </td>
						<td class="text-left"><?php echo $order['create_by']; ?></td>
						<td class="text-left"><?php  
		       if($order['approve_status']=='0') 
                                   {
                                     echo "Not Approved";
                                   }
                                   else if($order['approve_status']=='1') 
                                   {
                                    echo "Approved";
                                   }
                                   else 
                                   {
                                    echo "";
                                   }
                            
                            ?></td>
                        <td class="text-left"><?php echo $order['approved_date']; ?></td>
                        <td class="text-left"><?php echo $order['approved_by']; ?></td>
						
                        <td class="text-left">
                        <?php    
                            if($order['status']=='3')
                            {
                        ?>
                            <a href="<?php echo 'index.php?route=purchaseorder/purchase_order/purchase_invoice_add&pono='.$order['sid'].'&token='.$token; ?>" data-toggle="tooltip" title="<?php echo "Add Invoice"; ?>" style="margin-left: 5px;" class="btn btn-info">
                            Add Invoice
                            </a>
                        <?php
                            }
                        ?>
                        </td>
							
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
$("#input-supplier").select2();
$('#button-filter').on('click', function() {
	url = 'index.php?route=purchaseorder/purchase_order/purchase_invoice&token=<?php echo $token; ?>';
	
        	
	
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
    var filter_po = $('#input-filter_po').val();
        if (filter_po) {
		url += '&filter_po=' + encodeURIComponent(filter_po);
	}     
	location = url;
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
