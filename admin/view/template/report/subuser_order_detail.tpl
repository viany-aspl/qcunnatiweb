<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo "Sub User Order Detail"; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo "Sub User Order Detail"; ?>"><?php "Material Summary"; ?></a></li>
        <?php } ?>
      </ul>


    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo "Order List"; ?></h3>
		
			 <button type="button" id="button-pdf" class="btn btn-primary pull-right" style="margin-top: -8px !important; margin-right: 10px !important;">
            Download PDF</button>	
			<button type="button" id="button-excel" class="btn btn-primary pull-right" style="margin-top: -8px !important; margin-right: 10px !important;">
            Download Excel</button>
      </div>

      <div class="panel-body">
        <div class="well">
          <div class="row">
		   
             <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo "Date Start "; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
             </div>
              <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo "Date End"; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              </div>
			   
            <div class="col-sm-6">
               
              <div class="form-group">
                <label class="control-label" >Select User</label>
                
                      
                  <select name="filter_user" id="filter_user" style="width:100%" class="select2 form-control">
                   <option selected="selected" value="">SELECT USER</option>
					<?php foreach($getuser as $user){ ?>
						<option value="<?php echo $user['user_id']; ?>" <?php if($filter_user==$user['user_id']){ ?> selected="selected" <?php } ?> ><?php echo $user['firstname']."  ".$user['lastname']; ?></option>
					<?php } ?>
                                  
                </select>
                
              </div>
			  
             
             </div>
			 <div class="col-sm-6">
               
              <div class="form-group">
                <label class="control-label" >Select Unit</label>
                    
                  <select name="filter_unit" style="width:100%" id="input-unit" class="form-control">
                         <option value="" >SELECT UNIT</option>
								<?php foreach($units as $unit) {  ?>
									<option value="<?php echo $unit['unit_id']; ?>" <?php if ($unit['unit_id'] === $filter_unit) { ?> selected="selected" <?php } ?>><?php echo $unit['unit_name']; ?></option> 
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
                <td class="text-left">S NO.</td>
				  <td class="text-left">Order Id</td> 
				  <td class="text-left">Date</td>
				  <td class="text-left">Sub User</td>
				  <td class="text-left">Store Name</td>
				  <td class="text-left">Grower Name</td>
				                 				    
                <td class="text-left">Transaction type</td>
				  <td class="text-left">Order Total</td>
				   <td class="text-left">Tagged Amount</td>
				    <td class="text-left">Cash Amount</td>
              </tr>
            </thead>
            <tbody>
              <?php if ($orders) { $total=0; $a=1;?>
			  
              <?php foreach ($orders as $order) {  //print_r($order); ?>
              <tr>
                <td class="text-left"><?php echo $a; ?></td>
				 <td class="text-left" data-toggle="modal" data-target="#myModal2" onclick="productdtl('<?php echo $order['order_id']; ?>','<?php echo strtotime($order['dat']); ?>','<?php echo $order['dat']; ?>');" ><a style="cursor:pointer;"><?php echo $order['order_id']; ?></a></td>
                
<td class="text-left"><?php echo $order['dat']; ?></td>  
<td class="text-left"><?php echo $order['username']; ?></td>
				<td class="text-left"><?php echo $order['store_name']; ?></td>
				  <td class="text-left"><?php echo $order['growername']; ?></td>		             
            
               
				  <td class="text-left"><?php echo $order['payment_method']; ?></td>
                <td class="text-left"><?php echo $order['total']; ?></td>
				 <td class="text-left"><?php echo $order['tagged']; ?></td>
				  <td class="text-left"><?php echo $order['cash']; ?></td>
				<!----<td class="text-left">
                  <a href=""><i class="fa fa-download"></i>
                   </a> 
               </td>----->
              </tr>
              <?php $total=$total+$order['amount'];
              
              $a++;} ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
       <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"> 
             
              <?php echo $results; ?>  </div>
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
  <script type="text/javascript">
   $('#button-pdf').on('click', function() {
	var filter_user = $('select[name=\'filter_user\']').val();
	var filter_unit = $('select[name=\'filter_unit\']').val();
	if(!filter_user)
	{
	  alertify.error('Please Select User');
	  return false;
	}
	
	url = 'index.php?route=report/subuser/Alldownload_pdf&token=<?php echo $token; ?>';
	
	if (filter_user) {
		url += '&filter_user=' + encodeURIComponent(filter_user);
	}
	if (filter_unit) {
		url += '&filter_unit=' + encodeURIComponent(filter_unit);
	}
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
    window.open(url, '_blank');
});
  $('#button-excel').on('click', function() {
	var filter_user = $('select[name=\'filter_user\']').val();
	var filter_unit = $('select[name=\'filter_unit\']').val();
	
	url = 'index.php?route=report/subuser/Alldownload_excel&token=<?php echo $token; ?>';
	
	if (filter_user) {
		url += '&filter_user=' + encodeURIComponent(filter_user); 
	}
	if (filter_unit) {
		url += '&filter_unit=' + encodeURIComponent(filter_unit);
	}
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
        window.open(url, '_blank');
});
	$("#input-unit").select2();
	$("#filter_user").select2(); 
function productdtl(oid,datee,date2)
 {
	  //alert(date2);
	  $('#cr_img').show(); 
	  $('#prd_table').hide();
	 //alert(oid);   
	 var url= 'index.php?route=report/subuser/SubUserOrderProductDetail&token=<?php echo $token; ?>&oid=' +  encodeURIComponent(oid);
	// alert(url);
	 $('#productdtl_body').html('');
	 $.ajax({
		url:url,
		dataType: 'json',			
		success: function(json) {
                //alert(JSON.stringify(json));
			$('#cr_img').hide(); 	
             var json_obj = json;
			 var r=1;
			 //var prd_total=0;
			$('#productdtl_body').html('');
			$("#orderid").html(oid);
			$("#orderdate").html(date2);
			//alert(JSON.stringify(json_obj['orders'][0]['name']));
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
						//alert(all_product_total);
					r=r+1;
					}
						$('#productdtl_body').append('<tr><td colspan="5" style="text-align: right;">Total Amount</td><td>'+all_product_total+'</td></tr>');
				 $('#prd_table').show();	
		
	        },
            error:function (json)
			{
					$('#cr_img').hide(); 	
                    //alert(JSON.stringify( json));
					alertify.error("Opps some error occurred !");
            }
               
	});
	 
 }  
  
  
  
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/subuser/SubUserOrderDetail&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	var filter_user = $('#filter_user').val();
	
	if (filter_user) {
		url += '&filter_user=' + encodeURIComponent(filter_user);
	}	
    var filter_unit = $('select[name=\'filter_unit\']').val();  
	if (filter_unit) {
		url += '&filter_unit=' + encodeURIComponent(filter_unit);
	}
	location = url;
});
</script> 

  <script type="text/javascript">
  
  <!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>