<div>
    <div class="title-box">
        {!! $icon !!} {{ $elementName }}
    </div>
    {{-- <p>prateek</p> --}}
    {{-- <div class="box">
        {{ $showname->name ?? '' }} <br>
        <hr>
        {{ $showname->description ?? '' }}
        {{ $showname->notification ?? '' }}
    </div> --}}
    <div class="footer-box" style="text-align: right; padding: 5px;">
        {{-- <i class="fas fa-tasks settings-button" onclick="loadContitions('{{ $type }}', {{ isset($element) ? $element->id : 0 }}, this);"></i> --}}
        @if ($type !== 'trigger')
            <i class="fas fa-cog settings-button"
                onclick="loadSettings('{{ $type }}', {{ isset($element) ? $element->id : 0 }}, this);"></i>
        @endif
    </div>
</div>
