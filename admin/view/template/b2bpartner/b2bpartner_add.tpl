<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-store" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1>B2B Partner Add</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">B2B Partner Add</a></li>
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i>B2B Partner Add</h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-store" class="form-horizontal">
            
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Name</label>
                <div class="col-sm-10">             
                <input type="text" name="name" value="" placeholder="Enter Name" id="name" class="form-control"  required="required" />
                </div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Email</label>
                <div class="col-sm-10">             
                <input type="email" name="email" value="" placeholder="Enter Email" id="email" class="form-control"  required="required" />
                </div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Telephone</label>
                <div class="col-sm-10">             
                <input type="text" name="telephone" value="" placeholder="Enter Telephone" id="telephone" class="form-control"  required="required" onkeypress='return event.charCode >= 48 &amp;&amp; event.charCode <= 57 || event.keyCode==8 || event.keyCode==46'

 />
                </div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Pancard</label>
                <div class="col-sm-10">             
                <input type="text" name="pancard" value="" placeholder="Enter Pancard" id="pancard" class="form-control"  required="required" />
                </div>
            </div>
            <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-meta-title">Gstn</label>
                <div class="col-sm-10">             
                <input type="text" name="gstn" value="" placeholder="Enter Gstn" id="gstn" class="form-control"  />
                </div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Address</label>
                <div class="col-sm-10">             
                <input type="text" name="address" value="" placeholder="Enter Address" id="address" class="form-control"  required="required" />
                </div>
            </div>
            
           
         
        </form>
      </div>
    </div>
  </div>
  
<?php echo $footer; ?>