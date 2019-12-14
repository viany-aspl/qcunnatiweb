<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
          <button type="button" onclick="return check_val();" form="form-product" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $product_name; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
<!--      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> Edit Credit</h3>
     <div class="pull-right">
             <span style="margin-right: 10px;float: left">
            <input type="checkbox" id="check_bx_all" onclick="return check_for_all(this.value);" value="0" />
                      <div style="margin-bottom: 5px;float: right;">Change credit for all stores 
                          </div> 
                          
            </span>
        </div>
      </div>-->
       
      </div>
        
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
          <div class="panel-heading pull-right" style="margin-top: -28px;">
              <div style="font-weight: bold;">
                   
                  <div id="div_for_all" style="display: none;float: left;">
                      <i style="color: red;float:right;font-size: 10px;">It will changed master price also.</i>
                      <br/>
              Maximun : 
              <input type="text" autocomplete="off" onkeypress="return isNumber(event)"  placeholder="Price+Tax " class="form-horizontal" name="for_all_price" id="for_all"  onkeyup="updatePriceStore(this.value,this.id)" onpaste="updatePriceStore(this.value,this.id)" onchange="updatePriceStore(this.value,this.id)"  />
                  </div>
             
        </div>
        </div>
          <div class="tab-content">
            
         <!--tab-quantity to display-->
              
              <div class="tab-pane active" id="tab-quantity">
              <div class="table-responsive">
                <table class="table table-bordered table-hover" id="p_table">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_store; ?></td>
                      <td class="text-left">Minimum Quantity</td>
                      <td class="text-left">Maximum Quantity</td>
                      <td class="text-left">Multiple</td>
                      <!--<td class="text-left"><?php echo $entry_price_tax; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $entry_price; ?></td>-->
                    </tr>
                  </thead>
                  <tbody>
                        <?php  foreach ($stores as $store) { ?>
                    <tr>
                      <td class="text-left"><?php echo $store['name']; ?></td>
                         <td class="text-left">
                           <?php $chk=false; foreach ($product_store_quantity as $store_quantity) {   ?>                    
                           <?php if($store['store_id']==$store_quantity['store_id']){ $chk=true;  ?>
                          <input type="text"  onkeypress="return isNumberp(event)" name="quantitystoremin<?php echo isset($store_quantity['store_id'])?$store_quantity['store_id']:0 ; ?>" value="<?php echo isset($store_quantity['minquantity'])?$store_quantity['minquantity']:0 ; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantitystore<?php echo isset($store_quantity['store_id'])?$store_quantity['store_id']:0 ; ?>" class="form-control" />                             
                          <?php  }  ?>
                   
                          <?php      } if(!$chk) {  ?>
                         <input type="text"  onkeypress="return isNumbp(event)" name="quantitystoremin<?php echo $store['store_id'] ; ?>" value="0" placeholder="<?php echo $entry_quantity; ?>" id="input-quantitystore<?php echo $store['store_id'] ; ?>" class="form-control" />                              
                      
   <?php  }  ?>
                      </td>
                      <?php $chkpr=false; foreach ($product_store_quantity as $store_quantity) {   ?>
                      <?php if($store['store_id']==$store_quantity['store_id']){ $chkpr=true; // print_r($store_quantity['store_id']); ?>
                     
                     
                      
                      
                      <td class="text-left">
                        <input type="text" onkeypress="return isNumber(event)"   name="qquantitystoremax<?php echo isset($store_quantity['store_id'])?$store_quantity['store_id']:0 ; ?>" value="<?php if(($store_quantity['maxquantity'])!=0){ echo $store_quantity['maxquantity']; } else { echo "0"; } //echo isset($store_quantity['store_price'])?($store_quantity['store_price']+$store_quantity['store_tax_amt']):0 ; ?>" placeholder="<?php echo $entry_price; ?>" id="iinput-quantitystoreprice<?php echo isset($store_quantity['store_id'])?$store_quantity['store_id']:1 ; ?>" class="form-control" />                                
                         
                      </td>
                      
                      
                      <td class="text-left" style="min-width: 90px;">
                          <input type="text"  name="quantitystorefactor<?php echo isset($store_quantity['store_id'])?$store_quantity['store_id']:0 ; ?>" value="<?php echo isset($store_quantity['factor'])?$store_quantity['factor']:0 ; ?>" placeholder="<?php echo $entry_price; ?>" id="input-quantitystoreprice<?php echo isset($store_quantity['store_id'])?$store_quantity['store_id']:0 ; ?>" class="form-control" />                              
                      </td>
                      <?php  }  ?>
                       <?php      } if(!$chkpr) {  ?>
                      <td class="text-left">
                          <input type="text" onkeypress="return isNumber(event)"  name="qquantitystorefactor<?php echo $store['store_id']; ?>" value="<?php if(($store_quantity['factor'])!=0){ echo $store_quantity['factor']; } else { echo "0"; } //echo isset($store_quantity['store_price'])?$store_quantity['store_price']:0 ; ?>" placeholder="<?php echo $entry_price; ?>" id="iinput-quantitystoreprice<?php echo $store['store_id']; ?>" class="form-control" />                              
                      </td>
                      <td class="text-left" style="min-width: 90px;">
                          <input type="text" name="quantitystorefactor<?php echo $store['store_id'] ; ?>"  value="0" placeholder="<?php echo $entry_price; ?>" id="input-quantitystoreprice<?php echo $store['store_id'] ; ?>" class="form-control" />                               
                      </td>
                      <?php  }  ?>
                      
                    </tr>
                     <?php } ?>
                  </tbody></table>
              </div></div>
          </div>
        </form>
      </div>
    </div>
  </div>


 <script>
    
function isNumber(evt)
       {
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
       function isNumberp(evt)
       {
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
        function check_for_all(val)
        {   //alert(val);
            if(val=="0")
            {
                $("#div_for_all").show();
                $("#check_bx_all").val('1');
                $("#for_all").val('');
            }
            if(val=="1")
            {
                $("#div_for_all").hide();
                $("#check_bx_all").val('0');
                $("#for_all").val('');
            }
        }
        function check_val()
        {
           
           
               try{
              var zerro=0;
               var tabel = document.getElementById('p_table');
               var rijen = tabel.rows.length;
               //document.getElementById("table01").rows[0].cells[2].input.value
               for (i = 1; i < rijen; i++){
                var inputs = tabel.rows[i].cells[3].getElementsByTagName("input")[0];//innerHTML;
                //alert(inputs.value);
                zerro=parseFloat(zerro)+parseFloat(inputs.value);}                                                                                             
               
           
           $("#form-product").submit();
            }catch(e){alert(e);}
        }
        </script>
  <script type="text/javascript">
			
   
    
    var rates = <?php echo $js_rates; ?>;
				
	        	function calculateTaxes(taxed_value, fixed_taxes){
	
	        		var amount = 0;
	        		
	        		$.each(rates, function(index, item) {
	            		if (item.type == 'F') {
							// Fixed
							if (fixed_taxes) {
								amount += parseFloat(item.rate);
							}
	        		    }
	        		    
	        		    else if (item.type == 'P') {
							// Percentual
							amount += (taxed_value / 100) * parseFloat(item.rate);
	        		    }
	        		});
	        		
	        		return amount;
	        	}
            	function calculateUntaxedValue(taxed_value, fixed_taxes){
                             
					var value = taxed_value;
					var rate = 0;
					
					// Removes fix taxes
					
            		$.each(rates, function(index, item) {
                		if (item.type == 'F') {
							// Fixed
							if (fixed_taxes) {
								value -= parseFloat(item.rate);
							}
            		    }
            		});

					// Calculates tax rate
            		
            		$.each(rates, function(index, item) {            		    
            		    if (item.type == 'P') {  
							// Percentual
							rate += parseFloat(item.rate);
            		    }
            		});

					var untaxed_value = 0;

					if (rate != 0) 
                                        { //alert(JSON.stringify(rates));
                                            untaxed_value = (value / (rate + 100)) * 100;
                                            //untaxed_value = value-((value*rate)/ 100);
                                        }
					else 
                                        {
                                            untaxed_value = value;
                                        }
            		
            		return untaxed_value;
            	}

 function updatePriceStore(val,id)
 {
      //alert(id);
      var aa=0;
      if(id=="for_all")
      {
          <?php  foreach ($stores as $store) { ?>
             id='iinput-quantitystoreprice<?php echo $store["store_id"]; ?>';
             //alert(id); 
              try{ 
    
					var value = parseFloat(val);
					if (isNaN(value)) value = 0;
					
					if (value == 0)
                                        {
                                            $('#'+id.slice(1)).val(0);
                                            $('#i'+id.slice(1)).val(value);
                                        }
					else
                                        {
                                            $('#'+id.slice(1)).val(calculateUntaxedValue(value, true));
                                            $('#i'+id.slice(1)).val(val);
                                        }
                                    }
                                catch(e)
                                {
                                        alert(e);
                                }    
          <?php } ?>
          
      }
      else
      {
      
      
      
      
                                try{ 
    
					var value = parseFloat(val);
					if (isNaN(value)) value = 0;
					
					if (value == 0)
                                        {
                                            $('#'+id.slice(1)).val(0);
                                        }
					else
                                        {
                                            $('#'+id.slice(1)).val(calculateUntaxedValue(value, true));
                                        }
                                    }
                                catch(e)
                                {
                                        //alert(e);
                                }
        }
}


function updatePriceTaxStore(val,id){
					var value = parseFloat($('#'+id).val());
					if (isNaN(value)) value = 0;
					
					if (value == 0) $('#'+id).val(0);
					else $('#'+id).val(value + calculateTaxes(value, true));
				}


				function updatePrice(){
					var value = parseFloat($('#form-product input[name=price_tax]').val());
					if (isNaN(value)) value = 0;
					
					if (value == 0) $('#form-product input[name=price]').val(0);
					else $('#form-product input[name=price]').val(calculateUntaxedValue(value, true));
				}
				function updatePriceTax(){
					var value = parseFloat($('#form-product input[name=price]').val());
					if (isNaN(value)) value = 0;
					
					if (value == 0) $('#form-product input[name=price_tax]').val(0);
					else $('#form-product input[name=price_tax]').val(value + calculateTaxes(value, true));
				}
				function updatePriceDiscount(row){
					var value = parseFloat($('#form-product #discount-row' + row + ' .product_discount_price_tax').val());
					if (isNaN(value)) value = 0;
					
					if (value == 0) $('#form-product #discount-row' + row + ' .product_discount_price').val(0);
					else $('#form-product #discount-row' + row + ' .product_discount_price').val(calculateUntaxedValue(value, true));
				}
				function updatePriceTaxDiscount(row){
					var value = parseFloat($('#form-product #discount-row' + row + ' .product_discount_price').val());
					if (isNaN(value)) value = 0;
					
					if (value == 0) $('#form-product #discount-row' + row + ' .product_discount_price_tax').val(0);
					else $('#form-product #discount-row' + row + ' .product_discount_price_tax').val(value + calculateTaxes(value, true));
				}
				function updatePriceSpecial(row){
					var value = parseFloat($('#form-product #special-row' + row + ' .product_special_price_tax').val());
					if (isNaN(value)) value = 0;
					
					if (value == 0) $('#form-product #special-row' + row + ' .product_special_price').val(0);
					else $('#form-product #special-row' + row + ' .product_special_price').val(calculateUntaxedValue(value, true));
				}
				function updatePriceTaxSpecial(row){
					var value = parseFloat($('#form-product #special-row' + row + ' .product_special_price').val());
					if (isNaN(value)) value = 0;
					
					if (value == 0) $('#form-product #special-row' + row + ' .product_special_price_tax').val(0);
					else $('#form-product #special-row' + row + ' .product_special_price_tax').val(value + calculateTaxes(value, true));
				}
				function updatePriceOption(row){
					var value = parseFloat($('#form-product #option-value-row' + row + ' .product_option_price_tax').val());
					if (isNaN(value)) value = 0;
					
					if (value == 0) $('#form-product #option-value-row' + row + ' .product_option_price').val(0);
					else $('#form-product #option-value-row' + row + ' .product_option_price').val(calculateUntaxedValue(value, false));
				}
				function updatePriceTaxOption(row){
					var value = parseFloat($('#form-product #option-value-row' + row + ' .product_option_price').val());
					if (isNaN(value)) value = 0;
					
					if (value == 0) $('#form-product #option-value-row' + row + ' .product_option_price_tax').val(0);
					else $('#form-product #option-value-row' + row + ' .product_option_price_tax').val(value + calculateTaxes(value, false));
				}
				$(document).ready(function(){
					$('#form-product input[name=price_tax]').on("change keyup paste", function(){
						updatePrice();
					});
					$('#form-product input[name=price]').on("change keyup paste", function(){
						updatePriceTax();
					});
		            <?php if ($config_tax_included) { ?>
					$('#form-product select[name=tax_class_id]').change(function(){
						$.ajax({
							type: 'POST',
							url: 'index.php?route=catalog/product/json_taxrates&token=<?php echo $token; ?>',
							data: { 'tax_class_id': $(this).val() },
							success: function(result){
										if (result.status == 'ok') {
											rates = result.tax_rates;
											updatePrice();
											$('#discount tbody').each(function(){
												updatePriceDiscount($(this).data('row'));
											});
											$('#special tbody').each(function(){
												updatePriceSpecial($(this).data('row'));
											});
											$('#tab-option tbody').each(function(){
												updatePriceOption($(this).data('row'));
											});
										}
									},
							dataType: 'json'
						});
					});
					<?php } ?>
					$('#form-product').on("change keyup paste", ".product_discount_price_tax", function(){
						updatePriceDiscount($(this).parent().parent().data('row'));
					});
					$('#form-product').on("change keyup paste", ".product_discount_price", function(){
						updatePriceTaxDiscount($(this).parent().parent().data('row'));
					});
					$('#form-product').on("change keyup paste", ".product_special_price_tax", function(){
						updatePriceSpecial($(this).parent().parent().data('row'));
					});
					$('#form-product').on("change keyup paste", ".product_special_price", function(){
						updatePriceTaxSpecial($(this).parent().parent().data('row'));
					});
					$('#form-product').on("change keyup paste", ".product_option_price_tax", function(){
						updatePriceOption($(this).parent().parent().data('row'));
					});
					$('#form-product').on("change keyup paste", ".product_option_price", function(){
						updatePriceTaxOption($(this).parent().parent().data('row'));
					});
				});
            </script>
  <script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
$('#input-description<?php echo $language['language_id']; ?>').summernote({height: 300});
<?php } ?>
//--></script> 
  <script type="text/javascript"><!--
// Manufacturer
$('input[name=\'manufacturer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				json.unshift({
					manufacturer_id: 0,
					name: '<?php echo $text_none; ?>'
				});
				
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['manufacturer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'manufacturer\']').val(item['label']);
		$('input[name=\'manufacturer_id\']').val(item['value']);
	}	
});

// Category
$('input[name=\'category\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['category_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'category\']').val('');
		
		$('#product-category' + item['value']).remove();
		
		$('#product-category').append('<div id="product-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_category[]" value="' + item['value'] + '" /></div>');	
	}
});

$('#product-category').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

// Filter
$('input[name=\'filter\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/filter/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['filter_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter\']').val('');
		
		$('#product-filter' + item['value']).remove();
		
		$('#product-filter').append('<div id="product-filter' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_filter[]" value="' + item['value'] + '" /></div>');	
	}	
});

$('#product-filter').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

// Downloads
$('input[name=\'download\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/download/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['download_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'download\']').val('');
		
		$('#product-download' + item['value']).remove();
		
		$('#product-download').append('<div id="product-download' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_download[]" value="' + item['value'] + '" /></div>');	
	}	
});

$('#product-download').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});
// Related
$('input[name=\'related\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete_crops&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'related\']').val('');
		
		$('#product-related' + item['value']).remove();
		
		$('#product-related').append('<div id="product-related' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_related[]" value="' + item['value'] + '" /></div>');	
	}	
});

$('#product-related').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});
//--></script> 
  <script type="text/javascript"><!--
var attribute_row = <?php echo $attribute_row; ?>;

function addAttribute() {
    html  = '<tr id="attribute-row' + attribute_row + '">';
	html += '  <td class="text-left" style="width: 20%;"><input type="text" name="product_attribute[' + attribute_row + '][name]" value="" placeholder="<?php echo $entry_attribute; ?>" class="form-control" /><input type="hidden" name="product_attribute[' + attribute_row + '][attribute_id]" value="" /></td>';
	html += '  <td class="text-left">';
	<?php foreach ($languages as $language) { ?>
	html += '<div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span><textarea name="product_attribute[' + attribute_row + '][product_attribute_description][<?php echo $language['language_id']; ?>][text]" rows="5" placeholder="<?php echo $entry_text; ?>" class="form-control"></textarea></div>';
    <?php } ?>
	html += '  </td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#attribute-row' + attribute_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
    html += '</tr>';
	
	$('#attribute tbody').append(html);
	
	attributeautocomplete(attribute_row);
	
	attribute_row++;
}

function attributeautocomplete(attribute_row) {
	$('input[name=\'product_attribute[' + attribute_row + '][name]\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',			
				success: function(json) {
					response($.map(json, function(item) {
						return {
							category: item.attribute_group,
							label: item.name,
							value: item.attribute_id
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('input[name=\'product_attribute[' + attribute_row + '][name]\']').val(item['label']);
			$('input[name=\'product_attribute[' + attribute_row + '][attribute_id]\']').val(item['value']);
		}
	});
}

$('#attribute tbody tr').each(function(index, element) {
	attributeautocomplete(index);
});
//--></script> 
  <script type="text/javascript"><!--	
var option_row = <?php echo $option_row; ?>;

$('input[name=\'option\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/option/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						category: item['category'],
						label: item['name'],
						value: item['option_id'],
						type: item['type'],
						option_value: item['option_value']
					}
				}));
			}
		});
	},
	'select': function(item) {
		html  = '<div class="tab-pane" id="tab-option' + option_row + '">';
		html += '	<input type="hidden" name="product_option[' + option_row + '][product_option_id]" value="" />';
		html += '	<input type="hidden" name="product_option[' + option_row + '][name]" value="' + item['label'] + '" />';
		html += '	<input type="hidden" name="product_option[' + option_row + '][option_id]" value="' + item['value'] + '" />';
		html += '	<input type="hidden" name="product_option[' + option_row + '][type]" value="' + item['type'] + '" />';
		
		html += '	<div class="form-group">';
		html += '	  <label class="col-sm-2 control-label" for="input-required' + option_row + '"><?php echo $entry_required; ?></label>';
		html += '	  <div class="col-sm-10"><select name="product_option[' + option_row + '][required]" id="input-required' + option_row + '" class="form-control">';
		html += '	      <option value="1"><?php echo $text_yes; ?></option>';
		html += '	      <option value="0"><?php echo $text_no; ?></option>';
		html += '	  </select></div>';
		html += '	</div>';
		
		if (item['type'] == 'text') {
			html += '	<div class="form-group">';
			html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
			html += '	  <div class="col-sm-10"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" class="form-control" /></div>';
			html += '	</div>';
		}
		
		if (item['type'] == 'textarea') {
			html += '	<div class="form-group">';
			html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
			html += '	  <div class="col-sm-10"><textarea name="product_option[' + option_row + '][value]" rows="5" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" class="form-control"></textarea></div>';
			html += '	</div>';			
		}
		 
		if (item['type'] == 'file') {
			html += '	<div class="form-group" style="display: none;">';
			html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
			html += '	  <div class="col-sm-10"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" class="form-control" /></div>';
			html += '	</div>';
		}
						
		if (item['type'] == 'date') {
			html += '	<div class="form-group">';
			html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
			html += '	  <div class="col-sm-3"><div class="input-group date"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" data-date-format="YYYY-MM-DD" id="input-value' + option_row + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
			html += '	</div>';
		}
		
		if (item['type'] == 'time') {
			html += '	<div class="form-group">';
			html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
			html += '	  <div class="col-sm-10"><div class="input-group time"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" data-date-format="HH:mm" id="input-value' + option_row + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
			html += '	</div>';
		}
				
		if (item['type'] == 'datetime') {
			html += '	<div class="form-group">';
			html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
			html += '	  <div class="col-sm-10"><div class="input-group datetime"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-value' + option_row + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
			html += '	</div>';
		}
			
		if (item['type'] == 'select' || item['type'] == 'radio' || item['type'] == 'checkbox' || item['type'] == 'image') {
			html += '<div class="table-responsive">';
			html += '  <table id="option-value' + option_row + '" class="table table-striped table-bordered table-hover">';
			html += '  	 <thead>'; 
			html += '      <tr>';
			html += '        <td class="text-left"><?php echo $entry_option_value; ?></td>';
			html += '        <td class="text-right"><?php echo $entry_quantity; ?></td>';
			html += '        <td class="text-left"><?php echo $entry_subtract; ?></td>';
html += '        <td class="text-right"><?php if ($config_tax_included) { ?><?=$entry_price_tax?><br/><?php } ?><?=$entry_price?></td>';
			html += '        <td class="text-right"><?php echo $entry_option_points; ?></td>';
			html += '        <td class="text-right"><?php echo $entry_weight; ?></td>';
			html += '        <td></td>';
			html += '      </tr>';
			html += '  	 </thead>';
			html += '  	 <tbody>';
			html += '    </tbody>';
			html += '    <tfoot>';
			html += '      <tr>';
			html += '        <td colspan="6"></td>';
			html += '        <td class="text-left"><button type="button" onclick="addOptionValue(' + option_row + ');" data-toggle="tooltip" title="<?php echo $button_option_value_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>';
			html += '      </tr>';
			html += '    </tfoot>';
			html += '  </table>';
			html += '</div>';
			
            html += '  <select id="option-values' + option_row + '" style="display: none;">';
			
            for (i = 0; i < item['option_value'].length; i++) {
				html += '  <option value="' + item['option_value'][i]['option_value_id'] + '">' + item['option_value'][i]['name'] + '</option>';
            }

            html += '  </select>';	
			html += '</div>';	
		}
		
		$('#tab-option .tab-content').append(html);
			
		$('#option > li:last-child').before('<li><a href="#tab-option' + option_row + '" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$(\'a[href=\\\'#tab-option' + option_row + '\\\']\').parent().remove(); $(\'#tab-option' + option_row + '\').remove(); $(\'#option a:first\').tab(\'show\')"></i> ' + item['label'] + '</li>');
		
		$('#option a[href=\'#tab-option' + option_row + '\']').tab('show');
$('#special-row' + special_row).data('row', special_row);
		$('#discount-row' + discount_row).data('row', discount_row);
		
		$('.date').datetimepicker({
			pickTime: false
		});
		
		$('.time').datetimepicker({
			pickDate: false
		});
		
		$('.datetime').datetimepicker({
			pickDate: true,
			pickTime: true
		});
				
		option_row++;
	}	
});
//--></script> 
  <script type="text/javascript"><!--		
var option_value_row = <?php echo $option_value_row; ?>;

function addOptionValue(option_row) {	
	html  = '<tr id="option-value-row' + option_value_row + '">';
	html += '  <td class="text-left"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_value_id]" class="form-control">';
	html += $('#option-values' + option_row).html();
	html += '  </select><input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][product_option_value_id]" value="" /></td>';
	html += '  <td class="text-right"><input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][quantity]" value="" placeholder="<?php echo $entry_quantity; ?>" class="form-control" /></td>'; 
	html += '  <td class="text-left"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][subtract]" class="form-control">';
	html += '    <option value="1"><?php echo $text_yes; ?></option>';
	html += '    <option value="0"><?php echo $text_no; ?></option>';
	html += '  </select></td>';
	html += '  <td class="text-right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price_prefix]" class="form-control">';
	html += '    <option value="+">+</option>';
	html += '    <option value="-">-</option>';
	html += '  </select>';
	//html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>';

<?php if ($config_tax_included) { ?>
	html += '    <input type="text" class="form-control product_option_price_tax" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price_tax]" value="" placeholder="<?=$entry_price_tax?>" />';
	<?php } ?>
	html += '  <input type="text" class="form-control product_option_price" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" /></td>';

	html += '  <td class="text-right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points_prefix]" class="form-control">';
	html += '    <option value="+">+</option>';
	html += '    <option value="-">-</option>';
	html += '  </select>';
	html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points]" value="" placeholder="<?php echo $entry_points; ?>" class="form-control" /></td>';	
	html += '  <td class="text-right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight_prefix]" class="form-control">';
	html += '    <option value="+">+</option>';
	html += '    <option value="-">-</option>';
	html += '  </select>';
	html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight]" value="" placeholder="<?php echo $entry_weight; ?>" class="form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(this).tooltip(\'destroy\');$(\'#option-value-row' + option_value_row + '\').remove();" data-toggle="tooltip" rel="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#option-value' + option_row + ' tbody').append(html);
$('#option-value-row' + option_value_row).data('row', option_value_row);
        $('[rel=tooltip]').tooltip();
        
	option_value_row++;
}
//--></script> 
  <script type="text/javascript"><!--
var discount_row = <?php echo $discount_row; ?>;

function addDiscount() {
	html  = '<tr id="discount-row' + discount_row + '">'; 
          html += '  <td class="text-left"><select name="product_discount[' + discount_row + '][store_id]" class="form-control">';
    <?php foreach ($stores as $store) { ?>
    html += '    <option value="<?php echo $store['store_id']; ?>"><?php echo addslashes($store['name']); ?></option>';
    <?php } ?>
    html += '  </select></td>';	
    html += '  <td class="text-left"><select name="product_discount[' + discount_row + '][customer_group_id]" class="form-control">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo addslashes($customer_group['name']); ?></option>';
    <?php } ?>
    html += '  </select></td>';		
    html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][quantity]" value="" placeholder="<?php echo $entry_quantity; ?>" class="form-control" /></td>';
    html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][priority]" value="" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>';
	//html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>';
 <?php if ($config_tax_included) { ?>
    html += '  <td class="text-right"><input class="form-control product_discount_price_tax type="text" name="product_discount[' + discount_row + '][price_tax]" value="" placeholder="<?=$entry_price_tax?>" /></td>';
    <?php } ?>
    html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="product_discount_price form-control" /></td>';
    html += '  <td class="text-left"><div class="input-group date"><input type="text" name="product_discount[' + discount_row + '][date_start]" value="" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
	html += '  <td class="text-left"><div class="input-group date"><input type="text" name="product_discount[' + discount_row + '][date_end]" value="" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#discount-row' + discount_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';	
	
	$('#discount tbody').append(html);

	$('.date').datetimepicker({
		pickTime: false
	});
	
	discount_row++;
}
//--></script> 
  <script type="text/javascript"><!--
var special_row = <?php echo $special_row; ?>;

function addSpecial() {
	html  = '<tr id="special-row' + special_row + '">'; 
    html += '  <td class="text-left"><select name="product_special[' + special_row + '][customer_group_id]" class="form-control">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo addslashes($customer_group['name']); ?></option>';
    <?php } ?>
    html += '  </select></td>';		
    html += '  <td class="text-right"><input type="text" name="product_special[' + special_row + '][priority]" value="" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>';
	//html += '  <td class="text-right"><input type="text" name="product_special[' + special_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>';
<?php if ($config_tax_included) { ?>
    html += '  <td class="text-right"><input type="text" class="product_special_price_tax form-control" name="product_special[' + special_row + '][price_tax]" value="" placeholder="<?=$entry_price_tax?>" /></td>';
    <?php } ?>
    html += '  <td class="text-right"><input type="text" name="product_special[' + special_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="product_special_price form-control" /></td>';
    html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_special[' + special_row + '][date_start]" value="" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
	html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_special[' + special_row + '][date_end]" value="" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#special-row' + special_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#special tbody').append(html);

	$('.date').datetimepicker({
		pickTime: false
	});
		
	special_row++;
}
//--></script> 
  <script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
	html  = '<tr id="image-row' + image_row + '">';
	html += '  <td class="text-left"><a href="" id="thumb-image' + image_row + '"data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /><input type="hidden" name="product_image[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';
	html += '  <td class="text-right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#images tbody').append(html);
	
	image_row++;
}
//--></script> 
  <script type="text/javascript"><!--
var recurring_row = <?php echo $recurring_row; ?>;

function addRecurring() {
	recurring_row++;
	
	html  = '';
	html += '<tr id="recurring-row' + recurring_row + '">';
	html += '  <td class="left">';
	html += '    <select name="product_recurrings[' + recurring_row + '][recurring_id]" class="form-control">>';
	<?php foreach ($recurrings as $recurring) { ?>
	html += '      <option value="<?php echo $recurring['recurring_id']; ?>"><?php echo $recurring['name']; ?></option>';
	<?php } ?>
	html += '    </select>';
	html += '  </td>';
	html += '  <td class="left">';
	html += '    <select name="product_recurrings[' + recurring_row + '][customer_group_id]" class="form-control">>';
	<?php foreach ($customer_groups as $customer_group) { ?>
	html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
	<?php } ?>
	html += '    <select>';
	html += '  </td>';
	html += '  <td class="left">';
	html += '    <a onclick="$(\'#recurring-row' + recurring_row + '\').remove()" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>';
	html += '  </td>';
	html += '</tr>';
	
	$('#tab-recurring table tbody').append(html);
}
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.time').datetimepicker({
	pickDate: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});
//--></script> 
  <script type="text/javascript"><!--
$('#language a:first').tab('show');
$('#option a:first').tab('show');
//--></script></div>
<?php echo $footer; ?> 