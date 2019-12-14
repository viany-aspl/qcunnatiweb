<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
       
      </div>
      
        <h3>Incomming Call</h3>
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
        <style>
            .form-group{
                margin-bottom: 0px  !important;
            }
           
    #accordion{
        text-align: left;
        
    }
    #titlecol{color:#ff6d18;}
        </style>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-12">
             <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-date-start">Start Date</label>
                <div class="input-group date">
                  <input type="text" name="filter_start_date" value="<?php echo $filter_start_date; ?>" placeholder="Start Date" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
             </div>
              <div class="col-sm-3"> 
              <div class="form-group">
                <label class="control-label" for="input-date-end">End Date</label>
                <div class="input-group date">
                  <input type="text" name="filter_end_date" value="<?php echo $filter_end_date; ?>" placeholder="End Date" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              </div>
             <div class="col-sm-2"> 
              <div class="form-group">
                <label class="control-label" for="input-date-end">Mobile Number</label>
                
                  <input type="text" name="filter_number" value="<?php echo $filter_number; ?>" placeholder="Mobile Number"  id="filter_number" class="form-control" />
                  
              </div>
              </div>
			  <div class="col-sm-2"> 
              <div class="form-group">
                <label class="control-label" for="input-date-end">Status</label>
                
				<select name="filter_status" id="filter_status" class="form-control">
				<option value="">SELECT</option>
				<option <?php if($filter_status==18) { ?>selected="selected" <?php } ?> value="18">Missed Call</option>
				<?php foreach($callstatus as $call_status) { ?>
					<option <?php if($filter_status==$call_status['STATUS_ID']) { ?>selected="selected" <?php } ?> value="<?php echo $call_status['STATUS_ID']; ?>" ><?php echo $call_status['STATUS_NAME']; ?></option>
				<?php } ?>
				</select>
                  
                 
              </div>
              </div>
                <div class="col-sm-2" >
              <button type="button" id="button-search" style="margin-top:16% " class="btn btn-primary pull-right"><i class="fa fa-search"></i> Search</button>
            </div>
          
          </div>
        </div>
        </div>
        <div class="row">
         <div class="col-sm-4 text-right">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                 
                  <td class="text-left">Contact Number</td>
                  <td class="text-left">Date</td>
                  <td class="text-left">State Name</td>   
                  <td class="text-left">Status</td>                                  
                </tr>
              </thead>
              <tbody>
                <?php if ($orders) { ?>
                <?php foreach ($orders as $order) { //print_r($order); ?>
                <tr>
                    
                  <td class="text-left"><a href="#" onclick="return show_order_data(<?php echo $order['mobile']; ?>,<?php echo $order['status_id']; ?>,<?php echo $order['transid']; ?>);"><?php echo $order['mobile']; ?></a></td>
                  <td class="text-left"><?php echo $order['date_added']; ?></td>
                  <td class="text-left"><?php echo $order['state_name']; ?></td>
                  <td class="text-left"><?php echo $order['status']; ?></td>
                 
           
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
            <div class="col-sm-8 text-right" id="dtldiv" style="display:none">
                <ul class="nav nav-tabs">
         
          <li class="active"><a href="#tab-form"  data-toggle="tab">Fill Form</a></li>
           <li><a href="#tab-crop" data-toggle="tab">Crop</a></li>
           <li ><a href="#tab-product" data-toggle="tab">Product Information</a></li> 
          <li><a href="#tab-location" data-toggle="tab">Nearest Location</a></li>
         <li><a href="#tab-recharge" data-toggle="tab">Recharge history</a></li>
        </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab-form" style="min-height: 200px;">
                    <div id="display_before_call">
                        Please Select a Number
                        
                    </div>
                    <div id="display_after_call" style="display: none;">
               
               <input type="hidden" name="transid" id="transid" />
               <input type="hidden" name="mobile_number" id="mobile_number" />
               <input type="hidden" name="current_call_status" id="current_call_status" />
               <input type="hidden" name="logged_user_data" id="logged_user_data" value="<?php echo $logged_user_data; ?>" />
               
               <div class="col-sm-12"> 
               <div class="form-group" style="text-align: left;">
                <label class="control-label" for="input-order-status">Call Status </label> - <span id="span_for_number"></span>
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
                   
                   
                   
              <div class="form-group" style="text-align: left;">
             <div class="col-sm-6">
                <label class="control-label" for="input-order-status">Village</label>
                <input name="village" class="form-control" id="village" placeholder="Village" />
              </div>
              </div>
              <div class="col-sm-6">
              <div class="form-group" style="text-align: left;">
                <label class="control-label" for="date-added">Sowing Date</label>
                <div class="input-group date">
                  <input type="text" name="sowing_date" placeholder="Sowing Date" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" readonly="readonly"/>
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              </div>
               
        <div class="col-sm-6">
	<div class="form-group" style="text-align: left;">
                <label class="control-label" for="date-added" style="font-size:11px !important">When you will come to buy the product</label>
                <div class="input-group date">
                  <input type="text" name="txt_response" placeholder="" data-date-format="YYYY-MM-DD" id="txt_response" class="form-control" readonly="readonly" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
        </div>
        </div>
        <div class="col-sm-6">
           <div class="form-group" style="text-align: left;">
                <label class="control-label" for="input-order-status">Acres</label>
                <input name="Acres" class="form-control" id="Acres" placeholder="Acres" />
           </div>  
        </div>
        <div class="col-sm-6">
               	<div class="form-group" style="text-align: left;">
                <label class="control-label" for="input-order-status">Query</label>
                <input name="query" class="form-control" id="query" placeholder="Query" />
              </div>
        </div>
         <div class="col-sm-6">
               	<div class="form-group" style="text-align: left;">
                <label class="control-label" for="input-order-status">Solution</label>
                <input name="solution" class="form-control" id="solution" placeholder="Solution" />
              </div>
        </div>
        <div class="col-sm-6">
               <div class="form-group" style="text-align: left;">
               
                <label class="control-label" for="input-order-status">You Want to buy anything in future</label>
                <select name="buy_new" id="buy_new" class="form-control" onchange="return change_buy_info(this.value);">
                 <option value="" selected="selected">SELECT</option>
                 <option value="Yes">Yes</option>
                 <option value="No">No</option>
                 
                </select>
              </div>
        </div>
        
 	<div class="form-group"  id="buy_date_div" style="text-align: left;display: none;">
        <div class="col-sm-6">
                <label class="control-label" for="date-added">Buying Date</label>
                <div class="input-group date">
                  <input type="text" name="buying_date" placeholder="Buying Date" data-date-format="YYYY-MM-DD" id="buying_date" class="form-control" readonly="readonly" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
        </div>
               <div class="form-group" id="buy_info_div" style="text-align: left;display: none;">
         <div class="col-sm-12">
                <label class="control-label" for="input-order-status">What Products</label>
                <textarea name="buy_product_text" class="form-control" id="buy_product_text" placeholder="Products Information"></textarea>
              </div>
        </div>
                    
                
                </div>
               <div class="col-sm-12 pull-right"><br/>
                   <button type="button" style="display: none;" id="button-filter" class="btn btn-primary pull-right" onclick="return submitformdata();">Submit</button>
                </div>
                </div>
               </div>
                <div class="tab-pane " id="tab-crop">
                    <label class="control-label pull-left" for="input-order-status">Crop Name</label>
                    <select class="form-control" id="cropid" name="cropname" onchange="get_product(this.value);">
                        <option value="">SELECT</option>
                    <?php if ($crops) { ?>
                <?php foreach ($crops as $crop) { //if($crop["id"]!=12) { //print_r($order); ?>
                <option value="<?php echo $crop['id']; ?>"><?php echo $crop['crop_name']; ?></option>
                <?php } //} ?>
                        </select>
                <?php } else { ?>
                
                <?php } ?>
                <br/>
                <ul class="nav nav-tabs" id="crop_details_id" style="display: none;">
         
                    <li class="active"><a href="#tab-pcomplete" id="a-p-tab-1" data-toggle="tab" onclick="show_pcomplete();">Paddy Complete</a></li>
                 <li><a href="#tab-pbreak" data-toggle="tab" id="a-p-tab-2" onclick="show_pbreak();" >Paddy Break</a></li>    
                </ul>
                
                <div class="table-content">
                    <div class="tab-pane active" id="tab-pcomplete" ></div>
                    <div class="tab-pane " id="tab-pbreak"></div>
                </div>
            </div>
                <div class="tab-pane" id="tab-product">
<div class="col-sm-12">
<div class="col-sm-6">
<div class="form-group">
<label class="control-label pull-left" for="input-name">Store Name</label>
<br/>
<select class="form-control" id="filter_store" name="storename" >
<option value="">SELECT</option>
<?php foreach ($stores as $crop) { //print_r($order); ?>
<option value="<?php echo $crop['store_id']; ?>"><?php echo $crop['name']; ?></option>
<?php } ?>
</select>

</div></div>
<div class="col-sm-6">
<div class="form-group">
<label class="control-label pull-left" for="input-name">Product Name</label>
<br/>
<input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
<input type="hidden" name="filter_name_id" value="<?php echo $filter_name_id; ?>" id="filter_name_id"/>
</div>
</div>


<div class="col-sm-12"><br/>
<div class="table-responsive" id="product_info">
</div>
</div> </div>
</div>
                <div class="tab-pane" id="tab-location">
                
               <div class="form-group">
                   <label class="control-label pull-left" for="input-name">Select District</label>
                <select class="form-control" id="distid" name="distid" onchange="get_storelocation(this.value);">
                        <option value="0">SELECT</option>
                         <option value="HAR">Hardoi</option>
                         <option value="LAK">Lakhimpur</option>
                         <option value="SIT">Sitapur</option>
                          <option value="SHA">Shahjahanpur</option>
                </select>
                </div>
               
              
                <br/>
                    <div class="table-responsive" id="location_info">
  


                    </div>
                </div>

	<div class="tab-pane" id="tab-recharge">
<div class="form-group">
<div class="table-responsive">
<table class="table table-bordered">
<thead>
<tr>
<td class="text-center">Mobile No</td>

<td class="text-center">Recharge</td>

<td class="text-center">Status/Error</td>
<td class="text-center">Date</td>
</tr>
</thead>
<tbody id="recharge_info">

</tbody>
</table>
</div>
</div>
</div>




            </div>
      </div>
        
      </div>
          
    </div>
  </div>
  <script type="text/javascript">
$('#button-search').on('click', function() {
	url = 'index.php?route=ccare/incommingcall&token=<?php echo $token; ?>'; 
	
	
	var filter_start_date = $('input[name=\'filter_start_date\']').val();
	
	if (filter_start_date) {
		url += '&filter_start_date=' + encodeURIComponent(filter_start_date);
	}

	var filter_end_date = $('input[name=\'filter_end_date\']').val();
	
	if (filter_end_date) {
		url += '&filter_end_date=' + encodeURIComponent(filter_end_date);
	}
	var filter_status = $('#filter_status').val();
	
	if (filter_status) {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
	var filter_number = $('#filter_number').val();
	if (filter_number) {
		url += '&filter_number=' + encodeURIComponent(filter_number);
	}
	location = url;
});
</script> 
       <script>
          $('.date').datetimepicker({
      pickTime: false 
   });
    
          
         function submitformdata()
           {
              
        url = 'index.php?route=ccare/incommingcall/submit_call_data&token=<?php echo $token; ?>';
	var transid = $('input[name=\'transid\']').val();
	
	if (transid ) {
		url += '&transid=' + encodeURIComponent(transid);
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
        if((call_status==27) && (farmer_first_name==""))
        {
            alert('Please enter First name');
            $('input[name=\'farmer_first_name\']').focus();
            return false;
        }
        var farmer_last_name = $('input[name=\'farmer_last_name\']').val();

	if (farmer_last_name) {
		url += '&farmer_last_name=' + encodeURIComponent(farmer_last_name);
	}
        if((call_status==27) && (farmer_last_name==""))
        {
            alert('Please enter Last name');
            $('input[name=\'farmer_last_name\']').focus();
            return false;
        }
	
	var village = $('input[name=\'village\']').val();

	if (village) {
		url += '&village=' + encodeURIComponent(village);
	}	
	 if((call_status==27) && (village==""))
        {
            alert('Please enter Village name');
            $('input[name=\'village\']').focus();
            return false;
        }
	
        var sowing_date = $('input[name=\'sowing_date\']').val();
 	if (sowing_date) {
		url += '&sowing_date=' + encodeURIComponent(sowing_date);
	}
         if((call_status==27) && (sowing_date==""))
        {
            alert('Please enter Sowing date');
            $('input[name=\'sowing_date\']').focus();
            return false;
        }
        
        var txt_response = $('#txt_response').val();
	
	if (txt_response) {
		url += '&txt_response=' + encodeURIComponent(txt_response);
	}
        if((call_status==27) && (txt_response==""))
        {
            alert('Please enter when you will come to buy the product');
            $('input[name=\'txt_response\']').focus();
            return false;
        }
         var Acres = $('#Acres').val();
	
	if (Acres) {
		url += '&Acres=' + encodeURIComponent(Acres);
	}
         if((call_status==27) && (Acres==""))
        {
            alert('Please enter Acres');
            $('input[name=\'Acres\']').focus();
            return false;
        }
         var query = $('#query').val();
	
	if (query) {
		url += '&query=' + encodeURIComponent(query);
	}
        if((call_status==27) && (query==""))
        {
            alert('Please enter query');
            $('input[name=\'query\']').focus();
            return false;
        }
         var solution = $('#solution').val();
	
	if (solution) {
		url += '&solution=' + encodeURIComponent(solution);
	}
         if((call_status==27) && (solution==""))
        {
            alert('Please enter solution');
            $('input[name=\'solution\']').focus();
            return false;
        }
        var buy_new = $('#buy_new').val();
	
	if (buy_new) {
		url += '&buy_new=' + encodeURIComponent(buy_new);
	}
        if((call_status==27) && (buy_new==""))
        {
            alert('Please enter you want to buy anything in future');
            $('input[name=\'buy_new\']').focus();
            return false;
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
         if((call_status==27) && (Acres==""))
        {
            alert('Please enter Acres');
            $('input[name=\'Acres\']').focus();
            return false;
        }
         var query = $('#query').val();
	
	if (query) {
		url += '&query=' + encodeURIComponent(query);
	}
        if((call_status==27) && (query==""))
        {
            alert('Please enter query');
            $('input[name=\'query\']').focus();
            return false;
        }
         var solution = $('#solution').val();
	
	if (solution) {
		url += '&solution=' + encodeURIComponent(solution);
	}
         if((call_status==27) && (solution==""))
        {
            alert('Please enter solution');
            $('input[name=\'solution\']').focus();
            return false;
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
         if((call_status==27) && (buying_date=="") && (buy_new=="Yes"))
        {
            alert('Please enter Buying Date');
            $('input[name=\'solution\']').focus();
            return false;
        }
        
        
        var buy_product_text = $('#buy_product_text').val();
	
	if (buy_product_text) {
		url += '&buy_product_text=' + encodeURIComponent(buy_product_text);
	}
         if((call_status==27) && (buy_product_text=="") && (buy_new=="Yes"))
        {
            alert('Please enter product Information');
            $('input[name=\'solution\']').focus();
            return false;
        }
        
        if(call_status=="27")
        {
        if((mobile_number) && (call_status) && (farmer_first_name) && (village) && (sowing_date) && (txt_response) && (buy_new) && (Acres))
        {
        
          if(buy_new=="Yes")
          {
            if(buy_product_text!="")
            {
                location = url;
                 
            }
            else
            {
             alert('Please Enter product Inforamtion');
            }
          }
          else
          {
	    location = url;
             alert(url);
          }
           
        }
        else
        {
         alert('Please fill all the required fields');
        }
        }
        else if(call_status!="")
        {
         location = url;
        }
               //alert(url);
               return false;
           }
 
  $( function() {
    $("#tab-pcomplete").hide();
    $("#tab-pbreak").hide();
  } );
  function show_pcomplete() {
   
      $("#tab-pcomplete").show();
      $("#tab-pbreak").hide();
      
  }
  function show_pbreak() {
      $("#tab-pcomplete").hide();
      $("#tab-pbreak").show();
  }
  </script>
      <script type="text/javascript">
          function get_product(crop_id)
          {
              
            $.ajax({
            url: 'index.php?route=ccare/incommingcall/cropprodinfo&token=<?php echo $token; ?>&crop_id='+crop_id,
             
              success: function(json1) { //alert(json1); alert(crop_id);
                  var json2=json1.split('-----and----');
                  var json=json2[0];
                  var json3=json2[1];
       	if(crop_id==12)
                  {
                  $("#a-p-tab-1").html('Cane Complete');
                  $("#a-p-tab-2").html('Cane Break');
                  }
                  else
                  {
                        $("#a-p-tab-1").html('Paddy Complete');
                        $("#a-p-tab-2").html('Paddy Break');
                  }
              $("#tab-pcomplete").show();
              $("#tab-pcomplete").html(json);
              $("#tab-pbreak").html(json3);
              $("#tab-pbreak").hide();
            }
        });
            if(crop_id!="")
            {
               $("#crop_details_id").show();
            }
            else
            {
                $("#crop_details_id").hide();
                $("#tab-pcomplete").hide();
                $("#tab-pbreak").hide();
            }
          }
          
          function get_storelocation(distid)
          {
                //alert(distid);
                $.ajax({
                url: 'index.php?route=ccare/incommingcall/getstorelocation&token=<?php echo $token; ?>&distid=' +distid,
                success: function(dataaa) {
                   // alert(dataaa);
                    $("#location_info").html(dataaa);
               
            }
               }); 
          }
          
          
$('input[name=\'filter_name\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['product_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'filter_name\']').val(item['label']);
                $('input[name=\'filter_name_id\']').val(item['value']);
                testt(item['value'],item['label']);
    }
});
function testt(product_id,product_name)
{ //alert(product_name);
var filter_store=$('#filter_store').val();

$.ajax({
url: 'index.php?route=ccare/incommingcall/productinfo&token=<?php echo $token; ?>&product_id=' +product_id+'&filter_store='+filter_store,
//dataType: 'json',
success: function(dataaa) {
//console.log(dataaa);
$("#product_info").html(dataaa);

}
});
}
</script>

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
              if(selected_value=="27")
              {
                 $("#form_div").show(); 
                 $("#button-filter").show();
              }
              
              else
              {
                 if(selected_value!="")
                 {
                    $("#button-filter").show(); 
                 }
                 else
                 {
                     $("#button-filter").hide();
                 }
                 $("#form_div").hide();  
              }
              return false;
          }
          
          function show_order_data(mobile,current_call_status,transid)
          {   
                   $("#dtldiv").show();
                   $("#mobile_number").val(mobile);
                   $("#transid").val(transid);
                   $("#display_before_call").hide();
                   $("#display_after_call").show();
                   $("#current_call_status").val(current_call_status);
                   $("#span_for_number").html(mobile);
	     $.ajax({
url: 'index.php?route=ccare/incommingcall/getrechargelist&token=<?php echo $token; ?>&mobile=' + encodeURIComponent(mobile),
//dataType: 'json',
complete: function(json) {

//alert(JSON.stringify(json));
$("#recharge_info").html(json.responseText);  
},
error: function(err)
{
// alert(JSON.stringify(err));
console.log(json);
}
});
             return false; 
          }
          </script>
  <script type="text/javascript">
      
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
//--></script>

  
</div>
<?php echo $footer; ?>