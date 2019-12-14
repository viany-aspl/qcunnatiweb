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
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
			  <tr>
                <td class="text-left"><?php echo $column_order_id; ?></td>
                <td class="text-left"><?php echo $column_order_date; ?></td>
				<td class="text-left"><?php echo "Supplier"; ?></td>
                <td class="text-left"><?php echo $column_total_products; ?></td>
              </tr>
            </thead>
            <tbody>
				<?php for($i = 0; $i<count($pending_orders); $i++){ ?>
				<tr>
					<td><?php echo $pending_orders[$i]['id']?></td>
					<td><?php echo $pending_orders[$i]['order_date']?></td>
					<td><?php if($pending_orders[$i]['pre_supplier_bit'] == 1){ echo $pending_orders[$i]['first_name'] . $pending_orders[$i]['last_name']; } else echo "Multiple"; ?></td>
					<td><?php echo $pending_orders[$i]['total_quantity']?></td>
				</tr>
				<?php } ?>
				<?php if(isset($pending_orders)){?>
				<!--<tr>
				<td class="text-right" colspan="5">
					<a id="download_pdf" href="<?php echo $pdf_export . '&export=1' . '&page_no=' . $page_no; ?>" target="_blank"><span class="input-group-btn">
						<button type="button" class="btn btn-primary pull-right"> <?php echo "Export as pdf"; ?></button>
					</span></a>
				</td>
			  </tr>-->
			<?php } ?>
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
 </div>
<?php echo $footer; ?>