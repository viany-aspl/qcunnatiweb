<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid"> 
      <h1>Farm</h1>
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
	
	<?php if(!empty($qr_image)){ ?>
	<img src="<?php echo '../system/upload/qrimages/'.$qr_image; ?>" />
	<?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i>Farm's Form</h3>
       
      </div>
      <div class="panel-body" style="min-height: 350px;">

	 
           
            <div class="widget">
                <div class="widget-header bordered-bottom bordered-lightred">
                   
                </div><br/>
                <div class="widget-body">
                    <div id="horizontal-form">
                        <form action="" method="post" enctype="multipart/form-data" id="form-coupon" class="form-horizontal">
						
							<div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label no-padding-right">Farm</label>
                                    <div class="col-sm-4">
                                        <select required name="farm" id="input-farm" class="form-control" onchange="return get_qr_img(this)">
												<option value="">SELECT</option>
												<?php foreach($farms_list as $farm) { ?>
												<option value="<?php echo $farm['id']; ?>"><?php echo $farm['farmname']; ?></option>
												<?php } ?>
											</select>
                                     </div>
									 <div class="col-sm-4" id="qr_img_div">
									 
									 </div>
                                
                           </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label no-padding-right">Ware House</label>
                                    <div class="col-sm-4">
                                        <select required name="ware_house" id="input-ware_house" class="form-control">
												<option value="">SELECT</option>
												<option value="WareHouse1">Ware House 1</option>
												<option value="WareHouse2">Ware House 2</option>
												<option value="WareHouse3">Ware House 3</option>
											</select>
                                     </div>
                                 
                           </div>
                            <div class="form-group">
                                <div class="col-sm-4">
										
									</div>
                                 <div class="col-sm-4">
										<button type="submit" style="float: left;margin-left: -92px;" id="button-filter" class="btn btn-primary"  >Submit</button>
								
									</div>
                           </div>
                        </form>
                    </div>
                </div>
            </div>  
             
            </div>
          
          
            
 
</div>
      
          
<script type="text/javascript"> 


 function get_qr_img(sel)
 {
   
 var txt=sel.options[sel.selectedIndex].text;
var selected_value=sel.options[sel.selectedIndex].value;


   if(selected_value=="")
    {
        alertify.error("Please Select farm");
        return false;
    }     
    else
    {
    selected_value=selected_value+"-"+txt;
     $.ajax({
		url: 'index.php?route=farm/farm/get_qr_img&token=<?php echo $token; ?>&selected_value=' +  encodeURIComponent(selected_value),
		//dataType: 'json',
		beforeSend:function()
			{
			
			$("#qr_img_div").hide();
			
			},			
		success: function(json) {
				//alert(json);
				$("#qr_img_div").html(json);
                $("#qr_img_div").show();
					return false;
				
               	
                    
                    
			},
                error:function (json){
                    alertify.error(JSON.stringify( json));
					alert(JSON.stringify( json));
					
					return false;
                }
                
	});
        
    
   
    }
 }  

 </script>     
      <?php echo $footer; ?>