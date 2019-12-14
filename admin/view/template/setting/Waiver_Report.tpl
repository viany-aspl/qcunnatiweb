<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
          
        <a href="#" onclick="return open_model();" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo "Cancel Button"; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
        </div>
      <h1>Waiver Report (Own-Store)</h1>
      <ul class="breadcrumb">
        <?php //foreach ($breadcrumbs as $breadcrumb) { ?>
        <!--<li><a href="<?php echo $breadcrumb['href']; ?>">Expense Report</a></li>-->
        <?php // } ?>
      </ul>
<i class="<?php echo $tool_tip_class; ?> " data-toggle="tooltip" style="<?php echo $tool_tip_style; ?>" title="<?php echo $tool_tip; ?>"></i>
 
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
        <h3 class="panel-title"><i class="fa fa-list"></i>Waiver Report</h3>
	<button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px;"> Download</button>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start">Start Date(Create Date)</label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-end">End Date (Create Date)</label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-status">Store</label>
                <select name="filter_stores_id" id="input-store" style="width: 100%;" class="select2 form-control">
                  <option value="0">Select Store</option>
                 
                  <?php foreach ($order_stores as $store) { ?>
                  <?php if ($store['store_id'] == $filter_stores_id) { ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                   
                </select>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i>Filter</button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                
                
                <td class="text-left">Store Name</td>
	<td class="text-left">Store ID</td>
	<td class="text-left">User Name</td>
               
               
                <td class="text-left">Date</td>
                <td class="text-left" style="max-width: 200px;">Waiver Description</td>
             
	 <td class="text-left">Amount</td>
	<td class="text-left">Approved By</td>
	<td class="text-left">Document No / Letter ID</td>
              </tr>
            </thead>
            <tbody>
              <?php if ($waveoffdata) { ?>
              <?php foreach ($waveoffdata as $pay) { ?>
              <tr>
                
                
                <td class="text-left"><?php echo $pay['name']; ?></td>
	<td class="text-left"><?php echo $pay['store_id']; ?></td>
	<td class="text-left"><?php echo $pay['store_user']; ?></td>
               
                 <td class="text-left"><?php echo $pay['cr_date']; ?></td>
                <td class="text-left"><?php echo $pay['response']; ?></td>
               
                  
	 <td class="text-left"><?php echo $pay['amount']; ?></td>
	 <td class="text-left"><?php echo $pay['firstname'].'&nbsp;'.$pay['lastname']; ?></td>
	<td class="text-left"><?php echo $pay['document_no']; ?></td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
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
          <h4 class="modal-title">Create Waiver </h4>
        </div>
        <div class="modal-body">
        <form action="index.php?route=setting/debitstore/audit&token=<?php echo $token; ?>" method="get" enctype="multipart/form-data" > 
            
            
            <div class="form-group">
            <label for="input-username">STORE</label>
            <div class="input-group"><span class="input-group-addon"><i class="fa fa-building-o" aria-hidden="true"></i></span>
                
            <select name="store_id" id="input-store_id" class="form-control" required onchange="return get_store_incharge(this.value);"  >
	    <option value="">SELECT STORE</option>
                  <?php foreach ($stores as $store) { ?>
                  <?php if (($store['store_id'] == $filter_store) & $filter_store="0") { ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
            </select>

            </div>
            </div>
            <div class="form-group">
            <label for="input-username">STORE IN-CHARGE</label>
            <div class="input-group"><span class="input-group-addon"><i class="fa fa-building-o" aria-hidden="true"></i></span>
                
            <select name="store_user_id" id="input-store_user_id" class="form-control" required >
	    <option value="">SELECT IN-CHARGE</option>
                  
            </select>

            </div>
            </div>
            <div class="text-right">
                <input type="button" onclick="return send_to_exp_waiver_form();" id="partner_sbmt_btn"   class="btn btn-primary" value="Submit" />
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
$("#input-store").select2();
function get_store_incharge(store_id)
{
//alert(store_id);
$.ajax({
              url: 'index.php?route=setting/debitstore/get_store_incharges&token=<?php echo $token; ?>&store_id=' +  encodeURIComponent(store_id),
              // dataType: 'json',
               success: function(json) 
               {
                   //alert(json);
                   //var json2=json.split('----and----');
                   //$("#tab-customer").html(json2[0]);
                   $("#input-store_user_id").html(json);
                   
               }
                       
              });
}
function open_model()
{
$('#myModal_create_bill').modal('show');

$('#input-store_id').val('');

return false;
}
function send_to_exp_waiver_form()
{
var store_id=$('#input-store_id').val();
var store_user_id=$("#input-store_user_id").val();
//alert(store_id);
//return false;
url = 'index.php?route=setting/debitstore/audit&token=<?php echo $token; ?>';
if((store_id!="") && (store_user_id!=""))
{
url += '&store_id=' + encodeURIComponent(store_id);
url += '&store_user_id=' + encodeURIComponent(store_user_id);
location = url;
}
else
{
return false;
}
}
<!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=setting/debitstore/waiver_report&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	var filter_stores_id = $('select[name=\'filter_stores_id\']').val();
	
	if (filter_stores_id != 0) {
		url += '&filter_stores_id=' + encodeURIComponent(filter_stores_id);
	}	

	location = url;
});
$('#button-download').on('click', function() {
	url = 'index.php?route=setting/debitstore/waiver_report_download&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	var filter_stores_id = $('select[name=\'filter_stores_id\']').val();
	
	if (filter_stores_id != 0) {
		url += '&filter_stores_id=' + encodeURIComponent(filter_stores_id);
	}	

	location = url;
});
//--></script> 
  <script type="text/javascript"><!--
$('input[name=\'filter_customer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=setting/debitstore/getWaveoffdata&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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