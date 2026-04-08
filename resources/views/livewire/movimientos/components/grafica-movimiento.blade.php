<div>
    {!! $chartData->container() !!}
    <script src="{{ $chartData->cdn() }}"></script>
    {{ $chartData->script() }}
</div>
