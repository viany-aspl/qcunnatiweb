<?php echo $header; ?>
<?php echo $column_left; ?>
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
<h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
</div>
<div class="panel-body" style="min-height:410px">
<?php //echo $tab3; ?>

<ul id="geo" class="nav nav-tabs nav-justified">
            <li class="<?php echo $tab1; ?>"><a onclick="return active_tab(1); " href="#tab-received" data-toggle="tab">Material received</a></li>
            <li class="<?php echo $tab2; ?>"><a onclick="return active_tab(2); " href="#tab-sold" data-toggle="tab">Material Sold</a></li>
            
            
        </ul>
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
                <label class="control-label" for="input-name">Product Name</label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                <input type="hidden" name="filter_name_id"  value="<?php echo $filter_name_id; ?>" id="filter_name_id"/>
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
              <div class="col-sm-6" style="float: right;">  
                   <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button> 
                  </div>
              
            </div>
</div>
</div>

</div>
    

          <div class="tab-content">  
              
     <!--*************************Material Receive***********************-->         
     <div class="tab-pane <?php echo $tab1; ?>" id="tab-received">
      <div class="panel-body">
     
        <div class="row">
            <div style="font-weight: bold;float: left;" class="col-sm-6" > Total Received Quantity : <?php echo $total_received; ?></div>
            <div class="col-sm-6" style="float: right;">
                  <button type="button" id="button-download1" class="btn btn-primary pull-right" style="margin-left:24px">
                  <i class="fa fa-download"></i> Download
                 </button>
                  
            </div>
        </div>
      </div>     
        <div class="panel-body">
          
          
          <div class="table-responsive">
           <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">Store Name (Receiver)</td>
                <td class="text-left">Order date</td>
                <td class="text-left">Received date</td>
                <td class="text-right">Product Name</td>
                <td  class="text-right">Product ID</td>
               
                <td  class="text-right">Qnty</td>
                
                
	     
              </tr>            </thead>
            <tbody>
               
            <?php if ($orders) { ?>
             <?php foreach ($orders as $order) { 

	     //print_r($order);
	     ?>
              <tr>
                <td class="text-left"><?php echo $order['store_name']; ?></td>
                <td class="text-left"><?php echo $order['order_date']; ?></td>
                <td class="text-left"><?php echo $order['recive_date']; ?></td>
                <td class="text-right"><?php echo $order['product_name']; ?></td>
                <td class="text-right"><?php echo $order['product_id']; ?></td>
               
                <td class="text-right"><?php echo $order["quantity"]; ?></td>
               
	        
              </tr>              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="12"><?php echo $text_no_results; ?></td>
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
 <!--*************************end Material Receive***********************-->   
 
 
 
 
 
 
 
 
 <!--*************************Stock Transfer***********************-->   
  <div class="tab-pane <?php echo $tab2; ?>" id="tab-sold">
                   
         
    <div class="panel-body">
        <div class="row">
              <div style="font-weight: bold;float: left;" class="col-sm-6" > Total Sold Quantity : <?php echo $total_sold; ?></div>
              <div class="col-sm-6" style="float: right;">
                  <button type="button" id="button-download2" class="btn btn-primary pull-right" style="margin-left:24px">
                  <i class="fa fa-download"></i> Download
                 </button>
                  
              </div>
        </div>
    </div>  
      
     <div class="panel-body">
        
          <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
              <tr>
                <td class="text-left">Store Name</td>
                <td class="text-left">Order date</td>
                
                <td class="text-right">Product Name</td>
                <td  class="text-right">Product ID</td>
               
                <td  class="text-right">Qnty</td>
                
              </tr>            
	</thead>
            <tbody>
            <?php if ($orders2) { ?>
             <?php foreach ($orders2 as $order) { ?>
              <tr>
	   <td class="text-left"><?php echo $order['store_name']; ?></td>
                <td class="text-left"><?php echo $order['order_date']; ?></td>
                
                <td class="text-right"><?php echo $order['product_name']; ?></td>
                <td class="text-right"><?php echo $order['product_id']; ?></td>
                <td class="text-right"><?php echo $order['quantity']; ?></td>
	    
              </tr>             
             <?php } ?>
            <?php } else { ?>
              <tr>
                <td class="text-center" colspan="12"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
          </div>
        
         
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination2; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results2; ?></div>
        </div>  
        </div>
 </div>
 <!--*************************End Stock Transfer***********************-->     
 
 

          <span id="tab_active_" style="display: none;">
<?php echo @$_REQUEST["tab"]; ?>
</span>    
          </div> 

</div>
</div>
</div>
</div>

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

<script type="text/javascript">
function active_tab(tab)
{
//alert(tab);
$('#tab_active_').html(tab);
//document.getElementById("tab_active_").value="text";
//document.getElementById("tab_active_").value=tab;
return false;
}
</script>

<input type="hidden" name="tab_active_22" id="tab_active_22" value="<?php echo @$_REQUEST["tab"]; ?>" />
<script type="text/javascript">
$('#button-filter').on('click', function() {
	 url = 'index.php?route=report/productreconciliation&token=<?php echo $token; ?>';
	 //url += '&page='+'1';
         //url += '&tab='+'1';
         
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
                var filter_name_id = $('input[name=\'filter_name_id\']').val();
	
	if (filter_name_id) {
		url += '&filter_name_id=' + encodeURIComponent(filter_name_id);
                
	}
	}
         var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != 0) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	
var tabbb=$('#tab_active_').html();
              if(tabbb=="")
              {
                url += '&tab=1';
              }
              else
              {
                  url += '&tab=' + tabbb;
              }
             //alert(tabbb);//return false;
	location = url;
});

</script> 

<script type="text/javascript">
    $('#button-download1').on('click', function() {
	url = 'index.php?route=report/productreconciliation/download_recived&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
                var filter_name_id = $('input[name=\'filter_name_id\']').val();
	
	if (filter_name_id) {
		url += '&filter_name_id=' + encodeURIComponent(filter_name_id);
                
	}
	}
         var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != 0) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	

        window.open(url, '_blank');
	
});
    
    </script> 
<script type="text/javascript">
    $('#button-download2').on('click', function() {
	url = 'index.php?route=report/productreconciliation/download_sold&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
                var filter_name_id = $('input[name=\'filter_name_id\']').val();
	
	if (filter_name_id) {
		url += '&filter_name_id=' + encodeURIComponent(filter_name_id);
                
	}
	}
         var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != 0) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	

        window.open(url, '_blank');
	
});
    
    </script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>
