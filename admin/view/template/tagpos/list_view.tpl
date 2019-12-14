<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">      
        <a href="<?php echo $redirect; ?>" data-toggle="tooltip" title="Return to File upload" class="btn btn-primary"><i class="fa fa-reply"></i></a>
		</div>
      <h1>Indent Upload</h1>
      
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
        <h3 class="panel-title"><i class="fa fa-list"></i>Indent List</h3>
		<!--<button type="button" id="button-pdf" class="btn btn-primary pull-right" style="margin-top: -8px !important; margin-right: 10px !important;">
            Download PDF</button>-->
		<button type="button" id="button-excel" class="btn btn-primary pull-right" style="margin-top: -8px !important; margin-right: 10px !important;">
            Download Excel</button>
<input type="button" value="click to toggle fullscreen" onclick="toggleFullScreen(document.body)">
      </div>
	  
	  <div class="panel-body">
        <form  method="post" enctype="multipart/form-data" id="form-restore">  
        
        <div class="well">
          <div class="row">
              
             <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-status">Factory Unit</label>
                <select  name="unit_id" id="unit_id" class="form-control">
                  <option value="">Select Unit</option>
                
                <?php foreach($units as $unit)
					{
						if($user_group_id=='1'){
						?>
                  
                  <option <?php if($unit['unit_id']==$unit_id){ ?> selected="selected" <?php  } ?> value="<?php echo $unit['unit_id']; ?>"><?php echo $unit['unit_name']; ?></option>
               
					<?php }
					else
					{
						if($user_unit[0]['unit_id']==$unit['unit_id'])
						{
					?>
						<option <?php if($unit['unit_id']==$unit_id){ ?> selected="selected" <?php  } ?> value="<?php echo $unit['unit_id']; ?>"><?php echo $unit['unit_name']; ?></option>
					
					<?php
						}
					}
					} ?>
                 
                
                 
                </select>
              </div>
				<div class="form-group">
                <label class="control-label" for="input-date-start">Indent Number</label>
                
                  <input required type="text" name="INDENT_NO"  value="<?php echo $INDENT_NO; ?>" placeholder="Indent Number" id="input-INDENT_NO" class="form-control" />
                  
              </div> 
            </div> 
			<div class="col-sm-4">
            <div class="form-group">
                <label class="control-label" for="input-date-start">Invoice Date</label>
                <div class="input-group date" id="date_from">
                  <input required type="text" name="filter_date"  value="<?php echo $filter_date; ?>" placeholder="Invoice Date" data-date-format="YYYY-MM-DD" id="input-date" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>  
           </div> 
              
            
           <div class="col-sm-4">
				<div class="form-group">
					<label class="control-label" for="input-status">Billing Status</label>
						<select  name="billing" id="billing" class="form-control">
							<option value="">All</option>
							<option <?php if($billing==1){ ?> selected="selected" <?php  } ?> value="1">Billed</option>
							<option <?php if($billing==2){ ?> selected="selected" <?php  } ?> value="2">Pending</option>
						</select>
				</div>
		   
		   <button type="button" id="button-filter" style="margin-top: 22px;" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Search </button>
               
           </div>
          </div>
        </div>
           </form>
       <!-- <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>-->
      </div>
	  
      <div class="panel-body">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-store">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td>SID</td>
				  <td>ORDER</td>
				  <td>INDENT NO</td>
				  <td>FM CODE</td>
				  <td>MOTIVATOR NAME</td>
				<!--<td>OFC</td>
				<td>OFFICER</td>
				
				
				<td>VCD</td>
				<td>GCD</td>
				<td>NAME</td>
				<td>FATHER</td>
				-->
				<td>Item</td>
				<td>Qty</td>
				<td>Rate</td>
				<!--<td>ADV.AMT</td>
				<td>HELD. AMT</td>
				<td>ACTI.AMT</td>
				<td>RET.AMT</td>
				<td>CP AMT</td>-->
				
                </tr>
              </thead>
              <tbody>
                <?php if ($hsn) { $a=1; ?>
                <?php foreach ($hsn as $un) { ?>
                <tr>
                  <td class="text-left"><?php echo $a; ?></td>
					<td class="text-left">
						<span id="inv_span_<?php echo $un['INDENT_NO']; ?>">
							<?php  if($un['billing_status']==1){ //  ?>
								Order is already place with Order ID : <?php echo $un['invoice_no']; ?>
							<?php  }?>
						</span>
					<?php if($un['billing_status']==0){ //  ?>
					<a id="order_now_btn_<?php echo $un['INDENT_NO']; ?>" onclick="return order_now(<?php echo $un['INDENT_NO']; ?>,<?php echo $un['MOC']; ?>,'<?php echo $un['MOTIVATOR']; ?>');" href="#" data-toggle="tooltip" title="<?php echo "ORDER NOW"; ?>" style="margin-left: 5px;" class="btn btn-info order_now_btn">
                           ORDER NOW
                  </a>
					<?php   } ?>
						<img id="submit_img_<?php echo $un['INDENT_NO']; ?>" src="view/image/processing_image.gif" style="height: 48px;width: 48px;display: none;float: right;" />
					</td>
					<td class="text-left">
					<img src='https://unnati.world/shop/admin/barcode.php?inv=<?php echo $un['INDENT_NO']; ?>' />
					
					
					</td>
					<td class="text-left"><?php echo $un['MOC']; ?></td>
					<td class="text-left"><?php echo $un['MOTIVATOR']; ?></td>
					<!--<td class="text-left"><?php echo $un['OFC']; ?></td>
                  <td class="text-left"><?php echo $un['OFFICER']; ?></td>
					
                  
					<td class="text-left"><?php echo $un['VCD']; ?></td>
                  <td class="text-left"><?php echo $un['GCD']; ?></td>
					<td class="text-left"><?php echo $un['NAME']; ?></td>
                  <td class="text-left"><?php echo $un['FATHER']; ?></td>
					-->
                  <td class="text-left"><?php echo $un['Item']; ?></td>
					<td class="text-left"><?php echo $un['Qty']; ?></td>
                  <td class="text-left"><?php echo $un['Rate']; ?></td>
					<!--<td class="text-left"><?php echo $un['ADV_AMT']; ?></td>
                  <td class="text-left"><?php echo $un['HELD_AMT']; ?></td>
					<td class="text-left"><?php echo $un['ACTI_AMT']; ?></td>
                  <td class="text-left"><?php echo $un['RET_AMT']; ?></td>
					<td class="text-left"><?php echo $un['CP_AMT']; ?></td>-->
                  
                
                </tr>
                <?php $a++;} ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
         
      </div>
	<div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"> 
             
              <?php echo $results; ?>  </div>
        </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 
<script type="text/javascript">

function order_now(indent_no,fmcode,fmname)
{
	//indent_no=2646453;
	//alert(indent_no);
	try{
	  $.ajax({
		url: 'index.php?route=tagpos/tagpos/order_now_by_excel&token=<?php echo $token; ?>&indent_number=' + encodeURIComponent(indent_no)+'&fmcode=' + encodeURIComponent(fmcode)+'&fmname=' + encodeURIComponent(fmname),
		dataType: 'json',
		beforeSend: function() 
		{
			//$(".order_now_btn").hide();
			$("#order_now_btn_"+indent_no).hide();
			$("#submit_img_"+indent_no).show(); 
			
		},
		complete: function() 
		{
			//$("#order_now_btn_"+indent_no).show();
			$("#submit_img_"+indent_no).hide();
			
		},
		success: function(html) 
		{
			//alert(JSON.stringify(html));
			//
			if(html['error']!='')
			{
				
				if(html['error']=='Indent already invoiced')
				{ alert(html['error']);
					$("#order_now_btn_"+indent_no).hide();
				}
				else
				{
					$("#order_now_btn_"+indent_no).show();
				}
				alertify.error(html['error']);
				return false;
			}
			else if(html['error']==undefined)
			{
				$("#order_now_btn_"+indent_no).show();
				alertify.error('Oops! Some error occur. Please try again');
				return false;
			}
			else
			{
				if(html['success']=='')
				{
					$("#order_now_btn_"+indent_no).show();
					//alert(html['error']);
					alertify.error(html['error']);
					return false;
				}
				else
				{
					//alert(html['success']);
					$("#order_now_btn_"+indent_no).hide();
					$("#inv_span_"+indent_no).html(html['success']);
					alertify.success(html['success']);
					return false;
				}
			}
			
		}
	  });
	}
	catch(e)
	{
		alert(e);
		alertify.error(e);
		$("#search_btn").show();
		$("#search_img").hide();
		return false;
	}
	return false;
}

$('.date').datetimepicker({
	pickTime: false
});
$('#button-filter').on('click', function() 
{
	url = 'index.php?route=tagpos/upload/view&token=<?php echo $token; ?>';
	
        var unit_id = $('select[name=\'unit_id\']').val();
	
	if (unit_id!="") {
		url += '&unit_id=' + encodeURIComponent(unit_id);
	}
	 var billing = $('select[name=\'billing\']').val();
	
	if (billing!="") {
		url += '&billing=' + encodeURIComponent(billing);
	}

	var filter_date = $('input[name=\'filter_date\']').val();
	
	if (filter_date) {
		url += '&filter_date=' + encodeURIComponent(filter_date);
	}
	var INDENT_NO = $('input[name=\'INDENT_NO\']').val();
	
	if (INDENT_NO) {
		url += '&INDENT_NO=' + encodeURIComponent(INDENT_NO);
	}
	 
	location = url;
});
$('#button-excel').on('click', function() 
{
	url = 'index.php?route=tagpos/upload/download_excel&token=<?php echo $token; ?>';
	
        var unit_id = $('select[name=\'unit_id\']').val();
	
	if (unit_id!="") {
		url += '&unit_id=' + encodeURIComponent(unit_id);
	}
	 var billing = $('select[name=\'billing\']').val();
	
	if (billing!="") {
		url += '&billing=' + encodeURIComponent(billing);
	}

	var filter_date = $('input[name=\'filter_date\']').val();
	
	if (filter_date) {
		url += '&filter_date=' + encodeURIComponent(filter_date);
	}
	var INDENT_NO = $('input[name=\'INDENT_NO\']').val();
	
	if (INDENT_NO) {
		url += '&INDENT_NO=' + encodeURIComponent(INDENT_NO);
	}
	window.open(url, '_blank'); 
	//location = url;
});
</script> 
<script type="text/javascript">

function toggleFullScreen(elem) {
    // ## The below if statement seems to work better ## if ((document.fullScreenElement && document.fullScreenElement !== null) || (document.msfullscreenElement && document.msfullscreenElement !== null) || (!document.mozFullScreen && !document.webkitIsFullScreen)) {
    if ((document.fullScreenElement !== undefined && document.fullScreenElement === null) || (document.msFullscreenElement !== undefined && document.msFullscreenElement === null) || (document.mozFullScreen !== undefined && !document.mozFullScreen) || (document.webkitIsFullScreen !== undefined && !document.webkitIsFullScreen)) {
        if (elem.requestFullScreen) {
            elem.requestFullScreen();
        } else if (elem.mozRequestFullScreen) {
            elem.mozRequestFullScreen();
        } else if (elem.webkitRequestFullScreen) {
            elem.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
        } else if (elem.msRequestFullscreen) {
            elem.msRequestFullscreen();
        }
    } else {
        if (document.cancelFullScreen) {
            document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
    }
}

$('#button-pdf').on('click', function() {

	url = 'index.php?route=tagpos/upload/download_pdf&token=<?php echo $token; ?>';
    var file_id = '<?php echo $file_id; ?>';
	
	if(!file_id)
	{
	  alertify.error('Please Select File');
	  return false;
	}
	url += '&file_id=' + encodeURIComponent(file_id);	
	//alert(url);
        window.open(url, '_blank');
	
});
</script> 