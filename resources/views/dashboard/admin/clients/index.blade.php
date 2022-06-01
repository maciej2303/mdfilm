@extends('layouts.app')
@section('title', 'Użytkownicy')


@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">

            <div class="ibox ">
                <div class="ibox-title">
                    <h5>{{ __('clients') }}</h5>
                    <div class="ibox-tools">

                    </div>
                </div>


                <div class="ibox-content">
                    <div class="row m-b">
                        <div class="col-md-10">
                            <a href="{{route('clients.create')}}" class="btn btn-primary"><i class="fas fa-plus"></i>
                                {{ __('add_client') }}</a>
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" placeholder="Szukaj..." id="searchTerm"
                                value="{{$searchTerm}}">
                        </div>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('name') }}</th>
                                <th>{{ __('nip') }}</th>
                                <th>{{ __('projects') }}</th>
                                <th>{{ __('users') }}</th>
                                <th>{{ __('status') }}</th>
                                <th class="text-right">{{ __('options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clients as $client)
                            <tr>
                                <td>{{$client->name}}</td>
                                <td>{{$client->nip}}</td>
                                <td>{{$client->projects_count ?? '-'}}</td>
                                <td>{{$client->users_count ?? '-'}}</td>
                                <td>
                                    @if ($client->status == 'Active')
                                    <span class="label label-primary">{{ __('active') }}</span>
                                    @else
                                    <span class="label label-danger">{{ __('inactive') }}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a type="button" class="btn btn-primary btn-xs tooltip-more"
                                        href="{{route('clients.show', $client->id)}}" title="{{ __('show') }}"><i
                                            class="fas fa-eye"></i></a>
                                    <a type="button" class="btn btn-primary btn-xs tooltip-more"
                                        href="{{route('clients.edit', $client->id)}}" title="{{ __('edit') }}"><i
                                            class="fas fa-pen"></i></a>
                                    <a type="button" class="btn btn-danger btn-xs tooltip-more delete-warning"
                                        data-id="{{$client->id}}" data-route="{{route('clients.destroy')}}"
                                        title="{{ __('delete') }}"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-12 d-flex justify-content-center">
                            {{$clients->links("pagination::bootstrap-4")}}
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@push('js')
<script src="{{asset('js/delete-modal.js')}}"></script>


<script>
    $(document).ready(function () {

        $('#searchTerm').on('input', _.debounce(function () {
            window.location.href = @json(route('clients.search')) + '/' + this.value;
        }, 1000));

        $('#searchTerm').on('keypress', function (e) {
            if (e.which == 13) {
                window.location.href = @json(route('clients.search')) + '/' + this.value;
            }
        });
    });

</script>

<script>

</script>
@endpush
@endsection
