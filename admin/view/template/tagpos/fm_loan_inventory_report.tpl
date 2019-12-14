<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo "FM Loan Inventory"; ?></h1>
      
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
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo "FM Loan Inventory"; ?></h3>
		<button type="button" id="button-download" class="btn btn-primary pull-right" 
		 style="margin-top: -8px !important; margin-right: 10px !important;">
            Download Excel</button>
         
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-store">Select Fm</label>
                
                  <?php //echo $filter_store; print_r($stores);//exit; ?>
              
                  <select name="filter_fm" id="input-fm" class="form-control" style="width:100%;" >
				  <option value="">Select fm</option>
                  <?php foreach ($fmlist as $fm) { ?>
                  <?php if ($fm['id'] == $filter_fm) { ?>
                  <option value="<?php echo $fm['id']; ?>" selected="selected"><?php echo $fm['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $fm['id']; ?>"><?php echo $fm['name']; ?></option>
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
               
				 <select required class="form-control tab_border" id="filter-product" style="width: 100%;" name="filter_product">
				 <option value="">Select Product</option>
				 <?php foreach ($products as $product) { ?>
				<?php if ($product['product_id'] == $filter_product) { ?>
                  <option value="<?php echo $product['product_id']; ?>" selected="selected"><?php echo $product['model']; ?></option>
                  <?php } else { ?>
				<option value="<?php echo $product['product_id']; ?>"><?php echo $product['model'];?></option>
				 <?php } ?>
				 <?php } ?>
			
				</select>
              </div>
              <div class="form-group">
				<label class="control-label" for="input-date-end"></label>
            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Search</button>
			
            </div>
            </div>
	
          </div>
        </div>
        <div class="table-responsive">
	
                           
                           <br/><br/>
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">S.No.</td>

                
				
                <td class="text-left">Fm Name</td>
				<td class="text-left">Product Name</td>
                <td class="text-left">Quantity</td>
				<td class="text-left">Billed</td>
				<td class="text-left">Balance</td>
				
                
              </tr>            </thead>
            <tbody>
              <?php    if ($orderss) { if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; } ?>
              <?php   foreach ($orderss as $order) { //print_r($order); ?>
              <tr>
                <td class="text-left"><?php echo $aa; ?></td>
               
                <td class="text-left"><?php echo $order['fm_name']; ?></td>
                <td class="text-left"><?php echo $order['product_name']; ?></td>
	            <td class="text-left"><?php echo $order['quantity']; ?></td>
                <td class="text-left"><?php echo $order['billed']; ?></td>
				 <td class="text-left"><?php echo $order['balance']; ?></td>
				 
				
               
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
$("#input-fm").select2();

$("#filter-product").select2();
$('#button-filter').on('click', function() {
	var filter_fm = $('select[name=\'filter_fm\']').val();
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	var filter_product = $('select[name=\'filter_product\']').val();
	//alert(filter_fmname);
	
	
	
	url = 'index.php?route=tagpos/loan_inventory/fm_loan_inventory&token=<?php echo $token; ?>';
	
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}	

	
	if (filter_fm) {
		url += '&filter_fm=' + encodeURIComponent(filter_fm);
	}
	
	
	if (filter_product) {
		url += '&filter_product=' + encodeURIComponent(filter_product);
	}
	
	
	location = url;
});

$('#button-download').on('click', function() {
	url = 'index.php?route=tagpos/loan_inventory/download_excelfmloan&token=<?php echo $token; ?>';
	
	var filter_fm = $('select[name=\'filter_fm\']').val();
	var filter_product = $('select[name=\'filter_product\']').val();
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	//alert(filter_fmname);
	
	
	
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}	
if (filter_fm) {
		url += '&filter_fm=' + encodeURIComponent(filter_fm);
	}
	
	if (filter_product) {
		url += '&filter_product=' + encodeURIComponent(filter_product);
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