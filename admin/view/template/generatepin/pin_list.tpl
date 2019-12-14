<?php echo $header; ?><?php echo $column_left;//print_r($results_n); ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Generate Pin</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
        <form  method="post" enctype="multipart/form-data" id="form-product">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> Generate Pin</h3>
        <!--<button type="button" id="button-download" class="btn btn-primary pull-right" style="margin-top: -8px !important;">
            Download</button>-->
      </div>
      <div class="panel-body">
        

        <div class="well">
          <div class="row">
           
            <div class="col-sm-12">
                <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-group"><?php //echo $entry_group; ?>Username</label>
					<input type="text" name="username" class="form-control" value="<?php echo $username; ?>" id="username" />
              </div>
                </div>
                
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Search</button>
           
            </div>
          </div>
        </div>


       <div class="table-responsive">

          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left">S. No.</td>
                <td class="text-left">Mobile Number</td>
                <td class="text-left">Name</td>
                
                <td class="text-left">Action</td>
                
                
                
              </tr>
            </thead>
            <tbody>
              <?php $total=0; if ($results) {  if($_GET["page"]=="") {$aa=1;} else if($_GET["page"]=="1") {$aa=1;}
              else{ $aa=(($_GET["page"]-1)*20)+1; } ?>
              <?php foreach ($users as $user) { //print_r($order); ?>
              <tr>
                <td class="text-left"><?php echo $aa; ?></td>
                <td class="text-left"><?php echo $user['username']; ?></td>
                <td class="text-left"><?php echo $user['firstname'].''.$user['lastname']; ?></td>
                <td class="text-left"><a type="button" class="btn btn-info btn-lg" value="<?php echo $user['user_id']; ?>"  onclick="return set_uid(<?php echo $user['user_id']; ?>);">
                    Reset Pin </a></td>
               
                
              </tr>
              <?php $tarr=explode('Rs.',$order['total']);$total=$total+$tarr[1];  $aa++; } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
            <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Generate Pin</h4>
      </div>
      <div class="modal-body">
        <label control-label" for="input-name1">Pin</label>
         <input type="hidden" name="user_id"   value="" id="user_id" class="form-control" />
         <input name="pin" value="" maxlength="6" onkeypress="return isNumber(event)" placeholder="Pin" id="pin" class="form-control" type="text">
        
      </div>
      <div class="modal-footer">
       <button type="button" class="btn btn-primary" id="button_submite">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-4 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-8 text-right">
	

         <?php echo $results; ?></div>
        </div>
      </div>
        </form>
    </div>
  </div>
 </div>

  <script type="text/javascript">
      function isNumber(evt)
       {
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
     function set_uid(user_id)
      {
          try{
                                     
                alertify.confirm('Are you sure ? You want to reset MPIN',
                function(e){ 
                    if(e){
                    $.ajax({ 
    type: 'post',
    url: 'index.php?route=generatepin/pin/insertpin&token=<?php echo $token; ?>',
    data: {user_id:user_id,user_group_id: '36'},
    cache: false,
    success: function(data) {
     //alert(data);
	 if(data!='0')
	 {
     alertify.success('PIN Sent Successfully');
	 location.reload();
	 }
	 else
	 {
		 alertify.error('Oops ! Some error occur.Please try again.'); 
                    return false;
	 }
     
                }
              });
                    return false;
                    
                }else{
                    alertify.error('Canceled by user'); 
                    return false;
                }
            }
                    
                        );
                 $("#alertify-ok").html('Continue');    
            }catch(e){alert(e);}
          return false;
      }  
      
    
    $('#button_submite').on('click', function() {
    var userid=$('#user_id').val(); 
    var p_in=$('#pin').val();
 
      
    $.ajax({ 
    type: 'post',
    url: 'index.php?route=generatepin/pin/insertpin&token=<?php echo $token; ?>',
    data: {userid:userid,p_in:p_in},
    cache: false,
    success: function(data) {
     //alert(data);
     //alert('PIn Insert Successfully');
     //location.reload(true);
                }
              });
 });
    
  
  
  
  
  
    
    <!--

$('.date').datetimepicker({
	pickTime: false
});
$('#button-filter').on('click', function() {
	url = 'index.php?route=generatepin/pin&token=<?php echo $token; ?>';
	
	var username = $('input[name=\'username\']').val();
	
	if (username) {
		url += '&username=' + encodeURIComponent(username);
	}
		
	location = url;
});

$('#button-download').on('click', function() {
    url = 'index.php?route=generatepin/pin/download_excel&token=<?php echo $token; ?>';
    	var username = $('input[name=\'username\']').val();
	
	if (username) {
		url += '&username=' + encodeURIComponent(username);
	}
    
    //location = url;
        window.open(url, '_blank');
});




//--></script>
<?php echo $footer; ?>