<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo "Amount With Account"; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
      <?php if ($success) {  ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo $text_list; ?></h3>
        <button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download</button>
      </div>
        
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              		 <div class="form-group">
                <label class="control-label" >Select CE</label>
                <div class="input-group">
                 
                  <span class="input-group-btn">
                      
                  <select name="filter_user" id="filter_user" class="form-control">
                   <option selected="selected" value="">SELECT CE</option>
                  <option value="110" <?php if($filter_user=="110"){ ?> selected="selected" <?php } ?> >Amit Kumar</option>
                  <option value="111" <?php if($filter_user=="111"){ ?> selected="selected" <?php } ?> >Anil Kumar</option>
                  <option value="106" <?php if($filter_user=="106"){ ?> selected="selected" <?php } ?> >Chitranjan Mishra</option>   
                  <option value="77" <?php if($filter_user=="77"){ ?> selected="selected" <?php } ?> >Kunwar Rana</option>
                  <option value="9" <?php if($filter_user=="9"){ ?> selected="selected" <?php } ?> >Om prakash</option>
                  <option value="108" <?php if($filter_user=="108"){ ?> selected="selected" <?php } ?> >Surjeet Mishra</option>
                  
                  
                  
                </select>
                  </span></div>
              </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <span style="font-weight: bold;"> Total Amount : <?php echo $total_amount; ?></span> <br/> <br/>
          <table class="table table-bordered">
            <thead>
              <tr>
	<td class="text-left">SID</td>
	<td class="text-left">Deposit Amount</td>
                <td class="text-right">Bank</td>
                <td class="text-right">Branch</td>
                <td class="text-left">Deposit Date</td>
                <td class="text-left">Deposit by</td>
	  <td class="text-left">Attcahed Slip</td>
               	<!--<td class="text-left">Filled by</td>-->
	 <td class="text-left">Transaction Number</td>
                <td class="text-left">Remarks</td>
	 <td class="text-left">Status</td>
	 <td class="text-left">Action</td>
              </tr>
            </thead>
            <tbody>
              <?php if ($orders) { $total=0; if($_GET["page"]=="1"){ $aa=1; }elseif($_GET["page"]==""){$aa=1;} else { $aa=@$_GET["page"]+20-1; } ?>
              <?php foreach ($orders as $order) { ?>
                
              <tr>
                	<td class="text-left"><?php echo $aa; ?></td>
                
                <td class="text-left"><?php echo $order['amount']; ?></td>
	<td class="text-left"><?php echo $order['bank']; ?></td>
                <td class="text-right"><?php echo $order['branch']; ?></td>
                <!--<td class="text-right"><?php echo $order['deposit_date']; ?></td>
                <td class="text-right"><?php echo $order['deposited_by']; ?></td>-->
                <td class="text-left"><?php echo $order['submit_date']; ?></td>
                <td class="text-left"><?php echo $order['filled_user']; ?></td>
                 <td class="text-left"><?php if($order['uploded_file']!=""){ ?><a href="../system/upload/cashbankslip/<?php echo $order['uploded_file']; ?>" download>View</a><?php } ?></td>
                <td class="text-left"><?php echo $order['transaction_number']; ?></td>
	<td class="text-left"><?php echo $order['remarks']; ?></td>
	<td class="text-left"><?php if($order['status']=="0") { echo "<spna style='color: #C59420;'>Pending</span>"; } else if($order['status']=="1") { echo "<spna style='color: green;'>Accepted</span>"; } else if($order['status']=="2") { echo "<spna style='color: red;'>Rejected</span>"; } ?></td>
               <td class="text-right"><?php if($order['status']=="0"){ ?><a class="btn btn-primary" onclick="return confirm('Are you sure ? You want to confirm the amount !');" href="index.php?route=cash/verify/accept_cash&token=<?php echo $token; ?>&tr_id=<?php echo $order['tr_id']; ?>&logged_user=<?php echo $logged_user; ?>&amount=<?php echo $order['amount']; ?>&runner_id=<?php echo $order['runner_id']; ?>">Verify</a> 
                       <br/>  <br/>
                        <a class="btn btn-primary" style="width: 61px;background-color: #B36330;" onclick="return confirm('Are you sure ? You want to reject the amount !');" href="index.php?route=cash/verify/reject_cash&token=<?php echo $token; ?>&tr_id=<?php echo $order['tr_id']; ?>&logged_user=<?php echo $logged_user; ?>">Reject</a> <?php } else {  } ?></td>
             
              </tr>
              <?php $total=$total+$order['amount'];
              		$aa++;
              } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"> 
              <span style="font-weight: bold;">Page Total Amount : <?php echo $total; ?></span> <br/>
              <?php echo $results; ?>  </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=cash/verify/verify_runner_deposit&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_store = $('#filter_store').val();
	
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
              var filter_user = $('#filter_user').val();
	
	if (filter_user) {
		url += '&filter_user=' + encodeURIComponent(filter_user);
	}
	location = url;
});
//--></script> 
    <script type="text/javascript"><!--
$('#button-download').on('click', function() {
	url = 'index.php?route=cash/verify/verify_runner_deposit_download&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_store = $('#filter_store').val();
	
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
              var filter_user = $('#filter_user').val();
	
	if (filter_user) {
		url += '&filter_user=' + encodeURIComponent(filter_user);
	}
        window.open(url, '_blank');
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>