@if($item->isHeader)
    <li class="nav-item-header">
        <div class="text-uppercase font-size-xs line-height-xs">{{ $item->title }}</div>
        <i class="icon-menu" title="{{ $item->title }}"></i></li>
@else
    <li class="nav-item @if($item->hasChildren) nav-item-submenu @endif">
        <a href="{{ $item->url }}" class="nav-link">
            @if($item->iconClass)
                <i class="{{ $item->iconClass }}"></i>
            @endif
            <span>{{ $item->title }}</span>
        </a>
        @if($item->hasChildren)
            <ul class="nav nav-group-sub" data-submenu-title="{{ $item->title }}">
                {!! $children !!}
            </ul>
        @endif
    </li>
@endif
