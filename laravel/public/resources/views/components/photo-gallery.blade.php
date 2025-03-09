<div>
    @if (!empty($photos))
        @foreach ($photos as $photo)
            <div style="display: flex">
                <img src="{{ $photo }}" alt="Photo" style="max-width: 20%; height: auto; margin-bottom: 10px;">
            </div>
        @endforeach
    @else
        <p>No photos available.</p>
    @endif
</div>
