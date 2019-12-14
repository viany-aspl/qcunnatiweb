<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        </div>
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
	<!--<button type="button" id="button-details" class="btn btn-primary pull-right" style="margin-top: -8px !important; margin-right: 10px !important;">
            Download Itemized Excel</button> -->
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
           <div class="col-sm-12">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-group">Category</label>
                
                  <select name="filter_category" id="input-category" class="form-control">
				   <option value="">Select Category</option>
                  <?php foreach ($categories as $category) { ?>
                  <?php if ($category['category_id'] == $filter_category) { ?>
                  <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['category_desc']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_desc']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
			   </div>
			  <div class="col-sm-6" style="width: 480px;">
              <div class="form-group">
                <label class="control-label" for="input-group">Store</label>
                <select name="filter_store" id="input-store" class="select2 form-control">
				 <option selected="selected" value="">SELECT STORE</option>
                  <?php foreach ($stores as $store) { ?>
                  <?php if ($store['store_id'] == $filter_store) { ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
			  </div>
              
           
			</div>
          <div class="col-sm-12">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-added">Start date</label>
                <div class="input-group date">
                  <input type="text" name="filter_start_date" value="<?php echo $filter_date_added; ?>" placeholder="Start date" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
			   </div>
			  <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-modified">End date</label>
                <div class="input-group date">
                  <input type="text" name="filter_end_date" value="<?php echo $filter_date_modified; ?>" placeholder="End date" data-date-format="YYYY-MM-DD" id="input-date-modified" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
			  </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
           
			</div>
          </div>
        </div>
        <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
              <tr>
	           <td class="text-left">Order ID</td>
	           <td class="text-left">Product Name</td>
              <td class="text-left">Quantity</td>
              <td class="text-left">Price</td>
              <td class="text-left">BCML Code</td>
              <td class="text-left">Subsidy</td>
              <td class="text-left">Store</td>
              </tr>
              </thead>
              <tbody>
                <?php if ($subsidies) { ?>
                <?php foreach ($subsidies as $subsidy) { ?>
                <tr>
                  <td class="text-left"><?php echo $subsidy['order_id']; ?></td>
                  <td class="text-left"><?php echo $subsidy['name']; ?></td>
	              <td class="text-left"><?php echo $subsidy['quantity']; ?></td>
	              <td class="text-left"><?php echo $subsidy['price']; ?></td>
                  <td class="text-left"><?php echo $subsidy['BCMLCODE']; ?></td>
                  <td class="text-left"><?php echo $subsidy['SubSidyPer']; ?></td>
                  <td class="text-left"><?php echo $subsidy['store_name']; ?></td>
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
	//url = 'index.php?route=reportbcml/productsummarysubsidy&token=<?php echo $token; ?>';
	url = 'index.php?route=reportbcml/productsummarysubsidy&token=<?php echo $token; ?>';
	var filter_category= $('select[name=\'filter_category\']').val();
	
	if (filter_category) {
		url += '&filter_category=' + encodeURIComponent(filter_category);
	}
	var filter_store = $('select[name=\'filter_store\']').val();
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
var filter_start_date = $('input[name=\'filter_start_date\']').val();
	
	if (filter_start_date) {
		url += '&filter_start_date=' + encodeURIComponent(filter_start_date);
	}
	
	var filter_end_date = $('input[name=\'filter_end_date\']').val();
	
	if (filter_end_date) {
		url += '&filter_end_date=' + encodeURIComponent(filter_end_date);
	}
				
	location = url;
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
//--></script>

<script>
var currentTime = new Date() 
var minDate = new Date(currentTime.getFullYear(), currentTime.getMonth(), +1); //one day next before month
var maxDate =  new Date(currentTime.getFullYear(), currentTime.getMonth() +2, +0); // one day before next month
$('.date').datepicker({ 
minDate: minDate, 
maxDate: maxDate 
});
</script>

<script type="text/javascript"><!--
$('#button-details').on('click', function() {
	url = 'index.php?route=reportbcml/order/download_item_excel&token=<?php echo $token; ?>';
	
	var filter_order_id = $('input[name=\'filter_order_id\']').val();
	
	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}
	
	var filter_customer = $('input[name=\'filter_customer\']').val();
	
	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}
	
	var filter_order_status = $('select[name=\'filter_order_status\']').val();
	
	if (filter_order_status != '*') {
		url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
	}	

	var filter_total = $('input[name=\'filter_total\']').val();

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	}	
	
	var filter_date_added = $('input[name=\'filter_date_added\']').val();
	
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}
	var filter_store = $('#input-store').val();
	
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
	var filter_date_modified = $('input[name=\'filter_date_modified\']').val();
	
	if (filter_date_modified) {
		url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
	}
	 window.open(url, '_blank');			
	//location = url;
});
$("#input-store").select2();
//$("#input-category").select2();
//--></script>
</div>
<?php echo $footer; ?>