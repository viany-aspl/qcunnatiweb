<?php echo $header; ?><?php echo $column_left; ?>

	
<div id="content">
  <div class="page-header">
    <div class="container-fluid"> 
      <h1>Dscl Order Detail</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">Dscl Order Detail</a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i>Dscl Order Detail</h3>
        <!--<button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download Excel</button>
        -->
     
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">


            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start">Create Date From</label>
                <div class="input-group date">
<input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
<span class="input-group-btn">
<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
</span></div>
              </div>
              
             
            </div>
	<div class="col-sm-6">
	<div class="form-group">
                <label class="control-label" for="input-date-end">Create Date To</label>
                <div class="input-group date">
<input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
<span class="input-group-btn">
<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
</span></div>
              </div>
 <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i>Search</button>
            
              	

              
            </div>
          </div>
        </div>
        <div class="table-responsive" >
	<!--<span style="font-weight: bold;">Total Amount : <?php echo number_format((float)$total_tagged_amount_All, 2, '.', ''); ?></span> 
                           -->
                           <br/><br/>
          <table class="table table-bordered" id="myTable" >
            <thead>
              <tr>
                <td class="text-right">Sl.No.</td>
                <td class="text-right">Order Id</td>
                <td class="text-right">Store Name</td>               			 
				 <td  class="text-right">User Name</td>
				 <td  class="text-right">Payment Method</td>
                <td class="text-right">Total</td>	
				<td class="text-right">Village</td>	
				<td class="text-right">Bill No</td>	
				<td class="text-right">Trans Order Id</td>
				<td class="text-right">Unit Id</td>
			  <td class="text-right">ORDER STATUS ID</td>				
                
              </tr>            </thead>
            <tbody>
              <?php  if ($orders) { if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; } ?>
              <?php foreach ($orders[0] as $order) { //print_r($order); ?>
              <tr>
                <td class="text-right"><?php echo $aa; ?></td>
                <td class="text-right" data-toggle="modal" data-target="#myModal2" onclick="productdtl(<?php echo $order['ORDER_ID']; ?>);"><a style="cursor:pointer;"><?php echo $order['ORDER_ID']; ?></a></td>                
                <td class="text-right"><?php echo $order['STORE_NAME']; ?></td>
				
				<td class="text-right"><?php echo $order['FIRSTNAME'].' '.$order['LASTNAME']; ?></td>
                <td class="text-right"><?php echo $order['PAYMENT_METHOD']; ?></td>
	        <td class="text-right"><?php echo $order['TOTAL']; ?></td>
			<td class="text-right"><?php echo $order['VILLAGE_NAME']; ?></td>
			<td class="text-right"><?php echo $order['BILL_NO']; ?></td>
			<td class="text-right"><?php echo $order['TRANS_ORDER_ID']; ?></td>
			<td class="text-right"><?php echo $order['UNIT_CODE']; ?></td>
			<td class="text-right"><?php echo $order['ORDER_STATUS_ID']; ?></td>
            
                
              </tr>              <?php $aa++; } ?>
              <?php  } else { ?>
              <!--<tr>
                <td class="text-center" ><?php echo $text_no_results; ?></td>
					<td></td><td></td><td></td><td></td><td></td><td></td>
              </tr>-->
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right">
		<!--<span style="font-weight: bold;">Page Total :: Total Amount : <?php echo number_format((float)$total_tagged_amount, 2, '.', ''); ?></span>--> 
                           
                           <br/>
		<?php //echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>


<!-- Modal -->
  <div class="modal fade" id="myModal_create_bill" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" id="partner_cncl_btn2" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Create Bill</h4>
        </div>
        <div class="modal-body">
        <form action="index.php?route=reportbcml/reconciliation/create_bill&token=<?php echo $token; ?>" method="post" enctype="multipart/form-data" onsubmit="return myFunction()" >
            <div class="form-group">
                <label class="control-label" for="input-date">Date</label>
                <div class="input-group date" id="date_cr">
                  <input required type="text" name="filter_date"  placeholder="" data-date-format="YYYY-MM-DD" id="input-date" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            <div class="form-group">
            <label for="input-username">Store</label>
            <div class="input-group"><span class="input-group-addon"><i class="fa fa-building-o" aria-hidden="true"></i></span>
                
               <select name="filter_store_2" id="input-store" class="form-control" required onchange="get_unit(this.value)">
	    <option value="">SELECT STORE</option>
                  <?php foreach ($stores as $store) { ?>
                  <?php if (($store['store_id'] == $filter_store) & $filter_store="0") { ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>

            </div>
            </div>
            <div class="form-group">
            <label for="input-username">Unit</label>
            <div class="input-group"><span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                <select name="filter_unit_2" id="input-unit_2" class="form-control" required >
                         <option value="" >SELECT UNIT</option>

                                        <?php foreach($units as $unit) {  ?>
				<option value="<?php echo $unit['unit_id']; ?>" <?php if ($unit['unit_id'] == $filter_unit) { ?> selected="selected" <?php } ?>><?php echo $unit['unit_name']; ?></option> 
			<?php } ?>
                                   
                </select>
            </div>
            </div>
            
            
            <div class="text-right">
                <input type="submit" id="partner_sbmt_btn"  class="btn btn-primary" value="Submit" />
                <button type="button" id="partner_cncl_btn" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </form>
        </div>
        <!--<div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> 
        </div>-->
      </div>
      
    </div>
  </div>
  <div id="myModal2" class="modal fade" role="dialog">
<div class="modal-dialog" >
<div class="modal-content">

	<div class="modal-header" style="height:60px;">
	<button type="button" class="close pull-right" data-dismiss="modal">&times;</button>
	<label>Order Id  :&nbsp;&nbsp;&nbsp;</label><label id="orderid"></label><!---&nbsp;&nbsp;&nbsp;&nbsp;<label>Payment Method :</label><label id="paymentmethod"></label>--->
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
					<td class="text-left">Reward</td>
					<td class="text-left">Reward Percentage</td>
					<td class="text-left">Total</td>
					
               </tr>
           </thead>
			<tbody id="productdtl_body"> 
				 	 
			</tbody>
			</table>			
	</div>
	</div>




<link rel="stylesheet" href="view/datatable/jquery.dataTables.min.css" />
<link rel="stylesheet" href="view/datatable/buttons.dataTables.min.css" />
 
    <script src="view/datatable/jquery.dataTables.min.js"></script>
    <script src="view/datatable/dataTables.buttons.min.js"></script>
    <script src="view/datatable/buttons.flash.min.js"></script>
    <script src="view/datatable/jszip.min.js"></script>
    <script src="view/datatable/pdfmake.min.js"></script>
    <script src="view/datatable/vfs_fonts.js"></script>
    <script src="view/datatable/buttons.html5.min.js"></script>
    <script src="view/datatable/buttons.print.min.js"></script>


  <script type="text/javascript">


//$(document).ready(function() { 
//alert('kk');
    $('#myTable').DataTable( {
        dom: 'Bfrtip',
		lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
        ],
        buttons: [
            'excel', 'pdf','pageLength'
        ]
    } );
//} );

$('#button-filter').on('click', function() { //alert('kkk');
	url = 'index.php?route=reportdscl/getDataFromDscl&token=<?php echo $token; ?>';	
	
     var filter_date_start = $('input[name=\'filter_date_start\']').val();

if (filter_date_start) {
url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
}

var filter_date_end = $('input[name=\'filter_date_end\']').val();

if (filter_date_end) {
url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
}

	location = url;
});
function productdtl(oid)
 {
	  $('#cr_img').show(); 
	  $('#prd_table').hide();
	 //alert(oid);   
	 var url= 'index.php?route=reportdscl/getDataFromDscl/GetOrderProductData&token=<?php echo $token; ?>&oid=' +  encodeURIComponent(oid);
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
			
					var all_product_total=0;
					for (i=0; i < json_obj.length; i++)
					{	
				        //prd_total=parseFloat(json_obj[i]['price'])+parseFloat(json_obj[i]['tax']);
						
						$('#productdtl_body').append('<tr><td>'+r+'</td><td>'+json_obj[i]['NAME']+'</td><td>'+json_obj[i]['QUANTITY']+'</td><td>'+json_obj[i]['PRICE']+'</td><td>'+json_obj[i]['TAX']+'</td><td>'+json_obj[i]['REWARD']+'</td><td>'+json_obj[i]['REWARD_PER']+'</td><td>'+json_obj[i]['TOTAL']+'</td></tr>');
					    
						all_product_total=parseFloat(all_product_total)+parseFloat(json_obj[i]['TOTAL']);
						//alert(all_product_total);
					r=r+1;
					}
						$('#productdtl_body').append('<tr><td colspan="7" style="text-align: right;">Total Amount</td><td>'+all_product_total+'</td></tr>');
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
</script>
  <script type="text/javascript">

$('.date').datetimepicker({
	pickTime: false,
	maxDate: new Date()
});

</script></div>
<?php echo $footer; ?>
