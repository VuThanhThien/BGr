<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
@if (isset($data['brand_name']))
<title>{{$data['brand_name']}}</title>
@endif
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
@if (isset($data['brand_name']))
<meta property="og:title" content="{{$data['brand_name']}}">
@endif
<meta property="og:type" content="website">
@if (isset($data['shop']->shop))
<meta property="og:url" content="{{$data['shop']->shop}}"> 
@endif
@if (isset($data['file']->image))
<meta property="og:image" content="{{$data['file']->image}}"> 
@endif
<meta name="twitter:card" content="summary" />
<link rel="manifest" href="site.webmanifest">
<link rel="apple-touch-icon" href="undefined">
</head>
<body>
<div style="">
    @if (isset($data['file']->image))
    <img src="{{$data['file']->image}}" style="max-width:100%" />
<p></p>
@endif
</div>
@if (isset($data['file']->link))
<script type="text/javascript">
    let link='{{$data['file']->link}}';
    link = generateLinkAffiliate(link)+ '{{$data['groupId']}}'+':'+'{{$data['code']}}';
    window.location = link;
    function generateLinkAffiliate(link){
        if(link)
      {
        let arrLink = link.split("?");
        if (arrLink.length > 1 && arrLink[1] !== "") {
          return link + "&bg_ref=";
        } else {
           return link + "?bg_ref=";
        }
      }
      else{
        return '';
      }
    }
</script>
@endif
</body>
</html>