@php
    $layout_path = asset(config('custom.layout_path'));
    $newsTicker = '';
    if(count($last_tickers))
    {
      $newsTicker .= '<div id="newsticker" class="Box">
      <div class="Corner-tl" style="background-image:url('.$layout_path.'/images/general/corner-tl.gif);"></div>
      <div class="Corner-tr" style="background-image:url('.$layout_path.'/images/general/corner-tr.gif);"></div>
      <div class="Border_1" style="background-image:url('.$layout_path.'/images/general/border-1.gif);"></div>
      <div class="BorderTitleText" style="background-image:url('.$layout_path.'/images/general/title-background-green.gif);"></div><img id="ContentBoxHeadline" class="Title" src="'.$layout_path.'/images/general/headline-newsticker.gif" alt="Contentbox headline">    
      <div class="Border_2">
      <div class="Border_3">
      <div class="BoxContent" style="background-image:url('.$layout_path.'/images/general/scroll.gif);">';
      foreach($last_tickers as $ticker)
      {
          $newsTicker .= '<div id="TickerEntry-'.$ticker->forumId.'" class="Row" onclick="TickerAction(&quot;TickerEntry-'.$ticker->forumId.'&quot;)">
          <div class="Odd">
          <div class="NewsTickerIcon" style="background-image:url('.$layout_path.'/images/general/newsicon_support_small.gif)"></div>
          <div id="TickerEntry-'.$ticker->forumId.'-Button" class="NewsTickerExtend" style="background-image: url('.$layout_path.'/images/general/plus.gif);"></div>
          <div class="NewsTickerText">
            <span class="NewsTickerDate"> '.date('M d Y', $ticker->post_date).' &nbsp;&nbsp;-&nbsp;</span>
            <div id="TickerEntry-'.$ticker->forumId.'-ShortText" class="NewsTickerShortText" style="display: block;">'.substr(Functions::showPost('', htmlspecialchars($ticker->post_text), $ticker->post_smile), 0, 70).'...</div>
            <div id="TickerEntry-'.$ticker->forumId.'-FullText" class="NewsTickerFullText" style="display: none;">'.Functions::showPost('', htmlspecialchars($ticker->post_text), $ticker->post_smile).'</div>
          </div>
          </div>
          </div>';
      }

      $newsTicker .= '</div>
      </div>
      </div>
      <div class="Border_1" style="background-image:url('.$layout_path.'/images/general/border-1.gif);"></div>
      <div class="CornerWrapper-b"><div class="Corner-bl" style="background-image:url('.$layout_path.'/images/general/corner-bl.gif);"></div></div>
      <div class="CornerWrapper-b"><div class="Corner-br" style="background-image:url('.$layout_path.'/images/general/corner-br.gif);"></div></div>
      </div>';
    }
    
    $featuredArticle = '';
    if(!empty($last_featuredArticle))
    {
      $featuredArticle .= '<div id="featuredarticle" class="Box">
      <div class="Corner-tl" style="background-image:url('.$layout_path.'/images/general/corner-tl.gif);"></div>
      <div class="Corner-tr" style="background-image:url('.$layout_path.'/images/general/corner-tr.gif);"></div>
      <div class="Border_1" style="background-image:url('.$layout_path.'/images/general/border-1.gif);"></div>
      <div class="BorderTitleText" style="background-image:url('.$layout_path.'/images/general/title-background-green.gif);"></div><img id="ContentBoxHeadline" class="Title" src="'.$layout_path.'/images/general/headline-featuredarticle.gif" alt="Contentbox headline">    
      <div class="Border_2">
        <div class="Border_3">
          <div class="BoxContent" style="background-image:url('.$layout_path.'/images/general/scroll.gif);"><a id="Link" style="position: absolute; margin-bottom: 10px; top: 2px;" href="/forum/communityboards/thread/'.$last_featuredArticle->forumId.'">» read more</a><div id="TeaserText">'.substr(Functions::showPost('', htmlspecialchars($last_featuredArticle->post_text), $last_featuredArticle->post_smile), 0, 400).'...</div>
          </div>
        </div>
      </div>
      <div class="Border_1" style="background-image:url('.$layout_path.'/images/general/border-1.gif);"></div>
      <div class="CornerWrapper-b"><div class="Corner-bl" style="background-image:url('.$layout_path.'/images/general/corner-bl.gif);"></div></div>
      <div class="CornerWrapper-b"><div class="Corner-br" style="background-image:url('.$layout_path.'/images/general/corner-br.gif);"></div></div>
    </div>';
    }
@endphp

<x-layout :$pageTitle :$subtopic :$newsTicker :$featuredArticle>
    @if(count($last_threads))
        @foreach($last_threads as $thread)
            <div class="NewsHeadline" style="margin-bottom: 10px;">
              <div class="NewsHeadlineBackground" style="background-image:url({{$layout_path}}/images/general/newsheadline_background.gif)">
                <img src="{{$layout_path}}/images/general/newsicon_community_big.gif" class="NewsHeadlineIcon" alt="">
                <div class="NewsHeadlineDate">{{date('M d Y', $thread->post_date)}} - </div>
                <div class="NewsHeadlineText">{{htmlspecialchars($thread->post_topic)}}</div>
              </div>
            </div>
            <table style="clear:both" border="0" cellpadding="0" cellspacing="0" width="100%"><tbody>
            <tr>{!!Functions::showPost('', htmlspecialchars($thread->post_text), $thread->post_smile)!!}</tr><tr><td><div style="text-align:right;margin:10px 10px 0 0;">
            <a href="/forum/communityboards/thread/{{$thread->forumId}}">» Comment on this news</a></div></td></tr></tbody>
            </table>
        @endforeach
    @else
        <h3>No news. Go forum and make new thread on board News.</h3>
    @endif
</x-layout>
