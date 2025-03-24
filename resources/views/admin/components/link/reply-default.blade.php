@isset($permission)
    @if (admin_permission_by_name($permission))
        <a href="{{ $href ?? "javascript:void(0)" }}" class="btn btn--base {{ $class ?? "" }}"><i class="fa fa-reply"></i></a>
    @endif
@else
    <a href="{{ $href ?? "javascript:void(0)" }}" class="btn btn--base {{ $class ?? "" }}"><i class="fa fa-reply"></i></a>
@endisset
