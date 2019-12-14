<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
      </div>
      <h1><?php echo "Product Return List"; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if (!empty($error_warning)) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if (!empty($success)) {		?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo "Product Return List"; ?></h3>
      </div>
      <div class="panel-body">
	  <form action="<?php echo $filter;?>" method="post" enctype="multipart/form-data" id="form-filter">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo "Return ID:"; ?></label>
                <input type="text" name="return_id" value="<?php if(isset($filter_id)){ echo $filter_id; }?>" placeholder="Return id" id="input-id" class="form-control" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57 || event.keyCode==8 || event.keyCode==46"/>
			  </div>
			</div>
			  <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-name">Product Name</label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                <input type="hidden" name="filter_name_id"  value="<?php echo $filter_name_id; ?>" id="filter_name_id"/>
              </div>
            </div>
		  </div>
		  <div class="row">
			<div class="col-sm-6">
                <div class="form-group">
                <label class="control-label" for="input-date-start">From</label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
			</div>
			<div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end">To</label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
		  </div>
		  <div class="row">
                      <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="status"><?php echo "Ware House"; ?></label>
                <div class="input-group">
                  <span class="input-group-btn">
                      <select class="form-control" name="warehouse" id="warehouse">
						<option value="">SELECT</option>
                                                <?php foreach($warehouses as $warehouse) { ?>
						<option value = "<?php echo $warehouse['store_id']; ?>" <?php if($warehouse['store_id']==$filter_warehouse){ ?>selected<?php } ?>><?php echo $warehouse['name']; ?></option>
                                                <?php } ?>
					</select>
                  </span></div>
              </div>
            </div>
				<div class="col-sm-6">
					<button class="btn btn-primary pull-right" id="clear-filter" onclick="reset_form();" type="button"> Clear</button>
					<button class="btn btn-primary pull-right" id="button-filter" style="margin-right:10px;" type="button"><i class="fa fa-search"></i> Filter</button>
				</div>
		  </div>
        </div>
		</form>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-order">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  
                  <td class="text-left">Return ID</td>
				  <td class="text-left">Date</td>
				  <td class="text-left">Return By</td>
                                  <td class="text-left">Store Name</td>
				  <td class="text-left">Ware House</td>
                                  <td class="text-left">Product</td>
				  
                                  <td class="text-left">Return Quantity</td>
				 <td class="text-left">Reason</td>
                                 <td class="text-left">Credit Note</td>
                </tr>
              </thead>
              <tbody>
                <?php if($orders){
					foreach($orders as $order)
					{ //print_r($order);
				?>
						<tr>
							
							<td class="text-left"><?php echo $order['id']; ?></td>
							<td class="text-left"><?php echo $order['return_date']; ?></td>
							<td class="text-left"><?php echo $order['name'];?></td>
                                                        <td class="text-left"><?php echo $order['store_name']; ?></td>
							<td class="text-left"><?php echo $order['supplier']; ?></td>
                                                        <td class="text-left"><?php echo $order['product_name']; ?></td>							
                                                        <td class="text-left"><?php echo $order['return_quantity']; ?></td>
							<td class="text-left"><?php echo $order['reason']; ?></td>
                                                        <td class="text-left"><?php //echo $order['status'];
                                                        if($order['status']=="0")
                                                        {
                                                            //echo "Create Note";
                                                        ?>
                                                            <a href="#" onclick="return open_model(<?php echo $order['id']; ?>);"  data-toggle="tooltip" title="<?php echo "Create Credit Note"; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
                                                        <?php
                                                        }
                                                        else
                                                        {
                                                            //echo "Download";
                                                        ?>
                                                            <a href="<?php echo $download_note . '&order_id='.$order['id']; ?>"  data-toggle="tooltip" title="<?php echo "Download Create Note"; ?>" class="btn btn-primary"><i class="fa fa-download"></i></a>
                                                        <?php
                                                        }
                                                        ?></td>
                                                </tr>
				<?php
					}
				}?>
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
  </div>
<!-- Modal -->
  <div class="modal fade" id="myModal_create_bill" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" id="partner_cncl_btn2" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Create Note</h4>
        </div>
        <div class="modal-body">
        <form action="index.php?route=purchase/return_orders/create_note&token=<?php echo $token; ?>" method="post" enctype="multipart/form-data" onsubmit="return myFunction()" >
            <input type="hidden" name="order_id" id="order_id" value="" />
            <input type="hidden" name="filter_name" id="filter_name" value="<?php echo $filter_name; ?>" />
            <input type="hidden" name="filter_name_id" id="filter_name_id" value="<?php echo $filter_name_id; ?>" />
            <input type="hidden" name="filter_date_start" id="filter_date_start" value="<?php echo $filter_date_start; ?>" />
            <input type="hidden" name="filter_date_end" id="filter_date_end" value="<?php echo $filter_date_end; ?>" />
            <input type="hidden" name="filter_id" id="filter_id" value="<?php echo $filter_id; ?>" />
            
            <div class="form-group">
            <label for="input-username">Ware House</label>
            <div class="input-group"><span class="input-group-addon"><i class="fa fa-building-o" aria-hidden="true"></i></span>
                
            <select name="filter_ware_house" id="input-store" class="form-control" required >
	    <option value="">SELECT WARE HOUSE</option>
                  <?php foreach ($ware_houses as $store) { ?>
                  <?php if (($store['store_id'] == $filter_store) & $filter_store="0") { ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
            </select>

            </div>
            </div>
            
            <div class="text-right">
                <input type="submit" id="partner_sbmt_btn"  class="btn btn-primary" value="Submit" />
                <button type="button" id="partner_cncl_btn" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </form>
        </div>
        <!--<div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>-->
      </div>
      
    </div>
  </div>

  <script type="text/javascript">
function open_model(order_id)
{
$('#myModal_create_bill').modal('show');
$('#order_id').val(order_id);
$('select[name=\'filter_ware_house\']').val('');

return false;
}
$('#button-filter').on('click', function() {
	url = 'index.php?route=purchase/return_orders&token=<?php echo $token; ?>';
	
        var filter_id = $('#input-id').val();
	
	if (filter_id) {
		url += '&filter_id=' + encodeURIComponent(filter_id);
	}
		
	var filter_warehouse = $('#warehouse').val();
	
	if (filter_warehouse) {
		url += '&filter_warehouse=' + encodeURIComponent(filter_warehouse);
	}
        var filter_date_start = $('#input-date-start').val();
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
        var filter_date_end = $('#input-date-end').val();
        if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
        var filter_name_id = $('input[name=\'filter_name_id\']').val();
        var filter_name = $('#input-name').val();

        if (filter_name_id) 
        {
                if(filter_name!="")
                {
                    url += '&filter_name_id=' + encodeURIComponent(filter_name_id);
                    url += '&filter_name=' + encodeURIComponent(filter_name);
                }
        }
        //alert(url);
	location = url;
});
</script> 
  <script type="text/javascript">
	$('.date').datetimepicker({
		pickTime: false
	});
	
	function reset_form()
	{
		$('[name=from]').val('');
		$('[name=to]').val('');
		$('[name=filter_id]').val('');
		$('[name=status]').prop('selectedIndex', 0);
	}
  </script>
  <script type="text/javascript">
$('input[name=\'filter_name\']').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['product_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'filter_name\']').val(item['label']);
                $('input[name=\'filter_name_id\']').val(item['value']);
    }
});
</script>
<?php echo $footer; ?> 
