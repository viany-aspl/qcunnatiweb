<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="button" onclick="submit_mail()" form="form-product" data-toggle="tooltip" title="<?php echo "Save Button"; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo "Cancel Button"; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo "Purchase Request"; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if (isset($_SESSION['errors'])) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $_SESSION['errors']; unset($_SESSION['errors']);?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo "Add Purchase Order (<span style='color:red'>*</span>) shows field is required"; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" name="order_form" id="form-product" class="form-horizontal">
          <div class="tab-content">
            <div class="tab-pane" style="display:block;" id="tab-discount">
			<div style="display:none;" class="row">
				<div class="col-lg-12">
					<div class="col-lg-3 pull-right form-group">
						<label>Supplier</label>
						<select class="form-control" id="input-supplier" name="supplier_id">
							<option>--Supplier--</option>
							<?php foreach($suppliers as $supplier){ ?>
							<option value="<?php echo $supplier['id']?>"><?php echo $supplier['first_name'] . " " . $supplier['last_name']; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>
              <div class="table-responsive">
                <table id="discount" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left required"><?php echo "Product" ?></td>
                      <td class="text-right required"><?php echo "Store"; ?></td>
                      <td class="text-right required"><?php echo "Quantity"; ?></td>
                      <td class="text-right required"><?php echo "Product Options" ?></td>
                      <td class="text-right required"><?php echo "Option Values"; ?></td>
					  <td class="text-right"></td>
                      <!--<td class="text-left"><?php //echo "Date Start"; ?></td>
                      <td class="text-left"><?php //echo "Date End"; ?></td>-->
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if($form_bit == 1){ ?>
                    <tr id="discount-row">
                      <td class="text-left dynamic-rowspan">
					  <select name="product[]" data-validate="required" class="form-control" <!--onchange="loadAttributes(this)"-->>
                          <option value="" selected="selected">--Products--</option>
                          <?php
							for($i = 0; $i<count($products); $i++)
							{
						  ?>
								<option value="<?php echo $product_ids[$i] .'_'. $products[$i]; ?>"><?php echo $products[$i]; ?></option>
						  <?php
							}
						  ?>
                      </select><!--<br />
					  <input type="text" data-validate="required" name="quantity[]" value="" placeholder="Quantity" class="form-control" />-->
					  </td>
                                          <td class="text-left dynamic-rowspan">
                                              
                                           <select name="stores[]" class="form-control">
                                               <option value="" selected="selected">--Stores--</option>
                          <?php  foreach ($stores as $store) {  ?>
                          
                          <option value="<?php echo $store['store_id'].'_'.$store['name']; ?>"><?php echo $store['name']; ?></option>
                          
                          <?php } ?>
                        </select>
                                          
                                          </td>
					  <td class="text-right dynamic-rowspan"><input data-validate="required" type="text" name="quantity[]" value=""placeholder="" class="form-control" /></td>
                      <td class="text-left">
					  <select name="options[]" class="form-control" onchange="loadRelatedOptionValues(this)">
                          <option value="" selected="selected">--Product Options--</option>
						  <?php
							foreach($options as $option)
							{
						  ?>
								<option value="<?php echo $option['option_id'] .'_'. $option['name'];; ?>"><?php echo $option['name']; ?></option>
						  <?php
							}
						  ?>
                      </select>
					  
					  </td>
                      <td class="text-left">
					  <select name="option_values[]" class="form-control attribute">
                          <option value="" selected="selected">--Option Values--</option>
                      </select>
					  </td>
					  <td class=""><button type="button" onclick="addAttribute(this);" data-toggle="tooltip" title="<?php echo "Add Attribute Button"; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                      <td class=""><button type="button" onclick="removeProduct(this)" data-toggle="tooltip"  title="<?php echo "Remove Product Button" ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
					</tr>
					<!--<tr id="remove_attribute">
                      <td colspan="4" style="text-align:right;"><b>Add Attribute</b></td>
                      <td class="text-left"><button type="button" onclick="addAttribute(this);" data-toggle="tooltip" title="<?php echo "Add Attribute Button"; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>-->
					<?php }else{
						//print_r($quantities_received);
						$count = count($product_received);
						$match_count = count($product_received);
						for($j=0; $j<$count; $j++)
						{
							$matched = false;
					?>		
					<tr id="discount-row">
                      <td class="text-left dynamic-rowspan">
					  <select name="product[]" class="form-control">
                          <option value="" selected="selected">--Products--</option>
                          <?php
							for($i = 0; $i<count($products); $i++)
							{
						  ?>
								<option value="<?php echo $product_ids[$i] .'_'. $products[$i]; ?>"<?php
									for($k=0; $k<$match_count; $k++){
										if(isset($product_received[$k][1]))
										{
											if(($product_received[$k][1] == $products[$i]) && !$matched){
												?>selected="selected"<?php
												unset($product_received[$k]);
												$product_received = array_values($product_received);
												$match_count = count($product_received);
												$matched = true;
												break;
											}
										}
									}?>><?php echo $products[$i]; ?>
								</option>
						  <?php
							}
						  ?>
                      </select>
</td>
<td class="text-left dynamic-rowspan">
                                              
                                           <select name="stores[]" class="form-control">
                                               <option value="" selected="selected">--Stores--</option>
                          <?php  foreach ($stores as $store) {  ?>
                          
                          <option value="<?php echo $store['store_id'].'_'.$store['name']; ?>"><?php echo $store['name']; ?></option>
                          
                          <?php } ?>
                        </select>
                                          
                                          </td>
					  <td class="text-right dynamic-rowspan"><input data-validate="required" type="text" name="quantity[]" value=""placeholder="" class="form-control" /></td>
                      <!--<br />
					  <input type="text" name="quantity[]" value="<?php echo $quantities_received[$j]; ?>"placeholder="Quantity" class="form-control" />-->
					  
                      <td class="text-left">
					  <select name="options[]" class="form-control" onchange="loadRelatedOptionValues(this)">
                          <option value="" selected="selected">--Product Options--</option>
						  <?php
							foreach($options as $option)
							{
						  ?>
								<option value="<?php echo $option['option_id'] .'_'. $option['name']; ?>" ><?php echo $option['name']; ?></option>
						  <?php
							}
						  ?>
                      </select>
					  <input type="hidden" value ="new product" name="options[]">
					  </td>
                      <td class="text-left">
					  <select name="option_values[]" class="form-control attribute">
                          <option value="" selected="selected">--Option Values--</option>
                      </select>
					  <input type="hidden" value ="new product" name="option_values[]">
					  </td>
					  <td class=""><button type="button" onclick="addAttribute(this);" data-toggle="tooltip" title="<?php echo "Add Attribute Button"; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                      <td class=""><button type="button" onclick="removeProduct(this)" data-toggle="tooltip"  title="<?php echo "Remove Product Button" ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
					</tr>
					<?php
						}
					} ?>
                  </tbody>
                  <tfoot>
					
					<tr>
                      <td colspan="5" style="text-align:right;"><b>Add product</b></td>
                      <td class=""><button type="button" onclick="addProduct();" data-toggle="tooltip" title="<?php echo "Add Product Button"; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript">
  var rowSpan = 1;
function addProduct() {
    html  = '<tr id="discount-row">';
    html += '  <td class="text-left dynamic-rowspan"><select data-validate="required" name="product[]" class="form-control" >';
    html += '    <option value="">--Products--</option>';
	<?php
		for($i = 0; $i<count($products); $i++)
		{
	?>
	html += '    <option value="<?php echo $product_ids[$i] .'_'. $products[$i]; ?>"><?php echo $products[$i]; ?></option>';
	<?php
		}
	?>
    //html += '  </select><br /><input type="text" data-validate="required" name="quantity[]" value=""placeholder="Quantity" class="form-control" /></td>';
	html += '  </select></td>';
        //stores
        html += '  <td class="text-left dynamic-rowspan"><select data-validate="required" name="stores[]" class="form-control" >';
    html += '    <option value="">--Stores--</option>';
	<?php
		for($i = 0; $i<count($stores); $i++)
		{
	?>
	html += '    <option value="<?php echo $stores[$i]["store_id"]."_".$stores[$i]["name"]; ?>"><?php echo $stores[$i]["name"]; ?></option>';
	<?php
		}
	?>
    //html += '  </select><br /><input type="text" data-validate="required" name="quantity[]" value=""placeholder="Quantity" class="form-control" /></td>';
	html += '  </select></td>';
    
        
    //stores    
	html += '  <td class="text-right dynamic-rowspan"><input data-validate="required" type="text" name="quantity[]" value="" placeholder="" class="form-control" /></td>';
    html += '<input type="hidden" value ="new product" name="options[]">';
	html += '  <td class="text-left"><select name="options[]" class="form-control" onchange="loadRelatedOptionValues(this)">';
    html += '<option value="">--Product Options--</option>'
	<?php foreach($options as $option){
	?>
	html += '    <option value="<?php echo $option['option_id'] .'_'. $option['name'];; ?>"><?php echo $option['name']; ?></option>';
	<?php
	}
	?>
    html += '  </select></td>';
	html += '<input type="hidden" value ="new product" name="option_values[]">';
	html += '  <td class="text-left"><select name="option_values[]" class="form-control attribute">';
    html += '    <option value="">--Option Values--</option>';
    html += '  </select></td>';
	html += '<td class=""><button type="button" onclick="addAttribute(this);" data-toggle="tooltip" title="<?php echo "Add Product Button"; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>';
	//html += '  <td class="text-right dynamic-rowspan"><input type="text" name="quantity[]" value="" placeholder="" class="form-control" /></td>';
    html += '  <td class="text-left dynamic-rowspan"><button type="button" onclick="removeProduct(this)" data-toggle="tooltip" title="<?php echo "Remove Button" ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	html += '<tr>';
	html += '</tr>';

	$('#discount tbody').append(html);

	$('.date').datetimepicker({
		pickTime: false
	});
}
function removeAttributes(evnt)
{
	$(document).ready(function()
    {
       $(evnt).parent().parent().remove();
    });
}
function removeProduct(evnt)
{
	$(document).ready(function()
    {
	   $(evnt).parent().parent().nextUntil("#discount-row").remove();
	   $(evnt).parent().parent().remove();
	});
}
//add attributes function

function addAttribute(evnt)
{
	html = '';
	html  = '<tr id="option-row">';
    html += '  <td class="text-left" style="border-width:0px; background-color:white;"></td>';
	html += '  <td class="text-left" style="border-width:0px; background-color:white;"></td>';
	html += '  <td class="text-left"><select name="options[]" class="form-control" onchange="loadRelatedOptionValues(this)">';
    html += '<option value="">--Product Options--</option>';
	<?php
		foreach($options as $option)
		{
	?>
	html += '	<option value="<?php echo $option['option_id'].'_'.$option['name']; ?>"><?php echo $option['name']; ?></option>'	;
	<?php
		}
	?>
	html += '  </select></td>';
	html += '  <td class="text-left"><select name="option_values[]" class="form-control attribute">';
    html += '    <option value="">--Option Values--</option>';
    html += '  </select></td>';
    html += '  <td class=""><button type="button" onclick="removeAttributes(this)" data-toggle="tooltip" title="<?php echo "Remove Button" ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
    html += '  <td class="text-left" style="border-width:0px;></td>';
	html += '  <td class="text-left" style="border-width:0px;></td>';
	html += '</tr>';
	$(evnt).parent().parent().after(html);
	//alert($(evnt).parent().parent().nextUntil("#discount-row").prev().attr('id'));
}
/*load related option values starts here*/

function loadRelatedOptionValues(evnt)
{
	$(document).ready(function()
    {
		var option_id = $(evnt).val();
		$.ajax({
		url: 'index.php?route=inventory/purchase_order/getRelatedOptionValues&token=<?php echo $token; ?>&option_id=' + option_id,
		type: 'post',
		dataType: 'json',
		data: 'option_id=' + option_id,
		success: function(json) {
			var option_values = new Array();
			var option_value_ids = new Array();
			var html = '<option>--Option Values--</option>';
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
				html += '<option value="'+option_value_ids[i]+"_"+option_values[i]+'">'+option_values[i]+'</option>';
			}
			if(json == "0")
			{
				$(evnt).parent().parent().children('td').children('select.attribute').children().remove();
				$(evnt).parent().parent().children('td').children('select.attribute').append(html);
			}
			else
			{
				$(evnt).parent().parent().children('td').children('select.attribute').children().remove();
				$(evnt).parent().parent().children('td').children('select.attribute').append(html);
			}
		}
	});
    });
}

/*load related option values ends here*/
</script>
  <script type="text/javascript">
  function submit_mail()
  {
	  $("#form-product").append("<input type='hidden' name='mail_bit' value='1'>");
		  
	  $("#form-product").submit();
  }
</script>
  <script type="text/javascript"><!--
$('#language a:first').tab('show');
$('#option a:first').tab('show');
//--></script></div>
<?php echo $footer; ?>
