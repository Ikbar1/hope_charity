<article class="program-card" data-program-key="{{ $program->key }}">
@if (in_array($program->order,[1,7]))
@include('public.partials.image',['file'=>$program->order === 1 ? 'program-mosque.jpg' : 'program-aid.jpg'])
@else
<div class="program-card-art" aria-hidden="true">{{ ['♜','◇','◉','♡','✧','☾','♡','✧'][$program->order-1] }}</div>
@endif
<div class="program-body"><h3>{{ $program->name }}</h3><p>{{ $copy['descriptions'][$program->order-1] }}</p></div></article>
