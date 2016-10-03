<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
      <button type="submit" form="form-product" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-info-circle"></i> <?php echo $text_order_product; ?> (#<?php echo $order_product_id; ?>)</h3>
        <p><?php echo $text_for_order; ?> #<?php echo $order_id; ?></p>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-2 text-right"><?php echo $text_product_name; ?></label>
                <div class="col-sm-10">
                    <?php echo $product_name; ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 text-right"><?php echo $text_quantity; ?></label>
                <div class="col-sm-10">
                    <?php echo $quantity; ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-order-status"><?php echo $text_status; ?></label>
                <div class="col-sm-10">
                    <select name="order_status_id" id="input-order-status" class="form-control">
                        <?php foreach ($order_statuses as $order_status) { ?>
                        <?php if ($order_status['order_status_id'] == $order_status_id) { ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 text-right"><?php echo $text_price; ?></label>
                <div class="col-sm-10">
                    <?php echo $price; ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 text-right"><?php echo $text_vendor_total; ?></label>
                <div class="col-sm-10">
                    <?php echo $vendor_total;; ?>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>