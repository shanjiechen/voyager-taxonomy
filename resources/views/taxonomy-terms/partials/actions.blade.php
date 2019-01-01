@php $action = new $action($dataType, $data); @endphp

@if ($action->shouldActionDisplayOnDataType())
    @can($action->getPolicy(), $data)
        @if ($action->getPolicy() == 'read')

        @elseif ($action->getPolicy() == 'edit')
            <a href="{{ route('voyager.taxonomy-terms.edit', ['vid' => $vid, 'id' => 0]) }}" title="{{ $action->getTitle() }}" {!! $action->convertAttributesToHtml() !!}>
                <i class="{{ $action->getIcon() }}"></i> <span class="hidden-xs hidden-sm">{{ $action->getTitle() }}</span>
            </a>
        @else
            <a href="{{ $action->getRoute($dataType->name) }}" title="{{ $action->getTitle() }}" {!! $action->convertAttributesToHtml() !!}>
                <i class="{{ $action->getIcon() }}"></i> <span class="hidden-xs hidden-sm">{{ $action->getTitle() }}</span>
            </a>
        @endif
    @endcan
@endif