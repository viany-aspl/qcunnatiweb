<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
     
      <h1><?php echo "Update Payment"; ?></h1>
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
	<?php if ($success) { ?>  
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
       <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo "Pending Payment List"; ?></h3>
      </div>
      <div class="panel-body" style="padding: 0px;">
        <div class="well">
        <div class="row">
	<!--<div class="col-sm-6">
            <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo "From"; ?></label>
                <div class="input-group date">
                    <input onkeypress="return false" type="text" name="from" value="<?php if(isset($filter_date_start)){ echo $filter_date_start; }?>" placeholder="Start date" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                    </span>
                </div>
            </div>
	</div> 
	<div class="col-sm-6">
            <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo "To"; ?></label>
                <div class="input-group date">
                    <input onkeypress="return false;" type="text" name="to" value="<?php if(isset($filter_date_end)){ echo $filter_date_end; }?>" placeholder="End date" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                    </span>
                </div>
            </div>
        </div>-->
        <div class="col-sm-6">
            <div class="form-group">
            <label class="control-label" for="input-date-end"><?php echo "Supplier"; ?></label>
            <select  style="width: 100%;"  name="filter_supplier" id="input-supplier" required="required" class="select2 form-control">
                              <option value="" >Select Supplier</option>
                  <?php $info=''; foreach ($suppliers as $supplier) { ?>
                  <?php if ($supplier['id'] == $filter_supplier) { ?>
                  <option value="<?php echo $supplier['id']; ?>" selected="selected" bank="<?php echo $supplier['BANK_NAME']; ?>" acc="<?php echo $supplier['ACC_ID']; ?>" ifsc="<?php echo $supplier['IFSC_CODE']; ?>"><?php echo $supplier['name']; ?></option>
                  <?php $info= 'BANK NAME --'.$supplier['BANK_NAME'] .'<br/>'.'ACCOUNT NUMBER --'.$supplier['ACC_ID'].'<br/>'.'IFSC --'.$supplier['IFSC_CODE'];} else { ?>
                  <option value="<?php echo $supplier['id']; ?>" bank="<?php echo $supplier['BANK_NAME']; ?>" acc="<?php echo $supplier['ACC_ID']; ?>" ifsc="<?php echo $supplier['IFSC_CODE']; ?>"><?php echo $supplier['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
            </select>
            </div>
		<div id="dtl"> <?php echo $info;?> </div>
        </div>
		<div class="col-sm-6">
            <div class="form-group">
            <label class="control-label" for="input-date-end">Invoice Number</label>
            <input type="text"name="filter_in_no" class="form-control" value="<?php echo $filter_in_no;  ?>" id="filter_in_no" placeholder="Invoice Number" />
            </div>
        </div>		<br/><br/>
                    <button class="btn btn-primary pull-right" id="button-filter"  style="margin-right:10px;" type="button"><i class="fa fa-search"></i> Filter</button>
        </div>
        
		  
        </div>
		
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-order">
          <div class="table-responsive">
	
	<span style="font-size: 18px;font-weight: bold;float: right;">Supplier Total OutStanding : <?php echo number_format($total_outstanding,2,'.',''); ?> </span>
	<span style="font-size: 18px;font-weight: bold;">Supplier Wallet Balance : <?php echo number_format($supplier_wallet_balance,2,'.',''); ?> </span>
	<br/><br/>
	<input type="hidden" name="supplier_balance" id="supplier_balance" value="<?php echo $supplier_wallet_balance; ?>" />

	

            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <!--<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>-->
	      <td class="text-left">Supplier Name</td>
	      <td class="text-left">PO Date</td>
	      <td class="text-left">Invoice Date</td>
                    <td class="text-left">PO Number</td>
                    <td class="text-left">Invoice Number</td>
                    				  
                    
                    <td class="text-left">Product Name</td>
                    <!--<td class="text-left">Quantity</td>
	      <td class="text-left">Rate</td>-->
	      <td class="text-left">Total Amount</td>
                    <td class="text-left">Delivery Address</td>
		<td class="text-left">PR Number</td>
                    <td class="text-left">Status</td>
                    <td class="text-left" style="max-width: 100px;">Action</td>
                </tr>
              </thead>
              <tbody>
                <?php 
                if($order_list)
                {
                    foreach($order_list as $order)
                    {
		?>
                    <tr>
		<td class="text-left"><?php echo $order['supplier']; ?></td>
		<td class="text-left"><?php echo date('d-m-Y',strtotime($order['po_date'])); ?></td>
		<td class="text-left"><?php echo date('d-m-Y',strtotime($order['invoice_date'])); ?></td>	
		<td class="text-left"><?php echo $order['id_prefix'].$order['sid']; ?></td>
                        <td class="text-left"><?php echo $order['invoice_no']; ?></td>
                        
                        
                        <td class="text-left"><?php echo $order['product']; ?></td>
                        <!--<td class="text-left"><?php echo $order['Quantity']; ?></td>
	          <td class="text-left"><?php echo $order['rate']; ?></td>-->
	          <td class="text-left"><?php echo $order['amount']; ?></td>
                        <td class="text-left"><?php echo $order['delivery_address']; ?></td>
		<td class="text-left"><?php echo $order['received_prn']; ?></td>
                        <td class="text-left">
                            <?php  if($order['status']=='0') 
                                   {
                                     echo "PO Raised";
                                   }
                                   else if($order['status']=='1') 
                                   {
                                    echo "PO Invoiced";
                                   }
                                   else if($order['status']=='2') 
                                   {
                                    echo "Invoice Paid";
                                   }
                            
                            ?>
                        </td>
                        <td class="text-left">
                        <?php    
                            if($order['status']=='1')
                            {
                        ?>
                           
                             <button  type="button" style="width: 110px;;" onclick="return invoice_payment('<?php echo $order['sid']; ?>','<?php echo $order['amount']; ?>');" class="btn btn-primary pull-right" id="cr_btn1<?php echo $order['sid']; ?>" >Adjust Payment</button> 
		<button  type="button" style="width: 110px;margin-top: 5px;" onclick="open_model('<?php echo $order['sid']; ?>','<?php echo $order['amount']; ?>','<?php echo $order['delivery_address']; ?>','<?php echo $order['invoice_no']; ?>')"  class="btn btn-info pull-right" id="cr_btn2<?php echo $order['sid']; ?>" >Payment Done</button> 



		&nbsp; &nbsp;
                            <img id="cr_img<?php echo $order['sid']; ?>" src="view/image/processing_image.gif" style="float: right;height: 60px;display: none;"/>
                        <?php
                            }
                        ?>
                        </td>
							
                    </tr>
		<?php
                    }
		}
		else
		{
		?>
		<tr><td colspan="9" style="text-align: center;"><?php echo $noresult; ?></td></tr>
		<?php
		}
                ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
     
    </div>
  </div>
  </div>
  
  
<!-- Modal -->
  <div class="modal fade" id="myModal_payment_done" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" id="partner_cncl_btn2" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><b> Payment Details</b> </h4>
		  <h3 class="modal-title" style="font-size: 11px;display: inline;" > <b>Delivery Address : </b> <span id="del_add"></span></h3> 
		  <h3 class="modal-title" style="font-size: 11px;display: inline;float: right;" > <b>Invoice Number : </b> <span id="del_invoice"></span></h3> 
        </div>
        <div class="modal-body">
        <form onsubmit="return show_progress_bar()" action="index.php?route=purchaseorder/suppliercreditposting/payment_done&token=<?php echo $token; ?>" method="post" enctype="multipart/form-data" onsubmit="return myFunction()" >
            <input  type="hidden" name="order_id"  id="order_id" class="form-control" />
	<input  type="hidden" name="filter_date_start2"  id="filter_date_start2" value="<?php echo $filter_date_start; ?>" class="form-control" />
	<input  type="hidden" name="filter_date_end2"  id="filter_date_end2" value="<?php echo $filter_date_end; ?>" class="form-control" />
	<input  type="hidden" name="filter_supplier2"  id="filter_supplier2" value="<?php echo $filter_supplier; ?>" class="form-control" />
	<div class="form-group required">
                <label class="control-label" for="input-date">Bank</label>
                
		<select name="payment_bank"   required="required" class="form-control">
					<option  value="">Select Bank</option>
                                        <option value="ICICI">ICICI</option>
                                        <option value="HDFC">HDFC</option>
                                        <option value="CASH">CASH</option>
                                        
				
		   </select>
                  
              </div>
            
           <div class="form-group required">
                <label class="control-label" for="input-date">Payment Method</label>
                <select name="payment_method"  required="required" class="form-control">
					<option  value="">Select Payment Method</option>
                                        <option value="ECMS">ECMS</option>
                                      <option value="NEFT">NEFT</option>
                                        <option value="RTGS">RTGS</option>
			<option value="IMPS">IMPS</option>
			<option value="ENET">ENET</option>
<option value="CASH">CASH</option>				
		    </select>
                  
              </div>
            <div class="form-group required">
                <label class="control-label" for="input-date">Transaction Number</label>
               <input type="text" name="tr_number" required="required" value="" placeholder="Transaction Number" id="input-tr_number" class="form-control" />
              </div>
	<div class="form-group required">
                <label class="control-label" for="input-date">Paid Amount</label>
               <input type="text" name="paid_amount" required="required" value="" placeholder="Paid Amount" id="input-paid_amount" class="form-control" />
              </div>
	 <div class="form-group">
                <label class="control-label" for="input-date">Snapshot of the Payment</label>
               <input type="file" name="snapshot" id="input-snapshot" class="form-control" />
              </div>
	 <div class="form-group ">
                <label class="control-label" for="input-date">Remarks</label>
               <textarea name="remarks"  placeholder="Remarks" id="input-remarks" class="form-control" ></textarea>
              </div>
            <div class="text-right">
                <input type="submit" id="partner_sbmt_btn"   class="btn btn-primary" value="Submit" />
                <button type="button" id="partner_cncl_btn" class="btn btn-default" data-dismiss="modal">Cancel</button>
	 <img id="cr_img" src="http://www.danubis-dcm.org/Content/Images/processing.gif" style="float: right;height: 60px;display: none;margin-top: -26px;"/>
	<br/>
            </div>
        </form>
        </div>
        <!--<div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>-->
      </div>
      
    </div>
  </div>

  <script type="text/javascript">
$("#input-supplier").select2();

$("#input-supplier").on('change', function() {
  
try{

$("#dtl").html('BANK NAME --'+$('option:selected', this).attr('bank')+'<br/>'+'ACCOUNT NUMBER --'+$('option:selected', this).attr('acc')+'<br/>'+'IFSC --'+$('option:selected', this).attr('ifsc'));

}catch(e){alert(e);}

});

function show_progress_bar()
{
//alert('kkk');
$("#partner_sbmt_btn").hide();
$("#partner_cncl_btn").hide();
$("#cr_img").show();
return true;
}
function open_model(order_id,amount,delivery_address,invoice_no)
{

 
$('#myModal_payment_done').modal('show');
$('#order_id').val(order_id);
$('input[name=\'filter_date22\']').val('');
$('select[name=\'filter_store_2\']').val('');
$('select[name=\'filter_unit_2\']').val('');
$("#input-paid_amount").val(amount);
$("#del_add").html(delivery_address);
$("#del_invoice").html(invoice_no);
return false;
}

function invoice_payment(pono,amount)
{ 
	//alert(pono+'-----'+amount);
  var supplier_balance=$("#supplier_balance").val();
  var supplier_balance = parseFloat(supplier_balance);

  amount=parseFloat(amount);
  if(supplier_balance<amount)
  {
    alertify.error('Supplier Wallet Balance is Low');
    return false;
  }

   //return false;
   $("#cr_btn1"+pono).hide(); 
   $("#cr_btn2"+pono).hide(); 
   $("#cr_img"+pono).show();

     $.ajax({
            url: 'index.php?route=purchaseorder/purchase_order/adjustpayment&token=<?php echo $token; ?>&pono='+encodeURIComponent(pono)+'&amount='+encodeURIComponent(amount),
            
            success: function(json) {
                
               //alert(json);
               location.reload();
       
            }
        });
     //return true;     
 }
function adjust_confirmation(sid,amount)
{
    var current_balance=$("#current_balance").val();
    if(current_balance<amount)
    {
       alertify.error('Available Wallet Balance is low !'); 
       return false;
    }
    else
    {
    //alert(sid);
   var cnfrm=confirm('Are You Sure ? You want to adjust the selected Invoice ');
   if(cnfrm){
                        
                    return true;
                }else{
                    
                    return false;
                }
                /*                     
                alertify.confirm('Are You Sure ? You want to adjust the selected Invoice ',
                function(e){ 
                    if(e){
                        alert('here');
                    return true;
                }else{
                    //alertify.error(''); 
                    return false;
                }
            }
                    
                        );
                 $("#alertify-ok").html('Continue');    
            */
    //return false;
    }
}     
      
      
      
$('#button-filter').on('click', function() {
	url = 'index.php?route=purchaseorder/purchase_order/purchase_payment&token=<?php echo $token; ?>';
	
        	
	
       
        var filter_supplier = $('#input-supplier').val();
	if (filter_supplier) {
		url += '&filter_supplier=' + encodeURIComponent(filter_supplier);
	}
	   
        var filter_in_no = $('#filter_in_no').val();
	if (filter_in_no) {
		url += '&filter_in_no=' + encodeURIComponent(filter_in_no);
	}
	
	/*
	 var filter_date_start = $('#input-date-start').val();
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
        	var filter_date_end = $('#input-date-end').val();
        	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
       	*/
	location = url;
});
</script> 
  <script type="text/javascript">
	$('.date').datetimepicker({
		pickTime: false
	});
	
	function reset_form()
	{
		$('[name=from]').val('');
		$('[name=to]').val('');
		$('[name=filter_id]').val('');
		$('[name=status]').prop('selectedIndex', 0);
	}
  </script>
<?php echo $footer; ?> 