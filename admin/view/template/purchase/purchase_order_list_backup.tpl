<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
      </div>
      <h1><?php echo "Unnati Krishi Kendra Requisition"; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo "Purchase Orders List"; ?></h3>
	<button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px;"> Download</button>
      </div>
      <div class="panel-body">
	  <form action="<?php echo $filter;?>" method="post" enctype="multipart/form-data" id="form-filter">
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
						<option value = "0" <?php if(isset($status)){ if($status=="0"){ ?>selected<?php }} ?>>Pending</option>
						<option value="1" <?php if(isset($status)){if($status=="1"){ ?>selected<?php }} ?>>Received</option>
					</select>
                  </span></div>
              </div>
            </div>
		  </div>
		  <div class="row">
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo "From"; ?></label>
                <div class="input-group date">
                  <input onkeypress="return false" type="text" name="from" value="<?php if(isset($from)) { echo $from; }?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              
			<div class="form-group">
                <label class="control-label" for="input-date-end">Select Store</label>
               
                      
                  <select name="filter_store" style="width: 100%" id="input-store" class="select2 form-control">
                   <option selected="selected" value="">SELECT STORE</option>
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
                  
              </div></div>
			</div>
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo "To"; ?></label>
                <div class="input-group date">
                  <input onkeypress="return false;" type="text" name="to" value="<?php if(isset($to)) { echo $to; }?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
		  </div>
		  <div class="row">
				<div class="col-sm-12">
					<button class="btn btn-primary pull-right" id="clear-filter" onclick="reset_form();" type="button"> Clear</button>
					<button class="btn btn-primary pull-right" id="button-filter" style="margin-right:10px;" type="button"><i class="fa fa-search"></i> Filter</button>
				</div>
		  </div>
        </div>
		</form>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-order">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left">Requisition ID</td>
				  <td class="text-left">Date</td>
				  <td class="text-left">Ordered By</td>
				  <td class="text-left">Supplier/Ware House / OTP</td>
                                  <td class="text-left">Store Name</td>
				  <td class="text-left"><a href="" class="">Product</a></td>
				  <td class="text-left"><a href="" class="">Quantity</a></td>
				  <td class="text-left">Status</td>
				<?php if($user_group_id!='27'){ ?>
				  <td class="text-left">Action</td>
				<?php } ?>
                </tr>
              </thead>
              <tbody>
                <?php if($order_list){
					foreach($order_list as $order)
					{ //print_r($order);
				?>
						<tr>
							<td class="text-left"><input type="checkbox" name="selected[]" value="<?php echo $order['id']; ?>" /></td>
							<td class="text-left"><?php echo $order['id']; ?></td>
							<td class="text-left"><?php echo $order['order_date']; ?></td>
							<td class="text-left"><?php echo $order['firstname'] . " " . $order['lastname'];?></td>
                                                        <td class="text-left">
                                                            <?php echo $order['ware_house_name'];
                                                            if($order['driver_otp']!="")
                                                            {
                                                                echo " / ".$order['driver_otp'];
                                                            }
                                                            ?>
                                                        </td>
							<td class="text-left"><?php echo $order['store_name']; ?></td>
							<td class="text-left" style="font-size:10px"><?php echo $order['product']; ?></td>
							<td class="text-left"><?php echo $order['quantity']; ?></td>
							<td class="text-left"><?php if($order['receive_date']=='0000-00-00'){ echo "pending"; }else{ echo "received"; }?></td>
							<?php if($user_group_id!='27'){ ?>
							<td class="text-left">
                                                            <a class="btn btn-info" href="<?php echo $view . '&order_id='.$order['id']; ?>" data-toggle="tooltip" title="view" class="btn btn-primary">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        <?php if($order['order_sup_send']=='0000-00-00'){ ?>
							<a href="<?php echo $receive . '&order_id='.$order['id']; ?>" data-toggle="tooltip" title="<?php echo "Confirm Requisition"; ?>" style="margin-left: 5px;" class="btn btn-info">
                                                            <i class="fa fa-truck"></i>
                                                        </a>
                                                        <?php } ?>
                                                        </td>
				<?php } ?>
						</tr>
				<?php
					}
				}?>
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
$("#input-store").select2();
$('#button-filter').on('click', function() {
	url = 'index.php?route=purchase/purchase_order&token=<?php echo $token; ?>';
	
        var filter_id = $('#input-id').val();
	
	if (filter_id) {
		url += '&filter_id=' + encodeURIComponent(filter_id);
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
       	var filter_store =$("#input-store").val();
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
	location = url;
});
$('#button-download').on('click', function() {
	url = 'index.php?route=purchase/purchase_order/download_excel&token=<?php echo $token; ?>';
	
        var filter_id = $('#input-id').val();
	
	if (filter_id) {
		url += '&filter_id=' + encodeURIComponent(filter_id);
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
	var filter_store =$("#input-store").val();
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
       	window.open(url,'_blank');
	//location = url;
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
