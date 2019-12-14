<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo "Loan Inventory"; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">Loan Inventory</a></li>
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

<?php if ($error) {  ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo "FM Delivery"; ?></h3>
		<!-- <button type="button" id="button-pdf" class="btn btn-primary pull-right" style="margin-top: -8px !important; margin-right: 10px !important;">
            Download PDF</button>--->
     
      </div>
	  
	  
	  <form action="index.php?route=tagpos/loan_inventory/addloaninventory&token=<?php echo $token;?>" method="post" enctype="multipart/form-data">
	  
	  
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-store">Select FM</label>
                  <select   name="filter_fmlist" id="fm_id" class="form-control" style="width:100%;" >
				  <option value="">Select FM</option>
                  <?php foreach ($fmlist as $list) { ?>
                  <?php if ($list['id'] == $filter_fmlist) { ?>
                  <option value="<?php echo $list['id']; ?>,<?php echo $list['name']; ?>" selected="selected"><?php echo $list['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $list['id']; ?>,<?php echo $list['name']; ?>"><?php echo $list['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
				
        </div>
			
 

 
 
            </div> 
			
			<div class="col-sm-6">
           
              
		</div>
		<!---<div class="col-sm-6">
			<div class="form-group">
				<label class="control-label" for="input-date-end"></label>
            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
			
            </div>
			</div>--->
          </div>
        </div>
        <div class="table-responsive">
	
                           
                           <br/><br/>
         <table id="myTable" class=" table order-list text-center table table-bordered " style="width:100%;">
				<thead>
				<tr>
				<td class="text-center">Product Name</td>
				<td class="text-center">Issue Date</td>
				<td class="text-center">Quantity</td>
				
				<td class="text-center"> <input type="button" class="btn btn-lg btn-block " id="addrow" value="Add Row" /></td>
				</tr>
				</thead>
				<tbody class="full_border">
				<tr >
				<td style="border-right:1px solid #656565; width:40%;">
				<select required class="form-control tab_border" id="product_id0" name="product_id[]">
				 <option value="">Select Product</option>
				 <?php foreach ($products as $product) { ?>
				
				<option value="<?php echo $product['product_id']; ?>"><?php echo $product['model'];?></option>
				
				 <?php } ?>
				</select>
				<input required type="hidden" name="store_id" placeholder="store_id" id="store_id" value=<?php echo $product['store_id'] ?> />
				<input required type="hidden" name="user_id" placeholder="username" id="username" value=<?php echo $user_id; ?> />
				</td>
				<td style="border-right:1px solid #656565; width:30%;" >
                   <div class="input-group date" id="date_from">
                  <input required type="text" id="date0" name="filter_date_start[]" value="<?php //echo $filter_date_start; ?>" placeholder="Issue Date" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>

				</td>
				
				<td style="border-right:1px solid #656565; width:30%; ">
				<input required type="text" name="qty[]" placeholder="Quantity" id="qty0"  onkeypress="return isNumber(event)" class="form-control tab_border" />
				</td>
				
				

				<td style=" width:20%; color:#fff;" ><a class="deleteRow"></a>

				</td>
				</tr>
				</tbody>
				<tfoot>
				<tr>
				
				</tr>
				<tr>
				</tr>
				</tfoot>

				</table>
        </div>
        <div class="row">
         <div class="col-sm-6"> <input onclick="return submit_order();" type="submit" class="btn btn-primary pull-right" value="Submit" />
			
                  </div>
        </div>
      </div>
    </div>
	
	</form>
  </div>




<script>
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

</script>

  <script type="text/javascript">
  
     function submit_order()
	{
		//alert("highlight_file");
	var fm_id=$("#fm_id").val();
	var product_id=$("#product_id0").val();
	//alert(product_id);
	var date=$("#date0").val();
	var qty=$("#qty0").val();
	
	if(fm_id=='')
		{
			alert('Please select Field Motivator');
			//alertify.error('Please select Field Motivator');
			return false;
		}
		
		if(product_id=='')
		{
			alert('Please select Product Name');
			//alertify.error('Please select Field Motivator');
			return false;
		}
		
		if(date=='')
		{
			alert('Please select Date');
			//alertify.error('Please select Field Motivator');
			return false;
		}
		
		if(qty=='')
		{
			alert('Please select Quantity');
			//alertify.error('Please select Field Motivator');
			return false;
		}
	
	
	
	//alert(fm_id);
	}
  
  

  //$(document).ready(function () {
	  //$("#filter_date_start"+counter ).datepicker({dateFormat: 'yy-mm-dd'});
	
	//var product_id=$("#product_id").val();
	//alert(product_id);
		
      var counter = 1;

    $("#addrow").on("click", function () {
	var pid=$('#myTable tbody tr');		
		for (i=0;i<pid.length;i++) {
			
			if($('#product_id'+i).val()=='')
			{
				
				alert('Please select product');
				return false;
			}
			if($('#date'+i).val()=='')
			{
				
				alert('Please select Date');
				return false;
			}
			if($('#qty'+i).val()=='')
			{
				
				alert('Please select quantity');
				return false;
			}
			
		}
		$.ajax({ 
        type: 'post',
        url: 'index.php?route=tagpos/loan_inventory/getdropdownproduct&token=<?php echo $token;?>',
        cache: false,
        success: function(data) {
		//alert(data);
		try{
        var newRow = $("<tr>");
        var cols = "";

        cols += '<td><select type="text" required="required" class="form-control" name="product_id[]" id="product_id' + counter + '"><option value="">Select Product</option>'+data+'</select></td>';
        cols += '<td><div class="input-group date"  id="date_from"><input type="text" required class="form-control" placeholder="Issue Date" name="filter_date_start[]" id="date' + counter + '" data-date-format="YYYY-MM-DD"/><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
        cols += '<td><input type="text" class="form-control" placeholder="Quantity" required name="qty[]" id="qty' + counter + '"/></td>';


        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
        newRow.append(cols);
        $("table.order-list").append(newRow);      
		$(".date").datetimepicker({
			  timepicker: false,
			  pickTime: false,
			  closeOnDateSelect: true
		});
	    counter++;
		}catch(e){alert(e);}
		}
        });
    });



    $("table.order-list").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();      
        counter -= 1
    });


//});



function calculateRow(row) {
    var price = +row.find('input[name^="price"]').val();

}

function calculateGrandTotal() {
    var grandTotal = 0;
    $("table.order-list").find('input[name^="price"]').each(function () {
        grandTotal += +$(this).val();
    });
    $("#grandtotal").text(grandTotal.toFixed(2));
}
  
$("#fm_id").select2();
$("#input-fm").select2();





</script> 
 <script type="text/javascript"><!--
$('#button-download').on('click', function() {
	url = 'index.php?route=reportbcml/reconciliation/download_excel&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_unit = $('select[name=\'filter_unit\']').val();
	
	if (filter_unit != '') {
		url += '&filter_unit=' + encodeURIComponent(filter_unit);
	}	

	
         var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != 0) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	
        window.open(url, '_blank');
	//location = url;
});
//--></script> 
        
<script type="text/javascript">
$('#button-pdf').on('click', function() {

    var filter_fm_name = $('select[name=\'filter_fm_name\']').val();
	var filter_store = $('select[name=\'filter_store\']').val();
	var filter_unit = $('select[name=\'filter_unit\']').val();
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	//alert(filter_fm_name);
	
	if(!filter_store)
	{
	  alertify.error('Please Select Store');
	  return false;
	}
	if(!filter_unit)
	{
	  alertify.error('Please Select Unit');
	  return false;
	}
	if(!filter_fm_name)
	{
	  //alertify.error('Please Select FM Name');
	  //return false;
	}
	if(!filter_date_start)
	{
	  alertify.error('Please Select Date');
	  return false;
	}
	url = 'index.php?route=tagpos/fmdelivery/download_pdf&token=<?php echo $token; ?>';
	//alert(url);
	
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	if (filter_unit) {
		url += '&filter_unit=' + encodeURIComponent(filter_unit);
	}
	if (filter_store) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}
	
    if (filter_fm_name) {
		url += '&filter_fm_name=' + encodeURIComponent(filter_fm_name);
	}	
	//alert(url);
        window.open(url, '_blank');
	//location = url;
});


$('#button-details').on('click', function() {
	url = 'index.php?route=reportbcml/reconciliation/download_item_excel&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_unit = $('select[name=\'filter_unit\']').val(); 
	
	if (filter_unit != '') {
		url += '&filter_unit=' + encodeURIComponent(filter_unit);
	}	

         var filter_store = $('select[name=\'filter_store\']').val();
	
	if (filter_store != 0) {
		url += '&filter_store=' + encodeURIComponent(filter_store);
	}	
        window.open(url, '_blank');
	//location = url;
});

//--></script> 
        <script type="text/javascript">
/*
    var maxDate =  new Date();
    //var minDate = new Date(maxDate.getFullYear(), maxDate.getMonth(), +1); //one day next before month
$("#date_cr").datetimepicker({
  timepicker: false,
  pickTime: false,
  maxDate: maxDate,
  closeOnDateSelect: true
}); 
  $("#date_from").datetimepicker({
  timepicker: false,
  pickTime: false,
  maxDate: maxDate,
  closeOnDateSelect: true
}).on('dp.change', function (ev) {
   
   change_to_date();
});
$("#date_to").datetimepicker({
  showClear: true,
  timepicker: false,
  pickTime: false,
  
  maxDate: maxDate,
  closeOnDateSelect: true
}).on('dp.change', function (ev) {
   
   change_to_equal_date();
});
function change_to_equal_date()
{
var frm=$("#input-date-start").val(); 
var too=$("#input-date-end").val(); 
var fromTime = new Date(frm);
var minDate = new Date(fromTime.getFullYear(), fromTime.getMonth(), +1); //one day next before month
var maxDate =  new Date(fromTime.getFullYear(), fromTime.getMonth() +1, +0); // one day before next month
var date_to=convert(maxDate);
var toTime = new Date(too);

var millisecondsPerDay = 1000 * 60 * 60 * 24;
var millisBetween = toTime.getTime()-fromTime.getTime();
var days = millisBetween / millisecondsPerDay;

//alert(fromTime+' && '+toTime+' && '+ days);

    if(new Date(frm).getTime()>new Date(too).getTime())
    {
        $("#input-date-end").val(date_to);
        alertify.error('End date can not be less then start date');
    }
    if(days>31)
    {
        $("#input-date-end").val(date_to);
        alertify.error('There can be maximum 1 month difference between start date and end date');
    }
}
function change_to_date()
{
var frm=$("#input-date-start").val();
var fromTime = new Date(frm);
var minDate = new Date(fromTime.getFullYear(), fromTime.getMonth(), +1); //one day next before month
var maxDate =  new Date(fromTime.getFullYear(), fromTime.getMonth() +1, +0); // one day before next month
var date_to=convert(maxDate);

$("#input-date-end").val(date_to);
//$("#date_to").datetimepicker('update', "2017/09/20");
//$("#date_to").removeClass("date");

$("#date_to").datetimepicker({
  showClear: true,
  timepicker: false,
  pickTime: false,
  minDate: minDate, 
  maxDate: maxDate,
  closeOnDateSelect: true
});
    
}
$('#date_from').change(function(){
   $(this).next('input.datetimepicker').destroy(); 

   $("#date_to").next('input.datetimepicker').datetimepicker({
       minDate:$(this).val()
   });
});
function convert(str) {
    var date = new Date(str),
        mnth = ("0" + (date.getMonth()+1)).slice(-2),
        day  = ("0" + date.getDate()).slice(-2);
    return [ date.getFullYear(), mnth, day ].join("-");
}
*/
</script>
  <script type="text/javascript">

$('.date').datetimepicker({
	pickTime: false,
	maxDate: new Date()
});

</script></div>
<?php echo $footer; ?>