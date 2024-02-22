@props(['value'])

<div>
    {{ \Illuminate\Support\Carbon::make($value)->diffForHumans(['parts'=>2]) }}
</div>