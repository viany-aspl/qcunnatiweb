<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        </div>
      <h2>Order Success</h2>
      
    </div>
  </div>
  <div class="container-fluid">
      <?php if ($success) {  ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

<?php if ($error) {  ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

    <div class="panel panel-default">
       <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-search"></i> Search Order ID</h3>
      </div>
      <div class="panel-body" style="min-height: 345px;">
        
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title" style="margin-top: 10px;">Order ID</label>
                <div class="col-sm-6">
                    <input type="text" style="margin-top: 5px;" onkeypress="return isNumber(event)" name="order_id" value="" placeholder="Order ID" id="order_id" class="form-control"  required="required" />
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>
                </div>
                <input type="hidden" id="order_id_go" value="0" />
	  <input type="hidden" id="order_status_id" value="0" />
	<div class="col-sm-4">
		<img id="search_img" src="view/image/processing_image.gif" style="height: 48px;width: 48px;display: none;" />
                <input id="search_btn" type="button" style="margin-top: 5px;" name="order_search" value="Seacrh" onclick="return search_order();" class="btn btn-primary"   />
                  
                </div>
            </div>
           
       
      <br/> <br/>
        <div class="table-responsive">
          <table class="table table-bordered" id="tbl_header_main" style="display: none;">
            <thead>
              <tr>
                <td class="text-left">ORDER ID</td>
                <td class="text-left">INDENT NUMBER</td>
                <td class="text-left">ORDER DATE</td>
                <td class="text-left">STORE NAME</td>
					<td class="text-left">OP NAME</td>
                <td class="text-left">CUSTOMER</td>
                <td class="text-left">CUSTOMER MOBILE</td>
                <td class="text-left">PAYMENT METHOD</td>
                <td class="text-left">TOTAL</td>
                <td class="text-left">CASH</td>
                <td class="text-left">TAGGED</td>
                <td class="text-left">SUBSIDY</td>
	  <td class="text-left">ORDER STATUS</td>
              </tr>
            </thead>
            <tbody>
	<tr>                
                <td class="text-left" id="order_id_show"></td>             
                <td class="text-left"  id="indent_number_show"></td>
                <td class="text-left" id="order_date"></td>
                <td class="text-left" id="store_name"></td>
				<td class="text-left" id="op_name"></td>
                <td class="text-left" id="customer"></td>
                <td class="text-left" id="custmer_mobile"></td>
                <td class="text-left" id="payment_method"></td>
                <td class="text-left" id="total"></td>
                <td class="text-left" id="cash"></td>
                <td class="text-left" id="tagged"></td>
                <td class="text-left" id="subsidy"></td>
                <td class="text-left" id="status"></td>
              </tr>
			  
			  </tbody>
          </table>
            
            <table class="table table-bordered" id="tbl_products" style="display: none;">
            <thead>
              <tr>
           
                <td class="text-left">PRODUCT ID</td>
                <td class="text-left">PRODUCT NAME</td>
                <td class="text-left">QUANTITY</td>
                <td class="text-left">PRICE</td>
                <td class="text-left">TAX</td>
                <td class="text-left">SUB TOTAL</td>
                
              </tr>
            </thead>
            <tbody id="detail_body">
                
            </tbody>
          </table>
		  
            <img id="submit_img" src="view/image/processing_image.gif" style="height: 48px;width: 48px;display: none;float: right;" />
            <input id="submit_btn" type="button" style="margin-top: 5px;float: right;display: none;" name="indent_submit" value="Success this Order" onclick="return submit_order();" class="btn btn-primary"   />
                  
        </div>
         
      
    </div>
    </div>
  </div>
  <script type="text/javascript">
    function submit_order()
    {
	var order_status_id=$("#order_status_id").val();
                    if(order_status_id=='1')
                    { 
       alertify.confirm('Are you Sure ? You want to Complete (Success) this Order !',
            function(e)
            { 
                if(e)
                {
                    var order_id=$("#order_id_go").val();
                    if(order_id)
                    {
                    send_to_success(order_id)
                    //alertify.success(e); 
                    }
                    else
                    {
                        alertify.error('Oops ! Some error occur ! Please try again.'); 
                        return false;  
                    
                    }
                    
                }
                else
                {
                    alertify.error('Cancel by user'); 
                    return false;
                }
            }
        );
        $("#alertify-ok").html('Continue');    
	}
	else	
	{
		alertify.error('Please check Order Status');
		return false;
	}
    }
    function send_to_success(order_id)
    {
      try{
	  $.ajax({
		url: 'index.php?route=sale/ordersuccess/completeOrder&token=<?php echo $token; ?>&order_id=' + encodeURIComponent(order_id),
		dataType: 'json',
		beforeSend: function() 
                {  
                    $("#submit_img").show();
                    $("#submit_btn").hide();
	      $("#search_btn").hide();
                },
		complete: function() 
                {
                    $("#submit_img").hide();
                    $("#submit_btn").hide();
	     $("#search_btn").show();	
		},
		success: function(html) 
                {
                    //alert(JSON.stringify(html)); 
                    if(html>0)
                    {
                        alertify.success('Order Compeleted(Success) Successfully');
	          $("#tbl_header_main").hide();
                        $("#tbl_products").hide();
	          $("#submit_img").hide();
	          $("#submit_btn").hide();
                       return false;
                    }
	      else if(html=='0')
                    {
                        alertify.error('Please Check Order Status');
	          $("#submit_img").hide();
	          $("#submit_btn").show();
                       return false;
                    }
                    else
                    {
                       alertify.error('Some error occur,please try again');
	         $("#submit_img").hide();
	          $("#submit_btn").show();
                       return false;
                    }
                },
		error: function(xhr, ajaxOptions, thrownError) 
                {
                    alertify.error(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    $("#submit_img").hide();
                    $("#submit_btn").show();
		}
            });
        }
	catch(e)
	{
		alertify.error(e);
		$("#submit_img").hide();
                $("#submit_btn").show();
		return false;
	}
    }
  function search_order()
  {
    var order_id=$("#order_id").val();
    if(order_id!='')
    {
	try{
	  $.ajax({
		url: 'index.php?route=sale/ordersuccess/search_order&token=<?php echo $token; ?>&order_id=' + encodeURIComponent(order_id),
		dataType: 'json',
		beforeSend: function() {
			$("#search_btn").hide();
			$("#search_img").show();
			$("#submit_img").hide();
			$("#submit_btn").hide();
			$("#tbl_header_main").hide();
                        $("#tbl_products").hide();
                            $("#order_id_show").html('');  
                            $("#indent_number_show").html('');      
                            $("#order_date").html('');      
                            $("#store_name").html('');  
							$("#op_name").html(''); 							
                            $("#customer").html('');      
                            $("#custmer_mobile").html('');      
                            $("#payment_method").html('');      
                            $("#total").html('');      
                            $("#cash").html('');      
                            $("#tagged").html('');      
                            $("#subsidy").html(''); 
		$("#status").html(''); 
			$("#order_id_go").val('0');
		},
		complete: function() {
			$("#search_btn").show();
			$("#search_img").hide();
			
		},
		success: function(html) {
                    //alert(JSON.stringify(html['order_id']));
                    //return false;
					if(html=='1') 
                    {
						alertify.error('Please Check order Status at Factory Server');
                        $("#search_btn").show();
						$("#search_img").hide();
						return;
					}
                    if(html['order_id']) 
                    {
                        $("#order_id_go").val(html['order_id_go']);
                        $("#order_id_show").html(html['order_id']);  
                        $("#indent_number_show").html(html['comment']);      
                        $("#order_date").html(html['date_added']);      
                        $("#store_name").html(html['store_name']);
						$("#op_name").html(html['op_name']); 
                        $("#customer").html(html['firstname']);      
                        $("#custmer_mobile").html(html['telephone']);      
                        $("#payment_method").html(html['payment_method']);      
                        $("#total").html(html['total']);      
                        $("#cash").html(html['cash']);      
                        $("#tagged").html(html['tagged']);      
                        $("#subsidy").html(html['subsidy']);  

	          var status='';
    	          if(html['order_status_id']==1)
	          {
		status='Pending';
	          }
	          else if(html['order_status_id']==5)
	          {
		status='Completed';
	          }
	          else if(html['order_status_id']==9)
	          {
		status='Cancel Reversed';
	          }
                        $("#status").html(status);   
	          $("#order_status_id").val(html['order_status_id']);
                        $("#detail_body").html('');
			
			for(var icount=0;icount<(html['products']).length;icount++ )
			{
				
				var detail_body_var='';
				detail_body_var='<tr><td class="text-left">'+html['products'][icount]['product_id']+'</td><td class="text-left">'+html['products'][icount]['name']+'</td><td class="text-left">'+html['products'][icount]['quantity']+'</td><td class="text-left">'+html['products'][icount]['price']+'</td><td class="text-left">'+html['products'][icount]['tax']+'</td><td class="text-left">'+html['products'][icount]['total']+'</td></tr>';
				$("#detail_body").append(detail_body_var);
				
				
			}
                        $("#tbl_header_main").show();
                        
                        $("#search_btn").show();
			$("#search_img").hide();
                        
			$("#tbl_products").show();
		$("#submit_img").hide();
		if(html['order_status_id']==1)
	          	{
                        		$("#submit_btn").show();
		}
                    }
                    else
                    {
                        alertify.error('No Record Found');
                        $("#search_btn").show();
			$("#search_img").hide();
                    }
			
		},
		error: function(xhr, ajaxOptions, thrownError) {

			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
	  }
	catch(e)
	{
		alert(e);
		$("#search_btn").show();
		$("#search_img").hide();
		return false;
	}
    }
    else
    {
        alertify.error('Please enter Order ID');
    }
    return false;
  }
  
  function isNumber(evt)
       {
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
  </script></div>
<?php echo $footer; ?> 