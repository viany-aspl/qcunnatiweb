<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
          
        <a href="index.php?route=factory/paymentdtl&token=<?php echo $token; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo "Cancel Button"; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a> 
        </div>
      <h1>Factory Payment List</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">Factory PaymentList</a></li>
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
        <h3 class="panel-title"><i class="fa fa-list"></i>Factory Payment List</h3>
<button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;"> Download</button>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
              <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-status">Company</label>
                <select name="filter_company" id="input-company" class="form-control" onchange="clear_company(this.value)">
                  <option value="">Select Company</option>
                 
                  <?php foreach ($comopanys as $company) { ?>
                  <?php if ($company['company_id'] == $filter_company) { ?>
                  <option value="<?php echo $company['company_id']; ?>" selected="selected"><?php echo $company['company_name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $company['company_id']; ?>"><?php echo $company['company_name']; ?></option>
                  <?php } ?>
                  <?php } ?>                   
                </select>
              </div>            
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-status">Unit</label>
                <select name="filter_unit" id="input-unit"  class="form-control">
                  <option value="">Select Unit</option>
                  <?php
 if(!empty($units2))
 {
 foreach($units2 as $dunit)
 {
 ?>
 <?php if ($dunit['unit_id'] == $filter_unit) {
 if($filter_unit!=""){
 ?>
 <option value="<?php echo $dunit['unit_id']; ?>" selected="selected"><?php echo $dunit['unit_name']; ?></option>
 <?php }} else { ?>
 <option value="<?php echo $dunit['unit_id']; ?>"><?php echo $dunit['unit_name']; ?></option>
 <?php } ?>
 
 
 <?php
 }
 }
 ?>                        
                </select>
              </div>            
            		
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i>Filter</button>
            </div>
          </div>
        </div>
		<strong>Total Amount :</strong>&nbsp;&nbsp;<?php print_r($total_amount); ?>
		
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                
                <td class="text-left">Company </td>
                <td class="text-left">Unit </td>
<td class="text-right">Bank</td>
                <td class="text-right">Amount</td>

                <td class="text-right">Transaction Type</td>     
<td class="text-right">Transaction Number</td>            
                <td class="text-right">Payment Method</td>
                <td class="text-right">Create Date</td>
		 <td class="text-right">Recieve Date</td>
              </tr>
            </thead>
            <tbody>
              <?php if ($payment) { ?>
              <?php foreach ($payment as $pay) { ?>
              <tr>
                
                <td class="text-left"><?php echo $pay['company_name']; ?></td>
                <td class="text-left"><?php echo $pay['unit_name']; ?></td>
<td class="text-right"><?php echo $pay['bank']; ?></td>
                <td class="text-right"><?php echo $pay['amount']; ?></td>
                <td class="text-right"><?php echo $pay['transaction_type']; ?></td>
<td class="text-right"><?php echo $pay['transaction_no']; ?></td>
                 <td class="text-right"><?php echo $pay['payment_method']; ?></td>
                  <td class="text-right"><?php echo $pay['create_date']; ?></td>
  <td class="text-right"><?php echo $pay['recieve_date']; ?></td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
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

  <script type="text/javascript">
            
function clear_company(data) {
//alert(data);
//$('#select_state_P').hide();


var companyid=data;
$.ajax({ 
type: 'post',
url: 'index.php?route=factory/paymentdtl/getUnitbyCompany&token='+getURLVar('token')+'&companyid='+companyid,
//data: 'companyid='+companyid,
//dataType: 'json',
cache: false,

success: function(data) {

//alert(data);
$("#input-unit").html(data);
}
});
}
$('#button-filter').on('click', function() {
    //alert("bjfdsbn");
var filter_date_start = $('input[name=\'filter_date_start\']').val();
var filter_date_end = $('input[name=\'filter_date_end\']').val();
var filter_unit = $('#input-unit').val();
var filter_company = $('#input-company').val();	
//alert(filter_date_start+'_'+filter_date_end+'_'+filter_unit +'_'+filter_company );
if((!filter_date_start) && (!filter_date_end) && (!filter_unit) && (!filter_company))
{
alertify.error("Please select atleast one filter");
return false;
}

	url = 'index.php?route=factory/paymentdtl/paymentlist&token=<?php echo $token; ?>';
	//alert(url);
	
	//alert(filter_date_start);
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	 
        if (filter_unit) {
		url += '&filter_unit=' + encodeURIComponent(filter_unit);
	}
        if (filter_company) {
		url += '&filter_company=' + encodeURIComponent(filter_company);
	}	
        //alert(url);

	location = url;
});

$('#button-download').on('click', function() {
    //alert("bjfdsbn");
 url = 'index.php?route=factory/paymentdtl/download_excel&token=<?php echo $token; ?>';
 //alert(url);
 var filter_date_start = $('input[name=\'filter_date_start\']').val();
 //alert(filter_date_start);
 if (filter_date_start) {
  url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
 }

 var filter_date_end = $('input[name=\'filter_date_end\']').val();
 
 if (filter_date_end) {
  url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
 }
 
   var filter_unit = $('#input-unit').val();
        if (filter_unit) {
  url += '&filter_unit=' + encodeURIComponent(filter_unit);
 }
 var filter_company = $('#input-company').val();
        if (filter_company) {
  url += '&filter_company=' + encodeURIComponent(filter_company);
 } 
        //alert(url);
 window.open(url, '_blank');
 //location = url;
});
</script> 
  <script type="text/javascript"><!--
$('input[name=\'filter_customer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=payout/payoutdtl/payoutlist&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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