<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">      
        <a href="<?php echo $redirect; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a></div>
      <h1>Material Discard</h1>
      
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i>Material Discard</h3>
<button type="button" id="button-download" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download Excel</button>
      </div>
      <div class="panel-body">

	<div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end">Store</label>
                
                      
                  <select name="filter_store"  id="input-store" style="width: 100%;" class="select2 form-control">
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
                <label class="control-label" for="input-date-start">Start Date</label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="Start Date" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
				<div class="form-group">
                <label class="control-label" for="input-date-end">Product</label>
                     
                  <select name="filter_product" id="input-product" style="width: 100%;" class="select2 form-control ">
                   
                  <option selected="selected" value="">SELECT PRODUCT</option>
                  <?php foreach ($products as $product) { //echo $store['store_id'];  ?>
                  <?php if ($product['product_id'] == $filter_product) {
                      if($filter_product!=""){
                      ?>
                  <option value="<?php echo $product['product_id']; ?>" selected="selected"><?php echo $product['name']; ?></option>
                      <?php }} else { ?>
                  <option value="<?php echo $product['product_id']; ?>"><?php echo $product['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
	
              </div>
            </div>
            <div class="col-sm-6">
                
              <div class="form-group">
                <label class="control-label" for="input-date-end">Reason</label>
                <div class="input-group ">
                 
                  <span class="input-group-btn">
                      
                  <select name="filter_reason" id="input-reason" class="form-control">
                   <option selected="selected" value="">SELECT REASON</option>
                  <option <?php if($filter_reason=="Damage"){ ?>  selected="selected" <?php } ?> value="Damage">Damage</option>
	    <option <?php if($filter_reason=="Sampling"){ ?>  selected="selected" <?php } ?> value="Sampling">Sampling</option>
	    <option <?php if($filter_reason=="Software Error"){ ?>  selected="selected" <?php } ?> value="Software Error">Software Error</option>
	    <option  <?php if($filter_reason=="Demo"){ ?>  selected="selected" <?php } ?> value="Demo">Demo</option>
	    <option  <?php if($filter_reason=="Complimentory"){ ?>  selected="selected" <?php } ?> value="Complimentory">Complimentory</option> 
		<option  <?php if($filter_reason=="Billing error"){ ?>  selected="selected" <?php } ?> value="Billing error">Billing error</option>
		<option  <?php if($filter_reason=="Account's error"){ ?>  selected="selected" <?php } ?> value="Account's">Account's error</option>
	    <option <?php if($filter_reason=="Store Incharge Material Loss"){ ?>  selected="selected" <?php } ?> value="Store Incharge Material Loss">Store Incharge Material Loss</option>
                </select>
                  </span></div>
              </div>
	<div class="form-group">
                <label class="control-label" for="input-date-end">End Date</label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="End Date" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Filter</button>
            </div>
          </div>
        </div>

        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-store">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left">SID</td>
	          <td class="text-left">Store Name</td>
                  <td class="text-left">Product Name</td>
	    <td class="text-left">Product Price</td>
                  <td class="text-left">Quantity</td>
                  <td class="text-left">Reason</td>
	    <td class="text-left">Discard Date</td>
	    
                  <td class="text-left">Debit/Credit</td>
                  <td class="text-left">Remarks</td> 
                </tr>
              </thead>
              <tbody>
                <?php if ($b2b) { $a=1; ?>
                <?php foreach ($b2b as $un) { ?>
                <tr>
                  <td class="text-left"><?php echo $a; ?></td>
	          <td class="text-left"><?php echo $un['store_name']; ?></td>
                  <td class="text-left"><?php echo $un['product_name']; ?></td>
	    <td class="text-left"><?php echo $un['product_price']; ?></td>
                  <td class="text-left"><?php echo $un['quantity']; ?></td>
                  <td class="text-left"><?php echo $un['reason']; ?></td>
	    <td class="text-left"><?php echo date('Y-m-d',strtotime($un['create_time'])); ?></td>
                  <td class="text-left"><?php echo $un['debit_credit']; ?></td>
                  <td class="text-left"><?php echo $un['remarks']; ?></td>
             
                
                </tr>
                <?php $a++;} ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
         
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
$('.date').datetimepicker({
	pickTime: false
});
//--></script>
<script type="text/javascript">
$("#input-product").select2();  
<!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=material/discard&token=<?php echo $token; ?>';
	
        	var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store!="") {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
	var filter_product = $('select[name=\'filter_product\']').val();
	
	if (filter_product!="") {
		url += '&filter_product=' + encodeURIComponent(filter_product);
	}
	var filter_reason = $('select[name=\'filter_reason\']').val();
	
	if (filter_reason!="") {
		url += '&filter_reason=' + encodeURIComponent(filter_reason);
	}
       	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	location = url;
});
$('#button-download').on('click', function() { 
	url = 'index.php?route=material/discard/download_excel&token=<?php echo $token; ?>';
	
        	var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store!="") {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
	var filter_product = $('select[name=\'filter_product\']').val();
	
	if (filter_product!="") {
		url += '&filter_product=' + encodeURIComponent(filter_product);
	}
	var filter_reason = $('select[name=\'filter_reason\']').val();
	
	if (filter_reason!="") {
		url += '&filter_reason=' + encodeURIComponent(filter_reason);
	}
       	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	//location = url;
	window.open(url,'_blank');
});
//--></script> 

<?php echo $footer; ?> 