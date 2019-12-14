<!DOCTYPE html>


<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
    <title>Pin Change for Card</title>

	<link rel="stylesheet" href="catalog/view/theme/default/stylesheet/alertify.core.css">
	<script src="catalog/view/theme/default/javascript/alertify.min.js"></script>

<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />
<link href="catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet">
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/common.js" type="text/javascript"></script>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>
<?php echo $google_analytics; ?>
</head>
<body class="<?php echo $class; ?>">
<div class="container">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <fieldset id="account">
          <legend>Pin Change for Card</legend>
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

          
          
          <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Card Serial Number</label>
                <div class="col-sm-10">
                    <input type="text" onkeypress='return event.charCode >= 48 &amp;&amp; event.charCode <= 57 || event.keyCode==8 || event.keyCode==46' maxlength="10" minlength='16' autocomplete='off' name="Card_Serial_Number" value="" placeholder="Card Serial Number" id="Card_Serial_Number" class="form-control"  required="required" />
                
                </div>
        </div>
            
        <div class="form-group required" id="div_for_old_pin">
            <label class="col-sm-2 control-label" for="input-meta-title">Old Pin (<a href="<?php echo $generate_pin_link; ?>" onclick="return send_otp();">If forgotten, then generate OTP</a>)</label>
                <div class="col-sm-10">
                    <input type="text" onkeypress='return event.charCode >= 48 &amp;&amp; event.charCode <= 57 || event.keyCode==8 || event.keyCode==46' maxlength="10" minlength='16' autocomplete='off' name="old_pin" value="" placeholder="Old Pin" id="old_pin" class="form-control"   />
                  
                </div>
        </div>
            <div class="form-group required" style="display: none;" id="div_for_otp">
            <label class="col-sm-2 control-label" for="input-meta-title">OTP</label>
                <div class="col-sm-10">
                    <input type="text" onkeypress='return event.charCode >= 48 &amp;&amp; event.charCode <= 57 || event.keyCode==8 || event.keyCode==46' maxlength="10" minlength='16' autocomplete='off' name="otp" value="" placeholder="OTP" id="otp" class="form-control"   />
                  
                </div>
        </div>
        <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">New Pin</label>
                <div class="col-sm-10">
                    <input type="text" onkeypress='return event.charCode >= 48 &amp;&amp; event.charCode <= 57 || event.keyCode==8 || event.keyCode==46' maxlength="10" minlength='16' autocomplete='off' name="new_pin" value="" placeholder="New Pin" id="new_pin" class="form-control"  required="required" />
                  
                </div>
        </div>
        <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Confirm New Pin</label>
                <div class="col-sm-10">
                    <input type="text" onkeypress='return event.charCode >= 48 &amp;&amp; event.charCode <= 57 || event.keyCode==8 || event.keyCode==46' maxlength="10" minlength='16' autocomplete='off' name="cnfrm_new_pin" value="" placeholder="Confirm New Pin" id="cnfrm_new_pin" class="form-control"  required="required" />
                 
                </div>
        </div> 
            <input type="hidden" name="pin_or_otp" id="pin_or_otp" value="pin" />
         <div class="buttons">
          <div class="pull-right">
            <input type="button" onclick="return reset_pin();" value="Submit" class="btn btn-primary" />
          </div>
        </div> 
        </div>
        
      </form>
     <script type="text/javascript">
function send_otp()
{
    return true;
   var Card_Serial_Number=$("#Card_Serial_Number").val();
    var grower_id=$("#grower_id").val();
    if((Card_Serial_Number!="") && (grower_id!=""))
    {
    
    $.ajax({
			url: 'index.php?route=card/card_pin/send_otp&token=<?php echo $token; ?>&Card_Serial_Number=' +  encodeURIComponent(Card_Serial_Number)+'&grower_id=' +  encodeURIComponent(grower_id),
			//dataType: 'json',			
			success: function(json) {
                                //alert(json);
				if(json==1)
                                {
                                    $("#pin_or_otp").val('otp');
                                    $("#div_for_old_pin").hide();
                                    $("#div_for_otp").show();
                                    alertify.success('OTP sent Successfully');
                                }
                                else if(json=='0')
                                {
                                    alertify.error('Some error occour. please try again');
                                }
                                else
                                {
                                    alertify.error(json);
                                }
                                
                                
			},
                        error: function(json)
                        {
                           alertify.error(json); 
                        }
                    
		});
    }
    else
    {
    alertify.error('Please Fill  Grower ID and Card Serial Number ');
    }
    return false; 
}
function reset_pin()
{
    var Card_Serial_Number=$("#Card_Serial_Number").val();
    //var grower_id=$("#grower_id").val();
    var old_pin=$("#old_pin").val();
    var otp=$("#otp").val();
    var new_pin=$("#new_pin").val();
    var cnfrm_new_pin=$("#cnfrm_new_pin").val();
    
    var pin_or_otp=$("#pin_or_otp").val();
    var grower_id='';
    //&& (grower_id!="")
    if((Card_Serial_Number!="")  && ((old_pin!='') || (otp!='')) && (new_pin!="") && (cnfrm_new_pin!=""))
    {
        
        if((pin_or_otp=='pin') && (old_pin==''))
        {
           alertify.error('Please enter Old Pin'); 
        }
        else if((pin_or_otp=='otp') && (otp==''))
        {
           alertify.error('Please enter OTP '); 
        }
        else if(new_pin!=cnfrm_new_pin)
        {
            alertify.error('New Pin and Confirm New Pin should be same');  
        }
        else
        {
                //+'&grower_id=' +  encodeURIComponent(grower_id)
                $.ajax({
			url: 'index.php?route=card/card_pin/change_pin_function&token=<?php echo $token; ?>&Card_Serial_Number=' +  encodeURIComponent(Card_Serial_Number)+'&old_pin=' +  encodeURIComponent(old_pin)+'&otp=' +  encodeURIComponent(otp)+'&new_pin=' +  encodeURIComponent(new_pin)+'&pin_or_otp=' +  encodeURIComponent(pin_or_otp),
			//dataType: 'json',			
			success: function(json) {
                                //alert(json);
				if(json==1)
                                {
                                    alertify.success('Pin Changed Successfully');
                                }
                                
                                else if(json=='0')
                                {
                                    alertify.error('Some error occour. please try again');
                                }
                                else
                                {
                                    alertify.error(json);
                                }
                                
                                
			},
                        error: function(json)
                        {
                           alertify.error(json); 
                        }
                    
		});
        }
    }
    else
    {
    alertify.error('Please Fill all details');
    }
    return false;
}
</script> 
</div> 

