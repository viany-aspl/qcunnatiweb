<?php echo $header; ?><?php echo $column_left; ?>


 
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $dead_heading_title; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $dead_heading_title; ?></h3>
      </div>
      <div class="panel-body">
	  <form action = "<?php echo $filter; ?>" method="post" enctype="multipart/form-data" id= "filter_form">
        <div class="well">
          <div class="row">
           <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $entry_date_start; ?></label>
                <div class="input-group date">
                  <input type="text" onkeypress="return false;" value="<?php if(isset($date_start)){ echo $date_start; }?>" name="date_start" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
					<span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
				</div>
              </div>
            </div>
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" onkeypress="return false;" value="<?php if(isset($date_end)){ echo $date_end; }?>" name="date_end" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
					<span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
				</div>
              </div>
            </div>
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-dead-limit"><?php echo $entry_dead_limit; ?></label>
                <input type="text" value="<?php if(isset($dead_limit)){ echo $dead_limit; }?>" name="dead_limit" placeholder="<?php echo $entry_dead_limit;?>" id="input-dead-limit" class="form-control" />
			  </div>
            </div>
			<div class="col-sm-12">
              <button type="submit" id="button-filter" onclick="filter()" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo "Filter"; ?></button>
              <button style="margin-right:10px;" type="button" onclick="resetForm()" id="clear-filter" class="btn btn-primary pull-right"> <?php echo $button_clear; ?></button></div>
			</div>
          </div>
        </div>
		</form>
		
		 <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
			  <tr>
                <td class="text-left"><?php echo $column_product_name;?></td>
                <td class="text-left"><?php echo $column_stock_quantity;?></td>
                <td class="text-left"><?php echo $column_sale_quantity;?></td>
              </tr>
            </thead>
            <tbody>
				<?php foreach($dead_details as $dead_detail){?>
					<?php if(isset($dead_detail['sales_quantity']))
						{
							if(isset($dead_limit))
							{
								if($dead_detail['sales_quantity'] <= $dead_limit)
								{
									
								
							
					?>
				<tr>
					<td><?php echo $dead_detail['name']; ?></td>
					<td><?php echo $dead_detail['quantity']; ?></td>
					<td><?php echo $dead_detail['sales_quantity']; ?></td>
				</tr>
				<?php
							}
						}
					}
				}
				?>
				<?php if(isset($dead_limit)) {?>
				<tr>
				
				<td class="text-right" colspan="5">
					<button type="button" onclick="export_pdf()" class="btn btn-primary pull-right"><!--<i class="fa fa-search"></i>--> <?php echo "Export as pdf"; ?></button>
				</td>
				
			  </tr>
				<?php } ?>
            </tbody>
          </table>
		</div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php if(isset($dead_limit)) echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php if(isset($dead_limit)) echo $results; ?></div>
        </div>
      </div>
		
		
		</div>
    </div>
	</div>

  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

function resetForm()
{
	$('[name=date_start]').val('');
	$('[name=date_end]').val('');
	$('[name=dead_limit]').val('');
}

function filter()
{
	if(!$('#input-dead-limit').val())
	{
		alert('Dead limit is required field');
		return false;
	}
	$('#export_bit').remove();
	$('#page_no').remove();
	$('#filter_form').removeAttr('target');
	$('#filter_form').append('<input type="hidden" name="filter_bit" value="1" id="filter_bit">');
	$('#filter_form').submit();
}
function export_pdf()
{
	$('#filter_bit').remove();
	$('#filter_form').attr('target','_blank');
	$('#filter_form').append('<input type="hidden" name="export_bit" value="1" id="export_bit">');
	$('#filter_form').append('<input type="hidden" name="page_no" value="<?php if(isset($page_no)) echo $page_no?>" id="page_no">');
	$('#filter_form').submit();
}
//--></script></div>
<?php echo $footer; ?> 
