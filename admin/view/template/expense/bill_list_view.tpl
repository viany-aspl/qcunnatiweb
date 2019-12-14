<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
      
     </div>
      <h1>Expense bill  list</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> List</h3>
        <button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download</button>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-date-start">Start date </label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="Date start" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
               <div class="form-group">
                <label class="control-label" for="input-order-status">Select Center</label>
                <select required name="filter_store" id="input-store" class="form-control" >
                      <option selected="selected" value="">SELECT CENTER</option>
                  <?php foreach ($stores as $store) {   ?>
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
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-date-added">End date </label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="Date end" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              
                
                
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-order-status">Select Reason</label>
               
               <select required name="filter_reason" id="filter_reason" class="form-control" >
                      <option selected="selected" value="">SELECT REASON</option>
                  <?php foreach ($reasons as $reason) {   ?>
                  <?php if ($reason['sid'] == $filter_reason) {
                      if($filter_reason!=""){
                      ?>
                  <option value="<?php echo $reason['sid']; ?>" selected="selected"><?php echo $reason['reason']; ?></option>
                      <?php }} else { ?>
                  <option value="<?php echo $reason['sid']; ?>"><?php echo $reason['reason']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              
              
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Filter</button>
            </div>
          </div>
        </div>
        <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left">Center</td>
                  <td class="text-left">Reason</td>
                  <td class="text-left">Employee name</td>
	 <td class="text-left">Amount</td>
                  <td class="text-left">Photo of bill</td>
                  <td class="text-left">Exepense date</td>
                  <td class="text-left">Submit date</td>
                  
                  <td class="text-left">Status</td>
                
                </tr>
              </thead>
              <tbody>
                <?php if ($bills) { ?>
                <?php foreach ($bills as $bill) { //print_r($bill); ?> 
                <tr>
                  <td class="text-left"><?php echo $bill['center']; ?></td>
                  <td class="text-left"><?php echo $bill['reason']; ?></td>
                  <td class="text-left"><?php echo $bill['employee_name']; ?></td>
	    <td class="text-left"><?php echo $bill['amount']; ?></td>
                  <td class="text-left"><?php if($bill['bill_pic']!=""){ ?><a href="../system/upload/expensebill/<?php echo $bill['bill_pic']; ?>" download>View</a><?php } ?></td>
                  
                  <td class="text-left"><?php echo $bill['exepense_date']; ?></td>
                 <td class="text-left"><?php echo $bill['create_time']; ?></td>
                  
                  <td class="text-left"><?php if($bill['status']=="0"){echo "Pending";} else if($bill['status']=="1") { echo "Accepted"; } else if($bill['status']=="2") { echo "Rejected"; } ?></td>
                  
                </tr>
                <?php } ?>
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
    
    

  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=expense/expense/getlist&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	
	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != '*') {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	

		
	var filter_reason = $('#filter_reason').val();

	if (filter_reason) {
		url += '&filter_reason=' + encodeURIComponent(filter_reason);
	}
	
	//alert(url);			
	location = url;
});
//--></script> 
<script type="text/javascript"><!--
$('#button-download').on('click', function() {
	url = 'index.php?route=expense/expense/getlist_download&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	
	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != '*') {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	

	var filter_reason = $('#filter_reason').val();

	if (filter_reason) {
		url += '&filter_reason=' + encodeURIComponent(filter_reason);
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