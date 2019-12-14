<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <img id="processing_image" src="https://unnati.world/shop/admin/view/image/processing_image.gif" style="height: 50px;width: 50px;display: none;" />
        
          <button type="submit" id="submit_button" form="form-user" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
<!--onclick="send('index.php?route=news/contact/send&token=<?php echo $token; ?>');" -->
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
      <?php if ($success) {  ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-envelope"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <form method="post" enctype="multipart/form-data" id="form-user" class="form-horizontal" action="index.php?route=news/contact&token=<?php echo $token; ?>">
            <input type="hidden" name="logged_user" value="<?php echo $logged_user; ?>" />
          <div class="form-group required" >
            <label class="col-sm-2 control-label" for="input-to">Category</label>
            <div class="col-sm-10">
              <select name="category" id="input-category" class="form-control" required>
                 <option value="">Select</option>
                <option value="newsletter">Newsletter</option>
                <option value="customer_all">Customer all</option>
                <option value="customer_group">Customer group</option>
                <option value="customer">Customer</option>
                <option value="affiliate_all">Affiliate all</option>
                <option value="affiliate">Affiliate</option>
                <option value="product">Product</option>
              </select>
            </div>
          </div>
          
     
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-subject"><?php echo $entry_subject; ?></label>
            <div class="col-sm-10">
              <input type="text" name="subject" value="" placeholder="<?php echo $entry_subject; ?>" id="input-subject" class="form-control" required />
            </div>
          </div>
           <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-subject">Link</label>
            <div class="col-sm-10">
              <input type="text" name="link" value="" placeholder="Link" id="input-link" class="form-control" required />
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-message"><?php echo $entry_message; ?></label>
            <div class="col-sm-10">
              <textarea name="message" placeholder="<?php echo $entry_message; ?>" id="input-message" class="form-control"></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#input-message').summernote({
	height: 300
});
//--></script> 
  <script type="text/javascript"><!--	
$('select[name=\'to\']').on('change', function() {
	$('.to').hide();
	
	$('#to-' + this.value.replace('_', '-')).show();
});

$('select[name=\'to\']').trigger('change');
//--></script> 
  <script type="text/javascript"><!--


/*
function send(url) {
	// Summer not fix
	$('textarea[name=\'message\']').html($('#input-message').code());
	
	$.ajax({
		url: url,
		type: 'post',
		data: $('#content select, #content input, #content textarea'),		
		dataType: 'json',
		beforeSend: function() {
			$('#button-send').button('loading');	
		},
		complete: function() {
			$('#button-send').button('reset');
		},				
		success: function(json) {
alert(json);
			
			
						
			
							
		},
		error: function(json) {
alert(json);
			
			
						
			
							
		}
	});
}
function send1(url) {
	// Summer not fix
	$('textarea[name=\'message\']').html($('#input-message').code());
	
	$.ajax({
		url: url,
		type: 'post',
		data: $('#content select, #content input, #content textarea'),		
		dataType: 'json',
		beforeSend: function() {
			$('#button-send').button('loading');	
		},
		complete: function() {
			$('#button-send').button('reset');
		},				
		success: function(json) {
alert(json);
			$('.alert, .text-danger').remove();
			
			if (json['error']) {
				if (json['error']['warning']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');
				}
				
				if (json['error']['subject']) {
					$('input[name=\'subject\']').after('<div class="text-danger">' + json['error']['subject'] + '</div>');
				}	
				
				if (json['error']['message']) {
					$('textarea[name=\'message\']').parent().append('<div class="text-danger">' + json['error']['message'] + '</div>');
				}									
			}			
			
			if (json['next']) {
				if (json['success']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i>  ' + json['success'] + '</div>');
					
					send(json['next']);
				}		
			} else {
				if (json['success']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
				}					
			}				
		}
	});
}
*/
//--></script></div>
<?php echo $footer; ?>