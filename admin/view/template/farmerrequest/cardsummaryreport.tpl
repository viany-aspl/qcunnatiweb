<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid"> 
      <h1> Card Summary Report</h1>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> Card Summary Report</h3>
        <button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download</button>
      </div>
      <div class="panel-body">

	 <!---<div class="well">
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
</div>

			
 <div class="col-sm-6 ">
 </div>

            <div class="col-sm-6 ">
                
              
              <button type="button" style="margin-top:23px;" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Filter</button>
			  
            </div>
          </div>
        </div>
-->
        <div class="col-md-12" >                                   
            <div class="widget-body">
            <div class="table-responsive">
            <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">Factory</td>
                <td class="text-left">Requisition</td>
	      <td class="text-left">Printing</td>
                <td class="text-left">Printed</td>
                <td class="text-left">Dispatch</td>
	<td class="text-left">Verify</td> 
	  <td class="text-left">Pending Approval</td>				
                <td class="text-left">Approved</td>
                <td class="text-left">Delivered</td> 
				<td class="text-left">Rejected</td>				
               <td class="text-left">Blocked</td>  
              </tr>
            </thead>
            <tbody id="checkboxes">
			<?php //print_r($orders); ?>
			
              <?php $total=0; if ($orders) {  if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; } ?>
              <?php foreach ($orders as $order) { //print_r($order);  ?>
              <tr>
			    <td class="text-left"><?php echo $order['FACTORY']; ?></td>				
                <td class="text-left"><?php echo $order['REQUTITION']; ?></td>				
				
				
                <td class="text-left"><?php echo $order['PRINTING']; ?></td>
				<td class="text-left"><?php echo $order['PRINTED']; ?></td>
                <td class="text-left"><?php echo $order['DISPATCHED']; ?></td>
<td class="text-left"><?php echo $order['VERIFY']; ?></td>
<td class="text-left"><?php echo $order['REQUEST']; ?></td>
                <td class="text-left"><?php echo $order['APPROVED']; ?></td>
                <td class="text-left"><?php echo $order['DELIVERED']; ?></td>
				<td class="text-left"><?php echo $order['REJECTED']; ?></td>
               <td class="text-left"><?php echo $order['BLOCKED']; ?></td>
                
				
              </tr>
            <?php $total=$total+$order['total']; 
			$aa++; } ?>
              <?php } ?>
            </tbody>
          </table>
        </div>
                                      <!---  </div>
                                        <p class="widget-caption mt10">* Display all Card which are un-verified</p>
                                    </div>---->
                                </div>
      

                    
                </div></div></div>
            <link href="view/javascript/ca_Style.css" rel="stylesheet" /> 
 <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
 <link href="https://fonts.googleapis.com/css?family=Jura" rel="stylesheet">
                     
 <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog" >

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="height:60px;">
	    <!--<span id="btn_html"></span>-->
       <!--- <button type="button" class="close pull-right" data-dismiss="modal">&times;</button>--->
        
      </div>
      <div class="modal-body" id="cmd">
     
<div class="centerbox">
 <?php //print_r($data); ?>
 <div class="grey" style="height:100%">

 <div class="dalmia_logo center">
 <img class="mt10" id="cname" src="">
 </div>

 <div class="Qr_code center" id="qr_img_div">
 
  <img class="qr_image" id="qr_img" src="<?php echo '../system/upload/'.$data['card_qr_img']; ?>">
 
 </div>

 </div>

 <div class="white">
 <div class="logo_icon">
 <img src="view/image/logoicon.png">
 </div>
 <div class="logo_text">
 <img src="view/image/unnati.png">
 </div>

 <div class="container">

 <label for="Name" class="input"  id="Grower_Name_level"><?php echo $data['Grower_Name']; ?></label>
 <label class=" small" for="name">Farmer Name</label>

 <label for="Name" class="input"  id="Father_Name_level"><?php echo $data['Father_Name']; ?></label>
 <label class=" small" for="name">Father Name</label>

 
 <label class="input" id="Grower_Code_level"   for="name"><?php echo $data['Grower_Code']; ?></label>
 <label class=" small" for="name">Grower Id</label>
 </div>

 <div class=semibox>
 <label for="Name" class="input"  id="Village_level"><?php echo $data['Village']; ?></label>
 <label class=" small" for="name">Village</label>
 </div>
 <div class=semibox>
 <label for="Name" class="input" id="Unit_level"><?php echo $data['Unit']; ?></label>
 <label class=" small" for="name">Unit</label>
 </div>

 <div class="serial_no">
 <h1  id="Card_Serial_Number_level"><?php echo $data['Card_Serial_Number']; ?></h1>
 </div>

 </div>
</div> 

	
	
	
	
      </div>
     
    </div>

  </div>
</div>                                   
                <!-- /Page Body -->
                <!-- Modal -->
  <div class="modal fade" id="excelModal"  data-backdrop="static" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
<!--          <button type="button" class="close" data-dismiss="modal">&times;</button>-->
          <h4 class="modal-title">Print data</h4>
        </div>
        <div class="modal-body">
          <p>if you want to download printed card data press download.</p>
        </div>
        <div class="modal-footer">
<!--          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
          <button type="button" id="button-download-excel" class="btn btn-default" data-dismiss="modal">Download</button>
        </div>
      </div>
      
    </div>
  </div>

 <script type="text/javascript">
 $('.date').datetimepicker({
	pickTime: false
});
 $('#button-download').on('click', function() {
    url = 'index.php?route=farmerrequest/cardsummaryreport/download_excel&token=<?php echo $token; ?>';
    	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
        window.open(url, '_blank');
        location.reload();
});
</script>
 
 <script type="text/javascript"><!--
$('#button-filter').on('click', function() {


var url = 'index.php?route=farmerrequest/cardsummaryreport&token=<?php echo $token; ?>';
	
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
//--></script> 
      
<?php echo $footer; ?>