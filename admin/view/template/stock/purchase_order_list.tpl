<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo "Add New"; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" style="display:none;" title="<?php echo "Delete"; ?>" class="btn btn-danger" onclick="confirm('<?php echo "Do you realy want to delete the order?"; ?>') ? $('#form-order').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo "Stock Request/Transfer"; ?></h1>
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
	<?php if (isset($_SESSION['receive_success_message'])) {		?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $_SESSION['receive_success_message']; unset($_SESSION['receive_success_message']);?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if (isset($_SESSION['delete_unsuccess_message'])) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $_SESSION['delete_unsuccess_message']; unset($_SESSION['delete_unsuccess_message']);?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if (isset($_SESSION['nothing_found_error'])) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $_SESSION['nothing_found_error']; unset($_SESSION['nothing_found_error']);?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if (isset($_SESSION['input_error'])) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $_SESSION['input_error']; unset($_SESSION['input_error']);?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if (isset($_SESSION['delete_success_message'])) {		?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $_SESSION['delete_success_message']; unset($_SESSION['delete_success_message']);?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if (isset($_SESSION['success_order_message'])) {		?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $_SESSION['success_order_message']; unset($_SESSION['success_order_message']);?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo "Stock Request List"; ?></h3>
      </div>
      <div class="panel-body">
	  <form action="<?php echo $filter;?>" method="post" enctype="multipart/form-data" id="form-filter">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo "Order ID:"; ?></label>
                <input type="text" name="filter_order_id" value="<?php if(isset($filter_order_id)){ echo $filter_order_id; }?>" placeholder="order id" id="input-id" class="form-control" />
			  </div>
			</div>
			  <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="status"><?php echo "Status"; ?></label>
                <div class="input-group">
                  <span class="input-group-btn">
					<select class="form-control" name="filter_order_status">
						<option value="">--Status--</option>
						<option value = "0" <?php if(isset($filter_order_status)){ if($filter_order_status=="0"){ ?>selected<?php }} ?>>Pending</option>
						<option value="1" <?php if(isset($filter_order_status)){if($filter_order_status=="1"){ ?>selected<?php }} ?>>Received</option>
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
                  <input <?php if($filter_date_start=="") { ?>onkeypress="return false"<?php } ?> type="text" name="filter_date_start" value="<?php if(isset($filter_date_start)) { echo $filter_date_start; }?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
			</div>
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo "To"; ?></label>
                <div class="input-group date">
                  <input <?php if($filter_date_end=="") { ?>onkeypress="return false"<?php } ?> type="text" name="filter_date_end" value="<?php if(isset($filter_date_end)) { echo $filter_date_end; }?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
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
                  <td class="text-left"><a href="" class="">Order ID</a></td>
				  <td class="text-left"><a href="" class="">Date</a></td>
				  <td class="text-left"><a href="" class="">Ordered By</a></td>
				  <td class="text-left"><a href="" class="">Store</td>
				  <td class="text-left"><a href="" class="">Status</a></td>
				  <td class="text-left"><a href="" class="">Action</a></td>
                </tr>
              </thead>
              <tbody>
                <?php if($order_list){
					foreach($order_list as $order)
					{
				?>
						<tr>
							<td class="text-left"><input type="checkbox" name="selected[]" value="<?php echo $order['id']; ?>" /></td>
							<td class="text-left"><?php echo $order['id']; ?></td>
							<td class="text-left"><?php echo $order['order_date']; ?></td>
							<td class="text-left"><?php echo $order['firstname'] . " " . $order['lastname'];?></td>
							<td class="text-left"><?php if($order['pre_supplier_bit'] == 1) echo $order['first_name'] . " " . $order['last_name']; else echo "Multiple"; ?></td>
							<td class="text-left"><?php if($order['receive_bit']==0){ echo "pending"; }else{ echo "received"; }?></td>
							<td class="text-left"><a class="btn btn-info" href="<?php echo $view . '&order_id='.$order['id']; ?>" data-toggle="tooltip" title="view" class="btn btn-primary"><i class="fa fa-eye"></i></a><a href="<?php echo $receive . '&order_id='.$order['id']; ?>" data-toggle="tooltip" title="<?php echo "Receive Order"; ?>" style="width:40px; margin-left: 5px;<?php if($order['order_sup_send']=='0000-00-00') { echo 'display:none;'; } else { echo 'display:block;'; } ?>" class="btn btn-info"><i class="fa fa-truck"></i></a></td>
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
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=stock/purchase_order&token=<?php echo $token; ?>';
	
	
         var filter_order_id = $('input[name=\'filter_order_id\']').val();
	
	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}
	
	var filter_customer = $('input[name=\'filter_customer\']').val();
	
	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}
	
	var filter_order_status = $('select[name=\'filter_order_status\']').val();
	
	if (filter_order_status != '') {
		url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
	}	

	var filter_total = $('input[name=\'filter_total\']').val();

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	}	
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	
	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	//alert(url);
        //window.open(url, '_blank');
	location = url;
});
//--></script> 
  
<?php echo $footer; ?> 
