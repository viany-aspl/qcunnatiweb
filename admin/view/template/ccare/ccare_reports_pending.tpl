<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        
      </div>
      <style>
       .table-responsive table td {padding: 4px !important;}
       .table-responsive table th {padding: 4px !important;}

        </style>
        <h1><?php echo $heading_title; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
        <button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download</button>
      </div>
      <div class="panel-body">
        <div class="well">
        <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="filter_date_start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="filter_date_end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
        </div></div>
            <div class="row">
         <div class="col-sm-12 text-right">
             <div class="table-responsive" style="width: 101% !important;">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                 
                  <td class="text-left">Customer Mobile</td>
                  <td class="text-left">Store</td>
                  <td class="text-left">Order id</td>
                  <td class="text-left">Call status</td>
                  <td class="text-left">Call date</td>
                  <td class="text-left">Farmer name</td>
                  <td class="text-left">Village</td>
                  <td class="text-left">Sowing Date</td>
                  <td class="text-left">When you will come to buy the product</td>
                  <td class="text-left" style="max-width: 100px;">Remarks</td>
                  <td class="text-left">Acres</td>
                  <td class="text-left">Will buy</td>
                  <td class="text-left">When buy</td>
                  <td class="text-left" style="max-width: 100px;">What buy</td>
                </tr>
              </thead>
              <tbody>
                <?php if ($orders) { ?>
                <?php foreach ($orders as $order) { //print_r($order); ?>
                <tr>
                    
                  <td class="text-left"><?php echo $order['mobile_number']; ?></td>
                  <td class="text-left"><?php echo $order['store_name']; ?></td>
                  <td class="text-left"><?php echo $order['order_id']; ?></td>
                  <td class="text-left"><?php if($order['call_status']=="1"){ echo "Answered" ;} if($order['call_status']=="2"){ echo "Busy" ;} if($order['call_status']=="3"){ echo "Not Reachable" ;} ?></td>
                  <td class="text-left"><?php echo $order['datetime']; ?></td>
                  <td class="text-left"><?php echo $order['farmer_name']; ?></td>
                  <td class="text-left"><?php echo $order['village_name']; ?></td>
                  <td class="text-left"><?php echo $order['sowing_date']; ?></td>
                  <td class="text-left"><?php echo $order['txt_response']; ?></td>
                  <td class="text-left"><?php echo $order['Reason_of_response']; ?></td>
                  <td class="text-left"><?php echo $order['Acres']; ?></td>
                  <td class="text-left"><?php echo $order['buy_new']; ?></td>
                  <td class="text-left"><?php echo $order['buying_date']; ?></td>
                  <td class="text-left"><?php echo $order['buy_product_text']; ?></td>
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
        <div class="row">
          <div class="col-sm-12 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-12 text-right"><?php echo $results; ?></div>
        </div>
        </div>
           
        
      </div>
          
    </div>
  </div>
      <script type="text/javascript"><!--
$('#button-download').on('click', function() {
	url = 'index.php?route=ccare/ccare/download_reports_pending&token=<?php echo $token; ?>';
	
	var filter_date_start = $('#filter_date_start').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	
	var filter_date_end = $('#filter_date_end').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
        
                location = url;
                // alert(url);
        
});
//--></script> 
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=ccare/ccare/get_reports_pending&token=<?php echo $token; ?>';
	
	var filter_date_start = $('#filter_date_start').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	
	var filter_date_end = $('#filter_date_end').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
        
                location = url;
                // alert(url);
        
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