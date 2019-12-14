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
      <h1><?php echo "Create PO"; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo "Created PO List"; ?></h3>
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
			<div class="form-group">
            <label class="control-label" for="input-date-end">PO Number (ASPL/PO/)</label>
            <input name="filter_po" id="input-filter_po" value="<?php echo $filter_po; ?>" required="required" style="width: 100%;" class="form-control" placeholder="PO NUmber" />

            </div>
        </div>    
        <div class="col-sm-6">
            <div class="form-group">
            <label class="control-label" for="input-date-end">Status</label>
            <select  name="filter_status" id="input-status" required="required" class="form-control">
                              <option value="" >All Status</option>
                  	<option <?php if ($filter_status == '0') { ?> selected="selected" <?php } ?> value="0" >PO Raised</option>
		<option <?php if ($filter_status == '1') { ?> selected="selected" <?php } ?> value="1" >PO Invoiced</option>
		<option <?php if ($filter_status == '2') { ?> selected="selected" <?php } ?> value="2" >Invoice Paid</option>
                <option <?php if ($filter_status == '3') { ?> selected="selected" <?php } ?> value="3" >PR Linked</option>
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
		<td class="text-left"><?php echo $order['create_date']; ?></td>	
	          <td class="text-left"><?php echo $order['id_prefix'].$order['sid']; ?></td>
                        
                        
                        <td class="text-left"><?php echo $order['product']; ?></td>
                        <td class="text-left"><?php echo $order['Quantity']; ?></td>
                        <td class="text-left"><?php echo $order['delivery_address']; ?></td>
                        <td class="text-left">
                            <?php  
		       if($order['status']=='0') 
                                   {
                                     echo "PO Raised";
                                   }
                                   else if($order['status']=='1') 
                                   {
                                    echo "PO Invoiced";
                                   }
                                   else if($order['status']=='2') 
                                   {
                                    echo "Invoice Paid";
                                   }
                                   else if($order['status']=='3') 
                                   {
                                    echo "PR Linked";
                                   }
                            
                            ?>
                        </td>
						<td class="text-left"><?php echo $order['create_by']; ?></td>
						<td class="text-left"><?php  
		       if($order['approve_status']=='0') 
                                   {
                                     echo "Pending";
                                   }
                                   else if($order['approve_status']=='1') 
                                   {
                                    echo "Approved";
                                   }
								   else if($order['approve_status']=='2') 
                                   {
                                    echo "Rejected";
                                   }
                                   else 
                                   {
                                    echo "";
                                   }
                            
                            ?></td>
                        <td class="text-left"><?php echo $order['approved_date']; ?></td>
                        <td class="text-left"><?php echo $order['approved_by']; ?></td>
						
                        <td class="text-left">
                            <a href="<?php echo 'index.php?route=purchaseorder/purchase_order/download_purchase_order&token='.$token.'&invoice_id='.$order['sid']; ?>" data-toggle="tooltip" title="<?php echo "Download Purchase Order"; ?>" style="margin-left: 5px;" class="btn btn-info">
                            <i class="fa fa-download"></i>
                            </a>
		<?php  
		       if($order['status']=='0') 
                                   {
			if($order['revised_status']=='0')
			{
                                    if((($user_group_id=='1') || ($user_group_id=='13') || ($user_group_id=='30')) &&($user_id!='363') )
			{ 
                            ?>
		<a href="#"  onclick='return open_model("<?php echo $order['sid']; ?>","<?php echo $order['Quantity']; ?>","<?php echo $order['rate']; ?>" )' data-toggle="tooltip" title="Edit PO Quantity" style="margin-left: 5px;" class="btn btn-info">
                            <i class="fa fa-edit"></i> 
                            </a>
		<?php 	} 
			}
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


<!-- Modal -->
  <div class="modal fade" id="myModal_create_bill" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" id="partner_cncl_btn2" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Update PO Quantity</h4>
        </div>
        <div class="modal-body">
        <form action="index.php?route=purchaseorder/purchase_order/update_po_qnty&token=<?php echo $token; ?>" method="post" enctype="multipart/form-data" onsubmit="return myFunction()" >
	<input required type="hidden" name="po_id"  placeholder=""  id="po_id" class="form-control" />
	<input required type="hidden" name="old_qnty"  placeholder=""  id="old_qnty" class="form-control" />
	<input required type="hidden" name="rate"  placeholder=""  id="rate" class="form-control" /> 
            <div class="form-group">
                <label class="control-label" for="input-date">Quantity</label>
                <div class="input-group">
                  <input required type="text" name="new_qnty" onkeypress="return isNumber(event)"   placeholder="Quantity"  id="new_qnty" class="form-control" />
                  </div>
              </div>
            <div class="form-group">
                <label class="control-label" for="input">Remarks</label>
                <div class="input-group">
                  <textarea required  name="remarks"    placeholder="Remarks"  id="remarks" class="form-control" ></textarea>
                  </div>
              </div>
          
            
            <div class="text-right">
                <input type="button" id="partner_sbmt_btn"  class="btn btn-primary" value="Submit" onclick="return myFunction();" />
                <button type="button" id="partner_cncl_btn" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </form>
        </div>
        <!--<div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>-->
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
function open_model(po_id,old_qnty,rate)
{
//alert(rate);
//return false;
$('#myModal_create_bill').modal('show');
$('#po_id').val(po_id);
$('#old_qnty').val(old_qnty);
$('#new_qnty').val(old_qnty);
$('#rate').val(rate);
$.ajax({
            		url: 'index.php?route=purchaseorder/purchase_order/getreamrks&token=<?php echo $token; ?>&po_id=' +  encodeURIComponent(po_id),
            		dataType: 'text',
            		success: function(json)  
		{ 
			//alert(json);
			$('#remarks').val(json);
		}
});

return false;


}
function myFunction() {
    
             var new_qnty = $('#new_qnty').val();
	var po_id= $('#po_id').val();
	var old_qnty= $('#old_qnty').val();
	var rate= $('#rate').val();
              var remarks= $('#remarks').val();
	if ( new_qnty!= '')
	{
	if(new_qnty==old_qnty)
	{
	alertify.error('New Quantity shold not be same as Old Quantity');
	return false;
	}
	if (!remarks)
	{
		alertify.error('Remarks is Mandatory');
		return false;
	}
	alertify.confirm('Are you sure ? You want to update quantity !',
                function(e){ 
                    if(e){
                    ////////////////
		$.ajax({
            		url: 'index.php?route=purchaseorder/purchase_order/update_po_qnty&token=<?php echo $token; ?>&po_id=' +  encodeURIComponent(po_id)+'&old_qnty=' +  encodeURIComponent(old_qnty)+'&new_qnty=' +  encodeURIComponent(new_qnty)+'&rate=' +  encodeURIComponent(rate)+'&remarks=' +  encodeURIComponent(remarks),
            		dataType: 'json',
            		success: function(json) { 
                		if(json=='1')
			{
                			$('#myModal_create_bill').modal('hide');
				alertify.success('Quantity updated Successfully');
				location.reload();
			}
			else
			{
				alertify.error('Ooops ! Some error occur.Please try again.');
			}		
               	 }
		});
		
		return false;

                }else{ /////////////clicked cancel
                   
                    return false;
                }
            }
                    );
                 $("#alertify-ok").html('Continue');
		
	}
	else
	{
		alertify.error('Please Enter Updated Quantity');
		return false;
	}

   
   
    
}
  $("#input-supplier").select2();
  $('.date').datetimepicker({
		pickTime: false
	});
	
  
$('#button-filter').on('click', function() {
	url = 'index.php?route=purchaseorder/purchase_order&token=<?php echo $token; ?>';
	
        	
	
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
       var filter_status = $('#input-status').val();
        if (filter_status) {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
	var filter_po = $('#input-filter_po').val();
        if (filter_po) {
		url += '&filter_po=' + encodeURIComponent(filter_po);
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
