<?php echo $header; ?>
<?php echo $column_left; ?>
  

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        
          <button type="button" onclick="backbtn()"   data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i></button>
      </div>
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
  	<button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download Excel</button>
      </div>
      <div class="panel-body">
  
        <div class="well">
           <div class="row">
          
            
            <div class="col-sm-3 form-group required">
              
              <div class=" input-group date">
              <input class="form-control" data-date-format="YYYY-MM-DD" value="<?php echo $lastfromdate; ?>" type="text" id="Group_Name" onchange="clear_Group_Name()" name="from_date"  placeholder="From Date"/>              
              <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
              </div>
              </div>
              
              <div class="col-sm-3 form-group required">
              <div class="input-group date">
              <input class="form-control" type="text" value="<?php echo $lasttodate; ?>" data-date-format="YYYY-MM-DD" id="Group_Name" onchange="clear_Group_Name()" name="to_date"  placeholder="To Date"/>
              
               <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
              </div>
            </div>
              <div class="col-sm-3 form-group required">
              <div class="">
             <input type="text" name="mobile" id="mobile" id="mobile" class="form-control" value="<?php echo $lastmobile; ?>" placeholder="Mobile" >
              </div>
             </div>     
	<div class="col-sm-3">
              <div class="form-group">
               
                <div class="input-group">
                 
                  <span class="input-group-btn">
                      
                  <select name="filter_store" id="input-store" class="form-control">
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
              
            </div>
                 <div class="form-group required">   
              <div class="">
                   
                  <button  id="searchbtn" class="btn btn-primary pull-right"><i class="fa fa-search"></i>  Search</button>
                 
              </div>
                    
          </div>
               
          
          </div>
            
           
        </div>
    
      </div>
    </div>
   <div class="panel-body">
  
        <div class="well">
           <div class="row">
<div class="col-sm-6">
                                <div class="widget-body" style="border:1px solid #dadada; border-radius:3px; margin-bottom:10px; padding:10px 0px">
                                    <h4 class="text-center">Recharge Status</h4>
                                    <div class="widget-main">
                <div id="piechart-placeholder"></div>
                        <div class="hr hr8 hr-double"></div>
                                   <div class="clearfix"></div>
                
                 </div>
               </div>
                           </div>
</div>
               </div>
                           </div>

    <div   class="panel panel-default">
        <div class="panel-body">
          <?php 
	$total_r=0;
            foreach($countresults as $countt)
            {
              $total_r=$total_r+$countt["count_n"];
              ?>
           <span style="font-weight: bold;">  <?php echo $countt["ResSerSts"]; ?> ::  </span> <span> <?php echo $countt["count_n"]; ?> </span>  ||  
           <?php 
            }

$color=array("#68BC31","#2091CF","#AF4E96","#DA5430","#FEE074"," #943126","#9b59b6","#f1c40f","#2c3e50","#f39c12","#4a235a","#ff0000","#ccff33","#1aff1a","#3333ff","#ffff1a","#ff0000");
    $i=0;
    
     $totalss=$total_r;
    
    foreach ($countresults as $geos) { 
        
         $percentage=($geos['count_n']/$totalss)*100;
         $percentage=number_format((float)$percentage, 2, '.', '');
        
$pie_data[]=array('label'=>"'".$geos["ResSerSts"]."'",'data'=>$percentage,'color'=>$color[$i],'count_n'=>$geos['count_n']);
$i++;
    }

$json_data=json_encode($pie_data);  
//print_r($json_data);

           ?>
 <span style="font-weight: bold;">  Total ::  </span> <span> <?php echo $total_r; ?> </span>
<br/><br/>
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
            <td class="text-center" style="font-weight: bold">Mobile</td>
            <td class="text-center" style="font-weight: bold">Recharge Amount</td>
            <td class="text-center" style="font-weight: bold">Order Id </td>
             <td class="text-center" style="font-weight: bold">Operator Name</td>
	<td class="text-center" style="font-weight: bold">Product name </td>
             <td class="text-center" style="font-weight: bold">Product quantity </td>
	<td class="text-center" style="font-weight: bold">Recharge status</td>
	<td class="text-center" style="font-weight: bold">Store name</td>
	<td class="text-center" style="font-weight: bold">Scheme name</td>
	<td class="text-center" style="font-weight: bold">Recharge date</td>
	<td class="text-center" style="font-weight: bold">Recharge time</td>
          </tr>
            <tr>
              </thead>
              <tbody>
                <?php if ($geo) { ?>
                <?php foreach ($geo as $resultt) { //print_r($resultt); ?>
                
          <td class="text-center">
              <?php echo $resultt['mobile']; ?>
            </td>
            <td class="text-center">
              <?php echo $resultt['recharge_amount']; ?>
            </td>                  
            <td class="text-center">
              <?php echo $resultt['order_id'];?>
            </td>
             <td class="text-center">
              <?php echo $resultt['operator_name'];?>
            </td>
	<td class="text-center">
              <?php echo $resultt['product_name'];?>
            </td>
            <td class="text-center">
              <?php echo $resultt['product_quantity'];?>
            </td>
            <td class="text-center">
              <?php echo $resultt['ResSerSts'];?>
            </td>
            <td class="text-center">
              <?php echo $resultt['store_name'];?>
            </td>
            <td class="text-center">
              <?php echo $resultt['scheme_name'];?>
            </td>
	<td class="text-center">
              <?php echo $resultt['recharge_date'];?>
            </td>
	<td class="text-center">
              <?php echo $resultt['recharge_time'];?>
            </td>
        
          </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
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
</div> 

<?php echo $footer; ?>
<script src="./view/javascript/jquery.flot.min.js"></script>
<script src="./view/javascript/jquery.flot.pie.min.js"></script>
<script src="./view/javascript/jquery.flot.resize.min.js"></script>


<script>
//jQuery(function($) { 
//alert('kkk');
                $('.easy-pie-chart.percentage').each(function(){
                    var $box = $(this).closest('.infobox');
                    var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css('color') : 'rgba(255,255,255,0.95)');
                    var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
                    var size = parseInt($(this).data('size')) || 50;
                    $(this).easyPieChart({
                        barColor: barColor,
                        trackColor: trackColor,
                        scaleColor: false,
                        lineCap: 'butt',
                        lineWidth: parseInt(size/10),
                        animate: ace.vars['old_ie'] ? false : 1000,
                        size: size
                    });
                })
            
                
              $.resize.throttleWindow = false;
            
              var placeholder = $('#piechart-placeholder').css({'width':'90%' , 'min-height':'150px'});
              var data = <?php echo $json_data; ?>;
              function drawPieChart(placeholder, data, position) {
                   $.plot(placeholder, data, {
                    series: {
                        pie: {
                            show: true,
                            tilt:0.8,
                            highlight: {
                                opacity: 0.25
                            },
                            stroke: {
                                color: '#fff',
                                width: 2
                            },
                            startAngle: 2
                        }
                    },
                    legend: {
                        show: true,
                        position: position || "ne",
                        labelBoxBorderColor: null,
                        margin:[-30,15]
                    }
                    ,
                    grid: {
                        hoverable: true,
                        clickable: true
                    }
                 })
             }
             drawPieChart(placeholder, data);
            
             /**
             we saved the drawing function and the data to redraw with different position later when switching to RTL mode dynamically
             so that's not needed actually.
             */
             placeholder.data('chart', data);
             placeholder.data('draw', drawPieChart);
            
            
              //pie chart tooltip example
              var $tooltip = $("<div class='tooltip top in'><div class='tooltip-inner'></div></div>").hide().appendTo('body');
              var previousPoint = null;
            
              placeholder.on('plothover', function (event, pos, item) {
                if(item) {
                    if (previousPoint != item.seriesIndex) {
                        previousPoint = item.seriesIndex;
                        var tip = item.series['label'] + " : " + (item.series['percent']).toFixed(2)+'%' + ' | Count : '+item.series['count_n'];
                        $tooltip.show().children(0).text(tip);
                    }
                    $tooltip.css({top:pos.pageY + 10, left:pos.pageX + 10});
                } else {
                    $tooltip.hide();
                    previousPoint = null;
                }
                
             });
              
           // })
                        

</script>
<script type="text/javascript"><!--
$('.date').datetimepicker({
    pickTime: false
});
//--></script>
<script type="text/javascript">
    
 $('#searchbtn').on('click', function() {

   
    url = 'index.php?route=report/rechargereport&token='+getURLVar('token');
    
    var filter_fdate_id = $('input[name=\'from_date\']').val();
    var filter_tdate_id = $('input[name=\'to_date\']').val();
    var filter_mobile = $('#mobile').val();
    var filter_store= $("#input-store").val(); 
    if (filter_fdate_id) {
        url += '&filter_from_date=' + encodeURIComponent(filter_fdate_id);
       
    }
    if (filter_tdate_id) {
       
        url += '&filter_to_date=' + encodeURIComponent(filter_tdate_id);
    }  
  
    if(filter_mobile) {
        
        url += '&filter_mobile=' + encodeURIComponent(filter_mobile);
    }
   if(filter_store) {
        
        url += '&filter_store=' + encodeURIComponent(filter_store);
    }
                
    location = url;
});

// download
$('#button-download').on('click', function() {

    url = 'index.php?route=report/rechargereport/downloadexcel&token='+getURLVar('token');
    
    var filter_fdate_id = $('input[name=\'from_date\']').val();
    var filter_tdate_id = $('input[name=\'to_date\']').val();
    var filter_mobile = $('#mobile').val();
    var filter_store= $("#input-store").val(); 
    if (filter_fdate_id) {
        url += '&filter_from_date=' + encodeURIComponent(filter_fdate_id);
       
    }
    if (filter_tdate_id) {
       
        url += '&filter_to_date=' + encodeURIComponent(filter_tdate_id);
    }  
  
    if(filter_mobile) {
        
        url += '&filter_mobile=' + encodeURIComponent(filter_mobile);
    }
   if(filter_store) {
        
        url += '&filter_store=' + encodeURIComponent(filter_store);
    }
                
    location = url;
});
//end download

</script> 