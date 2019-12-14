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
.table.table-bordered.table-hover tr td:first-child {
    display: none !important;
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
.owner-date{
	width: 100%;
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
	width:25%;
	font-weight:bold;
	font-size:12px;
}
.type span{
	float:right;
	font-size:12px;
	font-weight:normal;
}
/*mail type*/
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
	font-size:12px;
	text-align:center;
	font-family:Verdana, Geneva, sans-serif;
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
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-return').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      <!--<h1><?php echo $heading_title; ?></h1>-->
      <!--<ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>-->
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
      <!--<div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>-->
	  
	  <div class="company_info">
      	<!--<p>Company Name: <span><?php echo $company_name?></span></p>
        <p>Company Address: <span><?php echo $company_address?></span></p>-->
		<p>Company Owner: <span><?php echo $company_owner?></span></p>
		<div class="mail_type">
			<div class="mail"><p>Company Email: <span><?php echo $company_email?></span></p></div>
			<div class="type">Report Type:<span><?php echo $heading_title ;?></span></div>
		</div>
		<p>Date: <span><?php echo date('Y-m-d');?></span></p>
		<!--<div class="owner-date">
            <div class="owner"><p>Company Owner: <span><?php echo $company_owner?></span></p></div>
            <div class="date">Date: <span><?php echo date('Y-m-d');?></span></div>
		</div>-->
		</div><br />
	  
      <div class="panel-body">
        
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-return">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td><?php echo $column_return_id?></td>
				  <td><?php echo $column_order_id?></td>
				  <td><?php echo $column_product?></td>
				  <td><?php echo $column_quantity; ?></td>
				  <td><?php echo $column_supplier?></td>
				  <td><?php echo $column_date?></td>
				  <td><?php echo $column_added_by?></td>
				</tr>
              </thead>
              <tbody>
				<?php foreach($return_orders as $return_order)
				{
				?>
				<tr>
					<td><?php echo $return_order['id'];?></td>
					<td><?php echo $return_order['order_id'];?></td>
					<td><?php echo $return_order['name'];?></td>
					<td><?php echo $return_order['return_quantity']; ?></td>
					<td><?php echo $return_order['first_name'] . " " . $return_order['last_name'];?></td>
					<td><?php echo $return_order['return_date'];?></td>
					<td><?php echo $return_order['firstname'] . " " . $return_order['lastname'];?></td>
				</tr>
				<?php
				}
				?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php //echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php //echo $results; ?></div>
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
	$('#export-form').append("<input type='hidden' name='export_bit' value='1' />");
	$('#export-form').submit();
}
//--></script></div>
<?php //echo $footer; ?> 