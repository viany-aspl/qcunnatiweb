<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
      
     </div>
      <h1>Expense Bill </h1>
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> List</h3>
        <button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download</button>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start">Start date </label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="Date start" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              
              
            </div>
	
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start">End date </label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="Date end" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              
              
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-order-status">Select store</label>
                <select required name="filter_store" id="input-store" style="width: 100%;" class="select2 form-control" onchange="return get_stores_data(this.value);">
                      <option selected="selected" value="">SELECT STORE</option>
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
            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Filter</button>
          </div>
        </div>
          </div>
        
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                   <td class="text-left">TRANS ID</td>
                   <td class="text-left">Exepense date</td>
                   <td class="text-left">Store</td>
	     <td class="text-left">Submit by</td>
	     <td class="text-left">Amount</td>
	     <td class="text-left">Expense type</td>
                   <td class="text-left">Bill Pic</td>
	     <td class="text-left">Remarks</td>
                   <td class="text-left">Status</td>
	     <td class="text-left">Reason for Reject (If any)</td>
                   <td class="text-left">Action</td>
                </tr>
              </thead>
              <tbody>
                <?php if ($bills) { ?>
                <?php foreach ($bills as $bill) { //print_r($bill); ?> 
                <tr>
	    <td class="text-left"><?php echo $bill['SID']; ?></td>
                  
                  <td class="text-left"><?php echo $bill['exepense_date']; ?></td>
                  <td class="text-left"><?php echo $bill['store_name']; ?></td>
	    <td class="text-left"><?php echo $bill['submitby']; ?></td>
                
                  <td class="text-left"><?php echo $bill['amount']; ?></td>
                  <td class="text-left"><?php echo $bill['expensetype']; ?></td>
                  <td class="text-left"><?php if($bill['bill_pic']!="") { ?><a href="../system/upload/expensebill/<?php echo $bill['bill_pic']; ?>" download> View Bill </a> <?php } else { echo "No file Uploaded"; }?></td>
                  <td class="text-left" style="max-width:100px;"><?php echo $bill['remarks']; ?></td>
                  <td class="text-left"><?php if($bill['status']=="3"){echo "Pending";} else if($bill['status']=="4") { echo "Accepted"; } else if($bill['status']=="5") { echo "Rejected"; } ?></td>
                  <td class="text-left" style="max-width: 200px;"><?php echo $bill['reimbursement_message']; ?></td>
	<td class="text-left">
                <?php if($bill['status']=="3"){ ?> 

		<a onclick="return confirm('Are you sure you want to approve this bill ?');" class="btn btn-primary pull-right"  style="background-color: #9DD432;width: 71px;" href="index.php?route=hr/expenseapp/accept_reimbursement&token=<?php echo $token; ?>&bill_id=<?php echo $bill["tr_id"]; ?>">Approve  </a>
<br/>
		<a class="btn btn-primary pull-right" onclick="return regQuery_bill_reject('<?php echo $bill["tr_id"]; ?>','<?php echo $bill["logged_user"]; ?>');" style="background-color: #D44F32;margin-top: 5px;width: 71px;" href="#">Reject</a>
	<?php } ?>

                </td> 
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
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
    
    <!-- Modal -->
  <div class="modal fade" id="myModal_partner" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" id="partner_cncl_btn2" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Tell us the reason, why you reject this bill ? </h4>
        </div>
        <div class="modal-body">
        <form action="index.php?route=hr/expenseapp/reject_reimbursement&token=<?php echo $token; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="bill_id" id="bill_id" />
            
            <input type="hidden" name="logged_user" id="logged_user" />
            
            <div class="form-group">
            <label for="input-username">Reason </label>
            <div class="input-group"><span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <textarea name="reject_Message" required id="reject_Message" class="form-control" placeholder="Reason of reject"></textarea>
            </div>
            </div>
           <div class="text-right">
               <img id="processing_image" src="https://unnati.world/shop/admin/view/image/processing_image.gif" style="height: 50px;width: 50px;display: none;">
               <input type="submit" id="submit_btn"  class="btn btn-primary" onclick="return show_prgressImg();" value="Submit" />
                <button type="button" id="submt_cncl_btn" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </form>
        </div>
        <!--<div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>-->
      </div>
      
    </div>
  </div>
   <script type="text/javascript">
$("#input-store").select2();
function regQuery_bill_reject(bill_id,logged_user)
{
    $('#bill_id').val(bill_id);
    
    $('#logged_user').val(logged_user);
    $('#myModal_partner').modal('show');
    return false;
    
}
function show_prgressImg()
{
    var reject_Message=$('#reject_Message').val();
    if(reject_Message=="")
    {
        return false;
    }
    else
    {
    $("#processing_image").show();
    $("#submit_btn").hide();
    $("#submt_cncl_btn").hide();
    return true;
    }
}
   </script> 
  
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=hr/expenseapp/reimbursement&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	
	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != '') {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	

	
	//alert(url);			
	location = url;
});
//--></script> 
<script type="text/javascript"><!--
$('#button-download').on('click', function() {
	url = 'index.php?route=hr/expenseapp/getlist_download_reimbursement&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	
	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != '') {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	

	//alert(url);			
	//location = url;
         window.open(url, '_blank');
});
//--></script>

  <script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>