<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
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
               
                 
               
                      
                  <select name="filter_user" id="filter_user" class="form-control">
                   <option selected="selected" value="">SELECT CE</option>
	
	<?php foreach($runner_list as $runner){ ?>
		<option value="<?php echo $runner['user_id']; ?>" <?php if($filter_user==$runner['user_id']){ ?> selected="selected" <?php } ?> ><?php echo $runner['firstname']." ".$runner['lastname']; ?></option>
	<?php } ?>
	
                </select>
                
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
              

	 <div class="form-group">
                <label class="control-label" >Status</label>
               
                 
               
                      
                  <select name="filter_status" id="filter_status" class="form-control">
                   <option selected="selected" value="">ALL</option>
	
	
		<option value="1" <?php if($filter_user=='1'){ ?> selected="selected" <?php } ?> >Deposit by Runner</option>
		<option value="3" <?php if($filter_user=='3'){ ?> selected="selected" <?php } ?> >Accepted</option>
		<option value="2" <?php if($filter_user=='2'){ ?> selected="selected" <?php } ?> >Rejected</option>
	
                </select>
                
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
	<td class="text-left">Store</td>
              <td class="text-left">Runner</td>
	<!---<td class="text-left">Trans ID</td>-->
	
                <td class="text-left">Bank</td>
	<td class="text-left">Deposit Amount</td>
              
                <td class="text-left">Deposit Date</td>
               
	  <td class="text-left">Attcahed Slip</td>
               	
	
	 <td class="text-left">Status</td>
	 <td class="text-left">Action</td>
              </tr>
            </thead>
            <tbody>
              <?php if ($orders) { $total=0; if($_GET["page"]=="1"){ $aa=1; }elseif($_GET["page"]==""){$aa=1;} else { $aa=@$_GET["page"]+20-1; } ?>
              <?php foreach ($orders as $order) { ?>
                
              <tr>
                	<td class="text-left"><?php echo $aa; ?></td>
                
	<td class="text-left"><?php echo $order['store_name']; ?></td>
  	<td class="text-left"><?php echo $order['filled_user']; ?></td>
	<td class="text-left"><?php echo $order['bank']; ?></td>
                <td class="text-left"><?php echo $order['amount']; ?></td>
	<td class="text-left"><?php echo $order['submit_date']; ?></td>
	<td class="text-left"><?php if($order['uploded_file']!=""){ ?><a href="../system/upload/cashslips/<?php echo $order['uploded_file']; ?>" download>View</a><?php } ?></td>
             
	
	<td class="text-left"><?php 
		if($order['status']=="0") 
		{ 
			echo "<spna style='color: #C59420;'>Pending at Runner</span>"; 
		} 

		else if($order['status']=="1") 
		{ 
			echo "<spna style='color: orange;'>Deposit by Runner</span>"; 

		} 
		else if($order['status']=="3") 
		{ 
			echo "<spna style='color: green;'>Accepted</span>"; 

		} 
		else if($order['status']=="2") 
		{ 
			echo "<spna style='color: red;'>Rejected</span>"; 
		} 

		?></td>
               <td class="text-right"><?php if($order['status']=="1"){ ?><a class="btn btn-primary" onclick="return confirm('Are you sure ? You want to confirm the amount !');" href="index.php?route=cash/verify/accept_cash_by_account&token=<?php echo $token; ?>&tr_id=<?php echo $order['tr_id']; ?>&logged_user=<?php echo $logged_user; ?>&amount=<?php echo $order['amount']; ?>&runner_id=<?php echo $order['runner_id']; ?>">Verify</a> 
                       <br/>  <br/>
                        <a class="btn btn-primary" style="width: 61px;background-color: #B36330;" onclick="return confirm('Are you sure ? You want to reject the amount !');" href="index.php?route=cash/verify/reject_cash_by_account&token=<?php echo $token; ?>&tr_id=<?php echo $order['tr_id']; ?>&logged_user=<?php echo $logged_user; ?>">Reject</a> <?php } else {  } ?></td>
             
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
	var filter_status = $('#filter_status').val();
	
	if (filter_status) {
		url += '&filter_status=' + encodeURIComponent(filter_status);
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
	var filter_status = $('#filter_status').val();
	
	if (filter_status) {
		url += '&filter_status=' + encodeURIComponent(filter_status);
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