<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Runner's Cash ledger Report</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">Runner's Cash ledger Report</a></li>
        <?php } ?>
      </ul>
<i class="<?php echo $tool_tip_class; ?> " data-toggle="tooltip" style="<?php echo $tool_tip_style; ?>" title="<?php echo $tool_tip; ?>"></i>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i>Runner's Cash ledger Report</h3>
		<button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -10px;"><i class="fa fa-download"></i>Download</button>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            
              <div class="col-sm-6">
               <div class="form-group">
                <label class="control-label" for="input-status">User</label>
                <select name="filter_user_id"  id="input-users" style="width: 100%;" class="select2 form-control">
                  <option value="0">Select User</option>
                 
                  <?php foreach ($users as $user) { ?>
                  <?php if ($user['user_id'] == $filter_user_id) { ?>
                  <option value="<?php echo $user['user_id']; ?>" selected="selected"><?php echo $user['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $user['user_id']; ?>"><?php echo $user['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                   
                </select>
              </div>
              
			  
            </div>
			<div class="col-sm-6">
               <div class="form-group">
                <label class="control-label" for="input-status">Trans Type</label>
                <select name="filter_tr_type"  id="input-tr_type" style="width: 100%;" class="select2 form-control">
                  <option value="0">Select Type</option>
    
                  <option value="CR" <?php if ('CR' == $filter_tr_type) { ?> selected="selected" <?php } ?> >Accepted from store</option>
                  <option value="DB" <?php if ('DB' == $filter_tr_type) { ?> selected="selected" <?php } ?> >Accepted by Account</option>
				  <option value="EXPENSE" <?php if ('EXPENSE' == $filter_tr_type) { ?> selected="selected" <?php } ?> >EXPENSE</option>
                   
                </select>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
		<b>Accepted from store : </b><?php echo $TotalCR; ?> || <b>Accepted by Account : </b><?php echo $TotalDB; ?> || <b>EXPENSE : </b><?php echo $TotalEXPENSE; ?>
		<br/><br/>
          <table class="table table-bordered">
            <thead>
              <tr>
                
                <td class="text-left">Runner Name</td>
					<td class="text-left">Runner ID</td>
					<td class="text-left">Runner Username</td>
                <td class="text-right">Amount</td>
                <td class="text-right">Order Id</td>
                <td class="text-right">CR_DB</td>
               
				  <td class="text-right">Current Cash</td>
                <td class="text-right">Trans Date</td>
				 <td class="text-right">Remarks</td>
              </tr>
            </thead>
            <tbody>
              <?php if ($products) { ?>
              <?php foreach ($products as $product) { ?>
              <tr>
              
                <td class="text-left"><?php echo $product['user']; ?></td>
				  <td class="text-left"><?php echo $product['runner_id']; ?></td>
				  <td class="text-left"><?php echo $product['username']; ?></td>
                <td class="text-right"><?php echo $product['amount']; ?></td>
                <td class="text-right"><?php echo $product['order_id']; ?></td>
                 <td class="text-right"><?php 
					if($product['tr_type']=='CR') { echo '<font color="green">Accepted from store</font>'; }
					if($product['tr_type']=='DB') { echo '<font color="red">Accepted by Account</font>'; }
					if($product['tr_type']=='EXPENSE') { echo '<font color="navy">EXPENSE</font>'; }
					?></td>
                 
					<td class="text-right"><?php if(strtotime($product['create_time'])>(strtotime('2018-05-08 12:09:00'))) 
					{ 
						echo $product['updated_cash']; 
					}  
					else
					{
						echo 'NA';
					}
					?></td>
                    <td class="text-right"><?php echo $product['create_time']; ?></td>
					<td class="text-right" style="max-width: 200px;"><?php echo $product['remarks']; ?></td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
              </tr>
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
  <script type="text/javascript">
$("#input-users").select2();

<!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/runner_cash_leadger&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
		
	var filter_user_id = $('select[name=\'filter_user_id\']').val();
	
	if (filter_user_id != 0) {
		url += '&filter_user_id=' + encodeURIComponent(filter_user_id);
	}
	var filter_tr_type = $('select[name=\'filter_tr_type\']').val();
	
	if (filter_tr_type != 0) {
		url += '&filter_tr_type=' + encodeURIComponent(filter_tr_type);
	}
	location = url;
});
$('#button-download').on('click', function() {
	url = 'index.php?route=report/runner_cash_leadger/download_excel&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_user_id = $('select[name=\'filter_user_id\']').val();
	
	if (filter_user_id != 0) {
		url += '&filter_user_id=' + encodeURIComponent(filter_user_id);
	}
	var filter_tr_type = $('select[name=\'filter_tr_type\']').val();
	
	if (filter_tr_type != 0) {
		url += '&filter_tr_type=' + encodeURIComponent(filter_tr_type);
	}
	location = url;
}); 

function get_store_users(store_id)
{
	if(store_id)
	{
		$.ajax({
			url: 'index.php?route=report/users_cash_leadger/get_store_users&token=<?php echo $token; ?>&store_id='+encodeURIComponent(store_id),
			beforeSend: function() 
                { 
				
                },
				complete: function() 
                {
                   	
				},
				success: function(html) 
                {	
					//alert(html);
					$("#input-users").html(html);
				},
				error: function(xhr, ajaxOptions, thrownError) 
                {
                    alertify.error(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    
				}
			
		});
	}
}

//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script> 

</div>
<?php echo $footer; ?>