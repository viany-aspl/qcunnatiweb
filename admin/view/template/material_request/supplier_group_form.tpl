<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-customer-group" data-toggle="tooltip" title="<?php echo "Save"; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo "Cancel"; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo "Supplier Groups"; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if (isset($_SESSION['unsuccess_message'])) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $_SESSION['unsuccess_message']; unset($_SESSION['unsuccess_message']); ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo "Add Supplier Group"; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-customer-group" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo "Supplier Group Name"; ?></label>
            <div class="col-sm-10">
              <div class="input-group"><span class="input-group-addon"></span>
                <input type="text" name="sgname" value="" placeholder="Supplier Group Name" class="form-control" />
              </div>
			  <?php if (isset($_SESSION['name_error'])) { ?>
             <div class="text-danger"><?php echo $_SESSION['name_error']; unset($_SESSION['name_error']); ?></div>
             <?php } ?>
             </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for=""><?php echo "Description"; ?></label>
            <div class="col-sm-10">
              <div class="input-group"><span class="input-group-addon"></span>
                <textarea name="sgdescription" rows="5" placeholder="<?php echo "Description"; ?>" id="" class="form-control"></textarea>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>