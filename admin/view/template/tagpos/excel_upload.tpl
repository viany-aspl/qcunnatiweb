<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">      
        <a href="<?php echo $view; ?>" data-toggle="tooltip" title="VIew Uploaded data" class="btn btn-primary"><i class="fa fa-eye"></i> View</a>
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
      
        
      <div class="panel-body">
        <form  method="post" enctype="multipart/form-data" id="form-restore">  
        
        <div class="well">
          <div class="row">
              
             <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-status">Factory Unit</label>
                <select required name="unit_id" id="unit_id" class="form-control">
                  <option value="">Select Unit</option>
                
                <?php foreach($units as $unit){
					if($user_group_id=='1'){
						?>
                  
                  <option <?php if($unit['unit_id']==$unit_id){ ?> selected="selected" <?php  } ?> value="<?php echo $unit['unit_id']; ?>"><?php echo $unit['unit_name']; ?></option>
               
					<?php }
					else
					{
						if($user_unit[0]['unit_id']==$unit['unit_id'])
						{
					?>
						<option <?php if($unit['unit_id']==$unit_id){ ?> selected="selected" <?php  } ?> value="<?php echo $unit['unit_id']; ?>"><?php echo $unit['unit_name']; ?></option>
					
					<?php
						}
					}
				} ?>
                 
                
                 
                </select>
              </div>
          
            </div> 
			<div class="col-sm-4">
            <div class="form-group">
                <label class="control-label" for="input-date-start">Invoice Date</label>
                <div class="input-group date" id="date_from">
                  <input required type="text" name="filter_date"  value="<?php echo $filter_date; ?>" placeholder="Invoice Date" data-date-format="YYYY-MM-DD" id="input-date" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>  
           </div> 
              
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                <input required class="btn btn-primary " id="choosefile" type="file" name="tfile" id="input-import" />
              </div>
             
            </div>
           <div class="col-sm-2">
               <input class="form-control btn btn-primary" type="submit" name="submit" value="UPLOAD" id="upload_btn" style="margin-top: 32px;">
           </div>
          </div>
        </div>
           </form>
       <!-- <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>-->
      </div>
             
    </div>
  </div>
 </div>
 <script type="text/javascript">

$('.date').datetimepicker({
	pickTime: false
});

</script> 
<script type="text/javascript">
$('#upload_btn').on('click', function() 
{
	//alert("hello");
	var unit_id=$('#unit_id').val();
	if(!unit_id)
	{
		alert('Please select unit');
		$('#unit_id').focus();
		return false;
	}
	var input_date=$('#input-date').val();
	if(!input_date)
	{
		alert('Please select invoice date');
		$('#input-date').focus();
		return false;
	}
	var choosefile=$("#choosefile").val();
	//alert(JSON.stringify(choosefile));
	var fileName = choosefile.files[0].name;
	if(!fileName)
	{
		alert('Please select File');
		$('#choosefile').focus();
		return false;
	}
          
	//return false;
   // $("#form-restore").submit();

 });
 </script>
<?php echo $footer; ?>