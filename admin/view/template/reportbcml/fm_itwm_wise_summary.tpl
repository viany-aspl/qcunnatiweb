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
                <label class="control-label" for="input-date-end">Select Store</label>
                
                      
                  <select name="filter_store" style="width: 100%" id="input-store" class="select2 form-control">
                   <option selected="selected" value="">SELECT STORE</option>
                  <?php foreach ($stores as $store) { //echo $store['store_id'];  ?>
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

      <div class="form-group">
                <label class="control-label" for="input-date-end">Field Delivery Type</label>
                
                  <select name="filter_report" style="width: 100%" id="input-report" class="form-control">
                   <option <?php if($filter_report=='ADVANCE') { ?> selected="selected"  <?php } ?> value="ADVANCE">ADVANCE</option>
                  <option <?php if($filter_report=='INDENT') { ?> selected="selected"  <?php } ?>  value="INDENT">INDENT</option>
	   <!--<option <?php if($filter_report=='ALL') { ?> selected="selected"  <?php } ?>  value="ALL">ALL</option>-->
                </select>
                  
              </div>
              
            </div>
            <div class="col-sm-6">
                      <div class="form-group">
                <label class="control-label" for="input-date-start">Date</label>
                <div class="input-group date" id="date_from">
                  <input type="text" name="filter_date"  value="<?php echo $filter_date; ?>" placeholder="Date" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
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
                <td class="text-left">Date</td>
                <td class="text-left">Store Name</td>
	<td class="text-left">Fm Code</td>
                <td class="text-left">Fm Name</td>
                <td class="text-left">Product Name</td>
          
                <td class="text-left">Quantity</td>
                <td class="text-left">Total Amount</td>
                <td class="text-left">Total Invoice</td>
                
              </tr>
            </thead>
            <tbody>
              <?php if ($orders) { $total_sales=0; ?>
              <?php foreach ($orders as $order) { ?>
              <tr>
                <td class="text-left"><?php echo $order['create_date']; ?></td>
                <td class="text-left"><?php echo $order['store_name']; ?></td>
          <td class="text-left"><?php echo $order['fmcode']; ?></td>
                <td class="text-left"><?php echo $order['fmname']; ?></td>
	  <td class="text-left"><?php echo $order['model']; ?></td>
                <td class="text-left"><?php echo $order['qnty']; ?></td>
                <td class="text-left"><?php echo number_format((float)$order['ttotal'],2,'.',''); ?></td>
                <td class="text-left"><?php echo $order['cnt']; ?></td>
              </tr>
              <?php 
              
              } ?>
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
          <div class="col-sm-6 text-right">
              
              
              <br/>
              <?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
$("#input-store").select2();
$('#button-filter').on('click', function() {
	url = 'index.php?route=reportbcml/report_fm&token=<?php echo $token; ?>';
	
	var filter_date = $('input[name=\'filter_date\']').val();
	
	if (filter_date) { 
		url += '&filter_date=' + encodeURIComponent(filter_date);
	}
	 var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store) { 
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
	 var filter_report = $('select[name=\'filter_report\']').val();
	
	if (filter_report) { 
		url += '&filter_report=' + encodeURIComponent(filter_report);
	}
	location = url;
});
//--></script> 
<script type="text/javascript">
$('#button-download').on('click', function() {
    url = 'index.php?route=reportbcml/report_fm/download_report&token=<?php echo $token; ?>';
   
    var filter_date = $('input[name=\'filter_date\']').val();
   
    if (filter_date) {
        url += '&filter_date=' + encodeURIComponent(filter_date);
    }
	 var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store) { 
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
	var filter_report = $('select[name=\'filter_report\']').val();
	
	if (filter_report) { 
		url += '&filter_report=' + encodeURIComponent(filter_report);
	}
    //location = url;
    window.open(url, '_blank');
});
//--></script>

  <script type="text/javascript">

$('.date').datetimepicker({
	pickTime: false
});

</script>
      
      </div>
<?php echo $footer; ?>