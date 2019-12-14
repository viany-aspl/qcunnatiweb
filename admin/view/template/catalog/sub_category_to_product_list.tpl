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


<a href="<?php echo $add_link; ?>" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> </a>
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

<button type="button" id="button-download" style="margin-top: -10px;" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download</button>
</div>
<div class="panel-body" style="min-height:410px">

    <div class="panel-body">

<div class="well">
<div class="row">
<div class="col-sm-6">
              
              	
              <div class="form-group">
                <label class="control-label" >Sub Category </label>
                <div class="input-group">
                 
                  <span class="input-group-btn">
                      
                  <select name="filter_sub_category_id" id="filter_sub_category_id" class="form-control">
                   <option selected="selected" value="">SELECT Sub Category</option>
	<?php foreach($sub_categories as $sub_category){ ?>
		 <option value="<?php echo $sub_category['sid']; ?>" <?php if($filter_sub_category_id==$sub_category['sid']){ ?> selected="selected" <?php } ?> >
			<?php echo $sub_category['name']; ?>
		</option>
	<?php } ?>
                  
                  
                  
                </select>
                  </span></div>
              </div>
            </div>
<div class="col-sm-6">
                <div class="form-group">
                <label class="control-label" for="input-date-end">Product</label>
                
                  <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="Product"  id="filter_name" class="form-control" />
				  <input type="hidden" name="filter_product_id" value="<?php echo $filter_product_id; ?>"   id="filter_product_id"  />
					
				 
              </div>
              
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Filter</button>
            </div>
</div>
</div>

</div>



<div class="panel-body">

<div class="table-responsive">
   
<table class="table table-bordered">
<thead>
              <tr>
                <td class="text-left">Sub Category </td>
                
                <td class="text-left">Product</td>
                
                
              </tr>
            </thead>
<tbody>
              <?php if ($subcategorytoproducts) { ?>
              <?php foreach ($subcategorytoproducts as $subcategorytoproduct) { ?>
              <tr>
                <td class="text-left"><?php echo $subcategorytoproduct['sub_category_name']; ?></td>
                
                <td class="text-left"><?php echo $subcategorytoproduct['product_name']; ?></td>
                
                
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
              
              <?php echo $results; ?>  </div>
        </div>

</div>





</div>
</div>


</div>
</div>

<script type="text/javascript"><!--
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
		$('input[name=\'filter_product_id\']').val(item['value']);
	}
});

</script>
<input type="text" name="tab_active_" id="tab_active_" value="tab1" />
<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=catalog/sub_category&token=<?php echo $token; ?>';
	
	var filter_sub_category_id = $('#filter_sub_category_id').val();
	
	if (filter_sub_category_id) {
		url += '&filter_sub_category_id=' + encodeURIComponent(filter_sub_category_id);
	}

	var filter_product_id = $('input[name=\'filter_product_id\']').val();
	
	
		
	var filter_name = $('#filter_name').val();
	
	if (filter_name) {
		if(!filter_product_id)
		{
		alertify.error('Please Select Product');
		}
		if (filter_product_id) 
		{
			url += '&filter_product_id=' + encodeURIComponent(filter_product_id);
		}
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}	
      // alert(url);     
	location = url;
});
//--></script> 
<script type="text/javascript"><!--
$('#button-download').on('click', function() {
    url = 'index.php?route=catalog/sub_category/download_sub_category_to_product_list&token=<?php echo $token; ?>';
	
	var filter_sub_category_id = $('#filter_sub_category_id').val();
	
	if (filter_sub_category_id) {
		url += '&filter_sub_category_id=' + encodeURIComponent(filter_sub_category_id);
	}

	var filter_product_id = $('input[name=\'filter_product_id\']').val();
	
	
		
	var filter_name = $('#filter_name').val();
	
	if (filter_name) {
		if(!filter_product_id)
		{
		alertify.error('Please Select Product');
		}
		if (filter_product_id) 
		{
			url += '&filter_product_id=' + encodeURIComponent(filter_product_id);
		}
		url += '&filter_name=' + encodeURIComponent(filter_name);
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
