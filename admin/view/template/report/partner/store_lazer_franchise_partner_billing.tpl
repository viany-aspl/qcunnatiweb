<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Partner Billing</h1>
      <ul class="breadcrumb">
        <!--<?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">Store Ledger Report</a></li>
        <?php } ?>-->
      </ul>
<i class="<?php echo $tool_tip_class; ?> " data-toggle="tooltip" style="<?php echo $tool_tip_style; ?>" title="<?php echo $tool_tip; ?>"></i>
 
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i>Partner Billing</h3>
	 <button type="button" id="button-download" class="btn btn-primary pull-right" <?php if($filter_stores_id=="") { ?>style="margin-top: -8px;display: none;" <?php } else { ?> style="margin-top: -8px;"  <?php } ?>> Download PDF</button>
	<button type="button" id="button-download_excel" class="btn btn-primary pull-right" <?php if($filter_stores_id=="") { ?>style="margin-top: -8px;margin-right: 5px;display: none;" <?php } else { ?> style="margin-top: -8px;margin-right: 5px;"  <?php } ?>> Download Excel</button>
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
                <label class="control-label" for="input-status">Partner Store</label>
                <select name="filter_stores_id"  id="input-store" style="width: 100%;" class="select2 form-control" onchange="return show_download(this.value);">
                  <option value="0">Select Partner Store</option>
                 
                  <?php foreach ($order_stores as $store) { ?>
                  <?php if ($store['store_id'] == $filter_stores_id) { ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
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
                
                <td class="text-left">Party Name</td>
	<td class="text-left">Store Name</td>
	<td class="text-left">Date of Invoice</td>
                <td class="text-left">Invoice Number</td>
                <td class="text-left"> Amount</td>
                <td class="text-left">Payment Status</td>
                <td class="text-left">Down load PDF Invoice</td>
                
               <td class="text-left">Send via E-Mail</td>
              </tr>
            </thead>
            <tbody>
              <?php if ($products) { ?>
              <?php foreach ($products as $product) { ?>
              <tr>
                
                <td class="text-left"><?php echo $product['party_name']; ?></td>
	<td class="text-left"><?php echo $product['store_name']; ?></td>
	<td class="text-left"><?php echo date('d-m-Y',strtotime($product['Date'])); ?></td>
	<td class="text-left" data-toggle="tooltip" data-original-title='Product : <?php echo $product['product_name']; ?><br/><br/>Quantity : <?php echo $product['p_qnty']; ?><br/><br/>Price : <?php echo $product['p_price']; ?>'><?php echo $product['order_id']; ?></td>
                
                <td class="text-left"><?php if($product['Withdrawals']!='0.00') { echo $product['Withdrawals']; }  ?></td>
                
                <td class="text-left" style="max-width: 200px;"><?php if($product['paid_status']=="0"){ echo "Un-Paid"; } else if($product['paid_status']=="1"){ echo "Paid"; } ?></td>
	<td class="text-left"><a href="<?php echo $product['download_link']; ?>"> Download </a></td>
	<td class="text-left">
		<image id="pr_img_<?php echo $product['order_id']; ?>" height="100" width="120" src="view/image/gifphy.gif" style="object-fit: cover;display: none;" />
		<a href="#" id="email_link_<?php echo $product['order_id']; ?>" onclick="return send_mail('<?php echo $product['mail_link']; ?>',<?php echo $product['order_id']; ?>);">Send E-Mail </a>
	</td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="4">Please Select Store</td>
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
  <script type="text/javascript">
$("#input-store").select2();
function send_mail(url,order_id)
{

$.ajax({
            url         : url,
            type        : "POST",
            contentType : false,
            cache       : false,
            processData :false,
            beforeSend  : function()
            {
              $("#pr_img_"+order_id).show();
	$("#email_link_"+order_id).hide();
              
            },
           success: function (data)
            {  //alert(data);
               if(data=="Error deleting")
	{
	
	alertify.success('Mail Sent Successfully');
	
	}
	 else if(data=="Deleted")
	{
	
	alertify.success('Mail Sent Successfully');
	
	}
	else
	{
                    alertify.error('Some error occur. Please try again');
	      
	 }
	      $("#pr_img_"+order_id).hide();
	      $("#email_link_"+order_id).show();
            },
            error: function(error) 
            { //alert(error);
              alertify.error('Some error occur. Please try again');
	$("#pr_img_"+order_id).hide();
	$("#email_link_"+order_id).show();
            }
        });
return false;
}

function show_download(store_id)
{
if(store_id=="")
{
	$('#button-download').hide();
	$('#button-download_excel').hide();
}
else if(store_id=="0")
{
	$('#button-download').hide();
	$('#button-download_excel').hide();
}
else
{
	$('#button-download').show();
	$('#button-download_excel').show();
}
return false;
}
<!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/partner/partner_billing&token=<?php echo $token; ?>';
	
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

	location = url;
});
//-->
$('#button-download').on('click', function() {
	url = 'index.php?route=report/partner/download_pdf_partner_billing&token=<?php echo $token; ?>';
	
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

	location = url;
});
$('#button-download_excel').on('click', function() {
	url = 'index.php?route=report/partner/download_excel_partner_billing&token=<?php echo $token; ?>';
	
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

	location = url;
});
</script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script> 
</div>
<?php echo $footer; ?>