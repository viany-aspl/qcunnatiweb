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
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end">Select Contractor</label>
                <div class="input-group">
                  <?php //echo $filter_store; //print_r($stores);//exit; ?>
                  <span class="input-group-btn">
                      
                  <select name="filter_store" id="input-store" class="form-control">
                   <option selected="selected" value="">SELECT CONTRACTOR</option>
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
                  </span></div>
              </div>
              
            </div>
            <div class="col-sm-6">
                
              	<div class="form-group">
                <label class="control-label" for="input-date-end">Select Unit</label>
                <div class="input-group">
                 
                  <span class="input-group-btn">
                      
                  <select name="filter_unit" id="input-store" class="form-control">
                   <option selected="selected" value="">SELECT UNIT</option>
                  
                 
                            <option value="01" <?php if($filter_unit=="01"){ ?> selected="selected" <?php } ?> >01</option>
                    		<option value="02" <?php if($filter_unit=="02"){ ?> selected="selected" <?php } ?> >02</option>
		<option value="03" <?php if($filter_unit=="03"){ ?> selected="selected" <?php } ?> >03</option>
		<option value="04" <?php if($filter_unit=="04"){ ?> selected="selected" <?php } ?> >04</option>
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
                <td class="text-left">SI ID <?php //echo $column_Si_Id; ?></td>
	        <td class="text-left">Contractor ID <?php //echo $column_title; ?></td>
                <td class="text-left">Name <?php //echo $column_title; ?></td>
                <td class="text-right">Zonal office Name<?php //echo //$column_orders; ?></td>
                <td class="text-right">Zonal Office <?php //echo $column_total; ?></td>
                <td class="text-right">Store Name <?php //echo $column_total; ?></td> 
                <td class="text-right">Unit id<?php //echo $column_total; ?></td>
                <td class="text-right">Company<?php //echo $column_total; ?></td>
	        <td class="text-right">Credit limit<?php //echo $column_total; ?></td>
                <td class="text-right">Current Credit<?php //echo $column_total; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($orders) {  if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; }?>
              <?php foreach ($orders as $order) {  
	$arrr=explode('Rs. ',$order['price']);
	$price=$arrr[1];
	$total_price=$price+$order['tax'];
	?>
              <tr>
                <td class="text-left"><?php echo $aa; ?></td>
	<td class="text-left"><?php echo $order['contractor_id']; ?></td>
                <td class="text-left"><?php echo $order['contractor_name']; ?></td>
                <td class="text-right"><?php echo $order['zonal_office_name']; ?></td>
                <td class="text-right"><?php echo $order['zonal_office']; ?></td>
                 <td class="text-right"><?php echo $order['store_name']; ?></td>
                  <td class="text-right"><?php echo $order['unit_id']; ?></td>
                   <td class="text-right"><?php echo $order['company']; ?></td>
                    <td class="text-right"><?php echo number_format((float)$order['creditlimit'], 2, '.', ''); ?></td>
                <td class="text-right"><?php echo number_format((float)$order['currentcredit'], 2, '.', ''); ?></td>
	      </tr>
              <?php 
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
             
              <?php echo $results; ?>  </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/contractor_report/get_creditlimit&token=<?php echo $token; ?>';
	
        var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store!="") {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
             var filter_unit = $('select[name=\'filter_unit\']').val();
	
	if (filter_unit!="") {
		url += '&filter_unit=' + encodeURIComponent(filter_unit);
	}

	
	location = url;
});
//--></script> 
<script type="text/javascript"><!--
$('#button-download').on('click', function() {
    url = 'index.php?route=report/contractor_report/get_creditlimit_download_excel&token=<?php echo $token; ?>';
    
        var filter_store = $('select[name=\'filter_store\']').val();
    
    if (filter_store!="") {
        url += '&filter_store=' + encodeURIComponent(filter_store);
    }
	var filter_unit = $('select[name=\'filter_unit\']').val();
	
	if (filter_unit!="") {
		url += '&filter_unit=' + encodeURIComponent(filter_unit);
	}
   
       
    //location = url;
        window.open(url, '_blank');
});
//-->

</script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>