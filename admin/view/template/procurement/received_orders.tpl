<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
	  <form id="filter_form" action = "<?php echo $filter; ?>" method="post" enctype="multipart/form-data">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="input-group date">
                  <input onkeypress="return false" type="text" name="date_start" value="<?php if(isset($start_date)) { echo $start_date; }?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
			</div>
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input onkeypress="return false;" type="text" name="date_end" value="<?php if(isset($end_date)) { echo $end_date; }?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_supplier; ?></label>
                <div class="input-group">
                  <span class="input-group-btn">
					<select class="form-control" name="supplier">
						<option>--supplier--</option>
						<?php if(isset($filter_supplier)) $name=explode(' ', $filter_supplier);?>
						<?php foreach($suppliers as $supplier){
						?>
						<option <?php if(isset($filter_supplier)){if(($supplier['first_name'] == $name[0]) && ($supplier['last_name'] == $name[1])){ ?>selected="selected"<?php }} ?>><?php echo $supplier['first_name'] . " " . $supplier['last_name']; ?></option>
						<?php
						}?>
					</select>
                  </span></div>
              </div>
            </div>
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_product; ?></label>
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
			<div class="col-sm-6">
				<div class="form-group">
					<label for="input-return-id" class="control-label"><?php echo $entry_order_id; ?></label>
					<input type="text" class="form-control" id="input-return-id" placeholder="<?php echo $entry_order_id; ?>" value="<?php if(isset($order_id)){ echo $order_id; }?>" name="order_id">
				</div>
			</div>
		  </div>
		  <div class="row">
				<div class="col-sm-12">
					<button type="button" onclick="reset_form();" id="clear-filter" class="btn btn-primary pull-right"> <?php echo $button_clear; ?></button>
					<button style="margin-right:10px;" type="button" onclick="filter()" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
				</div>
		  </div>
        </div>
		</form>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
			  <tr>
                <td class="text-left"><?php echo $column_order_id; ?></td>
                <td class="text-left"><?php echo $column_date_start; ?></td>
                <td class="text-left"><?php echo $column_date_end; ?></td>
				<td class="text-right"><?php echo $column_product; ?></td>
                <td class="text-right"><?php echo $column_supplier; ?></td>
				<td class="text-right"><?php echo $column_quantity; ?></td>
				<td class="text-right"><?php echo $column_price; ?></td>
				<td class="text-right"><?php echo $column_total_products; ?></td>
                <td class="text-right"><?php echo $column_total; ?></td>
              </tr>
            </thead>
            <tbody>
				<?php $grand_total = 0; ?>
				<?php for($i = 0; $i<count($received_orders); $i++){ ?>
				<tr>
					<td><?php echo $received_orders[$i]['order_id']; ?></td>
					<td><?php echo $received_orders[$i]['order_date']; ?></td>
					<td><?php echo $received_orders[$i]['receive_date']; ?></td>
					<td><?php 
						foreach($received_orders[$i]['products'] as $product)
						{
							echo $product . "<br />";
						}
					?></td>
					<td><?php 
						foreach($received_orders[$i]['suppliers'] as $supplier)
						{
							echo $supplier . "<br />";
						}
					?></td>
					<td><?php 
						foreach($received_orders[$i]['rcvd_qnty'] as $qnty)
						{
							echo $qnty . "<br />";
						}
					?></td>
					<td><?php 
						foreach($received_orders[$i]['prices'] as $price)
						{
							echo $price . "<br />";
						}
					?></td>
					<td><?php echo $received_orders[$i]['total_products']; ?></td>
					<td><?php echo $received_orders[$i]['total_price']; ?></td>
				</tr>
				<?php $grand_total += $received_orders[$i]['total_price']; ?>
				<?php } ?>
			  <tr>
                <td class="text-right" colspan ="8"><b><?php echo $grand_total_text; ?></b></td>
				<td class="text-left" ><?php echo $grand_total; ?></td>
              </tr>
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
	$('#filter_form').append('<input id="filter_bit" type="hidden" name="filter_bit" value="1" />');
	$('#filter_form').submit();
}
function export_pdf()
{
	$('#filter_bit').remove();
	$('#filter_form').attr('target','_blank');
	$('#filter_form').append('<input id="export_bit" type="hidden" name="export_bit" value="1" />');
	$('#filter_form').append('<input id="page_no" type="hidden" name="page_no" value="<?php if(isset($page_no)){ echo $page_no; } ?>" />');
	$('#filter_form').submit();
}
function reset_form()
{
	$('[name=date_start]').val('');
	$('[name=date_end]').val('');
	$('[name=supplier]').prop('selectedIndex', 0);
	$('[name=product]').prop('selectedIndex', 0);
	$('[name=order_id]').val('');
	
}
//--></script></div>
<?php echo $footer; ?>