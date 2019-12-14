<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
      
     </div>
      <h1>Bill submision list</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
<i class="<?php echo $tool_tip_class; ?> " data-toggle="tooltip" style="<?php echo $tool_tip_style; ?>" title="<?php echo $tool_tip; ?>"></i>
 
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> List</h3>
        <button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download</button>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-date-start">Start date </label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="Date start" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-added">End date </label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="Date end" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-order-status">Select store</label>
                <select required name="filter_store"  id="input-store" style="width: 100%;" class="select2 form-control" onchange="return get_stores_data(this.value);">
                      <option selected="selected" value="">SELECT STORE</option>
                  <?php foreach ($stores as $store) {   ?>
                  <?php if ($store['store_id'] == $filter_store) {
                      if($filter_store!=""){
                      ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                      <?php }} else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
                
                <div class="form-group">
                <label class="control-label" for="input-order-status">Select Unit</label>
               
                <select  name="filter_unit"  id="filter_unit" class="form-control"  >
                      <option selected="selected" value="">SELECT UNIT</option>
                      <option  value="Ajbapur" <?php if($filter_unit=="Ajbapur") { echo "selected";} ?>>Ajbapur</option>
                      <option  value="Loni" <?php if($filter_unit=="Loni") { echo "selected";} ?>>Loni</option>
                      <option  value="Rupapur" <?php if($filter_unit=="Rupapur") { echo "selected";} ?>>Rupapur</option>
                      <option  value="Hariyawan" <?php if($filter_unit=="Hariyawan") { echo "selected";} ?>>Hariyawan</option>
                    </select>
              </div>
              
            </div>
            <div class="col-sm-4">
              
              <div class="form-group">
                <label class="control-label" for="input-date-modified">Submission date</label>
                <div class="input-group date">
                  <input type="text" name="filter_date_submission" value="<?php echo $filter_date_submission; ?>" placeholder="Submission date" data-date-format="YYYY-MM-DD" id="input-date-submission" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left">Submission date</td>
                  <td class="text-left">Unit</td>
                  <td class="text-left">Store</td>
	 <td class="text-left">Submitted by</td>
                  <td class="text-left">Date start</td>
                  <td class="text-left">Date end</td>
                  <td class="text-left">Amount</td>
                  <td class="text-left">Attched File</td>
                  <td class="text-left">Remarks</td>
                  <td class="text-left">Payment status</td>
                
                </tr>
              </thead>
              <tbody>
                <?php if ($bills) { ?>
                <?php foreach ($bills as $bill) { //print_r($bill); ?> 
                <tr>
                  <td class="text-left"><?php echo $bill['submission_date']; ?></td>
                  <td class="text-left"><?php echo $bill['unit']; ?></td>
                  <td class="text-left"><?php echo $bill['store_name']; ?></td>
	  <td class="text-left"><?php echo $bill['submitted_by']; ?></td>
                  <td class="text-left"><?php echo $bill['date_start']; ?></td>
                  <td class="text-left"><?php echo $bill['date_end']; ?></td>
                  <td class="text-left"><?php echo $bill['amount']; ?></td>
                  <td class="text-left"><?php if($bill['uploded_file']!=""){ ?><a href="../system/upload/bill/<?php echo $bill['uploded_file']; ?>" download>View</a><?php } ?></td>
                  <td class="text-left"><div style="max-width: 200px;"><?php echo $bill['remarks']; ?></div></td>
                  <td class="text-left"><?php if($bill['payment_status']=="0"){echo "Pending";} else if($bill['payment_status']=="1") { echo "Accepted"; } else if($bill['payment_status']=="2") { echo "Rejected"; } ?></td>
                  
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
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
    
    <!-- Modal -->
  <div class="modal fade" id="myModal_partner" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" id="partner_cncl_btn2" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Tell us the reason, why you reject this bill ? </h4>
        </div>
        <div class="modal-body">
        <form action="index.php?route=tag/bills/reject&token=<?php echo $token; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="bill_id" id="bill_id" />
            <input type="hidden" name="submission_date" id="submission_date" />
            <input type="hidden" name="unit" id="unit" />
            <input type="hidden" name="store_name" id="store_name" />
            <input type="hidden" name="date_start" id="date_start" />
            <input type="hidden" name="date_end" id="date_end" />
            <input type="hidden" name="amount" id="amount" />
            <input type="hidden" name="logged_user" id="logged_user" />
            
            <div class="form-group">
            <label for="input-username">Reason </label>
            <div class="input-group"><span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <textarea name="reject_Message" required id="reject_Message" class="form-control" placeholder="Reason of reject"></textarea>
            </div>
            </div>
           <div class="text-right">
               <img id="processing_image" src="https://unnati.world/shop/admin/view/image/processing_image.gif" style="height: 50px;width: 50px;display: none;">
               <input type="submit" id="submit_btn"  class="btn btn-primary" onclick="return show_prgressImg();" value="Submit" />
                <button type="button" id="submt_cncl_btn" class="btn btn-default" data-dismiss="modal">Cancel</button>
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
$("#input-store").select2();
<!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=tag/billsubmission/getlist&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	
	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != '*') {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	

	var filter_date_submission = $('input[name=\'filter_date_submission\']').val();

	if (filter_date_submission) {
		url += '&filter_date_submission=' + encodeURIComponent(filter_date_submission);
	}	
	var filter_unit = $('#filter_unit').val();

	if (filter_unit) {
		url += '&filter_unit=' + encodeURIComponent(filter_unit);
	}
	
	//alert(url);			
	location = url;
});
//--></script> 
<script type="text/javascript"><!--
$('#button-download').on('click', function() {
	url = 'index.php?route=tag/billsubmission/getlist_download&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	
	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != '*') {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	

	var filter_date_submission = $('input[name=\'filter_date_submission\']').val();

	if (filter_date_submission) {
		url += '&filter_date_submission=' + encodeURIComponent(filter_date_submission);
	}	
	var filter_unit = $('#filter_unit').val();

	if (filter_unit) {
		url += '&filter_unit=' + encodeURIComponent(filter_unit);
	}
	
	//alert(url);			
	//location = url;
         window.open(url, '_blank');
});
//--></script>
  <script type="text/javascript"><!--
$('input[name=\'filter_customer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['customer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_customer\']').val(item['label']);
	}	
});
//--></script> 
  <script type="text/javascript"><!--
$('input[name^=\'selected\']').on('change', function() {
	$('#button-shipping, #button-invoice').prop('disabled', true);
	
	var selected = $('input[name^=\'selected\']:checked');
	
	if (selected.length) {
		$('#button-invoice').prop('disabled', false);
	}
	
	for (i = 0; i < selected.length; i++) {
		if ($(selected[i]).parent().find('input[name^=\'shipping_code\']').val()) {
			$('#button-shipping').prop('disabled', false);
			
			break;
		}
	}
});

$('input[name^=\'selected\']:first').trigger('change');

$('a[id^=\'button-delete\']').on('click', function(e) {
	e.preventDefault();
	
	if (confirm('<?php echo $text_confirm; ?>')) {
		location = $(this).attr('href');
	}
});
//--></script> 
  <script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>