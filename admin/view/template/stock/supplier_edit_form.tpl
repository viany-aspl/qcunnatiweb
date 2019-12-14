<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-customer" data-toggle="tooltip" title="<?php echo "Save"; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo "Cancel"; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo "Suppliers"; ?></h1>
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
	<?php if (isset($_SESSION['update_unsuccess_message'])) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $_SESSION['update_unsuccess_message']; unset($_SESSION['update_unsuccess_message']); ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if (isset($_SESSION['delete_unsuccess_message'])) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $_SESSION['delete_unsuccess_message']; unset($_SESSION['delete_unsuccess_message']); ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo "Edit Supplier"; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-customer" class="form-horizontal">
          <input type="hidden" name ="supplier_id" value="<?php echo $supplier_info['id']; ?>" />
		  <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo "General"; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="row">
                <div class="col-sm-10">
                  <div class="tab-content">
                    <div class="tab-pane active" id="tab-customer">
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-supplier-group"><?php echo "Supplier Group"; ?></label>
                        <div class="col-sm-10">
                          <select name="supplier_group_id" id="input-supplier-group" class="form-control">
                            <!--<option value="<?php echo 0; ?>" selected="selected">Default</option>-->
                            <?php foreach ($supplier_groups as $supplier_group) { ?>
                            <option value="<?php echo $supplier_group['id']; ?>"<?php if($supplier_group['id'] == $supplier_info['supplier_group_id']){?>selected="selected"<?php } ?>><?php echo $supplier_group['supplier_group_name']; ?></option>
							<?php
							}
							?>
						  </select>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-firstname"><?php echo "First Name"; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="firstname" value="<?php if(isset($supplier_info['first_name'])) echo $supplier_info['first_name'];?>" placeholder="First Name" id="input-firstname" class="form-control" />
                          <?php if (isset($_SESSION['error_firstname'])) { ?>
                          <div class="text-danger"><?php echo $_SESSION['error_firstname']; unset($_SESSION['error_firstname']); ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-lastname"><?php echo "Last Name"; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="lastname" value="<?php if(isset($supplier_info['last_name'])) echo $supplier_info['last_name'];?>" placeholder="<?php echo "Last Name"; ?>" id="input-lastname" class="form-control" />
                          <?php if (isset($_SESSION['error_lastname'])) { ?>
                          <div class="text-danger"><?php echo $_SESSION['error_lastname']; unset($_SESSION['error_lastname']); ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-email"><?php echo "Email"; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="email" value="<?php if(isset($supplier_info['email'])) echo $supplier_info['email'];?>" placeholder="<?php echo "Email"; ?>" id="input-email" class="form-control" />
                          <?php if (isset($_SESSION['error_email'])) { ?>
                          <div class="text-danger"><?php echo $_SESSION['error_email']; unset($_SESSION['error_email']); ?></div>
                          <?php  } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-telephone"><?php echo "Telephone"; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="telephone" value="<?php if(isset($supplier_info['telephone'])) echo $supplier_info['telephone'];?>" placeholder="<?php echo "Telephone"; ?>" id="input-telephone" class="form-control" />
                          <?php if (isset($_SESSION['error_telephone'])) { ?>
                          <div class="text-danger"><?php echo $_SESSION['error_telephone']; unset($_SESSION['error_telephone']); ?></div>
                          <?php  } ?>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-fax"><?php echo "Fax" ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="fax" value="<?php if(isset($supplier_info['fax'])) echo $supplier_info['fax']; ?>" placeholder="<?php echo "Fax"; ?>" id="input-fax" class="form-control" />
                        </div>
                      </div>
                     </div>
                </div>
              </div>
            </div>
        </form>
      </div>
    </div>
  </div>
<?php echo $footer; ?>
