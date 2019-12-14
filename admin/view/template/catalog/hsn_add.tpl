<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button onclick="return check_form();" type="button" form="form-store" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1>HSN ADD</h1>
      <!--<ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">HSN ADD</a></li>
        <?php } ?>
      </ul>-->
    </div>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> HSN ADD</h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-store" class="form-horizontal">
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">HSN Code</label>
                <div class="col-sm-10">
                    <input type="text" name="hsn_code" value="" placeholder="Add HSN Code" id="hsn_code" class="form-control"  required="required" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57 || event.keyCode==8 || event.keyCode==46" />
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>
                </div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">HSN Name</label>
                <div class="col-sm-10">
                    <input type="text" name="hsn_name" value="" placeholder="Add HSN" id="hsn_name" class="form-control"  required="required" />
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>
                </div>
            </div>
           
         
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript">
function check_form()
{

var hsn_code=$("#hsn_code").val();
var hsn_name=$("#hsn_name").val();
//alert(hsn_code);
//return false;
if(hsn_code=="")
{
 $("#hsn_code").focus();
 return false;
}
else if(hsn_name=="")
{
 $("#hsn_name").focus();
 return false;
}
else
{
 $("#form-store").submit();
 return true;
}
}

</script> 
  </div>
<?php echo $footer; ?>