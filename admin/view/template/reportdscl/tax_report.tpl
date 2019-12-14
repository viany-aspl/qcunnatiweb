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
          <table class="table table-bordered">
            <thead>
              <tr>
                
                <td class="text-left">Store ID <?php //echo $column_date_end; ?></td>
                <td class="text-left">Store Name <?php //echo $column_date_end; ?></td>
                <td class="text-left">Product ID<?php //echo $column_title; ?></td>
                <td class="text-right">Product Name <?php //echo //$column_orders; ?></td>
	<td class="text-right">Order ID <?php //echo //$column_orders; ?></td>
                <td class="text-right">Sale Date <?php //echo $column_total; ?></td>
                <td class="text-right">Price <?php //echo $column_total; ?></td>
                <!--<td class="text-right">Tax Title <?php //echo $column_total; ?></td>-->
                <td class="text-right">Tax Amount <?php //echo $column_total; ?></td>
                <td class="text-right">Quantity <?php //echo $column_total; ?></td>
	<td class="text-right">Total Tax <?php //echo $column_total; ?></td>
                <td class="text-right">Total <?php //echo $column_total; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($orders) { $total=0; ?>
              <?php foreach ($orders as $order) { ?>
              <tr>
                <td class="text-left"><?php echo $order['store_id']; ?></td>
                <td class="text-left"><?php echo $order['store_name']; ?></td>
                <td class="text-left"><?php echo $order['product_id']; ?></td>
                <td class="text-left"><?php echo $order['Product_name']; ?></td>
	<td class="text-left"><?php echo $order['order_id']; ?></td>
                <td class="text-right"><?php echo $order['sale_date']; ?></td>
                <td class="text-right"><?php echo $order['price']; ?></td>
                <!--<td class="text-right"><?php echo $order['tax_title']; ?></td>-->
                <td class="text-right"><?php echo $order['tax_amount']; ?></td>
                <td class="text-right"><?php echo $order['quantity']; ?></td>
	<td class="text-right"><?php echo $order['Total_tax'] ?></td>
                 <td class="text-right"><?php echo $order['Total']; ?></td>
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
              <!--<span style="font-weight: bold;">Total Amount : <?php echo $total; ?></span> <br/>-->
              <?php echo $results; ?>  </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/tax_report&token=<?php echo $token; ?>';
	
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

	location = url;
});
//--></script> 
<script type="text/javascript"><!--
$('#button-download').on('click', function() {
    url = 'index.php?route=report/tax_report/download_excel&token=<?php echo $token; ?>';
    
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