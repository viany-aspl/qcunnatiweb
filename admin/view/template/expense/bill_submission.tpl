<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
          <img id="processing_image" src="https://unnati.world/shop/admin/view/image/processing_image.gif" style="height: 50px;width: 50px;display: none;" />
        <button type="submit" form="form-user" id="submit_button" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1>Expense Bill Submission</h1>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> Expense Bill Submission</h3>
      </div>
      <div class="panel-body">

        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-user" class="form-horizontal">
            <input type="hidden" name="logged_user" value="<?php echo $logged_user; ?>" />
           
            <div class="form-group required">
                <label class="col-sm-2 control-label" style="float: left;" for="input-username">Select Center</label>
            <div class="col-sm-10">
                <div class="input-group col-sm-12">
              
                <select required name="filter_store" id="input-store" class="form-control" onchange="return get_stores_data(this.value);">
                      <option selected="selected" value="">SELECT CENTER</option>
                  <?php foreach ($stores as $store) {   ?>
                  <?php if ($store['store_id'] == $filter_store) {
                      if($filter_store!=""){
                      ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                      <?php }} else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>

                </div>

             </div>
          </div>
         <div class="form-group required">
                <label class="col-sm-2 control-label" style="float: left;" for="input-username">Select reason</label>
            <div class="col-sm-10">
                <div class="input-group col-sm-12">
                <select required name="reason" id="input-reason" class="form-control" >
                      <option selected="selected" value="">SELECT REASON</option>
                  <?php foreach ($reasons as $reason) {   ?>
                  <option value="<?php echo $reason['sid']; ?>" ><?php echo $reason['reason']; ?></option>
                  <?php } ?>
                </select>
                </div>

             </div>
          </div>
           <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-transaction_number">Employee name</label>
            <div class="col-sm-10">
              <input type="text" required name="employee_name"  placeholder="Employee name" id="input-employee_name" class="form-control" />
              <input type="hidden" required name="employee_id"  placeholder="Employee ID" id="input-employee_id" class="form-control" /> 
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" style="float: left;" for="input-username">Exepense date </label>
            <div class="col-sm-10">
                <div class="input-group date">
              <input type="text" name="exepense_date" required data-date-format="YYYY-MM-DD"  placeholder="Exepense date " id="input-exepense_date" class="form-control" />
              <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
                </div>
            </div>
          </div>
          
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-transaction_number">Amount</label>
            <div class="col-sm-10">
              <input type="text" required name="amount"  placeholder="Amount" id="input-amount" class="form-control" />
            </div>
          </div>
          <div class="form-group required" > 
            <label class="col-sm-2 control-label" for="input-transaction_number">Photo of bill</label>
            <div class="col-sm-10">
              <input type="file" required name="file" style="padding: 0px;" id="input-amount" class="form-control" />
            </div>
          </div>
         
            
        </form>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function(){
    $('#form-user').on('submit', function(e){
        
      var store=$("#input-store").val();
      var employee_name=$("#input-employee_name").val();
      var employee_id=$("#input-employee_id").val();
      var exepense_date=$("#input-exepense_date").val();
      var amount=$("#input-amount").val();
      var reason=$("#input-reason ").val();
      if((store!="") && (employee_id!="") && (exepense_date!="") && (amount!="") && (reason!=""))
      {
        $("#submit_button").hide();  
        $("#processing_image").show();
        return true;
      }
      else
      {
        return false;
      }
      
    });
});
 </script> 
  <script type="text/javascript"><!--
$('input[name=\'employee_name\']').autocomplete({
	'source': function(request, response) {
                            if(request!="")
                            {
		$.ajax({
			url: 'index.php?route=expense/expense/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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
                          }
	},
	'select': function(item) {
		$('input[name=\'employee_name\']').val(item['label']);
                            $('input[name=\'employee_id\']').val(item['value']);
	}	
});

$('input[name=\'filter_email\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_email=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['email'],
						value: item['customer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_email\']').val(item['label']);
	}	
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?> 