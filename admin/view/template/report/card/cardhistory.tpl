<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Card's Order History</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">Card's Order History</a></li>
        <?php } ?>
      </ul>
<i class="<?php echo $tool_tip_class; ?> " data-toggle="tooltip" style="<?php echo $tool_tip_style; ?>" title="<?php echo $tool_tip; ?>"></i>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i>Card's Order History</h3>
		<button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -10px;"><i class="fa fa-download"></i>Download</button>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start">Start Date</label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-end">End Date</label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
			  
               <div class="form-group">
                <label class="control-label" for="input-status">Grower ID</label>
				<input type="text" name="filter_grower_id" value="<?php echo $filter_grower_id; ?>" placeholder="Grower ID"  id="input-filter_grower_id" class="form-control" />
               
              </div>
              
            
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-status">Unit <?php echo $filter_unit_id; ?></label>
                <select  name="filter_unit_id"  id="input-units" style="width: 100%;" class="select2 form-control">
                  <option value="0">Select Unit </option>
                 
                  <?php foreach ($order_units as $unit) { ?>
                  <?php if ($unit['unit_id'] == $filter_unit_id) { ?>
                  <option value="<?php echo $unit['unit_id']; ?>" selected="selected"><?php echo $unit['unit_name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $unit['unit_id']; ?>"><?php echo $unit['unit_name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                   
                </select>
              </div>
              
            
               <div class="form-group">
                <label class="control-label" for="input-status">Card Number</label>
                <input type="text" name="filter_card_number" value="<?php echo $filter_card_number; ?>" placeholder="Card Number"  id="input-filter_card_number" class="form-control" />
              </div>
              
			  <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
			
			
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
			  <td class="text-left">Card Number</td>
			  <td class="text-left">Grower ID</td>
			  <td class="text-left">Unit</td>
                <td class="text-left">Store Name</td>
                <td class="text-left">Date</td>
                <td class="text-right">Invoice No</td>
				<td class="text-right">Payment Method</td>
                <td class="text-right">Order Total </td>
                <td class="text-right">Tagged Amount</td>
                <td class="text-right">Cash Amount </td>
				  <td class="text-right">Subsidy Amount </td>
                
              </tr>
            </thead>
            <tbody>
              <?php if ($products) { ?>
              <?php foreach ($products as $product) { ?>
              <tr>
			  <td class="text-left"><?php echo $product['card_serial_no']; ?></td>
			  <td class="text-left"><?php echo $product['grower_id']; ?></td>
			  <td class="text-left"><?php echo $product['unit_name']; ?></td>
                <td class="text-left"><?php echo $product['storename']; ?></td>
                <td class="text-left"><?php echo $product['datea']; ?></td>
                
                <td class="text-right" >
				
					<a href="#" data-toggle="modal" data-target="#myModal2" 
				onclick="return productdtl('<?php echo $product['order_id']; ?>','<?php echo strtotime($product['date_added']); ?>','<?php echo $product['date_added']; ?>');">
				
						<?php echo $product['order_id']; ?>
					</a>
				
				</td>
				<td class="text-right"><?php echo $product['payment_method']; ?></td>
                 <td class="text-right"><?php echo $product['total']; ?></td>
                  <td class="text-right"><?php echo $product['tagged']; ?></td>
					<td class="text-right"><?php echo $product['cash']; ?></td>
                    <td class="text-right"><?php echo $product['subsidy']; ?></td>
					
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
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
  
  <div id="myModal2" class="modal fade" role="dialog">
<div class="modal-dialog" >
<div class="modal-content">

	<div class="modal-header" style="height:60px;">
	<button type="button" class="close pull-right" data-dismiss="modal">&times;</button>
	<label>Order Id  :&nbsp;&nbsp;&nbsp;</label><label id="orderid"></label>&nbsp;&nbsp;&nbsp;&nbsp;<label>ORDER DATE  :</label><label id="orderdate"></label>
	</div>
	<div class="modal-body" id="printarea">
	<div class="table-responsive">
	<img id="cr_img" src="view/image/processing_image.gif" style="float: right; margin-right:40%; height: 60px;display : none;"/>
			<table class="table table-bordered" id="prd_table">
			<thead>
				<tr>
					<td class="text-left">S no</td>
				    <td class="text-left">Product Name</td>
				    <td class="text-left">Quantity</td>
				    <td class="text-left">Price</td>
					<td class="text-left">Tax</td>		
					<td class="text-left">Total</td>
				
					
               </tr>
           </thead>
			<tbody id="productdtl_body"> 
				 	 
			</tbody>
			</table>			
	</div>
	</div>
  </div>
	</div>
	</div>
	
  <script type="text/javascript">
  $("#input-units").select2();
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/cardhistory&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	var filter_unit_id = $('select[name=\'filter_unit_id\']').val();
	
	if (filter_unit_id != 0) {
		url += '&filter_unit_id=' + encodeURIComponent(filter_unit_id);
	}
	var filter_card_number = $('#input-filter_card_number').val();
	
	if (filter_card_number) {
		url += '&filter_card_number=' + encodeURIComponent(filter_card_number);
	}	
	var filter_grower_id = $('#input-filter_grower_id').val();
	
	if (filter_grower_id) {
		url += '&filter_grower_id=' + encodeURIComponent(filter_grower_id);
	}	
	location = url;
});
$('#button-download').on('click', function() {
	url = 'index.php?route=report/cardhistory/download_excel&token=<?php echo $token; ?>';
	
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	var filter_unit_id = $('select[name=\'filter_unit_id\']').val();
	
	if (filter_unit_id != 0) {
		url += '&filter_unit_id=' + encodeURIComponent(filter_unit_id);
	}
	var filter_card_number = $('#input-filter_card_number').val();
	
	if (filter_card_number) {
		url += '&filter_card_number=' + encodeURIComponent(filter_card_number);
	}	
	var filter_grower_id = $('#input-filter_grower_id').val();
	
	if (filter_grower_id) {
		url += '&filter_grower_id=' + encodeURIComponent(filter_grower_id);
	}	
	location = url;
}); 

///////////////////////////

///////////////////////////////
function productdtl(oid,datee,date2)
 {
	$('#cr_img').show(); 
	$('#prd_table').hide();
	var url= 'index.php?route=report/users_cash_leadger/OrderDetail&token=<?php echo $token; ?>&oid=' +  encodeURIComponent(oid);
	$('#productdtl_body').html('');
	$.ajax({
		url:url,
		dataType: 'json',			
		success: function(json) {
               
			$('#cr_img').hide(); 	
             var json_obj = json;
			 var r=1;
			
			$('#productdtl_body').html('');
			$("#orderid").html(oid);
			$("#orderdate").html(date2);
			
					var all_product_total=0;
					var addamount=0;
					var tot=0;
					for (i=0; i < json_obj['orders'].length; i++)
					{	
						
						addamount=(parseFloat(json_obj['orders'][i]['price']))+(parseFloat(json_obj['orders'][i]['tax']));
						tot=parseFloat((json_obj['orders'][i]['quantity'])*(addamount));
						tot=tot.toFixed(2);
						
						$('#productdtl_body').append('<tr><td>'+r+'</td><td>'+json_obj['orders'][i]['name']+'</td><td>'+json_obj['orders'][i]['quantity']+'</td><td>'+json_obj['orders'][i]['price']+'</td><td>'+json_obj['orders'][i]['tax']+'</td><td>'+tot+'</td></tr>');
					    
						all_product_total=parseFloat(all_product_total)+parseFloat(tot);
						
					r=r+1;
					}
						$('#productdtl_body').append('<tr><td colspan="5" style="text-align: right;">Total Amount</td><td>'+all_product_total+'</td></tr>');
						
						$('#productdtl_body').append('<tr><td colspan="6" style="text-align: right;"></td></tr>');
						$('#productdtl_body').append('<tr><td colspan="2" style="text-align: right;"><b>Order Total </b></td><td colspan="2" style="text-align: right;"><b>Cash Amount </b></td><td><b>Tagged Amount</b></td><td><b>Subsidy Amount</b></td></tr>');
						$('#productdtl_body').append('<tr><td colspan="2" style="text-align: right;">'+json['order_total']+'</td><td colspan="2" style="text-align: right;">'+json['order_cash']+'</td><td>'+json['order_tagged']+'</td><td>'+json['order_subsidy']+'</td></tr>');
						
				 $('#prd_table').show();	
		
	        },
            error:function (json)
			{
					$('#cr_img').hide(); 	
                   
					alertify.error("Opps some error occurred !");
            }
               
	});
	 
 }  
  
</script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script> 

</div>
<?php echo $footer; ?>