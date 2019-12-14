<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Store's target</h1>
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
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i>Target list</h3>
        <!--<button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download</button>-->
      </div>
        
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end">Select Store</label>
                <div class="input-group date">
                  
                  <span class="input-group-btn">
                      
                  <select name="filter_store" id="filter_store" class="form-control">
                   <option selected="selected" value="">SELECT STORE</option>
                  <?php foreach ($stores as $store) {  ?>
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
              </div>
              
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                <label class="control-label" for="input-date-end">Select month</label>
                <div class="input-group date">
                  
                  <span class="input-group-btn">
                      
                  <select name="filter_month" id="filter_month" class="form-control">
                   <option selected="selected" value="">SELECT MONTH</option>  
                  <option value="1" <?php if($filter_month=='1'){ echo 'selected="selected"'; }?> >January</option>
                  <option value="2" <?php if($filter_month=='2'){ echo 'selected="selected"'; }?> >February</option>
                  <option value="3" <?php if($filter_month=='3'){ echo 'selected="selected"'; }?> >March</option>
                  <option value="4" <?php if($filter_month=='4'){ echo 'selected="selected"'; }?> >April</option>
                  <option value="5" <?php if($filter_month=='5'){ echo 'selected="selected"'; }?> >May</option>
                  <option value="6" <?php if($filter_month=='6'){ echo 'selected="selected"'; }?> >June</option>
                  <option value="7" <?php if($filter_month=='7'){ echo 'selected="selected"'; }?> >July</option>
                  <option value="8" <?php if($filter_month=='8'){ echo 'selected="selected"'; }?> >August</option>
                  <option value="9" <?php if($filter_month=='9'){ echo 'selected="selected"'; }?> >September</option>
                  <option value="10" <?php if($filter_month=='10'){ echo 'selected="selected"'; }?> >October</option>
                  <option value="11" <?php if($filter_month=='11'){ echo 'selected="selected"'; }?> >November</option>
                  <option value="12" <?php if($filter_month=='12'){ echo 'selected="selected"'; }?> >December</option>
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
                <td class="text-left">Store Name </td>
                <td class="text-left">Fertilizer </td>
                <td class="text-left">Crop Protection </td>
                <td class="text-left">Crop Care </td>
                <td class="text-left">Seeds </td>
                <td class="text-left">Totals </td>
                <td class="text-left">Target Month-Year </td>
                <td class="text-left">Remarks </td>
              </tr>
            </thead>
            <tbody>
              <?php if ($orders) { $total=0;$month_v="";$total_1=0; ?>
              <?php foreach ($orders as $order) { 
                  
                  if($order['month']=="1"){ $month_v="January"; }
                  if($order['month']=="2"){ $month_v="February"; }
                  if($order['month']=="3"){ $month_v="March"; }
                  if($order['month']=="4"){ $month_v="April"; }
                  if($order['month']=="5"){ $month_v="May"; }
                  if($order['month']=="6"){ $month_v="June"; }
                  if($order['month']=="7"){ $month_v="July"; }
                  if($order['month']=="8"){ $month_v="August"; }
                  if($order['month']=="9"){ $month_v="September"; }
                  if($order['month']=="10"){ $month_v="October"; }
                  if($order['month']=="11"){ $month_v="November"; }
                  if($order['month']=="12"){ $month_v="December"; }
                  $total_1=$order['Fertilizer']+$order['Crop_Protection']+$order['Crop_Care']+$order['Seeds'];
                  ?>
                
              <tr>
                <td class="text-left"><?php echo $order['store_name']; ?></td>
                <td class="text-left"><?php echo $order['Fertilizer']; ?></td>
                <td class="text-left"><?php echo $order['Crop_Protection']; ?></td>
                <td class="text-left"><?php echo $order['Crop_Care']; ?></td>
                <td class="text-left"><?php echo $order['Seeds']; ?></td>
                <td class="text-left"><?php echo $total_1; ?></td>
                <td class="text-left"><?php echo $month_v." - ".$order['year']; ?></td>
                <td class="text-left" style='max-width: 200px;'><?php echo $order['Remarks']; ?></td>
              </tr>
              <?php $total=$total+$total_1;
              
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
              <span style="font-weight: bold;">Total Target : <?php echo $total; ?></span> <br/>
              <?php echo $results; ?>  </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=target/target/view&token=<?php echo $token; ?>';
	
	var filter_month = $('#filter_month').val();
	
	if (filter_month) {
		url += '&filter_month=' + encodeURIComponent(filter_month);
	}

	var filter_store = $('#filter_store').val();
	
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
		
	//alert(url);
	location = url;
});
//--></script> 
     <script type="text/javascript"><!--
$('#button-download').on('click', function() {
	url = 'index.php?route=report/cash_report/download_excel&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_group = $('select[name=\'filter_group\']').val();
	
	if (filter_group) {
		//url += '&filter_group=' + encodeURIComponent(filter_group);
	}
	
	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').val();
	
	if (filter_order_status_id != 0) {
		//url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
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