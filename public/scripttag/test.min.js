var bixgrowUrl = 'https://api.bixgrow.test';
var gbRefParam = bgGetParameterByName('bg_ref');
var bgGroup = 0;
if(gbRefParam)
{
    if(gbRefParam.indexOf(':')!=-1)
    {
        let tempgbRefParam = gbRefParam.split(':');
         gbRefParam =  tempgbRefParam[1];   
         bgGroup =  tempgbRefParam[0];
    }
}
function bgGetParameterByName(name, url = window.location.href) {
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

function bgSetCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
function bgSetCookieByUnixTime(cname, cvalue, unixTime) {
    var d = new Date(unixTime*1000);
    // d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
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

function bguuid() {
    var temp_url = URL.createObjectURL(new Blob());
    var uuid = temp_url.toString();
    URL.revokeObjectURL(temp_url);
    return uuid.split(/[:\/]/g).pop();
}

function bgUpdateCart(affiliateId) {

    var xhttp = new XMLHttpRequest;
    xhttp.open("POST", "/cart/update.js", !0);
    xhttp.setRequestHeader("Accept", "application/json, text/javascript, */*; q=0.01");
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
    xhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhttp.send("attributes[bg_affiliate_id]=".concat(encodeURIComponent(affiliateId)));
}

function bgPostEvent(data) {

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            res = JSON.parse(this.responseText);
            if(res.event_type == 'click') {
                bgSetCookieByUnixTime('bgclient_id', data.client_id, res.expire_at);
                bgSetCookieByUnixTime('bgaffilite_id', data.aff_id, res.expire_at);
                bgSetCookieByUnixTime('bglast_click', new Date().getTime(), res.expire_at);
                bgSetCookieByUnixTime('bgexpire_time', res.expire_at, res.expire_at);
                bgSetCookieByUnixTime('bggroups',bgGroup , res.expire_at);
            }
        }else{
            if(data.event_type == 'add_to_cart') {
                clearInterval(bgSetInterval);
            }
        }
    };
    xhttp.open("POST", bixgrowUrl+"/api/bg_track", true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.send(JSON.stringify(data));
}

if(gbRefParam){
    if( bgGetCookie('bgclient_id') === "") {
        let payload = {
            aff_id: gbRefParam,
            client_id: bguuid(),
            event_type:'click',
        }
        bgPostEvent(payload);
    }else{
        if( bgGetCookie('bgaffilite_id') !=  gbRefParam ) {
            let payload = {
                aff_id: gbRefParam,
                client_id: bgGetCookie('bgclient_id'),
                event_type:'click',
            }
            bgPostEvent(payload);
        }else{
            if (new Date().getTime()-bgGetCookie('bglast_click') > 60*1000){
                let payload = {
                    aff_id: gbRefParam,
                    client_id: bgGetCookie('bgclient_id'),
                    event_type:'click',
                }
                bgPostEvent(payload);
            }
        }
    }

}

var bgSetInterval = setInterval(function(){
    let currentShopifyCart = bgGetCookie('cart');
    if(bgGetCookie('bgclient_id') !== "" && currentShopifyCart !== "" && (bgGetCookie('bgcart') != bgGetCookie('cart')) ) {
        bgSetCookie('bgcart', currentShopifyCart, 100);
        clearInterval(bgSetInterval);
        let payload = {
            aff_id: bgGetCookie('bgaffilite_id'),
            client_id: bgGetCookie('bgclient_id'),
            event_type: 'add_to_cart',
            cart_token: currentShopifyCart
        }
        bgPostEvent(payload);
    }
},1000);

if(Shopify && Shopify.Checkout && Shopify.Checkout.step === "thank_you"  ) {
    if(bgGetCookie('bgclient_id') !== "") {
        let payload = {
            aff_id: bgGetCookie('bgaffilite_id'),
            client_id: bgGetCookie('bgclient_id'),
            event_type: 'checkout_finish',
            checkout_token: Shopify.checkout.token
        }
        bgPostEvent(payload);
    }

}
if(typeof  bixgrow_groups_discount_code_customer !== 'undefined')
{
    if( bixgrow_groups_discount_code_customer.includes(parseInt(bgGetCookie('bggroups'))))
    {
        bixgrowAutomaticCouponCustomer();
    }
}
function bixgrowAutomaticCouponCustomer(){
    let xhttp = new XMLHttpRequest;
    xhttp.open("GET", bixgrowUrl+"/api/automatic-coupon-customer?"+'shop='+Shopify.shop+ '&affiliateId='+bgGetCookie('bgaffilite_id'), true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.send();
    xhttp.onload = function() {
      let obj = JSON.parse(this.responseText);
      if(Object.keys(obj).length>0)
      {
        let couponCodePath = encodeURI("/discount/" + obj.couponCode);
        couponCodePath = couponCodePath.replace("#", "%2523");
        var iframeBixgrow = document.createElement('iframe');
        iframeBixgrow.style.cssText = 'height: 0; width: 0; display: none;';
        iframeBixgrow.src = couponCodePath;
        iframeBixgrow.innerHTML = 'Your browser does not support iframes';
        let app = document.querySelector('body');
        app.prepend(iframeBixgrow);
        // let iframeDiscount = '<iframe src="' + couponCodePath + '" height=0 width=0 frameborder=0 marginheight=0 marginwidth=0 scrolling=no onload="scroll(0,0);" style="display: none"></iframe>';
        // document.write(iframeDiscount + '>Your browser does not support iframes</iframe>');
      }
    }
  }
