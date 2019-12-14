<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-location" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="index.php?route=margin/margin/margingetList&token=<?php echo $token;?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php if(!empty($margin_id)){ ?>Edit Margin<?php }else { ?>Add Margin<?php } ?></h1>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php if(!empty($margin_id)){ ?>Edit Margin<?php }else { ?>Add Margin<?php } ?></h3>
      </div>
      <div class="panel-body">
        <form action="index.php?route=margin/margin/<?php echo $action; ?>&token=<?php echo $token;//echo $add; ?><?php //echo $action; ?>" method="post" enctype="multipart/form-data" id="form-location" class="form-horizontal">
			
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php //echo $entry_warehouse; ?>Select Product</label>
            <div class="col-sm-10">
            <input type="text" name="product_name" placeholder="Product Name<?php //echo $product_name; ?>" value="<?php echo $product_name;?>" id="input-name" class="form-control" />
              <input type="hidden" name="product_id" value="<?php echo $product_id;?>" placeholder="<?php echo $entry_name_id; ?>" id="input-name_id" class="form-control" />  
             <?php if ($error_product_id) { ?>
                  <div class="text-danger"><?php echo $error_product_id; ?></div>
                  <?php } ?>
			</div>
          </div>
            
            <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name">Margin</label>
            <div class="col-sm-10">
                <input type="text" name="margin" onkeypress='return validate_numeric(event);' value="<?php echo $margin; ?>" placeholder="Margin" id="input-name" class="form-control" />
             
                <?php if ($error_margin) { ?>
                  <div class="text-danger"><?php echo $error_margin; ?></div>
                  <?php } ?>  
            </div>
          </div>
          
            <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php //echo $entry_warehouse; ?>Select Month</label>
            <div class="col-sm-10">
			
			
          <select name="month" id="month" class="form-control ">
				<option value=''>Select Month</option>
				<?php
				foreach($allmonth as $month2){
				?>
				<option value="<?php echo $month2[0];?>" <?php echo ($month2[0] == $month) ? 'selected' : ''; ?>><?php echo $month2[1]; ?></option>
				<?php
				}
				?>
				</select>
				<?php if ($error_month) { ?>
                  <div class="text-danger"><?php echo $error_month; ?></div>
               <?php } ?>
                
            </div>
          </div>
            
            
      
          
   
         
          
       
        
        </form>
      </div>
    </div>
  </div>
</div>
<script>

function validate_numeric(event) {
  var key = window.event ? event.keyCode : event.which;
    if (event.keyCode === 8 || event.keyCode === 46) {
        return true;
    } else if ( key < 48 || key > 57 ) {
        return false;
    } else {
        return true;
    }
}

 function checkFile(e,idd) 
{
    /// get list of files
    var file_list = e.target.files;
    /// go through the list of files
    for (var i = 0, file; file = file_list[i]; i++) 
    {

        var sFileName = file.name;
        var sFileExtension = sFileName.split('.')[sFileName.split('.').length - 1].toLowerCase();
        var iFileSize = file.size;
        var iConvert = (file.size / 1048576).toFixed(2);
        if (!(sFileExtension === "pdf" || sFileExtension === "zip" || sFileExtension === "rar" ) || iFileSize > 1048576) 
         { 
            
            var txt = "Please make sure your file is in pdf or zip or rar format and less than 1 MB";
            document.getElementById(idd).value='';
            alertify.error(txt);
            return false;
        }
        else
        {
          //alertify.success('Great ! file ');
          return true;
        }
    }
}function checkFile(e,idd) 
{
    /// get list of files
    var file_list = e.target.files;
    /// go through the list of files
    for (var i = 0, file; file = file_list[i]; i++) 
    {

        var sFileName = file.name;
        var sFileExtension = sFileName.split('.')[sFileName.split('.').length - 1].toLowerCase();
        var iFileSize = file.size;
        var iConvert = (file.size / 1048576).toFixed(2);
        if (!(sFileExtension === "pdf" || sFileExtension === "zip" || sFileExtension === "rar" ) || iFileSize > 1048576) 
         { 
            
            var txt = "Please make sure your file is in pdf or zip or rar format and less than 1 MB";
            document.getElementById(idd).value='';
            alertify.error(txt);
            return false;
        }
        else
        {
          //alertify.success('Great ! file ');
          return true;
        }
    }
}
</script>  
 <script type="text/javascript"><!--
$('input[name=\'product_name\']').autocomplete({
	'source': function(request, response) 
	{
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) 
			{
				$('input[name=\'product_id\']').val('');
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'product_name\']').val(item['label']);
		$('input[name=\'product_id\']').val(item['value']);
	}
});


//--></script>
    
<?php echo $footer; ?>