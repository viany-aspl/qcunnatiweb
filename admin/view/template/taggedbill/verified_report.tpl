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
        <!--<button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download</button>-->
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
              		<!--  <div class="form-group">
                <label class="control-label" for="input-date-end">Select Store</label>
                <div class="input-group ">
                 
                  <span class="input-group-btn">
                      
                  <select name="filter_store" id="filter_store" class="form-control">
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
                  </span></div>
              </div>-->
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
          <!--<span style="font-weight: bold;"> Total Amount : <?php echo $total_amount; ?></span> <br/> <br/>-->
          <table class="table table-bordered">
            <thead>
              <tr>
	<td class="text-left">SID</td>
	<td class="text-left">Deposit Amount</td>
                <td class="text-left">Store name</td>
                <td class="text-left">Unit</td>
                <td class="text-left">Bill date</td>
                <td class="text-left">Created by</td>
	
	 <td class="text-left">Created date</td>
               
	 <td class="text-left">Status</td>
	 <td class="text-left">Action</td>
              </tr>
            </thead>
            <tbody>
              <?php if ($orders) { $total=0; if($_GET["page"]=="1"){ $aa=1; }elseif($_GET["page"]==""){$aa=1;} else { $aa=@$_GET["page"]+20-1; } ?>
              <?php foreach ($orders as $order) { ?>
                
              <tr>
                	<td class="text-left"><?php echo $order['bill_id']; ?></td>
                
                <td class="text-left"><?php echo number_format((float)$order['total_amount'], 2, '.', ''); ?></td>
	  <td class="text-left"><?php echo $order['store_name']; ?></td>
                <td class="text-left"><?php echo $order['unit']; ?></td>
                <td class="text-left"><?php echo $order['bill_date']; ?></td>
                <td class="text-left"><?php echo $order['filled_user']; ?></td>
                <td class="text-left"><?php echo $order['create_date']; ?></td>  
	
	<td class="text-left"><?php if($order['status']=="0") { echo "Pending"; } else if($order['status']=="1") { echo "Accepted"; } else if($order['status']=="2") { echo "Rejected"; } ?></td>
               <td class="text-left"><?php if($order['status']=="0"){ ?><a class="btn btn-primary" onclick="return confirm('Are you sure ? You want to confirm the bill !');" href="index.php?route=taggedbill/bill/accept_cash&token=<?php echo $token; ?>&bill_id=<?php echo $order['bill_id']; ?>&logged_user=<?php echo $logged_user; ?>&amount=<?php echo $order['total_amount']; ?>&created_user=<?php echo $order['created_user']; ?>">Verify</a> 
                       <br/>  <br/>
                        <a class="btn btn-primary" style="width: 61px;background-color: #B36330;" onclick="return confirm('Are you sure ? You want to reject the bill !');" href="index.php?route=taggedbill/bill/reject_cash&token=<?php echo $token; ?>&bill_id=<?php echo $order['bill_id']; ?>&logged_user=<?php echo $logged_user; ?>">Reject</a> <?php } else {  } ?></td>
             
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
              <!--<span style="font-weight: bold;">Page Total Amount : <?php echo $total; ?></span> <br/>-->
              <?php echo $results; ?>  </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=taggedbill/bill&token=<?php echo $token; ?>';
	
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
	location = url;
});
//--></script> 
    <script type="text/javascript"><!--
$('#button-download').on('click', function() {
	url = 'index.php?route=taggedbill/bill/download_excel&token=<?php echo $token; ?>';
	
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
        window.open(url, '_blank');
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>