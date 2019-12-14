<?php echo $header; ?><?php echo $column_left; ?>
<div class="page-content">
  <div class="page-breadcrumbs">
    
      <!-- Page Header -->
        <div class="page-header position-relative">
            <div class="header-title">
                <h1>
                    <?php echo $heading_title; ?>
                </h1>
            </div>
        <div class="pull-right">
            <button type="submit" form="form-coupon" data-toggle="tooltip" title="" class="btn btn-primary"><i class="fa fa-save"></i></button></div>
        </div>
        </div>
  <div class="page-body"> 
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i>-----</h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-order-id">Grower ID</label>
                <input type="text" name="grower_id" value="" placeholder="Enter Grower ID " id="input-grower_id" class="form-control" />
              </div>
             
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-order-status">Mobile No</label>
                <input type="text" name="mobile" onkeypress='return event.charCode >= 48 &amp;&amp; event.charCode <= 57 || event.keyCode==8 || event.keyCode==46' max='10' min='10' value="" placeholder="Enter Mobile Number " id="input-mobile" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-total"></label>
                <input type="submit" id="button-filter" class="btn btn-primary pull-right" onclick="chekgrowerid();" value="submit"/>
              </div>
            </div>
            
        </div>
        </div>
         <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-coupon" class="form-horizontal">
        <div class="panel-body">       
            <div id="rk" style="display: none">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-code">Farmer Name</label>
                <div class="col-sm-10">
                  <input type="text" name="farmername" value="" placeholder="Enter Farmer Name" id="input-farmername" class="form-control" />
                  <?php if ($error_code) { ?>
                  <div class="text-danger"><?php echo $error_farmername; ?></div>
                  <?php } ?>
                </div>
              </div>
                <div class="form-group">
                <label class="col-sm-2 control-label" for="input-code">Father Name</label>
                <div class="col-sm-10">
                    <input type="text" name="fathername" value="" placeholder="Enter Father Name" id="input-fathername" class="form-control" />
                  <?php if ($error_code) { ?>
                  <div class="text-danger"><?php echo $error_fathername; ?></div>
                  <?php } ?>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-mobile">Mobile Number</label>
                <div class="col-sm-10">
                  <input type="text" name="discount" onkeypress='return event.charCode >= 48 &amp;&amp; event.charCode <= 57 || event.keyCode==8 || event.keyCode==46' max='10' min='10' value="" placeholder="Enter Mobile Number " id="input-discount" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-mobile">Addhar Number</label>
                <div class="col-sm-10">
                  <input type="text" name="addhar" onkeypress='return event.charCode >= 48 &amp;&amp; event.charCode <= 57 || event.keyCode==8 || event.keyCode==46' value="" placeholder="Enter Addhar Number " id="input-addhar" class="form-control" />
                </div>
              </div>
             </div>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"></div>
          <div class="col-sm-6 text-right"></div>
        </div>
      </div>
    </div>
  </div>
    
   <!----<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=farmerrequest/farmerrequest&token=<?php echo $token; ?>';
	
	var grower_id = $('input[name=\'grower_id\']').val();
	
	if (grower_id) {
		url += '&grower_id=' + encodeURIComponent(grower_id);
	}
	
	var mobile = $('input[name=\'mobile\']').val();
	
	if (mobile) {
		url += '&mobile=' + encodeURIComponent(mobile);
	}
  
	    location = url;
             //alert(url);
         
});
//--></script>  
   <script type="text/javascript"> 
 function chekgrowerid()
 {
    alert("nsdknfks");
    var grower_id = document.getElementById('input-grower_id').value;
    var mobile = document.getElementById('input-mobile').value;
    try{
     $.ajax({
		url: 'index.php?route=farmerrequest/farmerrequest/chek&token=<?php echo $token; ?>&grower_id=' +  encodeURIComponent(grower_id),
		dataType: 'json',			
		success: function(json) {
		
			}
                
	});
        }
        catch(e)
        {
        alert(e);
        }
     
 }
 </script>     
      
      

    
 </div>
<?php echo $footer; ?>