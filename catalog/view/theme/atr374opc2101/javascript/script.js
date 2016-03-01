/******************************************************Featured products slider**************************************************************************************/
$(window).load(function() {
	var carfeat = $("#content #carousel_featured");
	var carfeatColLeft = $("#column-left #carousel_featured");
  var carfeatColRight = $("#column-right #carousel_featured");
	
	if(carfeat.length){
		$('#content #carousel_featured').carouFredSel({
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			swipe: {
				onMouse: true,
				onTouch: true
			},
			width: '100%',
			height: 'variable',
			scroll: 1,
			items: {
				width: 236,
        height: 'variable',
				visible: {
				  min: 1,
				  max: 6
				  }
			},
			auto: {
				duration: 1000,
				pauseOnHover: 'immediate'
			}
		});
	}
  
  if(carfeatColLeft.length){
		$('#column-left #carousel_featured').carouFredSel({
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			swipe: {
				onMouse: true,
				onTouch: true
			},
			width: '100%',
			height: 'variable',
			scroll: 1,
			items: {
				width: 236,
        height: 'variable',
				visible: {
				  min: 1,
				  max: 1
				  }
			},
			auto: {
				duration: 800,
				pauseOnHover: 'immediate'
			}
		});
	}
  
  if(carfeatColRight.length){
		$('#column-right #carousel_featured').carouFredSel({
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			swipe: {
				onMouse: true,
				onTouch: true
			},
			width: '100%',
			height: 'variable',
			scroll: 1,
			items: {
				width: 236,
        height: 'variable',
				visible: {
				  min: 1,
				  max: 1
				  }
			},
			auto: {
				duration: 800,
				pauseOnHover: 'immediate'
			}
		});
	}


// /******************************************************Modules products slider**************************************************************************************/
	var contentBest = $("#content .box-bestseller");
	var contentLatest = $("#content .box-latest");
	var contentSpecial = $("#content .box-special");
	var contentBestSm6 = $("#content.col-sm-6 .box-bestseller");
	var contentLatestSm6 = $("#content.col-sm-6 .box-latest");
	var contentSpecialSm6 = $("#content.col-sm-6 .box-special");
  var contentBestSm12 = $("#content.col-sm-12 .box-bestseller");
	var contentLatestSm12 = $("#content.col-sm-12 .box-latest");
	var contentSpecialSm12 = $("#content.col-sm-12 .box-special");

	
	if(contentBest.length){
		$('#content .box-bestseller').carouFredSel({
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			prev: '#content .prev-bestseller',
			next: '#content .next-bestseller',
			swipe: {
				onMouse: false,
				onTouch: true
			},
			width: '100%',
      height: 'variable',
			scroll: 1,
			items: {
				width: 256,
				height: 'variable',
				visible: {
				  min: 1,
				  max: 3
				  }
			},
			auto: {
				duration: 1000,
				pauseOnHover: 'immediate'
			}
		});
	}
	if(contentBestSm6.length){
		$('#content.col-sm-6 .box-bestseller').carouFredSel({
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			prev: '#content .prev-bestseller',
			next: '#content .next-bestseller',
			swipe: {
				onMouse: false,
				onTouch: true
			},
      width: '100%',
      height: 'variable',
			items: {
				width: 256,
				height: 'variable',
				visible: {
				  min: 1,
				  max: 2
				  }
			},
			auto: {
				duration: 1000,
				pauseOnHover: 'immediate'
				}
		});
	}
  if(contentBestSm12.length){
		$('#content.col-sm-12 .box-bestseller').carouFredSel({
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			prev: '#content .prev-bestseller',
			next: '#content .next-bestseller',
			swipe: {
				onMouse: false,
				onTouch: true
			},
      width: '100%',
      height: 'variable',
			items: {
				width: 256,
				height: 'variable',
				visible: {
				  min: 1,
				  max: 5
				  }
			},
			auto: {
				duration: 1000,
				pauseOnHover: 'immediate'
				}
		});
	}
	
	if(contentLatest.length){
		$('#content .box-latest').carouFredSel({
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			prev: '#content .prev-latest',
			next: '#content .next-latest',
			swipe: {
				onMouse: false,
				onTouch: true
			},
			width: '100%',
      height: 'variable',
			scroll: 1,
			items: {
				width: 256,
        height: 'variable',
				visible: {
				  min: 1,
				  max: 3
				  }
			},
			auto: {
				duration: 1000,
				pauseOnHover: 'immediate'
			}
		});
	}
	if(contentLatestSm6.length){
		$('#content.col-sm-6 .box-latest').carouFredSel({
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			prev: '#content .prev-latest',
			next: '#content .next-latest',
			swipe: {
				onMouse: false,
				onTouch: true
			},
      width: '100%',
      height: 'variable',
			items: {
				width: 256,
        height: 'variable',
				visible: {
				  min: 1,
				  max: 2
				  }
			},
			auto: {
				duration: 1000,
				pauseOnHover: 'immediate'
			}
		});
	}
  if(contentLatestSm12.length){
		$('#content.col-sm-12 .box-latest').carouFredSel({
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			prev: '#content .prev-latest',
			next: '#content .next-latest',
			swipe: {
				onMouse: false,
				onTouch: true
			},
      width: '100%',
      height: 'variable',
			items: {
				width: 256,
        height: 'variable',
				visible: {
				  min: 1,
				  max: 5
				  }
			},
			auto: {
				duration: 1000,
				pauseOnHover: 'immediate'
			}
		});
	}	
	
	if(contentSpecial.length){
		$('#content .box-special').carouFredSel({
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			prev: '#content .prev-special',
			next: '#content .next-special',
			swipe: {
				onMouse: false,
				onTouch: true
			},
			width: '100%',
			height: 'variable',
			scroll: 1,
			items: {
				width: 256,
        height: 'variable',
				visible: {
				  min: 1,
				  max: 3
				  }
			},
			auto: {
				duration: 1000,
				pauseOnHover: 'immediate'
			}
		});
	}
	if(contentSpecialSm6.length){
		$('#content.col-sm-6 .box-special').carouFredSel({
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			prev: '#content .prev-special',
			next: '#content .next-special',
			swipe: {
				onMouse: false,
				onTouch: true
			},
      width: '100%',
			height: 'variable',
			items: {
				width: 256,
        height: 'variable',
				visible: {
				  min: 1,
				  max: 2
				  }
			},
			auto: {
				duration: 1000,
				pauseOnHover: 'immediate'
			}
		});
	}
  if(contentSpecialSm12.length){
		$('#content.col-sm-12 .box-special').carouFredSel({
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			prev: '#content .prev-special',
			next: '#content .next-special',
			swipe: {
				onMouse: false,
				onTouch: true
			},
      width: '100%',
			height: 'variable',
			items: {
				width: 256,
        height: 'variable',
				visible: {
				  min: 1,
				  max: 5
				  }
			},
			auto: {
				duration: 1000,
				pauseOnHover: 'immediate'
			}
		});
	}



/******************************************************Modules products slider RTL**************************************************************************************/
	var contBestRtl = $("#content .box-bestseller-rtl");
	var contLateRtl = $("#content .box-latest-rtl");
	var contSpecRtl = $("#content .box-special-rtl");
	
	if(contBestRtl.length){
		$('#content .box-bestseller-rtl').carouFredSel({
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			prev: '#content .prev-bestseller',
			next: '#content .next-bestseller',
			swipe: {
				onMouse: false,
				onTouch: true
			},
			width: '100%',
      height: 'variable',
			scroll: 1,
			direction: 'right',
			items: {
				width: 256,
        height: 'variable',
				visible: {
				  min: 1,
				  max: 3
				  }
			},
			auto: {
				duration: 1000,
				pauseOnHover: 'immediate'
			}
		});
	}
	
	if(contLateRtl.length){
		$('#content .box-latest-rtl').carouFredSel({       
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			prev: '#content .prev-latest',
			next: '#content .next-latest',
			swipe: {
				onMouse: false,
				onTouch: true
			},
			width: '100%',
      height: 'variable',
			scroll: 1,
			direction: 'right',
			items: {
				width: 256,
        height: 'variable',
				visible: {
				  min: 1,
				  max: 3
				  }
			},
			auto: {
				duration: 1000,
				pauseOnHover: 'immediate'
			}
		});
	}
	
	if(contSpecRtl.length){
		$('#content .box-special-rtl').carouFredSel({
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			prev: '#content .prev-special',
			next: '#content .next-special',
			swipe: {
				onMouse: false,
				onTouch: true
			},
			width: '100%',
      height: 'variable',
			scroll: 1,
			direction: 'right',
			items: {
				width: 256,
        height: 'variable',
				visible: {
				  min: 1,
				  max: 3
				  }
			},
			auto: {
				duration: 1000,
				pauseOnHover: 'immediate'
			}
		});
	}


/******************************************************Column modules products slider**************************************************************************************/
/* Column left */
	var leftBest = $("#column-left .box-bestseller");
	var leftLatest = $("#column-left .box-latest");
	var leftSpecial = $("#column-left .box-special");
	
	if(leftBest.length){
		$('#column-left .box-bestseller').carouFredSel({
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			prev: '#column-left .prev-bestseller',
			next: '#column-left .next-bestseller',
			swipe: {
				onMouse: false,
				onTouch: true
			},
			width: '100%',
      height: 'variable',
			scroll: 1,
			items: {
				width: 234,
        height: 'variable',
				visible: {
				  min: 1,
				  max: 1
				  }
			},
			auto: {
				duration: 1000,
				pauseOnHover: 'immediate'
			}
		});
	}
	
	if(leftLatest.length){
		$('#column-left .box-latest').carouFredSel({
			responsive: true,
			mousewheel: true,
			prev: '#column-left .prev-latest',
			next: '#column-left .next-latest',
			swipe: {
				onMouse: false,
				onTouch: true
			},
			width: '100%',
      height: 'variable',
			scroll: 1,
			items: {
				width: 234,
        height: 'variable',
				visible: {
				  min: 1,
				  max: 1
				  }
			},
			auto: {
				duration: 1000,
				pauseOnHover: 'immediate'
			}
		});
	}
	
	if(leftSpecial.length){
		$('#column-left .box-special').carouFredSel({
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			prev: '#column-left .prev-special',
			next: '#column-left .next-special',
			swipe: {
				onMouse: false,
				onTouch: true
			},
			width: '100%',
      height: 'variable',
			scroll: 1,
			items: {
				width: 234,
        height: 'variable',
				visible: {
				  min: 1,
				  max: 1
				  }
			},
			auto: {
				duration: 1000,
				pauseOnHover: 'immediate'
			}
		});
	}


/* Column right */
	var rightBest = $("#column-right .box-bestseller");
	var rightLatest = $("#column-right .box-latest");
	var rightSpecial = $("#column-right .box-special");
	
	if(rightBest.length){
		$('#column-right .box-bestseller').carouFredSel({
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			prev: '#column-right .prev-bestseller',
			next: '#column-right .next-bestseller',
			swipe: {
				onMouse: false,
				onTouch: true
			},
			width: '100%',
      height: 'variable',
			scroll: 1,
			items: {
				width: 234,
        height: 'variable',
				visible: {
				  min: 1,
				  max: 1
				  }
			},
			auto: {
				duration: 1000,
				pauseOnHover: 'immediate'
			}
		});
	}
	
	if(rightLatest.length){
		$('#column-right .box-latest').carouFredSel({
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			prev: '#column-right .prev-latest',
			next: '#column-right .next-latest',
			swipe: {
				onMouse: false,
				onTouch: true
			},
			width: '100%',
      height: 'variable',
			scroll: 1,
			items: {
				width: 234,
        height: 'variable',
				visible: {
				  min: 1,
				  max: 1
				  }
			},
			auto: {
				duration: 1000,
				pauseOnHover: 'immediate'
			}
		});
	}
	
	if(rightSpecial.length){
		$('#column-right .box-special').carouFredSel({
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			prev: '#column-right .prev-special',
			next: '#column-right .next-special',
			swipe: {
				onMouse: false,
				onTouch: true
			},
			width: '100%',
      height: 'variable',
			scroll: 1,
			items: {
				width: 234,
        height: 'variable',
				visible: {
				  min: 1,
				  max: 1
				  }
			},
			auto: {
				duration: 1000,
				pauseOnHover: 'immediate'
			}
		});
	}


/******************************************************Column modules products slider RTL**************************************************************************************/
/* column left RTL */
	var leftBestRtl = $("#column-left .box-bestseller-rtl");
	var leftLateRtl = $("#column-left .box-latest-rtl");
	var leftSpecRtl = $("#column-left .box-special-rtl");
	
	if(leftBestRtl.length){
		$('#column-left .box-bestseller-rtl').carouFredSel({
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			prev: '#column-left .prev-bestseller',
			next: '#column-left .next-bestseller',
			swipe: {
				onMouse: false,
				onTouch: true
			},
			width: '100%',
      height: 'variable',
			scroll: 1,
      direction: 'right',
			items: {
				width: 234,
				height: 'variable',
				visible: {
				  min: 1,
				  max: 1
				  }
			},
			auto: {
				duration: 1000,
				pauseOnHover: 'immediate'
			}
		});
	}
	
	if(leftLateRtl.length){
		$('#column-left .box-latest-rtl').carouFredSel({
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			prev: '#column-left .prev-latest',
			next: '#column-left .next-latest',
			swipe: {
				onMouse: false,
				onTouch: true
			},
			width: '100%',
      height: 'variable',
			scroll: 1,
      direction: 'right',
			items: {
				width: 234,
				height: 'variable',
				visible: {
				  min: 1,
				  max: 1
				  }
			},
			auto: {
				duration: 1000,
				pauseOnHover: 'immediate'
			}
		});
	}
	
	if(leftSpecRtl.length){
		$('#column-left .box-special-rtl').carouFredSel({
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			prev: '#column-left .prev-special',
			next: '#column-left .next-special',
			swipe: {
				onMouse: false,
				onTouch: true
			},
			width: '100%',
      height: 'variable',
			scroll: 1,
      direction: 'right',
			items: {
				width: 234,
        height: 'variable',
				visible: {
				  min: 1,
				  max: 1
				  }
			},
			auto: {
				duration: 1000,
				pauseOnHover: 'immediate'
			}
		});
	}


/* column right RTL */
	var rightBestRtl = $("#column-right .box-bestseller-rtl");
	var rightLateRtl = $("#column-right .box-latest-rtl");
	var rightSpecRtl = $("#column-right .box-special-rtl");
	
	if(rightBestRtl.length){
		$('#column-right .box-bestseller-rtl').carouFredSel({
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			prev: '#column-right .prev-bestseller',
			next: '#column-right .next-bestseller',
			swipe: {
				onMouse: false,
				onTouch: true
			},
			width: '100%',
      height: 'variable',
			scroll: 1,
      direction: 'right',
			items: {
				width: 234,
				height: 'variable',
				visible: {
				  min: 1,
				  max: 1
				  }
			},
			auto: {
				duration: 1000,
				pauseOnHover: 'immediate'
			}
		});
	}
	
	if(rightLateRtl.length){
		$('#column-right .box-latest-rtl').carouFredSel({
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			prev: '#column-right .prev-latest',
			next: '#column-right .next-latest',
			swipe: {
				onMouse: false,
				onTouch: true
			},
			width: '100%',
      height: 'variable',
			scroll: 1,
      direction: 'right',
			items: {
				width: 234,
				height: 'variable',
				visible: {
				  min: 1,
				  max: 1
				  }
			},
			auto: {
				duration: 1000,
				pauseOnHover: 'immediate'
			}
		});
	}
	
	if(rightSpecRtl.length){
		$('#column-right .box-special-rtl').carouFredSel({
			responsive: true,
			mousewheel: true,
			onWindowsResize: 'throttle',
			prev: '#column-right .prev-special',
			next: '#column-right .next-special',
			swipe: {
				onMouse: false,
				onTouch: true
			},
			width: '100%',
      height: 'variable',
			scroll: 1,
      direction: 'right',
			items: {
				width: 234,
				height: 'variable',
				visible: {
				  min: 1,
				  max: 1
				  }
			},
			auto: {
				duration: 1000,
				pauseOnHover: 'immediate'
			}
		});
	}
	

/******************************************************Product-info slider**************************************************************************************/
	var imgAdd = $("#image-additional");
	
	if(imgAdd.length){
		$('#image-additional ul').carouFredSel({
			mousewheel: true,
			onWindowsResize: 'throttle',
			prev: '#prevInfo',
			next: '#nextInfo',
			swipe: {
				onMouse: false,
				onTouch: true
			},
			width: null,
			auto: false,						
			scroll: 1,
			items: {
				width: 94,
				height: 'variable',						
				visible: {
				  min: 1,
				  max: 8
				  }
			},
			auto: false
		});
	}


/******************************************************Carousel wrapper css**************************************************************************************/
	$('.nav-slider a[style="display:none;"]').parent().css('background-colo','#f00');
	$('.caroufredsel_wrapper').css('margin','0');



/******************************************************Sticky header, Menu, Search and Cart**************************************************************************************/
	$("#sticky_head").sticky({ topSpacing: 0 });

	
	$('#sticky_head .col-sm-5 > .search-toggle').click(function(){
		if ($("#sticky_head #search, #search.mobile-search").hasClass("search-open")){
			$("#sticky_head #search, #search.mobile-search").removeClass("search-open");
			$("#sticky_head .col-sm-5 > .search-toggle").removeClass("open");
		}
		else
		{
			$("#sticky_head #search, #search.mobile-search").addClass("search-open");
			$("#sticky_head .col-sm-5 > .search-toggle").addClass("open");
		}
	});
});


/******************************************************Pagination**************************************************************************************/
$(document).ready(function() {
	var pagibar = $(".pagination");
	
	if(pagibar.length){
		$('.pagination li > a:last, .pagination li > span:last').addClass('pagination-last');
	}


/******************************************************Quantity product info**************************************************************************************/

	$('#q_up').click(function(){var q_val_up=parseInt($("input#input-quantity").val());if(isNaN(q_val_up)){q_val_up=0;}
	$("input#input-quantity").val(q_val_up+1).keyup(); return false; });$('#q_down').click(function(){var q_val_up=parseInt($("input#input-quantity").val());if(isNaN(q_val_up)){q_val_up=0;}
	if(q_val_up>1){$("input#input-quantity").val(q_val_up-1).keyup();} return false; });


/******************************************************Back top top**************************************************************************************/
	//Check to see if the window is top if not then display button
	$(window).scroll(function(){
		if ($(this).scrollTop() > 300) {
			$('.scrollToTop').fadeIn();
		} else {
			$('.scrollToTop').fadeOut();
		}
	});
	
	//Click event to scroll to top
	$('.scrollToTop').click(function(){
		$('html, body').animate({scrollTop : 0},800);
		return false;
	});
});