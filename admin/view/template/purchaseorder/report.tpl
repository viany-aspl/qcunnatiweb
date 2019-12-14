<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
<div class="page-header">
<div class="container-fluid">

<h1><?php echo $heading_title; ?></h1>
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
<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
<button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php } ?>
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
</div>
<div class="panel-body" style="min-height:410px">
<?php //echo $tab3; ?>

<ul id="geo" class="nav nav-tabs nav-justified">
            <li class="<?php echo $tab1; ?>"><a onclick="return active_tab(1); " href="#tab-open" data-toggle="tab">Open PO</a></li>
            <li class="<?php echo $tab2; ?>"><a onclick="return active_tab(2); " href="#tab-pending" data-toggle="tab">Pending Invoice</a></li>
            <li class="<?php echo $tab3; ?>"><a onclick="return active_tab(3); " href="#tab-paid" data-toggle="tab">Paid Invoice</a></li>
            <li class="<?php echo $tab4; ?>"><a onclick="return active_tab(4); " href="#tab-leadger" data-toggle="tab">Ledger</a></li>
            
        </ul>
    <div class="panel-body">

<div class="well">
<div class="row">
<div class="col-sm-6">
              <div class="form-group" id="div_for_start_date" <?php if($tab2=="active"){ ?> style="display: none;" <?php } ?>>
                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              	
              <div class="form-group">
                <label class="control-label" for="input-date-end">Supplier</label>
               
                      
                  <select name="filter_store" style="width: 100%" id="input-store" class="select2 form-control">
                      <option value="">SELECT SUPPLIER</option>
                  <?php foreach ($stores as $store) { ?>
                  <?php if ($store['id'] == $filter_store) { ?>
                  <option value="<?php echo $store['id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $store['id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                 
              </div>
            </div>
<div class="col-sm-6">
                <div class="form-group" id="div_for_end_date" <?php if($tab2=="active"){ ?> style="display: none;" <?php } ?>>
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="col-sm-6" style="float: right;">  
                   <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button> 
                  </div>
              
            </div>
</div>
</div>

</div>
    

          <div class="tab-content">  
              
     <!--*************************open po start***********************-->         
     <div class="tab-pane <?php echo $tab1; ?>" id="tab-open">
      <div class="panel-body">
     
        <div class="row">
            <div class="col-sm-6" style="float: right;">
                  <button type="button" id="button-download1" class="btn btn-primary pull-right" style="margin-left:24px">
                  <i class="fa fa-download"></i> Download
                 </button>
                  
            </div>
        </div>
      </div>     
        <div class="panel-body">
          
          <div class="table-responsive">
           <table class="table table-bordered">
             <thead>
                <tr>
                    <td class="text-left">Supplier Name</td>
		<td class="text-left">PO Date</td>
                    <td class="text-left">PO Number</td>
                    				  
                    
                     <td class="text-left">Product Name</td>
                      <td class="text-left">Quantity</td>
                    <td class="text-left">Delivery Address</td>
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
		<td class="text-left"><?php echo $order['create_date']; ?></td>	
	          <td class="text-left"><?php echo $order['id_prefix'].$order['sid']; ?></td>
                        
                        
                        <td class="text-left"><?php echo $order['product']; ?></td>
                        <td class="text-left"><?php echo $order['Quantity']; ?></td>
                        <td class="text-left"><?php echo $order['delivery_address']; ?></td>
                        <td class="text-left">
                            <?php  
		       if($order['status']=='0') 
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
                            <a href="<?php echo 'index.php?route=purchaseorder/report/download_purchase_order&token='.$token.'&invoice_id='.$order['sid']; ?>" data-toggle="tooltip" title="<?php echo "Download Purchase Order"; ?>" style="margin-left: 5px;" class="btn btn-info">
                            <i class="fa fa-download"></i>
                            </a>
                        </td>
							
                    </tr>
		<?php
                    }
		}
		else
		{
		?>
		<tr><td class="text-center" colspan="9"><?php echo $text_no_results; ?></td></tr>
		<?php
		}
                ?>
              </tbody>
          </table>
          </div>
        
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>  
        </div>
       
 </div>
 <!--*************************end open po***********************-->   
 
 
 
 
 
 
 
 
 <!--*************************pending invoice start***********************-->   
  <div class="tab-pane <?php echo $tab2; ?>" id="tab-pending">
                   
         
    <div class="panel-body">
        <div class="row">
            
              <div class="col-sm-6" style="float: right;">
                  <button type="button" id="button-download2" class="btn btn-primary pull-right" style="margin-left:24px">
                  <i class="fa fa-download"></i> Download
                 </button>
                  
              </div>
        </div>
    </div>  
      
     <div class="panel-body">
        
          <div class="table-responsive">
	<b>Total Pending Invoice </b>: <?php echo number_format((float)$total_pending_invoice_amount,2,'.',''); ?>
	<br/><br/>
            <table class="table table-bordered">
                <thead>
                    <tr>
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
                    <td class="text-left">Status</td>
                    </tr>            
                </thead>
            <tbody>
                <?php 
                if($order_list2)
                {
                    foreach($order_list2 as $order)
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
                        
							
                    </tr>
		<?php
                    }
		}
		else
		{
		?>
		<tr><td class="text-center" colspan="9"><?php echo $text_no_results; ?></td></tr>
		<?php
		}
                ?>
              </tbody>
          </table>
          </div>
        
         
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination2; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results2; ?></div>
        </div>  
        </div>
 </div>
 <!--*************************End pending invoice***********************-->     
 
 
 
 
<!--*****************************************paid invoice start*****************************************-->  
              
 <div class="tab-pane <?php echo $tab3; ?>" id="tab-paid">
                
    <div class="panel-body">
        
        <div class="row">
            <div class="col-sm-6" style="float: right;">
                <button type="button" id="button-download3" class="btn btn-primary pull-right" style="margin-left:24px">
                     <i class="fa fa-download"></i> Download
                </button>
            
            </div>
        </div>
    </div>         
        <div class="panel-body">
         
          <div class="table-responsive">
	<b>Total Payment Amount </b>: <?php echo number_format((float)$total_payment_amount,2,'.',''); ?>
	<br/><br/>
         <table class="table table-bordered" style="font-size: 88% !important;">
                <thead>
                    <tr>
	             <td class="text-left">Supplier Name</td>
	      <td class="text-left">PO Date</td>
	      <td class="text-left">Invoice Date</td>
                    <td class="text-left">PO Number</td>
                    <td class="text-left">Invoice Number</td>
                    <td class="text-left">Paid Date</td>			  
                    <td class="text-left">Paid Bank</td>
	      <td class="text-left">Bank Tr. No.</td>
                    <td class="text-left">Product Name</td>
                    <!--<td class="text-left">Quantity</td>
	      <td class="text-left">Rate</td>-->
	      <td class="text-left">Total Amount</td>
                    <td class="text-left">Delivery Address</td>
                    <td class="text-left">Status</td>
                    </tr>            
                </thead>
            <tbody>
                <?php 
                if($order_list3)
                {
                    foreach($order_list3 as $order)
                    {
		?>
                    <tr>
		<td class="text-left"><?php echo $order['supplier']; ?></td>
		<td class="text-left"><?php echo date('d-m-Y',strtotime($order['po_date'])); ?></td>
		<td class="text-left"><?php echo date('d-m-Y',strtotime($order['invoice_date'])); ?></td>	
		<td class="text-left"><?php echo $order['id_prefix'].$order['sid']; ?></td>
                        <td class="text-left"><?php echo $order['invoice_no']; ?></td>
                        <td class="text-left"><?php echo $order['payment_date']; ?></td>
                        <td class="text-left"><?php echo $order['payment_bank']; ?></td>
	          <td class="text-left"><?php echo $order['bank_tr_no']; ?></td>
                        <td class="text-left"><?php echo $order['product']; ?></td>
                        <!--<td class="text-left"><?php echo $order['Quantity']; ?></td>
	          <td class="text-left"><?php echo $order['rate']; ?></td>-->
	          <td class="text-left"><?php echo $order['amount']; ?></td>
                        <td class="text-left"><?php echo $order['delivery_address']; ?></td>
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
                        
							
                    </tr>
		<?php
                    }
		}
		else
		{
		?>
		<tr><td class="text-center" colspan="9"><?php echo $text_no_results; ?></td></tr>
		<?php
		}
                ?>
              </tbody>
          </table>
        </div>
       
        
        <div class="row">
          <div class="col-sm-4 text-left"><?php echo $pagination3; ?></div>
          <div class="col-sm-8 text-right">
	   
         <?php echo $results3; ?></div>
        </div>
            
            
        </div>       
</div>
 
<!--*****************************************End paid invoice*****************************************--> 
       

              
 <!--*************************leadger start ***********************-->               
<div class="tab-pane <?php echo $tab4; ?>" id="tab-leadger">
                  
    <div class="panel-body">
        <div class="row">
            
              <div class="col-sm-6" style="float: right;">
                  <button type="button" id="button-download4" class="btn btn-primary pull-right" style="margin-left:24px">
                  <i class="fa fa-download"></i> Download Excel
                 </button>
	<button type="button" id="button-download4pdf" class="btn btn-primary pull-right" style="margin-left:24px">
                  <i class="fa fa-download"></i> Download PDF
                 </button>
              </div>
        </div>
    </div> 
                  
           
    <div class="panel-body">
      
          <div class="table-responsive">

	<span style="font-weight: bold;"> Total Invoice : </span> <?php echo number_format((float)($total_debit), 2, '.', ''); ?> &nbsp; | &nbsp; 
	<span style="font-weight: bold;">Total  Payment: </span> <?php echo number_format((float)($total_credit), 2, '.', ''); ?> &nbsp; |  &nbsp;
	<span style="font-weight: bold;"> Liability : </span> <?php echo number_format((float)($total_debit-$total_credit+$opening_balance), 2, '.', '');

?>
              <br/><br/>

          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-right">Date</td>
                
                <td class="text-right">Transaction Type</td>
                <td class="text-right">Tr Number/Invoice Number</td>
                <td class="text-right">Debit</td>
                <td class="text-right">Credit</td>
	<td class="text-right">Invoice Status</td>
              </tr>
            </thead>
            <tbody>
				
              <?php if ($order_list4) { $total3=0; ?>
				<tr>
                <td class="text-right"><?php echo date('d-m-Y',strtotime($filter_date_start)); ?></td>
                
                <td class="text-right"><?php echo 'Opening Balance'; ?></td>
                <td class="text-right"><?php  echo 'NA';  ?></td>
                <td class="text-right"><?php if($opening_balance>0) { echo number_format((float)$opening_balance, 2, '.', ''); } else { echo '0.00'; } ?></td>
                <td class="text-right"><?php if($opening_balance<0) { echo number_format((float)$opening_balance, 2, '.', ''); }  else { echo '0.00'; } ?></td>
				  <td class="text-right"><?php  ?></td>     
              </tr>
			  
              <?php foreach ($order_list4 as $order) { ?>
              <tr>
                <td class="text-right"><?php echo date('d-m-Y',strtotime($order['tr_date'])); ?></td>
                
                <td class="text-right"><?php echo $order['tr_type']; ?></td>
                <td class="text-right"><?php if($order['tr_number']!='') { echo $order['tr_number']; } else { echo 'NA'; } ?></td>
                <td class="text-right"><?php echo $order['total_debit']; ?></td>
                <td class="text-right"><?php echo $order['total_credit']; ?></td>
	   <td class="text-right"><?php if($order['invoice_status']=='1') { echo "Un-Paid"; } else if($order['invoice_status']=='2') { echo "Paid"; } ?></td>     
              </tr>
              <?php $total3=$total3+$order['amount3'];
              
              } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
       
         <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination4; ?></div>
          <div class="col-sm-6 text-right"> 
              
              <?php echo $results4; ?>  </div>
        </div> 
    </div>        
</div>
<!--******************************* END leadger *********************************--> 
 

          <span id="tab_active_" style="display: none;">
<?php echo @$_REQUEST["tab"]; ?>
</span>    
          </div> 

</div>
</div>
</div>
</div>

<script type="text/javascript">
$("#input-store").select2();
function active_tab(tab)
{
//alert(tab);
$('#tab_active_').html(tab);
//document.getElementById("tab_active_").value="text";
//document.getElementById("tab_active_").value=tab;
if(tab=='2')
{
	$("#div_for_start_date").hide();
	$("#div_for_end_date").hide();
}
else
{
	$("#div_for_start_date").show();
	$("#div_for_end_date").show();
}
return false;
}
</script>

<input type="hidden" name="tab_active_22" id="tab_active_22" value="<?php echo @$_REQUEST["tab"]; ?>" />
<script type="text/javascript">
$('#button-filter').on('click', function() {
	 url = 'index.php?route=purchaseorder/report&token=<?php echo $token; ?>';
	 //url += '&page='+'1';
         //url += '&tab='+'1';
         
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
                var filter_name_id = $('input[name=\'filter_name_id\']').val();
	
	if (filter_name_id) {
		url += '&filter_name_id=' + encodeURIComponent(filter_name_id);
                
	}
	}
         var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != 0) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	
var tabbb=$('#tab_active_').html();
              if(tabbb=="")
              {
                url += '&tab=1';
              }
              else
              {
                  url += '&tab=' + tabbb;
              }
             //alert(tabbb);//return false;
	location = url;
});

</script> 

<script type="text/javascript">
    $('#button-download1').on('click', function() {
	url = 'index.php?route=purchaseorder/report/open_po_download&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
    var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != 0) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	

        window.open(url, '_blank');
	
});
    /*********************************   Stock Transfer ****************************************/ 

$('#button-download2').on('click', function() {
	url = 'index.php?route=purchaseorder/report/pending_invoice_download&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
                var filter_name_id = $('input[name=\'filter_name_id\']').val();
	
	if (filter_name_id) {
		url += '&filter_name_id=' + encodeURIComponent(filter_name_id);
                
	}
	}
         var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != 0) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	
	
        window.open(url, '_blank');
	
});
/*********************************  End Stock Transfer ****************************************/ 
$('#button-download3').on('click', function() {
    url = 'index.php?route=purchaseorder/report/paid_invoice_download&token=<?php echo $token; ?>';
    	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
    
    //location = url;
        window.open(url, '_blank');
});
   $('#button-download4').on('click', function() {
    url = 'index.php?route=purchaseorder/report/leadger_download_excel&token=<?php echo $token; ?>';
    
   var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
         	var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != 0) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	

	location = url;
}); 
$('#button-download4pdf').on('click', function() {
    url = 'index.php?route=purchaseorder/report/download_leadger_pdf&token=<?php echo $token; ?>';
    
   var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
         	var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != 0) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	

	location = url;
}); 
    </script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>
