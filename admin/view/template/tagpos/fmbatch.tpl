<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo "FM Batch"; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">FM Batch</a></li>
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
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo "FM Batch"; ?></h3>
      </div>
      <div class="panel-body" style="min-height: 445px;">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-store">Batch</label>
                  <input type="text" name="batch" value=""  id="batch" class="form-control" onkeypress='return event.charCode >= 48 &amp;&amp; event.charCode <= 57 || event.keyCode==8 || event.keyCode==46'   required="required" /><br/>
				  <img id="search_img" src="view/image/processing_image.gif" style="height: 48px;width: 48px;display: none;" class="pull-right" />
				  <button type="button" id="button-filter" class="btn btn-primary pull-right" onclick="batchinv();">submit</button>
              </div> 
            </div>
          </div>
        </div>
       </div>
      </div>
	  
	  <!--for printing-->
	   <div style="display:none;">
	  <div  class="new_for_print"  id="final_print"></div>
	  </div>
<!--end printing-->
  <script type="text/javascript" src="view/javascript/pos/print/printThis.js"></script>
  <script type="text/javascript">
  function batchinv()
  { 
  var batch = document.getElementById('batch').value;
  $.ajax({ 
type: 'post',
url: 'index.php?route=tagpos/fmdelivery/getbatchinv&token='+getURLVar('token'),
data: 'batch='+batch,
//dataType: 'json',
cache: false,
beforeSend: function(){ 

$("#button-filter").hide();
$("#search_img").show();
},
success: function(data) {
$("#search_img").hide();
//alert(data);
alertify.confirm('Invoice No Count :-  '+data+'<br/><input type="radio" name="copy_type" checked id="center" value="Center Copy">&nbsp;&nbsp;Center Copy&nbsp;&nbsp;<input type="radio" name="copy_type" id="mill" value="Mill Copy">&nbsp;&nbsp;Mill Copy<br/>',


 //function(){ alertify.success('Ok') }
 function(e){ 
                if(e)
				{
					var copy_type='';
					if ($("#center").prop("checked")) {
						copy_type="1";
						
					}
					if ($("#mill").prop("checked")) {
						copy_type="2";
						
					}
					
					
					
                    //alertify.success(e); 
                    //return true;
					$.ajax({ 
					type: 'post',
					url: 'index.php?route=tagpos/fmdelivery/printallinvoice&token='+getURLVar('token')+'&copy_type='+copy_type,
					data: 'batch='+batch,
					dataType: 'json',
					cache: false,
					beforeSend: function(){ 

						$("#button-filter").hide();
						$("#search_img").show();
					},

					success: function(data) {
						//alert(url);
						$("#final_print").html('');	
						
						var finalhtml='';		
						for(var icount=0;icount<data.length;icount++)
						{				
							
							finalhtml+=(data[icount]['inv_html']+"<div style='page-break-after: always;'></div>");																								
						}
						
						$("#final_print").html(finalhtml);								
						//print 
						setTimeout(print, 3000);
		
						//end
						
					}
					});
						
                   
                }
				else
				{
                    alertify.error('Print Canceled'); 
					$("#button-filter").show();
					$("#search_img").hide();
                    return false;
                }
            }
 
 );

}
});

        
  }
  function print()
  {
	   
	  				 $(".new_for_print").printThis({
       debug: false, // show the iframe for debugging
       importCSS: true, // import parent page css
       printContainer: true, // print outer container/$.selector
       loadCSS: "view/javascript/pos/print/print.css", // load an additional css file
       pageTitle: "INVOICE", // add title to print page
       removeInline: false, // remove all inline styles
       cleardata: true
   });
    $("#button-filter").show();
					$("#search_img").hide();
  }
  </script>




 
<?php echo $footer; ?>