{!! $header !!}
<style>
  #bixgrow_iframe {
    width:100%;
    height: 100vh;
    border:none;
    margin:0;
    padding:0;
    overflow:hidden;
    z-index:999999;
  }

  #bixgrow_iframe_wrapper {
    -webkit-overflow-scrolling: touch;
    overflow-y: scroll;
    scrollbar-width: none;
    -ms-overflow-style: none;

  }

  #bixgrow_iframe_wrapper::-webkit-scrollbar {
    width: 0px;
  }

  #bixgrow_not_active_alert {
    display: none;
  }

  #bixgrow_not_active_admin_alert {
    display: none;
  }
  @if($css)
  {!! $css !!}
  @endif


</style>

<div id="bixgrow_iframe_wrappe">
  <script>
  	var iframeSrc = "{{$iframeUrl}}";
  	document.write('<iframe id="bixgrow_iframe" src=' + iframeSrc + '>Your browser does not support iframes</iframe>');
    let iframe = document.querySelector("#bixgrow_iframe");
    window.addEventListener('message', function(e) {
        // message that was passed from iframe page
        var message = e.data;
        if(e.origin.includes('bixgrow')){
         	iframe.style.height = message.height + 'px';
       	}
    } , false);
  </script>
</div>
{!! $footer !!}
