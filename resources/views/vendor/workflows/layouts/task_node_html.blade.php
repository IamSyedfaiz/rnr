<div>
    <div class="title-box">
        {!! $icon !!} {{ $elementName }}
    </div>
    <div class="footer-box" style="text-align: right; padding: 5px;">
        {{-- <i class="fas fa-tasks settings-button" onclick="loadContitions('{{ $type }}', {{ isset($element) ? $element->id : 0 }}, this);"></i> --}}
        {{-- @if ($type !== 'trigger')
            <i class="fas fa-cog settings-button"
                onclick="loadSettings('{{ $type }}', {{ isset($element) ? $element->id : 0 }}, this);"></i>
        @endif --}}
        @if ($type !== 'trigger')
            <i class="fas fa-cog settings-button" data-type="{{ $type }}"
                data-element-id="{{ isset($element) ? $element->id : 0 }}"></i>
        @endif
    </div>
</div>
