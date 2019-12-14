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
	<?php if (isset($_SESSION['delete_unsuccess_message'])) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $_SESSION['delete_unsuccess_message']; unset($_SESSION['delete_unsuccess_message']); ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo "Add Supplier"; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-customer" class="form-horizontal">
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
                            <option value="<?php echo $supplier_group['id']; ?>"<?php if(isset($supplier_group_id) && ($supplier_group['id'] == $supplier_group_id)){ ?>selected="selected"<?php } ?>><?php echo $supplier_group['supplier_group_name']; ?></option>
							<?php
							}
							?>
						  </select>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-firstname"><?php echo "First Name"; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="firstname" value="<?php if(isset($first_name)) echo $first_name;?>" placeholder="First Name" id="input-firstname" class="form-control" />
                          <?php if (isset($_SESSION['error_firstname'])) { ?>
                          <div class="text-danger"><?php echo $_SESSION['error_firstname']; unset($_SESSION['error_firstname']); ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-lastname"><?php echo "Last Name"; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="lastname" value="<?php if(isset($last_name)) echo $last_name;?>" placeholder="<?php echo "Last Name"; ?>" id="input-lastname" class="form-control" />
                          <?php if (isset($_SESSION['error_lastname'])) { ?>
                          <div class="text-danger"><?php echo $_SESSION['error_lastname']; unset($_SESSION['error_lastname']); ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-email"><?php echo "Email"; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="email" value="<?php if(isset($email)) echo $email;?>" placeholder="<?php echo "Email"; ?>" id="input-email" class="form-control" />
                          <?php if (isset($_SESSION['error_email'])) { ?>
                          <div class="text-danger"><?php echo $_SESSION['error_email']; unset($_SESSION['error_email']); ?></div>
                          <?php  } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-telephone"><?php echo "Telephone"; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="telephone" value="<?php if(isset($telephone)) echo $telephone;?>" placeholder="<?php echo "Telephone"; ?>" id="input-telephone" class="form-control" />
                          <?php if (isset($_SESSION['error_telephone'])) { ?>
                          <div class="text-danger"><?php echo $_SESSION['error_telephone']; unset($_SESSION['error_telephone']); ?></div>
                          <?php  } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-fax"><?php echo "District" ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="fax" value="<?php if(isset($fax)) echo $fax; ?>" placeholder="<?php echo "District"; ?>" id="input-fax" class="form-control" />
<?php if (isset($_SESSION['error_district'])) { ?>
                          <div class="text-danger"><?php echo $_SESSION['error_district']; unset($_SESSION['error_district']); ?></div>
                          <?php  } ?>

                        </div>
                      </div>


			<div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-Account"><?php echo "Bank Account Number" ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="account" value="<?php if(isset($account)) echo $account; ?>" placeholder="<?php echo "Bank Account Number"; ?>" id="input-Account" class="form-control" />
<?php if (isset($_SESSION['error_account'])) { ?>
                          <div class="text-danger"><?php echo $_SESSION['error_account']; unset($_SESSION['error_account']); ?></div>
                          <?php  } ?>

                        </div>
                      </div>


<div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-Bank"><?php echo "Bank Name" ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="bank" value="<?php if(isset($bank)) echo $bank; ?>" placeholder="<?php echo "Bank Name"; ?>" id="input-Bank" class="form-control" />
<?php if (isset($_SESSION['error_bank'])) { ?>
                          <div class="text-danger"><?php echo $_SESSION['error_bank']; unset($_SESSION['error_bank']); ?></div>
                          <?php  } ?>

                        </div>
                      </div>

<div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-BankAddress"><?php echo "Bank Address" ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="bankaddress" value="<?php if(isset($bankaddress)) echo $bankaddress; ?>" placeholder="<?php echo "Bank Address"; ?>" id="input-BankAddress" class="form-control" />
<?php if (isset($_SESSION['error_address'])) { ?>
                          <div class="text-danger"><?php echo $_SESSION['error_address']; unset($_SESSION['error_address']); ?></div>
                          <?php  } ?>

                        </div>
                      </div>


<div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-Ifsc"><?php echo "IFSC Code" ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="ifsc" value="<?php if(isset($ifsc)) echo $ifsc; ?>" placeholder="<?php echo "IFSC Code"; ?>" id="input-Ifsc" class="form-control" />
<?php if (isset($_SESSION['error_ifsc'])) { ?>
                          <div class="text-danger"><?php echo $_SESSION['error_ifsc']; unset($_SESSION['error_ifsc']); ?></div>
                          <?php  } ?>

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
