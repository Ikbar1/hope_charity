@if (is_file(public_path('images/'.$file)))
<img src="{{ asset('images/'.$file) }}" alt="{{ $file === 'hero-mosque.jpg' || $file === 'program-mosque.jpg' ? $copy['illustration'] : $copy['concept'] }}" width="385" height="465" @if (! ($eager ?? false)) loading="lazy" @endif>
@else
<span class="media-fallback">{{ $copy['illustration'] }}</span>
@endif
