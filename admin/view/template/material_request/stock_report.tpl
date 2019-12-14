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
                <td class="text-left"><?php echo $column_product_name;?></td>
                <td class="text-left"><?php echo $column_quantity;?></td>
              </tr>
            </thead>
            <tbody>
				<?php foreach($stock_details as $stock_detail){?>
					<tr>
						<td><?php echo $stock_detail['name'];?></td>
						<td><?php echo $stock_detail['quantity'];?></td>
					</tr>
				<?php } ?>
				<!--<tr>
				<td class="text-right" colspan="5">
					<a id="download_pdf" href="<?php echo $export . "&export_bit=1&page_no=" . $page_no;?>" target="_blank"><span class="input-group-btn">
						<button type="button" class="btn btn-primary pull-right"> <?php echo "Export as pdf"; ?></button>
					</span></a>
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
 </div>
<?php echo $footer; ?>