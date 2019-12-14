<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo "Add Supplier"; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo "Delete"; ?>" class="btn btn-danger" onclick="confirm('<?php echo "Are you sure, you want to delete the supplier"; ?>') ? $('#form-customer').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo "Suppliers"; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if (isset($error_warning)) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if (isset($_SESSION['delete_unsuccess_message'])) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $_SESSION['delete_unsuccess_message']; unset($_SESSION['delete_unsuccess_message']); ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if (isset($_SESSION['success_message'])) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if (isset($_SESSION['delete_success_message'])) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $_SESSION['delete_success_message']; unset($_SESSION['delete_success_message']); ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if (isset($_SESSION['update_success_message'])) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $_SESSION['update_success_message']; unset($_SESSION['update_success_message']); ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo "Supplier List"; ?></h3>
      </div>
      <div class="panel-body">
	  <form action = "<?php echo $filter; ?>" method="post" enctype="multipart/form-data" id ="export-form">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo "Supplier Name"; ?></label>
                <input value="<?php if(isset($filter_name)) echo $filter_name; ?>" type="text" name="filter_name" placeholder="Supplier Name" id="input-name" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-email"><?php echo "Supplier Email"; ?></label>
                <input value="<?php if(isset($filter_email)) echo $filter_email; ?>" type="text" name="filter_email" placeholder="Supplier Email" id="input-email" class="form-control" />
              </div>
            </div>
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-customer-group"><?php echo "Supplier Group" ?></label>
                <select name="filter_supplier_group" id="input-customer-group" class="form-control">
                  <option value="">--Supplier Group--</option>
				  <?php
				  foreach($supplier_groups as $supplier_group)
					{
				?>
						<option <?php if(isset($filter_supplier_group)){ if($filter_supplier_group == $supplier_group['supplier_group_name']){ ?>selected = "selected"<?php }} ?>><?php echo $supplier_group['supplier_group_name']; ?></option>
				<?php
					}
				  ?>
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo "Date Added"; ?></label>
                <div class="input-group date">
                  <input type="text" onkeypress="return false;" name="filter_date_added" placeholder="<?php echo "Date Added"; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" value="<?php if(isset($filter_date_added)) echo $filter_date_added; ?>" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
			<div class="col-sm-12">
				<button type="button" onclick="$('#export-form')[0].reset();" id="clear-filter" class="btn btn-primary pull-right"> <?php echo "Clear"; ?></button>
				<button style="margin-right:10px;" type="submit" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo "Filter"; ?></button>
			</div>
          </div>
        </div>
		</form>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-customer">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><a href="#"><?php echo "Supplier Name"; ?></a></td>
                  <td class="text-left"><a href="#"><?php echo "Supplier Email" ?></a></td>
                  <td class="text-left"><a href="#"><?php echo "Supplier group" ?></a></td>
                  <td class="text-left"><a href="#"><?php echo "Date Added" ?></a></td>
                  <td class="text-right"><?php echo "Action" ?></td>
                </tr>
              </thead>
              <tbody>
			  <?php foreach($suppliers as $supplier){?>
				<tr>
					<td class="text-left"><input type="checkbox" name="selected[]" value="<?php echo $supplier['id']; ?>" /></td>
					<td><?php echo $supplier['first_name'] . " " . $supplier['last_name']; ?></td>
					<td><?php echo $supplier['email']; ?></td>
					<td><?php echo $supplier['supplier_group_name']; ?></td>
					<td><?php echo $supplier['date_added']; ?></td>
					<td class="text-left"><!--<a class="btn btn-info" href="#" data-toggle="tooltip" title="view" class="btn btn-primary"><i class="fa fa-eye"></i></a>--><a class="btn btn-primary" href="<?php echo $edit . "&supplier_id=" . $supplier['id']; ?>" data-toggle="tooltip" title="Edit" class="btn btn-primary" style="margin-left: 5px;"><i class="fa fa-pencil"></i></a></td>
				</tr>
			  <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?> 
