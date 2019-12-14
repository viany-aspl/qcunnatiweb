<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-return" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i>Payout Detail</h3>
      </div>
      <div class="panel-body">
        <form action="" method="post" enctype="multipart/form-data" id="form-return" class="form-horizontal">
        
              <div class="form-group required">
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
                  <select name="transaction_type"   required="required" class="form-control">
					<option  value="">Select Transaction</option>
                                        <option value="Credit Posting">Credit</option>
                                        <!---<option value="Debit">Debit</option>--->
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
                  <select name="payment_method"  required="required" class="form-control">
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
                  <input type="text" name="amount" required="required" value="" placeholder="Enter Amount" id="input-product" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57 || event.keyCode==8 || event.keyCode==46" class="form-control" />
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
                                   
          </div>
        
        </form>
     
    </div>
  </div>
  <script type="text/javascript">
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