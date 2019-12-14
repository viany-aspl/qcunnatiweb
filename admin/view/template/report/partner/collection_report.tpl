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
	<i class="<?php echo $tool_tip_class; ?> " data-toggle="tooltip" style="<?php echo $tool_tip_style; ?>" title="<?php echo $tool_tip; ?>"></i>
	
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
                <label class="control-label" for="input-date-end">Select Partner</label>
                
                      
                  <select name="filter_store"  id="input-store" style="width: 100%;" class="select2 form-control">
                   <option selected="selected" value="">SELECT PARTNER</option>
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
              
            </div>
            <div class="col-sm-6">
                
              
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <span style="font-weight: bold;">
                  Total Amount : <?php echo number_format((float)$totalcredit, 2, '.', ''); ?> 
                 
              </span><br/> <br/> 
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">SI ID </td>
	  <td class="text-left">Store Name</td>
	  <td class="text-left">Partner Name</td>
                <td class="text-left">Location</td>
                <td class="text-left">Mobile</td>
                <td class="text-left">Credit Limit</td>
                <td class="text-left">Open Invoice Outstanding</td> 
                <td class="text-left">Wallet Balance</td> 
	  <td class="text-left">Actual Outstanding</td> 
              </tr>
            </thead>
            <tbody>
              <?php if ($products) {  if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; }?>
              <?php foreach ($products as $order) { ?>
              <tr>
                <td class="text-left"><?php echo $aa; ?></td>
	  <td class="text-left"><?php echo $order['name']; ?></td>
                <td class="text-left"><?php echo $order['partner_name']; ?></td>
                <td class="text-left" style="max-width: 200px;"><?php echo $order['address']; ?></td>
                <td class="text-left"><?php echo $order['mobile']; ?></td>
                <td class="text-left"><?php echo $order['creditlimit']; ?></td>
                <td class="text-left"><?php if($order['currentcredit']>0) { echo "-".$order['currentcredit']; } else { echo $order['currentcredit']; } ?></td>
                <td class="text-left"><?php echo $order['wallet_balance']; ?></td>
	  <td class="text-left"><?php 
	  if($order['actual_outstanding']>0) { echo "<font style='color: red;'>-".$order['actual_outstanding']."</font>"; }
	else if($order['actual_outstanding']<0) { echo "<font style='color: green;'>".(-1)*($order['actual_outstanding'])."</font>"; }
	else
	{
		echo "<font style='color: green;'>".($order['actual_outstanding'])."</font>";
	}
	  ?></td>
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
  <script type="text/javascript">
$("#input-store").select2();
<!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/partner/collection_report&token=<?php echo $token; ?>';
	
        var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store!="") {
		url += '&filter_stores_id=' + encodeURIComponent(filter_store);
	}
       
	location = url;
});
//--></script> 
<script type="text/javascript"><!--
$('#button-download').on('click', function() {
    url = 'index.php?route=report/partner/collection_reportdownload_excel&token=<?php echo $token; ?>';
    
        var filter_store = $('select[name=\'filter_store\']').val();
    
    if (filter_store!="") {
        url += '&filter_stores_id=' + encodeURIComponent(filter_store);
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