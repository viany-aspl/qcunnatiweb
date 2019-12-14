<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo "Physical Inventory"; ?></h1>
      
<i class="<?php echo $tool_tip_class; ?> " data-toggle="tooltip" style="<?php echo $tool_tip_style; ?>" title="<?php echo $tool_tip; ?>"></i>
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
                
                      
                  <select name="filter_store" id="filter_store" style="width:100%" class="select2 form-control">
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
              <div class="form-group">
                <label class="control-label" for="input-name">Product Name</label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                <input type="hidden" name="filter_name_id"  value="<?php echo $filter_name_id; ?>" id="filter_name_id"/>
              </div>
             <br/><br/>
				  <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
   
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">SI ID </td>
				  <td class="text-left">Store Name</td>
                <td class="text-left">Product Name</td>
				<td class="text-left">Product Rate</td>
				  <td class="text-left">System Quantity </td>
				  <td class="text-left">Physical Quantity </td>
				  <td class="text-left">Field Quantity</td>
                <td class="text-left">Date </td>
                
              </tr>
            </thead>
            <tbody>
              <?php if ($orders) {  if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; }?>
              <?php foreach ($orders as $order) { ?>
              <tr>
                <td class="text-left"><?php echo $aa; ?></td>
				<td class="text-left"><?php echo $order['storename']; ?></td>
				<td class="text-left"><?php echo $order['productname']; ?></td>
				<td class="text-left"><?php echo number_format((float)($order['store_price']+$order['store_tax_amt']),2,'.',''); ?></td>
				<td class="text-left"><?php echo $order['store_quantity']; ?></td>
				<td class="text-left"><?php echo $order['field_quantity']; ?></td>
				<td class="text-left"><?php echo ($order['store_quantity']-$order['field_quantity']); ?></td>
				<td class="text-left"><?php echo $order['dat']; ?></td>
				
                
                
              </tr>
              <?php 
              $aa++;
              } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
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
  $("#filter_store").select2();
$('#button-filter').on('click', function() {
	url = 'index.php?route=reportbcml/inventory_report/field_inventory_report&token=<?php echo $token; ?>';
	
       
var filter_store = $('#filter_store').val();
	
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	
	var filter_name_id = $('input[name=\'filter_name_id\']').val();
        var filter_name = $('input[name=\'filter_name\']').val();

    if (filter_name_id) {
                if(filter_name!="")
                {
        url += '&filter_name_id=' + encodeURIComponent(filter_name_id);
                }
    }
       

    if (filter_name) {
        url += '&filter_name=' + encodeURIComponent(filter_name);
    }		
		
       
	location = url;
});
</script> 
<script type="text/javascript">
$('#button-download').on('click', function() {
    url = 'index.php?route=reportbcml/inventory_report/field_inventory_report_download&token=<?php echo $token; ?>';
  
   var filter_store = $('#filter_store').val();
	
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
    var filter_name_id = $('input[name=\'filter_name_id\']').val();
        var filter_name = $('input[name=\'filter_name\']').val();

    if (filter_name_id) {
                if(filter_name!="")
                {
        url += '&filter_name_id=' + encodeURIComponent(filter_name_id);
                }
    }
       

    if (filter_name) {
        url += '&filter_name=' + encodeURIComponent(filter_name);
    }	
        window.open(url, '_blank');
});
</script> 
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
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>