<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid"> 
      <h1>Cards to be printed</h1>
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
        <h3 class="panel-title"><i class="fa fa-list"></i>Cards to be printed</h3>
         <button type="button" id="button-download-excel" class="btn btn-primary pull-right"  style="margin-top: -8px !important; margin-right: 10px !important;">
            Download Excel</button>
      </div>
      <div class="panel-body">

<div class="well">
   <div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label class="control-label" for="input-date-start">Start Date</label>
				<div class="input-group date">
					<input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="Start Date" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
					<span class="input-group-btn">
						<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
					</span>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				<label class="control-label" for="input-date-end">End Date</label>
				<div class="input-group date">
					<input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="End Date" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
					<span class="input-group-btn">
						<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
					</span>
				</div>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group">
				<label class="control-label" for="input-date-end">Unit</label>
				<select style="width: 100%;" name="filter_unit" id="input-unit" class="form-control">
					<option selected="selected" value="">SELECT UNIT</option>
					<?php 
						foreach($units as $dunit)
						{
							if ($dunit['unit_id'] == $filter_unit) 
							{
								
					?>
					<option value="<?php echo $dunit['unit_id']; ?>" selected="selected"><?php echo $dunit['unit_name']; ?></option>
					<?php 		
							} 
							else 
							{ 
					?>
					<option value="<?php echo $dunit['unit_id']; ?>"><?php echo $dunit['unit_name']; ?></option>
					<?php 
							} 
						}					
					?>
				</select>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				<label class="control-label" for="input-date-end">Sub User</label>
				<select name="filter_subuser" style="width: 100%;" id="input-filter_subuser" class="form-control" >
					<option selected="selected" value="">SELECT SUB USER</option>
					<?php 
						foreach($subsusers as $subsuser)
						{
							if ($subsuser['user_id'] == $filter_subuser) 
							{
								
					?>
					<option value="<?php echo $subsuser['user_id']; ?>" selected="selected"><?php echo $subsuser['firstname'].' '.$subsuser['lastname']; ?></option>
					<?php 		
							} 
							else 
							{ 
					?>
					<option value="<?php echo $subsuser['user_id']; ?>"><?php echo $subsuser['firstname'].' '.$subsuser['lastname']; ?></option>
					<?php 
							} 
						}					
					?>
				</select>
			</div>
		</div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end">Grower ID</label>
					<input autocomplete="off" type="text" style="text-transform: uppercase; ;" value="<?php echo $filter_growerid; ?>" placeholder="GROWER ID" name="filter_growerid" id="filter_growerid" class="form-control " />
               </div>
           </div>
           <div class="col-sm-6 ">
		   <div class="form-group">
				<label class="control-label" for="input-date-end">Status</label>
				<select style="width: 100%;" name="filter_status" id="filter_status" class="form-control" >
					<option value="">SELECT STATUS</option>
					<option <?php if($filter_status==1){ ?> selected="selected" <?php } ?> value="1">Printed</option>
					<option <?php if($filter_status==2){ ?> selected="selected" <?php } ?> value="2">Not Printed</option>
				</select>
			</div>
				<button type="button" style="margin-top:23px;" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Filter</button>
			</div>
          </div>
        </div>

        <div class="col-md-12" >                                   
            <div class="widget-body">
            <div class="table-responsive">
            <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">SN</td>
                <td class="text-left">SUB USER</td>
					<td class="text-left">UNIT</td>
                <td class="text-left">GROWER ID</td>
                <td class="text-left">DATE</td>
                <td class="text-left">STATUS</td>
                <td class="text-left">PRINT</td>
					<td class="text-left">CARD STATUS</td>
               
                
              </tr>
            </thead>
            <tbody id="checkboxes">
			<?php if ($orders) {  if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; } ?>
              <?php foreach ($orders as $order) { //print_r($order); ?>
              <tr>
					<td class="text-left"><?php echo $aa; ?></td>
					<td class="text-left"><?php echo $order['SUBUSER_NAME']; ?></td>
				<td class="text-left"><?php echo $order['UNIT_NAME']; ?></td>
                <td class="text-left"><?php echo $order['GROWER_ID']; ?></td>
                <td class="text-left"><?php echo $order['CREATE_DATE']; ?></td>
                <td class="text-left"><span id="span_status"><?php echo $order['STATUS_NAME']; ?></span></td>
                <td class="text-left">
                    <button  id="viewbtn"  type="button" data-toggle="modal" data-target="#myModal"  class="btn btn-primary " 
							
                             onclick="cardview('<?php echo $order['CARD_SERIAL_NUMBER']; ?>','<?php echo $order['GROWER_NAME']; ?>','<?php echo $order['FTH_HUS_NAME']; ?>','<?php echo $order['VILLAGE_NAME']; ?>',' <?php echo $order['UNIT_NAME']; ?>','<?php echo $order['GROWER_ID']; ?>')">
                     
                        View
                    </button>  
                </td>
				<td>
				<button id="viewbtn" type="button" data-toggle="modal" data-target="#myModal2" class="btn btn-primary " onclick="cardstatusdscl('<?php echo $order['GROWER_ID']; ?>','<?php echo $order['CARD_SERIAL_NUMBER']; ?>','<?php echo $order['UNIT_ID']; ?>')">
				Card Status from DSCL
				</button>
				</td>
              </tr>
            <?php $total=$total+$order['total']; $aa++; } ?>
              <?php } ?>
            </tbody>
          </table>
        </div>
        
                                </div>
       <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right">
           <!--<span style="font-weight: bold;">Total Amount : <?php echo $total; ?></span> <br/>-->

         <?php echo $results; ?></div>
        </div>
                    </div>

                    
                </div></div></div>
				
				
<div id="myModal2" class="modal fade" role="dialog">
<div class="modal-dialog" >

<!-- Modal content-->
<div class="modal-content">
<div class="modal-header" style="height:100px;">
<!--<span id="btn_html"></span>-->

<button type="button" class="close pull-right" data-dismiss="modal">&times;</button>


<div >
 <div class="form-group">
    <label for="inputEmail3" style="color:red;" class="col-sm-3 control-label no-padding-right">DSCL CARD STATUS :</label>
      
	   <div class="col-sm-9">
           <input type="text"  name="dsclcardstatus"  value=""  id="dsclcardstatus"  class="form-control" />
			<img id="pimage" class="pull-right" src="view/image/processing_image.gif" style="height:60px; margin-right:40px;margin-top:-50px; display:none;" />	
	  </div>
 </div>
 </div>
</div>
</div>

</div>
</div>
            <link href="view/javascript/ca_Style.css" rel="stylesheet" /> 
 <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
 <link href="https://fonts.googleapis.com/css?family=Jura" rel="stylesheet">
<script type="text/javascript">

	$("#input-filter_subuser").select2();
	$("#filter_status").select2();
	$("#input-unit").select2();
	function cardstatusdscl(grower_id,card_number,unit)
	{
		$("#dsclcardstatus").val('');
		$('#pimage').show(); 
 
		//alert(grower_id+','+card_number+','+unit);
		url="index.php?route=farmerrequest/managercardprint/getCardStatusFromDscl&token=<?php echo $token; ?>&card_number="+card_number+"&grower_id="+grower_id+"&unit="+unit;
		//alert(url);
		$.ajax({ 
		type: 'post',
		url: url,
 
		//dataType: 'json',
		cache: false,

		success: function(json) 
		{
			$('#pimage').hide(); 
			//alert(json);
			if(json=='' || json=='0' || json=='false')
			{
				$('#myModal2').modal('hide');
				alertify.error("Opps ! Server Error ");
	
				return false;
			}
			else
			{
	
			if(json=='"1"')
			{
				json="CARD REQUEST";
			}
			if(json=='"2"')
			{
				json="CARD VERIFIED";
			}
			if(json=='"3"')
			{
				json="CARD APPROVED";
			}
			if(json=='"4"')
			{
				json="CARD REQUEST REJECTED";
			}
			if(json=='"5"')
			{
				json="CARD SEND PRINTING";
			}
			if(json=='"6"')
			{
				json="CARD PRINTED";
			}
			if(json=='"7"')
			{
				json="CARD DISPATCHED";
			}
			if(json=='"8"')
			{
				json="CARD RECIEVED GROWER";
			}
			if(json=='"9"')
			{
				json="CARD ACTIVATED";
			}
			if(json=='"10"')
			{
				json="CARD LOST DAMAGED";
			}
			if(json=='"11"')
			{
				json="CARD DEACTIVATED";
			}
			if(json=='"12"')
			{
				json="CARD REISSUE";
			}
			//alert(json);
			$("#dsclcardstatus").val(json);
			} 
		}
 
	});
	
}
	function cardview(card_number,farmer_name,father_name,village,unit,grower_id)
	{
		$.ajax({ 
				type: 'post',
				url: 'index.php?route=farmerrequest/cardstatus/deleteqr&token=<?php echo $token; ?>&CardSerialNo='+card_number,
				cache: false,
				success: function(data) 
				{
					
				}

			});	
		
		$.ajax({ 
			type: 'post',
			url: 'index.php?route=farmerrequest/cardstatus/generateqrReissue&token=<?php echo $token; ?>&CardSerialNo='+card_number,
			cache: false,
			success: function(data) 
			{
				CARD_QR_IMG=data;
				url = 'index.php?route=farmerrequest/managercardprint/getcardprint&token=<?php echo $token; ?>';
				if (card_number!="") 
				{
					url += '&card_number=' + encodeURIComponent(card_number);
				}
				if (CARD_QR_IMG!="") 
				{
					url += '&CARD_QR_IMG=' + encodeURIComponent(CARD_QR_IMG);
				}
				if (farmer_name!="") 
				{
					url += '&farmer_name=' + encodeURIComponent(farmer_name);
				}
				if (father_name!="") 
				{
					url += '&father_name=' + encodeURIComponent(father_name);
				}
				if (village!="") 
				{
					url += '&village=' + encodeURIComponent(village);
				}
				if (unit!="") 
				{
					url += '&unit=' + encodeURIComponent(unit);
				}
				if (grower_id!="") 
				{
					url += '&grower_id=' + encodeURIComponent(grower_id);
				}
				if(CARD_QR_IMG)
				{
					setTimeout(function() {
						location.reload();
					}, 5000); 
					//$("#span_status").html('Printed');
					window.open (url,"mywindow","menubar=1,resizable=1,width=450,height=450");
				}
			}
		});
}
	$('#button-download-excel').on('click', function() 
	{
		url = 'index.php?route=farmerrequest/managercardprint/download_excel&token=<?php echo $token; ?>';
		
		var filter_unit = $('select[name=\'filter_unit\']').val();
		
		if (filter_unit) 
		{
			url += '&filter_unit=' + encodeURIComponent(filter_unit);
		}

		var filter_date_start = $('input[name=\'filter_date_start\']').val();

		if (filter_date_start) 
		{
			url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
		}

		var filter_date_end = $('input[name=\'filter_date_end\']').val();

		if (filter_date_end) 
		{
			url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
		}
		var filter_subuser = $('#input-filter_subuser').val();

		if (filter_subuser) 
		{
			url += '&filter_subuser=' + encodeURIComponent(filter_subuser);
		}
		var filter_growerid = $('input[name=\'filter_growerid\']').val();

		if (filter_growerid) 
		{
			url += '&filter_growerid=' + encodeURIComponent(filter_growerid);
		}
		var filter_status = $('#filter_status').val();

		if (filter_status) 
		{
			url += '&filter_status=' + encodeURIComponent(filter_status);
		}
		
        window.open(url, '_blank');
       
	});
</script>

<script type="text/javascript"> 
$('.date').datetimepicker({
pickTime: false
});

</script>  

<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=farmerrequest/managercardprint&token=<?php echo $token; ?>';
		
		var filter_unit = $('select[name=\'filter_unit\']').val();
		
		if (filter_unit) 
		{
			url += '&filter_unit=' + encodeURIComponent(filter_unit);
		}

		var filter_date_start = $('input[name=\'filter_date_start\']').val();

		if (filter_date_start) 
		{
			url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
		}

		var filter_date_end = $('input[name=\'filter_date_end\']').val();

		if (filter_date_end) 
		{
			url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
		}
		var filter_subuser = $('#input-filter_subuser').val();

		if (filter_subuser) 
		{
			url += '&filter_subuser=' + encodeURIComponent(filter_subuser);
		}
		var filter_growerid = $('input[name=\'filter_growerid\']').val();

		if (filter_growerid) 
		{
			url += '&filter_growerid=' + encodeURIComponent(filter_growerid);
		}
		var filter_status = $('#filter_status').val();

		if (filter_status) 
		{
			url += '&filter_status=' + encodeURIComponent(filter_status);
		}
		location = url;
});
//--></script> 
      
<?php echo $footer; ?>