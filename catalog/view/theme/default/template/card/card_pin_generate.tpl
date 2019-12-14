<!DOCTYPE html>


<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
    <title>Pin Retrieval for Card</title>

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
          <legend>Pin Retrieval for Card</legend>
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
            <label class="col-sm-2 control-label" for="input-telephone">Card Serial Number</label>
            <div class="col-sm-10">
              <input type="tel" name="Card_Serial_Number" onkeypress='return event.charCode >= 48 &amp;&amp; event.charCode <= 57 || event.keyCode==8 || event.keyCode==46' maxlength="10" minlength='16' autocomplete='off'  value="<?php echo $telephone; ?>" placeholder="Card Serial Number" id="Card_Serial_Number" class="form-control" />
             
              <div class="text-danger"><?php echo $error_telephone; ?></div>
              
            </div>
          </div>
         <div class="buttons">
          <div class="pull-right">
            <input type="button" onclick="return create_pin();" value="Submit" class="btn btn-primary" />
          </div>
        </div> 
        </div>
        
      </form>
     <script type="text/javascript">
function create_pin()
{
    var Card_Serial_Number=$("#Card_Serial_Number").val();
    //var grower_id=$("#grower_id").val();
    //&& (grower_id!="")
    var grower_id='';
    if((Card_Serial_Number!=""))
    {
    
    $.ajax({
			url: 'index.php?route=card/card_pin/generate_pin&token=<?php echo $token; ?>&Card_Serial_Number=' +  encodeURIComponent(Card_Serial_Number)+'&grower_id=' +  encodeURIComponent(grower_id),
			//dataType: 'json',			
			success: function(json) {
                                //alert(json);
				if(json==1)
                                {
                                    alertify.success('Pin Successfully Generated');
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
    alertify.error('Please Fill Grower ID and Card Serial Number ');
    }
    return false;
}
</script>
</div> 

