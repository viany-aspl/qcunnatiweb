<link rel="stylesheet" href="view/stylesheet/pos/spectrum.css">
<?php echo $header; ?>
<div id="content">

<div class="container">
               
 <div class="row services-1" style="margin-top:0;">
                    
                    <div class="col-sm-12 text-center" data-scrollreveal="enter bottom over 1.5s" data-sr-init="true" data-sr-complete="true">
                        <ul id="services-1-carousel" class="icon-effect list-inline">
                          
                            <li class="item">
                                <span class="icon"><span class="bulfertilizer"></span></span>
                                <h3>Bulk Fertilizer</h3>

                            </li>
                            <li class="item">
                                <span class="icon"><span class="cropnutirtion"></span></span>
                                <h3>Crop Nutrition</h3>

                            </li>
                            <li class="item">
                                <span class="icon"><span class="cropprotection"></span></span>
                                <h3>Crop Protection</h3>

                            </li>
                            <li class="item">
                                <span class="icon"><span class="plantregulator"></span></span>
                                <h3>Plant Regulators</h3>

                            </li>

                             <li class="item">
                                <span class="icon"><span class="seed"></span></span>
                                <h3>Seeds</h3>

                            </li>

                        </ul>
                    </div>
                </div>

  <div class="container-fluid"><br />
    <br />
    <div class="row">
      <div class="col-sm-offset-4 col-sm-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h1 class="panel-title"><i class="fa fa-lock"></i> <?php echo $text_login; ?></h1>
          </div>
          <div class="panel-body">
            <?php if ($success) { ?>
            <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
              <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php } ?>
            <?php if ($error_warning) { ?>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
              <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php } ?>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label for="input-username"><?php echo $entry_username; ?></label>
                <div class="input-group"><span class="input-group-addon"><i class="fa fa-user"></i></span>
                  <input type="text" autocomplete="off" name="username" value="<?php echo $username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label for="input-password"><?php echo $entry_password; ?></label>
                <div class="input-group"><span class="input-group-addon"><i class="fa fa-lock"></i></span>
                  <input type="password" autocomplete="off" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
                </div>
                <?php if ($forgotten) { ?>
                <span class="help-block"><a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></span>
                <?php } ?>
              </div>
              <div class="text-right">
                <button type="submit" class="btn btn-primary"><i class="fa fa-key"></i> <?php echo $button_login; ?></button>
              </div>
              <?php if ($redirect) { ?>
              <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
              <?php } ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>