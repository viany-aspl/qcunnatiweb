<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      
      <h1>Partner Cash In-Hand adjustment</h1>
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
   
  <div class="tab-content">
  <div class="tab-pane active" id="tab-bank_payment">
  <div class="container-fluid">
    <?php if ($error) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
	
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i>Cash In-Hand Detail</h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $cash_adjustment_form; ?>" method="post" enctype="multipart/form-data" id="form-bank_payment" class="form-horizontal">
                 
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-customer">Store</label>
                <div class="col-sm-10">
                  <select name="store" id="input-store" onchange="return getstoreuser(this.value);" style="width: 100%;" required="required" class="form-control">
			<option  value="">Select Store</option>
			<?php foreach ($store as $liststore) { ?>                   
                        <option value="<?php echo $liststore['store_id']; ?>" <?php if($liststore['store_id']==$filter_store) { echo 'selected'; } ?>><?php echo $liststore['name']; ?></option>
                    
                  <?php } ?>
	        </select>
                </div>
		
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-customer">User</label>
                <div class="col-sm-10">
                  <select name="user" id="input-user" onchange="return getinhandcash(this.value);" style="width: 100%;" required="required" class="form-control">
			<option  value="">Select User</option>
			
	        </select>
                </div>
				<div class="col-sm-2" ></div>
				<div class="col-sm-6" style="float: left;text-align: left;font-weight: bold;color: navy;" id="in_hand_cash_div"></div>
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
                <label class="col-sm-2 control-label" for="input-lastname">Remarks</label>
                <div class="col-sm-10">
                  <textarea name="remarks" id="remarks" required="required" value="" placeholder="remarks"  class="form-control" ></textarea>
                  
                </div>
              </div>
                 <input type="submit"  id="bank_payment_submit" value="Submit" class="btn btn-primary pull-right" />                  
          </div>
        
        </form>
     
    </div>
  </div>
 </div>
 

 </div>
  <script type="text/javascript">
  $("#input-store").select2();
  function getstoreuser(store_id)
  {
	  $("#input-user").html('');  
	  $("#in_hand_cash_div").html('');  
	  $.ajax({
			type: "POST",
			url: "index.php?route=partner/cash_adjustment/getstoreuser&token=<?php echo $token; ?>&store_id="+store_id,
			
			cache: false,
			contentType: false,
			processData: false,
			success: function(data)
			{
				
				$("#input-user").html(data);
			}
	});
	  return false;
  }
  function getinhandcash(user_id)
  {
	  $("#in_hand_cash_div").html('');  
	  if(user_id)
	  {
	  var store_id=$("#input-store").val();
	  $.ajax({
			type: "POST",
			url: "index.php?route=partner/cash_adjustment/getinhandcash&token=<?php echo $token; ?>&store_id="+store_id+"&user_id="+user_id,
			
			cache: false,
			contentType: false,
			processData: false,
			success: function(data)
			{
				$("#in_hand_cash_div").show();
				$("#in_hand_cash_div").html('In-hand Cash : '+data);
			}
	});
	  }
	  return false;
  }
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