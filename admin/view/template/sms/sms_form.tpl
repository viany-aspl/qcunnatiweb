<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-customer"  data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
       <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i><?php echo $success; ?>
      <button type="button" form="form-backup" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-customer" onsubmit="return validForms();" class="form-horizontal">
            <div class="row">
                  <div class="tab-content">
                    <div class="tab-pane active" id="tab-customer">
                        <div class="col-sm-6"> 
                            <div class="form-group required">
                        <label class="col-sm-3 control-label" for="input-username"><?php echo $entry_username; ?></label>
                        <div class="col-sm-6">
                          <input type="text" name="username" required  placeholder="<?php echo $entry_username; ?>" id="uname" class="form-control" />
                        </div>
                        </div>
                      </div>
                          <div class="col-sm-6">
                              <div class="form-group required">
                        <label class="col-sm-3 control-label" for="input-password"><?php echo $entry_password; ?></label>
                        <div class="col-sm-6">
                          <input type="password" name="password" required placeholder="<?php echo $entry_password; ?>" id="password"  class="form-control" />
                         </div>
                        </div>
                      </div>
                        <div class="col-sm-6"> 
                            <div class="form-group required">
                        <label class="col-sm-3 control-label" for="input-hname"><?php echo $h_name; ?></label>
                        <div class="col-sm-6">
                          <input type="url" name="url" required  placeholder="<?php echo $h_name; ?>" id="hname" class="form-control" />
                            <!---<span id="NewUrl"></span>--->
                        </div>
                        </div>
                      </div>
                          <div class="col-sm-6">
                              <div class="form-group required">
                        <label class="col-sm-3 control-label" for="input-dname"><?php echo $d_name; ?></label>
                        <div class="col-sm-6">
                          <input type="text" name="displayname" required  placeholder="<?php echo $d_name; ?>" id="displayname"  class="form-control" onkeyup="var start =this.selectionStart;var end = this.selectionEnd;this.value = this.value.toUpperCase();this.setSelectionRange(start, end);"/>
                         </div>
                        </div>
                      </div>
                        <div class="col-sm-6"> 
                            <div class="form-group required">
                        <label class="col-sm-3 control-label" for="input-operator"><?php echo $operator; ?></label>
                        <div class="col-sm-6">
                          <input type="text" name="operator"  required placeholder="<?php echo $operator; ?>" id="operator" class="form-control" />
                        </div>
                        </div>
                      </div>
                         <div class="col-sm-6"> 
                            <div class="form-group required">
                        <label class="col-sm-3 control-label" for="input-qtype"><?php echo $q_type; ?></label>
                        <div class="col-sm-6">
                            <select name="query_type" id="qtype"  class="form-control">
                                <option value="">Query Type</option>
                                <option value="GET"> GET</option>
                                <option value="POST"> POST</option>
                            </select>
                        </div>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
            <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab">Form</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="row">
                <div class="col-sm-12">
                  <div class="tab-content">
                    
                <table id="myTable" class=" table order-list">
                <thead>
                    <tr>
                        <td><?php echo $entry_name; ?></td>
                        <td><?php echo $entry_value; ?></td>
                       
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="col-sm-4">
                        <input type="text" name="name[]" id="first_name" placeholder="<?php echo $entry_name; ?>" class="form-control" />
                    </td>
                    <td class="col-sm-4">
                        <input type="text" name="value[]" id="value" placeholder="<?php echo $entry_value; ?>"  class="form-control"/>
                    </td>
                   
                    <td class="col-sm-1">  
                        <button type="button" class="btn btn-lg btn-block" class="btn btn-primary" data-toggle="tooltip" id="addrow"  style="background-color:#1870A8;color:#fff;" /><i class="fa fa-plus-circle"></i></button>
                    </td>
                        <td class="col-sm-2"><a class="deleteRow"></a>

                        </td>
                    </tr>
                </tbody>

            </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </form>
          </div>  
          </div>
        
      </div>
    </div>
  
<?php echo $footer; ?>

<link rel="stylesheet" href="https://rawgit.com/anhr/InputKeyFilter/master/InputKeyFilter.css" type="text/css">		
<script type="text/javascript" src="https://rawgit.com/anhr/InputKeyFilter/master/Common.js"></script>
<script type="text/javascript" src="https://rawgit.com/anhr/InputKeyFilter/master/InputKeyFilter.js"></script>
	
<script type="text/javascript">
$(document).ready(function () {
    var counter = 0;

    $("#addrow").on("click", function () {
        var newRow = $("<tr>");
        var cols = "";

        cols += '<td><input type="text" id="name' + counter +'" class="form-control" placeholder="Name" name="name[]"/></td>';
        cols += '<td><input type="text" id="value' + counter +'" class="form-control" placeholder="Value" name="value[]"/></td>';
      
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger"  value="Delete"></td>';
        newRow.append(cols);
        $("table.order-list").append(newRow);
        counter++;
    });

    $("table.order-list").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        counter -= 1
    });

});

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
 
   function validForms(){
 
            var fname=document.getElementById('uname').value;
            var pwd=document.getElementById('password').value;
            var hname=document.getElementById('hname').value;
            var dname=document.getElementById('displayname').value;
            var operator=document.getElementById('operator').value;
            var qtype=document.getElementById('qtype').value;
            var first_name=document.getElementById('first_name').value;
            var value=document.getElementById('value').value;
         
                if(fname==''){
                    alert('Username required');
                    return false;
                }
                
             else if(pwd==''){
                    alert('Password required');
                    return false;
                }
                
               else if(pwd.length>0 && pwd.length<6){
                  alert('Password must be greater than 6 character');
                  return false;
                 }
                 else if(hname==''){
                    alert('Hostname required');
                    return false;
                }
                 else if(dname==''){
                    alert('Display name required');
                    return false;
                }
                
                 else if(operator==''){
                    alert('Operator required');
                    return false;
                }
                
                  else if(qtype==''){
                    alert('Query Type required');
                    return false;
                }
                
                else if(first_name==''){
                alert('Name required');
                return false;
               }
               
               
              else if(value==''){
                alert('value required');
                return false;
               }
               
                
                else{
                    return true;
                }
            }
    
	CreateUrlFilter("hname", function(event){//onChange event
			inputKeyFilter.RemoveMyTooltip();
			var elementNewInteger = document.getElementById("NewUrl");
			elementNewInteger.innerHTML = this.value;
		}
		
		//onblur event. Use this function if you want set focus to the input element again if input value is NaN. (empty or invalid)
		, function(event){ this.ikf.customFilter(this); }
	);
    
    
    
    function forceInputUppercase(e)
  {
    var start = e.target.selectionStart;
    var end = e.target.selectionEnd;
    e.target.value = e.target.value.toUpperCase();
    e.target.setSelectionRange(start, end);
  }

  document.getElementById("field1").addEventListener("keyup", forceInputUppercase, false);
  document.getElementById("field2").addEventListener("keyup", forceInputUppercase, false);

</script>