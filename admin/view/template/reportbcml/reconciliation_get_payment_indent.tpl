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
        <!--<button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download Excel</button>
        -->
     
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">


            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start">Letter Number </label>
                <div class="input-group" id="date_from">
                  <input type="text" name="filter_letter_number" value="<?php echo $filter_letter_number; ?>" placeholder="Letter Number"  id="input-filter_letter_number" class="form-control" />
                  </div>
              </div>
              
             
            </div>
	<div class="col-sm-6">
	<div class="form-group">
                <label class="control-label" for="input-date-end">Select Unit</label>
                <div class="input-group">
                  <span class="input-group-btn">
                      
                  <select name="filter_unit" id="input-unit" class="form-control">
                         <option value="" >SELECT UNIT</option>
			<?php foreach($units as $unit) {  ?>
				<option value="<?php echo $unit['unit_id']; ?>" <?php if ($unit['unit_id'] == $filter_unit) { ?> selected="selected" <?php } ?>><?php echo $unit['unit_name']; ?></option> 
			<?php } ?>
			<!--
                                          <option value="01" <?php if ('01' == $filter_unit) { ?> selected="selected" <?php } ?>>AJBAPUR</option>
			<option value="02" <?php if ('02' == $filter_unit) { ?> selected="selected" <?php } ?>>RUPAPUR</option>
			<option value="03" <?php if ('03' == $filter_unit) { ?> selected="selected" <?php } ?>>HARIYAWAN</option>
			<option value="04" <?php if ('04' == $filter_unit) { ?> selected="selected" <?php } ?>>LONI</option>
                                   	-->
                </select>
                  </span></div>
              </div>
				<button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i>Search</button>
            
            </div>
          </div>
        </div>
        <div class="table-responsive" style="overflow: hidden;">
			<?php
			if((!empty( $filter_unit)) && (!empty($filter_letter_number)))
			{
				?>
	<span style="font-weight: bold;">Total Amount : <?php echo number_format((float)$TotalTaggedValue, 2, '.', ''); ?></span> 
			<?php } ?>              
                           <br/><br/>
          <table class="table table-bordered" id="myTable" >
            <thead>
              <tr>
                <td class="text-right">Sl.No.</td>
                <td class="text-right">Invoice No</td>
                <td class="text-right">Invoice Date</td>
                <td class="text-right">Indent No</td>
	<td  class="text-right">Tagged Value</td>
   	 <td  class="text-right">User Name</td>
                
	
                <td class="text-right">Debit Note Status</td>
                
              </tr>            </thead>
            <tbody>
              <?php if ($orders) { if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; } ?>
              <?php foreach ($orders as $order) { //print_r($order); ?>
              <tr>
                <td class="text-right"><?php echo $aa; ?></td>
                <td class="text-right"><?php echo $order['InvoiceNo']; ?></td>
                <td class="text-right"><?php echo date('Y-m-d',strtotime($order['InvoiceDate'])); ?></td>
                <td class="text-right"><?php echo $order['IndentNo']; ?></td>
                <td class="text-right"><?php echo $order['TaggedValue']; ?></td>
	<td class="text-right"><?php echo $order['UserName']; ?></td>
                <td class="text-right"><?php 	if($order['DebitNoteStatus']==0)
					{
					echo "Un Released";
					}
					else if($order['DebitNoteStatus']==1)
					{
					echo "Released";
					}
					else
					{
					echo $order['DebitNoteStatus']; 
					}
					?>
</td>
                
              </tr>              <?php $aa++; } ?>
              <?php  } else { ?>
              <tr>
                <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
					
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right">
		<!--<span style="font-weight: bold;">Page Total :: Total Amount : <?php echo number_format((float)$total_tagged_amount, 2, '.', ''); ?></span>--> 
                           
                           <br/>
		<?php //echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>


<link rel="stylesheet" href="view/datatable/jquery.dataTables.min.css" />
<link rel="stylesheet" href="view/datatable/buttons.dataTables.min.css" />
 
    <script src="view/datatable/jquery.dataTables.min.js"></script>
    <script src="view/datatable/dataTables.buttons.min.js"></script>
    <script src="view/datatable/buttons.flash.min.js"></script>
    <script src="view/datatable/jszip.min.js"></script>
    <script src="view/datatable/pdfmake.min.js"></script>
    <script src="view/datatable/vfs_fonts.js"></script>
    <script src="view/datatable/buttons.html5.min.js"></script>
    <script src="view/datatable/buttons.print.min.js"></script>


  <script type="text/javascript">

//$(document).ready(function() { 
//alert('kk');
   
//} );

$('#button-filter').on('click', function() { //alert('kkk');
	url = 'index.php?route=reportbcml/reconciliation/getdebitnotedetail&token=<?php echo $token; ?>';	
	
              var filter_letter_number = $('#input-filter_letter_number').val();
	
	if (filter_letter_number) 
	{
		url += '&filter_letter_number=' + encodeURIComponent(filter_letter_number);
	}
	else
	{
		alertify.error('Please enter Letter number');
		return false;
	}
	var filter_unit = $('select[name=\'filter_unit\']').val();
	
	if (filter_unit) 
	{
		url += '&filter_unit=' + encodeURIComponent(filter_unit);
	}	
	else
	{
		alertify.error('Please select Unit');
		return false;
	}
	location = url;
});
 $('#myTable').DataTable( {
        dom: 'Bfrtip',
		lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
        ],
        buttons: [
            'excel', 'pdf','pageLength'
        ]
    } );
</script>
  <script type="text/javascript">

$('.date').datetimepicker({
	pickTime: false,
	maxDate: new Date()
});

</script></div>
<?php echo $footer; ?>
