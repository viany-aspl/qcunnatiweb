<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">      
        <a href="<?php echo $redirect; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a></div>
      <h1>B2B Partner</h1>
      
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
        <h3 class="panel-title"><i class="fa fa-list"></i>B2B Partner List</h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-store">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left">SID</td>
	          <td class="text-left">Name</td>
                  <td class="text-left">Email</td>
                  <td class="text-left">Telephone</td>
                  <td class="text-left">Pancard</td>
                  <td class="text-left">Gstn</td>
                  <td class="text-left">Address</td> 
                </tr>
              </thead>
              <tbody>
                <?php if ($b2b) { $a=1; ?>
                <?php foreach ($b2b as $un) { ?>
                <tr>
                  <td class="text-left"><?php echo $a; ?></td>
	          <td class="text-left"><?php echo $un['name']; ?></td>
                  <td class="text-left"><?php echo $un['email']; ?></td>
                  <td class="text-left"><?php echo $un['telephone']; ?></td>
                
	          <td class="text-left"><?php echo $un['pan_card']; ?></td>
                  <td class="text-left"><?php echo $un['gstn']; ?></td>
                  <td class="text-left"><?php echo $un['address']; ?></td>
             
                
                </tr>
                <?php $a++;} ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
         
      </div>
	<div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"> 
             
              <?php echo $results; ?>  </div>
        </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 