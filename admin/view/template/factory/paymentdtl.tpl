<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <form action="" method="post" enctype="multipart/form-data" id="form-return" class="form-horizontal">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button onclick="return issubmit();" type="button" form="form-return" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
</div>
      <h1>Factory Payment</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">Factory Payment</a></li>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i>Payment Detail</h3>
      </div>
      <div class="panel-body">
        
               <div class="form-group ">                 
                <label class="col-sm-2 control-label" for="input-order-id">Company</label>
                <div class="col-sm-10">
                <select name="company" id="company"  required="required" class="form-control" onchange="clear_company(this.value)">
		        <option value="">Select Company</option>
		        <?php foreach ($comopanys as $listcompany) { ?>                   
                        <option value="<?php echo $listcompany['company_id']; ?>" <?php if($listcompany['company_id']==$filter_company) { echo 'selected'; } ?>><?php echo $listcompany['company_name']; ?></option>
                    
                  <?php } ?>
	        </select>
                  <?php if ($error_order_id) { ?>
                  <div class="text-danger"><?php echo $error_order_id; ?></div>
                  <?php } ?>
                </div>
              </div>
            
              <div class="form-group ">                 
                <label class="col-sm-2 control-label" for="input-order-id">Unit</label>
                <div class="col-sm-10">
                <select name="unit"  id="input-unit" required="required" class="form-control">
		        <option value="">Select Unit</option>
		       	        </select>
                  <?php if ($error_order_id) { ?>
                  <div class="text-danger"><?php echo $error_order_id; ?></div>
                  <?php } ?>
                </div>
              </div>             
           <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-bank">Bank</label>
                <div class="col-sm-10">
                    <select name="payment_bank" id="payment_bank"  required="required" class="form-control">
     <option  value="">Select Bank</option>
<option value="ICICI">ICICI</option>
<option value="HDFC">HDFC</option>
      </select>
                  <?php if ($error_email) { ?>
                  <div class="text-danger"><?php echo $error_email; ?></div>
                  <?php  } ?>
                </div>
              </div>
              <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-firstname">Transaction</label>
                <div class="col-sm-10">
                    <select name="transaction_type" id="transaction_type"  required="required" class="form-control">
					<option  value="">Select Transaction</option>
                                        <option value="Credit">Credit</option>
                                        <!---<option value="Debit">Debit</option>
                                        <option value="Wave">Wave Off</option>--->
                                        
				
		   </select>
                  <?php if ($error_firstname) { ?>
                  <div class="text-danger"><?php echo $error_firstname; ?></div>
                  <?php } ?>
                </div>
              </div>
            <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-email">Payment Method</label>
                <div class="col-sm-10">
                    <select name="payment_method" id="payment_method"  required="required" class="form-control">
					<option  value="">Select Payment Method</option>
			 <option value="NEFT">NEFT</option>
			<option value="RTGS">RTGS</option>
			 <option value="IMPS">IMPS</option>
			 
                                        <option value="Cash">Cash</option>
                                       <option value="Cheque">Cheque</option>
                                      
                                        
				
		    </select>
                  <?php if ($error_email) { ?>
                  <div class="text-danger"><?php echo $error_email; ?></div>
                  <?php  } ?>
                </div>
              </div>
              <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-lastname">Enter Amount</label>
                <div class="col-sm-10">
                  <input type="text" name="amount" id="amount" required="required" value="" placeholder="Amount" id="input-product" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57 || event.keyCode==8 || event.keyCode==46" class="form-control" />
                  <?php if ($error_lastname) { ?>
                  <div class="text-danger"><?php echo $error_lastname; ?></div>
                  <?php } ?>
                </div>
              </div>
             <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-lastname">Transaction Number</label>
                <div class="col-sm-10">
                    <input type="text" name="tr_number" id="tr_number" required="required" value="" placeholder="Transaction Number" id="input-tr_number" class="form-control">
                  <?php if ($error_lastname) { ?>
                  <div class="text-danger"><?php echo $error_lastname; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
<label class="col-sm-2 control-label" for="input-date-end">Recieve Date</label>
<div class="col-sm-10">
<div class="input-group date">
<input type="text" name="recieve_date" value="" placeholder="Recieve Date" data-date-format="YYYY-MM-DD" id="recieve_date" class="form-control" />
<span class="input-group-btn">
<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
</span></div>
</div></div>
                                   
          </div>
        
        
     
    </div>
      
  </div></form>
  <script type="text/javascript"> 
    function issubmit()
    {
        var issbmt1='false';
        var company=$("#company").val();
        if(company!='')
        {
            $("#company").focus();
            issbmt1='true';
        }
        else
        {
          issbmt1='false';  
        }
        var unit=$("#input-unit").val();
        if(unit!='')
        {
        issbmt1='true';
        }
        else
        {
          issbmt1='false';  
        }
        var transaction_type=$("#transaction_type").val();
        if(transaction_type!='')
        {
        issbmt1='true';
        }
        else
        {
          issbmt1='false';  
        }
        var payment_method=$("#payment_method").val();
        if(payment_method!='')
        {
        issbmt1='true';
        }
        else
        {
          issbmt1='false';  
        }
        var amount=$("#amount").val();
        if(amount!='')
        {
        issbmt1='true';
        }
        else
        {
          issbmt1='false';  
        }
        
        if(issbmt1=='true')
        {
            $("#form-return").submit();
        }
        else
        {
            alert('please fill all required fields');
                    return false;
            
        }
        
    }
            <!--
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

//--></script> 
 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>