<?php //echo $header; ?><?php //echo $column_left; ?>

<style type="text/css">
thead tr td{
		font-weight:bold;
	}
	.table-responsive thead td {
    background: #eeeeee none repeat scroll 0 0;
    padding: 5px !important;
    text-align: center;
	border-bottom:2px solid #cccccc;
}
.table-responsive tbody td {
    padding: 5px !important;
	line-height: 1.7;
}
.panel-heading {
    background: #eeeeee none repeat scroll 0 0;
}
.page-header{
	text-align:center;
}
.table.table-bordered tbody tr:nth-child(2n) {
    background-color: #eeeeee !important;
}


.header{
	width: 100%;
}
.logo{
	width: 25%;
	float:left;
	margin-top:20px !important;
}
.company{
	width: 72%;
	float:right;
	margin-top:20px !important;
}
.logo img, .company_info p{
	width:100%;
}
.company h2{
	float:right;
}
.panel-heading{
	display:none;
}
.table-bordered, .table-bordered td {
    border: 1px solid #dddddd;
	border-collapse:collapse;
}
.company_info p{
	width: 100%;
	font-weight: bold;
	margin:0px;
}
.company_info p span{
	font-size:12px;
}
.date span{
	float:right;
}

/*mail type*/
.mail_type{
	width:100%;
}
.mail{
	float:left;
	width:50%;
}
.type{
	float:right;
	width:30%;
	font-weight:bold;
	font-size:12px;
}
.type span{
	float:right;
	font-size:12px;
	font-weight:normal;
}
/*mail type*/

.owner-date{
	width: 100%;
}
.owner{
	float:left;
	width: 50%;
}
.date{
	float:right;
	width: 17%;
	font-weight: bold;
	font-size:12px;
}
.date span{
	float:right;
	font-size:12px;
	font-weight:normal;
}
table{
	margin:10px auto;
	width:100%;
	font-family:Verdana, Geneva, sans-serif;
	font-size:12px;
	text-align:center;
}
td{
	width:auto;
}
p{
	font-size:12px;
}
p span{
	font-weight:normal;
}
#content{
	font-family:Verdana, Geneva, sans-serif;
}
.footer{
	width:100%;
}
.address
{
	width:70%;
	float:left;
}
.pageno{
	width:4%;
	float:right;
}
</style>
 
<div id="content">
  <!--<div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $best_heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>-->
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $best_heading_title; ?></h3>
      </div>
	  
	  <div class="company_info">
      	<!--<p>Company Name: <span><?php echo $company_name?></span></p>
        <p>Company Address: <span><?php echo $company_address?></span></p>-->
        <p>Company Owner: <span><?php echo $company_owner?></span></p>
		<div class="mail_type">
			<div class="mail"><p>Company Email: <span><?php echo $company_email?></span></p></div>
			<div class="type">Report Type:<span><?php echo $best_heading_title ;?></span></div>
		</div>
		<p>Date: <span><?php echo date('Y-m-d');?></span></p>
        <!--<div class="owner-date">
            <div class="owner"><p>Company Owner: <span><?php echo $company_owner?></span></p></div>
            <div class="date">Date: <span><?php echo date('Y-m-d');?></span></div>
        </div>-->
      </div><br />
	  
      <div class="panel-body">
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
			<?php
				foreach($best_products as $best_product)
				{
					if(isset($best_product['sales_quantity']))
					{
						if(isset($best_limit))
						{
							if($best_product['sales_quantity'] >= $best_limit)
							{
				?>
					<tr>
						<td><?php echo $best_product['name']; ?></td>
						<td><?php echo $best_product['quantity']; ?></td>
						<td><?php echo $best_product['sales_quantity']; ?></td>
					</tr>
				<?php
							}
						}
						/*elseif($best_product['sales_quantity'] >= 0)
						{
				?>
							<tr>
								<td><?php echo $best_product['name']; ?></td>
								<td><?php echo $best_product['quantity']; ?></td>
								<td><?php echo $best_product['sales_quantity']; ?></td>
							</tr>
				<?php
						}*/
					}
			?>
			<?php }	?>
				
            </tbody>
          </table>
		</div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php //echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php //echo $results; ?></div>
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
	$('[name=best_limit]').val('');
}

function filter()
{
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
<?php //echo $footer; ?> 
