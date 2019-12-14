<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
          <img id="processing_image" src="https://unnati.world/shop/admin/view/image/processing_image.gif" style="height: 50px;width: 50px;display: none;" />
        
          <button type="submit" id="submit_button" form="form-user" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1>Cash deposit form</h1>
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
      <?php if ($success) {  ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> Cash deposit</h3>
      </div>
      <div class="panel-body">

        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-user" class="form-horizontal">
            <input type="hidden" name="logged_user" value="<?php echo $logged_user; ?>" />
	
	<div class="form-group required">
            <label class="col-sm-2 control-label" for="input-transaction_number">Deposit Amount</label>
            <div class="col-sm-10">
              <input type="text" required name="deposit_amount" value="" placeholder="Deposit Amount" id="deposit_amount" class="form-control" />
              <?php if ($error_transaction_number) { ?>
              <div class="text-danger"></div>
              <?php } ?>
            </div>
          </div>

           <div class="form-group required">
                <label class="col-sm-2 control-label" style="float: left;" for="input-username">Select bank</label>
            <div class="col-sm-10">
                <div class="input-group col-sm-12">
              
                <select required name="filter_bank" id="filter_bank" class="form-control" >
                      <option selected="selected" value="">SELECT BANK</option>
                   
                  <option value="HDFC">HDFC</option>
                  	        <option value="ICICI">ICICI</option>
	<option value="SBI">SBI</option>
	<option value="TAGGED BILLS">TAGGED BILLS</option>
                
                </select>

                </div>
             </div>
          </div>
	  <div class="form-group required">
                <label class="col-sm-2 control-label" style="float: left;" for="input-username">Branch</label>
            <div class="col-sm-10">
                <div class="input-group col-sm-12">
              
                <input type="text" required name="branch" value="<?php echo $branch; ?>" placeholder="Branch" id="input-branch" class="form-control" />

                </div>
             </div>
          </div>

            <div class="form-group required">
                <label class="col-sm-2 control-label" style="float: left;" for="input-username">Deposit Date</label>
            <div class="col-sm-10">
                <div class="input-group date">
              <input type="text" name="deposit_date" required data-date-format="YYYY-MM-DD"  placeholder="Deposit Date" id="input-deposit_date" class="form-control" />
              <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
                </div>
                  <?php if ($error_deposit_date) { ?>
              
              <div class="text-danger"><?php echo $error_deposit_date; ?></div>
              <?php } ?>
            </div> 
          </div>
       <!--   	
  <div class="form-group required">
                <label class="col-sm-2 control-label" style="float: left;" for="input-username">Deposit by</label>
            <div class="col-sm-10">
                <div class="input-group col-sm-12">
              
                <input type="text" required name="deposit_by"  placeholder="Deposit by" id="input-deposit_by" class="form-control" />

                </div>
             </div>
          </div>-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-transaction_number">Transaction Number</label>
            <div class="col-sm-10">
              <input type="text"  name="transaction_number" value="<?php echo $transaction_number; ?>" placeholder="Transaction Number" id="input-transaction_number" class="form-control" />
              <?php if ($error_transaction_number) { ?>
              <div class="text-danger"><?php echo $error_transaction_number; ?></div>
              <?php } ?>
            </div>
          </div>
            
          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-remarks ">Remarks </label>
            <div class="col-sm-10">
              <textarea name="remarks" rows="5" placeholder="Remarks" id="input-remarks " class="form-control"><?php echo $remarks; ?></textarea>
            </div>
          </div>
            
           
           
        </form>
      </div>
    </div>
  </div>
</div>

//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>
<script type="text/javascript">


function get_stores_data(store_id)
{
        $.ajax({
            url: 'index.php?route=cash/verify/get_store_data&token=<?php echo $token; ?>&store_id=' +  encodeURIComponent(store_id),
            //dataType: 'json',
            success: function(json) 
            {

             document.getElementById("for_current_credit").innerHTML='Current Credit - Rs. '+json; 
            }
        });
    
    return false;
}


</script>
 <script>
    $(document).ready(function(){
    $('#form-user').on('submit', function(e){
        
      var store=$("#input-store").val();
      var filter_trans_type=$("#filter_trans_type").val();
      var deposit_date=$("#input-deposit_date").val();
      var transaction_number=$("#input-transaction_number").val();
      var deposit_amount=$("#deposit_amount").val();
      var branch_code=$("#input-branch_code").val();
      var branch_location=$("#input-branch_location").val();
      
      if((store!="") && (filter_trans_type!="") && (deposit_date!="") && (transaction_number!="") && (deposit_amount!="") && (branch_code!="") && (branch_location!=""))
      {
       $("#submit_button").hide();  
       $("#processing_image").show();
      }
      
    });
});
 </script>
<?php echo $footer; ?> 