<div id="content">
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-body">
        <form action="" method="post" id="form-drvrDtl" class="form-horizontal">
          <input type="hidden" name ="order_id" value="<?php echo $invoice_no;  ?>" />
		  <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo "Dispatch  Form";?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="row">
                <div class="col-sm-10">
                  <div class="tab-content">
                    <div class="tab-pane active" id="tab-customer">
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-supplier-group"><?php echo "Vehicle Select";  required?></label>
                        <div class="col-sm-10">
<?php $vehicle_type = array("1"=>"2 Wheeler", "2"=>"3 Wheeler(Auto)" , "3"=>"4 Wheeler- Mini Van", "4"=>"4 Wheeler - Mini Truck", "5"=>"4 Wheeler- Big Truck" , "6"=>"ZI Pick-Up by ".$zi_name." ");?>
                          <select name="vehicle_type" id="vehicle_type" class="form-control">
                            <option value="">Select Vehicle</option>
                            <?php foreach  ($vehicle_type as $ky=>$vl)  { ?>
                            <option value="<?php echo $ky; ?>"<?php if($this->request->post['vehicle_type'] == $ky){?>selected="selected"<?php } ?>><?php echo $vl; ?></option>
							<?php
							}
							?>
						  </select>
                        </div>
                      </div>
                        
                      <div id="vhcl_dtl">
                        <div class="form-group required">
                          <label class="col-sm-2 control-label" for="input-driver_name"><?php echo "Driver Name"; ?></label>
                          <div class="col-sm-10">
                            <input maxlength="50" type="text" name="driver_name" value="<?php echo $this->request0->post['driver_name'];?>" placeholder="Driver Name" id="input-driver_name" class="form-control" required />
                            <?php if (isset($_SESSION['error_driver_name'])) { ?>
                            <div class="text-danger"><?php echo $_SESSION['error_driver_name']; unset($_SESSION['error_driver_name']); ?></div>
                            <?php } ?>
                          </div>
                        </div>

                           <div class="form-group required">
                          <label class="col-sm-2 control-label" for="input-mobile"><?php echo "Driver Mobile Number"; ?></label>
                          <div class="col-sm-10">
                            <input maxlength="10" type="text" name="mobile" value="<?php if(isset($supplier_info['mobile'])) echo $supplier_info['mobile'];?>" placeholder="<?php echo "Driver Mobile Number"; ?>" id="mobile" class="form-control" required  pattern="\d*" title="Enter Valid Mobile Numbers only" />
                            <?php if (isset($_SESSION['error_mobile'])) { ?>
                            <div class="text-danger"><?php echo $_SESSION['error_mobile']; unset($_SESSION['error_mobile']); ?></div>
                            <?php  } ?>
                          </div>
                        </div>

                        <div class="form-group required">
                          <label class="col-sm-2 control-label" for="input-vehicle_number"><?php echo "Vehicle Number"; ?></label>
                          <div class="col-sm-10">
                            <input maxlength="30" type="text" name="vehicle_number" value="<?php if(isset($supplier_info['vehicle_number'])) echo $supplier_info['vehicle_number'];?>" placeholder="<?php echo "Vehicle Number"; ?>" id="input-vehicle_number" class="form-control" required/>
                           </div>
                        </div>
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
  </div>
</div> 
 <script type="text/javascript" language="javascript">
    jQuery('#vehicle_type').on('change',function(){
        if($(this).val()=='6'){
            jQuery('#vhcl_dtl').hide();
        }else{
            jQuery('#vhcl_dtl').show();        
        }
    });

    
</script>