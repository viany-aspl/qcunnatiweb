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
<i class="<?php echo $tool_tip_class; ?> " data-toggle="tooltip" style="<?php echo $tool_tip_style; ?>" title="<?php echo $tool_tip; ?>"></i>
 
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
                <label class="control-label" for="input-date-end">Select Store</label>
                
                      
                  <select name="filter_store" style="width:100%;" id="input-store" class="select2 form-control">
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
            
              </div>
              
            </div>
            <div class="col-sm-6">
                
              <div class="form-group">
                <label class="control-label" for="input-date-end">Person Name</label>
                <div class="input-group date">
                  <?php //echo $filter_store; //print_r($stores);//exit; ?>
                  <span class="input-group-btn">
                      
                  <input type="text" style="text-transform: uppercase" value="<?php echo $filter_name; ?>" placeholder="Person Name" name="filter_name" id="filter_name" class="form-control" />
                  
                
                  </span></div>
              </div>
 <label class="control-label" for="button-filter"></label>

              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Filter</button>
 </div>



          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                  <td class="text-left">SID</td>
                <td class="text-left">Name</td>
                <td class="text-left">Mobile Number</td>
	        <td class="text-left">Store Name</td>
                <td class="text-left">Store ID</td>
                <td class="text-left">User Group </td>
               </tr>            
            </thead>
            <tbody>
              <?php if ($orders) { if($_GET["page"]=="1"){ $aa=1; }elseif($_GET["page"]==""){$aa=1;} else { $aa=@$_GET["page"]+20-1; }; ?>
              <?php foreach ($orders as $order) { ?>
              <tr>
                  <td class="text-left"><?php echo $aa; ?></td>
                 <td class="text-left"><?php echo $order['name']; ?></td>
                <td class="text-left"><?php echo $order['telephone']; ?></td>
	        <td class="text-left"><?php echo $order['store_name']; ?></td>
                <td class="text-left"><?php echo $order['store_id']; ?></td>
                <td class="text-left"><?php echo $order['group_name']; ?></td>
                

                
              </tr>              <?php $aa++; } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
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
$('#input-store').select2();
<!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/stores&token=<?php echo $token; ?>';
	
        	var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store!="") {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}

	var filter_name = $('#filter_name').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
		
	location = url;
});
//--></script> 
 <script type="text/javascript"><!--
$('#button-download').on('click', function() {
	url = 'index.php?route=report/stores/download_excel&token=<?php echo $token; ?>';
	
	
              var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store!="") {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}

	var filter_name = $('#filter_name').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}	
	
        window.open(url, '_blank');
	//location = url;
});
//--></script> 
  
     
     </div>
<?php echo $footer; ?>