@switch($status)
    @case('pending')
        <span class="label text-uppercase label-default {{$floatRight == 1 ? 'float-right' : ''}}">{{ __('pending') }}</span>
        @break
    @case('to accept')
        <span class="label text-uppercase label-warning {{$floatRight == 1 ? 'float-right' : ''}}">{{ __('to_accept') }}</span>
        @break
    @case('in progress')
    <span class="label text-uppercase label-warning {{$floatRight == 1 ? 'float-right' : ''}}">{{ __('in_progres') }}</span>
        @break
    @case('comments')
        <span class="label text-uppercase label-warning {{$floatRight == 1 ? 'float-right' : ''}}">{{ __('remarks') }}</span>
        @break
    @case('accepted')
        <span class="label text-uppercase label-primary {{$floatRight == 1 ? 'float-right' : ''}}">{{ __('accepted') }}</span>
        @break
    @case('closed')
        <span class="label text-uppercase label-default {{$floatRight == 1 ? 'float-right' : ''}}">{{ __('closed') }}</span>
        @break
    @case('canceled')
        <span class="label text-uppercase label-danger {{$floatRight == 1 ? 'float-right' : ''}}">{{ __('canceled') }}</span>
        @break
    @case('suspended')
        <span class="label text-uppercase label-danger {{$floatRight == 1 ? 'float-right' : ''}}">{{ __('suspended') }}</span>
        @break
    @case('')
        <span class="label text-uppercase label-default {{$floatRight == 1 ? 'float-right' : ''}}">{{ __('pending') }}</span>
        @break
    @default
@endswitch
