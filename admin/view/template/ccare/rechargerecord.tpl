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
      
        <h3>Recharge Call</h3>
      <!--<ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>-->
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
       <style>
            .form-group{
                margin-bottom: 0px  !important;
            }
            
        </style>
    <div class="panel panel-default">
     
      <div class="panel-body">
        
        <div class="row">
         <div class="col-sm-6 text-right">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>                 
                  <td class="text-left">Customer Mobile</td>
                  <td class="text-left">Recharge Date</td>
                  <td class="text-left">Recharge Amount</td>
                  <td class="text-left">Recharge Scheme</td> 
                  <td class="text-left">Recharge Status</td>
                  <td class="text-left">Call Status</td>
                </tr>
              </thead>
              <tbody>
                <?php if ($orders) { ?>
                <?php foreach ($orders as $order) { //print_r($order); ?>
                <tr id="<?php echo $order['telephone']; ?>tr">                    
                  <td class="text-left"><a href="#" onclick="return show_order_data(<?php echo $order['telephone']; ?>,<?php echo $order['order_id']; ?>,<?php echo $order['call_status']; ?>,'<?php echo $order['ResSerSts']; ?>','<?php echo $order['ResRocTransID']; ?>','<?php echo $order['recharge_amount']; ?>','<?php echo $order['transid']; ?>');"><?php echo $order['telephone']; ?></a></td>
                  <td class="text-left"><?php echo $order['recharge_date']; ?></td>
                  <td class="text-left"><?php echo $order['recharge_amount']; ?></td> 
                  <td class="text-left" ><?php echo $order['scheme_name']; ?></td> 
                  <td class="text-left"  id="<?php echo $order['telephone']; ?>"><?php echo $order['ResSerSts']; ?></td>
                  <td class="text-left"><?php echo $order['call_STATUS_NAME']; ?></td> 
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
                <div class="processing" style="display: none;z-index: 200000;position: absolute;width: 100%;height: 100%;">
                    <img style="width: 150px;    margin-right: 40%;margin-top: 70px;" src="http://www.plastemart.com/images/processing.gif" /></div>
                <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-history"  data-toggle="tab">Fill Form</a></li>
          <li><a href="#tab-order" data-toggle="tab">Order Details</a></li>
         
          
          <li><a href="#tab-product" data-toggle="tab">Products</a></li>
          
          <li><a href="#tab-rstatus"  data-toggle="tab">Recharge Status</a></li>
          
        </ul>
            <div class="tab-content">
                <div class="tab-pane " id="tab-order">
                    
                    Please Select a Number
                </div>
                <div class="tab-pane" id="tab-product">
                    
                    Please Select a Number
                </div>
                <div class="tab-pane" id="tab-rstatus">
                    
                    <div id="display_before_call_r">
                    Please Select a Number
                    </div>
                   <div id="display_after_call_r" style="display: none;">
                       <div id="div_for_success" style="font-size: 14px;font-weight: bold;color: #08BF37;">
                           Recharge was Successfull.
                       </div>
                       <div id="div_for_re_recharge">
                           <select class="form-control" name="operator_code" id="operator_code">
                               <option value="">SELECT</option>
                               <option value="28">Airtel</option>
                               <option value="8">Idea</option>
                               <option value="22">Vodafone</option>
                           </select>
                           <br/><br/>
                           <select class="form-control" name="pre_post" id="pre_post">
                               
                               <option value="prepaid">Prepaid</option>
                               <option value="postpaid">Postpaid</option>
                           </select>
                           <br/><br/>
                           <a class="btn btn-primary pull-right" href="#" onclick="return send_to_Re_recharge();">Re-Recharge</a>
                       </div>
                       <div id="div_for_re_recharge_after"></div>
                       <div id="div_for_pending" style="font-size: 14px;font-weight: bold;color: navy;">
                           Recharge is under processing.<br/><br/>
                           <a class="btn btn-primary pull-right" href="#" onclick="return check_recharge_status();">Check here to check Recharge Status</a>
                       </div>  
                   </div>
                </div>
                <div class="tab-pane active" id="tab-history">
                    <div id="display_before_call">
                        Please Select a Number
                        
                    </div>
                    <div id="display_after_call" style="display: none;">
               <input type="hidden" name="order_id" id="order_id" />
               <input type="hidden" name="mobile_number" id="mobile_number" />
               <input type="hidden" name="ResRocTransID" id="ResRocTransID" />
               <input type="hidden" name="recharge_amount" id="recharge_amount" />
               <input type="hidden" name="rtransid" id="rtransid" />
               <input type="hidden" name="current_call_status" id="current_call_status" />
               <input type="hidden" name="logged_user_data" id="logged_user_data" value="<?php echo $logged_user_data; ?>" />
               <input type="hidden" name="current_order_status" id="current_order_status" value="<?php echo $current_order_status; ?>" />
               <div class="col-sm-12"> 
               <div class="form-group" style="text-align: left;">
                <label class="control-label" for="input-order-status">Call Status</label>
                <select name="call_status" onchange="return change_form(this.value);" id="call-status" class="form-control">
                 <option value="" selected="selected">SELECT</option>
                 <?php foreach($callstatus as $calls) {  ?>
                 <option value="<?php echo $calls["STATUS_ID"]; ?>"><?php echo $calls["STATUS_NAME"]; ?></option>
                
                 <?php } ?>
                </select>
              </div>
               </div>
               <div id="form_div" style="display: none;">
            
             <div class="form-group" style="text-align: left;">
             <div class="col-sm-6">
                <label class="control-label" for="input-order-status">First Name</label>
                <input name="farmer_first_name" class="form-control" id="farmer_first_name" placeholder="First Name" />
              </div>
              </div>
              <div class="col-sm-6">
              <div class="form-group" style="text-align: left;">
                <label class="control-label" for="date-added">Last Name</label>
                
                  <input name="farmer_last_name" class="form-control" id="farmer_last_name" placeholder="Last name" />
               
              </div>
              </div>      
                 
        
        <div class="col-sm-12">
               	<div class="form-group" style="text-align: left;">
                <label class="control-label" for="input-order-status">Remarks</label>
                <textarea name="Reason_of_response" class="form-control" id="Reason_of_response" placeholder="Remarks"></textarea>
              </div>
        </div>
        
                    </div><br/>
                <div class="col-sm-12 pull-right">
                    <br/>
                    <button type="button" id="button-filter" class="btn btn-primary pull-right">Submit</button>
                </div>
                </div>
               </div>
            </div>
      </div>
        
      </div>
          
    </div>
  </div>
      <script>
          $("#div_for_success").hide();
          $("#div_for_re_recharge").hide();
          $("#div_for_re_recharge_after").hide();
          $("#div_for_pending").hide();
          function send_to_Re_recharge()
          {
              var ResRocTransID=$("#ResRocTransID").val();
              var mobile=$("#mobile_number").val();
              var recharge_amount=$("#recharge_amount").val();
              var rtransid=$("#rtransid").val();
              var operator_code=$("#operator_code").val();
              var pre_post=$("#pre_post").val();
              
              var theurl='index.php?route=ccare/ccare/send_to_re_recharge&token=<?php echo $token; ?>&mobile=' +  encodeURIComponent(mobile)+'&operator_code=' +  encodeURIComponent(operator_code)+'&recharge_amount='+recharge_amount+'&rockettranstblid=' +  encodeURIComponent(ResRocTransID)+'&transtblid='+rtransid+'&pre_post='+pre_post;
              //alert(theurl);
              if(operator_code!="")
              {
              $.ajax({
              url: theurl,
              
            beforeSend  : function()
            {
              $(".processing").show();
              $("#div_for_re_recharge").hide();
            },
               success: function(json) 
               {
                   $("#div_for_success").hide();
                   $("#div_for_re_recharge").hide();
                   $("#div_for_re_recharge_after").hide();
                   $("#div_for_pending").hide();
                   //alert(json); 
                   if(json!="")
                   {
                   $("#"+mobile+'tr').hide(); 
                   $("#div_for_re_recharge_after").html(json);
                   if(json=="Refund")
                   {
                      $("#div_for_re_recharge_after").show();
                      $("#div_for_re_recharge").hide(); 
                   }
                   else
                   {
                   $("#div_for_re_recharge_after").show();
                   $("#div_for_re_recharge").hide(); 
                   }
                   }
                  $(".processing").hide();
               }
                       
              });
          }
          else
          {
              alert('Please select Operator');
          }
              return false;
          }
          function check_recharge_status()
          {
              var ResRocTransID=$("#ResRocTransID").val();
              var mobile=$("#mobile_number").val();
              var rtransid=$("#rtransid").val();
              $.ajax({
              url: 'index.php?route=ccare/ccare/check_recharge_status&token=<?php echo $token; ?>&mobile=' +  encodeURIComponent(mobile)+'&ResRocTransID=' +  encodeURIComponent(ResRocTransID)+'&rtransid='+rtransid, 
              
            beforeSend  : function()
            {
              $(".processing").show();
              $("#div_for_pending").hide();
            },
               success: function(json) 
               {
                   $("#div_for_success").hide();
                   $("#div_for_re_recharge").hide();
                   $("#div_for_re_recharge_after").hide();
                   $("#div_for_pending").hide();
                   //alert(json); 
                   if(json!="")
                   {
                   $("#"+mobile).html(json); 
                   //$("#div_for_pending").html(json);
                   if(json=="Refund")
                   {
                      $("#div_for_re_recharge").show(); 
                      alert('Recharge was failed. please recharge again');
                   }
                    else if(json=="Success")
                   {
                      $("#div_for_pending").hide();
                      $("#div_for_success").show();
                   }
                   else
                   {
                   $("#div_for_pending").show();
                   alert('Recharge is under processing');
                   }
                   }
                   else if(ResRocTransID=="")
                   {
                       $("#div_for_re_recharge").show(); 
                      alert('Recharge was failed. please recharge again');
                   }
                   
                   
                  $(".processing").hide();
               }
                       
              });
              return false;
          }
          
          function change_form(selected_value)
          {
              if(selected_value=="27")
              {
                 $("#form_div").show(); 
              }
              
              else
              {
                 $("#form_div").hide();  
              }
              return false;
          }
          
          function show_order_data(mobile,order_id,call_status,recharge_status,ResRocTransID,recharge_amount,rtransid)
          {   //order_id=36;
              
              $.ajax({
              url: 'index.php?route=ccare/ccare/get_order_info_recharge&token=<?php echo $token; ?>&mobile=' +  encodeURIComponent(mobile)+'&order_id=' +  encodeURIComponent(order_id),
              
            beforeSend  : function()
            {
              $(".processing").show();
              
            },
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
                   $("#current_call_status").val(call_status);
                   $("#recharge_amount").val(recharge_amount);
                   $("#rtransid").val(rtransid);
                   $(".processing").hide();
               }
                       
              });
              $("#div_for_success").hide();
              $("#div_for_re_recharge").hide();
              $("#div_for_pending").hide();
              $("#div_for_re_recharge_after").hide();
              if(recharge_status=="Success")
              {
                  $("#div_for_success").show();
              } 
              if(recharge_status=="Refund")
              {
                  $("#div_for_re_recharge").show();
                  
              } 
              if(recharge_status=="Pending")
              {
                  $("#div_for_pending").show();
                  //$("#div_for_pending").html('<a href="#" onclick="return check_recharge_status();">Check Recharge Status</a>');
                  
              }
	if(recharge_status=="")
              {
                  $("#div_for_pending").show();
                  //$("#div_for_pending").html('<a href="#" onclick="return check_recharge_status();">Check Recharge Status</a>');
                  
              }
              if(recharge_status=="Unknown Error")
              {
                  $("#div_for_re_recharge").show();
              }
              if(recharge_status=="Insufficient Balance")
              {
                  $("#div_for_re_recharge").show();
              }
              if(recharge_status=="Invalid Account Number")
              {
                  $("#div_for_re_recharge").show();
              }
              
              $("#display_after_call_r").show();
              $("#display_before_call_r").hide();
              $("#ResRocTransID").val(ResRocTransID);
             return false; 
          }
          </script>
  <script type="text/javascript">
$('#button-filter').on('click', function() {
	url = 'index.php?route=ccare/ccare/submit_recharge_call_data&token=<?php echo $token; ?>';
	
	var order_id = $('input[name=\'order_id\']').val();
	
	if (order_id) {
		url += '&order_id=' + encodeURIComponent(order_id);
	}
        var ResRocTransID = $('input[name=\'ResRocTransID\']').val();
	
	if (ResRocTransID) {
		url += '&ResRocTransID=' + encodeURIComponent(ResRocTransID);
	}
        var recharge_amount = $('input[name=\'recharge_amount\']').val();
	
	if (recharge_amount) {
		url += '&recharge_amount=' + encodeURIComponent(recharge_amount);
	}
        var rtransid = $('input[name=\'rtransid\']').val();
	
	if (rtransid) {
		url += '&rtransid=' + encodeURIComponent(rtransid);
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
	
	var Reason_of_response = $('#Reason_of_response').val();

	if (Reason_of_response) {
		url += '&remarks=' + encodeURIComponent(Reason_of_response);
	}
        var logged_user_data = $('#logged_user_data').val();
	
	if (logged_user_data) {
		url += '&logged_user_data=' + encodeURIComponent(logged_user_data);
	}
        //alert(url);
        if(call_status=="27")
        {
        if((order_id) && (mobile_number) && (call_status) && (farmer_first_name) )
        {   $(".processing").show();
             location = url;
             //alert(url);
        }
        else
        {
         alert('Please fill all the fields');
        }
        }
        else if(call_status!="")
        {
            $(".processing").show();
            location = url; 
        }
        else
        {
            return false;
        }
      //alert(url);
});
  </script> 
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