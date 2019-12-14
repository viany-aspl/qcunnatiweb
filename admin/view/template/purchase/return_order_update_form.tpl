<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="button" onclick="checkQuantity(1)" form="form-return" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if (isset($_SESSION['error_warning'])) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $_SESSION['error_warning']; unset($_SESSION['error_warning']); ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i><?php echo $text_order_returns; ?></h3>
      </div>
	  <div class="panel-body">
		<form action="<?php echo $update_return_order; ?>" method="post" enctype="multipart/form-data" id="form-return" class="form-horizontal">
			<div class="form-group required">
                <label class="col-sm-2 control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="order_id" disabled="disabled" value="<?php if(isset($order_id)) echo $order_id;?>" placeholder="<?php echo $entry_order_id; ?>" onblur="loadProducts()" id="input-order-id" class="form-control" />
				<input type="hidden" name="order_id" value="<?php if(isset($order_id)) echo $order_id;?>" placeholder="<?php echo $entry_order_id; ?>" onblur="loadProducts()" id="input-order-id" class="form-control" />
					<?php if (isset($_SESSION['error_order_id'])) { ?>
                          <div class="text-danger"><?php echo $_SESSION['error_order_id']; unset($_SESSION['error_order_id']); ?></div>
                   <?php  } ?>
				</div>
            </div>
			<div class="form-group required">
				<label class="col-sm-2 control-label" for="input-product"><?php echo $entry_product; ?></label>
                <div class="col-sm-10">
					<select class="form-control" disabled="disabled" id="product" name="product" onchange="loadSuppliers()">
						<option><?php echo $entry_product; ?></option>
						<?php
							if(isset($product))
							{
								foreach($products as $prdct)
								{
						?>
						<option value="<?php echo $prdct['id'];?>"<?php if($prdct['id'] == $product){?>selected<?php } ?>><?php echo $prdct['name']; ?></option>
						<?php if($prdct['id'] == $product){?>
						
						<!--<input type="hidden" name="product" value="<?php echo $prdct['id'];?>">-->
							<?php $product_id = $prdct['id']; ?>
						
						<?php 		}
								}
							}
							if(isset($product_id))
							{
						?>
							<input type="hidden" name="product" value="<?php echo $product_id;?>">
						<?php
							}
						?>
					</select>
					<?php if (isset($_SESSION['product_error'])) { ?>
                          <div class="text-danger"><?php echo $_SESSION['product_error']; unset($_SESSION['product_error']); ?></div>
                   <?php  } ?>
                </div>
			</div>
			<div class="form-group required">
				<label class="col-sm-2 control-label" for="input-supplier"><?php echo $entry_supplier; ?></label>
                <div class="col-sm-10">
					<select class="form-control" disabled id="append_supplier" name="supplier">
						<option><?php echo $entry_supplier; ?></option>
						<?php
							if(isset($supplier)){
						?>
						<?php
								foreach($suppliers as $splr)
								{
						?>
						<option value="<?php echo $splr['id'];?>"<?php if($splr['id'] == $supplier){?>selected<?php } else{ ?>style="display:none;"<?php } ?>><?php echo $splr['first_name'] . ' ' . $splr['last_name']; ?></option>
						<?php if($splr['id'] == $supplier){?>
						
						<input type="hidden" name="supplier" value="<?php echo $splr['id']; ?>">
						
						<?php } ?>
						<?php
								}
							}
						?>
					</select>
					<?php if (isset($_SESSION['supplier_error'])) { ?>
                          <div class="text-danger"><?php echo $_SESSION['supplier_error']; unset($_SESSION['supplier_error']); ?></div>
                   <?php  } ?>
                </div>
			</div>
			<div class="form-group required">
                <label class="col-sm-2 control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
                <div class="col-sm-10">
                  <input type="text" id="return_quantity" name="return_quantity" value="<?php if(isset($return_quantity)) echo $return_quantity;?>" placeholder="<?php echo $entry_quantity; ?>" id="input-order-id" class="form-control" />
				  <?php if (isset($_SESSION['quantity_error'])) { ?>
                          <div class="text-danger"><?php echo $_SESSION['quantity_error']; unset($_SESSION['quantity_error']); ?></div>
                   <?php  } ?>
				</div>
            </div>
			<div class="form-group required">
                <label class="col-sm-2 control-label" for="input-reason"><?php echo "Reason"; ?></label>
                <div class="col-sm-10">
                  <input type="text" id="reason" disabled name="reason" value="<?php if(isset($reason)) echo $reason;?>" placeholder="<?php echo "Reason"; ?>" id="input-order-id" class="form-control" />
				  <?php if (isset($_SESSION['reason_error'])) { ?>
                          <div class="text-danger"><?php echo $_SESSION['reason_error']; unset($_SESSION['reason_error']); ?></div>
                   <?php  } ?>
				</div>
            </div>
		</form>
	  </div>
  </div>
</div>
<script type="text/javascript">
	function loadProducts()
	{
		var order_id = $('#input-order-id').val();
		if(!order_id)
		{
			alert("Please! enter the valid order no");
			$('#input-order-id').focus();
			return false;
		}
		
		$(document).ready(function()
		{
			$.ajax({
				url: 'index.php?route=purchase/return_orders/getProducts&token=<?php echo $token; ?>&order_id=' + order_id,
				type: 'post',
				dataType: 'json',
				data: 'order_id=' + order_id,
				success: function(json) {
					if(json == "nothing")
					{
						$('#product').children().nextAll().remove();	
						alert("This order no. does not exist");
					}
					else
					{
						$('#product').children().nextAll().remove();	
						var html = '';
						for(var key in json)
						{
							html += '<option value =' +json[key].id+ '>' + json[key].name + '</option>';
						}
						$('#product').append(html);	
					}
				}
			});
		});
	}
	
	
	function loadSuppliers()
	{
		var order_id = $('#input-order-id').val();
		var product_id = $('#product').val();
		$(document).ready(function()
		{
			$.ajax({
				url: 'index.php?route=purchase/return_orders/getSuppliers&token=<?php echo $token; ?>',
				type: 'post',
				dataType: 'json',
				data: 'order_id=' + order_id + '&product_id=' + product_id,
				success: function(json) {
					$('#append_supplier').children().nextAll().remove();
					var html = '';
						for(var key in json)
						{
							html += '<option value =' +json[key].id+ '>' + json[key].first_name + ' ' + json[key].last_name + '</option>';
						}
						$('#append_supplier').append(html);
				}
			});
		});
	}
	
	function checkQuantity(eventon)
	{
		var order_id = $('#input-order-id').val();
		var product_id = $('#product').val();
		var supplier_id = $('#append_supplier').val();
		var return_quantity = $('#return_quantity').val();
		if(order_id && product_id && supplier_id && return_quantity && product_id != "Product" && supplier_id != "Supplier")
		{
			$(document).ready(function()
			{
				$.ajax({
					url: 'index.php?route=purchase/return_orders/checkUpdateQuantity&token=<?php echo $token; ?>',
					type: 'post',
					dataType: 'json',
					data: 'order_id=' + order_id + '&product_id=' + product_id + '&supplier_id=' + supplier_id,
					success: function(received_quantity) {
						if(received_quantity < return_quantity)
						{
							alert('The present products are '+received_quantity+' in order but return quantity '+return_quantity+' is greater than the present');
							$('#return_quantity').focus();
						}
						else
						{
							$('#form-return').submit();
						}
					}
				});
			});
		}
		else
		{
			if(eventon ==1)
			{
				$('#form-return').submit();
			}
		}
	}
</script>
<?php echo $footer; ?>