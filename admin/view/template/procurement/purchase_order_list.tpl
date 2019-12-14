<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
    
      <h1><?php echo "Purchase Orders"; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if (isset($error_warning)) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

	<?php if (isset($success)) {		?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success;?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo "Purchase Orders List"; ?></h3>
      </div>
      <div class="panel-body">
	  
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo "Order ID:"; ?></label>
                <input type="text" name="filter_id" value="<?php if(isset($filter_id)){ echo $filter_id; }?>" placeholder="order id" id="input-id" class="form-control" />
			  </div>
			</div>
			  <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="status"><?php echo "Status"; ?></label>
                <div class="input-group">
                  <span class="input-group-btn">
					<select class="form-control" name="status">
						<option value="">--Status--</option>
                                                <?php //foreach($order_statuses as $order_status){ ?>
                                                <!--<option value = "<?php echo $order_status['order_status_id']; ?>" <?php if($filter_status==$order_status['order_status_id']){ ?>selected<?php } ?>><?php echo $order_status['name']; ?></option>-->
                                                <?php //} ?>
						<option value = "0" <?php if($filter_status=='0'){ ?>selected<?php } ?>>Pending</option>
                                                <option value = "1" <?php if($filter_status=='1'){ ?>selected<?php } ?>>Completed</option>
                                                <option value = "3" <?php if($filter_status=='3'){ ?>selected<?php } ?>>Canceled</option>
					</select>
                  </span></div>
              </div>
            </div>
		  </div>
		  <div class="row">
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo "Start date"; ?></label>
                <div class="input-group date">
                  <input onkeypress="return false" type="text" name="from" value="<?php if(isset($filter_date_start)) { echo $filter_date_start; }?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
			
	
<div class="form-group">
                <label class="control-label" for="input-date-end">Select Warehouse</label>
               
                      
                  <select name="filter_store" style="width: 100%" id="input-store" class="select2 form-control">
                   <option selected="selected" value="">SELECT WAREHOUSE</option>
                  <?php foreach ($stores as $store) { //echo $store['store_id'];  ?>
                  <?php if ($store['store_id'] == $filter_store) {
                      if($filter_store!=""){
                      ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                      <?php }} else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                  
              </div>
	</div>
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo "End date"; ?></label>
                <div class="input-group date">
                  <input onkeypress="return false;" type="text" name="to" value="<?php if(isset($filter_date_end)) { echo $filter_date_end; }?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
	<button class="btn btn-primary pull-right" id="clear-filter" onclick="reset_form();" type="button"> Clear</button>
					<button class="btn btn-primary pull-right" id="button-filter" style="margin-right:10px;" type="submit"><i class="fa fa-search"></i> Filter</button>
            </div>
		  </div>
		  
        </div>
		
        <!--<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-order">-->
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <!--<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>-->
                  <td class="text-left">Order ID</td>
				  <td class="text-left">Date</td>
				  <td class="text-left">Ordered By</td>
				  <td class="text-left">Supplier</td>
                                  <td class="text-left">Store Name</td>
				  <td class="text-left"><a href="" class="">Product</a></td>
				  <td class="text-left"><a href="" class="">Quantity</a></td>	
				  <td class="text-left">Status</td>
                                  <?php //if(($user_group_id=='1')|| ($user_group_id=='27')|| ($user_group_id=='28')){ ?>
                                  <td class="text-left">Credit Limit</td>
                                  <td class="text-left">Current Credit</td>
                                  <?php //} ?>
				  <td class="text-left">Action</td>
                </tr>
              </thead>
              <tbody>
                <?php if($order_list){
			foreach($order_list as $order)
			{  //print_r($order);
			?>
			<tr>
			    <!--<td class="text-left"><input type="checkbox" name="selected[]" value="<?php echo $order['id']; ?>" /></td>-->
			    <td class="text-left"><?php echo $order['id']; ?></td>
			    <td class="text-left"><?php echo $order['order_date']; ?></td>
			    <td class="text-left"><?php echo $order['firstname'] . " " . $order['lastname'];?></td>
                            <td class="text-left"><?php echo $order['first_name'] . " " . $order['last_name'];  if($order['driver_otp']!="") { ?> / <?php echo $order['driver_otp'];  ?> <?php } ?></td>
			    <td class="text-left"><?php echo $order['store_name']; ?></td>
			    <td class="text-left" style="font-size:10px"><?php echo $order['product']; ?></td>
			    <td class="text-left"><?php echo $order['quantity']; ?></td>
			    <td class="text-left"><?php echo $order['order_status']; ?></td>
                            <?php //if(($user_group_id=='1')|| ($user_group_id=='27')|| ($user_group_id=='28')){ ?>
                            <td class="text-left"><?php echo $order['creditlimit']; ?></td>
                            <td class="text-left"><?php echo $order['currentcredit']; ?></td>
                            <?php //} ?>
			    <td class="text-left" style="min-width: 140px;">
				
			    <?php //print_r($order); 
                            if($order['order_status']=='Pending'){ ?>
                                
                                <?php if($order['order_sup_send']=='0000-00-00'){ ?>
                                <a href="<?php echo $order['view'] . '&order_id='.$order['id']; ?><?php if($_GET['filter_date_start']){ ?>&filter_date_start=<?php echo $_GET['filter_date_start']; } ?><?php if($_GET['filter_date_end']){ ?>&filter_date_end=<?php echo $_GET['filter_date_end']; } ?><?php if($_GET['filter_id']){ ?>&filter_id=<?php echo $_GET['filter_id']; } ?><?php if($_GET['page']){ ?>&page=<?php echo $_GET['page']; } ?><?php if($_GET['filter_status']){ ?>&filter_status=<?php echo $_GET['filter_status']; } ?>"  data-toggle="tooltip" title="<?php echo "Approve Order"; ?>"  class="btn btn-primary"><i class="fa fa-truck"></i></a>
                                
                                <a href="<?php echo $order['view'] . '&order_id='.$order['id']; ?><?php if($_GET['filter_date_start']){ ?>&filter_date_start=<?php echo $_GET['filter_date_start']; } ?><?php if($_GET['filter_date_end']){ ?>&filter_date_end=<?php echo $_GET['filter_date_end']; } ?><?php if($_GET['filter_id']){ ?>&filter_id=<?php echo $_GET['filter_id']; } ?><?php if($_GET['page']){ ?>&page=<?php echo $_GET['page']; } ?><?php if($_GET['filter_status']){ ?>&filter_status=<?php echo $_GET['filter_status']; } ?>"  data-toggle="tooltip" title="<?php echo "Cancel Order"; ?>" class="btn btn-danger"><i class="fa fa-close"></i></a>
                                <?php } ?>
                                
                            <?php } ?>
			    <a href="<?php echo $order['view'] . '&act=view&order_id='.$order['id']; ?><?php if($_GET['filter_date_start']){ ?>&filter_date_start=<?php echo $_GET['filter_date_start']; } ?><?php if($_GET['filter_date_end']){ ?>&filter_date_end=<?php echo $_GET['filter_date_end']; } ?><?php if($_GET['filter_id']){ ?>&filter_id=<?php echo $_GET['filter_id']; } ?><?php if($_GET['page']){ ?>&page=<?php echo $_GET['page']; } ?><?php if($_GET['filter_status']){ ?>&filter_status=<?php echo $_GET['filter_status']; } ?>"  data-toggle="tooltip" title="<?php echo "View Order"; ?>" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                                
                                
                            </td>
			</tr>
		    <?php
			}
		    }?>
              </tbody>
            </table>
          </div>
        <!--</form>-->
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  </div>
<!-- Modal -->
  <div class="modal fade" id="myModal_partner" role="dialog">
    <div class="modal-dialog">
   <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" id="partner_cncl_btn2" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Tell us the reason, why you canceled this order ? </h4>
        </div>
        <div class="modal-body">
        <form action="index.php?route=tag/bills/reject&token=<?php echo $token; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" id="user_id" value="<?php echo $user_id; ?>" />
            <div class="form-group">
            <label for="input-username">Reason </label>
            <div class="input-group"><span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <textarea name="reject_Message" required id="reject_Message" class="form-control" placeholder="Reason of reject"></textarea>
            </div>
            </div>
           <div class="text-right">
               <img id="processing_image" src="https://unnati.world/shop/admin/view/image/processing_image.gif" style="height: 50px;width: 50px;display: none;">
               <input type="button" id="submit_btn"  class="btn btn-primary" 
                   <?php if(($user_group_id=='1')|| ($user_group_id=='27'))
                       { ?> 
                      onclick="return canceled_by_rm_with_msg();" 
                   <?php } ?>
                     
                      value="Submit" />
               <input type="hidden" id="order_id" value="" name="order_id" />
               <input type="hidden" id="order_status_id" value="" name="order_status_id" />
                <button type="button" id="submt_cncl_btn" class="btn btn-default" data-dismiss="modal">Cancel</button>
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
	$("#input-store").select2();
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
  <script type="text/javascript">
$('#button-filter').on('click', function() {
	url = 'index.php?route=procurement/purchase_order&token=<?php echo $token; ?>';
	
        var filter_id = $('input[name=\'filter_id\']').val();
	
	if (filter_id) {
		url += '&filter_id=' + encodeURIComponent(filter_id);
	}
        
        var filter_status = $('select[name=\'status\']').val();
	
	if (filter_status!="") {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
        var filter_date_start = $('input[name=\'from\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	var filter_date_end = $('input[name=\'to\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}	
       	var filter_store =$("#input-store").val();
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
	location = url;
});

function confirm_order(order_id,order_status_id)
{
    
    alertify.confirm('Are you sure ! you want to approve this order ?',
                function(e){ 
                    if(e){
        url = 'index.php?route=procurement/rm/confirm_order_by_rm&token=<?php echo $token; ?>';
	
        var filter_id = $('input[name=\'filter_id\']').val();
	
	if (filter_id) {
		url += '&filter_id=' + encodeURIComponent(filter_id);
	}
        
        var filter_status = $('select[name=\'status\']').val();
	
	if (filter_status!="") {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
        var filter_date_start = $('input[name=\'from\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	var filter_date_end = $('input[name=\'to\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}	
        url += '&order_id=' + encodeURIComponent(order_id);
        url += '&order_status_id=' + encodeURIComponent(order_status_id); 
        var user_id = '<?php echo @$user_id; ?>';
        url += '&user_id=' + encodeURIComponent(user_id);
        var page = '<?php echo @$_GET['page']; ?>';
        url += '&page=' + encodeURIComponent(page);
	location = url;
                }else{
                    alertify.error('You canceled this action !'); 
                    return false;
                }
            }
                    
                        );
                 $("#alertify-ok").html('Continue'); 
                 //return false;
}
function cancel_order(order_id,order_status_id)
{
    
    alertify.confirm('Are you sure ! you want to cancel this order ?',
                function(e){ 
                    if(e){
                        $("#order_id").val(order_id);
                        $("#order_status_id").val(order_status_id);
                        $('#myModal_partner').modal('show');
                        return false;
                }else{
                    alertify.error('You canceled this action !'); 
                    return false;
                }
            }
                    
                        );
                 $("#alertify-ok").html('Continue'); 
                 //return false;
}
/////////////////////////////
////////////////////////////////////////////
function canceled_by_rm_with_msg()
{
    var order_id=$("#order_id").val();
    var order_status_id=$("#order_status_id").val();
    var reject_Message=$('#reject_Message').val();
    if(reject_Message=="")
    {
        return false;
    }
    else
    {
    $("#processing_image").show();
    $("#submit_btn").hide();
    $("#submt_cncl_btn").hide();
    
    url = 'index.php?route=procurement/rm/cancel_order_by_rm&token=<?php echo $token; ?>';
	
        var filter_id = '<?php echo @$_GET['filter_id']; ?>';
	
	if (filter_id) {
		url += '&filter_id=' + encodeURIComponent(filter_id);
	}
        
        var filter_status = '<?php echo @$_GET['filter_status']; ?>';
	
	if (filter_status!="") {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
        var filter_date_start = '<?php echo @$_GET['filter_date_start']; ?>';
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	var filter_date_end = '<?php echo @$_GET['filter_date_end']; ?>';
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
        url += '&reject_Message=' + encodeURIComponent(reject_Message);
        url += '&order_id=' + encodeURIComponent(order_id);
        url += '&order_status_id=' + encodeURIComponent(order_status_id);
        var user_id = '<?php echo @$user_id; ?>';
        url += '&user_id=' + encodeURIComponent(user_id);
        var page = '<?php echo @$_GET['page']; ?>';
        url += '&page=' + encodeURIComponent(page);
	location = url;
        return true;
    }
}
////////////////////////////////////////////////////
function confirm_order_by_cm(order_id,order_status_id)
{
    
    alertify.confirm('Are you sure ! you want to approve this order ?',
                function(e){ 
                    if(e){
        url = 'index.php?route=procurement/cm/confirm_order_by_cm&token=<?php echo $token; ?>';
	
        var filter_id = $('input[name=\'filter_id\']').val();
	
	if (filter_id) {
		url += '&filter_id=' + encodeURIComponent(filter_id);
	}
        
        var filter_status = $('select[name=\'status\']').val();
	
	if (filter_status!="") {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
        var filter_date_start = $('input[name=\'from\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	var filter_date_end = $('input[name=\'to\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}	
        url += '&order_id=' + encodeURIComponent(order_id);
        url += '&order_status_id=' + encodeURIComponent(order_status_id);
	location = url;
                }else{
                    alertify.error('You canceled this action !'); 
                    return false;
                }
            }
                    
                        );
                 $("#alertify-ok").html('Continue'); 
                 //return false;
}
function cancel_order_by_cm(order_id)
{
    
    alertify.confirm('Are you sure ! you want to cancel this order ?',
                function(e){ 
                    if(e){
        url = 'index.php?route=procurement/cm/cancel_order_by_cm&token=<?php echo $token; ?>';
	
        var filter_id = $('input[name=\'filter_id\']').val();
	
	if (filter_id) {
		url += '&filter_id=' + encodeURIComponent(filter_id);
	}
        
        var filter_status = $('select[name=\'status\']').val();
	
	if (filter_status!="") {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
        var filter_date_start = $('input[name=\'from\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	var filter_date_end = $('input[name=\'to\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}	
        url += '&order_id=' + encodeURIComponent(order_id);
	location = url;
                }else{
                    alertify.error('You canceled this action !'); 
                    return false;
                }
            }
                    
                        );
                 $("#alertify-ok").html('Continue'); 
                 //return false;
}
  </script>
<?php echo $footer; ?> 
