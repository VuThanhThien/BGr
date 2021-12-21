
{{-- var total = "{{$shopSettings->checkout_popup_data->total}}"; --}}
var imgSrc ="{{$shopSettings->checkout_popup_data->pathCDN}}";
var titleText ="{{$shopSettings->checkout_popup_data->titleText}}";
var titleColor ="{{$shopSettings->checkout_popup_data->titleColor}}";
var textContent3 ="{{$shopSettings->checkout_popup_data->textContent3}}";
var textContent2 ="{{$shopSettings->checkout_popup_data->textContent2}}";
var contentColor ="{{$shopSettings->checkout_popup_data->contentColor}}";
var btnText12 = "{{$shopSettings->checkout_popup_data->btnText12}}";
var btnText3 = "{{$shopSettings->checkout_popup_data->btnText3}}";
var imgHeight = "{{$shopSettings->checkout_popup_data->imgHeight}}";
var imgWidth = "{{$shopSettings->checkout_popup_data->imgWidth}}";
var btnColor = "{{$shopSettings->checkout_popup_data->btnColor}}";
var btnBg = "{{$shopSettings->checkout_popup_data->btnBg}}";
var btnConer = "{{$shopSettings->checkout_popup_data->btnConer}}";
var titleFontSize = "{{$shopSettings->checkout_popup_data->titleFontSize}}";
var contentFontSize = "{{$shopSettings->checkout_popup_data->contentFontSize}}";
/**link register  */
var onClickLink = "{{$shopSettings->checkout_popup_data->onClickLink}}";
var popupType = "{{$shopSettings->checkout_popup_data->popupType}}";
var newtab1 = "{{$shopSettings->checkout_popup_data->newtab1}}";
var newtab2 = "{{$shopSettings->checkout_popup_data->newtab2}}";
var closeOnBg = "{{$shopSettings->checkout_popup_data->closeOnBg}}";
var referralLinkLabel = "{{$shopSettings->checkout_popup_data->referralLinkLabel}}";
var referralLink = "{{$shopSettings->checkout_popup_data->referralLink}}";
var couponCode = "{{$shopSettings->checkout_popup_data->couponCode}}";
var couponCodeLabel = "{{$shopSettings->checkout_popup_data->couponCodeLabel}}";
var CopyText = "{{$shopSettings->checkout_popup_data->CopyText}}";
var ExtraCss = "{{$shopSettings->checkout_popup_data->ExtraCss}}";
var embededInPage = {{intval($shopSettings->checkout_popup_data->embededInPage)}};
var sharringMsg = "{{$shopSettings->checkout_popup_data->sharringMsg}}";
var showOnce = {{intval($shopSettings->checkout_popup_data->showOnce)}};
var showArray =  {!!json_encode($shopSettings->checkout_popup_data->showArray)!!};
var shareArray = {!!json_encode($shopSettings->checkout_popup_data->shareArray)!!};
var showIfMoreThan ={{floatval($shopSettings->checkout_popup_data->showIfMoreThan)}};
var isBlank = ['','target="_blank"'];
let BIXGROW_mask = document.createElement("div");
BIXGROW_mask.id = "BIXGROW_mask";
// document.body.appendChild(BIXGROW_mask);
var head = document.head || document.getElementsByTagName('head')[0],
style = document.createElement('style');
/**css */
//#region css
let css = '';
if(embededInPage == 0){
  document.body.appendChild(BIXGROW_mask);
   css = '#BIXGROW_mask{'+
            'display: none;  z-index: 4 ;position: fixed;left: 0;top: 0;width: 100%;height: 100%;'+
            'overflow: auto;background-color: rgba(0,0,0,0.4);'+
            '-webkit-animation-name: fadeIn;'+
            '-webkit-animation-duration: 0.4s;'+
            'animation-name: fadeIn;'+
            'animation-duration: 0.4s'+
          '}'+
          '.BIXGROW_input_block{'+
              'margin: 16px 4px;'+
              'width: 80%;'+
              'margin-left: auto;'+
              'margin-right: auto;'+
              'display: none;'+
          '}'+
          '.BIXGROW_label {'+
              'font-weight: 700;'+
              'font-size: 1rem;'+
              'display: inline-block;'+
              'width: 100%;'+
              'margin: 0 0 10px 0;'+
              'padding: 0;'+
              'text-align: left;'+
          '}'+
          '.BIXGROW_input_wrapper {'+
              'display: flex;'+
              'border-radius: 5px;'+
              'border: 1px solid #ced4da;'+
              'height: 40px;'+
          '}'+
          '.BIXGROW_input {'+
              'border: transparent;'+
              'background: transparent;'+
              'outline: none;'+
              'flex: 1 1;'+
              'padding: 0 10px;'+
              'opacity: .75;'+
          '}'+
          '.BIXGROW_copy_btn {'+
              'background-color: #efefef;'+
              'border-color: transparent;'+
              'padding: 8px;'+
              'height: 100%;'+
              'border-top-right-radius: inherit;'+
              'border-bottom-right-radius: inherit;'+
          '}'+
          '.BIXGROW_copy_btn:hover{'+
            'background-color: #b5b4b4;'+
            'transition: .1s ease;'+
          '}'+
          'button:not(:disabled) {'+
              'cursor: pointer;'+
          '}'+
          '.BIXGROW_card{'+
            'margin: 10% auto;position: relative; border-radius: 10px; height: max-content; width:'+ imgWidth +'px; background-color:#fff;'+
            '-webkit-animation-name: animatetop;'+
            '-webkit-animation-duration: 0.4s;'+
            'animation-name: animatetop;'+
            'animation-duration: 0.4s'+
          '}'+
          '#BIXGROW_close_btn{'+
            'color: #000 ;right: 5px;z-index: 6;top: 5px; font-size: 25px;font-weight: bold;font-weight: lighter;;position: absolute; line-height: 1rem; cursor: pointer;'+
          '}'+
          '.BIXGROW_title{'+
            'text-align: center; margin-top: 20px;margin-bottom: 10px;font-weight: 900;font-size: '+titleFontSize+'px; max-width: 80%;color: '+titleColor+';margin-left: auto;margin-right: auto;'+
          '}'+
          '.BIXGROW_content{'+
            'width: 60%; text-align: center; margin-left: auto; margin-right: auto; font-weight: 600;line-height: 1.25rem; color: '+contentColor+';font-size: '+contentFontSize+'px;'+
          '}'+
          '.BIXGROW_btn_container{'+
            'display: flex;justify-content: center;padding-bottom: 20px;margin-top: 20px;'+
          '}'+
          '.BIXGROW_image{'+
            'margin-left: auto;'+
            'margin-right: auto;'+
            'background-repeat: no-repeat;'+
            'background-size: cover;'+
            'z-index: 5;border-top-left-radius: inherit;border-top-right-radius: inherit;'+
            'height: '+imgHeight+'px;'+
            ' width:'+ imgWidth +'px;'+
            ' background-image: url('+imgSrc+');'+
            'background-position: 50% 50%;'+
          '}'+
          '.BIXGROW_submit_btn {'+
            'font-size: 15px; margin-left: auto;margin-right: auto;border-radius: '+btnConer+'px;padding: 10px 15px 10px 15px; height: auto; color: '+btnColor+'; border: none; background-color: '+btnBg+';width: 58%; transition: .3s; font-weight: 700;'+
          '}'+
          '.BIXGROW_submit_btn:hover{'+
            '  opacity: 0.6;'+
          '}'+
          '.BIXGROW_share_wrapper{'+
            'padding: 10px 0 12px;'+
            'text-align: center;'+
            'height: 40px;'+
            'display: flex ;'+
            'justify-content: center;'+
            'width: auto;'+
            'border-bottom-left-radius: 10px;'+
            'border-bottom-right-radius: 10px;'+
          '}'+
          '.BIXGROW_share_wrapper a{'+
            'text-decoration: none;'+
            'margin: 4px;'+
            'width: 32px;'+
            'height: 32px;'+
            'display: none;'+
          '}'+
          '.BIXGROW_share_wrapper img{'+
            'width: 28px;'+
            'height: 28px;'+
            'margin: 2px;'+
            'transition: .1s;'+
          '}'+
          '.BIXGROW_share_wrapper img:hover{'+
            'width: 32px;'+
            'height: 32px;'+
          '}'+
            '@-webkit-keyframes animatetop {'+
            'from {top: -300px; opacity: 0} '+
            'to {top: 15%; opacity: 1}'+
            '}'+

            '@keyframes animatetop {'+
            'from {top: -300px; opacity: 0}'+
            'to {top: 15%; opacity: 1}'+
            '}'+

            '@-webkit-keyframes fadeIn {'+
            'from {opacity: 0} '+
            'to {opacity: 1}'+
            '}'+

            '@keyframes fadeIn {'+
            'from {opacity: 0} '+
            'to {opacity: 1}'+
            '}'
            ;
}
else{
  document.getElementsByClassName("section")[0].appendChild(BIXGROW_mask);
   css = '#BIXGROW_mask{'+
            'display: none;margin-top: 1rem;border: 1px solid #d9d9d9;border-radius: 5px;'+
          '}'+
          '.BIXGROW_input_block{'+
              'margin: 16px 4px;'+
              'width: 80%;'+
              'margin-left: auto;'+
              'margin-right: auto;'+
              'display: none;'+
          '}'+
          '.BIXGROW_label {'+
              'font-weight: 700;'+
              'font-size: 1rem;'+
              'display: inline-block;'+
              'width: 100%;'+
              'margin: 0 0 10px 0;'+
              'padding: 0;'+
              'text-align: left;'+
          '}'+
          '.BIXGROW_input_wrapper {'+
              'display: flex;'+
              'border-radius: 5px;'+
              'border: 1px solid #ced4da;'+
              'height: 40px;'+
          '}'+
          '.BIXGROW_input {'+
              'border: transparent;'+
              'background: transparent;'+
              'outline: none;'+
              'flex: 1 1;'+
              'padding: 0 10px;'+
              'opacity: .75;'+
          '}'+
          '.BIXGROW_copy_btn {'+
              'background-color: #efefef;'+
              'border-color: transparent;'+
              'padding: 8px;'+
              'height: 100%;'+
              'border-top-right-radius: inherit;'+
              'border-bottom-right-radius: inherit;'+
          '}'+
          '.BIXGROW_copy_btn:hover{'+
            'background-color: #b5b4b4;'+
            'transition: .1s ease;'+
          '}'+
          'button:not(:disabled) {'+
              'cursor: pointer;'+
          '}'+
          '.BIXGROW_card{'+
            'margin-left: auto;margin-right: auto;position: relative;top: 5%; border-radius: 5px; height: max-content; width:'+ imgWidth +'px; background-color:#fff;width: 100%;'+
          '}'+
          '#BIXGROW_close_btn{'+
            'display:none;'+
          '}'+
          '.BIXGROW_title{'+
            'text-align: center; margin-top: 20px;margin-bottom: 10px;font-weight: 900;font-size: '+titleFontSize+'px; max-width: 80%;color: '+titleColor+'!important ;margin-left: auto;margin-right: auto;'+
          '}'+
          '.BIXGROW_content{'+
            'width: 60%; text-align: center; margin-left: auto; margin-right: auto; font-weight: 600;line-height: 1.25rem; color: '+contentColor+';font-size: '+contentFontSize+'px;'+
          '}'+
          '.BIXGROW_btn_container{'+
            'display: flex;justify-content: center;padding-bottom: 20px;margin-top: 20px;'+
          '}'+
          '.BIXGROW_image{'+
            'margin-left: auto;'+
            'margin-right: auto;'+
            'background-repeat: no-repeat;'+
            'background-size: cover;'+
            'z-index: 5;border-top-left-radius: inherit;border-top-right-radius: inherit;'+
            'min-height: 250px;'+
            ' width:100%;'+
            ' background-image: url('+imgSrc+');'+
            'background-position: 50% 50%;'+
          '}'+
          '.BIXGROW_submit_btn {'+
            ' margin-left: auto;'+
            'margin-right: auto; '+
            ' border-radius: '+ btnConer+'px; '+
            ' padding: 10px 15px 10px 15px; '+
            ' height: auto; '+
            ' color: '+ btnColor +'; border: none; background-color: '+btnBg+';width: 50%; transition: .3s; font-size: 15px;font-weight: 700;'+
          '}'+
          '.BIXGROW_submit_btn:hover{'+
            '  opacity: 0.6;'+
          '}'+
          '.BIXGROW_share_wrapper{'+
            'padding: 10px 0 12px;'+
            'text-align: center;'+
            'height: 40px;'+
            'display: flex;'+
            'justify-content: center;'+
            'width: auto;'+
            'border-bottom-left-radius: 10px;'+
            'border-bottom-right-radius: 10px;'+
          '}'+
          '.BIXGROW_share_wrapper a{'+
            'text-decoration: none;'+
            'margin: 4px;'+
            'width: 32px;'+
            'height: 32px;'+
            'display: none;'+
          '}'+
          '.BIXGROW_share_wrapper img{'+
            'width: 28px;'+
            'height: 28px;'+
            'margin: 2px;'+
            'transition: .1s;'+
          '}'+
          '.BIXGROW_share_wrapper img:hover{'+
            'width: 32px;'+
            'height: 32px;'+
          '}'
            ;

}
if(ExtraCss != null && ExtraCss != undefined){
  css += ExtraCss;
}
//#endregion
head.appendChild(style);


style.type = 'text/css';
if (style.styleSheet){
  // This is required for IE8 and below.
  style.styleSheet.cssText = css;
} else {
  style.appendChild(document.createTextNode(css));
}
/**html */
//#region basic
let popupBasic =
'<div id="BGCard" class="BIXGROW_card">'+
  '<a href="'+onClickLink+'" '+isBlank[newtab1]+' style="text-decoration: none">'+
    '<div class="BIXGROW_image"></div>' +
  '</a>'+
'</div>';
//#endregion basic
//#region simple
let popupSimple =
'<div id="BGCard" class="BIXGROW_card">'+
      '<span id="BIXGROW_close_btn">&times;</span>'+
      '<div class="BIXGROW_image" ></div>' +
      '<h2 class="BIXGROW_title">'+titleText+'</h2>'+
      '<h4 class="BIXGROW_content">'+textContent2+'</h4>'+
        '<a class="BIXGROW_btn_container" href="'+onClickLink+'" '+isBlank[newtab2]+' style="text-decoration: none">'+
          '<button id="BIXGROW_submit_btn" class="BIXGROW_submit_btn" >'+btnText12+'</button>'+
        '</a>'+
  '</div>';
//#endregion simple
//#region advanced
  let popupAdvanced =
  '<div id="BGCard" class="BIXGROW_card">'+
    '<span id="BIXGROW_close_btn">&times;</span>'+
    '<div class="BIXGROW_image"></div>' +
    '<h2 class="BIXGROW_title">'+titleText+'</h2>'+
    '<h4 class="BIXGROW_content">'+textContent3+'</h4>'+
    '<div class="BIXGROW_input_block" id="bg_referral_link">'+
      '<label class="BIXGROW_label">'+
        referralLinkLabel+
      '</label>'+
      '<div class="BIXGROW_input_wrapper">'+
        '<input id="BIXGROW_input_reflink" class="BIXGROW_input" readonly="" value="'+
        referralLink +
        '"></input>'+
        '<button class="BIXGROW_copy_btn" onclick="copyReferralLinkToClipboard()">'+ CopyText +'</button>'+
      '</div>'+
    '</div>'+
    '<div class="BIXGROW_input_block" id="bg_coupon_code">'+
      '<label class="BIXGROW_label">'+
        couponCodeLabel+
      '</label>'+
      '<div class="BIXGROW_input_wrapper">'+
        '<input id="BIXGROW_input_coupon" class="BIXGROW_input" readonly="" value="'+
        couponCode +
        '"></input>'+
        '<button class="BIXGROW_copy_btn" onclick="copyCouponCodeToClipboard()">'+ CopyText +'</button>'+
      '</div>'+
    '</div>'+
      '<a id="BIXGROW_active_link" class="BIXGROW_btn_container"  onclick="checkoutPopupRegisterBixgrow(event)" style="text-decoration: none">'+
        '<button id="BIXGROW_submit_btn" class="BIXGROW_submit_btn" >'+btnText3+'</button>'+
      '</a>'+
    '<div class="BIXGROW_share_wrapper" id="BIXGROW_share_wrapper" style="display:none">'+
        '<a title="Share on Facebook" target="_blank" id="bg_fb_icon"><img src="https://bixgrow.s3.us-west-2.amazonaws.com/creatives/1632474194_facebook.svg"></a>'+
        '<a title="Share on Twitter" target="_blank" id="bg_tw_icon"><img src="https://bixgrow.s3.us-west-2.amazonaws.com/creatives/1632474290_twitter.svg"></a>'+
        '<a title="Share on Pinterest" target="_blank" id="bg_ptr_icon"><img src="https://bixgrow.s3.us-west-2.amazonaws.com/creatives/1632474317_pinterest.svg"></a>'+
        '<a title="Share on LinkedIn" target="_blank" id="bg_ln_icon"><img src="https://bixgrow.s3.us-west-2.amazonaws.com/creatives/1632474346_linkedin.svg"></a>'+
    '</div>'+
'</div>';
//#endregion advanced
if(popupType == 'basic'){
  BIXGROW_mask.insertAdjacentHTML('beforeend', popupBasic);
}
if(popupType == 'simple'){
  BIXGROW_mask.insertAdjacentHTML('beforeend', popupSimple);
}
if(popupType == 'advanced'){
  BIXGROW_mask.insertAdjacentHTML('beforeend', popupAdvanced);
}
if(parseFloat(Shopify.checkout['total_price'])>=parseFloat(showIfMoreThan))
{
  bixgrowIsShowPopup();
}
var BGSubmitBtn = document.getElementById("BGSubmitBtn");
var BIXGROW_close_btn = document.getElementById("BIXGROW_close_btn");
var BGCard = document.getElementById("BGCard");


if (BIXGROW_close_btn !== null){
BIXGROW_close_btn.addEventListener("mouseover", function( event ) {
    event.target.style.color = "#aaaaaa";
  }, false);

BIXGROW_close_btn.addEventListener("mouseout", function( event ) {
event.target.style.color = "#000";
}, false);
/**close popup */
BIXGROW_close_btn.onclick = function () {
    BIXGROW_mask.style.display = "none";
    BGCard.style.display = "none";
  };
}
if(closeOnBg == 1){
  window.onclick = function (event) {
    if (event.target == BIXGROW_mask) {
      BIXGROW_mask.style.display = "none";
      BGCard.style.display = "none";
    }
  };
}


function copyReferralLinkToClipboard() {
  var textBox = document.getElementById('BIXGROW_input_reflink');
  textBox.select();
  document.execCommand("copy");
}
function copyCouponCodeToClipboard() {
  var textBox = document.getElementById('BIXGROW_input_coupon');
  textBox.select();
  document.execCommand("copy");
}

@php
   $bixgrowUrl ='http://'.config('endpoint.api_domain');
@endphp
function checkoutPopupRegisterBixgrow(event) {
  event.preventDefault();
  document.querySelector('#BIXGROW_submit_btn').innerHTML = 'Please wait...';
  let xhttp = new XMLHttpRequest;
  xhttp.open("POST", "{{$bixgrowUrl}}"+"/api/partner/checkout_popup_register", true);
  xhttp.setRequestHeader("Content-Type", "application/json");
  let dataRegister={
    email : Shopify.checkout['email'],
    shop : Shopify.shop,
    total_price : Shopify.checkout['total_price'],
    first_name : Shopify.checkout['billing_address']['first_name'],
    last_name : Shopify.checkout['billing_address']['last_name'],
    affiliate_id :  bgGetCookie('bgaffilite_id')
  }
  xhttp.send(JSON.stringify(dataRegister));
  xhttp.onload = function() {
    let obj = JSON.parse(this.responseText);
    if(Object.keys(obj).length>0)
    {
      document.getElementById("BIXGROW_input_reflink").value = obj.reflink;
      document.getElementById("BIXGROW_input_coupon").value = obj.couponCode;
      let bixgrowMessageFb = "https://www.facebook.com/sharer.php?u="+obj.reflink+ "&quote="+ obj.sharing_message;
      let bixgrowMessageTw ="https://twitter.com/intent/tweet?url="+obj.reflink+ "&text="+ obj.sharing_message;
      let bixgrowMessagePi ="http://pinterest.com/pin/create/link/?url=" + obj.reflink;
      let bixgrowMessageLi ="https://www.linkedin.com/shareArticle?mini=true&url=" + obj.reflink;
      let bixElementFbIcon =  document.getElementById("bg_fb_icon");
      let bixElementTwIcon =  document.getElementById("bg_tw_icon");
      let bixElementPiIcon =  document.getElementById("bg_ptr_icon");
      let bixElementLiIcon =  document.getElementById("bg_ln_icon");
      let bixShareWrapper = document.getElementById("BIXGROW_share_wrapper");
      bixElementFbIcon.href = bixgrowMessageFb;
      bixElementTwIcon.href = bixgrowMessageTw;
      bixElementPiIcon.href = bixgrowMessagePi;
      bixElementLiIcon.href = bixgrowMessageLi;

     if(shareArray.length > 0 && popupType == 'advanced'){
        bixShareWrapper.style.display = "flex";
        shareArray.forEach(element => {
            if(element == 'Facebook'){
                bixElementFbIcon.style.display = "inline-block";
            }
            if(element == 'Twitter'){
                bixElementTwIcon.style.display = "inline-block";
            }
            if(element == 'Pinterest'){
                bixElementPiIcon.style.display = "inline-block";
            }
            if(element == 'Linkedln'){
                bixElementLiIcon.style.display = "inline-block";
            }
        })
     }else{
    if(bixShareWrapper){
            bixShareWrapper.style.display = "none";
        }
    }

      if(showArray.length > 0 && popupType == 'advanced'){

        let bg_referral_link = document.getElementById("bg_referral_link");

        let bg_coupon_code = document.getElementById("bg_coupon_code");

        showArray.forEach(element => {

          if(element == 'Referral Link'){

            bg_referral_link.style.display = "block";

          }

          if(element == 'Coupon Code' && obj.couponCode != ''){

            bg_coupon_code.style.display = "block";

          }

        });

      }
      let BIXGROW_active_link= document.getElementById("BIXGROW_active_link");
      BIXGROW_active_link.remove();
    }
    else
    {
     let bgMask= document.getElementById("BIXGROW_mask");
     bgMask.remove();
    }
  }
}

function bixgrowIsShowPopup(){
  let xhttp = new XMLHttpRequest;
  xhttp.open("POST", "{{$bixgrowUrl}}"+"/api/partner/is_show_checkout_popup_register", true);
  xhttp.setRequestHeader("Content-Type", "application/json");
  let dataRegister={
    email : Shopify.checkout['email'],
    shop : Shopify.shop
  }
  xhttp.send(JSON.stringify(dataRegister));
  xhttp.onload = function() {
    let obj = JSON.parse(this.responseText);
    if(Object.keys(obj).length>0)
    {
      if(bgGetCookie('bgShowOnce')==1 && showOnce==1)
      {
        BIXGROW_mask.style.display = "none";
      }
      else
      {
        BIXGROW_mask.style.display = "block";
        bgSetCookie('bgShowOnce', 1, 30);
      }
    }
  }
}
function bgGetCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
          c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
          return c.substring(name.length, c.length);
      }
  }
  return "";
}
function bgSetCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}


