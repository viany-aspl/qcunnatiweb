
<div id="grid">
  <div class="hold_form">            
      <div class="panel-heading">
          <h3 > <?php echo $text_form; ?></h3>      
         <div class="pull-right">                    
                        <button id="add_customer" class="button">Add</button>                    
                </div>
          <hr>
          <div class="message_wrapper"></div>  
          <div id="myProgress" style="display:none;">
  <div id="myBar"></div>
</div>
          
        </div>
      
              <div class="row">                
                <div class="span8">                  
                    
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-customer-group"><?php echo $entry_customer_group; ?></label>
                        <div data-role="input-control" class="input-control select">
                          <select name="customer_group_id" id="customer_group_id" class="form-control">
                            <?php foreach ($customer_groups as $customer_group) { ?>
                            <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
                            <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
                        <div data-role="input-control" class="input-control text">
                          <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="firstname" class="form-control" />
                          <?php if ($error_firstname) { ?>
                          <div class="text-danger"><?php echo $error_firstname; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
                        <div data-role="input-control" class="input-control text">
                          <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="lastname" class="form-control" />
                          <?php if ($error_lastname) { ?>
                          <div class="text-danger"><?php echo $error_lastname; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                     
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
                        <div data-role="input-control" class="input-control text">
                          <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="telephone" class="form-control" />
                          <?php if ($error_telephone) { ?>
                          <div class="text-danger"><?php echo $error_telephone; ?></div>
                          <?php  } ?>
                        </div>
                      </div>
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-card"><?php echo $entry_card; ?></label>
                        <div data-role="input-control" class="input-control text">
                          <input type="text" name="card" value="<?php echo $card; ?>" placeholder="<?php echo $entry_card; ?>" id="card" class="form-control" />
                          <?php if ($error_card) { ?>
                          <div class="text-danger"><?php echo $error_card; ?></div>
                          <?php  } ?>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-village"><?php echo $entry_village; ?></label>
                        <div data-role="input-control" class="input-control text">
                          <input type="text" name="village" value="<?php echo $village; ?>" placeholder="<?php echo $entry_village; ?>" id="village" class="form-control" />
                        </div>
                      </div>
                                                                                                           
                                                         
                </div>
              </div>
      
    
  
  </div></div>

<script type="text/javascript">
    $('#add_customer').click(function(){
        try{
            document.getElementById("myProgress").style.display = 'block';
 var elem = document.getElementById("myBar"); 
 var width = 10;
  width+=30; 
      elem.style.width = width + '%'; 
 //add customer
 $.post('index.php?route=pos/pos/addcustomer&token=<?php echo $token; ?>',{ firstname: $('#firstname').val() , lastname: $('#lastname').val() , telephone: $('#telephone').val() , village: $('#village').val(), customer_group_id:$('#customer_group_id').val(),card:$('#card').val()}, function(data){
 
 
        var data = JSON.parse(data);
     
     if(data['error']){
         $('.message_wrapper').html('<div class="warning">'+data['error']+'</div>');
         document.getElementById("myProgress").style.display = 'none'; 
     }
     
     if(data['success']){
         $('.fancybox-close').trigger('click');
         width=100;
      elem.style.width = width + '%';    
     }
     
     
 });
        }catch(e){alert(e);}
        
//end customer        
});
</script>