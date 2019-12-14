<?php echo $header; ?><?php echo $column_left;//print_r($results_n); ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Sale Summary</h1>
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
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> Sale Summary</h3>
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
                <label class="control-label" for="input-store">Select Store</label>
                <div class="input-group">
                  
                  <span class="input-group-btn">
                      
                  <select name="filter_store" id="input-store" class="select2 form-control">
                   <option selected="selected" value="">SELECT STORE</option>
                  <?php foreach ($stores as $store) { //echo $store['store_id'];  ?>
                  <?php if ($store['store_id'] == $filter_store) {
                      if($filter_store!=""){
                      ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                      <?php }} else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                  </span></div>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Search</button>
            </div>
          </div>
        </div>


        <div class="table-responsive">
<span style="font-weight: bold;">Total Cash taken by store : <?php echo number_format((float)$total_cash_all, 2, '.', '');; ?></span> 
          
           &nbsp; | &nbsp;
           <span style="font-weight: bold;">Total Subsidy given Company : <?php echo number_format((float)$total_subsidy_all, 2, '.', '');; ?></span> 
           
           &nbsp; | &nbsp;  
           <span style="font-weight: bold;">Total : <?php $total=number_format((float)$total_Total_all, 2, '.', ''); echo $total; ?></span> 
<!--
<span style="float: right;font-weight: bold;color: #933B3B;">Note : C-Tagged=>Cash Tagged</span>-->
           <br/><br/>
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">S. No.</td>
                <td class="text-right">Store name</td>
             
                
	<td class="text-right">Cash taken by store</td>
              <td class="text-right"> Subsidy given Company</td> 
	<td class="text-right">No. Order (Subsidy)</td>
	
                <td class="text-left">Total</td>
                
              </tr>
            </thead>
            <tbody>
              <?php $total=0; if ($results) {  if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; } ?>
              <?php foreach ($orders as $order) { //print_r($order); ?>
              <tr>
               <td class="text-left"><?php echo $aa; ?></td>
                
              <td class="text-right"><?php echo $order['store_name']; ?></td>
             
              <td class="text-right"><?php echo $order['cash']; ?></td> 
	
	<td class="text-right"><?php echo $order['subsidy']; ?></td>
	
             
	<td class="text-right"><?php echo $order['subsidy_order']; ?></td>
              <td class="text-left"><?php echo $order['total']; ?></td>
                
              </tr>
              <?php $tarr=explode('Rs.',$order['total']);$total=$total+$tarr[1];  $aa++; } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-4 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-8 text-right">
	<span style="font-weight: bold;">Page Total  :: </span> 
           &nbsp;  
           <span style="font-weight: bold;">Cash taken by store : <?php echo number_format((float)$total_cash, 2, '.', '');; ?></span> 
           
           &nbsp; | &nbsp;
           <span style="font-weight: bold;">Total Subsidy given Company: <?php echo number_format((float)$total_subsidy, 2, '.', '');; ?></span> 
           
           &nbsp; | &nbsp; 
           <span style="font-weight: bold;">Total : <?php $total=number_format((float)$total_total, 2, '.', ''); echo $total; ?></span> 
           <br/>

         <?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
 </div>

  <script type="text/javascript"><!--

$("#input-store").select2();

$('.date').datetimepicker({
	pickTime: false
});
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/sale_summary/subsidy&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}			
	location = url;
});

$('#button-download').on('click', function() {
    url = 'index.php?route=report/sale_summary/download_excel_subsidy&token=<?php echo $token; ?>';
    	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
    
    //location = url;
        window.open(url, '_blank');
});
//--></script>
<?php echo $footer; ?>