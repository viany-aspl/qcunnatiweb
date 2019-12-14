<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid"> 
      <h1>  Send Pin</h1>
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
        <h3 class="panel-title"><i class="fa fa-list"></i>  Send Pin</h3>
       
      </div>
      <div class="panel-body">

	 
           
            <div class="widget">
                <div class="widget-header bordered-bottom bordered-lightred">
                   
                </div><br/>
                <div class="widget-body">
                    <div id="horizontal-form">
                        <form action="" method="post" enctype="multipart/form-data" id="form-coupon" class="form-horizontal">
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label no-padding-right">Card Serial Number</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="cardserialno" value="" placeholder="Card serial number " id="cardserialno" class="form-control" />
                                     </div>
                                 <div class="col-sm-4">
                                <button type="button" id="button-filter" class="btn btn-primary" onclick="getmobileno();" >Submit</button>
								<img id="cr_img" src="view/image/processing_image.gif" style="float: right;height: 60px;display : none;"/>
								</span>
                            </div>
                           </div>
                            
                        </form>
                    </div>
                </div>
            </div>  
             
            </div>
          
          
            
 
</div>
      
          
<script type="text/javascript"> 
 function getmobileno()
 {
   
    var cardserial_id = document.getElementById('cardserialno').value;
  
   if(cardserial_id=="")
    {
        alertify.error("Please enter card serial number");
        return false;
    }     
    else
    {
    
     $.ajax({
		url: 'index.php?route=farmerrequest/sendpin/sendpintomobile&token=<?php echo $token; ?>&cardserial_id=' +  encodeURIComponent(cardserial_id),
		dataType: 'json',
		beforeSend:function()
			{
			$("#button-filter").hide();
			$("#cr_img").show();
			
			},			
		success: function(json) {
				//alert(json);
                //alert(JSON.stringify(json));
				if(json!="")
				{
                if((json.CARD_PIN=="undefined") || (json.CARD_PIN=="0"))
                {
                    alertify.error('Your card is not activated !');
					$("#button-filter").show();
					$("#cr_img").hide();
					return false;
                }
                else
                {
                    alertify.success('Pin successfully send to your mobile number '); 
					$("#button-filter").show();
					$("#cr_img").hide();
					return false;
                }
				    
				}
				else
				{
				    alertify.error('No record found for this card number !');
					$("#button-filter").show();
					$("#cr_img").hide();
					return false;
				}
               	
                    
                     $('#cardserialno').val('');
			},
                error:function (json){
                    alertify.error(JSON.stringify( json));
					$("#button-filter").show();
					$("#cr_img").hide();
					return false;
                }
                
	});
        
    
   
    }
 }  

 </script>     
      <?php echo $footer; ?>