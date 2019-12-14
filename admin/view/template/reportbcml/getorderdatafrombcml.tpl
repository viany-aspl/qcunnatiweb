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
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo $text_list; ?></h3>
        
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label">Order ID</label>
               
                  <input type="text" name="filter_id" value="<?php echo $filter_id; ?>" placeholder="Order ID"  id="input-filterid" class="form-control" />
                  
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-end">Select Store</label>
                
                      
                  <select name="filter_store" style="width: 100%;" id="input-store" class="select2 form-control">
                      <option value="">SELECT STORE</option>
                  <?php foreach ($stores as $store) { ?>
                  <?php if ($store['store_id'] == $filter_store) { ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
             
              </div>
              
            </div>
            <div class="col-sm-6">
              

		<div class="form-group">
                <label class="control-label" for="input-date-end">Select Unit</label>
               
                 
                      
                  <select name="filter_unit" id="input-unit" class="form-control">
                         <option value="" >SELECT UNIT</option>
			<?php foreach($units as $unit) {  ?>
				<option value="<?php echo $unit['unit_id']; ?>" <?php if ($unit['unit_id'] == $filter_unit) { ?> selected="selected" <?php } ?>><?php echo $unit['unit_name']; ?></option> 
			<?php } ?>
			
                </select>
      
              </div>

              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
	
                           
                           <br/><br/>
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">OrderID</td>
                <td class="text-left">IndentNo</td>
                <td class="text-right">DeliveryDate</td>
                <td class="text-right">InvoiceValue</td>
	<td  class="text-right">CashValue</td>
   	 <td  class="text-right">TaggedValue</td>
                
	
                <td class="text-right">DeliveryMode</td>
                <td class="text-left">FMCode</td>
                <td class="text-right">VerifiedThrough</td>
                
                <td class="text-right">DeliveryReceipt</td>
              </tr>            </thead>
            <tbody>
              <?php if ($orderdata) {  ?>
              <?php foreach ($orderdata as $order) { //print_r($order); ?>
              <tr>
                <td class="text-left"><?php echo $order['OrderID']; ?></td>
                <td class="text-left"><?php echo $order['IndentNo']; ?></td>
                <td class="text-right"><?php echo $order['DeliveryDate']; ?></td>
                <td class="text-right"><?php echo $order['InvoiceValue']; ?></td>
                <td class="text-right"><?php echo $order['CashValue']; ?></td>
	<td class="text-right"><?php echo $order['TaggedValue']; ?></td>
                <td class="text-right"><?php echo $order['DeliveryMode']; ?></td>
                <td class="text-left"><?php echo $order['FMCode']; ?></td>
                <td class="text-right"><?php echo $order['VerifiedThrough']; ?></td>
         
                <td class="text-right"><?php echo $order['DeliveryReceipt']; ?></td>
              </tr>              <?php } ?>
              <?php  } else { ?>
              <tr>
                <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right">
		<!--<span style="font-weight: bold;">Page Total :: Total Amount : <?php //echo number_format((float)$total_tagged_amount, 2, '.', ''); ?></span> 
                           
                           <br/>-->
		<?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>






  <script type="text/javascript">
$("#input-store").select2();

$('#button-filter').on('click', function() {
	url = 'index.php?route=reportbcml/reconciliation/getorderdata&token=<?php echo $token; ?>';
	
	var filter_id = $('input[name=\'filter_id\']').val();
	
	if (filter_id) {
		url += '&filter_id=' + encodeURIComponent(filter_id);
	}
	else
	{
		alertify.error('Please Enter Order ID');
		return false;
	}	
	
              var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != 0) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
	else
	{
		alertify.error('Please Select Store');
		return false;
	}
	var filter_unit = $('select[name=\'filter_unit\']').val();
	
	if (filter_unit != '') {
		url += '&filter_unit=' + encodeURIComponent(filter_unit);
	}	
	else
	{
		alertify.error('Please Select Unit');
		return false;
	}
	location = url;
});
</script> 
 </div>
<?php echo $footer; ?>