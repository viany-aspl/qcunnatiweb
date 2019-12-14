<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-return" id="submit_btn" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
</div>
      <h1>Credit Posting</h1>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i>Posting Detail</h3>
      </div>
      <div class="panel-body">
        <form action=""  method="post" enctype="multipart/form-data" id="form-creditposting" class="form-horizontal">
        
                          
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-customer">Supplier</label>
                <div class="col-sm-10">
                  <select name="supplier" id="input-supplier" required="required" class="form-control">
			<option  value="">Select Supplier</option>
			<?php foreach ($suppliers as $supplier) { ?>
                  <?php if ($supplier['id'] == $filter_supplier) { ?>
                  <option value="<?php echo $supplier['id']; ?>" selected="selected"><?php echo $supplier['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $supplier['id']; ?>"><?php echo $supplier['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
	        </select>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-firstname">Transaction</label>
                <div class="col-sm-10">
                  <select name="transaction_type" id="transaction_type" required="required" class="form-control">
					<option  value="">Select Transaction</option>
                                        <option value="Credit Posting">Credit</option>
                                        <option value="Debit">Debit</option>
                                        <!---<option value="Waiver Subsidy">Waiver Subsidy</option>--->
                                        
				
		   </select>
                  <?php if ($error_firstname) { ?>
                  <div class="text-danger"><?php echo $error_firstname; ?></div>
                  <?php } ?>
                </div>
              </div>
	   <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-firstname">Bank</label>
                <div class="col-sm-10">
                  <select name="payment_bank" id="payment_bank"  required="required" class="form-control">
					<option  value="">Select Bank</option>
                                        <option value="ICICI">ICICI</option>
                                        <option value="HDFC">HDFC</option>
                                        <option value="CREDIT NOTE">CREDIT NOTE</option>
                                        
				
		   </select>
                  <?php if ($error_firstname) { ?>
                  <div class="text-danger"><?php echo $error_firstname; ?></div>
                  <?php } ?>
                </div>
              </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-email">Payment Method</label>
                <div class="col-sm-10">
                  <select name="payment_method"  required="required" id="payment_method" class="form-control" onchange="return show_hide_date(this.value);" >
					<option  value="">Select Payment Method</option>
                                        <option value="ECMS">ECMS</option>
                                       
                                      <option value="NEFT">NEFT</option>
                                        <option value="RTGS">RTGS</option>
			<option value="IMPS">IMPS</option>	
			<option value="CREDIT NOTE">CREDIT NOTE</option>	
		    </select>
                  <?php if ($error_email) { ?>
                  <div class="text-danger"><?php echo $error_email; ?></div>
                  <?php  } ?>
                </div>
              </div>
			  <div class="form-group" id="date_div" style="display: none;">
                <label class="col-sm-2 control-label" for="input-lastname">Invoice Date</label>
                <div class="col-sm-10">
                  
                 <div class="input-group date" id="date_from">
                  <input type="text" name="invoice_date" id="invoice_date" placeholder="Invoice Date" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Enter Amount</label>
                <div class="col-sm-10">
                  <input type="text" name="amount" required="required" value="" placeholder="Enter Amount" id="amount" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57 || event.keyCode==8 || event.keyCode==46" class="form-control" />
                  <?php if ($error_lastname) { ?>
                  <div class="text-danger"><?php echo $error_lastname; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required ">
                <label class="col-sm-2 control-label" for="input-lastname">Transaction Number</label>
                <div class="col-sm-10">
                  <input type="text" name="tr_number" required="required" value="" placeholder="Transaction Number" id="input-tr_number" class="form-control" />
                  <?php if ($error_lastname) { ?>
                  <div class="text-danger"><?php echo $error_lastname; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-lastname">Snapshot of the Payment</label>
                <div class="col-sm-10">
                  <input type="file" name="snapshot" id="input-snapshot" class="form-control" />
                  <?php if ($error_snapshot) { ?>
                  <div class="text-danger"><?php echo $error_snapshot; ?></div>
                  <?php } ?>
                </div>
              </div>  
            <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-lastname">Remarks</label>
                <div class="col-sm-10">
                    <textarea name="remarks"  placeholder="Remarks" id="input-remarks" class="form-control" ></textarea>
                  <?php if ($error_lastname) { ?>
                  <div class="text-danger"><?php echo $error_lastname; ?></div>
                  <?php } ?>
                </div>
              </div>
          </div>
        
        </form>
     
    </div>
  </div>
  <script type="text/javascript">
  $('#form-creditposting').submit(function(e)
      {
        //e.preventDefault();
		var supplier=$("#input-supplier").val();
		
		if(!supplier)
		{
			alertify.error('Please Select Supplier');
			$("#input-supplier").focus();
			return false;
		}
		var transaction_type=$("#transaction_type").val();
		
		if(!transaction_type)
		{
			alertify.error('Please Select transaction type');
			$("#transaction_type").focus();
			return false;
		}
		var payment_bank=$("#payment_bank").val();
		
		if(!payment_bank)
		{
			alertify.error('Please Select payment bank');
			$("#payment_bank").focus();
			return false;
		}
		var payment_method=$("#payment_method").val();
		
		if(!payment_method)
		{
			alertify.error('Please Select payment method');
			$("#payment_method").focus();
			return false;
		}
		var invoice_date=$("#invoice_date").val();
		
		if(!invoice_date)
		{
			//alertify.error('Please Select invoice date');
			//$("#invoice_date").focus();
			//return false;
		}
		var amount=$("#amount").val();
		
		if(!amount)
		{
			alertify.error('Please Enter amount');
			$("#amount").focus();
			return false;
		}
		var tr_number=$("#input-tr_number").val();
		
		if(!tr_number)
		{
			alertify.error('Please Enter transaction number');
			$("#input-tr_number").focus();
			return false;
		}
        $("#submit_btn").hide();
        return true;
      });
function show_hide_date(pay_method)
{
	//alert(pay_method);
	if(pay_method=='CREDIT NOTE')
	{
		$("#date_div").show();
	}
	else
	{
		$("#date_div").hide();
	}
	return false;
}
</script>
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>