<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
     
      <h1><?php echo "Supplier OutStanding"; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
       <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo "Supplier OutStanding List"; ?></h3>
	   <!-- <button class="btn btn-primary pull-right" id="button-download"  style="margin-right:10px;margin-top: -9px;" type="button"><i class="fa fa-download"></i> Download Excel</button>
       -->
      </div>
      <div class="panel-body" style="padding: 0px;">
        <div class="well">
        <div class="row">
	
        <div class="col-sm-6">
            <div class="form-group">
            <label class="control-label" for="input-date-end"><?php echo "Supplier"; ?></label>
            <select  style="width: 100%;"  name="filter_supplier" id="input-supplier" required="required" class="select2 form-control">
                              <option value="" >Select Supplier</option>
                  <?php foreach ($suppliers as $supplier) { ?>
                  <?php if ($supplier['id'] == $filter_supplier) { ?>
                  <option value="<?php echo $supplier['id']; ?>" selected="selected"><?php echo $supplier['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $supplier['id']; ?>"><?php echo $supplier['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
            </select>
            </div>
        </div>
			<br/><br/>
                    <button class="btn btn-primary pull-right" id="button-filter"  style="margin-right:10px;" type="button"><i class="fa fa-search"></i> Filter</button>
        </div>
        
		  
        </div>
		
          <div class="table-responsive">
		  <span style="margin-right: 10px;"><b>Total Outanding : </b> <?php echo number_format($total_outstanding, 2, '.', ''); ?> </span>|| 
		  <span style="margin-right: 10px;margin-left: 10px;"><b>Wallet Balance : </b> <?php echo number_format($wallet_balance, 2, '.', ''); ?> </span>|| 
		  <span style="margin-left: 10px;"><b>Total Actual Outanding: </b> <?php echo number_format(($total_outstanding-$wallet_balance), 2, '.', ''); ?></span>
		  
		  </br></br>
	<table class="table table-bordered table-hover">
              <thead>
                <tr>
					<td class="text-left">S.N.</td>
					<td class="text-left">Supplier ID</td>
					<td class="text-left">Supplier Name</td>
					
					<td class="text-left">OutStanding</td>
					<td class="text-left">Wallet Balance</td>
					<td class="text-left">Actual Outstanding</td>
                </tr>
              </thead>
              <tbody>
                <?php 
                if($order_list)
                {
					$a=1;
                    foreach($order_list as $order)
                    {
		?>
                    <tr>
					<td class="text-left"><?php echo $a; ?></td>
						<td class="text-left"><?php echo $order['id']; ?></td>
						<td class="text-left"><?php echo $order['name']; ?></td>
						
						<td class="text-left"><?php echo number_format($order['outstanding'], 2, '.', ''); ?></td>
                     <td class="text-left"><?php echo number_format($order['wallet_balance'], 2, '.', ''); ?></td>
					 <td class="text-left"><?php echo number_format(($order['outstanding']-$order['wallet_balance']), 2, '.', ''); ?></td>
                    </tr>
		<?php
		$a++;
                    }
		}
		else
		{
		?>
		<tr><td colspan="9" style="text-align: center;"><?php echo $noresult; ?></td></tr>
		<?php
		}
                ?>
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


  <script type="text/javascript">
$("#input-supplier").select2();

$('#button-filter').on('click', function() {
	url = 'index.php?route=purchaseorder/purchase_order/supplier_outstanding&token=<?php echo $token; ?>';
	
    var filter_supplier = $('#input-supplier').val();
	if (filter_supplier) 
	{
		url += '&filter_supplier=' + encodeURIComponent(filter_supplier);
	}
	
	location = url;
});
$('#button-download').on('click', function() {
	url = 'index.php?route=purchaseorder/purchase_order/supplier_outstanding_download_excel&token=<?php echo $token; ?>';
	
    var filter_supplier = $('#input-supplier').val();
	if (filter_supplier) 
	{
		url += '&filter_supplier=' + encodeURIComponent(filter_supplier);
	}
	
	window.open(url,'_blank');
});
</script> 
  <script type="text/javascript">
	$('.date').datetimepicker({
		pickTime: false
	});
  </script>
<?php echo $footer; ?> 