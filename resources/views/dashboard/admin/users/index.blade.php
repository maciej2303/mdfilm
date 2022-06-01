@extends('layouts.app')
@section('title', 'Użytkownicy')



@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">

            <div class="ibox ">
                <div class="ibox-title">
                    <h5>{{ __('users') }}</h5>
                    <div class="ibox-tools">

                    </div>
                </div>

                <div class="ibox-content">
                    <div class="row m-b">
                        <div class="col-md-10">
                            <a href="{{route('users.create')}}" class="btn btn-primary"><i class="fas fa-plus"></i>
                                {{ __('add_user') }}</a>
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" placeholder="{{ __('find') }}" id="searchTerm"
                                value="{{$searchTerm}}">
                        </div>
                    </div>
                     <div class="row row-filters">
                        <div class="col m-b-sm">
                            <label class="m-t-xs">{{ __('email') }}</label>
                            <select class="filter form-control" id="emailFilter" multiple="multiple"
                                onchange="filter()">
                                @foreach ($emails as $email)
                                <option @if(isset($session_filters['emailFilter']) && in_array($email, $session_filters['emailFilter'])) selected @endif value="{{$email}}">{{$email}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col m-b-sm">
                            <label class="m-t-xs">{{ __('user_name') }}</label>
                            <select class="filter form-control" id="userNameFilter" multiple="multiple"
                                onchange="filter()">
                                @foreach ($userNames as $userName)
                                <option @if(isset($session_filters['userNameFilter']) && in_array($userName, $session_filters['userNameFilter'])) selected @endif value="{{$userName}}">{{$userName}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col m-b-sm">
                            <label class="m-t-xs">{{ __('level') }}</label>
                            <select class="filter form-control" id="levelFilter" multiple="multiple"
                                onchange="filter()">
                                @foreach ($levels as $level)
                                <option @if(isset($session_filters['levelFilter']) && in_array($level['value'], $session_filters['levelFilter'])) selected @endif value="{{$level['value']}}">{{$level['description']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col m-b-sm">
                            <label class="m-t-xs">{{ __('client') }}</label>
                            <select class="filter form-control" id="clientFilter" multiple="multiple" onchange="filter()">
                                @foreach ($clients as $client)
                                <option @if(isset($session_filters['clientFilter']) && in_array($email, $session_filters['clientFilter'])) selected @endif value="{{$client->name}}">{{$client->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col m-b-sm">
                            <label class="m-t-xs">{{ __('status') }}</label>
                            <select class="filter form-control" id="statusFilter" multiple="multiple" onchange="filter()">
                                <option @if(isset($session_filters['statusFilter']) && in_array('Active', $session_filters['statusFilter'])) selected @endif value="Active">{{ __('active') }}</option>
                                <option @if(isset($session_filters['statusFilter']) && in_array('Inactive', $session_filters['statusFilter'])) selected @endif value="Inactive">{{ __('inactive') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="results">
                        @include('dashboard.admin.users.results')
                    </div>


                </div>
            </div>

        </div>
    </div>
</div>
@push('js')
<script src="{{asset('js/delete-modal.js')}}"></script>


<script>
       var filter = (page = null) => {
        let searchTerm = $('#searchTerm').val();
        let emailFilter = $('#emailFilter').val();
        let userNameFilter = $('#userNameFilter').val();
        let levelFilter = $('#levelFilter').val();
        let clientFilter = $('#clientFilter').val();
        let statusFilter = $('#statusFilter').val();
        $.ajax({
            url: @json(route('users.filter')),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "get",
            data: {
                setFilter: true,
                searchTerm: searchTerm,
                emailFilter: emailFilter,
                userNameFilter: userNameFilter,
                levelFilter: levelFilter,
                clientFilter: clientFilter,
                statusFilter: statusFilter,
                page: page,
            },
            success: function (response) {
                $('.results').html(response);
                $('.tooltip-more').tooltip();
            },
            error: function (jqXHR, textStatus, errorThrown) {}
        });
    }

    $(document).on('click', '.pagination a', function (event) {
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        filter(page);
    });
    $(document).ready(function () {

        $(".filter").select2({
            placeholder: "Filtruj",
            tags: true,
            allowClear: true
        });

        $('#searchTerm').on('input', _.debounce(function () {
            filter();
        }, 1000));

        $('#searchTerm').on('keypress', function (e) {
            if (e.which == 13) {
                filter();
            }
        });
    });

</script>
@endpush
@endsection
