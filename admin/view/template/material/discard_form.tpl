<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-store" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1>Material Discard Form</h1>
      
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i>Material Discard Form</h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-store" class="form-horizontal">
            
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Store</label>
                <div class="col-sm-10">             
               <select name="filter_store" id="input-store" class="form-control" required>
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
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Product</label>
                <div class="col-sm-10">             
                <select name="product" id="product" class="select2 " required>
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
			<div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Quantity</label>
                <div class="col-sm-10">             
                <input type="text" name="quantity" value="" placeholder="Enter Quantity" id="quantity" class="form-control"  required="required" onkeypress='return event.charCode >= 48 &amp;&amp; event.charCode <= 57 || event.keyCode==8 || event.keyCode==46'

			/>
                </div>
            </div>
	<div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Reason</label>
                <div class="col-sm-10">             
                <select name="reason" id="reason" class="form-control" required>
                  <option selected="selected" value="">SELECT REASON</option>
                  <option  value="Damage">Damage</option>
	    <option  value="Sampling">Sampling</option>
	    <option value="Software Error">Software Error</option>
	    <option  value="Demo">Demo</option>
	    <option  value="Complimentory">Complimentory</option>
		<option  value="Billing error">Billing error</option>
		<option  value="Account's error">Account's error</option> 
	    <option  value="Store Incharge Material Loss">Store Incharge Material Loss</option>
		<option  value="Season End Material Discard">Season End Material Discard</option> 
                </select>
                </div>
            </div>

	<div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Debit/Credit</label>
                <div class="col-sm-10">             
                <select name="debit_credit" id="debit_credit" class="form-control" required>
                  
                  <option  value="Debit">Debit</option>
	    <option  value="Credit">Credit</option>
	    
                </select>
                </div>
            </div>
            
            <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-meta-title">Remarks</label>
                <div class="col-sm-10">             
                <textarea name="remarks" value="" placeholder="Remarks" id="remarks" class="form-control" ></textarea>
                </div>
            </div>
           
         
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript">
$("#product").select2();  
</script> 
<?php echo $footer; ?>