<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
    <div class="container-fluid">
	<?php if (isset($_SESSION['empty_fields_error'])) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $_SESSION['empty_fields_error']; unset($_SESSION['empty_fields_error']);?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if (isset($_SESSION['receive_success_message'])) {		?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $_SESSION['receive_success_message']; unset($_SESSION['receive_success_message']);?>
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
              <!--<td class="text-left" style="width: 25%; visibility:hidden;">Attribute Group</td>-->
	      <!--<td class="text-left" style="20%">Option Values</td>-->
			  <td class="text-left" style="width:20%;">Quantity</td>
			  <td class="text-left" style="widht:20%">Supplier</td>
			  <td class="text-left" style="widht:20%">Remaining Quantity</td>
            </tr>
          </thead>
          <tbody>
          <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>" />
		  <?php //echo $order_information['order_info']['order_sup_send'];
			$start_loop = 0;
			foreach($order_information['products'] as $product)
			{ //print_r($product);
		  ?>
            <tr>
              <td class="text-left" style="width: 20%;"><?php echo  $product['name'];?></td>
			  <!--<td class="text-left" style="visibility:hidden;">
			  <?php for($i=0; $i<count($product['attribute_groups']);$i++){
				echo  $product['attribute_groups'][$i] . "<br />";
			  }?>
			  </td>
			  <td class="text-left" style="width: 20%;">
			  <?php for($j=$start_loop; $j<($start_loop + count($product['attribute_category']));$j++){
				  if($product['attribute_category'][$j] != 'optionvalue')
				  {
					echo  $product['attribute_category'][$j] . "<br />";
				}
			  } 
			  $start_loop = $j;
			  ?>
			  </td>-->
			  <td class="text-left quantity" style="width: 20%;"><?php echo  $product['quantity'];?></td>
			  <td class="text-left" style="width: 20%;">
			  <?php if(isset($product['quantities'])){?>
			  <?php for($i = 0; $i<count($product['quantities']); $i++){?>
				<select class="form-control" name="supplier[]" <?php if((!isset($validation_bit) && $product['quantities'][$i]!=0) || $disable_bit){?> disabled <?php } ?>>
				<?php foreach($suppliers as $supplier){ ?>
					<option value="<?php echo $supplier['id']; ?>" <?php if($product['suppliers'][$i] == $supplier['id']){?> selected="selected" <?php } ?>><?php echo $supplier['first_name'] . " " . $supplier['last_name']; ?></option>
				<?php } ?>
				</select>
				<?php 
						if((!isset($validation_bit) && $product['quantities'][$i]!=0) || isset($disable_bit))
						{
							foreach($suppliers as $supplier){
								if($product['suppliers'][$i] == $supplier['id']){
					?>
							<input type ="hidden" name="supplier[]" value="<?php echo $supplier['id']; ?>">
					<?php
								}
							}
					?>
							<input type="hidden" name="disable_bit" value="1">
					<?php
						}
					?>
				<?php //echo $order_info['receive_bit']; exit;?>
				<?php if(!isset($validation_bit)){ ?>
			  <input type="text" style ="float:left;width:50%;" <?php if($product['quantities'][$i] == 0){?> onblur="getRemainingQuantity(this)" <?php } else{?>onblur="getRQuantity(this)"<?php } ?> name="receive_quantity[]" value="<?php if($product['quantities'][$i] == 0){  echo '' . '"'; }elseif($product['prices'][$i] != 0){ echo $product['quantities'][$i];?>" disabled <?php }elseif($product['quantities'][$i] != 0){ echo $product['quantities'][$i] . '"'; } ?> placeholder="Receive Quantity" class="form-control receive_quantity" /><input type="text" style ="width:50%;" name="price[]" value="<?php if($product['prices'][$i] == 0) echo '' . '"'; else{ echo $product['prices'][$i];?>" disabled <?php } ?> placeholder="Price" class="form-control price" /><?php echo "<br />"; ?>
				<?php 
				}
				else
				{
				?>
					<input type="text" style ="float:left;width:50%;" <?php if(!isset($disable_bit)){?> onblur="getRemainingQuantity(this)"  <?php }else{?>onblur="getRQuantity(this)"<?php } ?>name="receive_quantity[]" value="<?php if(isset($product['quantities'][$i])){ echo $product['quantities'][$i]; } ?>" placeholder="Receive Quantity" class="form-control receive_quantity" /><input type="text" style ="width:50%;" name="price[]" value="<?php if(isset($product['prices'][$i])){ echo $product['prices'][$i];?>"<?php } ?> placeholder="Price" class="form-control price" /><?php echo "<br />"; ?> 
				
			<?php
				
				} 
				
				?>
			<?php	
			  } 
			  }
			  else
			  {
				  ?>
				<select class="form-control" name="supplier[]">
				<?php foreach($suppliers as $supplier){ ?>
					<option value="<?php echo $supplier['id']; ?>"><?php echo $supplier['first_name'] . " " . $supplier['last_name']; ?></option>
				<?php } ?>
				</select>
			  <input type="text" style ="float:left;width:50%;" onblur="getRemainingQuantity(this)" name="receive_quantity[]" value="<?php if($product['received_products'] == 0){ echo ''; } else echo $product['received_products'];?>" placeholder="Receive Quantity" class="form-control receive_quantity" /><input type="text" style ="width:50%;" name="price[]" value="" placeholder="Price" class="form-control price" />
			  <?php
			  }
			  ?></td>
			  <td class="text-left remaining_quantity" style="width: 20%;"><?php if($product['received_products'] == 0) echo ''; else echo $product['quantity'] - $product['received_products'];?><span id ="remaining_quantity"><?php if(!isset($receive_bit)){ if(isset($product['rq'])) echo $product['rq']; ?></span> | <span><button onclick="skipQuantity(this)" class="btn btn-primary" type="button">Skip Quantity</button></span><input type="hidden" class="rq" name="remaining_quantity[]" value="<?php if(isset($product['rq'])) echo $product['rq']; ?>"><?php } ?></td>
			  <input type="hidden" value="next product" name="receive_quantity[]">
			  <input type="hidden" value="next product" name="supplier[]">
			  <input type="hidden" value="next product" name="price[]">
			</tr>
			<input type ="hidden" name="product_id[]" value="<?php echo $product['product_id']; ?>">
		<?php
			
			}
		?>
			<tr>
				<td class="text-right" colspan="3" style="width: 80%;"><b>Ordered By:</b></td>
				<td class="text-left" style="width: 20%;"><?php echo $order_information['order_info']['firstname'] ." ".$order_information['order_info']['lastname']; ?></td>
			</tr>
			<tr>
				<td class="text-right" colspan="3" style="width: 80%;"><b>Date Added:</b></td>
				<td class="text-left" style="width: 20%;"><?php echo $order_information['order_info']['order_date']; ?></td>
			</tr>
			<tr>
				<td class="text-right" colspan="3" style="width: 80%;"><b>Order Confirm Date:</b></td>
				<td class="text-left" style="width: 20%;"><div class="input-group date">
				<?php if(isset($ftime_bit)){
					?>
					<input onkeypress="return false;" type="text" name="order_receive_date" value="<?php if($order_information['order_info']['order_sup_send'] == '0000-00-00'){ echo ''.'"'; }else{ echo $order_information['order_info']['order_sup_send']; ?>" disabled<?php } ?> placeholder="Order confirm Date" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                <?php }else{ 
				?>
                                        <input onkeypress="return false;" type="text" name="order_receive_date" value="<?php if($order_information['order_info']['order_sup_send'] == '0000-00-00'){ echo ''.'"'; }else{ echo $order_information['order_info']['order_sup_send']; ?>"<?php } ?> <?php if(!isset($validation_bit)){?> disabled <?php }?> placeholder="Order confirm Date" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                <?php } ?>
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div></td>
			</tr>
			<?php //echo $product['received_products']; 
                        if($product['received_products'] == 0){?>
			<tr>
			<td class="text-right" colspan="5" style="width: 100%;"><span class="input-group-btn">
                          <button <?php if($order_information['order_info']['order_sup_send']!='0000-00-00'){ ?> disabled="disabled" <?php } ?> type="button" id="button-filter" class="btn btn-primary pull-right" onclick="submit_form()"><!--<i class="fa fa-search"></i>--> <?php echo "Confirm Requisition"; ?></button>
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
	
	window.skipQuantity = function(evnt){
		var quantity = parseInt($(evnt).parent().parent().prev().prev().text());
		var remaining_quantity = parseInt($(evnt).parent().prev().text());
		
		var receive_quantity = quantity - remaining_quantity;
		$(evnt).parent().prev().text('0');
		var rq = 0;
		$(evnt).parent().parent().prev().children('input.receive_quantity').each(function(){
			
			if($(this).val())
			{
				rq = rq + parseInt($(this).val());
			}
			if(receive_quantity == rq)
			{
				$(this).next().nextAll().remove();
				return false;
			}
		});
	}
	
</script>
<script type="text/javascript">
	function getRQuantity(evnt)
	{
		var quantity = parseInt($(evnt).parent().prev().text());
		var receive_quantity = parseInt($(evnt).val());
		if(receive_quantity>quantity)
		{
                        $(evnt).val('');
			alertify.error("Receive quantity should be less than the quantity");
		}
		else
		{
			var remaining_quantity = quantity - receive_quantity;
			$(evnt).parent().next().children('#remaining_quantity').text(remaining_quantity);
			$(evnt).parent().next().children('input.rq').val(remaining_quantity);
		}
	}
	function getRemainingQuantity(evnt)
	{
		/*if(!$(evnt).val())
		{
			alert("The quantity field must be filled");
			$(evnt).focus();
			return false;
		}*/
		if($(evnt).val())
		{
			var quantity = parseInt($(evnt).parent().prev().text());
			var receive_quantity = 0;
			var remaining_quantity = 0;
			var no_value = '';
			$(evnt).parent().children('input.receive_quantity').each(function(){
				if($(this).val())
				{
					receive_quantity += parseInt($(this).val());
				}
				if(receive_quantity >= quantity)
				{
					$(evnt).next().nextAll().remove();
					return false;
				}
			});
			if(receive_quantity > quantity)
			{
                                 $(evnt).val('');
				alertify.error("Receive quantity should be less than the quantity");
				$(evnt).focus();
				return false;
			}
			else
			{
				
				remaining_quantity = quantity - receive_quantity;
			}
			
			if(remaining_quantity > 0)
			{
				$(evnt).next().nextAll().remove();
				var html = '';
					html += '<br />';
					html +='<select class="form-control" name="supplier[]">';
				<?php foreach($suppliers as $supplier){ ?>
					html += '<option value="<?php echo $supplier['id']; ?>"><?php echo $supplier['first_name'] . " " . $supplier['last_name']; ?></option>';
				<?php } ?>
					html += '</select>';
					html += '<input type="text" style="float:left;width:50%" onblur="getRemainingQuantity(this)" name="receive_quantity[]" value="" placeholder="Receive Quantity" class="form-control receive_quantity" /><input type="text" style ="width:50%;" name="price[]" value="" placeholder="Price" class="form-control price" />';
					$(evnt).parent().next().children('#remaining_quantity').text(remaining_quantity);
					$(evnt).parent().next().children('input.rq').val(remaining_quantity);
					$(evnt).parent().append(html);
			}
			else if(remaining_quantity == 0)
			{
				//$(evnt).parent().next().text(remaining_quantity);
				$(evnt).parent().next().children('#remaining_quantity').text(remaining_quantity);
				$(evnt).parent().next().children('input.rq').val(remaining_quantity);
			}
		}
		else{
			return false;
		}
	}
	
</script>
<script type="text/javascript">
	function submit_form()
	{ var dt=$("#input-date-added").val();
		if(dt=="")
                { //alert ('empty');
		 return false;
                }
                else
                {
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
