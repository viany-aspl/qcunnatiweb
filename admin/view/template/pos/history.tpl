<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">  
    <div class="row">
        <div class="col-lg-12">
            <form id="form" enctype="multipart/form-data" method="post" action="">
                <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <td class="right"><?php echo $column_username; ?></td>
                        <td class="left"><?php echo $column_name; ?></td>
                        <td class="left"><?php echo $column_withdraw; ?></td>
                        <td class="right"><?php echo $column_time; ?></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($rows as $row) { ?>
                      <tr>
                        <td class="right"><?php echo $row['username']; ?></td>
                        <td class="left"><?php echo $row['firstname'].' '.$row['lastname']; ?></td>
                        <td class="left"><?php echo $row['amount']; ?></td>
                        <td class="right"><?php echo date('d/m/Y h:i:s A', strtotime($row['date'])) ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                </table>
                <div class="pagination">
                    <?= $pagination ?>
                </div><!-- END .pagination -->  
        </div><!-- END .col-lg-12 -->
      </div><!-- END .row -->
    </div><!-- END .container-fluid -->
  </div><!-- END .content -->  
<?php echo $footer; ?>
