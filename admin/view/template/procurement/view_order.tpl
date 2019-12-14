<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><!--<button onclick ="print_order()" data-toggle="tooltip" title="<?php echo "Print Order"; ?>" class="btn btn-info"><i class="fa fa-print"></i></button>--><!--<a href="<?php echo $shipping; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-info"><i class="fa fa-truck"></i></a> <a href="<?php echo $edit; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>--> <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo "Cancel Button"; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo "Orders"; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
      
      <?php if (isset($error_warning)) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

	<?php if (isset($success)) {		?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success;?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    </div>
  </div>
  <div class="panel panel-default" id = "print_div">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-info-circle"></i>
			<?php echo "Order # " . $order_information['order_info']['id']; ?>
                        
		</h3>
                <h3 class="panel-title pull-right">
			<i class="fa fa-info-circle"></i>
			<?php 
                        if($order_information['order_info']['receive_bit']=="1")
                        {
                            $order_status="Completed";
                        }
                        if($order_information['order_info']['receive_bit']=="0")
                        {
                            $order_status="Pending";
                        }
                        if($order_information['order_info']['canceled_message']!=''){
                             $order_status="Canceled";
                        }
                        echo "Order Status : " .$order_status ; ?>
                        
		</h3>
	</div>
	<div class="panel-body">
                <div class="row">
			<div class="col-lg-3">
				<label>Store Name:</label>
			</div>
			<div class="col-lg-9">
				<?php echo $store_information['name']; ?>
			</div>
                    
		</div>
		<div class="row">
			<div class="col-lg-3">
				<label>Ordered By:</label>
			</div>
			<div class="col-lg-9">
				<?php echo $order_information['order_info']['firstname'] ." ".$order_information['order_info']['lastname']; ?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-3">
			<label>
				Purchase order date:
			</label>
			</div>
			<div class="col-lg-9">
				<?php echo $order_information['order_info']['order_date']; ?>
			</div>
		</div>
		<?php if($order_information['order_info']['receive_date'] != '0000-00-00'){?>
		<div class="row">
			<div class="col-lg-3">
				<label>Received On:</label>
			</div>
			<div class="col-lg-9">
				<?php echo $order_information['order_info']['receive_date']; ?>
			</div>
		</div>
		<?php } ?>
            <div class="row">
			<div class="col-lg-3">
				<label>Credit Limit</label>
			</div>
			<div class="col-lg-3">
				<?php echo $creditlimit=$store_information['creditlimit']; ?>
			</div>
                
                    
		</div>
            <div class="row">
			
                <div class="col-lg-3">
				<label>Current Credit</label>
			</div>
			<div class="col-lg-3">
				<?php echo $currentcredit=$store_information['currentcredit']; ?>
			</div>
		</div>
		<table class="table table-bordered" id="print_table" border="1">
          <thead>
            <tr>
              <td class="text-left" style="width: 11.11%;">Product Name</td>
              <!--<td class="text-left" style="width: 25%; visibility:hidden;">Attribute Group</td>-->
			  <td class="text-left" style="width: 11.11%;display: none;">Option Values</td>
			  <td class="text-left" style="width: 11.11%;">Demand</td>
			  <td class="text-left" style="width: 11.11%;">Total Received Quantity</td>
			  <td class="text-left remaining_quantity" style="width: 11.11%;">Remaining Quantity</td>
			  <td class="text-left" style="width: 11.11%;">Supplier</td>
			  <td class="text-left" style="width: 11.11%;">Quantity from supplier</td>
			  <td class="text-left" style="width: 11.11%;">Price</td>
			  <td class="text-left" style="width: 11.11%;">Total Price</td>
			</tr>
          </thead>
          <tbody>
		  <?php
			$grand_total = 0;
		    $start_loop = 0;
			foreach($order_information['products'] as $product)
			{
		  ?>
            <tr>
              <td class="text-left"><?php echo  $product['name'];?></td>
			  <!--<td class="text-left" style="visibility:hidden;">
			  <?php for($i=0; $i<count($product['attribute_groups']);$i++){
				echo  $product['attribute_groups'][$i] . "<br />";
			  }?>
			  </td>-->
              <td class="text-left"  style="display: none;">
			  <?php for($j=$start_loop; $j<($start_loop + count($product['attribute_category']));$j++){
				if($product['attribute_category'][$j] != 'optionvalue')
				{
					echo  $product['attribute_category'][$j] . "<br />";
				}
			  } 
			  $start_loop = $j;
			  ?>
			  </td>
			  <td class="text-left"><?php echo  $product['quantity'];?></td>
			  <td class="text-left"><?php if($product['received_products'] == 0) echo ''; else echo $product['received_products'];?></td>
			  <td class="text-left remaining_quantity"><?php if($product['received_products'] == 0) echo $product['quantity'] - $product['received_products']; else echo $product['quantity'] - $product['received_products'];?></td>
			  <td class="text-left">
			  <?php if(isset($product['supplier_names'])){
				for($i=0; $i<count($product['supplier_names']); $i++)
				{					
			  ?>
			  <?php if($product['supplier_names'][$i] != "default ") echo $product['supplier_names'][$i] . "<br />"; else echo ""; ?>
			  <?php }} ?>
			  </td>
			  <td class="text-left">
			  <?php if(isset($product['quantities'])){
				for($i=0; $i<count($product['quantities']); $i++)
				{					
			  ?>
			  <?php if($product['quantities'][$i] == 0) echo ''; else echo $product['quantities'][$i] . "<br />"; ?>
			  <?php }} ?>
			  </td>
			  <td class="text-left">
			  <?php if(isset($product['prices'])){
				for($i=0; $i<count($product['prices']); $i++)
				{					
			  ?>
			  <?php if($product['prices'][$i] == 0) 
                          {
                              echo $product['prices_p'][$i] . "<br />";
                          }
                          else 
                          {
                          echo $product['prices'][$i] . "<br />";
                          
                          } ?>
			  <?php }} ?>
			  </td>
			  <td class="text-left">
			  <?php if(isset($product['prices'])){
				for($i=0; $i<count($product['prices']); $i++)
				{ //print_r($product);
					
			  ?>
			  <?php if((intval($product['prices'][$i]) * intval($product['quantity'])) == 0) 
                          {
                              echo (intval($product['prices_p'][$i]) * intval($product['quantity'])) . "<br />";
                              $grand_total += intval($product['prices_p'][$i]) * intval($product['quantity']);
                          }
                          else 
                          {
                              echo (intval($product['prices'][$i]) * intval($product['quantity'])) . "<br />";
                              $grand_total += intval($product['prices'][$i]) * intval($product['quantity']);
                          }
                          ?>
			  <?php }}
                          
                          
                           
                          ?>
			  </td>
			</tr>
		<?php
			}
		?>
			<tr>
				<td class="text-right" id="set_colspan" colspan="7"><b>Grand Total:</b></td>
				<td class ="text-left"><?php if($grand_total == 0) echo ''; else echo $grand_total; ?></td>
			</tr>
			<!--<tr>
				<td class="text-right" colspan="4"><b>Ordered By:</b></td>
				<td class="text-left"><?php echo $order_information['order_info']['firstname'] ." ".$order_information['order_info']['lastname']; ?></td>
			</tr>
			<tr>
				<td class="text-right" colspan="4"><b>Purchase order date:</b></td>
				<td class="text-left"><?php echo $order_information['order_info']['order_date']; ?></td>
			</tr>
			<tr>
				<td class="text-right" colspan="4"><b>Purchase order receive date:</b></td>
				<td class="text-left"><?php echo $order_information['order_info']['receive_date']; ?></td>
			</tr>-->
			</tbody>
        </table>
           <?php if(@$_GET['act']!='view'){ ?>
            
                                <?php if($order_information['order_info']['canceled_message']==''){ ?>
                                
                                <a onclick="return cancel_order_by_account(<?php echo $order_id; ?>);"  data-toggle="tooltip" title="<?php echo "Cancel Order"; ?>" style="margin-left: 5px;" class="btn btn-danger"><i class="fa fa-close"></i></a>
                                <?php } ?>
                                <?php if(($order_information['order_info']['pending_bit']!='0') && ($order_information['order_info']['canceled_message']=='')){ ?>
                                <a onclick="return confirm_order_by_account(<?php echo $order_id; ?>);"  data-toggle="tooltip" title="<?php echo "Approve Order"; ?>" style="margin-left: 5px;" class="btn btn-primary"><i class="fa fa-truck"></i></a>
            
                                    <?php } ?>            
                <?php } ?>         
                         
		<!--<a id="download_pdf" href="<?php echo $pdf_export . '&export=1'; ?>" target="_blank"><span class="input-group-btn">
			<button type="button" class="btn btn-primary pull-right"><?php echo "Download as pdf"; ?></button>
		</span></a>-->
		
                                
                            
	</div>
	
  </div>
  
</div>
<!-- Modal -->
  <div class="modal fade" id="myModal_partner" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" id="partner_cncl_btn2" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Tell us the reason, why you canceled this order ? </h4>
        </div>
        <div class="modal-body">
        <form action="index.php?route=tag/bills/reject&token=<?php echo $token; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" id="user_id" value="<?php echo $user_id; ?>" />
            <div class="form-group">
            <label for="input-username">Reason </label>
            <div class="input-group"><span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <textarea name="reject_Message" required id="reject_Message" class="form-control" placeholder="Reason of reject"></textarea>
            </div>
            </div>
           <div class="text-right">
               <img id="processing_image" src="https://unnati.world/shop/admin/view/image/processing_image.gif" style="height: 50px;width: 50px;display: none;">
               <input type="button" id="submit_btn"  class="btn btn-primary" 
                   
                      <?php if(($order_information['order_info']['order_status_id']!='6') && (($user_group_id=='1')|| ($user_group_id=='13')))
                       { ?> 
                      onclick="return canceled_by_account_with_msg(<?php echo $order_id; ?>);" 
                   <?php } ?>
                      value="Submit" />
                <button type="button" id="submt_cncl_btn" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </form>
        </div>
        <!--<div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>-->
      </div>
      
    </div>
  </div>


<script>
////////////////////////////////////////////////
function confirm_order(order_id)
{
    
    alertify.confirm('Are you sure ! you want to approve this order ?',
                function(e){ 
                    if(e){
        url = 'index.php?route=procurement/rm/confirm_order_by_rm&token=<?php echo $token; ?>';
	
        var filter_id = '<?php echo @$_GET['filter_id']; ?>';
	
	if (filter_id) {
		url += '&filter_id=' + encodeURIComponent(filter_id);
	}
        
        var filter_status = '<?php echo @$_GET['filter_status']; ?>';
	
	if (filter_status!="") {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
        var filter_date_start = '<?php echo @$_GET['filter_date_start']; ?>';
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	var filter_date_end = '<?php echo @$_GET['filter_date_end']; ?>';
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}	
        url += '&order_id=' + encodeURIComponent(order_id);
        var order_status_id = '<?php echo $order_information['order_info']['order_status_id']; ?>';
        url += '&order_status_id=' + encodeURIComponent(order_status_id); 
        var user_id = '<?php echo @$user_id; ?>';
        url += '&user_id=' + encodeURIComponent(user_id);
        var page = '<?php echo @$_GET['page']; ?>';
        url += '&page=' + encodeURIComponent(page);
	location = url;
                }else{
                    alertify.error('You canceled this action !'); 
                    return false;
                }
            }
                    
                        );
                 $("#alertify-ok").html('Continue'); 
                 //return false;
}
function cancel_order(order_id)
{
    
    alertify.confirm('Are you sure ! you want to cancel this order ?',
                function(e){ 
                    if(e){
                        $('#myModal_partner').modal('show');
                        return false;
                }else{
                    alertify.error('You canceled this action !'); 
                    return false;
                }
            }
                    
                        );
                 $("#alertify-ok").html('Continue'); 
                 //return false;
}
////////////////////////////////////////////////
function confirm_order_by_cm(order_id)
{
    
    var order_total='<?php echo $grand_total; ?>';
    var creditlimit='<?php echo $creditlimit; ?>';
    var currentcredit='<?php echo $currentcredit; ?>';
    var total_1=parseFloat(order_total)+parseFloat(currentcredit);
    if(total_1>creditlimit)
    {
        alertify.error('Order total is greater then Available credit Limit !'); 
        return false;
    }
    else
    {
    alertify.confirm('Are you sure ! you want to approve this order ?',
                function(e){ 
                    if(e){
        url = 'index.php?route=procurement/cm/confirm_order_by_cm&token=<?php echo $token; ?>&order_total='+order_total+'&creditlimit='+creditlimit+'&currentcredit='+currentcredit;
	
        var filter_id = '<?php echo @$_GET['filter_id']; ?>';
	
	if (filter_id) {
		url += '&filter_id=' + encodeURIComponent(filter_id);
	}
        
        var filter_status = '<?php echo @$_GET['filter_status']; ?>';
	
	if (filter_status!="") {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
        var filter_date_start = '<?php echo @$_GET['filter_date_start']; ?>';
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	var filter_date_end = '<?php echo @$_GET['filter_date_end']; ?>';
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}	
        url += '&order_id=' + encodeURIComponent(order_id);
        var order_status_id = '<?php echo $order_information['order_info']['order_status_id']; ?>';
        url += '&order_status_id=' + encodeURIComponent(order_status_id); 
        var user_id = '<?php echo @$user_id; ?>';
        url += '&user_id=' + encodeURIComponent(user_id);
        var page = '<?php echo @$_GET['page']; ?>';
        url += '&page=' + encodeURIComponent(page);
	location = url;
                }else{
                    alertify.error('You canceled this action !'); 
                    return false;
                }
            }
                    
                        );
                 $("#alertify-ok").html('Continue'); 
    }
                 //return false;
}
function cancel_order_by_cm(order_id)
{
    
    alertify.confirm('Are you sure ! you want to cancel this order ?',
                function(e){ 
                    if(e){
                        $('#myModal_partner').modal('show');
                        return false;
            
                        
                }else{
                    alertify.error('You canceled this action !'); 
                    return false;
                }
            }
                    
                        );
                 $("#alertify-ok").html('Continue'); 
                 //return false;
}
////////////////////////////////////////////////
function confirm_order_by_account(order_id)
{
    
    var order_total='<?php echo $grand_total; ?>';
    var creditlimit='<?php echo $creditlimit; ?>';
    var currentcredit='<?php echo $currentcredit; ?>';
    var total_1=parseFloat(order_total)+parseFloat(currentcredit);
    if(total_1>creditlimit)
    {
        alertify.error('Order total is greater then Available credit Limit !'); 
        return false;
    }
    else
    {
    alertify.confirm('Are you sure ! you want to approve this order ?',
                function(e){ 
                    if(e){
        url = 'index.php?route=procurement/account/receive_order&token=<?php echo $token; ?>&order_total='+order_total+'&creditlimit='+creditlimit+'&currentcredit='+currentcredit;
	
        var filter_id = '<?php echo @$_GET['filter_id']; ?>';
	
	if (filter_id) {
		url += '&filter_id=' + encodeURIComponent(filter_id);
	}
        
        var filter_status = '<?php echo @$_GET['filter_status']; ?>';
	
	if (filter_status!="") {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
        var filter_date_start = '<?php echo @$_GET['filter_date_start']; ?>';
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	var filter_date_end = '<?php echo @$_GET['filter_date_end']; ?>';
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}	
        url += '&order_id=' + encodeURIComponent(order_id);
        var order_status_id = '<?php echo $order_information['order_info']['order_status_id']; ?>';
        url += '&order_status_id=' + encodeURIComponent(order_status_id); 
        var user_id = '<?php echo @$user_id; ?>';
        url += '&user_id=' + encodeURIComponent(user_id);
        var page = '<?php echo @$_GET['page']; ?>';
        url += '&page=' + encodeURIComponent(page);
	location = url;
                }else{
                    alertify.error('You canceled this action !'); 
                    return false;
                }
            }
                    
                        );
                 $("#alertify-ok").html('Continue'); 
    }
                 //return false;
}
function cancel_order_by_account(order_id)
{
    
    alertify.confirm('Are you sure ! you want to cancel this order ?',
                function(e){ 
                    if(e){
                        $('#myModal_partner').modal('show');
                        return false;
                }else{
                    alertify.error('You canceled this action !'); 
                    return false;
                }
            }
                    
                        );
                 $("#alertify-ok").html('Continue'); 
                 //return false;
}
////////////////////////////////////////////
function canceled_by_rm_with_msg(order_id)
{
    var reject_Message=$('#reject_Message').val();
    if(reject_Message=="")
    {
        return false;
    }
    else
    {
    $("#processing_image").show();
    $("#submit_btn").hide();
    $("#submt_cncl_btn").hide();
    
    url = 'index.php?route=procurement/rm/cancel_order_by_rm&token=<?php echo $token; ?>';
	
        var filter_id = '<?php echo @$_GET['filter_id']; ?>';
	
	if (filter_id) {
		url += '&filter_id=' + encodeURIComponent(filter_id);
	}
        
        var filter_status = '<?php echo @$_GET['filter_status']; ?>';
	
	if (filter_status!="") {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
        var filter_date_start = '<?php echo @$_GET['filter_date_start']; ?>';
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	var filter_date_end = '<?php echo @$_GET['filter_date_end']; ?>';
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
        url += '&reject_Message=' + encodeURIComponent(reject_Message);
        url += '&order_id=' + encodeURIComponent(order_id);
        var order_status_id = '<?php echo $order_information['order_info']['order_status_id']; ?>';
        url += '&order_status_id=' + encodeURIComponent(order_status_id); 
        var user_id = '<?php echo @$user_id; ?>';
        url += '&user_id=' + encodeURIComponent(user_id);
        var page = '<?php echo @$_GET['page']; ?>';
        url += '&page=' + encodeURIComponent(page);
	location = url;
        return true;
    }
}
//////////////////////////////////////////
function canceled_by_cm_with_msg(order_id)
{
    var reject_Message=$('#reject_Message').val();
    if(reject_Message=="")
    {
        return false;
    }
    else
    {
    $("#processing_image").show();
    $("#submit_btn").hide();
    $("#submt_cncl_btn").hide();
    
    url = 'index.php?route=procurement/cm/cancel_order_by_cm&token=<?php echo $token; ?>';
	
        var filter_id = '<?php echo @$_GET['filter_id']; ?>';
	
	if (filter_id) {
		url += '&filter_id=' + encodeURIComponent(filter_id);
	}
        
        var filter_status = '<?php echo @$_GET['filter_status']; ?>';
	
	if (filter_status!="") {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
        var filter_date_start = '<?php echo @$_GET['filter_date_start']; ?>';
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	var filter_date_end = '<?php echo @$_GET['filter_date_end']; ?>';
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
        url += '&reject_Message=' + encodeURIComponent(reject_Message);
        url += '&order_id=' + encodeURIComponent(order_id);
        var order_status_id = '<?php echo $order_information['order_info']['order_status_id']; ?>';
        url += '&order_status_id=' + encodeURIComponent(order_status_id); 
        var user_id = '<?php echo @$user_id; ?>';
        url += '&user_id=' + encodeURIComponent(user_id);
        var page = '<?php echo @$_GET['page']; ?>';
        url += '&page=' + encodeURIComponent(page);
	location = url;
        return true;
    }
}
///////////////////////////////////////////////
function canceled_by_account_with_msg(order_id)
{
    var reject_Message=$('#reject_Message').val();
    if(reject_Message=="")
    {
        return false;
    }
    else
    {
    $("#processing_image").show();
    $("#submit_btn").hide();
    $("#submt_cncl_btn").hide();
    
    url = 'index.php?route=procurement/account/cancel_order_by_account&token=<?php echo $token; ?>';
	
        var filter_id = '<?php echo @$_GET['filter_id']; ?>';
	
	if (filter_id) {
		url += '&filter_id=' + encodeURIComponent(filter_id);
	}
        
        var filter_status = '<?php echo @$_GET['filter_status']; ?>';
	
	if (filter_status!="") {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
        var filter_date_start = '<?php echo @$_GET['filter_date_start']; ?>';
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	var filter_date_end = '<?php echo @$_GET['filter_date_end']; ?>';
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
        url += '&reject_Message=' + encodeURIComponent(reject_Message);
        url += '&order_id=' + encodeURIComponent(order_id);
        var order_status_id = '<?php echo $order_information['order_info']['order_status_id']; ?>';
        url += '&order_status_id=' + encodeURIComponent(order_status_id); 
        var user_id = '<?php echo @$user_id; ?>';
        url += '&user_id=' + encodeURIComponent(user_id);
        var page = '<?php echo @$_GET['page']; ?>';
        url += '&page=' + encodeURIComponent(page);
	location = url;
        return true;
    }
}
</script>

<script type="text/javascript">
	function print_order()
	{
		var prtContent = document.getElementById("print_div");
		var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
		
		WinPrint.document.writeln('<!DOCTYPE html>');
        WinPrint.document.writeln('<html><head><title></title>');
        WinPrint.document.writeln('<style>table{border:1px; border-collapse:collapse;}');
        WinPrint.document.writeln('table, td, th {border: 1px solid black;}');
		WinPrint.document.writeln('label{font-weight:bold}');
		WinPrint.document.writeln('.text-right{text-align:right;}');
		WinPrint.document.writeln('.remaining_quantity{display:none;}');
		document.getElementById('set_colspan').setAttribute('colspan','7');
		WinPrint.document.writeln('</style></head><body>');
		WinPrint.document.write(prtContent.innerHTML);
		WinPrint.document.writeln('</body></html>');
		WinPrint.document.close();
		WinPrint.focus();
		WinPrint.print();
		WinPrint.close();
	}
	
	function download_pdf()
	{
		var doc = new jsPDF();
		doc.fromHTML($('#print_div').get(0),20,20,{
			'width':5000
		});
		doc.save('test.pdf');
	}
	
	function print_order()
	{
		document.getElementById("download_pdf").style.display = "none";
        var printContents = document.getElementById('print_div').innerHTML;
        var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
		document.getElementById("download_pdf").style.display = "block";
    }
	
	
	function demoFromHTML() {
    var pdf = new jsPDF('l', 'pt', 'letter',true);
	//pdf.setFontSize(8);
	source = $('#print_div')[0];
	specialElementHandlers = {
        '#bypassme': function (element, renderer) {
            return true
        }
    };
    margins = {
        top: 100,
        bottom: 80,
        left: 80,
        width:1000
    };
    pdf.fromHTML(
    source,
    margins.left,
    margins.top, {
        'width': margins.width,
        'elementHandlers': specialElementHandlers
    },

    function (dispose) {
        pdf.save('Test.pdf');
    }
	);
}
</script>
<?php echo $footer; ?>