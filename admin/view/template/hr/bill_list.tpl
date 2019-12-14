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
                <select required name="filter_store" id="input-store" style="width: 100%;" class="select2 form-control">
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
<div class="col-sm-6">
               <div class="form-group">
                <label class="control-label" for="input-order-status">Select status</label>
                <select required name="filter_status" id="input-status" class="form-control" >
                 <option selected="selected" value="">SELECT STATUS</option>
                 <option <?php if($filter_status=="1") { ?> selected="selected" <?php } ?> value="1">Accepted</option>
                 <option <?php if($filter_status=="0") { ?> selected="selected" <?php } ?> value="0">Pending</option>
                 <option <?php if($filter_status=="2") { ?> selected="selected" <?php } ?> value="2">Rejected</option> 
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
                  <td class="text-left">SID</td>
                  <td class="text-left">Start date</td>
                  <td class="text-left">End date</td>
                  <td class="text-left">Store</td>
	    <td class="text-left">Submit by</td>
                  <td class="text-left">Approved by</td>
                 
                  <td class="text-left">Amount</td>
	     <td class="text-left">Month/Year</td>
	    
                  <td class="text-left">Attched File</td>
                  <td class="text-left">Remarks</td>
                  <td class="text-left">Status</td>
                  <td class="text-left">Reason for Reject (If any)</td>
                </tr>
              </thead>
              <tbody>
                <?php if ($bills) { ?>
                <?php foreach ($bills as $bill) { //print_r($bill); ?> 
                <tr>
	    <td class="text-left"><?php echo $bill['SID']; ?></td>
                  <td class="text-left"><?php echo $bill['start_date']; ?></td>
                  <td class="text-left"><?php echo $bill['end_date']; ?></td>
                  <td class="text-left"><?php echo $bill['store_name']; ?></td>
	    <td class="text-left"><?php echo $bill['submitby']; ?></td>
                  <td class="text-left"><?php echo $bill['approvedby']; ?></td>
                  <td class="text-left"><?php echo $bill['amount']; ?></td>
                  <td class="text-left"><?php echo $bill['filter_month']."/".$bill['filter_year']; ?></td>
                  <td class="text-left"><?php if($bill['uploded_file']!=""){ ?><a href="../system/upload/hrexpensebill/<?php echo $bill['uploded_file']; ?>" download>View</a><?php } else { echo "No Attachment";  }?></td>
                  <td class="text-left"><div style="max-width: 200px;"><?php echo $bill['remarks']; ?></div></td>
                  <td class="text-left"><?php if($bill['status']=="0"){echo "Pending";} else if($bill['status']=="1") { echo "Accepted"; } else if($bill['status']=="2") { echo "Rejected"; } ?></td>
                 
                  <td class="text-left"><div style="max-width: 200px;"><?php echo $bill['reject_message']; ?></div></td>
                
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
    
    
   
  
  <script type="text/javascript">
$("#input-store").select2();
<!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=hr/expenseview&token=<?php echo $token; ?>';
	
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
              var filter_status = $('select[name=\'filter_status\']').val();
	
	if (filter_status != '') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
	
	//alert(url);			
	location = url;
});
//--></script> 
<script type="text/javascript"><!--
$('#button-download').on('click', function() {
	url = 'index.php?route=hr/expenseview/getlist_download&token=<?php echo $token; ?>';
	
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
              var filter_status = $('select[name=\'filter_status\']').val();
	
	if (filter_status != '') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
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