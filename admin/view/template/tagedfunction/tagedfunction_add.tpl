<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-store" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1>Tagged Function Add</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">Tagged Function Add</a></li>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> Tagged Function Add</h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-store" class="form-horizontal">
            
             <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Company</label>
                <div class="col-sm-10">
             
                <select required name="company_name" id="input-store" class="form-control" >
                      <option selected="selected" value="">SELECT COMPANY</option>
                      <?php foreach ($company as $comp) { ?>
                   
                  <option value="<?php echo $comp['company_id']; ?>" <?php if($comp['company_id']==$company_name) { echo 'selected'; } ?>><?php echo $comp['company_name']; ?></option>
                    
                  <?php } ?>
                </select>
                </div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Function Type</label>
                <div class="col-sm-10">
             
                <select  name="function_name" id="function_name" class="form-control" >
                      <option selected="selected" value="">SELECT FUNCTION</option>
                      <?php foreach ($functype as $fun) { ?>
                   
                  <option value="<?php echo $fun['functypeid']; ?>" <?php if($fun['functypeid']==$function_name) { echo 'selected'; } ?>><?php echo $fun['functypename']; ?></option>
                    
                  <?php } ?>
                </select>
                </div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title">Function Name</label>
                <div class="col-sm-10">
                    <input type="text" name="f_name" value="" placeholder="Add Function" id="f_name" class="form-control"  required="required" />
                  <?php if ($error_meta_title) { ?>
                  <div class="text-danger"><?php echo $error_meta_title; ?></div>
                  <?php } ?>
                </div>
            </div>
           
         
        </form>
      </div>
    </div>
  </div>
  
<?php echo $footer; ?>