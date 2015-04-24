jQuery(function( $ ){

	homeheight = function() {
		$homeheight = $(window).height();
		$homeheighthalf = Math.floor(parseInt( ($homeheight) / 1.5));
		$('.home-featured .wrap') .css({'height': $homeheighthalf+'px'});
	};

	/**
	* Outputs the Google Maps iframe.
	*/
	gmapiframe = function() {
		var iframe = document.createElement('iframe');
		iframe.frameBorder=0;
		iframe.border=0;
		iframe.width="600px";
		iframe.height="450px";
		iframe.id="gmap-iframe";
		iframe.setAttribute("src", 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2131.830487504874!2d11.958053499999995!3d57.702349700000006!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x464ff368430eb32b%3A0x1b75d0e11b60aef!2sHvitfeldtsgatan+4%2C+411+20+G%C3%B6teborg!5e0!3m2!1ssv!2sse!4v1416739234273');
		$( ".gmap" ).append(iframe);
	}

	mapheight = function() {
		$loveheight = 0;
		$loveheight = $(".contact-us .gmap").outerHeight();
		$('.contact-us .contact').css('height', $loveheight);
		//console.log('love' + $loveheight);
	};

	/**
	* Use this function if mobile layout. A Google Map Image instead of an iframe.
	*/
	gmapimage = function() {
		$(".gmap").append($("<a>",
		{
			href: "https://goo.gl/maps/EoQj9",
			target: "_blank",
			html: $("<img>", { alt: "Google Map of Hvitfeltsgatan 4, 411 20 Göteborg", src: "http://maps.googleapis.com/maps/api/staticmap?center=Hvitfeltsgatan+4,+411+20+Göteborg&zoom=16&scale=2&size=600x450&maptype=roadmap&sensor=false&format=png&visual_refresh=true&markers=size:mid%7Ccolor:red%7CHvitfeltsgatan+4,+411+20+Göteborg" })
		}));
	}

	/**
	* This part handles the highlighting functionality.
	* We use the scroll functionality again, some array creation and
	* manipulation, class adding and class removing, and conditional testing
	*/
	navhighlight = function() {
		var aChildren = $("ul#menu-main-menu li").children(); // find the a children of the list items
		var aArray = []; // create the empty aArray
		for (var i=0; i < aChildren.length; i++) {
			var aChild = aChildren[i];
			var ahref = $(aChild).attr('href');
			aArray.push(ahref);
		} // this for loop fills the aArray with attribute href values

		$(window).scroll(function(){
			var windowPos = $(window).scrollTop(); // get the offset of the window from the top of page
			var windowHeight = $(window).height(); // get the height of the window
			var docHeight = $(document).height();

			for (var i=0; i < aArray.length; i++) {
				var theID = aArray[i];
				var theID = theID.replace("/", "");
				var divPos = $(theID).offset().top; // get the offset of the div from the top of page
				if (theID == "#home") {
					var divHeight = $( ".home-featured" ).outerHeight();
				} else if (theID == "#about-us") {
					var divHeight = $( ".about-us-widgets" ).outerHeight();
				} else {
					var divHeight = $( theID ).parentsUntil( ".widget-area" ).outerHeight();
				}
				if ( ( windowPos >= divPos && windowPos < (divPos + divHeight) ) && (windowPos + windowHeight != docHeight) ) {
					$("a[href='/" + theID + "']").parent().addClass("active");
				} else {
					$("a[href='/" + theID + "']").parent().removeClass("active");
				}
			}
			if( windowPos <= 0 ){
				$("ul#menu-main-menu li:first-child").addClass("active"); //Set the first menu option to be "selected" when page scrolled up
			}
			if( windowPos + windowHeight == docHeight ) { //Set the last menu option as active when end of page reached
				if (!$("ul#menu-main-menu li:last-child").hasClass("active")) {
					var navActiveCurrent = $(".active a").attr("href");
					$("a[href='/" + theID + "']").parent().removeClass("active");
					$("ul#menu-main-menu li:last-child").addClass("active");
				}
			}
		});
	};

	$(document).ready(function() {
		$("ul#menu-main-menu li:first-child").addClass("active");  //Set the first menu option to be "selected" when page loaded
		homeheight();
		navhighlight();
		if ($('.responsive-check').css('display') == 'inline') {
			gmapiframe();
			mapheight();
		} else if ($('.responsive-check').css('display') == 'block') {
			gmapimage();
			//mapheight();
			$('.contact-us .gmap').imagesLoaded(mapheight);
		} else {
			gmapimage();
		}
	});

	$(window).resize(function() {
		if ($('.responsive-check').css('display') != 'none') {
			homeheight();
			mapheight();
		}
		navhighlight();
	});

  $(".home-featured .home-widgets-1 .widget:last-child").after('<p class="arrow"><a class="fa fa-angle-down" href="#home-widgets"></a></p>');

  $.localScroll({
  	duration: 750
  });

});
