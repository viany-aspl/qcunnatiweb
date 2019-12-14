<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
          <button type="button" onclick="return checkFileSize();" form="form-setting" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
          <h3 class="panel-title"><i class="fa fa-pencil"></i> Store documents <br/></h3>
          <i class="pull-right" style="font-size: 10px;color: red;">Note: First time all documents are compulsory</i>
      </div>
      <div class="panel-body">
          <style>
              form ul .active a{
                  /*background-color: beige !important;
                  color: black !important;*/
              }
              input[type=file]{
                  height: 32px;
              }
              </style>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-setting" class="form-horizontal">
            <ul class="nav nav-tabs" style="font-size: 11px;">
                
            <li <?php if($error_document=="oo"){ ?> class="active" <?php } if(($error_document!="oo") && (!empty($error_document))){ ?>  style="background-color: #FCD9DF !important;" class="active" <?php } ?>>
                <a href="#tab-document" <?php if($error_document=="oo"){  }elseif($error_document==""){ ?> style="background-color: #8CA25A;color: white;" <?php }else{ ?>style="background-color: #FCD9DF;" <?php } ?> data-toggle="tab">Document Details</a>
            </li>
            <li <?php  if(($error_license!="oo") && (!empty($error_license)) && (empty($error_document))){ ?> class="active" style="background-color: #FCD9DF !important;" <?php } ?>>
                <a href="#tab-license" <?php if($error_license=="oo"){  }elseif($error_license==""){ ?> style="background-color: #8CA25A;color: white;" <?php }else{ ?>style="background-color: #FCD9DF;" <?php } ?> data-toggle="tab">License Details</a>
            </li>
          </ul>
            
            
          <div class="tab-content">
              <div class="tab-pane <?php if($error_document=="oo"){ ?> active <?php } if(($error_document!="oo") && (!empty($error_document))){ ?>  active <?php } ?>" id="tab-document">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-name">Store name</label>
                <div class="col-sm-10">
                    <input type="text" readonly="readonly" name="config_name" value="<?php echo $config_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                  <?php if ($error_name) { ?>
                  <div class="text-danger"><?php echo $error_name; ?></div>
                  <?php } ?>
                </div>
              </div>
               
              <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-owner">Bank signature verification</label>
                <div class="col-sm-6">
                  <input type="file" onchange="return checkFile(event,this.id);" style="max-width: 160px;" name="config_Bank_signature" value="<?php echo $config_Bank_signature; ?>"  id="input-Bank_signature" />
                  <input type="hidden" name="Bank_signature_h" value="<?php echo $config_Bank_signature; ?>"  id="input-Bank_signature_h" class="form-control" />
                  <?php if ($error_Bank_signature) { ?>
                  <div class="text-danger"><?php echo $error_Bank_signature; ?></div>
                  <?php } ?>
                </div>
                <div class="col-sm-4" style="font-weight: bold;padding-top: 8px;">
                     <?php if($config_Bank_signature!=""){ ?> <a href="../system/upload/store_doc/<?php echo $store_id."/".$config_Bank_signature; ?>" download="">View Uploded doc</a> <?php }  else{ echo "no document uploaded"; } ?>
                </div>
              </div>
              <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-owner">Partner Agreement</label>
                <div class="col-sm-6">
                  <input type="file" onchange="return checkFile(event,this.id);" style="max-width: 160px;" name="config_Partner_Agreement" value="<?php echo $config_Partner_Agreement; ?>"  id="input-Partner_Agreement"  />
                  <input type="hidden" name="Partner_Agreement_h" value="<?php echo $config_Partner_Agreement; ?>"  id="input-Partner_Agreement_h" class="form-control" />
                  <?php if ($error_Partner_Agreement) { ?>
                  <div class="text-danger"><?php echo $error_Partner_Agreement; ?></div>
                  <?php } ?>
                </div>
                <div class="col-sm-4" style="font-weight: bold;padding-top: 8px;">
                     <?php if($config_Partner_Agreement!=""){ ?> <a href="../system/upload/store_doc/<?php echo $store_id."/".$config_Partner_Agreement; ?>" download="">View Uploded doc</a> <?php } ?>
                 </div>
              </div>
                  
                  <h2>Partner Indemnity </h2>
                  
                  <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-owner">Stamp Paper – Agreement (Copy 1)</label>
                <div class="col-sm-2">
                  <input type="file" onchange="return checkFile(event,this.id);"  style="max-width: 160px;" name="config_Stamp_Paper_Agreement_1" value="<?php echo $config_Stamp_Paper_Agreement_1; ?>"  id="input-Stamp_Paper_Agreement_1"  />
                  <input type="hidden" name="Stamp_Paper_Agreement_1_h" value="<?php echo $config_Stamp_Paper_Agreement_1; ?>"  id="input-Stamp_Paper_Agreement_1_h" class="form-control" />
                  <?php if ($error_Stamp_Paper_Agreement_1) { ?>
                  <div class="text-danger"><?php echo $error_Stamp_Paper_Agreement_1; ?></div>
                  <?php } ?>
                </div>
              <div class="col-sm-2" style="font-weight: bold;padding-top: 8px;">
                     <?php if($config_Stamp_Paper_Agreement_1!=""){ ?> <a href="../system/upload/store_doc/<?php echo $store_id."/".$config_Stamp_Paper_Agreement_1; ?>" download="">View Uploded doc</a> <?php } ?>
                 </div>
                
                <label class="col-sm-2 control-label" for="input-owner">Stamp Paper – Agreement (Copy 2)</label>
                <div class="col-sm-2">
                  <input type="file" onchange="return checkFile(event,this.id);"  style="max-width: 160px;" name="config_Stamp_Paper_Agreement_2" value="<?php echo $config_Stamp_Paper_Agreement_2; ?>"  id="input-Stamp_Paper_Agreement_2"  />
                  <input type="hidden" name="Stamp_Paper_Agreement_2_h" value="<?php echo $config_Stamp_Paper_Agreement_2; ?>"  id="input-Stamp_Paper_Agreement_2_h" class="form-control" />
                  <?php if ($error_Stamp_Paper_Agreement_2) { ?>
                  <div class="text-danger"><?php echo $error_Stamp_Paper_Agreement_2; ?></div>
                  <?php } ?>
                </div>
                <div class="col-sm-2" style="font-weight: bold;padding-top: 8px;">
                     <?php if($config_Stamp_Paper_Agreement_2!=""){ ?> <a href="../system/upload/store_doc/<?php echo $store_id."/".$config_Stamp_Paper_Agreement_2; ?>" download="">View Uploded doc</a> <?php } ?>
                 </div>
              </div>
                  
                  
                  <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-owner">Aadhar ID of Partner & Surety(Number)</label>
                <div class="col-sm-4">
                    <input required type="text" name="config_Aadhar_ID_number" placeholder="Aadhar ID (Number)" value="<?php echo $config_Aadhar_ID_number; ?>"  id="input-Aadhar_ID_number" class="form-control" />
                  <?php if ($error_Aadhar_ID_number) { ?>
                  <div class="text-danger"><?php echo $error_Aadhar_ID_number; ?></div>
                  <?php } ?>
                </div>
              
                <label class="col-sm-2 control-label" for="input-owner">Aadhar ID of Partner & Surety(file)</label>
                <div class="col-sm-2">
                  <input type="file" onchange="return checkFile(event,this.id);"  style="max-width: 160px;" name="config_Aadhar_ID_file" value="<?php echo $config_Aadhar_ID_file; ?>"  id="input-Aadhar_ID_file"  />
                  <input type="hidden" name="Aadhar_ID_file_h" value="<?php echo $config_Aadhar_ID_file; ?>"  id="input-Aadhar_ID_file_h" class="form-control" />
                  <?php if ($error_Aadhar_ID_file) { ?>
                  <div class="text-danger"><?php echo $error_Aadhar_ID_file; ?></div>
                  <?php } ?>
                </div>
                 <div class="col-sm-2" style="font-weight: bold;padding-top: 8px;">
                     <?php if($config_Aadhar_ID_file!=""){ ?> <a href="../system/upload/store_doc/<?php echo $store_id."/".$config_Aadhar_ID_file; ?>" download="">View Uploded doc</a> <?php } ?>
                 </div>
              </div>
                  
                  <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-owner">PAN ID of Partner & Surety(Number)</label>
                <div class="col-sm-4">
                    <input required type="text" name="config_PAN_ID_number" placeholder="PAN ID (Number)" value="<?php echo $config_PAN_ID_number; ?>"  id="input-PAN_ID_number" class="form-control" />
                  <?php if ($error_PAN_ID_number) { ?>
                  <div class="text-danger"><?php echo $error_PAN_ID_number; ?></div>
                  <?php } ?>
                </div>
              
                <label class="col-sm-2 control-label" for="input-owner">PAN ID of Partner & Surety(file)</label>
                <div class="col-sm-2">
                  <input type="file" onchange="return checkFile(event,this.id);"  style="max-width: 160px;" name="config_PAN_ID_file" value="<?php echo $config_PAN_ID_file; ?>"  id="input-PAN_ID_file"  />
                  <input type="hidden" name="PAN_ID_file_h" value="<?php echo $config_PAN_ID_file; ?>"  id="input-PAN_ID_file_h" class="form-control" />
                  <?php if ($error_PAN_ID_file) { ?>
                  <div class="text-danger"><?php echo $error_PAN_ID_file; ?></div>
                  <?php } ?>
                </div>
                 <div class="col-sm-2" style="font-weight: bold;padding-top: 8px;">
                     <?php if($config_PAN_ID_file!=""){ ?> <a href="../system/upload/store_doc/<?php echo $store_id."/".$config_PAN_ID_file; ?>" download="">View Uploded doc</a> <?php } ?>
                 </div>
              </div>
              <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-owner">Residence Proof of Partner & Surety</label>
                <div class="col-sm-6">
                  <input type="file" onchange="return checkFile(event,this.id);"  style="max-width: 160px;" name="config_Residence_Proof" value="<?php echo $config_Residence_Proof; ?>"  id="input-Residence_Proof"  />
                  <input type="hidden" name="Residence_Proof_h" value="<?php echo $config_Residence_Proof; ?>"  id="input-Residence_Proof_h" class="form-control" />
                  <?php if ($error_Residence_Proof) { ?>
                  <div class="text-danger"><?php echo $error_Residence_Proof; ?></div>
                  <?php } ?>
                </div>
                 <div class="col-sm-4" style="font-weight: bold;padding-top: 8px;">
                     <?php if($config_Residence_Proof!=""){ ?> <a href="../system/upload/store_doc/<?php echo $store_id."/".$config_Residence_Proof; ?>" download="">View Uploded doc</a> <?php } ?>
                 </div>
              </div>
              <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-owner">Bank Statement (Last 6 Month of Partner & Surety)</label>
                <div class="col-sm-6">
                  <input type="file" onchange="return checkFile(event,this.id);"  style="max-width: 160px;" name="config_Bank_Statement" value="<?php echo $config_Bank_Statement; ?>"  id="input-Bank_Statement"  />
                  <input type="hidden" name="Bank_Statement_h" value="<?php echo $config_Bank_Statement; ?>"  id="input-Bank_Statement_h" class="form-control" />
                  <?php if ($error_Bank_Statement) { ?>
                  <div class="text-danger"><?php echo $error_Bank_Statement; ?></div>
                  <?php } ?>
                </div>
                <div class="col-sm-4" style="font-weight: bold;padding-top: 8px;">
                     <?php if($config_Bank_Statement!=""){ ?> <a href="../system/upload/store_doc/<?php echo $store_id."/".$config_Bank_Statement; ?>" download="">View Uploded doc</a> <?php } ?>
                </div>
              </div> 
                  
              <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-owner">1 Signed Cheque for Rs. 11000/- for signature verification</label>
                <div class="col-sm-6">
                  <input type="file" onchange="return checkFile(event,this.id);"  style="max-width: 160px;" name="config_Signed_Cheque" value="<?php echo $config_Signed_Cheque; ?>"  id="input-Signed_Cheque"  />
                  <input type="hidden" name="Signed_Cheque_h" value="<?php echo $config_Signed_Cheque; ?>"  id="input-Signed_Cheque_h" class="form-control" />
                  <?php if ($error_Signed_Cheque) { ?>
                  <div class="text-danger"><?php echo $error_Signed_Cheque; ?></div>
                  <?php } ?>
                </div>
                <div class="col-sm-4" style="font-weight: bold;padding-top: 8px;">
                     <?php if($config_Signed_Cheque!=""){ ?> <a href="../system/upload/store_doc/<?php echo $store_id."/".$config_Signed_Cheque; ?>" download="">View Uploded doc</a> <?php } ?>
                </div>
              </div> 
                  
              <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-owner">1 Cheque of Rs. 15,000/- for issuance of Tablet + Thermal Printer + Stationary Items etc.</label>
                <div class="col-sm-6">
                  <input type="file" onchange="return checkFile(event,this.id);"  style="max-width: 160px;" name="config_Cheque_issuance" value="<?php echo $config_Cheque_issuance; ?>"  id="input-Cheque_issuance"  />
                  <input type="hidden" name="Cheque_issuance_h" value="<?php echo $config_Cheque_issuance; ?>"  id="input-Cheque_issuance_h" class="form-control" />
                  <?php if ($error_Cheque_issuance) { ?>
                  <div class="text-danger"><?php echo $error_Cheque_issuance; ?></div>
                  <?php } ?>
                </div>
                <div class="col-sm-4" style="font-weight: bold;padding-top: 8px;">
                     <?php if($config_Cheque_issuance!=""){ ?> <a href="../system/upload/store_doc/<?php echo $store_id."/".$config_Cheque_issuance; ?>" download="">View Uploded doc</a> <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-owner">1 Cheque of Rs. 15,000/- for UFC branding etc. (Optional if the work is done by partner)</label>
                <div class="col-sm-6">
                  <input type="file" onchange="return checkFile(event,this.id);"  style="max-width: 160px;" name="config_Cheque_UFC" value="<?php echo $config_Cheque_UFC; ?>"  id="input-Cheque_UFC"  />
                  <input type="hidden" name="Cheque_UFC_h" value="<?php echo $config_Cheque_UFC; ?>"  id="input-Cheque_UFC_h" class="form-control" />
                  <?php if ($error_Cheque_UFC) { ?>
                  <div class="text-danger"><?php echo $error_Cheque_UFC; ?></div>
                  <?php } ?>
                </div>
                <div class="col-sm-4" style="font-weight: bold;padding-top: 8px;">
                     <?php if($config_Cheque_UFC!=""){ ?> <a href="../system/upload/store_doc/<?php echo $store_id."/".$config_Cheque_UFC; ?>" download="">View Uploded doc</a> <?php } ?>
                </div>
              </div>
               <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-owner">3 Signed Cheque of Surety to be deposited to Akshamaala</label>
                <div class="col-sm-6">
                  <input type="file" onchange="return checkFile(event,this.id);"  style="max-width: 160px;" name="config_Cheque_Akshamaala" value="<?php echo $config_Cheque_Akshamaala; ?>"  id="input-Cheque_Akshamaala"  />
                  <input type="hidden" name="Cheque_Akshamaala_h" value="<?php echo $config_Cheque_Akshamaala; ?>"  id="input-Cheque_Akshamaala_h" class="form-control" />
                  <?php if ($error_Cheque_Akshamaala) { ?>
                  <div class="text-danger"><?php echo $error_Cheque_Akshamaala; ?></div>
                  <?php } ?>
                </div>
                <div class="col-sm-4" style="font-weight: bold;padding-top: 8px;">
                     <?php if($config_Cheque_Akshamaala!=""){ ?> <a href="../system/upload/store_doc/<?php echo $store_id."/".$config_Cheque_Akshamaala; ?>" download="">View Uploded doc</a> <?php } ?>
                </div>
              </div>  
               <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-owner">Signature verification letter duly attested from Bank as per Akshamaala format</label>
                <div class="col-sm-6">
                  <input type="file" onchange="return checkFile(event,this.id);"  style="max-width: 160px;" name="config_Signature_verification" value="<?php echo $config_Signature_verification; ?>"  id="input-Signature_verification" />
                  <input type="hidden" name="Signature_verification_h" value="<?php echo $config_Signature_verification; ?>"  id="input-Signature_verification_h" class="form-control" />
                  <?php if ($error_Signature_verification) { ?>
                  <div class="text-danger"><?php echo $error_Signature_verification; ?></div>
                  <?php } ?>
                </div>
                <div class="col-sm-4" style="font-weight: bold;padding-top: 8px;">
                     <?php if($config_Signature_verification!=""){ ?> <a href="../system/upload/store_doc/<?php echo $store_id."/".$config_Signature_verification; ?>" download="">View Uploded doc</a> <?php } ?>
                </div>
              </div>   
            </div>
              
              <div class="tab-pane <?php  if(($error_license!="oo") && (!empty($error_license)) && (empty($error_document))){ ?> active  <?php } ?>" id="tab-license">
              <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-name">Fertilizer</label>
                <div class="col-sm-2">
                  <input  type="text" name="config_fertilizer_number" value="<?php echo $config_fertilizer_number; ?>" placeholder="License number" id="input-fertilizer_number" class="form-control" />
                  <?php if ($error_fertilizer_number) { ?>
                  <div class="text-danger"><?php echo $error_fertilizer_number; ?></div>
                  <?php } ?>
                </div>
                <div class="col-sm-2">
                    <div class=" input-group date">
                        <input  class="form-control" name="config_fertilizer_from" data-date-format="YYYY-MM-DD" value="<?php echo $config_fertilizer_from; ?>" type="text" id="input-fertilizer_from"   placeholder="Valid from"/>              
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                  
                  <?php if ($error_fertilizer_from) { ?>
                  <div class="text-danger"><?php echo $error_fertilizer_from; ?></div>
                  <?php } ?>
                </div>
                <div class="col-sm-2">
                  <div class=" input-group date">
                        <input  class="form-control" name="config_fertilizer_to" data-date-format="YYYY-MM-DD" value="<?php echo $config_fertilizer_to; ?>" type="text" id="input-fertilizer_to"   placeholder="Valid to"/>              
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                  
                  <?php if ($error_fertilizer_to) { ?>
                  <div class="text-danger"><?php echo $error_fertilizer_to; ?></div>
                  <?php } ?>
                </div>
                <div class="col-sm-2">
                  <input type="file" onchange="return checkFile(event,this.id);"  style="max-width: 160px;" name="config_fertilizer_file" value="<?php echo $config_fertilizer_file; ?>"  id="input-fertilizer_file"  />
                  <input type="hidden" name="fertilizer_file_h" value="<?php echo $config_fertilizer_file; ?>"  id="input-fertilizer_file_h" class="form-control" />
                  <?php if ($error_fertilizer_file) { ?>
                  <div class="text-danger"><?php echo $error_fertilizer_file; ?></div>
                  <?php } ?>
                </div>
                <div class="col-sm-2" style="font-weight: bold;padding-top: 8px;">
                     <?php if($config_fertilizer_file!=""){ ?> <a href="../system/upload/store_doc/<?php echo $store_id."/".$config_fertilizer_file; ?>" download="">View Uploded doc</a> <?php } ?>
                </div>
              </div>
              
             <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-name">Pesticide</label>
                <div class="col-sm-2">
                  <input  type="text" style="max-width: 160px;" name="config_Pesticide_number" value="<?php echo $config_Pesticide_number; ?>" placeholder="License number" id="input-Pesticide_number" class="form-control" />
                  <?php if ($error_Pesticide_number) { ?>
                  <div class="text-danger"><?php echo $error_Pesticide_number; ?></div>
                  <?php } ?>
                </div>
                <div class="col-sm-2">
                  <div class=" input-group date">
                        <input  class="form-control" name="config_Pesticide_from" data-date-format="YYYY-MM-DD" value="<?php echo $config_Pesticide_from; ?>" type="text" id="input-Pesticide_from"   placeholder="Valid from"/>              
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                  
                  <?php if ($error_Pesticide_from) { ?>
                  <div class="text-danger"><?php echo $error_Pesticide_from; ?></div>
                  <?php } ?>
                </div>
                <div class="col-sm-2">
                  <div class=" input-group date">
                        <input  class="form-control" name="config_Pesticide_to" data-date-format="YYYY-MM-DD" value="<?php echo $config_Pesticide_to; ?>" type="text" id="input-Pesticide_to"   placeholder="Valid to"/>              
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                  
                  <?php if ($error_Pesticide_to) { ?>
                  <div class="text-danger"><?php echo $error_Pesticide_to; ?></div>
                  <?php } ?>
                </div>
                <div class="col-sm-2">
                  <input type="file" onchange="return checkFile(event,this.id);"  style="max-width: 160px;" name="config_Pesticide_file" value="<?php echo $config_Pesticide_file; ?>"  id="input-Pesticide_file"  />
                  <input type="hidden" name="Pesticide_file_h" value="<?php echo $config_Pesticide_file; ?>"  id="input-Pesticide_file_h" class="form-control" />
                  <?php if ($error_Pesticide_file) { ?>
                  <div class="text-danger"><?php echo $error_Pesticide_file; ?></div>
                  <?php } ?>
                </div>
                <div class="col-sm-2" style="font-weight: bold;padding-top: 8px;">
                     <?php if($config_Pesticide_file!=""){ ?> <a href="../system/upload/store_doc/<?php echo $store_id."/".$config_Pesticide_file; ?>" download="">View Uploded doc</a> <?php } ?>
                </div>
              </div>
                  <div class="form-group ">
                <label class="col-sm-2 control-label" for="input-name">Seed</label>
                <div class="col-sm-2">
                  <input  type="text" name="config_Seed_number" value="<?php echo $config_Seed_number; ?>" placeholder="License number" id="input-Seed_number" class="form-control" />
                  <?php if ($error_Seed_number) { ?>
                  <div class="text-danger"><?php echo $error_Seed_number; ?></div>
                  <?php } ?>
                </div>
                <div class="col-sm-2">
                  <div class=" input-group date">
                        <input  class="form-control" name="config_Seed_from" data-date-format="YYYY-MM-DD" value="<?php echo $config_Seed_from; ?>" type="text" id="input-Seed_from"   placeholder="Valid from"/>              
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                  
                  <?php if ($error_Seed_from) { ?>
                  <div class="text-danger"><?php echo $error_Seed_from; ?></div>
                  <?php } ?>
                </div>
                <div class="col-sm-2">
                  <div class=" input-group date">
                        <input  class="form-control" name="config_Seed_to" data-date-format="YYYY-MM-DD" value="<?php echo $config_Seed_to; ?>" type="text" id="input-Seed_to"   placeholder="Valid to"/>              
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                  
                  <?php if ($error_Seed_to) { ?>
                  <div class="text-danger"><?php echo $error_Seed_to; ?></div>
                  <?php } ?>
                </div>
                <div class="col-sm-2">
                  <input type="file" onchange="return checkFile(event,this.id);"  style="max-width: 160px;" name="config_Seed_file" value="<?php echo $config_Seed_file; ?>"  id="input-Seed_file"  />
                  <input type="hidden" name="Seed_file_h" value="<?php echo $config_Seed_file; ?>"  id="input-Seed_file_h" class="form-control" />
                  <?php if ($error_Seed_file) { ?>
                  <div class="text-danger"><?php echo $error_Seed_file; ?></div>
                  <?php } ?>
                </div>
                <div class="col-sm-2" style="font-weight: bold;padding-top: 8px;">
                     <?php if($config_Seed_file!=""){ ?> <a href="../system/upload/store_doc/<?php echo $store_id."/".$config_Seed_file; ?>" download="">View Uploded doc</a> <?php } ?>
                </div>
              </div>
            </div>
           </div>
        </form>
      </div>
    </div>
  </div>
    
    <script type="text/javascript"><!--
$('.date').datetimepicker({
    pickTime: false
});
//--></script>
<script>
    
function checkFile(e,idd) 
{
    /// get list of files
    var file_list = e.target.files;
    /// go through the list of files
    for (var i = 0, file; file = file_list[i]; i++) 
    {

        var sFileName = file.name;
        var sFileExtension = sFileName.split('.')[sFileName.split('.').length - 1].toLowerCase();
        var iFileSize = file.size;
        var iConvert = (file.size / 1048576).toFixed(2);
        if (!(sFileExtension === "pdf" || sFileExtension === "zip" || sFileExtension === "rar" ) || iFileSize > 1048576) 
         { 
            
            var txt = "Please make sure your file is in pdf or zip or rar format and less than 1 MB";
            document.getElementById(idd).value='';
            alertify.error(txt);
            return false;
        }
        else
        {
          //alertify.success('Great ! file ');
          return true;
        }
    }
}
function checkFileSize()
    { 
    var total_files_size=0;
    var file_req_er='';
    var field_req_er='';
    var node_list = document.getElementsByTagName('input');
    for (var i = 0; i < node_list.length; i++) 
    {
        var node = node_list[i];
        if (node.getAttribute('type') == 'file') 
        { 
            var sFileName = node.value;
            if(sFileName!='')
            {
            var sFileExtension = sFileName.split('.')[sFileName.split('.').length - 1];
            var iFileSize = node.files[0].size;
            var iConvert=(node.files[0].size/10485760).toFixed(2);
            total_files_size+=Number(iFileSize);
            }
            else
            {
               var hiddn_id=node.getAttribute('id')+'_h'; 
               var hiddn_val=$("#"+hiddn_id).val();
               if(hiddn_id=='input-Cheque_UFC_h')
               {
               
               }
               else
               {
                 if(hiddn_val=="")
                 {
                  //file_req_er="some error";
                 }
               }
            }
            
        }
        else if (node.getAttribute('type') == 'text') 
        {
          if(node.value=="")
          {
              //field_req_er="some error";
          }
        }
         
    }
    //alert(total_files_size);
    if (total_files_size>18874368)
    {   
            var txt = "Please make sure your all files size is less than 18 MB";
            //document.getElementById(idd).value='';
            alertify.error(txt);
            return false;
    }
    else if(file_req_er!="")
    {
            alertify.error("Please make sure you selected all required files");
            return false;
    }
    else if(field_req_er!="")
    {
            alertify.error("Please make sure you fill all required fields");
            return false;
    }
    else
    {
         $("#form-setting").submit();
    }
}
        </script>
 </div>
<?php echo $footer; ?>