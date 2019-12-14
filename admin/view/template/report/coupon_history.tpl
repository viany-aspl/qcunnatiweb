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
        <button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px;"> Download</button>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end">Select Coupon</label>
 		<?php //echo 'kkk';print_r($coupons);  ?>
                <div class="input-group date">
                  <?php //echo $filter_store; //print_r($stores);//exit; ?>
                  <span class="input-group-btn">
                     
                  <select name="filter_coupon" id="input-coupon" class="form-control">
                   <option selected="selected" value="">SELECT COUPON</option>
                  <?php foreach ($coupons as $coupon) { //echo $store['store_id'];  ?>
                  <?php if ($coupon['coupon_id'] == $filter_coupon) {
                      if($filter_coupon!=""){
                      ?>
                  <option value="<?php echo $coupon['coupon_id']; ?>" selected="selected"><?php echo $coupon['name']; ?></option>
                      <?php }} else { ?>
                  <option value="<?php echo $coupon['coupon_id']; ?>"><?php echo $coupon['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                  </span></div>
              </div>
              
            </div>
            <div class="col-sm-6">
                
              
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">Order ID</td>
	<td class="text-left">Customer</td>
                <td class="text-left">Discount Amount</td>
	<td class="text-left">Order Total</td>
                <td class="text-left">Date Added</td>
                
              </tr>
            </thead>
            <tbody>
              <?php if ($histories) {  if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; }?>
              <?php foreach ($histories as $order) { ?>
              <tr>
               
	<td class="text-left"><?php echo $order['order_id']; ?></td>
                <td class="text-left"><?php echo $order['customer']; ?></td>
                <td class="text-left"><?php echo $order['amount']; ?></td>
	<td class="text-left"><?php echo $order['total']; ?></td>
                <td class="text-left"><?php echo $order['date_added']; ?></td>
                
              </tr>
              <?php 
              $aa++;
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
             
              <?php echo $results; ?>  </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/coupon/history&token=<?php echo $token; ?>';
	
        	var filter_coupon= $('select[name=\'filter_coupon\']').val();
	
	if (filter_coupon!="") {
		url += '&filter_coupon=' + encodeURIComponent(filter_coupon);
	}
	//alert(url);
	location = url;
});
//--></script> 
<script type="text/javascript"><!--
$('#button-download').on('click', function() {
    url = 'index.php?route=report/coupon/history_download&token=<?php echo $token; ?>';
	
        	var filter_coupon= $('select[name=\'filter_coupon\']').val();
	
	if (filter_coupon!="") {
		url += '&filter_coupon=' + encodeURIComponent(filter_coupon);
	}
        window.open(url, '_blank');
});
//-->

</script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>