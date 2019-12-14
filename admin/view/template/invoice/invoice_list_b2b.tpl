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
      <h1><?php echo "B2B Invoice"; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo "Invoice List"; ?></h3>
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
                <label class="control-label" for="input-date-added"><?php echo "Invoice Number (ASPL/BB/)"; ?></label>
                <input type="text" name="filter_id" value="<?php if(isset($filter_id)){ echo $filter_id; }?>" placeholder="Invoice Number " id="input-id" class="form-control" />
			  </div>
			</div>
				<div class="col-sm-6">
					<button class="btn btn-primary pull-right" id="clear-filter" onclick="reset_form();" type="button"> Clear</button>
					<button class="btn btn-primary pull-right" id="button-filter" style="margin-right:10px;" type="button"><i class="fa fa-search"></i> Filter</button>
				</div>
		  </div>
        </div>
		
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-order">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <!--<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>-->
                  <td class="text-left">Invoice Number</td>
				  <td class="text-left">Create Date</td>
				  
                                  <td class="text-left">B2B Partner Name</td>
				  
                                  <td class="text-left" style="max-width: 100px;">Action</td>
                </tr>
              </thead>
              <tbody>
                <?php if($order_list){
					foreach($order_list as $order)
					{ //print_r($order);
				?>
						<tr>
							<!--<td class="text-left"><input type="checkbox" name="selected[]" value="<?php echo $order['id']; ?>" /></td>-->
							<td class="text-left"><?php echo $order['po_invoice_prefix']."/".$order['po_invoice_n']; ?></td>
							<td class="text-left"><?php echo $order['create_date']; ?></td>
							<td class="text-left"><?php echo $order['store_name']; ?></td>
							
							<td class="text-left">
                                                            <!--<a class="btn btn-info" href="<?php echo $view . '&order_id='.$order['id']; ?>" data-toggle="tooltip" title="view" class="btn btn-primary">
                                                                <i class="fa fa-eye"></i>
                                                            </a>-->
								
                                                            
                                                            <a href="<?php echo $invoice . '&invoice_id='.$order['sid']; ?>" data-toggle="tooltip" title="<?php echo "Download Invoice"; ?>" style="margin-left: 5px;" class="btn btn-info">
                                                                <i class="fa fa-download"></i>
                                                            </a>
                                                           
                                                        </td>
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
$('#button-filter').on('click', function() {
	url = 'index.php?route=invoice/purchase_order/b2b&token=<?php echo $token; ?>';
	
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
       
	location = url;
});
</script> 

<script type="text/javascript">
$('#button-download').on('click', function() {
	url = 'index.php?route=invoice/purchase_order/download_excel&token=<?php echo $token; ?>';
	
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
	var filter_store = $('#input-store').val();
        if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
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
