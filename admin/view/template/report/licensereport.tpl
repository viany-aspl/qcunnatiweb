<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>License Report</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">License Report</a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i>License Report List</h3>
<!--        <button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download</button>-->
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
<!--              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>-->
<div class="form-group">
                <label class="control-label" for="input-date-end">Select Store</label>
                <div class="input-group date">
                  <?php //echo $filter_store; print_r($stores);//exit; ?>
                  <span class="input-group-btn">
                      
                  <select name="filter_store" id="input-store" class="form-control">
                  <?php foreach ($stores as $store) { ?>
                  <?php if ($store['store_id'] == $filter_store) { ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                  </span></div>
              </div>
            </div>
           <br/>
           <button style="margin-top:5px" type="button" id="button-filter" class="btn btn-primary "><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
           
          </div>
        </div>
        <div class="table-responsive">


          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">Partner Name</td>
                <td class="text-left">GSTN</td>
                <td class="text-left">Fertiliser License Number</td>
                <td  class="text-left">Fertiliser Issue Date</td>
                <td class="text-left">Fertiliser Expirt Date</td>
                <td  class="text-left">Pesticide License Number</td>
                <td class="text-left">	Pesticide Issue Date</td>
                <td class="text-left">Pesticide Expiry Date</td>
                <td class="text-left">Seed License Number</td>
                <td class="text-left">	MSMFID NO</td>
              </tr>            </thead>
            <tbody>
              <?php if ($orders) { ?>
              <?php foreach ($orders as $order) { ?>
              <tr>
                <td  class="text-left"><?php echo $order['store_id']; ?></td>
                <td class="text-left"><?php echo $order['gstn']; ?></td>
                <td class="text-left"><?php echo $order['fertilizer']; ?></td>
               
                <td class="text-left"><?php echo $order['fertilizerissue']; ?></td>
                <td class="text-left"><?php echo $order['fertilizerto']; ?></td>
                <td  class="text-left"><?php echo $order['pesticide']; ?></td>
                <td class="text-left"><?php echo $order['pesticidefrom']; ?></td>
                <td class="text-left"><?php echo $order['pesticideto']; ?></td>
                <td  class="text-left"><?php echo $order['seed']; ?></td>
                <td  class="text-left"><?php echo $order['MSMFID']; ?></td>
                
              </tr>              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="6"> 
		
		<?php echo $text_no_results; ?>

	</td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right">
		
		<?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/licensereport&token=<?php echo $token; ?>';
	
         var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != 0) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	

	location = url;
});
//--></script> 
 <script type="text/javascript"><!--
$('#button-download').on('click', function() {
	url = 'index.php?route=report/sale_order/download_excel&token=<?php echo $token; ?>';
	
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
		url += '&filter_group=' + encodeURIComponent(filter_group);
	}
	
	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').val();
	
	if (filter_order_status_id != 0) {
		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
	}
         var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != 0) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	
        window.open(url, '_blank');
	//location = url;
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>