<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      
      <h1><?php echo "Partner Invoice Adjustment"; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if (!empty($error_warning)) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if (!empty($success)) {		?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo "Invoice List"; ?></h3>
      </div>
      <div class="panel-body">
	  
        <div class="well">
         
		  <div class="row">
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end">Partner Store</label>
                <div class="input-group">
                  <?php //echo $filter_store; //print_r($stores);//exit; ?>
                  <span class="input-group-btn">
                      
                  <select name="filter_store" id="input-store" class="form-control">
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
                  </span></div>
              </div>
			</div>
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end">Paid Status</label>
                <div class="input-group">
                  <?php //echo $filter_store; //print_r($stores);//exit; ?>
                  <span class="input-group-btn">
                      
                  <select name="filter_type" id="input-filter_type" class="form-control">
                   <option selected="selected" value="">ALL</option>
                   <option <?php if($filter_type=="1"){ ?> selected="selected" <?php } ?> value="1">Paid</option>
                   <option <?php if($filter_type=="0"){ ?> selected="selected" <?php } ?> value="0">Un-Paid</option>
                </select>
                  </span></div>
              </div>
            </div>
		  </div>
            
		  <div class="row">
                        <div class="col-sm-6">
             
			</div>
				<div class="col-sm-6">
					<button class="btn btn-primary pull-right" id="clear-filter" onclick="reset_form();" type="button"> Clear</button>
					<button class="btn btn-primary pull-right" id="button-filter" style="margin-right:10px;" type="button"><i class="fa fa-search"></i> Filter</button>
				</div>
		  </div>
        </div>
	<?php if($partner_info['name']!=""){  ?>
          <div class="row" style="font-weight: bold;font-size: 15px;margin-bottom: 23px;color: rgba(48, 33, 33, 0.68);">
              <div class="col-sm-6">
                  Store: <?php echo $partner_info['name']; ?>
		<br/>
	    Firm Name: <?php echo $partner_info['firm_name']; ?>
              </div>
              <div class="col-sm-6">
                 Current Wallet Balance : <?php echo $partner_info['wallet_balance']; ?>
                 <input type="hidden" id="current_balance" value="<?php echo $partner_info['wallet_balance']; ?>" />
              </div>
          </div>    
	<?php } ?>
         <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left">Partner Name</td>
                  <td class="text-left">Invoice Number</td>
		  <td class="text-left">Invoice Amount</td>
                  <td class="text-left">Invoice Date</td>
                  <td class="text-left">Paid Status</td>
                  <td class="text-left">Paid Date (If any)</td>
		  <td class="text-left" style="max-width: 100px;">Action</td>
                </tr>
              </thead>
              <tbody>
                <?php if($order_list){
		      foreach($order_list as $order)
		      { //print_r($order);
		?>
			<tr>
                            <td class="text-left"><?php echo $order['store_name']; ?></td> 				
                            <td class="text-left"><?php echo $order['po_invoice_prefix']."/".$order['po_invoice_n']; ?></td>
                            <td class="text-left"><?php echo $order['order_total']; ?></td> 
                            <td class="text-left"><?php echo $order['create_date']; ?></td>
                            <td class="text-left"><?php if($order['paid_status']=="0"){ echo "Un-Paid";} else if($order['paid_status']=="1"){ echo "Paid";} ?></td>
                            <td class="text-left"><?php if(($order['paid_date']!="0000-00-00") && ($order['paid_date']!="")){ echo $order['paid_date'];} ?></td>
                            <td class="text-left">
		<?php if($order['paid_status']=="0"){ ?>
                                <a href="<?php echo $adjust_invoice; ?>&invoice_id=<?php echo $order['sid']; ?>" onclick="return adjust_confirmation(<?php echo $order['sid']; ?>,<?php echo $order['order_total']; ?>);" data-toggle="tooltip" title="" style="margin-left: 5px;" 
                                   class="btn btn-info"> Adjust Amount
                                    
                                </a>
                                <?php } ?>                           
                            </td>
			</tr>
		<?php
                    }
		}?>
              </tbody>
            </table>
          </div>
        
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  </div>
  <script type="text/javascript">
      
function adjust_confirmation(sid,amount)
{
    var current_balance=$("#current_balance").val();
    if(current_balance<amount)
    {
       alertify.error('Available Wallet Balance is low !'); 
       return false;
    }
    else
    {
    //alert(sid);
   var cnfrm=confirm('Are You Sure ? You want to adjust the selected Invoice ');
   if(cnfrm){
                        
                    return true;
                }else{
                    
                    return false;
                }
                /*                     
                alertify.confirm('Are You Sure ? You want to adjust the selected Invoice ',
                function(e){ 
                    if(e){
                        alert('here');
                    return true;
                }else{
                    //alertify.error(''); 
                    return false;
                }
            }
                    
                        );
                 $("#alertify-ok").html('Continue');    
            */
    //return false;
    }
}
$('#button-filter').on('click', function() {
	url = 'index.php?route=invoice/adjustment&token=<?php echo $token; ?>';
	
        
		
	var filter_type = $('#input-filter_type').val();
	
	if (filter_type) {
		url += '&filter_type=' + encodeURIComponent(filter_type);
	}
        
        var filter_store = $('#input-store').val();
        if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
       
	location = url;
});
</script> 
  <script type="text/javascript">
	$('.date').datetimepicker({
		pickTime: false
	});
	
	function reset_form()
	{
		$('[name=from]').val('');
		$('[name=to]').val('');
		$('[name=filter_id]').val('');
		$('[name=status]').prop('selectedIndex', 0);
	}
  </script>
<?php echo $footer; ?> 
