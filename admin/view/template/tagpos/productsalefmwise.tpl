<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo "Product Sale Fm Wise"; ?></h1>
      
    </div>
  </div>
  <div class="container-fluid">
<?php if ($success) {  ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

<?php if ($error) {  ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo "Product Sale Fm Wise"; ?></h3>
		 <button type="button" id="button-download" class="btn btn-primary pull-right" 
		 style="margin-top: -8px !important; margin-right: 10px !important;">
            Download Excel</button>
         
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-store">Select Store</label>
                
                  <?php //echo $filter_store; print_r($stores);//exit; ?>
              
                  <select name="filter_store" id="input-store" class="form-control" style="width:100%;" >
				  <option value="">Select Store</option>
                  <?php foreach ($stores as $store) { ?>
                  <?php if ($store['store_id'] == $filter_store) { ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                 
              
        </div>
			 
 

 <div class="form-group">
                <label class="control-label" for="input-date-end">End Date</label>
                <div class="input-group date" id="date_from">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="End Date" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
 
            </div> 
			<div class="col-sm-6">
			
            <div class="form-group">
                <label class="control-label" for="input-date-start">Start Date</label>
                <div class="input-group date" id="date_from">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="Start Date" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
 
			
                
            </div>
            
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-name">Product Name</label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                <input type="hidden" name="filter_name_id"  value="<?php echo $filter_name_id; ?>" id="filter_name_id"/>
              </div>
              
            </div>
		<div class="col-sm-6">
			<div class="form-group">
				<label class="control-label" for="input-date-end"></label>
            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Search</button>
			
            </div></div>
          </div>
        </div>
        <div class="table-responsive">
	
                           
                           <br/><br/>
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">S.No.</td>

                <td class="text-right">Store Name</td>
               <td class="text-right">Order Date</td>
   	            <td  class="text-right">Inv no.</td> 
                <td class="text-right">Grower ID</td>
                <td class="text-right">Grower Details</td>
				
                <td class="text-right">Fm Name</td>
				<td class="text-right">Product Name</td>
                <td class="text-right">Quantity</td>
				<td class="text-right">Payment Method</td>
				<td class="text-right">Order Total</td>
                <td class="text-right">Tagged-Amount</td>
				<td class="text-right">Cash-Amount</td>
				<td class="text-right">Subsidy-Amount</td>
              </tr>            </thead>
            <tbody>
              <?php if ($orders) { if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; } ?>
              <?php foreach ($orders as $order) { //print_r($order); ?>
              <tr>
                <td class="text-left"><?php echo $aa; ?></td>
               
                <td class="text-right"><?php echo $order['store_name']; ?></td>
              <td class="text-right"><?php echo $order['date']; ?></td>
	            <td class="text-right"><?php echo $order['order_id']; ?></td>
                <td class="text-right"><?php echo $order['grower_id']; ?></td>
				
                <td class="text-right"><?php echo $order['payment_firstname']; ?></td>
				 <td class="text-right"><?php echo $order['fmname']; ?></td>
				<td class="text-right"><?php echo $order['model']; ?></td>
				 <td class="text-right"><?php echo $order['qnty']; ?></td>
                <td class="text-right"><?php echo $order['payment_method']; ?></td>
				<td class="text-right"><?php echo number_format((float)$order['total'], 2, '.', ''); ?></td>
				
				<td class="text-right"><?php echo number_format((float)$order['tagged'], 2, '.', ''); ?></td>
				
                
            <td class="text-right"><?php echo number_format((float)$order['cash'], 2, '.', ''); ?></td>
			<td class="text-right"><?php echo number_format((float)$order['subsidy'], 2, '.', ''); ?></td>
              </tr>              <?php $aa++; } ?>
              <?php  } else { ?>
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
		
		<?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>






  <script type="text/javascript">
$("#input-store").select2();


$('#button-filter').on('click', function() {
	var filter_store = $('select[name=\'filter_store\']').val();
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	//alert(filter_fmname);
	
	if(!filter_store)
	{
	  alertify.error('Please Select Store');
	  return false;
	}
	
	url = 'index.php?route=tagpos/fmdelivery/productsalefmwise&token=<?php echo $token; ?>';
	
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}	

	
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

$('#button-download').on('click', function() {
	url = 'index.php?route=tagpos/fmdelivery/download_excelproductsalefmwise&token=<?php echo $token; ?>';
	
	var filter_store = $('select[name=\'filter_store\']').val();
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	//alert(filter_fmname);
	
	if(!filter_store)
	{
	  alertify.error('Please Select Store');
	  return false;
	}
	
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}	

	
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
		//alert (url);
		window.open(url, '_blank');
	//location = url;
});


$('.date').datetimepicker({
	pickTime: false,
	maxDate: new Date()
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
</div>
<?php echo $footer; ?>