<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-return" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
</div>
      <h1>Expense Balance</h1>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i>Expense Balance Detail</h3>
      </div>
      <div class="panel-body">
        <form action="" method="post" enctype="multipart/form-data" id="form-return" class="form-horizontal">
        
              <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-order-id">Unit</label>
                <div class="col-sm-10">
                <select name="unit"  onchange="return unitstore(this.value);"  required="required" class="form-control">
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
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-customer">Store</label>
                <div class="col-sm-10">
                  <select name="store" id="input-store" required="required" class="form-control" style="width:100%">
			<option  value="">Select Store</option>
			<?php foreach ($store as $liststore) { ?>                   
                        <option value="<?php echo $liststore['store_id']; ?>" <?php if($liststore['store_id']==$filter_store) { echo 'selected'; } ?>><?php echo $liststore['name']; ?></option>
                    
                  <?php } ?>
	        </select>
                </div>
              </div>
              <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-firstname">Transaction Number</label>
                <div class="col-sm-10">
                  <input type="text" name="transaction_no" required="required" value="" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57 || event.keyCode==8 || event.keyCode==46" placeholder="Enter transaction Number" id="input-product"  class="form-control" />
                  <?php if ($error_firstname) { ?>
                  <div class="text-danger"><?php echo $error_firstname; ?></div>
                  <?php } ?>
                </div>
              </div>
            <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-email">Payment Method</label>
                <div class="col-sm-10">
                  <select name="payment_method"  required="required" class="form-control">
					<option  value="">Select Payment Method</option>
                                        <option value="cash">Cash</option>
                                       <option value="cheque">Cheque</option>
                                      
                                        
				
		    </select>
                  <?php if ($error_email) { ?>
                  <div class="text-danger"><?php echo $error_email; ?></div>
                  <?php  } ?>
                </div>
              </div>
              <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-lastname">Amount</label>
                <div class="col-sm-10">
                  <input type="text" name="amount" required="required" value="" placeholder="Enter Amount" id="input-product" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57 || event.keyCode==8 || event.keyCode==46" class="form-control" />
                  <?php if ($error_lastname) { ?>
                  <div class="text-danger"><?php echo $error_lastname; ?></div>
                  <?php } ?>
                </div>
              </div>
                    
             <strong class="col-sm-2 control-label" for="input-lastname">Account Information</strong>
             <br/><br/>
              <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-email">Bank</label>
                <div class="col-sm-10">
                  <select name="bank_name"  required="required" class="form-control">
					<option  value="">Select Bank </option>
                                        <option value="icici">ICICI</option>
                                       <option name="hdfc">HDFC</option>.
		          </select>
                  <?php if ($error_email) { ?>
                  <div class="text-danger"><?php echo $error_email; ?></div>
                  <?php  } ?>
                </div>
            </div>
         <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-lastname">Account No</label>
                <div class="col-sm-10">
                  <input type="text" name="accountno" required="required" value="" placeholder="Enter Account No" id="input-product" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57 || event.keyCode==8 || event.keyCode==46" class="form-control" />
                  <?php if ($error_lastname) { ?>
                  <div class="text-danger"><?php echo $error_lastname; ?></div>
                  <?php } ?>
                </div>
              </div>
              
            
	     <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-lastname"> IFSC</label>
                <div class="col-sm-10">
                  <input type="text" name="ifsc" required="required" value="" placeholder="Enter IFSC Code" id="input-product" class="form-control" />
                  <?php if ($error_lastname) { ?>
                  <div class="text-danger"><?php echo $error_lastname; ?></div>
                  <?php } ?>
                </div>
              </div>   
              <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-lastname">Account Name</label>
                <div class="col-sm-10">
                  <input type="text" name="account_name" required="required" value="" placeholder="Enter Account Name" id="input-product" class="form-control" />
                  <?php if ($error_lastname) { ?>
                  <div class="text-danger"><?php echo $error_lastname; ?></div>
                  <?php } ?>
                </div>
              </div>  
          </div>
                                   
          </div>
		    
        </form>
     
    </div>
  </div>
  <script type="text/javascript">
$("#input-store").select2();
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
// alert(data);

//$('#input-store').html(data);
}
});
}
</script>
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>