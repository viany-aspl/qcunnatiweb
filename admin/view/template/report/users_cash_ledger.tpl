<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>User's Cash Ledger Report</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">User's Cash Ledger Report</a></li>
        <?php } ?>
      </ul>
<i class="<?php echo $tool_tip_class; ?> " data-toggle="tooltip" style="<?php echo $tool_tip_style; ?>" title="<?php echo $tool_tip; ?>"></i>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i>User's Cash Ledger Report</h3>
		<button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -10px;"><i class="fa fa-download"></i>Download</button>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-status">Store</label>
                <select onchange="return get_store_users(this.value);" name="filter_stores_id"  id="input-store" style="width: 100%;" class="select2 form-control">
                  <option value="0">Select Store</option>
                 
                  <?php foreach ($order_stores as $store) { ?>
                  <?php if ($store['store_id'] == $filter_stores_id) { ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                   
                </select>
              </div>
              
            </div>
              <div class="col-sm-6">
               <div class="form-group">
                <label class="control-label" for="input-status">User</label>
                <select name="filter_user_id"  id="input-users" style="width: 100%;" class="select2 form-control">
                  <option value="0">Select User</option>
                 
                  <?php foreach ($users as $user) { ?>
                  <?php if ($user['user_id'] == $filter_user_id) { ?>
                  <option value="<?php echo $user['user_id']; ?>" selected="selected"><?php echo $user['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $user['user_id']; ?>"><?php echo $user['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                   
                </select>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">Store Name</td>
                <td class="text-left">User Name</td>
                <td class="text-right">Amount</td>
                <td class="text-right">Order Id</td>
                <td class="text-right">CR_DB</td>
                <td class="text-right">Trans Type</td>
				  <td class="text-right">Current Cash</td>
                <td class="text-right">Trans Date</td>
				 <td class="text-right">Remarks</td>
              </tr>
            </thead>
            <tbody>
              <?php if ($products) { ?>
              <?php foreach ($products as $product) { ?>
              <tr>
                <td class="text-left"><?php echo $product['storename']; ?></td>
                <td class="text-left"><?php echo $product['user']; ?></td>
                <td class="text-right"><?php echo $product['amount']; ?></td>
                <td class="text-right" >
				<?php if(($product['payment_method']=='Cash') || ($product['payment_method']=='Tagged Cash') || ($product['payment_method']=='Subsidy Cash') || ($product['payment_method']=='Subsidy') || ($product['payment_method']=='Tagged')) 
				{ ?>
					<a href="#" data-toggle="modal" data-target="#myModal2" 
				onclick="return productdtl('<?php echo $product['order_id']; ?>','<?php echo strtotime($product['create_time']); ?>','<?php echo $product['create_time']; ?>');">
				
						<?php echo $product['order_id']; ?>
					</a>
				<?php }  else if($product['payment_method']=='CD') 
				{ ?>
					<a href="#" data-toggle="modal" data-target="#myModal" 
				onclick="return cash_deposit_by_store_incharge('<?php echo $product['order_id']; ?>','<?php echo strtotime($product['create_time']); ?>','<?php echo $product['create_time']; ?>');">
				
						<?php echo $product['order_id']; ?>
					</a>
				<?php 
				}  
				else if($product['payment_method']=='SCR') 
				{ ?>
					<a href="#" data-toggle="modal" data-target="#myModal3" 
				onclick="return cash_deposit_by_sub_user('<?php echo $product['order_id']; ?>','<?php echo strtotime($product['create_time']); ?>','<?php echo $product['create_time']; ?>');">
				
						<?php echo $product['order_id']; ?>
					</a>
				<?php }
				else  { echo $product['order_id']; } ?>
				</td>
                 <td class="text-right"><?php echo $product['tr_type']; ?></td>
                  <td class="text-right"><?php echo $product['payment_method']; ?></td>
					<td class="text-right"><?php echo $product['updated_cash']; ?></td>
                    <td class="text-right"><?php echo $product['create_time']; ?></td>
					<td class="text-right" style="max-width: 200px;"><?php echo $product['remarks']; ?></td>
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
	  <div id="myModal" class="modal fade" role="dialog">
<div class="modal-dialog" >
<div class="modal-content">

	<div class="modal-header" style="height:60px;">
	<button type="button" class="close pull-right" data-dismiss="modal">&times;</button>
	<h4>Cash Deposit details: </h4>
	<label>Trans Id  :&nbsp;&nbsp;&nbsp;</label><label id="deposittransid"></label>&nbsp;&nbsp;&nbsp;&nbsp;
	<label>TRANS DATE  :</label><label id="depositdate"></label>
	</div>
	<div class="modal-body">
	<div class="table-responsive">
	<img id="cr_img1" src="view/image/processing_image.gif" style="float: right; margin-right:40%; height: 60px;display : none;"/>
			<table class="table table-bordered" id="deposittranstable">
			
			</table>			
	</div>
	</div>
  </div>
	</div>
	</div>
  
  <div id="myModal3" class="modal fade" role="dialog">
<div class="modal-dialog" >
<div class="modal-content">

	<div class="modal-header" style="height:60px;">
	<button type="button" class="close pull-right" data-dismiss="modal">&times;</button>
	<h4>Cash Received from Sub USer details: </h4>
	<label>Trans Id  :&nbsp;&nbsp;&nbsp;</label><label id="deposittransidsubuser"></label>&nbsp;&nbsp;&nbsp;&nbsp;
	<label>TRANS DATE  :</label><label id="depositdatesubuser"></label>
	</div>
	<div class="modal-body">
	<div class="table-responsive">
	<img id="cr_img3" src="view/image/processing_image.gif" style="float: right; margin-right:40%; height: 60px;display : none;"/>
			<table class="table table-bordered" id="deposittranstablesubuser">
			
			</table>			
	</div>
	</div>
  </div>
	</div>
	</div>
  <script type="text/javascript">
$("#input-store").select2();
$("#input-users").select2();

///////////////////////////
function cash_deposit_by_store_incharge(oid,datee,date2)
 {
	 
	$('#cr_img1').show(); 
	var url= 'index.php?route=report/users_cash_leadger/get_cash_deposit_by_store_incharge&token=<?php echo $token; ?>&oid=' +  encodeURIComponent(oid);
	$.ajax({
		url:url,
				
		success: function(json) 
		{
            //alert(JSON.stringify(json));
			$('#cr_img1').hide(); 	
            $('#deposittransid').html(oid);
			$('#depositdate').html(date2);
			$("#deposittranstable").html(json);
		
	    },
        error:function (json)
		{
			$('#cr_img1').hide(); 	
			alertify.error("Opps some error occurred !");
        }
               
	});
	 
 }  

//////////////////////////////

function cash_deposit_by_sub_user(oid,datee,date2)
 {
	 
	$('#cr_img3').show(); 
	var url= 'index.php?route=report/users_cash_leadger/cash_deposit_by_sub_user&token=<?php echo $token; ?>&oid=' +  encodeURIComponent(oid);
	$.ajax({
		url:url,
				
		success: function(json) 
		{
            //alert(JSON.stringify(json));
			$('#cr_img3').hide(); 	
            $('#deposittransidsubuser').html(oid);
			$('#depositdatesubuser').html(date2);
			$("#deposittranstablesubuser").html(json);
		
	    },
        error:function (json)
		{
			$('#cr_img3').hide(); 	
			alertify.error("Opps some error occurred !");
        }
               
	});
	 
 }
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
  
<!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/users_cash_leadger&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	var filter_stores_id = $('select[name=\'filter_stores_id\']').val();
	
	if (filter_stores_id != 0) {
		url += '&filter_stores_id=' + encodeURIComponent(filter_stores_id);
	}	
	var filter_user_id = $('select[name=\'filter_user_id\']').val();
	
	if (filter_user_id != 0) {
		url += '&filter_user_id=' + encodeURIComponent(filter_user_id);
	}
	location = url;
});
$('#button-download').on('click', function() {
	url = 'index.php?route=report/users_cash_leadger/download_excel&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	var filter_stores_id = $('select[name=\'filter_stores_id\']').val();
	
	if (filter_stores_id != 0) {
		url += '&filter_stores_id=' + encodeURIComponent(filter_stores_id);
	}	
	var filter_user_id = $('select[name=\'filter_user_id\']').val();
	
	if (filter_user_id != 0) {
		url += '&filter_user_id=' + encodeURIComponent(filter_user_id);
	}
	location = url;
}); 

function get_store_users(store_id)
{
	if(store_id)
	{
		$.ajax({
			url: 'index.php?route=report/users_cash_leadger/get_store_users&token=<?php echo $token; ?>&store_id='+encodeURIComponent(store_id),
			beforeSend: function() 
                { 
				
                },
				complete: function() 
                {
                   	
				},
				success: function(html) 
                {	
					//alert(html);
					$("#input-users").html(html);
				},
				error: function(xhr, ajaxOptions, thrownError) 
                {
                    alertify.error(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    
				}
			
		});
	}
}

//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script> 

</div>
<?php echo $footer; ?>