<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $insert; ?>" data-toggle="tooltip" title="<?php echo $button_insert; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-courier').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-courier">
      <div class="table-responsive">
         <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
              <td class="text-center"><?php echo $column_image; ?></td>
			  <td class="text-left"><?php if ($sort == 'courier_name') { ?>
                <a href="<?php echo $sort_courier_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_courier_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_courier_name; ?>"><?php echo $column_courier_name; ?></a>
                <?php } ?></td>
			  <td class="text-left"><?php echo $column_description; ?></td>
			  <td class="text-left"><?php echo $column_total_products; ?></td>
              <td class="text-left"><?php if ($sort == 'sort_order') { ?>
                <a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?></a>
                <?php } ?></td>
              <td class="text-right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($couriers) { ?>
            <?php foreach ($couriers as $courier) { ?>
            <tr>
              <td class="text-center"><?php if ($courier['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $courier['courier_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $courier['courier_id']; ?>" />
                <?php } ?></td>
			  <td class="text-center"><?php if ($courier['image']) { ?>
                <img src="<?php echo $courier['image']; ?>" alt="<?php echo $courier['courier_name']; ?>" class="img-thumbnail" />
                <?php } else { ?>
                <span class="img-thumbnail"><i class="fa fa-camera fa-5x"></i></span>
                <?php } ?></td>
              <td class="text-left"><?php echo $courier['courier_name']; ?></td>
              <td class="text-left"><?php echo $courier['description']; ?></td>
              <td class="text-left"><?php echo $courier['total_products']; ?></td>
              <td class="text-left"><?php echo $courier['sort_order']; ?></td>
              <td class="text-right"><a href="<?php echo $courier['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </form>
    <div class="row">
      <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
      <div class="col-sm-6 text-right"><?php echo $results; ?></div>
    </div>
  </div>
  </div>
  </div>
</div>
<?php echo $footer; ?>