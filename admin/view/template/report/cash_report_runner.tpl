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
              	<!--<div class="form-group">
                <label class="control-label" for="input-date-end">Select Store</label>
                <div class="input-group">
                 
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
               <div class="form-group">
                <label class="control-label" >Select Status</label>
                <div class="input-group">
                 
                  <span class="input-group-btn">
                      
                  <select name="filter_status" id="filter_status" class="form-control">
                   <option selected="selected" value="">SELECT STATUS</option>
                 
                  <option value="1" <?php if($filter_status=="1"){ ?> selected="selected" <?php } ?> >Accepted</option>
                  <option value="0" <?php if($filter_status=="0"){ ?> selected="selected" <?php } ?> >Pending</option>
                  <option value="2" <?php if($filter_status=="2"){ ?> selected="selected" <?php } ?> >Rejected</option>   
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
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">SI ID </td>
                <td class="text-left">Runner name</td>
               
                <td class="text-left">Bank</td>
	 <td class="text-left">Branch</td>
                <td class="text-left">Deposit date </td>
                <td class="text-left">Amount </td>
	<td class="text-right">Status</td>
	
              </tr>
            </thead>
            <tbody>
              <?php if ($orders) { $total=0; ?>
              <?php foreach ($orders as $order) { ?>
              <tr>
                <td class="text-left"><?php echo $order['SIID']; ?></td>
                <td class="text-left"><?php echo $order['runner_name']; ?></td>
                <td class="text-left"><?php echo $order['bank']; ?></td>
                <td class="text-left"><?php echo $order['branch']; ?></td>
                <td class="text-left"><?php echo $order['deposit_date']; ?></td>
                <td class="text-left"><?php echo $order['amount']; ?></td>
	<td class="text-right"><?php if($order['status']=="0") { echo "<span style='color: #CC760F;'>Pending</span>"; } else if($order['status']=="1") { echo "<span style='color: #2F9217;'>Accepted</span>"; } else if($order['status']=="2") { echo "<span style='color: #C0250C;'>Rejected</span>"; } ?></td>
	
              </tr>
              <?php $total=$total+$order['amount'];
              
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
              <span style="font-weight: bold;">Total Amount : <?php echo $total; ?></span> <br/>
              <?php echo $results; ?>  </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/cash_report/runner&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
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
    url = 'index.php?route=report/cash_report/download_excel_runner&token=<?php echo $token; ?>';
    
    var filter_date_start = $('input[name=\'filter_date_start\']').val();
    
    if (filter_date_start) {
        url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
    }

    var filter_date_end = $('input[name=\'filter_date_end\']').val();
    
    if (filter_date_end) {
        url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
    }
        
   var filter_user = $('#filter_user').val();
	
	if (filter_user) {
		url += '&filter_user=' + encodeURIComponent(filter_user);
	}
             var filter_status = $('#filter_status').val();
	
	if (filter_status) {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
    //location = url;
        window.open(url, '_blank');
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>