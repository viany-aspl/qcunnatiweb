<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-return').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if (isset($_SESSION['error_wrong'])) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $_SESSION['error_wrong']; unset($_SESSION['error_wrong']); ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if (isset($_SESSION['error_no_change'])) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $_SESSION['error_no_change']; unset($_SESSION['error_no_change']); ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if (isset($_SESSION['text_success'])) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $_SESSION['text_success']; unset($_SESSION['text_success']); ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if (isset($_SESSION['text_success_updated'])) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $_SESSION['text_success_updated']; unset($_SESSION['text_success_updated']); ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if (isset($_SESSION['text_delete_success'])) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $_SESSION['text_delete_success']; unset($_SESSION['text_delete_success']); ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
			<form action = "<?php echo $filter; ?>" method="post" enctype="multipart/form-data" id="export-form" >
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-return-id"><?php echo $entry_return_id; ?></label>
                <input type="text" name="filter_return_id" value="<?php if(isset($return_id)){ echo $return_id; }?>" placeholder="<?php echo $entry_return_id; ?>" id="input-return-id" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
                <input type="text" name="filter_order_id" value="<?php if(isset($order_id)){ echo $order_id; }?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-product"><?php echo $entry_product; ?></label>
				<select name="filter_product" class="form-control">
					<option>--product--</option>
					<?php
						foreach($products as $product)
						{
					?>
							<option <?php if(isset($filter_product)){ if($filter_product == $product){ ?>selected="selected"<?php }}?>><?php echo $product;?></option>
					<?php
						}
					?>
				</select>
                <!--<input type="text" name="filter_product" value="<?php if(isset($product)){ echo $product; }?>" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" />-->
              </div>
			  <div class="form-group">
                <label class="control-label" for="input-supplier"><?php echo $entry_supplier; ?></label>
				<select name="filter_supplier" class="form-control">
					<option>--supplier--</option>
					<?php
						if(isset($filter_supplier))
						{
							$name = explode(' ',$filter_supplier);
						}
						foreach($suppliers as $supplier)
						{
					?>
							<option <?php if(isset($filter_supplier)){ if(($name[0] == $supplier['first_name']) && $name[1] == $supplier['last_name']){ ?>selected="selected"<?php }}?>><?php echo $supplier['first_name'] . " ". $supplier['last_name'];?></option>
					<?php
						}
					?>
				</select>
                <!--<input type="text" name="filter_supplier" value="<?php if(isset($supplier)){ echo $supplier; }?>" placeholder="<?php echo $entry_supplier; ?>" id="input-product" class="form-control" />-->
              </div>
			  
			</div>
			
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $entry_start_date; ?></label>
                <div class="input-group date">
                  <input type="text" onkeydown="return false" name="filter_start_date" value="<?php if(isset($start_date)){ echo $start_date; }?>" placeholder="<?php echo $entry_start_date; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
			</div>
			
			
			
			<div class="col-sm-6">
			  <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $entry_end_date; ?></label>
                <div class="input-group date">
                  <input type="text" onkeydown="return false" name="filter_end_date" value="<?php if(isset($end_date)){ echo $end_date; }?>" placeholder="<?php echo $entry_end_date; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
			</div>
			
			<div class="col-sm-12">
			  <button type="button" onclick="reset_form()" id="clear-filter" class="btn btn-primary pull-right"> <?php echo $button_clear; ?></button>
			  <button  style="margin-right:10px;" type="button" onclick="filter()" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
			</div>
			</form>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-return">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
				<td class="text-center" style="width: 1px;">
					<input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
				</td>
                  <td><a href="#"><?php echo $column_return_id?></a></td>
				  <td><a href="#"><?php echo $column_order_id?></a></td>
				  <td><a href="#"><?php echo $column_product?></a></td>
				  <td><a href="#"><?php echo $column_quantity; ?></a></td>
				  <td><a href="#"><?php echo $column_supplier?></a></td>
				  <td><a href="#"><?php echo $column_date?></a></td>
				  <td><a href="#"><?php echo $column_added_by?></a></td>
				  <td><a href="#"><?php echo $column_action; ?></a></td>
				</tr>
              </thead>
              <tbody>
				<?php if(isset($return_orders)){?>
				<?php foreach($return_orders as $return_order)
				{
				?>
				<tr>
					<td class="text-left">
						<input type="checkbox" value="<?php echo $return_order['id']; ?>" name="selected[]">
					</td>
					<td><?php echo $return_order['id'];?></td>
					<td><?php echo $return_order['order_id'];?></td>
					<td><?php echo $return_order['name'];?></td>
					<td><?php echo $return_order['return_quantity']; ?></td>
					<td><?php echo $return_order['first_name'] . " " . $return_order['last_name'];?></td>
					<td><?php echo $return_order['return_date'];?></td>
					<td><?php echo $return_order['firstname'] . " " . $return_order['lastname'];?></td>
					<td><a class="btn btn-primary" href="<?php echo $edit . "&return_order_id=" . $return_order['id']; ?>" data-toggle="tooltip" title="Edit" class="btn btn-primary" style="margin-left: 5px;"><i class="fa fa-pencil"></i></a></td>
				</tr>
				<?php
				}
				}
				?>
				<!--<tr>
					<td colspan="9">
						<span class="input-group-btn">
								<button class="btn btn-primary pull-right" onclick="export_pdf()" type="button"> Export As PDF</button>
						</span>
					</td>
				</tr>-->
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
function export_pdf()
{
	$('#export-form').attr('target','_blank');
	$('#filter_bit').remove();
	$('#export-form').append("<input id='export_bit' type='hidden' name='export_bit' value='1' />");
	$('#export-form').append("<input id='page_no' type='hidden' name='page_no' value='<?php if(isset($page_no)){ echo $page_no; } ?>' />");
	$('#export-form').submit();
}

function filter()
{
	$('#export_bit').remove();
	$('#export-form').append("<input id='filter_bit' type='hidden' name='filter_bit' value='1' />");
	$('#export-form').removeAttr('target');
	$('#export-form').submit();
}
function reset_form()
{
	$('[name=filter_return_id]').val('');
	$('[name=filter_order_id]').val('');
	$('[name=filter_start_date]').val('');
	$('[name=filter_end_date]').val('');
	$('[name=filter_supplier]').prop('selectedIndex', 0);
	$('[name=filter_product]').prop('selectedIndex', 0);
	$('[name=order_id]').val('');
}
</script></div>
<?php echo $footer; ?> 