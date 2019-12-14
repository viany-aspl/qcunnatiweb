<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>DSCL Store - Item Wise Product Sold</h1>
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
        
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start">Date</label>
                <div class="input-group date" id="date_from">
                  <input type="text" name="filter_date"  value="<?php echo $filter_date; ?>" placeholder="Date" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              
            </div>
            <div class="col-sm-6">
              <br/>
              <button type="button" id="button-download_till_date" class="btn btn-primary pull-right" style="margin-right: 10px;"><i class="fa fa-download"></i> Download Till Date</button>
	<button type="button" id="button-download_on_date" class="btn btn-primary pull-right" style="margin-right: 10px;"><i class="fa fa-download"></i> Download On Date</button>
            </div>
          </div>
        </div>
        
        <div style="min-height: 200px;">


	</div>
    </div>
  </div>
  

<!-- Modal -->
  <div class="modal fade" id="myModal_create_bill" data-backdrop="static" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        
        <div class="modal-body" style="min-height: 200px;text-align: center;padding-top: 78px;">
        <span id="wait_1" style="font-size: 24px;">
	
		Please Wait...

	</span>
	<br/>
	<span  id="wait_2">It will take 5 to 10 Minutes
	</span>
	<input id="download_link" type="hidden" value="" />
	<br/>
	<button type="button" id="button-download_link" class="btn btn-primary" style="display: none;"><i class="fa fa-download"></i> Download Now </button>
	<br/>
        </div>
        <!--<div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>-->
      </div>
      
    </div>
  </div>

<script type="text/javascript">
$('#button-download_link').on('click', function() 
{

var download_link=$("#download_link").val();
url = 'https://unnati.world/shop/system/upload/'+download_link;
window.open(url, '_blank');
location.reload();
});

$('#button-download_till_date').on('click', function() {
    url = 'index.php?route=reportdscl/report/download_dscl_Item_Wise_Product_Sold_till_date&token=<?php echo $token; ?>';
   
    var filter_date = $('input[name=\'filter_date\']').val();
   
    if (filter_date) {
        url += '&filter_date=' + encodeURIComponent(filter_date);
    }
var currentdate = new Date(); 
    var datetime = "Now: " + currentdate.getDate() + "/"
                + (currentdate.getMonth()+1)  + "/" 
                + currentdate.getFullYear() + " @ "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();

    //location = url;
if(currentdate.getHours()<18)
{
alertify.error('Download will be available after 6 P.M.');
return false;
}
else
{
$.ajax({
url: url,
beforeSend: function() {
$('#myModal_create_bill').modal('show');
    },
success: function(result)
{
//alert(result);
$("#download_link").val(result);
$("#wait_1").html("File is ready");
$("#wait_2").hide();
$("#button-download_link").show();
}

});

}
    //window.open(url, '_blank');
});
$('#button-download_on_date').on('click', function() {
    url = 'index.php?route=reportdscl/report/download_dscl_Item_Wise_Product_Sold_on_date&token=<?php echo $token; ?>';
   
    var filter_date = $('input[name=\'filter_date\']').val();
   
    if (filter_date) {
        url += '&filter_date=' + encodeURIComponent(filter_date);
    }
var currentdate = new Date(); 
    var datetime = "Now: " + currentdate.getDate() + "/"
                + (currentdate.getMonth()+1)  + "/" 
                + currentdate.getFullYear() + " @ "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();

    //location = url;
if(currentdate.getHours()<18)
{
alertify.error('Download will be available after 6 P.M.');
return false;
}
else
{
$.ajax({
url: url,
beforeSend: function() {
$('#myModal_create_bill').modal('show');
    },
success: function(result)
{
//alert(result);
$("#download_link").val(result);
$("#wait_1").html("File is ready");
$("#wait_2").hide();
$("#button-download_link").show();
}

});

}
    //location = url;
    //window.open(url, '_blank');
});
</script>

  <script type="text/javascript">

$('.date').datetimepicker({
	pickTime: false
});

</script>
      
      </div>
<?php echo $footer; ?>