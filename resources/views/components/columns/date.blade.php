@props(['value'])

<div>
    {{ \Illuminate\Support\Carbon::make($value)->toFormattedDateString() }}
</div>