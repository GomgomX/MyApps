
@props(['pageTitle', 'subtopic', 'header', 'newsTicker', 'featuredArticle'])
@php
  $layout_path = asset(config('custom.layout_path'));
  $layout_header = '<script type="text/javascript">
  function GetXmlHttpObject()
  {
  var xmlHttp=null;
  try
  {
  xmlHttp=new XMLHttpRequest();
  }
  catch (e)
  {
  try
      {
      xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
      }
  catch (e)
      {
      xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
  }
  return xmlHttp;
  }

  function MouseOverBigButton(source)
  {
  source.firstChild.style.visibility = "visible";
  }
  function MouseOutBigButton(source)
  {
  source.firstChild.style.visibility = "hidden";
  }
  function BigButtonAction(path)
  {
  window.location = path;
  }
  var';
  if(auth()->guard('account')->check()) {
    $layout_header .= "loginStatus=1; loginStatus='true';";
  } else { 
    $layout_header .= "loginStatus=0; loginStatus='false';"; 
  }
  $layout_header .= "var activeSubmenuItem='".(isset($subtopic) ? $subtopic : '')."';  var IMAGES=0; IMAGES='".$layout_path."/images'; var LINK_ACCOUNT=0; LINK_ACCOUNT='".$server_config['url']."';</script>";
  echo $layout_header;
@endphp

<html>
<head>
  <title>{{isset($pageTitle) ? $pageTitle.' - ' : ''}}{{config('app.name')}}</title> {{-- $server_config['serverName'] --}}
  <meta name="description" content="Tibia is a free massive multiplayer online role playing game (MMORPG)." />
  <meta name="author" content="Gomgom" />
  <meta http-equiv="content-language" content="en" />
  <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
  <meta name="keywords" content="free online game, free multiplayer game, ots, open tibia server" />
  <meta name="csrf-token" content="{{csrf_token()}}">
  <!--  regular browsers -->
  <link rel="shortcut icon" href="{{$layout_path}}/images/favicon.ico" type="image/x-icon">
  <!-- For iPad with high-resolution Retina display running iOS = 7: -->
  <link rel="apple-touch-icon" sizes="152x152" href="{{$layout_path}}/images/apple-touch-icon-152x152.png">
  <!-- For iPad with high-resolution Retina display running iOS = 6: -->
  <link rel="apple-touch-icon" sizes="144x144" href="{{$layout_path}}/images/apple-touch-icon-144x144.png">
  <!-- For iPhone with high-resolution Retina display running iOS = 7: -->
  <link rel="apple-touch-icon" sizes="120x120" href="{{$layout_path}}/images/apple-touch-icon-120x120.png">
  <!-- For iPhone with high-resolution Retina display running iOS = 6: -->
  <link rel="apple-touch-icon" sizes="114x114" href="{{$layout_path}}/images/apple-touch-icon-114x114.png">
  <!-- For the iPad mini and the first- and second-generation iPad on iOS = 7: -->
  <link rel="apple-touch-icon" sizes="76x76" href="{{$layout_path}}/images/apple-touch-icon-76x76.png">
  <!-- For the iPad mini and the first- and second-generation iPad on iOS = 6: -->
  <link rel="apple-touch-icon" sizes="72x72" href="{{$layout_path}}/images/apple-touch-icon-72x72.png">
  <!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
  <link rel="apple-touch-icon" href="{{$layout_path}}/images/apple-touch-icon.png">
  <!-- Fallback for older devices: -->
  <link rel="apple-touch-icon-precomposed" href="{{$layout_path}}/images/apple-touch-icon-precomposed.png">
  <link href="{{$layout_path}}/basic.css" rel="stylesheet" type="text/css">
  <script type='text/javascript'> var IMAGES=0; IMAGES='{{$layout_path}}/images'; var g_FormField='';  var LINK_ACCOUNT=0; LINK_ACCOUNT='/';</script>
  <script type="text/javascript" src="{{$layout_path}}/initialize.js"></script>
  <script type="text/javascript" src="{{$layout_path}}/newsticker.js"></script>
  <script type="text/javascript" src="{{$layout_path}}/jquery.js"></script>
  <script type="text/javascript" src="{{$layout_path}}/interactions.js"></script>
  <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body onBeforeUnLoad="SaveMenu();" onUnload="SaveMenu();">
 <a name="top"></a>
  <div id="ArtworkHelper" style="background-image:url({{$layout_path}}/images/header/background-artwork.jpg);" >
    <div id="MainHelper" >
    <div id="Bodycontainer">
      <div id="ContentRow">
        <div id="MenuColumn">
          <div id="LeftArtwork">
            <img id="TibiaLogoArtworkTop" src="{{$layout_path}}/images/header/tibia-logo-artwork-top.gif" alt="logoartwork" onClick="window.location = '/';">
            <img id="LogoLink" src="{{$layout_path}}/images/header/tibia-logo-artwork-string.gif" onClick="window.location = '/';" alt="logoartwork">
          </div>
          
  <div id="Loginbox" >
    <div id="LoginTop" style="background-image:url({{$layout_path}}/images/general/box-top.gif)" ></div>
    <div id="BorderLeft" class="LoginBorder" style="background-image:url({{$layout_path}}/images/general/chain.gif)" ></div>
    <div class="Loginstatus" style="background-image:url({{$layout_path}}/images/loginbox/loginbox-textfield-background.gif)" >
      <div id="LoginstatusText_1" class="LoginstatusText" style="background-image:url({{$layout_path}}/images/loginbox/loginbox-font-you-are-not-logged-in.gif)" ></div>
    </div>
    <div id="LoginButtonContainer" style="background-image:url({{$layout_path}}/images/loginbox/loginbox-textfield-background.gif)" >
      <div id="LoginButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" > 
        <div onClick="LoginButtonAction();" onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);"><div class="Button" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif)" ></div>
          {{-- <div id="ButtonText" ></div> --}}
          <input id="ButtonText" class="BigButtonText" type="submit">
        </div>
      </div>
    </div>
    <div style="clear:both" ></div>
    <div class="Loginstatus" style="background-image:url({{$layout_path}}/images/loginbox/loginbox-textfield-background.gif)" >
      <div id="LoginstatusText_2" onClick="LoginstatusTextAction(this);" onMouseOver="MouseOverLoginBoxText(this);" onMouseOut="MouseOutLoginBoxText(this);" ><div id="LoginstatusText_2_1" class="LoginstatusText" style="background-image:url({{$layout_path}}/images/loginbox/loginbox-font-create-account.gif)" ></div><div id="LoginstatusText_2_2" class="LoginstatusText" style="background-image:url({{$layout_path}}/images/loginbox/loginbox-font-create-account-over.gif)" ></div></div>
    </div>
     <div id="BorderRight" class="LoginBorder" style="background-image:url({{$layout_path}}/images/general/chain.gif)" ></div>
    <div id="LoginBottom" class="Loginstatus" style="background-image:url({{$layout_path}}/images/general/box-bottom.gif)" ></div>
  </div>

<div id='Menu'>
<div id='MenuTop' style='background-image:url({{$layout_path}}/images/general/box-top.gif);'></div>

<x-topic topic="news" :$layout_path>
  <x-subtopic page="news/latestnews" pageTitle="Latest News" subtopic="latestnews" :$layout_path/>
  <x-subtopic page="news/archive" pageTitle="News Archive" subtopic="archive" :$layout_path/>
</x-topic>

<x-topic topic="account" :$layout_path>
  <x-subtopic page="account/accountmanagement" pageTitle="Account Managment" subtopic="accountmanagement" :$layout_path/>
  <x-subtopic page="account/createaccount" pageTitle="Create Account" subtopic="createaccount" :$layout_path/>
  <x-subtopic page="account/lostaccount" pageTitle="Lost Account?" subtopic="lostaccount" :$layout_path/>
  <x-subtopic page="account/serverrules" pageTitle="Server Rules" subtopic="serverrules" :$layout_path/>
  @if(config('custom.download_page'))
    <x-subtopic page="account/downloads" pageTitle="Downloads" subtopic="downloads" :$layout_path/>
  @endif
</x-topic>

<x-topic topic="community" :$layout_path>
  <x-subtopic page="community/characters" pageTitle="Characters" subtopic="characters" :$layout_path/>
  <x-subtopic page="community/whoisonline" pageTitle="Who Is Online?" subtopic="whoisonline" :$layout_path/>
  <x-subtopic page="community/highscores" pageTitle="Highscores" subtopic="highscores" :$layout_path/>
  <x-subtopic page="community/houses" pageTitle="Houses" subtopic="houses" :$layout_path/>
  <x-subtopic page="community/topfraggers" pageTitle="Top Fraggers" subtopic="topfraggers" :$layout_path/>
  <x-subtopic page="community/latestdeaths" pageTitle="Latest Deaths" subtopic="latestdeaths" :$layout_path/>
  <x-subtopic page="community/guilds" pageTitle="Guilds" subtopic="guilds" :$layout_path/>
  <x-subtopic page="community/supporlist" pageTitle="Suppor List" subtopic="supporlist" :$layout_path/>
</x-topic>

<x-topic topic="forum" :$layout_path>
  <x-subtopic page="forum/communityboards" pageTitle="Community Boards" subtopic="communityboards" :$layout_path/>
</x-topic>

<x-topic topic="library" :$layout_path>
  @if(config('custom.serverinfo_page'))
    <x-subtopic page="library/serverinfo" pageTitle="Server Info" subtopic="serverinfo" :$layout_path/>
  @endif
  <x-subtopic page="library/experiencetable" pageTitle="Experience Table" subtopic="experiencetable" :$layout_path/>
</x-topic>

@if(config('custom.shop_system'))
  <x-topic topic="shops" :$layout_path>
    <x-subtopic page="shop/buypoints" pageTitle="Buy Points" subtopic="buypoints" :$layout_path/>
    <x-subtopic page="shop/shopoffer" pageTitle="Shop Offer" subtopic="shopoffer" :$layout_path/>
    @if(auth()->guard('account')->check())
      <x-subtopic page="shop/transactionhistory" pageTitle="Transaction History" subtopic="transactionhistory" :$layout_path/>
      @if(auth()->guard('account')->user()->group_id >= config('custom.access_admin_panel'))
        <x-subtopic page="shop/shopadmin" pageTitle="! Shop Admin !" subtopic="shopadmin" :$layout_path/>
      @endif
    @endif
  </x-topic>
@endif

<div id='MenuBottom' style='background-image:url({{$layout_path}}/images/general/box-bottom.gif);'></div>
</div>
  <script type='text/javascript'>InitializePage();</script></div>
        <div id="ContentColumn">
          <div class="Content">
            <div id="ContentHelper">

            {!!!empty($newsTicker) ? $newsTicker : ''!!}

            {!!!empty($featuredArticle) ? $featuredArticle : ''!!}

    <div id="{{$subtopic}}" class="Box">
    <div class="Corner-tl" style="background-image:url({{$layout_path}}/images/content/corner-tl.gif);"></div>
    <div class="Corner-tr" style="background-image:url({{$layout_path}}/images/content/corner-tr.gif);"></div>
    <div class="Border_1" style="background-image:url({{$layout_path}}/images/content/border-1.gif);"></div>
    <div class="BorderTitleText" style="background-image:url({{$layout_path}}/images/content/title-background-green.gif);"></div>
    <img class="Title" src="{{$layout_path}}/images/header/headline-{{(isset($header) ? $header : $subtopic)}}.gif" alt="Contentbox headline" />
    <div class="Border_2">
      <div class="Border_3">
        <div class="BoxContent" style="background-image:url({{$layout_path}}/images/content/scroll.gif);">
          {{$slot}}
      </div>
      </div>
    </div>
    <div class="Border_1" style="background-image:url({{$layout_path}}/images/content/border-1.gif);"></div>

    <div class="CornerWrapper-b"><div class="Corner-bl" style="background-image:url({{$layout_path}}/images/content/corner-bl.gif);"></div></div>
    <div class="CornerWrapper-b"><div class="Corner-br" style="background-image:url({{$layout_path}}/images/content/corner-br.gif);"></div></div>
  </div>
           </div>
          </div>
          <div id="Footer">
@php
$time_end = microtime(true);
$time = $time_end - LARAVEL_START;
@endphp
            Account Maker made by Gomgom <font color="black">&#8226;</font> Layout by CipSoft GmbH<br/>Page has been viewed {{$page_views}} times <font color="black">&#8226;</font> Load: {{round($time, 2)}}s <font color="black">&#8226;</font> Visitors: <span id="visitors">{{$visitors}}</span>
          </div>
        </div>
        <div id="ThemeboxesColumn">
          <div id="RightArtwork">
            <img id="Monster" src="{{$layout_path}}/images/{{config('custom.logo_monster')}}.gif" alt="Monster of the Week">
            <img id="PedestalAndOnline" src="{{$layout_path}}/images/header/pedestal-and-online.gif" alt="Monster Pedestal and Players Online Box">
            <div id="PlayersOnline" onClick="window.location='/community/whoisonline'">
              @if($server_status['serverStatus_online'] == 1)
                <span id="players">{{$server_status['serverStatus_players']}}</span><br>Players Online
              @else
                <font color="red"><b>Server<br/>OFFLINE</b></font>
              @endif
            </div>
          </div>
  <div id="Themeboxes">
          
  <div id="NewcomerBox" class="Themebox" style="background-image:url({{$layout_path}}/images/themeboxes/newcomer/newcomerbox.gif);">
    <div class="ThemeboxButton" onClick="BigButtonAction('/account/createaccount')" onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif);"><div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);"></div>
      {{-- <div class="ButtonText" style="background-image:url({{$layout_path}}/images/buttons/_sbutton_jointibia.gif);"></div> --}}
      <input class="BigButtonText" type="submit" value="Join {{$server_config['serverName']}}">
    </div>
    <div class="Bottom" style="background-image:url({{$layout_path}}/images/general/box-bottom.gif);"></div>
  </div>

        </div>
      </div>
     </div>
    </div>
  </div>
  </div>
</body>
</html>