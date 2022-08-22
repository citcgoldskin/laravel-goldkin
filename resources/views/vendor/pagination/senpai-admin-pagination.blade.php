@if ($paginator->total())
    <section id="pager">
        <div class="pager_area">
            <ul>
                @if (!$paginator->onFirstPage())
                    <li class="prev_page"><a href="{{ $paginator->previousPageUrl() }}"></a></li>
                @endif

                @if($paginator->currentPage() > 2)
                    <li><a href="{{ $paginator->url(1) }}">1</a></li>
                @endif

                @if($paginator->currentPage() > 3)
                    <li>・・・</li>
                @endif

                @if($paginator->currentPage() - 1 > 0)
                        <li><a href="{{ $paginator->previousPageUrl() }}"><?php echo ($paginator->currentPage() - 1);?></a></li>
                @endif

                <li class="now_page"><?php echo $paginator->currentPage();?></li>

                @if($paginator->currentPage() < $paginator->lastPage())
                    <li><a href="{{ $paginator->nextPageUrl() }}"><?php echo ($paginator->currentPage() + 1);?></a></li>
                @endif

                @if($paginator->currentPage() + 2 < $paginator->lastPage())
                    <li>・・・</li>
                @endif

                @if($paginator->currentPage() + 1 < $paginator->lastPage())
                    <li><a href="{{ $paginator->url($paginator->lastPage()) }}"><?php echo $paginator->lastPage();?></a></li>
                @endif

                @if (!$paginator->onLastPage())
                    <li class="next_page"><a href="{{ $paginator->nextPageUrl() }}"></a></li>
                @endif
            </ul>
        </div>
    </section>
@endif

