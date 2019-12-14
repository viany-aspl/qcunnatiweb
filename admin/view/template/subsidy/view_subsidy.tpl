<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>View Subsidy</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
      <?php if ($success) {  ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i>Subsidy list</h3>
        <!--<button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download</button>-->
      </div>
        
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-end">Select Store</label>
               
                      
                  <select name="filter_store" id="filter_store" style="width: 100%;" class="select2 form-control" >
                   <option selected="selected" value="">SELECT STORE</option>
                  <?php foreach ($stores as $store) {  ?>
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
                <label class="control-label" for="input-date-end">Product Subsidy Category</label>
               
                      
                  <select name="filter_category" id="filter_category" style="width: 100%;" class="select2 form-control" >
                   <option selected="selected" value="">SELECT CATEGORY</option>
                  <?php foreach ($categories as $category) {   //print_r($categories);  ?>
                  <?php if ($category['category_id'] == $filter_category) {
                     
                      ?>
                  <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['category_name']; ?></option>
                      <?php } else { ?>
                  <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                 
              </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                <label class="control-label" for="input-date-end">Select Product</label>
                <div class="input-group">
                  
                  <span class="input-group-btn">
                 <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="Product" id="input-name" class="form-control" />
                <input type="hidden" name="filter_name_id"  value="<?php echo $filter_name_id; ?>" id="filter_name_id"/>

                  
                  </span></div>
              </div>
                <button type="button" id="button-update" class="btn btn-primary pull-left" style="display:none;" onclick="updateprodSubsidy('<?php echo $store['name']; ?>');">Set Subsidy Zero (for all products)</button>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">Store Name </td>
                <td class="text-left">Store ID </td>
                <td class="text-left">Product Name </td>
                <td class="text-left">Product ID </td>
	  <td class="text-left">Category</td>
                <td class="text-left">Subsidy (%) </td>
                <td class="text-left">Action <br/><i style="font-size: 8px">(Make Subsidy to Zero)</i></td>
               
              </tr>
            </thead>
            <tbody>
              <?php if ($orders) {  ?>
              <?php foreach ($orders as $order) { //print_r($order);
                  ?>
                
              <tr>
                <td class="text-left"><?php echo $order['store_name']; ?></td>
                <td class="text-left"><?php echo $order['store_id']; ?></td>
                <td class="text-left"><?php echo $order['product_name']; ?></td>
                <td class="text-left"><?php echo $order['product_id']; ?></td>
	  <td class="text-left"><?php echo $order['category_name']; ?></td>
                <td class="text-left"><?php echo $order['subsidy']; ?> % </td>
                <td><button type="button" id="button-filter" class="btn btn-primary pull-right" onclick="setSubsidyZero('<?php echo $order['store_id']; ?>','<?php echo $order['product_id']; ?>','<?php echo $order['category_id']; ?>');">Set Zero</button></td>
              </tr>
              <?php $total=$total+$total_1;
              
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
    
  <script type="text/javascript">
     
   $("#filter_store").select2();
      
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
    var butt=getURLVar('filter_butt');
    if(butt=="1")
         {       
           
          //var rk= $('#filter_store :selected').text();
           $("#button-update").html("set subsidy zero (for all product)"+"<br/>"+$('#filter_store :selected').text());
          //alert(rk);
           $("#button-update").show();
         }
         else
         {
           $("#button-update").hide(); 
         }
    <!--
$('#button-filter').on('click', function() {

         
        //alert(store_name);
	url = 'index.php?route=subsidy/subsidy/view&token=<?php echo $token; ?>';
        
	
	var filter_name = $('#input-name').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
        var filter_name_id = $('#filter_name_id').val();
	
	if (filter_name_id) {
                if(filter_name){
		url += '&filter_name_id=' + encodeURIComponent(filter_name_id);
                }
	}
	var filter_category = $('#filter_category').val();
	
	if (filter_category) {
		url += '&filter_category=' + encodeURIComponent(filter_category);
               
	}
	var filter_store = $('#filter_store').val();
	
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
               
	
	
		url += '&filter_butt=' + encodeURIComponent("1");
	
	}
        	
        
        
        
       
		
	//alert(url);
	location = url;
        
});
//--></script> 
     <script type="text/javascript"><!--
$('#button-download').on('click', function() {
	url = 'index.php?route=report/cash_report/download_excel&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_group = $('select[name=\'filter_group\']').val();
	
	if (filter_group) {
		//url += '&filter_group=' + encodeURIComponent(filter_group);
	}
	
	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').val();
	
	if (filter_order_status_id != 0) {
		//url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
	}	

	//location = url;
        window.open(url, '_blank');
});
//--></script> 
    
    <script type="text/javascript">
       function setSubsidyZero(storeid,product_id,category_id)
       {
       //alert(storeid);
       //alert(product_id);
        swal({
                title: "Are you sure?",
                text: "This will set your subsidy value to  zero !",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, make it!",
                cancelButtonText: "No, cancel it!",
                closeOnConfirm: false,
                closeOnCancel: false
                
            },
        function(isConfirm){
          if (isConfirm) {
          var url="index.php?route=subsidy/subsidy/updateSubsidyZero&token="+getURLVar('token')+"&store_id="+storeid+"&product_id="+product_id+"&category_id="+category_id;
         // alert(url);
          console.log(url);    
          $.ajax({
                    type: "POST",
                    url: url,
                    //data: data,
                    contentType: false,
                    cache : false,
                    processData :false,
                    dataType: "text",
                    success: function( data ) {

                    //alert(data); 
                     
                          swal("Updated!", "Your subsidy value to  zero.", "success");
                          $('.confirm').click(function(){
                              
                           var urln = 'index.php?route=subsidy/subsidy/view&token=<?php echo $token; ?>';
               var filter_name = $('#input-name').val();
	
	if (filter_name) {
		urln += '&filter_name=' + encodeURIComponent(filter_name);
	}
        var filter_name_id = $('#filter_name_id').val();
	
	if (filter_name_id) {
                if(filter_name){
		urln += '&filter_name_id=' + encodeURIComponent(filter_name_id);
                }
	}
	var filter_store = $('#filter_store').val();
	
	if (filter_store) {
		urln += '&filter_store=' + encodeURIComponent(filter_store);
               					
	}
        
      
        location.href=urln;
                
          });
                    },
                    error: function(res)
                    {
                        alert(res); 
                    }
                    }); 
            //swal("Updated!", "Your subsidy value to  zero.", "success");
          } 
          else {
            swal("Cancelled", "Your subsidy value is safe :)", "error");
          }
        });
       }
    </script>
    
    <script type="text/javascript">
       function updateprodSubsidy(storename)
       {
       //alert("hello")
       //alert(storeid);
       //alert(product_id);
       var store=document.getElementById('filter_store').value;
         var rk= $('#filter_store :selected').text(); 
       //alert(store);
       if(store=="")
       {
       sweetAlert("Oops...", "Please Select Store!", "error");
       return false;
       }
        
        swal({
                title: "Are you sure?",
                text: "This will set all product subsidy value to  zero of Store "+"\n"+rk,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, make it!",
                cancelButtonText: "No, cancel it!",
                closeOnConfirm: false,
                closeOnCancel: false
                
            },
        function(isConfirm){
          if (isConfirm) {
           
          
          var url="index.php?route=subsidy/subsidy/updateProductSubsidyZero&token="+getURLVar('token')+"&store_id="+store;
          
          console.log(url);    
          $.ajax({
                    type: "POST",
                    url: url,
                    //data: data,
                    contentType: false,
                    cache : false,
                    processData :false,
                    dataType: "text",
                    success: function( data ) {                  
                          swal("Updated!", "Your subsidy value to  zero.", "success");
                          $('.confirm').click(function(){
                              
                           var urln = 'index.php?route=subsidy/subsidy/view&token=<?php echo $token; ?>';
               var filter_name = $('#input-name').val();
	
	if (filter_name) {
		urln += '&filter_name=' + encodeURIComponent(filter_name);
	}
        var filter_name_id = $('#filter_name_id').val();
	
	if (filter_name_id) {
                if(filter_name){
		urln += '&filter_name_id=' + encodeURIComponent(filter_name_id);
                }
	}
	var filter_store = $('#filter_store').val();
	
	if (filter_store) {
		urln += '&filter_store=' + encodeURIComponent(filter_store);
               					
	}
        
      
        location.href=urln;
                
          });
                    },
                    error: function(res)
                    {
                        alert(res); 
                    }
                    }); 
            //swal("Updated!", "Your subsidy value to  zero.", "success");
          } 
          else {
            swal("Cancelled", "Your subsidy value is safe :)", "error");
          }
        });
        }
       
       
       function subsidyzero(stoteid)
       {
         
         if(stoteid!="")
         {
            
           
          //var rk= $('#filter_store :selected').text();
           $("#button-update").html("set subsidy zero (for all product)"+"<br/>"+$('#filter_store :selected').text());
          //alert(rk);
           $("#button-update").show();
         }
         else
         {
           $("#button-update").hide(); 
         }
       }
    </script>
    
    
    
    
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>