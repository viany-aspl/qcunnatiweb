<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-location" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php if(!empty($upload_margin)){ ?>Edit Margin<?php }else { ?>Add Margin<?php } ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> </h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-location" class="form-horizontal">
			<?php if(!empty($upload_margin)){ ?>
			<input type="hidden" name="old_file" value="<?php echo $upload_margin; ?>" />
			<?php } ?>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php //echo $entry_warehouse; ?>Select Store</label>
            <div class="col-sm-10">
                <select name="store_id" required class="form-control">
                  <option value="0">Select Store<?php //echo $text_all_status; ?></option>
                  <?php foreach ($stores as $store) { ?>
                  <?php if ($store['store_id'] == $store_id) { ?>
                  <option value="<?php echo $store['store_id']; ?>,<?php echo $store['name']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $store['store_id']; ?>,<?php echo $store['name']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                
            </div>
          </div>
            
            
            
            <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php //echo $entry_warehouse; ?>Select Month</label>
            <div class="col-sm-10">
          <select name="month_id" required class="form-control">
           <option value="0">Select Month<?php //echo $text_all_status; ?></option>
           <option value="1,January" <?php if (1 == $month_id) { ?> selected="selected" <?php } ?>>January</option>
           <option value="2,February" <?php if (2 == $month_id) { ?> selected="selected" <?php } ?>>February</option>
           <option value="3,March" <?php if (3 == $month_id) { ?> selected="selected" <?php } ?>>March</option>
           <option value="4,April" <?php if (4 == $month_id) { ?> selected="selected" <?php } ?>>April</option>
           <option value="5,May" <?php if (5 == $month_id) { ?> selected="selected" <?php } ?>>May</option>
           <option value="6,June" <?php if (6 == $month_id) { ?> selected="selected" <?php } ?>>June</option>
           <option value="7,July" <?php if (7 == $month_id) { ?> selected="selected" <?php } ?>>July</option>
           <option value="8,August" <?php if (8 == $month_id) { ?> selected="selected" <?php } ?>>August</option>
           <option value="9,September" <?php if (9 == $month_id) { ?> selected="selected" <?php } ?>>September</option>
           <option value="10,October" <?php if (10 == $month_id) { ?> selected="selected" <?php } ?>>October</option>
           <option value="11,November" <?php if (11 == $month_id) { ?> selected="selected" <?php } ?>>November</option>
           <option value="12,December" <?php if (12 == $month_id) { ?> selected="selected" <?php } ?>>December</option>
                                            
                                
                    
                </select>
                
            </div>
          </div>
            
            
      
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name">Upload Margin</label>
            <div class="col-sm-10">
                <input type="file" <?php if(empty($upload_margin)){ ?> required <?php } ?> name="upload_margin" value="<?php //echo $filter_date_end; ?>" placeholder="Upload Margin<?php //echo $entry_date_end; ?>"  class="form-control"  style="padding: 0px 10px;"/>
                 
            </div>
          </div>
   
         
          
       
        
        </form>
      </div>
    </div>
  </div>
</div>
<script>
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
    
<?php echo $footer; ?>