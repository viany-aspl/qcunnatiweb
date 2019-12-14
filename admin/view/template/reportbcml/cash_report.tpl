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
        <button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download</button>
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
                <label class="control-label" for="input-date-end">Select Store</label>
                
                      
                  <select name="filter_store" style="width: 100%" id="input-store" class="select2 form-control">
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
                <label class="control-label" >Select CE</label>
                <div class="input-group">
                 
                  <span class="input-group-btn">
                      
                  <select name="filter_user" id="filter_user" class="form-control">
                   <option selected="selected" value="">SELECT CE</option>
	<?php foreach($allces as $ce){ ?>
		 <option value="<?php echo $ce['user_id']; ?>" <?php if($filter_user==$ce['user_id']){ ?> selected="selected" <?php } ?> ><?php echo $ce['firstname']." ".$ce['lastname']; ?></option>
	<?php } ?>
	<!--
                  <option value="110" <?php if($filter_user=="110"){ ?> selected="selected" <?php } ?> >Amit Kumar</option>
                  <option value="111" <?php if($filter_user=="111"){ ?> selected="selected" <?php } ?> >Anil Kumar</option>
                  <option value="106" <?php if($filter_user=="106"){ ?> selected="selected" <?php } ?> >Chitranjan Mishra</option>   
                  <option value="77" <?php if($filter_user=="77"){ ?> selected="selected" <?php } ?> >Kunwar Rana</option>
                  <option value="9" <?php if($filter_user=="9"){ ?> selected="selected" <?php } ?> >Om prakash</option>
                  <option value="108" <?php if($filter_user=="108"){ ?> selected="selected" <?php } ?> >Surjeet Mishra</option>
                  -->
                  
                  
                </select>
                  </span></div>
              </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" >Select Status</label>
                <div class="input-group">
                 
                  <span class="input-group-btn">
                      
                  <select name="filter_status" id="filter_status" class="form-control">
                   <option selected="selected" value="">SELECT STATUS</option>
                 
                  <option value="1" <?php if($filter_status=="1"){ ?> selected="selected" <?php } ?> >Accepted</option>
                  <option value="0" <?php if($filter_status=="0"){ ?> selected="selected" <?php } ?> >Pending</option>
                  <option value="2" <?php if($filter_status=="2"){ ?> selected="selected" <?php } ?> >Rejected</option>   
                </select>
                  </span></div>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
	<span style="font-weight: bold;">Total  Amount :: HDFC : <?php echo $hdfc_total; ?></span> &nbsp; |  &nbsp;
	<span style="font-weight: bold;"> ICICI : <?php echo $ICICI_total; ?></span> &nbsp; | &nbsp; 
	<span style="font-weight: bold;"> SBI : <?php echo $State_Bank_of_India_total; ?></span> &nbsp;  | &nbsp; 
	
	<span style="font-weight: bold;"> TAGGED BILLS : <?php echo $TAGGED_BILLS_total; ?></span>
              <br/><br/>

          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">SI ID <?php //echo $column_Si_Id; ?></td>
                <!--<td class="text-left">Store ID <?php //echo $column_date_end; ?></td>-->
                <td class="text-left">Store Name <?php //echo $column_title; ?></td>
                <td class="text-right">Bank <?php //echo //$column_orders; ?></td>
                <td class="text-right">Date <?php //echo $column_total; ?></td>
                <td class="text-right">Amount <?php //echo $column_total; ?></td>
	<td class="text-right">Status</td>
	<td class="text-right">By Whom</td>
              </tr>
            </thead>
            <tbody>
              <?php if ($orders) { $total=0; ?>
              <?php foreach ($orders as $order) { ?>
              <tr>
                <td class="text-left"><?php echo $order['SIID']; ?></td>
                <!--<td class="text-left"><?php echo $order['store_id']; ?></td>-->
                <td class="text-left"><?php echo $order['name']; ?></td>
                <td class="text-right"><?php echo $order['bank_name']; ?></td>
                <td class="text-right"><?php echo $order['date_added']; ?></td>
                <td class="text-right"><?php echo $order['amount']; ?></td>
	<td class="text-right"><?php if($order['status']=="0") { echo "<span style='color: #CC760F;'>Pending</span>"; } else if($order['status']=="1") { echo "<span style='color: #2F9217;'>Accepted</span>"; } else if($order['status']=="2") { echo "<span style='color: #C0250C;'>Rejected</span>"; } ?></td>
	<td class="text-right"><?php echo $order['accepted_by']; ?></td>
              </tr>
              <?php $total=$total+$order['amount'];
              
              } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"> 
              <span style="font-weight: bold;">Pgae Total ::  Amount : <?php echo $total; ?></span> <br/>
              <?php echo $results; ?>  </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
$("#input-store").select2();
<!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=reportbcml/cash_report&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_store = $('#input-store').val();
	
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	
              var filter_status = $('#filter_status').val();
	
	if (filter_status) {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
              var filter_user = $('#filter_user').val();
	
	if (filter_user) {
		url += '&filter_user=' + encodeURIComponent(filter_user);
	}
	location = url;
});
//--></script> 
<script type="text/javascript"><!--
$('#button-download').on('click', function() {
    url = 'index.php?route=reportbcml/cash_report/download_excel&token=<?php echo $token; ?>';
    
    var filter_date_start = $('input[name=\'filter_date_start\']').val();
    
    if (filter_date_start) {
        url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
    }

    var filter_date_end = $('input[name=\'filter_date_end\']').val();
    
    if (filter_date_end) {
        url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
    }
        
    var filter_store = $('#input-store').val();
	
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
             var filter_status = $('#filter_status').val();
	
	if (filter_status) {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
              var filter_user = $('#filter_user').val();
	
	if (filter_user) {
		url += '&filter_user=' + encodeURIComponent(filter_user);
	}
    //location = url;
        window.open(url, '_blank');
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>