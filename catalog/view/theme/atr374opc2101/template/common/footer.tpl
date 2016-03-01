<footer id="footer">
  <div class="container">
    <div class="row">
      <?php if ($informations) { ?>
      <div class="col-sm-3">
        <h5><?php echo $text_information; ?></h5>
        <ul class="list-unstyled">
          <?php foreach ($informations as $information) { ?>
          <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php } ?>
      <div class="col-sm-3">
        <h5><?php echo $text_service; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
          <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
          <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
        </ul>
      </div>
      <div class="col-sm-3">
        <h5><?php echo $text_extra; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
          <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
          <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
          <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
        </ul>
      </div>
      <div class="col-sm-3">
        <h5><?php echo $text_account; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
          <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
          <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
          <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
        </ul>
      </div>
    </div>
    <hr />
  </div>
  <div id="wrap_powered_social" class="clearfix">
    <div class="container">
      <div id="powered">Design : <a target="_blank" href="http://www.ecommercetoolsmarket.com/">Ecommercetoolsmarket</a> | <?php echo $powered; ?></div>
      <ul id="socialColumn"><!--
     --><li>
          <a href="#">
            <img class="social-hover" src="catalog/view/theme/atr374opc2101/image/social/facebook-hover.png" alt="facebook" />
            <img class="social-link" src="catalog/view/theme/atr374opc2101/image/social/facebook.png" alt="facebook" />
          </a>
        </li><!--
     --><li>
          <a href="#">
            <img class="social-hover" src="catalog/view/theme/atr374opc2101/image/social/twitter-hover.png" alt="twitter" />
            <img class="social-link" src="catalog/view/theme/atr374opc2101/image/social/twitter.png" alt="twitter" />
          </a>
        </li><!--
     --><li>
          <a href="#">
            <img class="social-hover" src="catalog/view/theme/atr374opc2101/image/social/pinterest-hover.png" alt="pinterest" />
            <img class="social-link" src="catalog/view/theme/atr374opc2101/image/social/pinterest.png" alt="pinterest" />
          </a>
        </li><!--
     --><li>
          <a href="#">
            <img class="social-hover" src="catalog/view/theme/atr374opc2101/image/social/google-hover.png" alt="google+" />
            <img class="social-link" src="catalog/view/theme/atr374opc2101/image/social/google.png" alt="google+" />
          </a>
        </li><!--
     --><li>
          <a href="#">
            <img class="social-hover" src="catalog/view/theme/atr374opc2101/image/social/instagram-hover.png" alt="instagram" />
            <img class="social-link" src="catalog/view/theme/atr374opc2101/image/social/instagram.png" alt="instagram" />
          </a>
        </li>
      </ul>
      <br class="clear" />
    </div>
  </div>
</footer>
<a href="#" class="scrollToTop"><img width="30" height="30" src="catalog/view/theme/atr374opc2101/image/back-top.png" /></a>

<!-- include carouFredSel plugin + helper plugins-->
<script type="text/javascript" src="catalog/view/theme/atr374opc2101/javascript/jquery.carouFredSel-6.2.1-packed.js"></script>
<script type="text/javascript" src="catalog/view/theme/atr374opc2101/javascript/helper-plugins/jquery.mousewheel.min.js"></script>
<script type="text/javascript" src="catalog/view/theme/atr374opc2101/javascript/helper-plugins/jquery.touchSwipe.min.js"></script>
<script type="text/javascript" src="catalog/view/theme/atr374opc2101/javascript/jquery/throttle-debounce/jquery.ba-throttle-debounce.min.js"></script>

<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//--> 

<!-- Theme created by Atragene, http://www.ecommercetoolsmarket.com -->

</body></html>