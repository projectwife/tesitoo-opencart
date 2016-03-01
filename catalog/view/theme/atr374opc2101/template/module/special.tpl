<div class="box">	
	<h3><span><?php echo $heading_title; ?></span>
		<div class="nav-slider">
			<a class="prev prev-special" href="#"><img width="20" height="20" src="catalog/view/theme/atr374opc2101/image/prev-module.png" alt="&lt;" /></a>
			<a class="next next-special" href="#"><img width="20" height="20" src="catalog/view/theme/atr374opc2101/image/next-module.png" alt="&gt;" /></a>
		</div>
	</h3>
	<div class="row carousel-module box-special">
	  <?php foreach ($products as $product) { ?>
	  <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="product-thumb transition">
		  <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
		  <div class="transition-right">
			  <div class="caption">
				<h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
				<?php if ($product['price']) { ?>
				<p class="price">
				  <?php if (!$product['special']) { ?>
				  <?php echo $product['price']; ?>
				  <?php } else { ?>
				  <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
				  <?php } ?>
				</p>
				<?php } ?>
			  </div>
			  <div class="button-group">
				<button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
			  </div>		  
			  <?php if ($product['rating']) { ?>
				<div class="rating">
				  <?php for ($i = 1; $i <= 5; $i++) { ?>
				  <?php if ($product['rating'] < $i) { ?>
				  <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
				  <?php } else { ?>
				  <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
				  <?php } ?>
				  <?php } ?>
				</div>
			  <?php } ?>
		  </div>
		</div>
	  </div>
	  <?php } ?>
	</div>
</div>