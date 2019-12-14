<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
	<div class="pull-right">      
        <a href="<?php echo $redirect; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a></div>
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
	 <div class="form-group">
 <br/>
                <label class="control-label" for="input-name">Product Name</label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                <input type="hidden" name="filter_name_id"  value="<?php echo $filter_name_id; ?>" id="filter_name_id"/>
              </div>
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
                <label class="control-label" for="input-date-end">Select Store</label>
                
                  <select name="filter_store" id="input-store" style="width: 100%;" class="select2 form-control">
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
              
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">SI ID </td>
                
                <td class="text-left">Store Name </td>
                <td class="text-right">Product ID</td>
	<td class="text-right">Product Name</td>
                <td class="text-right">Date </td>
                <td class="text-right">Quantity</td>
                <td class="text-right">Remarks</td>
	<td class="text-right">Status</td>
              </tr>
            </thead>
            <tbody>
              <?php if ($orders) { $total=0; ?>
              <?php foreach ($orders as $order) { ?>
                
              <tr>
                <td class="text-left"><?php echo $order['SIID']; ?></td>
               
                <td class="text-left"><?php echo $order['store_name']; ?></td>
                <td class="text-right"><?php echo $order['product_id']; ?></td>
                <td class="text-right"><?php echo $order['product_name']; ?></td>
                <td class="text-right"><?php echo $order['date_added']; ?></td>
                <td class="text-right"><?php echo $order['quantity']; ?></td>
	<td class="text-right" style="max-width: 200px;"><?php echo $order['remarks']; ?></td>
	<td class="text-right"><?php if($order['status']=="0") { echo "<span style='color: red;'>Failure</span>"; } else if($order['status']=="1"){ echo "<span style='color: green;'>Success</span>";} else { echo "Pending"; } ?></td>
              </tr>
              <?php 
              
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
  <script type="text/javascript">
$("#input-store").select2();
<!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=reversal/reversal/trans&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_name_id = $('input[name=\'filter_name_id\']').val();
              var filter_name = $('input[name=\'filter_name\']').val();

              if (filter_name_id) 
	{
                if(filter_name!="")
                {
                   url += '&filter_name_id=' + encodeURIComponent(filter_name_id);
                }
            }
       

           if (filter_name) {
             url += '&filter_name=' + encodeURIComponent(filter_name);
           }		
	var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store!="") {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
	location = url;
});
//--></script> 
     <script type="text/javascript"><!--
$('#button-download').on('click', function() {
	url = 'index.php?route=reversal/reversal/trans_download&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();   
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_name_id = $('input[name=\'filter_name_id\']').val();
              var filter_name = $('input[name=\'filter_name\']').val();

              if (filter_name_id) 
	{
                if(filter_name!="")
                {
                   url += '&filter_name_id=' + encodeURIComponent(filter_name_id);
                }
            }
       

           if (filter_name) {
             url += '&filter_name=' + encodeURIComponent(filter_name);
           }		
	var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store!="") {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
        window.open(url, '_blank');
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>
<script type="text/javascript">
$('input[name=\'filter_name\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['product_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'filter_name\']').val(item['label']);
                $('input[name=\'filter_name_id\']').val(item['value']);
    }
});
</script>

</div>
<?php echo $footer; ?>