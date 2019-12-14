<?php echo $header; ?><?php echo $column_left;?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo "Add Supplier"; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo "Delete"; ?>" class="btn btn-danger" onclick="confirm('<?php echo "Are you sure, you want to delete the supplier"; ?>') ? $('#form-customer').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo $sale_chart_heading_text; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if (isset($error_warning)) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if (isset($_SESSION['offer_unsccess'])) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $_SESSION['offer_unsccess']; unset($_SESSION['offer_unsccess']); ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if (isset($_SESSION['applied_sccess'])) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $_SESSION['applied_sccess']; unset($_SESSION['applied_sccess']); ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if (isset($_SESSION['delete_success_message'])) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $_SESSION['delete_success_message']; unset($_SESSION['delete_success_message']); ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if (isset($_SESSION['update_success_message'])) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $_SESSION['update_success_message']; unset($_SESSION['update_success_message']); ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $sale_chart_heading_text; ?></h3>
      </div>
      <div class="panel-body">
	  <form action = "<?php echo $filter; ?>" method="post" enctype="multipart/form-data" id ="export-form">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-category"><?php echo $select_categor_text; ?></label>
                <select name="filter_category" id="input-category" class="form-control">
                  <option value="">--Category--</option>
				  <?php
					foreach($categories as $category)
					{
				?>
						<option <?php if(isset($filter_category)){if($filter_category == $category['name']){?>selected = "selected"<?php } } ?>><?php echo $category['name']; ?></option>
				<?php
					}
				  ?>
                </select>
              </div>
            </div>
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-option"><?php echo $select_option_text; ?></label>
                <select onchange="loadRelatedOptionValues(this)" name="filter_option" id="input-option" class="form-control">
                  <option value="">--Option--</option>
				  <?php
					foreach($options as $option)
					{
				?>
						<option value="<?php echo $option['option_id']; ?>"<?php if(isset($filter_option)){ if($filter_option == $option['option_id']){ ?>selected="selected"<?php } } ?>><?php echo $option['name']; ?></option>
				<?php
					}
				  ?>
                </select>
              </div>
            </div>
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-option-value"><?php echo $select_option_value_text; ?></label>
                <select name="filter_option_value" id="input-option-value" class="form-control">
					<?php if(isset($filter_option_value)){
					?>
						<option value="">--Option Values--</option>
					<?php
						foreach($option_values as $option_values)
						{
							if($filter_option_value == $option_values['name'])
							{
					?>
								<option selected="selected"><?php echo $option_values['name'];?></option>
					<?php
							}
							else
							{
					?>
								<option><?php echo $option_values['name'];?></option>
					<?php
											
							}
						}
					}?>
				</select>
              </div>
            </div>
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-sold-less"><?php echo $sold_less_text; ?></label>
                <input type="text" value="<?php if(isset($filter_sold_less)){ echo $filter_sold_less; }?>" name="filter_sold_less" placeholder="<?php echo $sold_less_text; ?>" id="input-soll-less" class="form-control" />
              </div>
            </div>
			<!--<div class="col-sm-6">
				<div class="form-group">
					<input type="checkbox" name="filter_all_products" value="1"/><label style="margin-left:10px;"class="control-label" for="input-sold-less"><?php echo $all_products_text; ?></label>
                </div>
			</div>-->
			
            <div class="col-sm-12">
				<button type="submit" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo "Filter"; ?></button>
			    <button style="margin-right:10px;" onclick="reset_form()" type="button" id="clear-filter" class="btn btn-primary pull-right"> <?php echo $button_clear; ?></button>
            </div>
          </div>
        </div>
		</form>
        <form action="<?php echo $offer_sale; ?>" method="post" enctype="multipart/form-data" id="form-discount">
          <div class="row">
			  <div class="col-sm-3" style="float:right;">
              <div class="form-group">
                <label class="control-label" for="discount"><?php echo "Discount"; ?></label>
                <div class="input-group">
                  <input type="text" name="discount"  placeholder="Discount" id="input-discount" class="form-control" />
				  <span class="input-group-btn">
                    <button type="button" id="button-discount" class="btn btn-primary pull-right" onclick="$('#form-discount').submit()"> <?php echo "Offer"; ?></button>
            </span></div>
              </div>
            </div>
			  
		  </div>
		  <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
			    <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><a href="#"><?php echo $column_product_name; ?></a></td>
                  <td class="text-left"><a href="#"><?php echo $column_category;  ?></a></td>
                  <td class="text-left"><a href="#"><?php echo $column_option_value; ?></a></td>
                  <td class="text-left"><a href="#"><?php echo $column_stock; ?></a></td>
                  <td class="text-right"><a href="#"><?php echo $column_sold; ?></a></td>
                </tr>
			</thead>
              <tbody>
			  <?php if(isset($products) && count($products) > 0){
				  
				  
						foreach($products as $product)
						{
							if(isset($filter_sold_less)){
								
								if($product['order_quantity'] < $filter_sold_less){
			?>
									
									
									<tr>
										<td class="text-left"><input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" /></td>
										<td><?php echo $product['product_name']; ?></td>
										<?php if(isset($product['category'])){?>
										<td>
										<?php for($i=0; $i<count($product['category']); $i++){?>
										<?php echo $product['category'][$i] . "<br />"; ?>
										<?php } ?>
										</td>
										<?php } ?>
										<td>
										<?php if(isset($product['option_value'])){?>
										<?php for($j=0; $j<count($product['option_value']); $j++){?>
										<?php echo $product['option_value'][$j] . " "; ?>
										<?php } ?>
										<?php }else{ echo '';} ?>
										</td>
										<td><?php echo $product['stock_quantity']; ?></td>
										<td><?php  echo $product['order_quantity']; ?> </td>
								  </tr>
									
									
			<?php
								}
							
							}
							else
							{
								?>
								
								<tr>
									<td class="text-left"><input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" /></td>
									<td><?php echo $product['product_name']; ?></td>
									<?php if(isset($product['category'])){?>
									<td>
									<?php for($i=0; $i<count($product['category']); $i++){?>
									<?php echo $product['category'][$i] . "<br />"; ?>
									<?php } ?>
									</td>
									<?php } ?>
									<td>
									<?php if(isset($product['option_value'])){?>
									<?php for($j=0; $j<count($product['option_value']); $j++){?>
									<?php echo $product['option_value'][$j] . " "; ?>
									<?php } ?>
									<?php }else{ echo '';} ?>
									</td>
									<td><?php echo $product['stock_quantity']; ?></td>
									<td><?php  echo $product['order_quantity']; ?> </td>
							  </tr>
								<?php
							}
			?>
						
			<?php 
						}
					} 
			
			?>
			</tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php //echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php //echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script>
  function loadRelatedOptionValues(evnt)
{
	$(document).ready(function()
    {
		var option_id = $(evnt).val();
		$.ajax({
		url: 'index.php?route=purchase/purchase_order/getRelatedOptionValues&token=<?php echo $token; ?>&option_id=' + option_id,
		type: 'post',
		dataType: 'json',
		data: 'option_id=' + option_id,
		success: function(json) {
			var option_values = new Array();
			var option_value_ids = new Array();
			var html = '<option value="">--Option Values--</option>';
			var i = 0;
			var j = 0;
			for(var option_value in json.option_values)
			{
				option_values[i] = json.option_values[option_value];
				i++;
			}
			for(var option_value_id in json.option_value_ids)
			{
				option_value_ids[j] = json.option_value_ids[option_value_id];
				j++;
			}
			for(var i = 0; i < option_values.length; i++)
			{
				html += '<option>'+option_values[i]+'</option>';
			}
			if(json == "0")
			{
				$('#input-option-value').children().remove();
				$('select#input-option-value').append(html);
			}
			else
			{
				$('#input-option-value').children().remove();
				$('select#input-option-value').append(html);
			}
		}
	});
    });
}
 
function reset_form()
{
	$('[name=filter_sold_less]').val('');
	$('[name=filter_category]').prop('selectedIndex', 0);
	$('[name=filter_option]').prop('selectedIndex', 0);
	$('[name=filter_option_value]').prop('selectedIndex', 0);
	$('[name=filter_all_products]').attr('checked', false);;
} 
  </script>
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});





//--></script></div>
<?php echo $footer; ?> 
