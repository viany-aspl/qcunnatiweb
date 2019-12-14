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
<div class="container-fluid">
   <ul class="nav nav-tabs">
		  
            <li <?php if(empty($active_tab)){ ?> class="active" <?php } ?>><a href="#tab-bank_payment" data-toggle="tab">Bank Payment</a></li>
            <li <?php if($active_tab=='tagged'){ ?> class="active" <?php } ?>><a href="#tab-tagged_payment" data-toggle="tab">Partner Tagged Payment</a></li>
            <li <?php if($active_tab=='subsidy'){ ?> class="active" <?php } ?>><a href="#tab-subsidy_payment" data-toggle="tab">Partner Subsidy Payment</a></li>         
    </ul>
  <div class="tab-content">
  <div class="tab-pane <?php if(empty($active_tab)){ ?> active <?php } ?>" id="tab-bank_payment">
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
                  <select name="store" id="input-store" style="width: 100%;" required="required" class="form-control">
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
											<option value="Debit">Debit</option>
                                        
				
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
			<option value="SETTLEMENT AMOUNT">SETTLEMENT AMOUNT</option>	
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
			  <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-lastname">Transaction Date</label>
                <div class="col-sm-10 input-group date">
                  <input type="text" name="transaction_date" value="" placeholder="Transaction Date" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
                </div>
              </div>
			  
			  <div class="form-group  ">
                <label class="col-sm-2 control-label" for="input-lastname">Remarks</label>
                <div class="col-sm-10">
                  <textarea name="remarks" id="remarks" value="" placeholder="Remarks"  class="form-control" ></textarea>
                 
                </div>
              </div>
                 <input type="button" onclick="return submit_bank_payment();" id="bank_payment_submit" value="Submit" class="btn btn-primary pull-right" />                  
          </div>
        
        </form>
     
    </div>
  </div>
 </div>
 
  <div class="tab-pane <?php if($active_tab=='tagged'){ ?> active <?php } ?>" id="tab-tagged_payment">
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
        <div class="well">
          <div class="row">
		<div class="col-sm-6" >
			<div class="form-group ">
                <label class="control-label" for="input-meta-title">Start Date</label>
                <div class="input-group date_tagged_start">
                   <input  readonly='readonly' type="text" name="filter_tagged_date_start" value="<?php echo $filter_tagged_date_start; ?>" placeholder="" data-date-format="YYYY-MM-DD" id="input-filter_tagged_date_start" class="form-control" />
                 <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span> 
				 
                </div>
            </div>
			
			<div class="form-group ">
                <label class="control-label" for="input-meta-title">Store</label>
               
                  <select name="tagged_store" style="width: 100%;" id="input-taggedstore"  class="form-control" >
                  <option  value="">Select Store</option>
			<?php foreach ($store as $liststore) { ?>  
						
                        <option value="<?php echo $liststore['store_id']; ?>" <?php if($liststore['store_id']==$taggedstore) { echo 'selected'; } ?>><?php echo $liststore['name']; ?></option>
                    
                  <?php } ?>
                   
                </select>
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>
				  </div>
			</div>
			<div class="col-sm-6" >
					<div class="form-group ">
                <label class="control-label" for="input-meta-title">End Date</label>
                <div class="input-group date_tagged_end" >
                   <input readonly='readonly' type="text" name="filter_tagged_date_end" value="<?php echo $filter_tagged_date_end; ?>" placeholder="" data-date-format="YYYY-MM-DD" id="input-filter_tagged_date_end" class="form-control" />
                 <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span> 
				 
                </div>
            </div>
				  <div class="form-group ">
                <input type="button"  id="tagged_search" style="margin-top: 41px;" value="Search" class="btn btn-primary pull-right" />
            </div>
			
     
           <!--<div class="form-group ">
		    <input type="button" onclick="return submit_tagged_payment();" id="tagged_submit" style="margin-top: 41px;" disabled value="Submit" class="btn btn-primary pull-right" />
                <label class="col-sm-2 control-label" for="input-meta-title">Tagged Value</label>
                <div class="col-sm-10">
                    <input type="text" name="tagged_value" value="" placeholder="Tagged Value" id="tagged_value" class="form-control"  required="required" readonly="readonly" />
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>

	<span id="tagged_order_count"></span>
                </div>
            </div>-->
         </div>
				
              </div>
            </div>
			<div class="table-responsive">
			<?php if(!empty($store_activation_date_tagged)){ ?>
               <span><b>Activation Date :</b> <?php echo $store_activation_date_tagged; ?> </span>
			   
				<?php } ?>
				<br/><br/>
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                <td class="text-left">Store Name</td>
				  <td class="text-left">Date</td>
				  <td class="text-left">Tagged Amount</td>
				  <td class="text-left">Status</td>
                <td class="text-right">Action</td>
				  
                </tr>
              </thead>
              <tbody>
                <?php if($tagged_orders){ $taggeda=1;
					foreach($tagged_orders as $tagged_order)
					{
				?>
						<tr>
							
							<td class="text-left"><?php echo $tagged_order['store_name']; ?>
							<input type="hidden" name="tagged_store" value="<?php echo $tagged_order['store_id'];?>" id="tagged_store_<?php echo $taggeda; ?>" />
							</td>
							<td class="text-left"><?php echo $tagged_order['sale_date']; ?>
							<input type="hidden" name="tagged_date" value="<?php echo $tagged_order['sale_date'];?>" id="tagged_date_<?php echo $taggeda; ?>" />
							</td>
							<td class="text-left"><?php echo $tagged_order['totaltaggedamount'];?>
							<input type="hidden" name="tagged_value" value="<?php echo $tagged_order['totaltaggedamount'];?>" id="tagged_value_<?php echo $taggeda; ?>" />
							</td>
                         <td class="text-left">
								<?php 
                                        if($tagged_order['tagged_payment_status']=="yes")
                                        {
                                            echo " Posted ";
                                        }
										if($tagged_order['tagged_payment_status']=="no")
                                        {
                                            echo " Not Posted ";
                                        }
                                ?>
                         </td>
							<td class="text-left">
								<?php 
                                        
										if($tagged_order['tagged_payment_status']=="no")
                                        {
											if($tagged_order['sale_date']!=(date('Y-m-d')))
											{
                                            ?>
											<input type="button" onclick="return submit_tagged_payment(<?php echo $taggeda; ?>);" id="tagged_submit_<?php echo $taggeda; ?>" value="Submit" class="btn btn-primary pull-right" />
											<?php
											}
                                        }
                                ?>
							
							</td>
							
							
							
						</tr>
				<?php
					$taggeda++;
					}
				}?>
              </tbody>
            </table>
          </div>
			
        </form>
      </div>
    </div>
  </div> </div> </div>
  </div>

<div class="tab-pane <?php if($active_tab=='subsidy'){ ?> active <?php } ?>" id="tab-subsidy_payment">
   <div class="panel-body">
 <div id="content">
  <div class="container-fluid">
    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i>Partner Subsidy Payment</h3>
      </div>
      <div class="panel-body">
        <form action="#" method="post" enctype="multipart/form-data" id="" class="form-horizontal">
	
        <div class="well">
          <div class="row">
		<div class="col-sm-6" >
			<div class="form-group ">
                <label class="control-label" for="input-meta-title">Start Date</label>
                <div class="input-group date_subsidy_start">
                   <input  readonly='readonly' type="text" name="filter_subsidy_date_start" value="<?php echo $filter_subsidy_date_start; ?>" placeholder="" data-date-format="YYYY-MM-DD" id="input-filter_subsidy_date_start" class="form-control" />
                 <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span> 
				 
                </div>
            </div>
			
			<div class="form-group ">
                <label class="control-label" for="input-meta-title">Store</label>
               
                  <select name="subsidy_store" style="width: 100%;" id="input-subsidystore"  class="form-control" >
                  <option  value="">Select Store</option>
			<?php foreach ($store as $liststore) { ?>  
						
                        <option value="<?php echo $liststore['store_id']; ?>" <?php if($liststore['store_id']==$subsidystore) { echo 'selected'; } ?>><?php echo $liststore['name']; ?></option>
                    
                  <?php } ?>
                   
                </select>
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>
				  </div>
			</div>
			<div class="col-sm-6" >
					<div class="form-group ">
                <label class="control-label" for="input-meta-title">End Date</label>
                <div class="input-group date_subsidy_end" >
                   <input readonly='readonly' type="text" name="filter_subsidy_date_end" value="<?php echo $filter_subsidy_date_end; ?>" placeholder="" data-date-format="YYYY-MM-DD" id="input-filter_subsidy_date_end" class="form-control" />
                 <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span> 
				 
                </div>
            </div>
				  <div class="form-group ">
                <input type="button"  id="subsidy_search" style="margin-top: 41px;" value="Search" class="btn btn-primary pull-right" />
            </div>
			
     
           <!--<div class="form-group ">
		    <input type="button" onclick="return submit_tagged_payment();" id="tagged_submit" style="margin-top: 41px;" disabled value="Submit" class="btn btn-primary pull-right" />
                <label class="col-sm-2 control-label" for="input-meta-title">Tagged Value</label>
                <div class="col-sm-10">
                    <input type="text" name="tagged_value" value="" placeholder="Tagged Value" id="tagged_value" class="form-control"  required="required" readonly="readonly" />
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>

	<span id="tagged_order_count"></span>
                </div>
            </div>-->
         </div>
				
              </div>
            </div>
			<div class="table-responsive">
			<?php if(!empty($store_activation_date_subsidy)){ ?>
               <span><b>Activation Date :</b> <?php echo $store_activation_date_subsidy; ?> </span>
			   
				<?php } ?>
				<br/><br/>
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                <td class="text-left">Store Name</td>
				  <td class="text-left">Date</td>
				  <td class="text-left">Subsidy Amount</td>
				  <td class="text-left">Status</td>
                <td class="text-right">Action</td>
				  
                </tr>
              </thead>
              <tbody>
                <?php if($subsidy_orders){ $subsidya=1;
					foreach($subsidy_orders as $subsidy_order)
					{
				?>
						<tr>
							
							<td class="text-left"><?php echo $subsidy_order['store_name']; ?>
							<input type="hidden" name="subsidy_store" value="<?php echo $subsidy_order['store_id'];?>" id="subsidy_store_<?php echo $subsidya; ?>" />
							</td>
							<td class="text-left"><?php echo $subsidy_order['sale_date']; ?>
							<input type="hidden" name="subsidy_date" value="<?php echo $subsidy_order['sale_date'];?>" id="subsidy_date_<?php echo $subsidya; ?>" />
							</td>
							<td class="text-left"><?php echo $subsidy_order['totalsubsidyamount'];?>
							<input type="hidden" name="subsidy_value" value="<?php echo $subsidy_order['totalsubsidyamount'];?>" id="subsidy_value_<?php echo $subsidya; ?>" />
							</td>
                         <td class="text-left">
								<?php 
                                        if($subsidy_order['subsidy_payment_status']=="yes")
                                        {
                                            echo " Posted ";
                                        }
										if($subsidy_order['subsidy_payment_status']=="no")
                                        {
                                            echo " Not Posted ";
                                        }
                                ?>
                         </td>
							<td class="text-left">
								<?php 
                                        
										if($subsidy_order['subsidy_payment_status']=="no")
                                        {
											if($subsidy_order['sale_date']!=(date('Y-m-d')))
											{
                                            ?>
											<input type="button" onclick="return submit_subsidy_payment(<?php echo $subsidya; ?>);" id="subsidy_submit_<?php echo $subsidya; ?>" value="Submit" class="btn btn-primary pull-right" />
											<?php
											}
                                        }
                                ?>
							
							</td>
							
							
							
						</tr>
				<?php
					$subsidya++;
					}
				}?>
              </tbody>
            </table>
          </div>
			
        </form>
      </div>
    </div>
  </div> </div> </div>
  </div>
  
  </div>
 </div>
 <script type="text/javascript">
 $("#input-store").select2();
 $("#input-subsidystore").select2();
 $("#input-taggedstore").select2();
 
 
$('#tagged_search').on('click', function() {
	url = 'index.php?route=partner/bank_payment/payment_form&tab=tagged&token=<?php echo $token; ?>';
	
        var filter_tagged_date_start = $('#input-filter_tagged_date_start').val();
	
	if (filter_tagged_date_start) {
		url += '&filter_tagged_date_start=' + encodeURIComponent(filter_tagged_date_start);
	}
	else
	{
		alertify.error('Please Select Start Date');
		return false;
	}
		
	
        var filter_tagged_date_end = $('#input-filter_tagged_date_end').val();
	if (filter_tagged_date_end) {
		url += '&filter_tagged_date_end=' + encodeURIComponent(filter_tagged_date_end);
	}
	else
	{
		alertify.error('Please Select End Date');
		return false;
	}
        var taggedstore = $('#input-taggedstore').val();
        if (taggedstore) {
		url += '&taggedstore=' + encodeURIComponent(taggedstore);
	}
	else
	{
		alertify.error('Please Select Store');
		return false;
	}
	 	
	location = url;
});
$('#subsidy_search').on('click', function() {
	url = 'index.php?route=partner/bank_payment/payment_form&tab=subsidy&token=<?php echo $token; ?>';
	
        var filter_subsidy_date_start = $('#input-filter_subsidy_date_start').val();
	
	if (filter_subsidy_date_start) {
		url += '&filter_subsidy_date_start=' + encodeURIComponent(filter_subsidy_date_start);
	}
	else
	{
		alertify.error('Please Select Start Date');
		return false;
	}
		
	
        var filter_subsidy_date_end = $('#input-filter_subsidy_date_end').val();
	if (filter_subsidy_date_end) {
		url += '&filter_subsidy_date_end=' + encodeURIComponent(filter_subsidy_date_end);
	}
	else
	{
		alertify.error('Please Select End Date');
		return false;
	}
        var subsidystore = $('#input-subsidystore').val();
        if (subsidystore) {
		url += '&subsidystore=' + encodeURIComponent(subsidystore);
	}
	else
	{
		alertify.error('Please Select Store');
		return false;
	}
	 	
	location = url;
});
</script> 
 
  <script type="text/javascript">
  function submit_tagged_payment(taggedid)
  {
	alertify.confirm('Are you Sure ! You want to processed the Payment ?', function (e) 
	{
    if (e) 
	{   
	url = 'index.php?route=partner/bank_payment/submit_tagged_payment&tab=tagged&token=<?php echo $token; ?>'; 
	var filter_tagged_date=$("#tagged_date_"+taggedid).val();
	if(!filter_tagged_date)
	{
		alertify.error('Error in Tagged Bill Date');
		return false;
	}
	else
	{
		url += '&filter_tagged_date=' + encodeURIComponent(filter_tagged_date);
	}
	var taggedstore=$("#tagged_store_"+taggedid).val();
	if(!taggedstore)
	{
		alertify.error('Error in  Store');
		return false;
	}
	else
	{
		url += '&taggedstore=' + encodeURIComponent(taggedstore);
	}
	var tagged_value=$("#tagged_value_"+taggedid).val();
	if(!tagged_value)
	{
		alertify.error('This amount is not Allowed');
		return false;
	}
	else
	{
		url += '&tagged_value=' + encodeURIComponent(tagged_value);
	}
	
	//////////////
	var filter_tagged_date_start = $('#input-filter_tagged_date_start').val();
	
	if (filter_tagged_date_start) {
		url += '&filter_tagged_date_start=' + encodeURIComponent(filter_tagged_date_start);
	}
	else
	{
		alertify.error('Please Select Start Date');
		return false;
	}
		
	
        var filter_tagged_date_end = $('#input-filter_tagged_date_end').val();
	if (filter_tagged_date_end) {
		url += '&filter_tagged_date_end=' + encodeURIComponent(filter_tagged_date_end);
	}
	else
	{
		alertify.error('Please Select End Date');
		return false;
	}
	alertify.success('Please Wait.Payment is Processing.');
	location = url;
	
    } ///////if of confirm end here
	else 
	{
        alertify.error('Canceled by User');
		return false;
    }
	});
	
  }
  function submit_subsidy_payment(taggedid)
  {
	alertify.confirm('Are you Sure ! You want to processed the Payment ?', function (e) 
	{
    if (e) 
	{   
	url = 'index.php?route=partner/bank_payment/submit_subsidy_payment&tab=subsidy&token=<?php echo $token; ?>'; 
	var filter_subsidy_date=$("#subsidy_date_"+taggedid).val();
	if(!filter_subsidy_date)
	{
		alertify.error('Error in subsidy Bill Date');
		return false;
	}
	else
	{
		url += '&filter_subsidy_date=' + encodeURIComponent(filter_subsidy_date);
	}
	var subsidystore=$("#subsidy_store_"+taggedid).val();
	if(!subsidystore)
	{
		alertify.error('Error in  Store');
		return false;
	}
	else
	{
		url += '&subsidystore=' + encodeURIComponent(subsidystore);
	}
	var subsidy_value=$("#subsidy_value_"+taggedid).val();
	if(!subsidy_value)
	{
		alertify.error('This amount is not Allowed');
		return false;
	}
	else
	{
		url += '&subsidy_value=' + encodeURIComponent(subsidy_value);
	}
	
	//////////////
	var filter_subsidy_date_start = $('#input-filter_subsidy_date_start').val();
	
	if (filter_subsidy_date_start) {
		url += '&filter_subsidy_date_start=' + encodeURIComponent(filter_subsidy_date_start);
	}
	else
	{
		alertify.error('Please Select Start Date');
		return false;
	}
		
	
        var filter_subsidy_date_end = $('#input-filter_subsidy_date_end').val();
	if (filter_subsidy_date_end) {
		url += '&filter_subsidy_date_end=' + encodeURIComponent(filter_subsidy_date_end);
	}
	else
	{
		alertify.error('Please Select End Date');
		return false;
	}
	alertify.success('Please Wait.Payment is Processing.');
	location = url;
	
    } ///////if of confirm end here
	else 
	{
        alertify.error('Canceled by User');
		return false;
    }
	});
	
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
/////////////////
$(".date_tagged_start").datetimepicker({
pickTime: false,
    onSelect: function(dateText) {
      get_amount('tagged');
    }
  }).on("change", function() {
    get_amount('tagged');
  });
  
  $(".date_tagged_end").datetimepicker({
pickTime: false,
    onSelect: function(dateText) {
      get_amount('tagged');
    }
  }).on("change", function() {
    get_amount('tagged');
  });
  
  ///////////////
  
$(".date_subsidy_start").datetimepicker({
pickTime: false,
    onSelect: function(dateText) {
      get_amount('subsidy');
    }
  }).on("change", function() {
    get_amount('subsidy');
  });
  
  $(".date_subsidy_end").datetimepicker({
pickTime: false,
    onSelect: function(dateText) {
      get_amount('subsidy');
    }
  }).on("change", function() {
    get_amount('subsidy');
  });
  
  //////////////////////
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
<script type="text/javascript">
<!--
var maxDate =  new Date();
$('.date').datetimepicker({
	pickTime: false,
	maxDate: maxDate
});
//--></script>
</div>
<?php echo $footer; ?>