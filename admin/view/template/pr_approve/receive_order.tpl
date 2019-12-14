<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
    <div class="container-fluid">
	<?php if (isset($error_warning)) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if (isset($success)) {		?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
      <div class="pull-right"><a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo "Cancel Button"; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo "Confirm Requisition"; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-info-circle"></i>
			<?php echo "Requisition  # " . $order_information['order_info']['id']; ?>
		</h3>
	</div>
	<div class="panel-body">
	<form action="<?php echo $action . "&order_id=".$order_id; ?>" method="post" enctype="multipart/form-data" id="form-order-receive" class="form-horizontal">
		<table class="table table-bordered">
          <thead>
            <tr>
              <td class="text-left" style="width: 20%;">Product Name</td>
              
	      <td class="text-left" style="width:20%;">Quantity</td>
	      <td class="text-left" style="widht:20%">Supplier</td>
	      <!--<td class="text-left" style="widht:20%">Remaining Quantity</td>-->
            </tr>
          </thead>
          <tbody>
          <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>" />
		  <?php //echo $order_information['order_info']['order_sup_send'];
			$start_loop = 0;
                        $a=1;
			foreach($order_information['products'] as $product)
			{ //print_r($product);
		  ?>
            <tr>
              <td class="text-left" style="width: 20%;"><?php echo  $product['name'];?></td>
			  
			 
			  
			  <td class="text-left quantity" style="width: 20%;"><?php echo  $product['quantity'];?></td>
			  <td class="text-left" style="width: 20%;">
			  
                              <select class="form-control" id="ware_house_<?php echo $a; ?>" name="ware_houses[]">
                                    <option value="">SELECT SUPPLIER</option>
                                    <?php foreach($suppliers as $supplier){ ?>
					<option value="<?php echo $supplier['id']; ?>" ><?php echo $supplier['first_name']." ".$supplier['last_name']; ?></option>
				<?php } ?>
				</select>
				
				<?php //echo $order_info['receive_bit']; exit;?>
				
			  <input type="text" id="product_sent_quantity_<?php echo $a; ?>" onkeypress="return onlynumeric(event);" onkeyup="return check_quantity(this.value,<?php echo $a; ?>,event);" style =""  name="receive_quantity[]" value="<?php echo  $product['quantity'];?>" placeholder="Receive Quantity" class="form-control receive_quantity" />
                         
			</td>
			  
			</tr>
			<input type ="hidden" name="product_id[]" value="<?php echo $product['product_id']; ?>">
                        <input type ="hidden" id="product_requested_quantity_<?php echo $a; ?>" name="product_requested_quantity[]" value="<?php echo  $product['quantity'];?>">
		<?php
			$a++;
			}
		?>
			<tr>
				<td class="text-right" colspan="2" style="width: 80%;"><b>Ordered By:</b></td>
				<td class="text-left" style="width: 20%;"><?php echo $order_information['order_info']['firstname'] ." ".$order_information['order_info']['lastname']; ?></td>
			</tr>
			<tr>
				<td class="text-right" colspan="2" style="width: 80%;"><b>Date Added:</b></td>
				<td class="text-left" style="width: 20%;"><?php echo $order_information['order_info']['order_date']; ?></td>
			</tr>
			<tr>
				<td class="text-right" colspan="2" style="width: 80%;"><b>Order Confirm Date:</b></td>
				<td class="text-left" style="width: 20%;"><div class="input-group date">
			<?php //echo $order_receive_date; ?>	
                  <input onkeypress="return false;" type="text" name="order_receive_date" value="<?php if($order_receive_date!="") { echo $order_receive_date.'"'; } else if($order_information['order_info']['order_sup_send'] == '0000-00-00'){ echo ''.'"'; } else{ echo $order_information['order_info']['order_sup_send']; ?>" disabled<?php } ?> placeholder="Order confirm Date" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div></td>
			</tr>
			<?php //echo $product['received_products']; 
                        if($product['received_products'] == 0){?>
			<tr>
			<td class="text-right" colspan="5" style="width: 100%;"><span class="input-group-btn">
                          <button  <?php if($order_information['order_info']['order_sup_send']!='0000-00-00'){ ?> disabled="disabled" <?php } ?> type="button" id="button-filter" class="btn btn-primary pull-right" onclick="submit_form()">
                           <?php echo "Confirm Requisition"; ?>
                          </button>
                          <img id="cr_img" src="http://www.danubis-dcm.org/Content/Images/processing.gif" style="float: right;height: 60px;display: none;"/>
                        
                         </span>
                        </td>
			</tr>
			<?php } ?>
          </tbody>
        </table>
		</form>
	</div>
  </div>
</div>


<script type="text/javascript">
function onlynumeric(evt)
{
    
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode == 46)
    {
             
    return true;
    }
    if (charCode > 31 && (charCode < 48 || charCode > 57))
    {
             return false;
    }
          
    
}  
function check_quantity(valuee,a,evt)
{
    var valuee=parseInt(valuee);
    var product_requested_quantity=parseInt($("#product_requested_quantity_"+a).val());
          
    if(valuee==0)
    {
    $("#product_sent_quantity_"+a).val('');
    }
    //alert(product_requested_quantity+','+valuee);
    if(valuee > product_requested_quantity)
    {
        alertify.error('Sent quantity can not be greater then requested quantity');
        $("#product_sent_quantity_"+a).val('');
        
    }
    return false;
}
function submit_form()
{ 
    <?php 
        $a=1;
	foreach($order_information['products'] as $product)
	{ 
    ?>
           var product_sent_quantity=$("#product_sent_quantity_"+<?php echo $a; ?>).val();
           var ware_house=$("#ware_house_"+<?php echo $a; ?>).val();
           if(product_sent_quantity=="")
           {
              alertify.error('Please enter Sent quantity '); 
              $("#product_sent_quantity_"+<?php echo $a; ?>).focus();
              return false;
           }
           if(ware_house=="")
           {
              alertify.error('Please Select Supplier '); 
              $("#ware_house_"+<?php echo $a; ?>).focus();
              return false;
           }
           
    var form=$("#form-order-receive");
/*
    var url = 'index.php?route=purchase/purchase_order/check_ware_house_quantity&token=<?php echo $token; ?>';
    var formData = $(form).serializeArray();
    $.post(url, formData).done(function (data) { 
        if(data!="")
        {
           alertify.error(data); 
           return false;
           //form.submit();
        }
        else
        {
	    //$("#cr_btn").hide();
	    //$("#cr_img").show();
            //form.submit();
            //return true;
            //return false;
        }
    });
	*/
    <?php $a++; } ?>
                var dt=$("#input-date-added").val();
		if(dt=="")
                { 
                    alertify.error('Please Select date '); 
                    $("#input-date-added").focus();
                    return false;
                }
                else
                {
                  $("#button-filter").hide();  
                  $("#cr_img").show();
                  $('#form-order-receive').submit();
                }
}
</script>
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>
<?php echo $footer; ?> 
