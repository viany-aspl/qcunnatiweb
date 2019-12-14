<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $inout_heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo $inout_text_list; ?></h3>
      </div>
      <div class="panel-body">
	  <form id="filter_form" action = "<?php echo $filter; ?>" method="post" enctype="multipart/form-data">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="input-group date">
                  <input onkeypress="return false" type="text" name="date_start" value="<?php if(isset($date_start)) { echo $date_start; }?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
			</div>
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input onkeypress="return false;" type="text" name="date_end" value="<?php if(isset($date_end)) { echo $date_end; }?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
		</div>
		<div class="row">
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-product"><?php echo $entry_product; ?></label>
                <div class="input-group">
                  <span class="input-group-btn">
					<select class="form-control" name="product">
						<option>--product--</option>
						<?php foreach($products as $product){
						?>
						<option <?php if(isset($filter_product)){if($product == $filter_product){?>selected="selected"<?php }} ?>><?php echo $product; ?></option>
						<?php
						}?>
					</select>
                  </span></div>
              </div>
            </div>
		</div>
		  <div class="row">
				<div class="col-sm-12">
				  <button type="button" onclick="resetForm()" id="clear-filter" class="btn btn-primary pull-right"> <?php echo $button_clear; ?></button>
				  <button style="margin-right:10px;" type="button" onclick="filter()" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
				</div>
		  </div>
        </div>
		</form>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
			  <tr>
                <td class="text-left"><?php echo $column_product_name; ?></td>
                <!--<td class="text-left"><?php echo $column_date; ?></td>-->
                <td class="text-left"><?php echo $column_instock; ?></td>
				<td class="text-right"><?php echo $column_outstock; ?></td>
              </tr>
            </thead>
            <tbody>
				<?php foreach($inout_details as $inout_detail){ ?>
				<tr>
					<!--<td><span onclick="view_inout_details(1,<?php echo $inout_detail['product_id'];?>)" style="cursor:pointer;cursor:hand" data-toggle="tooltip" data-original-title="click to view details"><?php echo $inout_detail['name']; ?></span></td>
					<td><span onclick="view_inout_details(2,<?php echo $inout_detail['product_id'];?>)" style="cursor:pointer;cursor:hand" data-toggle="tooltip" data-original-title="click to view details"><?php if(isset($inout_detail['purchase_quantity'])){ echo $inout_detail['purchase_quantity']; } ?></span></td>
					<td><span onclick="view_inout_details(3,<?php echo $inout_detail['product_id'];?>)" style="cursor:pointer;cursor:hand" data-toggle="tooltip" data-original-title="click to view details"><?php if(isset($inout_detail['sales_quantity'])){ echo $inout_detail['sales_quantity']; } ?></span></td>-->
					<td><span data-toggle="tooltip"><?php echo $inout_detail['name']; ?></span></td>
					<td><span data-toggle="tooltip"><?php if(isset($inout_detail['purchase_quantity'])){ echo $inout_detail['purchase_quantity']; } ?></span></td>
					<td><span data-toggle="tooltip"><?php if(isset($inout_detail['sales_quantity'])){ echo $inout_detail['sales_quantity']; } ?></span></td>
				</tr>
				<?php } ?>
				<!--<tr>
					<td class="text-right" colspan="9">
						<span>
							<button type="button" onclick="export_pdf()" class="btn btn-primary pull-right"> <?php echo "Export as pdf"; ?></button>
						</span>
					</td>
				</tr>-->
            </tbody>
          </table>
		</div>
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
function filter()
{
	$('#filter_form').removeAttr('target');
	$('#export_bit').remove();
	$('#page_no').remove();
	$('#detail_bit').remove();
	$('#report_bit').remove();
	$('#product_id').remove();
	$('#filter_form').append('<input id="filter_bit" type="hidden" name="filter_bit" value="1" />');
	$('#filter_form').submit();
}
function export_pdf()
{
	$('#filter_bit').remove();
	$('#detail_bit').remove();
	$('#report_bit').remove();
	$('#product_id').remove();
	$('#filter_form').attr('target','_blank');
	$('#filter_form').append('<input id="export_bit" type="hidden" name="export_bit" value="1" />');
	$('#filter_form').append('<input id="page_no" type="hidden" name="page_no" value="<?php if(isset($page_no)){ echo $page_no; } ?>" />');
	$('#filter_form').submit();
}

function view_inout_details(report_bit,product_id)
{
	$('#filter_bit').remove();
	$('#export_bit').remove();
	$('#page_no').remove();
	$('#filter_form').attr('target','_blank');
	$('#filter_form').append('<input id="detail_bit" type="hidden" name="detail_bit" value="1" />');
	$('#filter_form').append('<input id="report_bit" type="hidden" name="report_bit" value="'+report_bit+'" />');
	$('#filter_form').append('<input id="product_id" type="hidden" name="product_id" value="'+product_id+'" />');
	$('#filter_form').submit();
}
function resetForm()
{
	$('[name=date_start]').val('');
	$('[name=date_end]').val('');
	$('[name=product]').prop('selectedIndex', 0);
}
//--></script></div>
<?php echo $footer; ?>