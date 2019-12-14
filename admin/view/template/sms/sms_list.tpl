<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid" >
      <div class="pull-right" style="display:none;"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button  type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-customer').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
      </div>
      <div class="panel-body">
       
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-customer">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left">
                   <?php echo $column_name; ?>
                    </td>
                  <td class="text-left">
                    <?php echo $column_operator; ?></td>
                  <td class="text-left">
                    <?php echo $qtype; ?>
                   </td>
                  
                  <!-- <td class="text-left">
                    <?php echo $tsms; ?>
                   </td>-->
                  
                  
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($getsms) { ?>
                <?php foreach ($getsms as $sms) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($sms['SID'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $sms['SID']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $sms['SID']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $sms['HOSTNAME']; ?></td>
                  <td class="text-left"><?php echo $sms['OPERATOR']; ?></td>
                  <td class="text-left"><?php echo $sms['QUERY_TYPE']; ?></td>
                 <!-- <td class="text-left" data-toggle="modal" data-target="#myModal" style="cursor: pointer;"
                      value="<?php echo $sms['SID']; ?>" onclick="return set_sid(<?php echo $sms['SID']; ?>);">
                    
                          Send</td> -->
                
                  <td class="text-left">
                <span style="display: none;" id="processing_txt<?php echo $sms['SID']; ?>" >Please Wait...</span> 
               
                  <label class="switch" id="switch<?php echo $sms['SID']; ?>">
                      <input type="checkbox" onclick="change_status('<?php echo $sms['SID']; ?>',this.value);" value="<?php echo $sms['ACT']; ?>" <?php if($sms['ACT']=='1'){ ?> checked <?php } ?> id="billing_control<?php echo $sms['SID']; ?>" >
                        
                        <span class="slider round"></span>
                    </label>
                       
                      
                      
             </td>
                   
               
                </tr>
                <?php } }  else { ?>
              
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                </tr>
                <?php  } ?>
              </tbody>
            </table>
          </div>
        </form>
          
          
        <!--------------------------Model Start------------------------------------->
        
         <div class="container">
                  <div class="modal fade" id="myModal" role="dialog">
                  <div class="modal-dialog">
                    <!-- Modal content-->
                 <div class="modal-content">
                 <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title"><?php echo $heading_title; ?></h4>
                 </div>
                     <form>
                     
                <div class="modal-body" >
                    
                    <div class="row">
                    <div class="tab-content">
                    <div class="tab-pane active" id="tab-customer">
                        <div class="col-sm-12"> 
                        <div class="form-group required">
                        <label class="col-sm-4 control-label"  for="input-mobile"><?php echo $entry_mobile; ?></label>
                        <div class="col-sm-8">
                            <input type="text" name="mobile_number" onchange="clear_mobile_number()" maxlength="10"  placeholder="<?php echo $entry_mobile; ?>" id="mobile_number" class="form-control" />
                          <input type="hidden" name="sid"   value="" id="sid" class="form-control" />
                        <p id="mobile_number_p"  style="display:none;color:red;">Required Mobile Number</p>
                        </div>
                        </div>
                      </div>
                        
                        <div class="col-sm-12" style="margin: 10px 0 0 0;"> 
                        <div class="form-group required">
                        <label class="col-sm-4 control-label" for="input-message"><?php echo $entry_message; ?></label>
                        <div class="col-sm-8">
                          <input type="text" name="message" onchange="clear_message()" placeholder="<?php echo $entry_message; ?>" id="message" class="form-control" />
                        <p id="message_p"  style="display:none;color:red;">Required Message</p>
                        </div>
                        </div>
                      </div>
                        
                        
                </div>
                </div>
                </div>
                </div>
                <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                     <button type="button"  id="button_save" class="btn btn-primary">Save changes</button>
             
                </div>
                     </form>
                </div>
      
               </div>
                </div>
                  </div>
              
          <!--------------------------Model End------------------------------------->
          
          
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
    
    
   <style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style> 
    
    
  <script type="text/javascript">
      function set_sid(sid)
      {
          
          $("#sid").val(sid);
          //alert(sid);
          return false;
      }
function change_status(sid,act) {
       //alert(sid+'-'+status);
       //alert(status);
      alertify.confirm('Are you Sure ! You want to change the status?', function (e)
    {
    if (e)
    {  
      $.ajax({
        url: 'index.php?route=sms/smslist/updatestatus&token=<?php echo $token; ?>&sid='+encodeURIComponent(sid),
        
        beforeSend: function() {
            $("#switch"+sid).hide();
            //$("#processing_img").show();
            $("#processing_txt"+sid).show();
            
        },
        complete: function() {
            alertify.success('Updated Successfully');
        },
        success: function(html) {
            location.reload();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
 
    } ///////if of confirm end here
    else
    {
        if(act=='0')
        {
            $('#billing_control'+sid).prop('checked', false);
        }
        if(act=='1')
        {
            $('#billing_control'+sid).prop('checked', true);
        }
        //location.reload();
        alertify.error('Canceled SMS');
        return false;
    }
    });
}
 
 
 
 
function clear_mobile_number()
 {
    $('#mobile_number_p').hide();
 }

function clear_message()
 {
    $('#mobile_number_p').hide();
 }

$('#button_save').on('click', function() {
 //alert(button_save);
    if ($('#mobile_number').val().length ===0) {
        
        $('#mobile_number_p').show();
     return false;
    }
    
     else if ($('#message').val().length ===0) {
        
        $('#message_p').show();
     return false;
    }
 
    var mobile_number=$('#mobile_number').val();
    var message=$('#message').val();
    var sid=$('#sid').val();
     //alert(sid);
    $.ajax({ 
    type: 'post',
    url: 'index.php?route=sms/smslist/smsdetails&token=<?php echo $token; ?>',
    data: {mobile_number:mobile_number,message:message,sid:sid},
    cache: false,
    success: function(data) {
    alert(data);
    location.reload(true);
                }
              });
 });  
</script> 
  
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?> 
