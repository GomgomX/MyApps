<style type="text/css">.page{display:inline-block;padding:2px 9px;margin-right:2px;border-radius:3px;border:1px solid silver;background:#e9e9e9;box-shadow:inset 0 1px 0 rgba(255,255,255,.8),0 1px 3px rgba(0,0,0,.1);font-size:.875em;font-weight:700;text-decoration:none;color:#717171;text-shadow:0 1px 0 rgba(255,255,255,1)}.page.gradient:hover,.page:hover{color:#fff;text-decoration:none;background:#fefefe;background:-webkit-gradient(linear,0 0,0 100%,from(#FEFEFE),to(#f0f0f0));background:-moz-linear-gradient(0% 0 270deg,#FEFEFE,#f0f0f0)}.pagination.dark{background:#414449;color:#feffff}.page.dark{border:1px solid #32373b;background:#3e4347;box-shadow:inset 0 1px 1px rgba(255,255,255,.1),0 1px 3px rgba(0,0,0,.1);color:#feffff;text-shadow:0 1px 0 rgba(0,0,0,.5)}.page.dark.gradient:hover,.page.dark:hover{background:#3d4f5d;background:-webkit-gradient(linear,0 0,0 100%,from(#547085),to(#3d4f5d));background:-moz-linear-gradient(0% 0 270deg,#547085,#3d4f5d)}.page.dark.active{color:#fff;text-decoration:none;border-radius:3px;border:1px solid silver;background:#2f3237;box-shadow:inset 0 0 8px rgba(0,0,0,.5),0 1px 0 rgba(255,255,255,.1)}.page.dark.gradient{background:-webkit-gradient(linear,0 0,0 100%,from(#565b5f),to(#3e4347));background:-moz-linear-gradient(0% 0 270deg,#565b5f,#3e4347)}.page.dark.disabled{background:-webkit-gradient(linear,0 0,0 100%,from(#36393B),to(#282A2B));background:-moz-linear-gradient(0% 0 270deg,#36393B,#282A2B);color:#CCC}</style>
@if ($paginator->hasPages())
    {{-- <nav class="d-flex justify-items-center justify-content-between"> --}}
        {{-- <div class="d-flex justify-content-between flex-fill d-sm-none">
            <ul class="pagination"> --}}
                {{-- Previous Page Link --}}
                {{-- @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">@lang('pagination.previous')</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a>
                    </li>
                @endif --}}

                {{-- Next Page Link --}}
                {{-- @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">@lang('pagination.next')</span>
                    </li>
                @endif
            </ul>
        </div> --}}

        <div style="padding-top: 9px">
        {{-- <div class="d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between"> --}}
            {{-- <div> --}}
                {{-- <p class="small text-muted"> --}}
                <p style="margin-top: 3px;position:absolute"><span style="float:left;text-align:center;font-weight:bold;font-size:11">
                    {!! __('Showing') !!}
                    {{-- <span class="fw-semibold"> --}}
                        {{ $paginator->firstItem() }}
                    {{-- </span> --}}
                    {!! __('to') !!}
                    {{-- <span class="fw-semibold"> --}}
                        {{ $paginator->lastItem() }}
                    {{-- </span> --}}
                    {!! __('of') !!}
                    {{-- <span class="fw-semibold"> --}}
                        {{ $paginator->total() }}
                    {{-- </span> --}}
                    {!! __('results') !!}
                {{-- </p> --}}
                </span></p>
            {{-- </div> --}}
            
            <span style="float:right;text-align:center;font-weight:bold;font-size:12">
            {{-- <div>
                <ul class="pagination"> --}}
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        {{-- <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')"><span class="page-link" aria-hidden="true"> --}}
                            <span style="pointer-events: none;" class="page dark previous disabled">&lsaquo;</span>
                        {{-- </span></li> --}}
                    @else
                        {{-- <li class="page-item">
                            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                        </li> --}}
                        <a href="{{ $paginator->previousPageUrl() }}"><span style="pointer-events: none;" class="page dark previous">&lsaquo;</span></a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            {{-- <li class="page-item disabled" aria-disabled="true"><span class="page-link"> --}}
                                <span style="pointer-events: none;" class="page dark disabled">{{ $element }}</span>
                            {{-- </span></li> --}}
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    {{-- <li class="page-item active" aria-current="page"><span class="page-link"> --}}
                                        <span style="pointer-events: none;" class="page dark active">{{ $page }}</span>
                                    {{-- </span></li> --}}
                                @else
                                    {{-- <li class="page-item">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li> --}}
                                    <a href="{{ $url }}"><span style="pointer-events: none;" class="page dark disabled">{{ $page }}</span></a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        {{-- <li class="page-item">
                            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                        </li> --}}
                        <a href="{{ $paginator->nextPageUrl() }}"><span style="pointer-events: none;" class="page dark next">&rsaquo;</span></a>
                    @else
                        {{-- <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')"><span class="page-link" aria-hidden="true"> --}}
                            <span style="pointer-events: none;" class="page dark next disabled">&rsaquo;</span>
                        {{-- </span></li> --}}
                    @endif
                {{-- </ul>
            </div> --}}
            </span>
        </div>
        {{-- </div> --}}
    {{-- </nav> --}}
@endif
