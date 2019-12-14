<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid"> 
      <h1>Doc Uplaod Report</h1>
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
        <h3 class="panel-title"><i class="fa fa-list"></i>Doc Uplaod Report</h3>
        <!-- <button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download</button>--->
      </div>
      <div class="panel-body">
		  <div class="well">
          <div class="row">
            
            <div class="col-sm-6">
               
              <div class="form-group">
                <label class="control-label" >Select User</label>
               
                      
                  <select name="filter_user" id="filter_user" style="width:100%"  class="select2 form-control">
                   <option selected="selected" value="">SELECT USER</option>
					<?php foreach($getuser as $user){ ?>
						<option value="<?php echo $user['user_id']; ?>" <?php if($filter_user==$user['user_id']){ ?> selected="selected" <?php } ?> ><?php echo $user['firstname']."  ".$user['lastname']; ?></option>
					<?php } ?>
                                  
                </select>
                  <br/>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>


        <div class="col-md-12" >                                   
            <div class="widget-body">
            <div class="table-responsive">
            <table class="table table-bordered">
            <thead>
              <tr>
			  
				

                <td class="text-left">Sno</td>
                <td class="text-left">User Name</td>				
                <td class="text-left">Store Name</td>
                <td class="text-left">Document Type</td>
                <td class="text-left">Remarks</td>
                <td class="text-left">View</td>
                
              </tr>
            </thead>
            <tbody id="checkboxes">
			<?php //print_r($orders); ?>
			
              <?php $total=0; if ($orders) {  if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; } ?>
              <?php foreach ($orders as $order) {// print_r($order); ?>
              <tr>
			  <td class="text-left"><?php echo $aa; ?></td>
                <td class="text-left"><?php echo $order['username']; ?></td>
				
                <td class="text-left"><?php echo $order['storename']; ?></td>
                <td class="text-left"><?php echo $order['document_description']; ?></td>
                <td class="text-left"><?php echo $order['remarks']; ?></td>
                <td class="text-left">
                    <button  id="viewbtn"  type="button" data-toggle="modal" data-target="#myModal"  class="btn btn-primary " 
						   onclick="docview('<?php echo $order['sid']; ?>')"	/>                     
                        View
                    </button> 
					
                 
                </td>
				
              </tr>
            <?php $total=$total+$order['total']; $aa++; } ?>
              <?php } ?>
            </tbody>
          </table>
        </div> <!---onclick="cardview('<?php echo $order['GROWER_ID']; ?>','<?php echo $order['CARD_SERIAL_NUMBER']; ?>','<?php echo $order['GROWER_NAME']; ?>','<?php echo $order['FTH_HUS_NAME']; ?>',' <?php echo $order['VILLAGE']; ?>','<?php echo $order['UNIT_ID']; ?>','<?php echo $order['CARD_QR_IMG']; ?>','<?php echo $order['CNAME']; ?>')"---->
                                      <!---  </div>
                                        <p class="widget-caption mt10">* Display all Card which are un-verified</p>
                                    </div>---->
                                </div>
       <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right">
          <?php //echo $total; ?></span> <br/>

         <?php echo $results; ?></div>
        </div>
                    </div>

                    
                </div></div></div>

                     
 <div id="myModal" class="modal fade"   role="dialog">
  <div class="modal-dialog" >

  
    <div class="modal-content">
      <div class="modal-header" style="height:60px;">
	    <!--<span id="btn_html"></span>-->
	
        <button type="button" class="close pull-right" data-dismiss="modal">&times;</button>
        
      </div>
      <div class="modal-body"  id="printarea">
     
	<div class="centerbox">
 

	<div class="white">
 

	 <div class="container pl-15"> 
	 
	  <img class="center" id="img" src="" style="height:300px;width:520px; margin:0 auto;" >
	 </div>

	     </div>
		</div> 
    </div>     
   </div>
 </div>
</div>                                   
                <!-- /Page Body -->
			
<script type="text/javascript">
$("#filter_user").select2();
 $('#button-filter').on('click', function() { //alert("gxhbg");
	url = 'index.php?route=report/document_upload&token=<?php echo $token; ?>';
	
	
	var filter_user = $('#filter_user').val();
	if (filter_user=='') {
		alertify.error("Please select user");
	}
	if (filter_user) {
		url += '&filter_user=' + encodeURIComponent(filter_user);
	}	
        
	location = url;
});
</script>    

<script type="text/javascript"> 

$('.date').datetimepicker({
pickTime: false
});


function docview(sid)
{
	//alert(sid);
 $('#pimage').show(); 

 $.ajax({ 
 type: 'post',
 url: 'index.php?route=report/document_upload/modaldocdisplay&token=<?php echo $token; ?>&sid='+sid,
 
 cache: false,

success: function(data) {
	//alert(data);
	 $('#pimage').hide(); 

    $("#img").attr("src","../system/upload/Doc/"+data);
	
 }
 
 });	
	
	

}


 
$('#button-download').on('click', function() {
    url = 'index.php?route=report/document_upload/download_excel&token=<?php echo $token; ?>';
  
   var filter_user = $('#filter_user').val();
	
	if (filter_user) {
		url += '&filter_user=' + encodeURIComponent(filter_user);
	}
    //location = url;
        window.open(url, '_blank');
});

    
 </script>
 
<?php echo $footer; ?>