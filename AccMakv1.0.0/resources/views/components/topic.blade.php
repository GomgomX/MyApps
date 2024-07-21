@props(['topic', 'layout_path'])
<div id='{{$topic}}' class='menuitem'>
    <span onClick="MenuItemAction('{{$topic}}')">
      <div class='MenuButton' style='background-image:url({{$layout_path}}/images/menu/button-background.gif);'>
        <div onMouseOver='MouseOverMenuItem(this);' onMouseOut='MouseOutMenuItem(this);'><div class='Button' style='background-image:url({{$layout_path}}/images/menu/button-background-over.gif);'></div>
          <span id='{{$topic}}_Lights' class='Lights'>
            <div class='light_lu' style='background-image:url({{$layout_path}}/images/menu/green-light.gif);'></div>
            <div class='light_ld' style='background-image:url({{$layout_path}}/images/menu/green-light.gif);'></div>
    
            <div class='light_ru' style='background-image:url({{$layout_path}}/images/menu/green-light.gif);'></div>
          </span>
          <div id='{{$topic}}_Icon' class='Icon' style='background-image:url({{$layout_path}}/images/menu/icon-{{$topic}}.gif);'></div>
          <div id='{{$topic}}_Label' class='Label' style='background-image:url({{$layout_path}}/images/menu/label-{{$topic}}.gif);'></div>
          <div id='{{$topic}}_Extend' class='Extend' style='background-image:url({{$layout_path}}/images/general/plus.gif);'></div>
        </div>
      </div>
    </span>
    <div id='{{$topic}}_Submenu' class='Submenu'>
      {{$slot}}
    </div>
    </div>