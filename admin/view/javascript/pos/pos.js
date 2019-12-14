
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
	$('.datetime').datetimepicker({
		dateFormat: 'yy-mm-dd',
		timeFormat: 'h:m'
	});
	$('.time').timepicker({timeFormat: 'h:m'});
	
	if ('block' == $('#order_product_content').css('display')) {
		$('.htabs a').tabs();

		// select the first row of the table
		preIndex = -1;
		pre_product_id = <?php if(isset($_GET['pre_select'])) {echo $_GET['pre_select']; } else {echo '-100';} ?>;
		if (pre_product_id > -2) {
			for (i = 0; i < $('#product tr').length; i++) {
				cur_product_id = $('#product tr:eq('+i+')').find('input').val();
				if (cur_product_id == pre_product_id) {
					preIndex = i;
					break;
				}
			}
		}
		if (preIndex == -1){
			preIndex = 0;
		}
		moveSelect(-1, preIndex);
	}
	
	CheckSizeZoom();
});

function showMessage(className, text, imgSrc) {
	divToAppend = '<div class="'+className+'">';
	if (className == 'pos_attention') {
		divToAppend += '<img src="view/image/loading.gif" alt="" />Wait</div>';
	} else {
		if (imgSrc) {
			divToAppend += '<img src="'+imgSrc+'" alt="" />';
		}
		
		var time = new Date ( );
		var hour = time.getHours();
		var minute = time.getMinutes();
		var second = time.getSeconds();
		hour = (hour < 10 ? "0" : "") + hour;
		minute = (minute < 10 ? "0" : "") + minute;
		second = (second < 10 ? "0" : "") + second;
		var year = time.getFullYear();
		var month = time.getMonth()+1;
		var day = time.getDate();
		month = (month < 10 ? "0" : "") + month;
		day = (day < 10 ? "0" : "") + day;

		divToAppend += '[' + year + '/' + month + '/' + day + ' ' + hour + ':' + minute + ':' + second + '] ' + text + '</div>';
	}
	$('#order_message').append(divToAppend);
};

function removeMessage() {
	$('.pos_success, .pos_warning, .pos_attention, .error').remove();
};

function detachCustomer() {
	var order_id = parseInt($('#order_id').text(), 10);
	$.ajax({
		url: 'index.php?route=module/pos/detachCustomer&token=<?php echo $token; ?>&order_id=' + order_id,
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			removeMessage();
			showMessage('pos_attention', null, null);
		},
		success: function(json) {
			if (json['success']) {
				$('#order_customer input[name=\'customer\']').attr('value', json['firstname']+' '+json['lastname']);
				$('#order_customer input[name=\'customer_id\']').attr('value', json['customer_id']);
				$('#order_customer input[name=\'customer_group_id\']').attr('value', json['customer_group_id']);
				$('#order_customer input[name=\'firstname\']').attr('value', json['firstname']);
				$('#order_customer input[name=\'lastname\']').attr('value', json['lastname']);
				$('#order_customer input[name=\'email\']').attr('value', json['email']);
				$('#order_customer input[name=\'telephone\']').attr('value', json['telephone']);
				$('#order_customer input[name=\'fax\']').attr('value', json['fax']);
				$('input[name=shipping_country_id]').attr('value', json['shipping_country_id']);
				$('input[name=shipping_zone_id]').attr('value', json['shipping_zone_id']);
				$('input[name=payment_country_id]').attr('value', json['payment_country_id']);
				$('input[name=payment_zone_id]').attr('value', json['payment_zone_id']);
				
				$('#general_customer_name').text(json['firstname']+' '+json['lastname']);
				$('#detach_customer_img').remove();
				$('#customer_name_td').append('<img id="add_customer_img" style="vertical-align: middle;" src="view/image/pos/plus_off.png" onclick="addCustomer();" />');
				$('#address_warning').remove();
				
				removeMessage();
				showMessage('pos_success', json['success'], null);
			}
		}
	});
}

$('#order_list_form input').live('keydown', function(e) {
	if (e.keyCode == 13) {
		filter($('.filter td:last a'));
	}
});

$('#button_new_order').live("click", function() {
	var url = 'index.php?route=module/pos/createEmptyOrder&token=<?php echo $token; ?><?php if(isset($store_id)) { echo '&store_id='.$store_id; } ?>';
	$.ajax({
		url: url,
		type: 'post',
		beforeSend: function() {
			removeMessage();
			showMessage('pos_attention', null, null);
		},
		success: function(html) {
			$('#divWrap').html($(html).find('div[id=\'divWrap\']').html());
			$('.htabs a').tabs();
			$('.vtabs a').tabs();
			$('select[name*=\'[country_id]\']').trigger('change');
			$('select[name*=\'customer_group_id\']').trigger('change');
			checkAndSaveOrder('button_new_order', 0);
		}
	});
});

function moveSelect(indexPre, index) {
	if (indexPre == index) { 
		return;
	}
	// select index and deselect indexPre
	var indexChecked = $('#product tr').index($('input[name=order_product_id]:checked', '#product').closest('tr'));
	$('#product tr:eq('+index+')').find("input[type=\'radio\']").attr('checked', true);
	$('#radio_selected_index').attr('value', index);
	$('#product tr:eq('+index+')').children('td,th').css('background-color', '#ffeda4');
	if (indexPre != -1) {
		$('#product tr:eq('+indexPre+')').children('td,th').css('background-color', '');
	} else {
		if (indexChecked >= 0 && indexChecked != index) {
			$('#product tr:eq('+indexChecked+')').children('td,th').css('background-color', '');
		}
	}
	if (index >= 0) {
		// not the new row, display product
		var product_id = $('#product tr:eq('+index+')').find("input[name$='[product_id]']").val();
		if (!product_id) {
			$('#tab_browse').trigger('click');
		} else {
			$('#tabs a:last').trigger('click');
			$('#product_details').empty();
			$.ajax({
				url: 'index.php?route=module/pos/getProductDetails&token=<?php echo $token; ?>&product_id='+product_id,
				beforeSend: function() {
					$('#product_details').html('<table border="0" width="100%" height="100%" align="center" valign="center"><tr align="center"><td align="center"><img src="view/image/loading.gif" class="loading" style="padding-left: 5px;"/></td></tr></table>');
				},
				complete: function() {
					$('.loading').remove();
				},
				success: function(html) {
					$('#product_details').html(html);
				}
			});
		}
		// scroll to the selected row
		var divHeight = $('#order_product_list_content').height();
		var scrollTop = $('#order_product_list thead').height();
		for (var i = 0; i < index; i++) {
			scrollTop += $('#product tr:eq('+i+')').height();
		}
		var scrollBottom = scrollTop + $('#product tr:eq('+index+')').height();
		var curPosition = $('#order_product_list_content').scrollTop();
		if (curPosition > scrollTop || curPosition + divHeight < scrollBottom) {
			$('#order_product_list_content').scrollTop(scrollTop);
		}
	}
};

$.widget('custom.catcomplete', $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this, currentCategory = '';
		
		$.each(items, function(index, item) {
			if (item.category != currentCategory) {
				ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');
				
				currentCategory = item.category;
			}
			
			self._renderItem(ul, item);
		});
	}
});

$('input[name=\'customer\']').live('focus', function(){
	$(this).catcomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {	
					response($.map(json, function(item) {
						return {
							category: item['customer_group'],
							label: item['name'],
							value: item['customer_id'],
							customer_group_id: item['customer_group_id'],
							firstname: item['firstname'],
							lastname: item['lastname'],
							email: item['email'],
							telephone: item['telephone'],
							fax: item['fax'],
							address: item['address']
						}
					}));
				}
			});
		}, 
		select: function(event, ui) { 
			$('input[name=\'customer\']').attr('value', ui.item['label']);
			$('input[name=\'customer_id\']').attr('value', ui.item['value']);
			$('input[name=\'firstname\']').attr('value', ui.item['firstname']);
			$('input[name=\'lastname\']').attr('value', ui.item['lastname']);
			$('input[name=\'email\']').attr('value', ui.item['email']);
			$('input[name=\'telephone\']').attr('value', ui.item['telephone']);
			$('input[name=\'fax\']').attr('value', ui.item['fax']);
				
			html = '<option value="0">None</option>'; 
				
			for (i in  ui.item['address']) {
				html += '<option value="' + ui.item['address'][i]['address_id'] + '">' + ui.item['address'][i]['firstname'] + ' ' + ui.item['address'][i]['lastname'] + ', ' + ui.item['address'][i]['address_1'] + ', ' + ui.item['address'][i]['city'] + ', ' + ui.item['address'][i]['country'] + '</option>';
			}
			
			$('select[name=\'shipping_address\']').html(html);
			$('select[name=\'payment_address\']').html(html);
			
			$('select[name=\'customer_group_id\']').attr('disabled', false);
			$('select[name=\'customer_group_id\']').attr('value', ui.item['customer_group_id']);
			$('select[name=\'customer_group_id\']').trigger('change');
			$('select[name=\'customer_group_id\']').attr('disabled', true); 
							
			return false; 
		},
		focus: function(event, ui) {
			return false;
		}
	});
});

function showCustomerContent() {
	$('#order_product_content').css('display', 'none');
	$('#order_customer_content').css('display', 'block');
	if ($('input[name=\'customer_id\']').val() == '0') {
		$('#order_customer').css('display', 'block');
		$('#customer_customer').css('display', 'none');
	} else {
		$('#order_customer').css('display', 'none');
		$('#customer_customer').css('display', 'block');
	}
};

$('input[name=\'filter_customer\']').live('focus', function(){
	$(this).catcomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							category: item.customer_group,
							label: item.name,
							value: item.customer_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('input[name=\'filter_customer\']').val(ui.item.label);
							
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
});
$('input[name=\'product\']').live('focus', function(){
	$(this).autocomplete({
		delay: 500,
		source: function(request, response) {
			var url = 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term);
			$.ajax({
				url: url,
				dataType: 'json',
				success: function(json) {	
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.product_id,
							model: item.model,
							option: item.option,
							price: item.price
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			handleOptionReturn(ui.item['label'], ui.item['value'], ui.item['option']);
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
});

function handleOptionReturn(product_name, product_id, product_option) {
	$('input[name=\'product\']').attr('value', product_name);
	$('input[name=\'product_id\']').attr('value', product_id);
	
	if (product_option != '') {
		html = '';

		for (var i = 0; i < product_option.length; i++) {
			var option = product_option[i];
			
			if (option['type'] == 'select') {
				html += '<div id="option-' + option['product_option_id'] + '">';
				
				if (option['required']) {
					html += '<span class="required">*</span> ';
				}
			
				html += option['name'] + '<br />';
				html += '<select name="option[' + option['product_option_id'] + ']">';
				// html += '<option value="">Select</option>';
			
				for (j = 0; j < option['option_value'].length; j++) {
					option_value = option['option_value'][j];
					
					html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];
					
					if (option_value['price']) {
						html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
					}
					
					html += '</option>';
				}
					
				html += '</select>';
				html += '</div>';
				html += '<br />';
			}
			
			if (option['type'] == 'radio') {
				html += '<div id="option-' + option['product_option_id'] + '">';
				
				if (option['required']) {
					html += '<span class="required">*</span> ';
				}
			
				html += option['name'] + '<br />';
				html += '<select name="option[' + option['product_option_id'] + ']">';
				//html += '<option value="">Select</option>';
			
				for (j = 0; j < option['option_value'].length; j++) {
					option_value = option['option_value'][j];
					
					html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];
					
					if (option_value['price']) {
						html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
					}
					
					html += '</option>';
				}
					
				html += '</select>';
				html += '</div>';
				html += '<br />';
			}
				
			if (option['type'] == 'checkbox') {
				html += '<div id="option-' + option['product_option_id'] + '">';
				
				if (option['required']) {
					html += '<span class="required">*</span> ';
				}
				
				html += option['name'] + '<br />';
				
				for (j = 0; j < option['option_value'].length; j++) {
					option_value = option['option_value'][j];
					
					html += '<input type="checkbox" name="option[' + option['product_option_id'] + '][]" value="' + option_value['product_option_value_id'] + '" id="option-value-' + option_value['product_option_value_id'] + '" />';
					html += '<label for="option-value-' + option_value['product_option_value_id'] + '">' + option_value['name'];
					
					if (option_value['price']) {
						html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
					}
					
					html += '</label>';
					html += '<br />';
				}
				
				html += '</div>';
				html += '<br />';
			}
		
			if (option['type'] == 'image') {
				html += '<div id="option-' + option['product_option_id'] + '">';
				
				if (option['required']) {
					html += '<span class="required">*</span> ';
				}
			
				html += option['name'] + '<br />';
				html += '<select name="option[' + option['product_option_id'] + ']">';
				// html += '<option value="">Select</option>';
			
				for (j = 0; j < option['option_value'].length; j++) {
					option_value = option['option_value'][j];
					
					html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];
					
					if (option_value['price']) {
						html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
					}
					
					html += '</option>';
				}
					
				html += '</select>';
				html += '</div>';
				html += '<br />';
			}
					
			if (option['type'] == 'text') {
				html += '<div id="option-' + option['product_option_id'] + '">';
				
				if (option['required']) {
					html += '<span class="required">*</span> ';
				}
				
				html += option['name'] + '<br />';
				html += '<input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['option_value'] + '" />';
				html += '</div>';
				html += '<br />';
			}
			
			if (option['type'] == 'textarea') {
				html += '<div id="option-' + option['product_option_id'] + '">';
				
				if (option['required']) {
					html += '<span class="required">*</span> ';
				}
				
				html += option['name'] + '<br />';
				html += '<textarea name="option[' + option['product_option_id'] + ']" cols="40" rows="5">' + option['option_value'] + '</textarea>';
				html += '</div>';
				html += '<br />';
			}
			
			if (option['type'] == 'file') {
				html += '<div id="option-' + option['product_option_id'] + '">';
				
				if (option['required']) {
					html += '<span class="required">*</span> ';
				}
				
				html += option['name'] + '<br />';
				html += '<a id="button-option-' + option['product_option_id'] + '" class="pos_button">Upload</a>';
				html += '<input type="hidden" name="option[' + option['product_option_id'] + ']" value="' + option['option_value'] + '" />';
				html += '</div>';
				html += '<br />';
			}
			
			if (option['type'] == 'date') {
				html += '<div id="option-' + option['product_option_id'] + '">';
				
				if (option['required']) {
					html += '<span class="required">*</span> ';
				}
				
				html += option['name'] + '<br />';
				html += '<input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['option_value'] + '" class="date" />';
				html += '</div>';
				html += '<br />';
			}
			
			if (option['type'] == 'datetime') {
				html += '<div id="option-' + option['product_option_id'] + '">';
				
				if (option['required']) {
					html += '<span class="required">*</span> ';
				}
				
				html += option['name'] + '<br />';
				html += '<input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['option_value'] + '" class="datetime" />';
				html += '</div>';
				html += '<br />';						
			}
			
			if (option['type'] == 'time') {
				html += '<div id="option-' + option['product_option_id'] + '">';
				
				if (option['required']) {
					html += '<span class="required">*</span> ';
				}
				
				html += option['name'] + '<br />';
				html += '<input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['option_value'] + '" class="time" />';
				html += '</div>';
				html += '<br />';						
			}
		}
		
		$('#option').html('<td class="left">Option</td><td class="left">' + html + '</td>');

		for (i = 0; i < product_option.length; i++) {
			option = product_option[i];
			
			if (option['type'] == 'file') {		
				new AjaxUpload('#button-option-' + option['product_option_id'], {
					action: 'index.php?route=sale/order/upload&token=<?php echo $token; ?>',
					name: 'file',
					autoSubmit: true,
					responseType: 'json',
					data: option,
					onSubmit: function(file, extension) {
						$('#button-option-' + (this._settings.data['product_option_id'] + '-' + this._settings.data['product_option_id'])).after('<img src="view/image/loading.gif" class="loading" />');
					},
					onComplete: function(file, json) {

						$('.error').remove();
						
						if (json['success']) {
							$('input[name=\'option[' + this._settings.data['product_option_id'] + ']\']').attr('value', json['file']);
						}
						
						if (json.error) {
							$('#option-' + this._settings.data['product_option_id']).after('<span class="error">' + json['error'] + '</span>');
						}
						
						$('.loading').remove();	
					}
				});
			}
		}
		
		$('.date').datepicker({dateFormat: 'yy-mm-dd'});
		$('.datetime').datetimepicker({
			dateFormat: 'yy-mm-dd',
			timeFormat: 'h:m'
		});
		$('.time').timepicker({timeFormat: 'h:m'});				
	} else {
		$('#option td').remove();
	}
};

$('select[name=\'customer_group_id\']').live('change', function() {
	var customer_group_id = this.value;

	if ($('input[name='+customer_group_id+'_company_id_display]').val() == '1') {
		$('.company-id-display').show();
	} else {
		$('.company-id-display').hide();
	}

	if ($('input[name='+customer_group_id+'_tax_id_display]').val() == '1') {
		$('.tax-id-display').show();
	} else {
		$('.tax-id-display').hide();
	}
});

$('select[name=\'customer_group_id\']').trigger('change');

$('select[name=\'customer_customer_group_id\']').live('change', function() {
	var customer_group_id = this.value;
	
	if ($('input[name='+customer_group_id+'_customer_company_id_display]').val() == '1') {
		$('.customer-company-id-display').show();
	} else {
		$('.customer-company-id-display').hide();
	}
	if ($('input[name='+customer_group_id+'_customer_tax_id_display]').val() == '1') {
		$('.customer-tax-id-display').show();
	} else {
		$('.customer-tax-id-display').hide();
	}
});

$('select[name=\'customer_customer_group_id\']').trigger('change');

$('#button_custom_cancel').live('click', function() {
	$('#order_product_content').css('display', 'block')
	$('#order_customer_content').css('display', 'none');
});

$('#customer_button_cancel').live('click', function() {
	$('#order_product_content').css('display', 'block')
	$('#order_customer_content').css('display', 'none');
});

$('#customer_button_save').live('click', function() {
	saveCustomer();
});

function completeOrder() {
	$('#order_status').val(5);
	$('#order_status').trigger('change');
}

$('#order_status').live('change', function() {
	var order_id = parseInt($('#order_id').text(), 10);
	var data = {'order_id': order_id, 'order_status_id':$(this).val()};
	$.ajax({
		url: 'index.php?route=module/pos/saveOrderStatus&token=<?php echo $token; ?>',
		type: 'post',
		data: data,
		dataType: 'json',
		beforeSend: function() {
			removeMessage();
			showMessage('pos_attention', null, null);
		},
		success: function(json) {
			if (json['success']) {
				removeMessage();
				showMessage('pos_success', json['success'], null);
			}
		}
	});
});

function afterPrintReceipt() {
	$('#pos_print').dialog('close');
}

$('#product tr').live('click', function() {
	index = $('#product tr').index($(this));
	if (index >= 0) {
		indexPre = $('#product tr').index($('input[name=order_product_id]:checked', '#product').closest('tr'));
		moveSelect(indexPre, index);
	}
});

$('#total_tr td:lt(2)').live('click', function() {
	toggleTotalDetails();
});

function toggleTotalDetails() {
	$('#totals_details').slideToggle('slow');
	var bgImg = $('#payment_total').css('background-image');
	if (bgImg.indexOf('_down') >= 0) {
		$('#payment_total').css('background-image', bgImg.replace('_down', '_up'));
	} else {
		$('#payment_total').css('background-image', bgImg.replace('_up', '_down'));
	}
};

$('#product input[type=\'radio\']').live('click', function () {
	indexPre = $('#radio_selected_index').val();
	index = $('#product tr').index($('input[name=order_product_id]:checked', '#product').closest('tr'));
	if (index >= 0) {
		moveSelect(indexPre, index);
	}
});

$('#button_up').live('click', function() {
	index = $('#product tr').index($('input[name=order_product_id]:checked', '#product').closest('tr'));
	indexPre = index;
	if (index == -1) {
		index = 0;
	} else if (index == 0) {
		index = $('#product tr').length -1;
	} else {
		index --;
	}
	moveSelect(indexPre, index);
});

$('#button_down').live('click', function() {
	index = $('#product tr').index($('input[name=order_product_id]:checked', '#product').closest('tr'));
	indexPre = index;
	if (index == -1 || (index == $('#product tr').length -1)) {
		index = 0;
	} else {
		index ++;
	}
	
	moveSelect(indexPre, index);
});

function disableActions() {
	$('#button_plus').unbind('click').attr('disabled', 'disabled');
	$('#button_minus').unbind('click').attr('disabled', 'disabled');
	$('#button_equal').unbind('click').attr('disabled', 'disabled');
	$('#button_delete').unbind('click').attr('disabled', 'disabled');
}

$('#button_plus, #button_minus, #button_delete, #button_voucher, #button_product').live('click', function() {
	checkAndSaveOrder($(this).attr('id'), 0);
});

$('#button_equal').live('click', function() {
	index = $('#product tr').index($('input[name=order_product_id]:checked', '#product').closest('tr'));
	if (index >= 0 && index < $('#product tr').length -1) {
		$('#keyboard').keyboard({layout : 'num', restrictInput : true,
			accepted : function(e, keyboard, el){
				value = el.value;
				if (value > 0) {
					checkAndSaveOrder('button_equal', value);
				}
			}
		});

		$('#keyboard_div').css('display', 'inline');
		$('#keyboard').attr('value', '');
		$('#keyboard').getkeyboard().reveal();
		$('#keyboard_div').css('display', 'none');
	}
});

function saveCustomer() {
	var data = '#customer_customer input[type=\'text\'], #customer_customer input[type=\'hidden\'], #customer_customer input[type=\'password\'], #customer_customer input[type=\'radio\']:checked, #customer_customer input[type=\'checkbox\']:checked, #customer_customer select, #customer_customer textarea';
	var url = 'index.php?route=module/pos/save_customer&token=<?php echo $token; ?>';
	var customer_id = $('input[name=customer_id]').val();
	url += '&customer_id=' + customer_id;
	$.ajax({
		url: url,
		type: 'post',
		data: $(data),
		dataType: 'json',
		beforeSend: function() {
			removeMessage();
			showMessage('pos_attention', null, null);
		},
		success: function(json) {
			$('#order_product_content').css('display', 'block');
			$('#order_customer_content').css('display', 'none');
			if (json['success']) {
				removeMessage();
				showMessage('pos_success', json['success'], null);
				var name = $('input[name=\'customer_firstname\']').val() + " " + $('input[name=\'customer_lastname\']').val();
				$('#general_customer_name').text(name);
				if (json['hasAddress'] && json['hasAddress'] == '2') {
					$('#address_warning').remove();
				}
			}
		},
		error: function(json) {
			$('#order_product_content').css('display', 'block');
			$('#order_customer_content').css('display', 'none');
			if (json['responseText']) {
				removeMessage();
				var index = json['responseText'].indexOf('{');
				showMessage('pos_warning', json['responseText'].substr(0, index), null);
				var name = $('input[name=\'customer_firstname\']').val() + " " + $('input[name=\'customer_lastname\']').val();
				$('#general_customer_name').text(name);
			}
		}
	});
};

function checkAndSaveOrder(eleId, quantity) {
	if ($('#order_id').text() == '') return false;
	
	if (eleId == 'button_product') {
		var prodQtyInput = $('#product_new input[name=\'quantity\']');
		var prodQty = prodQtyInput.val();
		prodQty = posParseFloat(prodQty);
		// check if zero is in the text
		if (prodQty <= 0) {
			prodQtyInput.css('border', 'solid 2px #FF0000');
			prodQtyInput.attr('alt', '0');
			prodQtyInput.attr('title', '0');
			return false;
		} else {
			prodQtyInput.css('border', '');
			prodQtyInput.attr('alt', '');
			prodQtyInput.attr('title', '');
		}
	}

	var data = {};

	$("#product input[type=\'hidden\']").each(function() {
		data[$(this).attr("name")] = $(this).val();
	});

	data['order_id'] = parseInt($('#order_id').text(), 10);
	
	var index = -1;
	if (eleId == 'button_plus' || eleId == 'button_minus' || eleId == 'button_delete' || eleId == 'button_equal') {
		index = $('#product tr').index($('input[name=order_product_id]:checked', '#order_product_list').closest('tr'));
		var indexQty = parseInt($('#product tr:eq('+index+')').find('td').eq(3).text());
		if (eleId == 'button_minus') {
			if (indexQty == 1) {
				return false;
			}
		}
		if (index >= 0 && index < $('#product tr').length -1) {
			var order_product_id = $('input[name=order_product_id]:checked').val();
			data['order_product_id'] = order_product_id;
			if (eleId == 'button_delete') {
				data['action'] = 'delete';
			} else {
				data['action'] = 'modify';
			}
			data['quantity'] = quantity;
			if (eleId == 'button_minus') {
				data['quantity'] = indexQty-1;
			} else if (eleId == 'button_plus') {
				data['quantity'] = indexQty+1;
			}
		} else {
			return false;
		}
	} else if (eleId == 'button_product') {
		formData = '#product_new input[type=\'text\'], #product_new input[type=\'hidden\'], #product_new input[type=\'radio\']:checked, #product_new input[type=\'checkbox\']:checked, #product_new select, #product_new textarea';
		$(formData).each(function() {
			data[$(this).attr('name')] = $(this).val();
		});
		data['action'] = 'insert';
	} else if (eleId == 'button_voucher') {
		formData = '#order_voucher_content input[type=\'text\'], #order_voucher_content input[type=\'hidden\'], #order_voucher_content input[type=\'radio\']:checked, #order_voucher_content input[type=\'checkbox\']:checked, #order_voucher_content select, #order_voucher_content textarea';
		data = $(formData);
		data['action'] = 'insert';
	} else if (eleId == 'button_new_order') {
		data['action'] = 'new';
		data['order_id'] = $('#order_general td:eq(1)').text();
	}

	data['store_id'] = '<?php echo $store_id; ?>';
	data['customer_id'] = $('input[name=customer_id]').val();
	data['customer_group_id'] = $('select[name=customer_group_id]').val();
	if (data['customer_id'] != '0') {
		data['customer_group_id'] = $('select[name=customer_customer_group_id]').val();
	}
	
	$.ajax({
		url: 'index.php?route=pos/checkout&token=<?php echo $token; ?>',
		type: 'post',
		data: data,
		dataType: 'json',	
		beforeSend: function() {
			removeMessage();
			showMessage('pos_attention', null, null);
		},			
		success: function(json) {
			// Check for errors
			if (json['error']) {
				removeMessage();
				if (json['error']['warning']) {
					showMessage('pos_warning', json['error']['warning'], null);
				}

				// Products
				if (json['error']['product']) {
					if (json['error']['product']['option']) {	
						for (i in json['error']['product']['option']) {
							$('#option-' + i).after('<span class="error">' + json['error']['product']['option'][i] + '</span>');
						}						
					}
					
					if (json['error']['product']['stock']) {
						showMessage('pos_warning', json['error']['product']['stock'], null);
					}	
											
					if (json['error']['product']['minimum']) {	
						for (i in json['error']['product']['minimum']) {
							showMessage('pos_warning', json['error']['product']['minimum'][i], null);
						}						
					}
				} else {
					$('input[name=\'product\']').attr('value', '');
					$('input[name=\'product_id\']').attr('value', '');
					$('#option td').remove();			
					$('input[name=\'quantity\']').attr('value', '1');
				}
				
				// Voucher
				if (json['error']['vouchers']) {
					if (json['error']['vouchers']['from_name']) {
						$('input[name=\'from_name\']').after('<span class="error">' + json['error']['vouchers']['from_name'] + '</span>');
					}	
					
					if (json['error']['vouchers']['from_email']) {
						$('input[name=\'from_email\']').after('<span class="error">' + json['error']['vouchers']['from_email'] + '</span>');
					}	
								
					if (json['error']['vouchers']['to_name']) {
						$('input[name=\'to_name\']').after('<span class="error">' + json['error']['vouchers']['to_name'] + '</span>');
					}	
					
					if (json['error']['vouchers']['to_email']) {
						$('input[name=\'to_email\']').after('<span class="error">' + json['error']['vouchers']['to_email'] + '</span>');
					}	
					
					if (json['error']['vouchers']['amount']) {
						$('input[name=\'amount\']').after('<span class="error">' + json['error']['vouchers']['amount'] + '</span>');
					}	
				} else {
					$('input[name=\'from_name\']').attr('value', '');	
					$('input[name=\'from_email\']').attr('value', '');
					$('input[name=\'to_name\']').attr('value', '');
					$('input[name=\'to_email\']').attr('value', '');
					$('textarea[name=\'message\']').attr('value', '');	
					$('input[name=\'amount\']').attr('value', '25.00');
				}
				
				// Coupon
				if (json['error']['coupon']) {
					showMessage('pos_warning', json['error']['coupon'], null);
				}
				
				// Voucher
				if (json['error']['voucher']) {
					showMessage('pos_warning', json['error']['voucher'], null);
				}
				
				// Reward Points		
				if (json['error']['reward']) {
					showMessage('pos_warning', json['error']['reward'], null);
				}	
			} else {
				$('input[name=\'product\']').attr('value', '');
				$('input[name=\'product_id\']').attr('value', '');
				$('#option td').remove();	
				$('input[name=\'quantity\']').attr('value', '1');	
				
				$('input[name=\'from_name\']').attr('value', '');	
				$('input[name=\'from_email\']').attr('value', '');	
				$('input[name=\'to_name\']').attr('value', '');
				$('input[name=\'to_email\']').attr('value', '');	
				$('textarea[name=\'message\']').attr('value', '');	
				$('input[name=\'amount\']').attr('value', '25.00');	
			}
			
			if (json['success'] && data['action'] == 'insert' && !json['order_product']) {
				// no product is found
				$('.pos_success, .pos_warning, .pos_attention, .error').remove();
				<?php if(isset($text_no_product)) { ?>
				showMessage('pos_warning', '<?php echo $text_no_product; ?>', null);
				<?php } ?>
			} else if (json['success']) {
				// save order
				var saveData = {};

				saveData['order_id'] = data['order_id'];
				if (json['order_total'] != '') {
					saveData['order_total'] = json['order_total'];
				}
				
				if (json['order_product']) {
					saveData['order_product'] = json['order_product'];
					if (saveData['order_product']['action'] == 'modify') {
						// find the quantity difference
						for (i = 0; i < $('#product tr').length-1; i++) {
							if ($('#product tr:eq('+i+')').find('input[type=\'radio\']').val() == saveData['order_product']['order_product_id']) {
								var curQuantity = parseInt($('#product tr:eq('+i+')').find('td').eq(3).text());
								saveData['order_product']['quantity_change'] = parseInt(saveData['order_product']['quantity']) - curQuantity;
								break;
							}
						}
					}
				} else {
					saveData['action'] = data['action'];
					saveData['order_product_id'] = data['order_product_id'];
					if (saveData['action'] == 'modify') {
						saveData['total'] = json['total'];
						// find the quantity difference
						var option_count = 0;

						for (i = 0; i < $('#product tr').length-1; i++) {
							if ($('#product tr:eq('+i+')').find('input[type=\'radio\']').val() == saveData['order_product_id']) {
								var curQuantity = parseInt($('#product tr:eq('+i+')').find('td').eq(3).text());
								if (eleId == 'button_plus') {
									saveData['quantity'] = curQuantity + 1;
									saveData['quantity_change'] = 1;
								} else if (eleId == 'button_minus') {
									saveData['quantity'] = curQuantity - 1;
									saveData['quantity_change'] = -1;
								} else {
									saveData['quantity'] = quantity;
									saveData['quantity_change'] = quantity-curQuantity;
								}
								
								saveData['product_id'] = $('#product tr:eq('+i+') input[name$=\'[product_id]\']').val();
								saveData['option'] = {};
								$('#product tr:eq('+i+') input[name$=\'[product_option_value_id]\']').each(function() {
									saveData['option'][option_count] = {};
									saveData['option'][option_count]['product_option_value_id'] = $(this).val();
									option_count++;
								});
								break;
							}
						}
					} else {
						var option_count = 0;

						for (i = 0; i < $('#product tr').length-1; i++) {
							if ($('#product tr:eq('+i+')').find('input[type=\'radio\']').val() == saveData['order_product_id']) {
								saveData['quantity'] = parseInt($('#product tr:eq('+i+')').find('td').eq(3).text());
								saveData['product_id'] = $('#product tr:eq('+i+') input[name$=\'[product_id]\']').val();
								saveData['option'] = {};
								$('#product tr:eq('+i+') input[name$=\'[product_option_value_id]\']').each(function() {
									saveData['option'][option_count] = {};
									saveData['option'][option_count]['product_option_value_id'] = $(this).val();
									option_count++;
								});
								break;
							}
						}
					}
				}
				
				// call modify order to save order
				var modifyUrl = 'index.php?route=module/pos/modifyOrder&token=<?php echo $token; ?>';
				$.ajax({
					url: modifyUrl,
					type: 'post',
					data: saveData,
					dataType: 'json',
					success: function(save_json) {
						// refresh order_product list and total list
						if (eleId == 'button_plus' || eleId == 'button_minus' || eleId == 'button_equal') {
							value = parseInt($('#product tr:eq('+index+')').find("td").eq(3).text());
							if (eleId == 'button_plus') value += 1;
							else if (eleId == 'button_minus') value -= 1;
							else value = quantity;
							$('#product tr:eq('+index+')').find("td").eq(3).text(''+value);
							$('#product tr:eq('+index+')').find("input[name$='[quantity]']").attr('value', value);
							$('#product tr:eq('+index+')').find("td").eq(5).text(json['total_text']);
						} else if (eleId == 'button_delete') {
							$('#product tr:eq('+index+')').remove();
							moveSelect(-1, 0);
						} else if (eleId == 'button_product') {
							if (json['order_product']) {
								if (json['order_product']['action'] == 'modify') {
									// it's actually a modification
									for (i = 0; i < $('#product tr').length-1; i++) {
										if (json['order_product']['order_product_id'] == $('#product tr:eq('+i+')').find("input[type=\'radio\']").val()) {
											$('#product tr:eq('+i+')').find("td").eq(3).text(''+json['order_product']['quantity']);
											$('#product tr:eq('+i+') input[name$=\'[quantity]\']').attr('value', json['order_product']['quantity']);
											$('#product tr:eq('+i+')').find("td").eq(5).text(''+json['order_product']['total_text']);
											break;
										}
									}
								} else {
									// append the product row
									var new_row_num = $('#product tr').length -1;
									new_row_id = 'product-row' +  new_row_num;
									html = '<tr id="' + new_row_id + '">';
									html += '<td style="text-align: center;"><input type="radio" name="order_product_id" value="' + save_json['order_product_id'] + '" />';
									html += ' <input type="hidden" name="order_product[' + new_row_num + '][order_product_id]" value="' + save_json['order_product_id'] +'" />';
									html += ' <input type="hidden" name="order_product[' + new_row_num + '][product_id]" value="' + json['order_product']['product_id'] +'" /></td>';
									html += '<td class="left">' + json['order_product']['name'];
									if (json['order_product']['option']) {
											for (i in json['order_product']['option']) {
									html +=		'<br />&nbsp;<small> - ' + json['order_product']['option'][i]['name'] + ': ' + json['order_product']['option'][i]['option_value'] + '</small>';
									html +=		' <input type="hidden" name="order_product[' + new_row_num + '][order_option][' + i + '][product_option_id]" value="' + json['order_product']['option'][i]['product_option_id'] + '" />';
									html +=		' <input type="hidden" name="order_product[' + new_row_num + '][order_option][' + i + '][product_option_value_id]" value="' + json['order_product']['option'][i]['product_option_value_id'] + '" />';
									html +=		' <input type="hidden" name="order_product[' + new_row_num + '][order_option][' + i + '][value]" value="' + json['order_product']['option'][i]['option_value'] + '" />';
									html +=		' <input type="hidden" name="order_product[' + new_row_num + '][order_option][' + i + '][type]" value="' + json['order_product']['option'][i]['type'] + '" />';
											}
									}
									html += '</td>';
									html += '<td class="left">' + json['order_product']['model'] + '</td>';
									html += '<td class="right">' + json['order_product']['quantity'] + '</td>';
									html += ' <input type="hidden" name="order_product[' + new_row_num + '][quantity]" value="' + json['order_product']['quantity'] +'" />';
									html += '<td class="right" id="price_text-' + new_row_num + '">' + json['order_product']['price_text'] + '</td>';
									html += ' <input type="hidden" name="order_product[' + new_row_num + '][price]" value="' + json['order_product']['price'] +'" />';
									html += '<td class="right">' + json['order_product']['total_text'] + '</td>';
									html += '</tr>';
									$(html).insertBefore('#new_product_row');
								}
								moveSelect(-1, $('#product tr').length-1);
							}
						} else {
							moveSelect(-1, 0);
						}
						
						if (json['order_total']) {
							var total_row = 0;
							html = '';
							$("#total tr").each(function() {
								$(this).remove();
							});
							
							var total_title = '';
							var total_text = '';
							var total_value = 0;
							var subtotal_value = 0;
							var total_discount = null;
							for (i = 0; i < json['order_total'].length; i++ ) {
								var total = json['order_total'][i];
								
								html += '<tr id="total-row' + total_row + '">';
								if (total['code'] == 'total') {
									html += '  <td class="center" width="60%"><span style="font: bold 15px Arial, Helvetica, sans-serif;">' + total['title'] + ':</span></td>';
									html += '  <td class="center" width="40%"><span style="font: bold 15px Arial, Helvetica, sans-serif;">' + total['text'] + '</span></td>';
									total_title = total['title'];
									total_text = total['text'];
									total_value = total['value'];
								} else {
									html += '  <td class="center" width="60%">' + total['title'] + ':</td>';
									html += '  <td class="center" width="40%">' + total['text'] + '</td>';
									if (total['code'] == 'sub_total') {
										subtotal_value = total['value'];
									}
								}
								html += '</tr>';
								
								total_row++;
							}
							
							$('#total').html(html);
							
							$('#total_tr td:eq(0) span').text(total_title+':');
							$('#total_tr td:eq(1) span').text(total_text);
						} else {
							html  = '</tr>';
							html += '  <td colspan="5" class="center">No result</td>';
							html += '</tr>';	

							$('#total').html(html);					
						}
						if (eleId != 'button_new_order') {
							calcDueAmount();
						}
						
						if (save_json['enable_openbay'] && save_json['enable_openbay'] == '1') {
							// save the product page
							url = 'index.php?route=catalog/product/update&token=<?php echo $token; ?>&product_id='+save_json['product_id'];
							$.ajax({
								url: url,
								type: 'get',
								success: function(html) {
									$('#hidden_div').html($(html).find('div[id=\'content\']').html());
									var product_change_url = $('#hidden_div').find('form[id=\'form\']').attr('action');
									var method = $('#hidden_div').find('form[id=\'form\']').attr('method');
									var product_change_data = '#hidden_div input[type=\'text\'], #hidden_div input[type=\'hidden\'], #hidden_div input[type=\'password\'], #hidden_div input[type=\'radio\']:checked, #hidden_div input[type=\'checkbox\']:checked, #hidden_div select, #hidden_div textarea';
									$.ajax({
										url: product_change_url,
										type: method,
										data: $(product_change_data),
										dataType: 'json',
										converters: {
											'text json': true
										},
										success: function(html) {
											removeMessage();
											if (save_json['success']) {
												showMessage('pos_success', save_json['success'], null);
											}
										}
									});
								}
							});
						} else {
							removeMessage();
							if (save_json['success']) {
								showMessage('pos_success', save_json['success'], null);
							}
						}
					}
				});
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
};

function not_implement_yet() {
	alert("Not available yet!");
};

$('#tendered_amount').live('keydown', function(event) {
	amountInputOnly(event);
});

function amountInputOnly(event) {
	// Allow: backspace, delete, tab, escape, and enter
	if ( event.keyCode == 46 || event.keyCode == 110 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || event.keyCode == 190 ||
		 // Allow: Ctrl+A
		(event.keyCode == 65 && event.ctrlKey === true) || 
		 // Allow: home, end, left, right
		(event.keyCode >= 35 && event.keyCode <= 39)) {
		// let it happen, don't do anything
		return;
	} else {
		// Ensure that it is a number and stop the keypress
		if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
			event.preventDefault(); 
		}
	}
};

function addPayment() {
	var amount = $('#tendered_amount').val();
	var dueAmount = $('#payment_due_amount').text();
	dueAmount = posParseFloat(dueAmount);
	if (dueAmount <= 0) {
		// nothing can be added
		return false;
	} else {
		// check if zero is in the text
		if (posParseFloat(amount) == 0 && $('#payment_type').val() != 'purchase_order') {
			$('#tendered_amount').css('border', 'solid 2px #FF0000');
			$('#tendered_amount').attr('alt', '0');
			$('#tendered_amount').attr('title', '0');
			return false;
		} else {
			$('#tendered_amount').css('border', '');
			$('#tendered_amount').attr('alt', '');
			$('#tendered_amount').attr('title', '');
		}
	}
	// remove warning or error tips
	$('#payment_warning_tips').remove();
	$('#payment_error_tips').remove();
	
	processAddPayment(amount, '');
};

function processAddPayment(amount, noteAppend) {
	var note = $('#payment_note').val();
	if (noteAppend != '') {
		note += ' ' + noteAppend;
	}
	var order_id = parseInt($('#order_id').text(), 10);
	var type = $('#payment_type option:selected').text();
	var d = new Date();
	var order_payment_id = order_id + '_' + d.getTime();
	
	var url = 'index.php?route=module/pos/addOrderPayment&token=<?php echo $token; ?>&order_payment_id='+order_payment_id+'&order_id='+order_id+'&payment_type='+type+'&tendered_amount='+amount+'&payment_note='+note;

	$.ajax({
		url: url,
		dataType: 'json',
		beforeSend: function() {
			$('#button_add_payment').hide();
			$('#button_add_payment').before('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');	
		},
		complete: function() {
			$('.loading').remove();
			$('#button_add_payment').show();
		},
		success: function(json) {
			if (json['error']) {
				$('#order_payment_list').prepend('<div class="pos_warning" style="display: none;">' + json['error'] + '</div>');
				$('.pos_warning').fadeIn('slow');
			}
			else {
				// translate the amount to money format
				// get rid of non digital first
				amount = posParseFloat(amount);
				amount = formatMoney(amount);
				var tr_element = '<tr id="' + order_payment_id +'"><td class="left" width="30%">' + type + '</td><td class="left" width="25%">' + amount + '</td><td class="left" width="35%" style="-ms-word-break: break-all; word-break: break-all; word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto;">' + note + '</td><td align="center" width="10%"><a onclick="deletePayment(\''+order_payment_id+'\');"><img src="view/image/pos/delete_off.png" width="22" height="22"/></a></td></tr>'
				$(tr_element).insertAfter('#button_add_payment_tr');
				// clear the current inputs
				var totalDue = calcDueAmount();
			}
			$('#payment_type option:eq(0)').attr('selected', true);
			$('#payment_type').trigger('change');
			$('#payment_note').attr('value', '');
		}
	});
}


function calcDueAmount() {
	// count the total quantity
	var totalQuantity = 0;
	for (i = 0; i < $('#product tr').length-1; i++) {
		totalQuantity += parseInt($('#product tr:eq('+i+')').find('td').eq(3).text());
	}
	$('#items_in_cart').text(totalQuantity);
	
	var totalNum = $('#total tr').length;
	var totalAmount = 0;
	if (totalNum > 0) {
		var totalText = $('#payment_total').find('span').text();
		totalAmount = posParseFloat(totalText);
	}
	var container = document.getElementById('payment_list');
	var rows = container.getElementsByTagName('TR');
	var totalPaid = 0;
	for (i = 1; i < rows.length; i++) {
		// ignore the first line
		rowAmount = rows[i].getElementsByTagName('TD')[1].innerHTML;
		rowAmount = posParseFloat(rowAmount);
		totalPaid += rowAmount;
	}
	totalDue = totalAmount - totalPaid;
	if (totalDue < 0) {
		$('#payment_due_amount').text(formatMoney(0));
		$('#payment_change').find('span').text(formatMoney(0-totalDue));
		$('#tendered_amount').attr('value', '0');
	} else {
		$('#payment_due_amount').text(formatMoney(totalDue));
		$('#payment_change').find('span').text(formatMoney(0));
		$('#tendered_amount').attr('value', posParseFloat(formatMoney(totalDue)));
	}
	if (totalDue < 0.01) {
		// change color to green
		$('#payment_due_amount').css("color", "green");
	} else {
		// change color to red
		$('#payment_due_amount').css("color", "red");
	}
	return totalDue;
};

function formatMoney(number, places, thousand, decimal) {
	// get the currency sign
	var orderAmount = $('#payment_due_amount').text();
	var symbol_left = orderAmount.charAt(0);
	if (symbol_left >= '0' && symbol_left <= '9' || symbol_left == '-') symbol_left = '';
	var symbol_right = orderAmount.charAt(orderAmount.length-1);
	if (symbol_right >= '0' && symbol_right <= '9') symbol_right = '';

	number = number || 0;
	places = !isNaN(places = Math.abs(places)) ? places : 2;
	thousand = thousand || ",";
	decimal = decimal || ".";
	var negative = number < 0 ? "-" : "",
	i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
	j = (j = i.length) > 3 ? j % 3 : 0;
	return symbol_left + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "") + symbol_right;
};

function country(element, index, zone_id) {
  if (element.value != '') {
		$.ajax({
			url: 'index.php?route=sale/customer/country&token=<?php echo $token; ?>&country_id=' + element.value,
			dataType: 'json',
			beforeSend: function() {
				$('select[name=\'customer_address[' + index + '][country_id]\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
			},
			complete: function() {
				$('.wait').remove();
			},			
			success: function(json) {
				if (json['postcode_required'] == '1') {
					$('#postcode-required' + index).show();
				} else {
					$('#postcode-required' + index).hide();
				}
				
				html = '<option value="">Select</option>';
				
				if (json['zone'] != '') {
					for (i = 0; i < json['zone'].length; i++) {
						html += '<option value="' + json['zone'][i]['zone_id'] + '"';
						
						if (json['zone'][i]['zone_id'] == zone_id) {
							html += ' selected="selected"';
						}
		
						html += '>' + json['zone'][i]['name'] + '</option>';
					}
				} else {
					html += '<option value="0">No result</option>';
				}
				
				$('select[name=\'customer_address[' + index + '][zone_id]\']').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
};

function addAddress() {	
	var address_row = $('#vtabs').find('a[href^=\'#tab_address_\']').length+1;
	html  = '<div id="tab_address_' + address_row + '" class="vtabs-content" style="display: none;">';
	html += '  <input type="hidden" name="customer_address[' + address_row + '][address_id]" value="" />';
	html += '  <table class="form">'; 
	html += '    <tr>';
    html += '	   <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>';
    html += '	   <td><input type="text" name="customer_address[' + address_row + '][firstname]" value="" /></td>';
    html += '    </tr>';
    html += '    <tr>';
    html += '      <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>';
    html += '      <td><input type="text" name="customer_address[' + address_row + '][lastname]" value="" /></td>';
    html += '    </tr>';
    html += '    <tr>';
    html += '      <td><?php echo $entry_company; ?></td>';
    html += '      <td><input type="text" name="customer_address[' + address_row + '][company]" value="" /></td>';
    html += '    </tr>';	
    html += '    <tr class="customer-company-id-display">';
    html += '      <td><?php echo $entry_company_id; ?></td>';
    html += '      <td><input type="text" name="customer_address[' + address_row + '][company_id]" value="" /></td>';
    html += '    </tr>';
    html += '    <tr class="customer-tax-id-display">';
    html += '      <td><?php echo $entry_tax_id; ?></td>';
    html += '      <td><input type="text" name="customer_address[' + address_row + '][tax_id]" value="" /></td>';
    html += '    </tr>';			
    html += '    <tr>';
    html += '      <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>';
    html += '      <td><input type="text" name="customer_address[' + address_row + '][address_1]" value="" /></td>';
    html += '    </tr>';
    html += '    <tr>';
    html += '      <td><?php echo $entry_address_2; ?></td>';
    html += '      <td><input type="text" name="customer_address[' + address_row + '][address_2]" value="" /></td>';
    html += '    </tr>';
    html += '    <tr>';
    html += '      <td><span class="required">*</span> <?php echo $entry_city; ?></td>';
    html += '      <td><input type="text" name="customer_address[' + address_row + '][city]" value="" /></td>';
    html += '    </tr>';
    html += '    <tr>';
    html += '      <td><span id="postcode_required' + address_row + '" class="required">*</span> <?php echo $entry_postcode; ?></td>';
    html += '      <td><input type="text" name="customer_address[' + address_row + '][postcode]" value="" /></td>';
    html += '    </tr>';
	html += '    <tr>';
    html += '      <td><span class="required">*</span> <?php echo $entry_country; ?></td>';
    html += '      <td><select name="customer_address[' + address_row + '][country_id]" onchange="country(this, \'' + address_row + '\', \'0\');">';
    html += '         <option value="">Select</option>';
    <?php 
		if (isset($customer_countries)) {
			foreach ($customer_countries as $customer_country) { ?>
    html += '         <option value="<?php echo $customer_country['country_id']; ?>"><?php echo addslashes($customer_country['name']); ?></option>';
    <?php }} ?>
    html += '      </select></td>';
    html += '    </tr>';
    html += '    <tr>';
    html += '      <td><span class="required">*</span> <?php echo $entry_zone; ?></td>';
    html += '      <td><select name="customer_address[' + address_row + '][zone_id]"><option value="false"><?php echo $this->language->get('text_none'); ?></option></select></td>';
    html += '    </tr>';
	html += '    <tr>';
    html += '      <td><?php echo $entry_default; ?></td>';
    html += '      <td><input type="radio" name="customer_address[' + address_row + '][default]" value="1" /></td>';
    html += '    </tr>';
    html += '  </table>';
    html += '</div>';
	
	$('#customer_customer').append(html);
	
	$('select[name=\'customer_address[' + address_row + '][country_id]\']').trigger('change');	
	
	$('#address_add').before('<a href="#tab_address_' + address_row + '" id="address_' + address_row + '"><?php echo $tab_address; ?> ' + address_row + '&nbsp;<img src="view/image/delete.png" alt="" onclick="$(\'#vtabs a:first\').trigger(\'click\'); $(\'#address_' + address_row + '\').remove(); $(\'#tab_address_' + address_row + '\').remove(); return false;" /></a>');
		 
	$('.vtabs a').tabs();
	
	$('#address_' + address_row).trigger('click');
	
	address_row++;
};

$('select[name$=\'[country_id]\']').trigger('change');
$('.vtabs a').tabs();

function updateClock() {
	var currentTime = new Date ( );

	var currentHours = currentTime.getHours();
	var currentMinutes = currentTime.getMinutes();
	currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
	var timeOfDay = ( currentHours < 12 ) ? "am" : "pm";
	currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;
	currentHours = ( currentHours == 0 ) ? 12 : currentHours;
	var currentDate = currentTime.getDate();
	currentDate = ( currentDate < 10 ? "0" : "" ) + currentDate;
	var currentMonth = currentTime.getMonth();
	var month_names = [];
<?php
	for ($i = 0; $i < count($text_months); $i++) {
?>
		month_names[<?php echo $i; ?>] = '<?php echo $text_months[$i]; ?>';
<?php
	}
?>
	var month_name = month_names[currentMonth];
	var currentYear = currentTime.getFullYear();
	var currentDay = currentTime.getDay();
	var week_days = [];
<?php
	for ($i = 0; $i < count($text_weeks); $i++) {
?>
		week_days[<?php echo $i; ?>] = '<?php echo $text_weeks[$i]; ?>';
<?php
	}
?>
	var week_day_name = week_days[currentDay];
	
	$('#header_year').text(currentYear);
	$('#header_month').text(month_name);
	$('#header_date').text(currentDate);
	$('#header_week').text(week_day_name);
	$('#header_hour').text(currentHours);
	$('#header_minute').text(currentMinutes);
	$('#header_apm').text(timeOfDay);
};

$('img').live('mouseenter', function() {
	var imgSrc = $(this).attr('src');
	if (imgSrc.indexOf('_off.png') >= 0) {
		$(this).attr('src', imgSrc.replace('_off.png', '_on.png'));
	}
});

$('img').live('mouseleave', function() {
	var imgSrc = $(this).attr('src');
	if (imgSrc.indexOf('_on.png') >= 0) {
		$(this).attr('src', imgSrc.replace('_on.png', '_off.png'));
	}
});

$(function() {
	updateClock();
	setInterval(updateClock,1000);
});

var resizeTimer;
$(window).resize(function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(CheckSizeZoom, 100);
});

function CheckSizeZoom() {
	if ($(window).width() > 1024) {
		var zoomLev = $(window).width() / 1080;
		if (720 * zoomLev > $(window).height() && $(window).height() / 720 > 1) {
			zoomLev = $(window).height() / 720;
		}
		
		if ($(window).width() > 1024 && $(window).height() > 680) {
			if (typeof (document.body.style.zoom) != "undefined" && !$.browser.msie) {
				$(document.body).css('zoom', zoomLev);
			}
		}
		
		$('#divWrap').css('margin', '0 auto');
	} else {
		$(document.body).css('zoom', '');
		$('#divWrap').css('margin', '');
	}
};

function window_print_url(url, data, fn, para) {
	// get the page from url and print it
	if (data['change']) {
		// get the change if there is any
		var change = $('#payment_change').find('span').text();
		change = posParseFloat(change);
		if (change < 0.01) {
			data['change'] = formatMoney(0);
		} else {
			data['change'] = formatMoney(change);
		}
	}
	$.ajax({
		url: url,
		type: 'post',
		data: data,
		dataType: 'json',
		beforeSend: function() {
			$('#pos_print').dialog('open');
		},
		converters: {
			'text json': true
		},
		success: function(html) {
			// send html to iframe for printing
			$('#print_iframe').contents().find('html').html(html);

			setTimeout(function() {
				var currentTime = new Date();
				var hour = currentTime.getHours();
				hour = (hour < 10 ? "0" : "") + hour;
				var minute = currentTime.getMinutes();
				minute = (minute < 10 ? "0" : "") + minute;
				var second = currentTime.getSeconds();
				second = (second < 10 ? "0" : "") + second;
				var month = currentTime.getMonth()+1;
				month = (month < 10 ? "0" : "") + month;
				var year = currentTime.getFullYear();
				var date = currentTime.getDate();
				date = (date < 10 ? "0" : "") + date;
				$('#print_iframe').contents().find('td[id=date_td]').text(month + '/' + date + '/' + year);
				$('#print_iframe').contents().find('td[id=time_td]').text(hour + ':' + minute + ':' + second);
				// append the print script
				if ( $.browser.msie ) {
					$("#print_iframe").get(0).contentWindow.document.execCommand('print', false, null);
				} else {
					$("#print_iframe").get(0).contentWindow.print();
				}
				// call the function to continue
				if (fn) {
					fn(para);
				}
			}, 1000);
		}
	});
};

$('#pos_print').dialog({
	autoOpen: false,
	height: 100,
	modal: true
});

// add for print invoice begin
function printInvoice() {
	// print the invoice
	var order_id = parseInt($('#order_id').text(), 10);
	$('#print_message').text('<?php echo $print_invoice_message; ?>');
	var url = 'index.php?route=sale/order/invoice&token=<?php echo $token; ?>&order_id='+order_id;
	window_print_url(url, {}, afterPrintReceipt, null);
};
// add for print invoice end

function posParseFloat(floatstring) {
	// to take care of different culture with the formatted currency string
	// convert to general thousand point (,) and decimal point (.)
	var fString = ''+floatstring;
	
	return parseFloat(fString.replace(/[^0-9-.]/g, ''));
};

// add for Browse begin
function showCategoryItems(category_id) {
	var data = {'category_id':category_id, 'currency_code':$('input[name=currency_code]').val(), 'currency_value':$('input[name=currency_value]').val()};
	$.ajax({
		url: 'index.php?route=module/pos/getCategoryItemsAjax&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: data,
		beforeSend: function() {
			removeMessage();
			showMessage('pos_attention', null, null);
		},
		complete: function() {
			removeMessage();
		},
		success: function(json) {
			$('#browse_category').empty();
			var tdhtml = '<a onclick="showCategoryItems(\'<?php echo $text_top_category_id; ?>\')"><?php echo $text_top_category_name; ?></a>';
			if (json['path']) {
				for (var i = 0; i < json['path'].length; i++) {
					tdhtml += '&nbsp;>&nbsp;';
					if (i < json['path'].length-1) {
						tdhtml += '<a onclick="showCategoryItems(\'' + json['path'][i]['id'] + '\')">' + json['path'][i]['name'] + '</a>';
					} else {
						tdhtml += json['path'][i]['name'];
					}
				}
			}
			$('#browse_category').html(tdhtml);
			if (json['browse_items']) {
				// set the category path name
				// clean up the display table
				$('#browse_product_div').empty();
				var html = '<table class="list" style="border: 0;">';
				var col_per_row = 3;
				var browse_total = json['browse_items'].length;
				var browse_total_row_no = (browse_total % col_per_row) == 0 ? browse_total / col_per_row : parseInt(browse_total / col_per_row) + 1;
				for (var row = 0; row < browse_total_row_no; row++) {
					html += '<tr>';
					for (var col = 0; col < col_per_row; col++) {
						var index = row*col_per_row+col;
						if (index < json['browse_items'].length) {
							if (json['browse_items'][index]['type'] == 'C') {
								html += '<td class="center" width="' + 100/col_per_row + '%" height="80px" style="padding: 3px 1px 0px 1px; border: 0; background-image: url(\'view/image/pos/category.png\'); background-position: center; background-repeat:no-repeat;">';
								html += '<a onclick="showCategoryItems(\'' + json['browse_items'][index]['id'] + '\')"><img src="' + json['browse_items'][index]['image'] + '" style="max-width: 50px; max-height: 50px; width: auto; height: auto;" /></a>';
								html += '</td>';
							} else {
								html += '<td class="center" width="' + 100/col_per_row + '%" style="padding: 3px 1px 0px 1px; border: 0;">';
								html += '<a onclick="selectProduct(this, \'' + json['browse_items'][index]['id'] + '\', \'' + json['browse_items'][index]['name'] + '\')"><img src="' + json['browse_items'][index]['image'] + '"  style="max-width: 75px; max-height: 75px; width: auto; height: auto;"/></a>';
								html += '<input type="hidden" value="' + json['browse_items'][index]['hasOptions'] + '" />';
								html += '</td>';
							}
						} else {
							html += '<td class="center" width="' + 100/col_per_row + '%" style="padding: 3px 1px 0px 1px; border: 0;"></td>';
						}
					}
					html += '</tr>';
					html += '<tr>';
					for (var col = 0; col < col_per_row; col++) {
						var index = row*col_per_row+col;
						if (index < json['browse_items'].length) {
							if (json['browse_items'][index]['type'] == 'C') {
								html += '<td class="center" width="' + 100/col_per_row + '%" style="padding:0px; vertical-align: top; border: 0;">';
								html += json['browse_items'][index]['name'];
								html += '</td>';
							} else {
								html += '<td class="center" width="' + 100/col_per_row + '%" style="padding:0px; vertical-align: top; border: 0;">';
								html += json['browse_items'][index]['name'] + '<br />';
								html += json['browse_items'][index]['price_text'] + '<br />';
								html += '(' + json['browse_items'][index]['stock_text'] + ')';
								html += '</td>';
							}
						} else {
							html += '<td class="center" width="' + 100/col_per_row + '%" style="padding: 0px; border: 0;"></td>';
						}
					}
					html += '</tr>';
				}
				html += '</table>';
				$('#browse_product_div').html(html);
			}
		}
	});
};

function toggleCategoryTree() {
	// toggle the category tree
};

function selectProduct(anchor, product_id, product_name) {
	// add the given product with the product_id
	$('#product_new input[name=quantity]').val('1');
	$('#product_new input[name=product_id]').val(product_id);
	if ($(anchor).closest('td').find('input').val() == '0') {
		// no option
		checkAndSaveOrder('button_product', 0);
	} else {
		$.ajax({
			url: 'index.php?route=module/pos/getProductOptions&token=<?php echo $token; ?>&product_id=' + product_id,
			type: 'post',
			dataType: 'json',
			data: {},
			beforeSend: function() {
				removeMessage();
				showMessage('pos_attention', null, null);
			},
			complete: function() {
				removeMessage();
			},
			success: function(json) {
				if (json) {
					handleOptionReturn(product_name, product_id, json['option_data']);
					$('#tabs a:first').trigger('click');
				}
			}
		});
	}
}
// add for Browse end