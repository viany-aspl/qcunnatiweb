<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      
      <h1>Credit Posting (Partner)</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
<div class="pull-right">
	<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>  
</div>
    </div>
   </div>
   <ul class="nav nav-tabs">
		  
            <li class="active"><a href="#tab-bank_payment" data-toggle="tab">Bank Payment</a></li>
            <li><a href="#tab-tagged_payment" data-toggle="tab">Partner Tagged Payment</a></li>
            <li><a href="#tab-subsidy_payment" data-toggle="tab">Partner Subsidy Payment</a></li>         
    </ul>
  <div class="tab-content">
  <div class="tab-pane active" id="tab-bank_payment">
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    
    <?php } ?>
	
    <div class="panel panel-default">
	
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i>Payout Detail</h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $bank_payment_form; ?>" method="post" enctype="multipart/form-data" id="form-bank_payment" class="form-horizontal">
        
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-order-id">Unit</label>
                <div class="col-sm-10">
                <select id="unit" name="unit"  onchange="return unitstore(this.value);"  required="required" class="form-control">
		        <option value="">Select Unit</option>
		        <?php foreach ($units as $listunit) { ?>                   
                        <option value="<?php echo $listunit['unit_id']; ?>" <?php if($listunit['unit_id']==$filter_unit) { echo 'selected'; } ?>><?php echo $listunit['unit_name']; ?></option>
                    
                  <?php } ?>
	        </select>
                  <?php if ($error_order_id) { ?>
                  <div class="text-danger"><?php echo $error_order_id; ?></div>
                  <?php } ?>
                </div>
              </div>             
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-customer">Store</label>
                <div class="col-sm-10">
                  <select name="store" id="input-store" required="required" class="form-control">
			<option  value="">Select Store</option>
			<?php foreach ($store as $liststore) { ?>                   
                        <option value="<?php echo $liststore['store_id']; ?>" <?php if($liststore['store_id']==$filter_store) { echo 'selected'; } ?>><?php echo $liststore['name']; ?></option>
                    
                  <?php } ?>
	        </select>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-firstname">Transaction</label>
                <div class="col-sm-10">
                  <select name="transaction_type" id="transaction_type"  required="required" class="form-control">
					<option  value="">Select Transaction</option>
                                        <option value="Credit Posting">Credit</option>
                                        
                                        <option value="Waiver Subsidy">Waiver Subsidy</option>
                                        
				
		   </select>
                  <?php if ($error_firstname) { ?>
                  <div class="text-danger"><?php echo $error_firstname; ?></div>
                  <?php } ?>
                </div>
              </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-email">Payment Method</label>
                <div class="col-sm-10">
                  <select name="payment_method" id="payment_method"  required="required" class="form-control">
					<option  value="">Select Payment Method</option>
                                        <option value="Cash">Cash</option>
                                       <option value="Cheque">Cheque</option>
                                      <option value="NEFT">NEFT</option>
                                        <option value="RTGS">RTGS</option>
			<option value="IMPS">IMPS</option>	
		    </select>
                  <?php if ($error_email) { ?>
                  <div class="text-danger"><?php echo $error_email; ?></div>
                  <?php  } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Enter Amount</label>
                <div class="col-sm-10">
                  <input type="text" name="amount" id="amount" required="required" value="" placeholder="Enter Amount" id="input-product" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57 || event.keyCode==8 || event.keyCode==46" class="form-control" />
                  <?php if ($error_lastname) { ?>
                  <div class="text-danger"><?php echo $error_lastname; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required ">
                <label class="col-sm-2 control-label" for="input-lastname">Transaction Number</label>
                <div class="col-sm-10">
                  <input type="text" name="tr_number" id="tr_number" required="required" value="" placeholder="Transaction Number" id="input-tr_number" class="form-control" />
                  <?php if ($error_lastname) { ?>
                  <div class="text-danger"><?php echo $error_lastname; ?></div>
                  <?php } ?>
                </div>
              </div>
                 <input type="button" onclick="return submit_bank_payment();" id="bank_payment_submit" value="Submit" class="btn btn-primary pull-right" />                  
          </div>
        
        </form>
     
    </div>
  </div>
 </div>
 
  <div class="tab-pane" id="tab-tagged_payment">
   <div class="panel-body">
 <div id="content">
  <div class="container-fluid">
    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i>Partner Tagged Payment</h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $tagged_form; ?>" method="post" enctype="multipart/form-data" id="tagged_payment_form" class="form-horizontal">
	<input type="hidden" value="0" id="is_tagged_pending_orders" /> 
	<div class="form-group ">
                <label class="col-sm-2 control-label" for="input-meta-title">Date</label>
                <div class="col-sm-10 input-group date_tagged" style="padding-right: 17px;padding-left: 15px;">
                   <input style="margin-left:0px;" readonly='readonly' type="text" name="filter_tagged_date" value="" placeholder="" data-date-format="YYYY-MM-DD" id="input-filter_tagged_date" class="form-control" />
                 <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span> 
				 
                </div>
            </div>
	
	<div class="form-group ">
                <label class="col-sm-2 control-label" for="input-meta-title">Store</label>
                <div class="col-sm-10">
                  <select name="tagged_store" id="input-taggedstore" onchange="get_amount('tagged');"  class="form-control" >
                  <option  value="">Select Store</option>
			<?php foreach ($store as $liststore) { ?>                   
                        <option value="<?php echo $liststore['store_id']; ?>" <?php if($liststore['store_id']==$filter_store) { echo 'selected'; } ?>><?php echo $liststore['name']; ?></option>
                    
                  <?php } ?>
                   
                </select>
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>
                </div>
            </div>

     
           <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-meta-title">Tagged Value</label>
                <div class="col-sm-10">
                    <input type="text" name="tagged_value" value="" placeholder="Tagged Value" id="tagged_value" class="form-control"  required="required" readonly="readonly" />
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>

	<span id="tagged_order_count"></span>
                </div>
            </div>
         
	<div class="form-group ">
             
                <div class="col-sm-12">
                   <input type="button" onclick="return submit_tagged_payment();" id="tagged_submit" disabled value="Submit" class="btn btn-primary pull-right" />
                </div>
            </div>

        </form>
      </div>
    </div>
  </div> </div> </div>
  </div>


<div class="tab-pane" id="tab-subsidy_payment">
  <div class="panel-body">
  <div id="content">
  
  <div class="container-fluid">
    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i>Partner Subsidy Payment</h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $subsidy_form; ?>" method="post" enctype="multipart/form-data" id="subsidy_payment_form" class="form-horizontal">
	<input type="hidden" value="0" id="is_subsidy_pending_orders" />
	
			<div class="form-group ">
                <label class="col-sm-2 control-label" for="input-meta-title">Date</label>
                <div class="col-sm-10 input-group date_subsidy" style="padding-right: 17px;padding-left: 15px;">
                   <input style="margin-left:0px;" readonly='readonly' type="text" name="filter_subsidy_date" value="" placeholder="" data-date-format="YYYY-MM-DD" id="input-filter_subsidy_date" class="form-control" />
                 <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span> 
				 
                </div>
            </div>
			<div class="form-group ">
                <label class="col-sm-2 control-label" for="input-meta-title">Store</label>
                <div class="col-sm-10">
                  <select name="subsidy_store" id="input-subsidystore" onchange="get_amount('subsidy')"  class="form-control" >
                  <option  value="">Select Store</option>
			<?php foreach ($store as $liststore) { ?>                   
                        <option value="<?php echo $liststore['store_id']; ?>" <?php if($liststore['store_id']==$filter_store) { echo 'selected'; } ?>><?php echo $liststore['name']; ?></option>
                    
                  <?php } ?>
                   
                </select>
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>
                </div>
            </div>

     
           
	       <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-meta-title">Subsidy Value</label>
                <div class="col-sm-10">
                    <input type="text" name="subsidy_value" value="" placeholder="Subsidy Value" id="subsidy_value" class="form-control"  readonly="readonly" required="required" />
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>
		<span id="subsidy_order_count"></span>
                </div>
            </div>
         <div class="form-group ">
              
                <div class="col-sm-12">
                   <input type="button" onclick="return submit_subsidy_payment();" id="subsidy_submit" disabled value="Submit" class="btn btn-primary pull-right" />
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
 
  </div>
  </div>
 </div>
 </div>
  <script type="text/javascript">
  function submit_tagged_payment()
  {
	  var filter_tagged_date=$("#input-filter_tagged_date").val();
	if(!filter_tagged_date)
	{
		alertify.error('Please Select Tagged Bill Date');
		return false;
	}
	var taggedstore=$("#input-taggedstore").val();
	if(!taggedstore)
	{
		alertify.error('Please Select Store');
		return false;
	}
	var tagged_value=$("#tagged_value").val();
	if(tagged_value=='0.00')
	{
		alertify.error('This amount is not Allowed');
		return false;
	}
	$( "#tagged_payment_form" ).submit();
	return false;
  }
  function submit_subsidy_payment()
  {
	  var filter_subsidy_date=$("#input-filter_subsidy_date").val();
	if(!filter_subsidy_date)
	{
		alertify.error('Please Select Subsidy Bill Date');
		return false;
	}
	var subsidystore=$("#input-subsidystore").val();
	if(!subsidystore)
	{
		alertify.error('Please Select Store');
		return false;
	}
	var subsidy_value=$("#subsidy_value").val();
	if(subsidy_value=='0.00')
	{
		alertify.error('This amount is not Allowed');
		return false;
	}
	$( "#subsidy_payment_form" ).submit();
	return false;
  }
function submit_bank_payment()
{
	var unit=$("#unit").val();
	if(!unit)
	{
		alertify.error('Please Select Unit');
		return false;
	}
	var input_store=$("#input-store").val();
	if(!input_store)
	{
		alertify.error('Please Select Store');
		return false;
	}
	var transaction_type=$("#transaction_type").val();
	if(!transaction_type)
	{
		alertify.error('Please Select transaction type');
		return false;
	}
	var payment_method=$("#payment_method").val();
	if(!payment_method)
	{
		alertify.error('Please Select payment method');
		return false;
	}
	var amount=$("#amount").val();
	if(!amount)
	{
		alertify.error('Please Enter Amount');
		return false;
	}
	var tr_number=$("#tr_number").val();
	if(!tr_number)
	{
		alertify.error('Please Enter transaction number');
		return false;
	}
	$( "#form-bank_payment" ).submit();
	return false;
}

function unitstore(unitid)
{
$.ajax({
type: "POST",
url: "index.php?route=unit/unit/getstorebyunit&token=<?php echo $token; ?>&unitid="+unitid,
// data: unitid,
cache: false,
contentType: false,
processData: false,
success: function(data)
{
$('#input-store').html(data);
}
});
}
</script>
  <script type="text/javascript">

$(".date_tagged").datetimepicker({
pickTime: false,
    onSelect: function(dateText) {
      get_amount('tagged');
    }
  }).on("change", function() {
    get_amount('tagged');
  });
$(".date_subsidy").datetimepicker({
pickTime: false,
    onSelect: function(dateText) {
      get_amount('subsidy');
    }
  }).on("change", function() {
    get_amount('subsidy');
  });
  function get_amount(tagged_or_subsidy) {
	var is_pending_order='0';
	if(tagged_or_subsidy=='tagged')
	{
		var selected_date=$("#input-filter_tagged_date").val();
		var selected_store=$("#input-taggedstore").val();
		$( "#tagged_submit" ).prop( "disabled", true );
		if((selected_date) && (selected_store))
		{
		var url ='index.php?route=partner/bank_payment/getTaggedvaluebyStoreDate&token=<?php echo $token; ?>&storeid='+selected_store+'&date='+selected_date;
		$.ajax({ 
			type: 'post',
			dataType: 'json',
			url: url,
			cache: false,
			beforeSend: function() 
			{
				$("#tagged_order_count").html('');
			},
			success: function(data) 
			{ 	
				//alert(JSON.stringify(data));
				
				if(data['taggedamount']=='0.00')
				{
					alertify.error('No Tagged Bill found for the Selected Date and Store');
					$('#tagged_value').val('0.00');
					return false;
				}
				else
				{
					$('#tagged_value').val(data['taggedamount']);
					for(var icount=0;icount<(data['getOrdersCountByStoreDate']).length;icount++ )
					{
						//alert(JSON.stringify(data['getOrdersCountByStoreDate'][icount]));
						if(data['getOrdersCountByStoreDate'][icount]['order_status_id']=='1')
						{
							var order_status='Pending Orders : ';
						}
						if(data['getOrdersCountByStoreDate'][icount]['order_status_id']=='1')
						{
							var order_status='Pending Orders : ';
							$("#tagged_order_count").append(order_status+data['getOrdersCountByStoreDate'][icount]['total_orders']+'<br/>');
							is_pending_order='1';
						}
						if(data['getOrdersCountByStoreDate'][icount]['order_status_id']=='5')
						{
							var order_status='Completed Orders : ';
							$("#tagged_order_count").append(order_status+data['getOrdersCountByStoreDate'][icount]['total_orders']+'<br/>');
						}
						
					}//////////for loop end here
					
					if(is_pending_order==0)
					{
						$("#is_tagged_pending_orders").val('0');
						$( "#tagged_submit" ).prop( "disabled", false );
					}
					else
					{
						$("#is_tagged_pending_orders").val('1');
						$( "#tagged_submit" ).prop( "disabled", true );
					}
					
				}
			}
		});
		
		}
	}
	if(tagged_or_subsidy=='subsidy')
	{
		var is_subsidy_pending_order='0';
		var selected_date=$("#input-filter_subsidy_date").val();
		var selected_store=$("#input-subsidystore").val();
		$( "#subsidy_submit" ).prop( "disabled", true );
		if((selected_date) && (selected_store))
		{
		var url ='index.php?route=partner/bank_payment/getSubsidyvaluebyStoreDate&token=<?php echo $token; ?>&storeid='+selected_store+'&date='+selected_date;
		$.ajax({ 
			type: 'post',
			dataType: 'json',
			url: url,
			cache: false,
			beforeSend: function() 
			{
				$("#subsidy_order_count").html('');
			},
			success: function(data) 
			{ 	
				//alert(JSON.stringify(data['getOrdersCountByStoreDate']));
				
				if(data['taggedamount']=='0.00')
				{
					alertify.error('No Subsidy Bill found for the Selected Date and Store');
					$('#subsidy_value').val('0.00');
					return false;
				}
				else
				{
					$('#subsidy_value').val(data['taggedamount']);
					for(var icount=0;icount<(data['getOrdersCountByStoreDate']).length;icount++ )
					{
						
						if(data['getOrdersCountByStoreDate'][icount]['order_status_id']=='1')
						{
							var order_status='Pending Orders : ';
						}
						if(data['getOrdersCountByStoreDate'][icount]['order_status_id']=='1')
						{
							var order_status='Pending Orders : ';
							$("#subsidy_order_count").append(order_status+data['getOrdersCountByStoreDate'][icount]['total_orders']+'<br/>');
							is_subsidy_pending_order='1';
						}
						if(data['getOrdersCountByStoreDate'][icount]['order_status_id']=='5')
						{
							var order_status='Completed Orders : ';
							$("#subsidy_order_count").append(order_status+data['getOrdersCountByStoreDate'][icount]['total_orders']+'<br/>');
						}
						
					}//////////for loop end here
					
					if(is_subsidy_pending_order==0)
					{
						$("#is_subsidy_pending_orders").val('0');
						$( "#subsidy_submit" ).prop( "disabled", false );
					}
					else
					{
						$("#is_subsidy_pending_orders").val('1');
						$( "#subsidy_submit" ).prop( "disabled", true );
					}
					
				}
			}
		});
		
		}
	}
	
  }
/*
$('.date').datetimepicker({ 
	onSelect: function(dateText) {
    alert('okk');
  },
	
});
*/
</script>

</div>
<?php echo $footer; ?>