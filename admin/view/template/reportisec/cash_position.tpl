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
         <button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px;"> Download</button>

      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <!--<div class="col-sm-6">

             <div class="form-group">
                <label class="control-label" for="input-date-start">Select update date</label>
                <div class="input-group date">
                  <input type="text" name="filter_date"  value="<?php echo $filter_date; ?>" placeholder="Update date" data-date-format="YYYY-MM-DD" id="filter_date" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              
            </div>-->
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end">Select Store</label>
                
                      
                  <select name="filter_store" id="filter_store" style="width: 100%;" class="form-control select2">
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
<button type="button" id="button-filter" class="btn btn-primary pull-left" style="margin-top: 22px;"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
   </div>

          </div>
        </div>
        <div class="table-responsive">
            <span style="font-weight: bold;">Total ::  Amount : <?php echo number_format((float)$total_amount, 2, '.', ''); ?></span> <br/>
          <br/>
            <table class="table table-bordered">
            <thead>
              <tr>
	<td class="text-left">SID</td>
                <td class="text-left">Store Name <div style="float: right;">Audit Status</div></td>
                <td class="text-left">Store ID </td>
                <td class="text-right">Amount </td>
                <td class="text-right">User</td>
              </tr>
            </thead>
            <tbody>
              <?php if ($orders) { $total=0; if($_GET["page"]=="1"){ $aa=1; }elseif($_GET["page"]==""){$aa=1;} else { $aa=(@$_GET["page"]*20)-19; }  ?>
              <?php foreach ($orders as $order) { ?>
              <tr>
	<td class="text-left"><?php echo $aa; ?></td>
                <td class="text-left"><?php echo $order['store_name']; if($order['audit_status']=="1"){ ?>

<div style="float: right;background-color: rgb(77, 213, 77);color: white;padding: 2px 10px;border-radius: 3px;font-size: 14px;font-weight: bold;" 
title="<?php echo "Audit date is: ".date('d M Y',strtotime($order['audit_date'])); ?>">Yes</div>
<?php } else { ?>
<div style="float: right;background-color: #D74E22;color: white;padding: 2px 10px;border-radius: 3px;font-size: 14px;font-weight: bold;" title="Not audit, please ask to audit the store" >No</div>
<?php } ?>
</td>
                <td class="text-left"><?php echo $order['store_id']; ?></td>
                <td class="text-right"><?php echo number_format((float)$order['amount'], 2, '.', ''); ?></td>
                <td class="text-right"><?php echo $order['user']; ?></td>
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
              <span style="font-weight: bold;">Page Total ::  Amount : <?php echo number_format((float)$total, 2, '.', ''); ?></span> <br/>
              <?php echo $results; ?>  </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
$("#filter_store").select2();
<!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=reportisec/cash_report/cash_position&token=<?php echo $token; ?>';
	
	var filter_store = $('#filter_store').val();
	
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
             // var filter_date = $('#filter_date').val();
	
	//if (filter_date) {
	//	url += '&filter_date=' + encodeURIComponent(filter_date);
	//}
	
	location = url;
});
//--></script> 
<script type="text/javascript"><!--
$('#button-download').on('click', function() {
    url = 'index.php?route=reportisec/cash_report/download_cash_position_excel&token=<?php echo $token; ?>';
	
	var filter_store = $('#filter_store').val();
	
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
    //location = url;
        window.open(url, '_blank');
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>
</div>
<?php echo $footer; ?>