<div>
  <?php foreach ($banners as $banner) { ?>
    <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive center-block fullwidth" />
    <div id="banner_overlay">
      <div class="overlay_bg"></div>
      <div class="overlay_text">
        <div class="overlay_text_1"><?php echo $banner['overlay_text_1']; ?></div>
        <div class="overlay_text_2"><?php echo $banner['overlay_text_2']; ?></div>
      </div>
    </div>

  <?php } ?>
</div>
