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
<form action="<?php echo $addgeo; ?>" method="post" id="form-addgeo" class="form-horizontal" data-toggle="validator" role="form">

<ul id="geo" class="nav nav-tabs nav-justified">
<li class="<?php echo $tab1; ?>"><a href="#tab-nation" data-toggle="tab" onclick="return active_tab('tab1');" >Cash Accepted</a></li>
<li class="<?php echo $tab2; ?>"><a href="#tab-zone" data-toggle="tab"onclick="return active_tab('tab2');" >Cash Deposited</a></li>

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
                <label class="control-label" >Select CE <?php echo $tab1; ?></label>
                <div class="input-group">
                 
                  <span class="input-group-btn">
                      
                  <select name="filter_user" id="filter_user" class="form-control">
                   <option selected="selected" value="">SELECT CE</option>
	<?php foreach($allces as $ce){ ?>
		 <option value="<?php echo $ce['user_id']; ?>" <?php if($filter_user==$ce['user_id']){ ?> selected="selected" <?php } ?> ><?php echo $ce['firstname']."  ".$ce['lastname']; ?></option>
	<?php } ?>
	<!--
                  <option value="110" <?php if($filter_user=="110"){ ?> selected="selected" <?php } ?> >Amit Kumar</option>
                  <option value="111" <?php if($filter_user=="111"){ ?> selected="selected" <?php } ?> >Anil Kumar</option>
                  <option value="106" <?php if($filter_user=="106"){ ?> selected="selected" <?php } ?> >Chitranjan Mishra</option>   
                  <option value="77" <?php if($filter_user=="77"){ ?> selected="selected" <?php } ?> >Kunwar Rana</option>
                  <option value="9" <?php if($filter_user=="9"){ ?> selected="selected" <?php } ?> >Om prakash</option>
                  <option value="108" <?php if($filter_user=="108"){ ?> selected="selected" <?php } ?> >Surjeet Mishra</option>-->
                  
                  
                  
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
              
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
</div>
</div>

</div>
<div class="tab-content">

<!--*************************nation***********************-->
<div class="tab-pane <?php echo $tab1; ?>" id="tab-nation">
<div class="panel-body">
           <button type="button" id="button-download1" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download</button>
</div>
<div class="panel-body">

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

<!--*************************end nation***********************-->

<!--*************************Zone***********************-->
<div class="tab-pane <?php echo $tab2; ?>" id="tab-zone">
<div class="panel-body">
           <button type="button" id="button-download2" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download</button>
</div>

<div class="panel-body">

<div class="table-responsive">
<!--<span style="font-weight: bold;">Total  Amount :: HDFC : <?php echo $hdfc_total2; ?></span> &nbsp; |  &nbsp;
	<span style="font-weight: bold;"> ICICI : <?php echo $ICICI_total2; ?></span> &nbsp; | &nbsp; 
	<span style="font-weight: bold;"> SBI : <?php echo $State_Bank_of_India_total2; ?></span> &nbsp;  | &nbsp; 
	
	<span style="font-weight: bold;"> TAGGED BILLS : <?php echo $TAGGED_BILLS_total2; ?></span>
              <br/><br/>-->
<table class="table table-bordered table-hover">
<thead>
              <tr>
                <td class="text-left">SI ID </td>
                <td class="text-left">Runner name</td>
               
                <td class="text-left">Bank</td>
	 <td class="text-left">Branch</td>
                <td class="text-left">Deposit date </td>
                <td class="text-left">Amount </td>
	<td class="text-right">Status</td>
	
              </tr>
            </thead>
<tbody>
              <?php if ($orders) { $total=0; ?>
              <?php foreach ($records as $order) { ?>
              <tr>
                <td class="text-left"><?php echo $order['SIID']; ?></td>
                <td class="text-left"><?php echo $order['runner_name']; ?></td>
                <td class="text-left"><?php echo $order['bank']; ?></td>
                <td class="text-left"><?php echo $order['branch']; ?></td>
                <td class="text-left"><?php echo $order['deposit_date']; ?></td>
                <td class="text-left"><?php echo $order['amount']; ?></td>
	<td class="text-right"><?php if($order['status']=="0") { echo "<span style='color: #CC760F;'>Pending</span>"; } else if($order['status']=="1") { echo "<span style='color: #2F9217;'>Accepted</span>"; } else if($order['status']=="2") { echo "<span style='color: #C0250C;'>Rejected</span>"; } ?></td>
	
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
<div class="col-sm-6 text-left"><?php echo $pagination2; ?></div>
<div class="col-sm-6 text-right"><?php echo $results2; ?></div>
</div>
</div>
</div>
<!--*************************End Zone***********************-->



</div>


</div>
</div>


</div>
</div>

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
<input type="text" name="tab_active_" id="tab_active_" value="tab1" />
<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=reportdscl/ce&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_store = $('#filter_store').val();
	
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
              var tabbb=$('#tab_active_').html();
              if(tabbb=="")
              {
                url += '&tab=tab1';
              }
              else
              {
                  url += '&tab=' + encodeURIComponent(tabbb);
              }
	location = url;
});
//--></script> 
<script type="text/javascript"><!--
$('#button-download1').on('click', function() {
    url = 'index.php?route=reportdscl/ce/download_excel&token=<?php echo $token; ?>';
    
    var filter_date_start = $('input[name=\'filter_date_start\']').val();
    
    if (filter_date_start) {
        url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
    }

    var filter_date_end = $('input[name=\'filter_date_end\']').val();
    
    if (filter_date_end) {
        url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
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
$('#button-download2').on('click', function() {
    url = 'index.php?route=reportdscl/ce/download_excel_runner&token=<?php echo $token; ?>';
    
    var filter_date_start = $('input[name=\'filter_date_start\']').val();
    
    if (filter_date_start) {
        url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
    }

    var filter_date_end = $('input[name=\'filter_date_end\']').val();
    
    if (filter_date_end) {
        url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
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
