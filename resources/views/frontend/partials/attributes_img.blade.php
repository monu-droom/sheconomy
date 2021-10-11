<style>
  .magnify {
    /*   border-radius: 50%; */
    border: 2px solid black;
    position: absolute;
    z-index: 99999;
    background-repeat: no-repeat;
    background-color: white;
    box-shadow: inset 0 0 20px rgba(0, 0, 0, .5);
    display: none;
    cursor: none;
  }
  @media(min-width: 601px){
  .side-width{
    width: 80px;
  }
  .main-width{
    margin-right: 250px;
  }
}
@media(max-width: 600px){
  .side-width{
    width: 60px;
  }
  .main-width{
    margin-right: 20px;
  }
}

/* Modal Slider Css start */
@media(min-width: 601px){
  #wrap{
  /* margin-top: 60px; */
  background-color: #fff;
  /* padding: 50px 0; */
  }
  #slider{
    max-width: 950px;
    max-height: 100%; 
    margin: 0 auto;
  }
  .slider-img{
      /* margin-left: -75px; */
      max-width: 900px;
      max-height: 100%;
    }
  button{
    margin: 0;
    padding: 0;
    background: none;
    border: none;
    border-radius: 0;
    outline: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
  }
  .slide-arrow{
    position: absolute;
    top: 50%;
    margin-top: -15px;
  }
  .prev-arrow{
    left: -40px;
    width: 0;
    height: 0;
    border-left: 0 solid transparent;
    border-right: 35px solid #ED4C67;
    border-top: 20px solid transparent;
    border-bottom: 20px solid transparent;
  }
  .next-arrow{
    right: -40px;
    width: 0;
    height: 0;
    border-right: 0 solid transparent;
    border-left: 35px solid #ED4C67;
    border-top: 20px solid transparent;
    border-bottom: 20px solid transparent;
  }
}

@media(max-width: 600px){
    #wrap{
    margin-top: 170px;
    background-color: #fff;
    /* padding: 50px 0; */
  }
  #slider{
    max-width: 310px;
    max-height: 300px; 
    margin: 0 auto;
    padding: 0;
  }
  .slider-img{
      max-width: 300px;
      max-height: 300px;
    }
  button{
    margin: 0;
    padding: 0;
    background: none;
    border: none;
    border-radius: 0;
    outline: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
  }
  .slide-arrow{
    position: absolute;
    top: 50%;
    margin-top: -15px;
  }
  .prev-arrow{
    left: -20px;
    width: 0;
    height: 0;
    border-left: 0 solid transparent;
    border-right: 15px solid #ED4C67;
    border-top: 10px solid transparent;
    border-bottom: 10px solid transparent;
  }
  .next-arrow{
    right: -20px;
    width: 0;
    height: 0;
    border-right: 0 solid transparent;
    border-left: 15px solid #ED4C67;
    border-top: 10px solid transparent;
    border-bottom: 10px solid transparent;
  }
}
/* Modal Slider Css End */
@media(min-width: 601px){
  .modal-body{
  height: 600px;
  width: 100%;
  }
  .modal-content{
  height: 600px;
  width: 1100px;
  margin-left: -30px;
  }
}

@media(max-width: 600px){
  /* .modal-body{
  height: 600px;
  width: 100%;
  } */
  .modal-content{
  height: 600px;
  /* width: 1100px; */
  /* margin-left: -30px; */
  }
}

</style>
<div class="popup-gallery" id="{{$variant_images->variant}}">
  <div class="product-gal sticky-top d-flex flex-row-reverse">
    @if(is_array(json_decode($variant_images->variant_img)) && count(json_decode($variant_images->variant_img)) > 0)
    <div class="product-gal-img image" id="img">
    <!-- <a href="{{ my_asset(json_decode($variant_images->variant_img)[0]) }}" id="theContent"> -->
        <img src="{{ my_asset('frontend/images/placeholder.jpg') }}" data-toggle="modal" data-target="#largeModal" class="xzoom2 img-fluid lazyload img2 " src="{{ my_asset('frontend/images/placeholder.jpg') }}" data-src="{{ my_asset(json_decode($variant_images->variant_img)[0]) }}" xoriginal="{{ my_asset(json_decode($variant_images->variant_img)[0]) }}" />
      <!-- </a> -->
    </div>
    <div class="product-gal-thumb">
      <div class="xzoom-thumbs">
        @foreach (json_decode($variant_images->variant_img) as $key => $photos)
        <!-- <a href="{{ my_asset($photos) }}"> -->
        <img width="50" src="{{ my_asset('frontend/images/placeholder.jpg') }}" class="imgs xzoom-gallery2 lazyload" src="{{ my_asset('frontend/images/placeholder.jpg') }}" data-src="{{ my_asset($photos) }}" @if($key==0) xpreview="{{ my_asset($photos) }}" @endif>
        <!-- </a> -->
        @endforeach
      </div>
    </div>
    @else
      <div class="product-gal-img">
        <img src="{{ my_asset('frontend/images/placeholder.jpg') }}" class="xzoom img-fluid lazyload" src="{{ my_asset('frontend/images/placeholder.jpg') }}" data-src="{{ my_asset(json_decode($detailedProduct->photos)[0]) }}" xoriginal="{{ my_asset(json_decode($detailedProduct->photos)[0]) }}" />
      </div>
      <div class="product-gal-thumb">
          <div class="xzoom-thumbs">
              @foreach (json_decode($detailedProduct->photos) as $key => $photo)
                  <a href="{{ my_asset($photo) }}">
                      <img src="{{ my_asset('frontend/images/placeholder.jpg') }}" class="xzoom-gallery lazyload" src="{{ my_asset('frontend/images/placeholder.jpg') }}" width="80" data-src="{{ my_asset($photo) }}"  @if($key == 0) xpreview="{{ my_asset($photo) }}" @endif>
                  </a>
              @endforeach
          </div>
      </div>
    @endif
  </div>

<!-- large modal -->
<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
  <div style="" class="modal-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
      <div class="modal-body">
      <div id="wrap">
        <ul id="slider">
        <?php $variant = json_decode($variant_images->variant_img, true); ?>
        @if($variant != '')
        @foreach (json_decode($variant_images->variant_img) as $key => $photos)
          <li class="slide-item">
            <img class="slider-img img2" id="theSliderContent" src="{{ my_asset($photos) }}">
          </li>
        @endforeach
        @endif
        </ul>
      </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('.imgs').click(function() {
      var idimg = $(this).attr('id');
      var srcimg = $(this).attr('src');
      $(".img2").attr('src', srcimg);
      $("#theContent").attr("href",srcimg);
      // $(".img2").attr("src",srcimg);
    });
  });

  jQuery(document).ready(function() {
    	jQuery('.popup-gallery').magnificPopup({
    		delegate: 'a',
    		type: 'image',
        callbacks: {
          elementParse: function(item) {
            // Function will fire for each target element
            // "item.el" is a target DOM element (if present)
            // "item.src" is a source that you may modify
            console.log(item.el.context.className);
            if(item.el.context.className == 'video') {
              item.type = 'iframe',
              item.iframe = {
                 patterns: {
                   youtube: {
                     index: 'youtube.com/', // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).

                     id: 'v=', // String that splits URL in a two parts, second part should be %id%
                      // Or null - full URL will be returned
                      // Or a function that should return %id%, for example:
                      // id: function(url) { return 'parsed id'; } 

                     src: '//www.youtube.com/embed/%id%?autoplay=1' // URL that will be set as a source for iframe. 
                   },
                   vimeo: {
                     index: 'vimeo.com/',
                     id: '/',
                     src: '//player.vimeo.com/video/%id%?autoplay=1'
                   },
                   gmaps: {
                     index: '//maps.google.',
                     src: '%id%&output=embed'
                   }
                 }
              }
            } else {
               item.type = 'image',
               item.tLoading = 'Loading image #%curr%...',
               item.mainClass = 'mfp-img-mobile',
               item.image = {
                 tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
               }
            }

          }
        },
    		gallery: {
    			// enabled: true,
    			navigateByImgClick: true,
    			preload: [0,1] // Will preload 0 - before current, and 1 after the current image
    		}
    	});
  
    });
    // Modal Slider Start
    $(function(){
  $("#slider").slick({
    speed: 1000,
    // dots: true,
    prevArrow: '<button class="slide-arrow prev-arrow"></button>',
    nextArrow: '<button class="slide-arrow next-arrow"></button>'
  });
});
    // Modal Slider End

// Magnifier Start
/*Size is  set in pixels... supports being written as: '250px' */
var magnifierSize = 250;

/*How many times magnification of image on page.*/
var magnification = 2;

function magnifier() {

  this.magnifyImg = function(ptr, magnification, magnifierSize) {
    var $pointer;
    if (typeof ptr == "string") {
      $pointer = $(ptr);
    } else if (typeof ptr == "object") {
      $pointer = ptr;
    }
    
    if(!($pointer.is('img'))){
      // alert('Object must be image.');
      return false;
    }

    magnification = +(magnification);

    $pointer.hover(function() {
      $(this).css('cursor', 'none');
      $('.magnify').show();
      //Setting some variables for later use
      var width = $(this).width();
      var height = $(this).height();
      var src = $(this).attr('src');
      var imagePos = $(this).offset();
      var image = $(this);

      if (magnifierSize == undefined) {
        magnifierSize = '150px';
      }

      $('.magnify').css({
        'background-size': width * magnification + 'px ' + height * magnification + "px",
        'background-image': 'url("' + src + '")',
        'width': magnifierSize,
        'height': magnifierSize
      });

      //Setting a few more...
      var magnifyOffset = +($('.magnify').width() / 2);
      var rightSide = +(imagePos.left + $(this).width());
      var bottomSide = +(imagePos.top + $(this).height());

      $(document).mousemove(function(e) {
        if (e.pageX < +(imagePos.left - magnifyOffset / 6) || e.pageX > +(rightSide + magnifyOffset / 6) || e.pageY < +(imagePos.top - magnifyOffset / 6) || e.pageY > +(bottomSide + magnifyOffset / 6)) {
          $('.magnify').hide();
          $(document).unbind('mousemove');
        }
        var backgroundPos = "" - ((e.pageX - imagePos.left) * magnification - magnifyOffset) + "px " + -((e.pageY - imagePos.top) * magnification - magnifyOffset) + "px";
        $('.magnify').css({
          'left': e.pageX - magnifyOffset,
          'top': e.pageY - magnifyOffset,
          'background-position': backgroundPos
        });
      });
    }, function() {

    });
  };

  this.init = function() {
    $('body').prepend('<div class="magnify"></div>');
  }

  return this.init();
}

var magnify = new magnifier();
magnify.magnifyImg('.magnifiedImg', magnification, magnifierSize);
// Magnifier End
</script>