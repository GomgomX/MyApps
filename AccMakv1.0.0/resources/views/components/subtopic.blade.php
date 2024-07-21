@props(['page', 'pageTitle', 'subtopic', 'layout_path'])

<a href='/{{$page}}'>
    <div id='submenu_{{$subtopic}}' class='Submenuitem' onMouseOver='MouseOverSubmenuItem(this)' onMouseOut='MouseOutSubmenuItem(this)'>
      <div class='LeftChain' style='background-image:url({{$layout_path}}/images/general/chain.gif);'></div>
      <div id='ActiveSubmenuItemIcon_{{$subtopic}}' class='ActiveSubmenuItemIcon' style='background-image:url({{$layout_path}}/images/menu/icon-activesubmenu.gif);'></div>
      <div class='SubmenuitemLabel'>{{$pageTitle}}</div>
      <div class='RightChain' style='background-image:url({{$layout_path}}/images/general/chain.gif);'></div>
    </div>
  </a>