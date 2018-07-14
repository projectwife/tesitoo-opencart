<div>
  <?php foreach ($banners as $banner) { ?>
    <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive center-block" />
    <div id="banner_overlay">
      <div class="overlay_bg"></div>
      <div class="overlay_text">
        <p><?php echo $banner['overlay_text_1']; ?></p>
        <p><?php echo $banner['overlay_text_2']; ?></p>
      </div>
    </div>

  <?php } ?>
</div>
