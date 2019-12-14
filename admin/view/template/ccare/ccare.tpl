<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <!--<button type="submit" id="button-shipping" form="form-order" formaction="<?php echo $shipping; ?>" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-info"><i class="fa fa-truck"></i></button>
        <button type="submit" id="button-invoice" form="form-order" formaction="<?php echo $invoice; ?>" data-toggle="tooltip" title="<?php echo $button_invoice_print; ?>" class="btn btn-info"><i class="fa fa-print"></i></button>
        <a <?php if($group=="11") { echo "style='display:none;'"; } ?> href="<?php echo $insert; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
      -->
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
        <h3 class="panel-title"><i class="fa fa-list"></i> Last 10 Days Orders</h3>
      </div>
      <div class="panel-body">
        
        <div class="row">
         <div class="col-sm-6 text-right">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                 
                  <td class="text-left">Customer Mobile</td>
                  <td class="text-left"><?php if ($sort == 'status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>">Status</a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>">Status</a>
                    <?php } ?></td>
                  <td class="text-left">
                      <?php if ($sort == 'store_name') { ?>
                    <a href="<?php echo $sort_store_name; ?>" class="<?php echo strtolower($order); ?>">Store</a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_store_name; ?>">Store</a>
                    <?php } ?>
                      </td>
                      <td class="text-left">ASE(If any)</td>
		<td class="text-left">Date Added</td>
                </tr>
              </thead>
              <tbody>
                <?php if ($orders) { ?>
                <?php foreach ($orders as $order) { //print_r($order); ?>
                <tr>
                    
                  <td class="text-left"><a href="#" onclick="return show_order_data(<?php echo $order['telephone']; ?>,<?php echo $order['order_id']; ?>);"><?php echo $order['telephone']; ?></a></td>
                  <td class="text-left"><?php echo $order['status']; ?></td>
                  <td class="text-left"><?php echo $order['store_name']; ?></td>
                  <td class="text-left"><?php echo $order['ase_name']; ?></td>
	    <td class="text-left"><?php echo $order['date_added']; ?></td>
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
            <div class="col-sm-6 text-right">
                <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-order" data-toggle="tab">Order Details</a></li>
         
          
          <li><a href="#tab-product" data-toggle="tab">Products</a></li>
          <li><a href="#tab-history"  data-toggle="tab">Fill Form</a></li>
          
        </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab-order">
                    
                    Please Select a Number
                </div>
                <div class="tab-pane" id="tab-product">
                    
                    Please Select a Number
                </div>
                <div class="tab-pane" id="tab-history">
                    <div id="display_before_call">
                        Please Select a Number
                        
                    </div>
                    <div id="display_after_call" style="display: none;">
               <input type="hidden" name="order_id" id="order_id" />
               <input type="hidden" name="mobile_number" id="mobile_number" />
               <input type="hidden" name="current_call_status" id="current_call_status" />
               <input type="hidden" name="logged_user_data" id="logged_user_data" value="<?php echo $logged_user_data; ?>" />
               <input type="hidden" name="current_order_status" id="current_order_status" value="<?php echo $current_order_status; ?>" />
               
               <div class="form-group" style="text-align: left;">
                <label class="control-label" for="input-order-status">Call Status</label>
                <select name="call_status" onchange="return change_form(this.value);" id="call-status" class="form-control">
                 <option value="" selected="selected">SELECT</option>
                 <option value="1">Answered</option>
                 <option value="2">Busy</option>
                 <option value="3">Not Reachable</option>
                 
                 
                 
                </select>
              </div>
               <div id="form_div" style="display: none;">
              <div class="form-group" style="text-align: left;">
                <label class="control-label" for="input-order-status">Farmer name</label>
                <input name="farmer_first_name" class="form-control" id="farmer_first_name" placeholder="First Name" />
                <br/><input name="farmer_last_name" class="form-control" id="farmer_last_name" placeholder="Last name" />
              </div>
              <div class="form-group" style="text-align: left;">
                <label class="control-label" for="input-order-status">Village</label>
                <input name="village" class="form-control" id="village" placeholder="Village" />
              </div>
              <div class="form-group" style="text-align: left;">
                <label class="control-label" for="date-added">Sowing Date</label>
                <div class="input-group date">
                  <input type="text" name="sowing_date" placeholder="Sowing Date" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
               <!--<div class="form-group" style="text-align: left;">
               
                <label class="control-label" for="input-order-status">Response</label>
                <select name="txt_response" id="txt_response" class="form-control">
                 <option value="" selected="selected">SELECT</option>
                 <option value="Good">Good</option>
                 <option value="Satisfactory">Satisfactory</option>
                 <option value="Not Good">Not Good</option>
                </select>
              </div>-->
	<div class="form-group" style="text-align: left;">
                <label class="control-label" for="date-added">When you will come to buy the product</label>
                <div class="input-group date">
                  <input type="text" name="txt_response" placeholder="" data-date-format="YYYY-MM-DD" id="txt_response" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
               	<div class="form-group" style="text-align: left;">
                <label class="control-label" for="input-order-status">Remarks</label>
                <textarea name="Reason_of_response" class="form-control" id="Reason_of_response" placeholder="Remarks"></textarea>
              </div>
              <div class="form-group" style="text-align: left;">
                <label class="control-label" for="input-order-status">Acres</label>
                <input name="Acres" class="form-control" id="Acres" placeholder="Acres" />
              </div>
               <div class="form-group" style="text-align: left;">
               
                <label class="control-label" for="input-order-status">You Want to buy anything in future</label>
                <select name="buy_new" id="buy_new" class="form-control" onchange="return change_buy_info(this.value);">
                 <option value="" selected="selected">SELECT</option>
                 <option value="Yes">Yes</option>
                 <option value="No">No</option>
                 
                </select>
              </div>
 	<div class="form-group"  id="buy_date_div" style="text-align: left;display: none;">
                <label class="control-label" for="date-added">Buying Date</label>
                <div class="input-group date">
                  <input type="text" name="buying_date" placeholder="Buying Date" data-date-format="YYYY-MM-DD" id="buying_date" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
               <div class="form-group" id="buy_info_div" style="text-align: left;display: none;">
                <label class="control-label" for="input-order-status">What Products</label>
                <textarea name="buy_product_text" class="form-control" id="buy_product_text" placeholder="Products Information"></textarea>
              </div>
                    </div>
               
                    <button type="button" id="button-filter" class="btn btn-primary pull-left">Submit</button>
               </div>
               </div>
            </div>
      </div>
        
      </div>
          
    </div>
  </div>
      <script>
          function change_buy_info(selected_value)
          {
             if(selected_value=="No")
              {
                 $("#buy_info_div").hide();
                 $("#buy_date_div").hide();
                 
              }
              else if(selected_value=="Yes")
              {
                  $("#buy_info_div").show(); 
                  $("#buy_date_div").show();
              }
              else
              {
                 $("#buy_info_div").hide();  
                 $("#buy_date_div").hide();
              }

              return false;
          }
          function change_form(selected_value)
          {
              if(selected_value=="1")
              {
                 $("#form_div").show(); 
              }
              
              else
              {
                 $("#form_div").hide();  
              }
              return false;
          }
          
          function show_order_data(mobile,order_id)
          {   //order_id=36;
              
              $.ajax({
              url: 'index.php?route=ccare/ccare/get_order_info&token=<?php echo $token; ?>&mobile=' +  encodeURIComponent(mobile)+'&order_id=' +  encodeURIComponent(order_id),
              // dataType: 'json',
               success: function(json) 
               {
                   //alert(json);
                   var json2=json.split('----and----');
                   $("#tab-order").html(json2[0]);
                   $("#tab-product").html(json2[1]);
                   $("#order_id").val(order_id);
                   $("#mobile_number").val(mobile);
                   $("#display_before_call").hide();
                   $("#display_after_call").show();
                   $("#current_call_status").val(json2[2]);
               }
                       
              });
             return false; 
          }
          </script>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=ccare/ccare/submit_call_data&token=<?php echo $token; ?>';
	
	var order_id = $('input[name=\'order_id\']').val();
	
	if (order_id) {
		url += '&order_id=' + encodeURIComponent(order_id);
	}
	
	var mobile_number = $('input[name=\'mobile_number\']').val();
	
	if (mobile_number) {
		url += '&mobile_number=' + encodeURIComponent(mobile_number);
	}
        var current_call_status = $('input[name=\'current_call_status\']').val();
	
	if (current_call_status) {
		url += '&current_call_status=' + encodeURIComponent(current_call_status);
	}
	
	var call_status = $('select[name=\'call_status\']').val();
	
	if (call_status != '*') {
		url += '&call_status=' + encodeURIComponent(call_status);
	}	

        var farmer_first_name = $('input[name=\'farmer_first_name\']').val();

	if (farmer_first_name) {
		url += '&farmer_first_name=' + encodeURIComponent(farmer_first_name);
	}
        var farmer_last_name = $('input[name=\'farmer_last_name\']').val();

	if (farmer_last_name) {
		url += '&farmer_last_name=' + encodeURIComponent(farmer_last_name);
	}
	
	var village = $('input[name=\'village\']').val();

	if (village) {
		url += '&village=' + encodeURIComponent(village);
	}	
	
	var sowing_date = $('input[name=\'sowing_date\']').val();
	
	if (sowing_date) {
		url += '&sowing_date=' + encodeURIComponent(sowing_date);
	}
        var txt_response = $('#txt_response').val();
	
	if (txt_response) {
		url += '&txt_response=' + encodeURIComponent(txt_response);
	}
        var buy_new = $('#buy_new').val();
	
	if (buy_new) {
		url += '&buy_new=' + encodeURIComponent(buy_new);
	}
        var buy_product_text = $('#buy_product_text').val();
	
	if (buy_product_text) {
		url += '&buy_product_text=' + encodeURIComponent(buy_product_text);
	}
	var Reason_of_response = $('#Reason_of_response').val();
	
	if (Reason_of_response) {
		url += '&Reason_of_response=' + encodeURIComponent(Reason_of_response);
	}
        var Acres = $('#Acres').val();
	
	if (Acres) {
		url += '&Acres=' + encodeURIComponent(Acres);
	}
        var logged_user_data = $('#logged_user_data').val();
	
	if (logged_user_data) {
		url += '&logged_user_data=' + encodeURIComponent(logged_user_data);
	}
        var current_order_status = $('#current_order_status').val();
	
	if (current_order_status) {
		url += '&current_order_status=' + encodeURIComponent(current_order_status);
	}
	var buying_date = $('#buying_date').val();
	
	if (buying_date) {
		url += '&buying_date=' + encodeURIComponent(buying_date);
	}
        //alert(buy_product_text);
	//alert(url);
        if(call_status=="1")
        {
        if((order_id) && (mobile_number) && (call_status) && (farmer_first_name) && (village) && (sowing_date) && (txt_response) && (buy_new) && (Reason_of_response) && (Acres))
        {
        
          if(buy_new=="Yes")
          {
            if(buy_product_text!="")
            {
                location = url;
                 //alert(url);
            }
            else
            {
             //alert('Please Enter product Inforamtion');
            }
          }
          else
          {
	    location = url;
             //alert(url);
          }
           
        }
        else
        {
         
        }
        }
        else if(call_status!="")
        {
         location = url;
        }
      //alert(url);
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