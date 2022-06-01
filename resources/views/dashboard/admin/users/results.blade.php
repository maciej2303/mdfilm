<table class="table table-striped">
    <thead>
        <tr>
            <th>{{ __('email') }}</th>
            <th>{{ __('user_name') }}</th>
            <th>{{ __('level') }}</th>
            <th>{{ __('last_login') }}</th>
            <th>{{ __('status') }}</th>
            <th class="text-right">{{ __('options') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr>
            <td>{{$user->email}}</td>
            <td>{{$user->name}}</td>
            <td>
                {{\App\Enums\UserLevel::getDescription($user->level)}}
                @if(isset($user->client))
                ({{$user->client->name}})
                @endif
            </td>
            <td>{{$user->last_login_at != null ? \Carbon\Carbon::parse($user->last_login_at)->format('d.m.Y H:i') : '-'}}
            </td>
            <td>
                @if($user->status == \App\Enums\UserStatus::INACTIVE || ($user->level ==
                \App\Enums\UserLevel::CLIENT && $user->client->status == \App\Enums\ClientStatus::INACTIVE))
                <span class="label label-danger">{{ __('inactive') }}</span>
                @else
                <span class="label label-primary">{{ __('active') }}</span>
                @endif
            </td>
            <td class="text-right">
                <a type="button" class="btn btn-primary btn-xs tooltip-more"
                    href="{{route('users.edit', $user->id)}}" title="{{ __('edit') }}"><i class="fas fa-pen"></i></a>
                <a type="button" class="btn btn-danger btn-xs tooltip-more delete-warning" data-id="{{$user->id}}"
                    data-route="{{route('users.destroy')}}" title="{{ __('delete') }}"><i class="fas fa-trash"></i></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="row">
    <div class="col-12 d-flex justify-content-center">
        {{$users->links("pagination::bootstrap-4")}}
    </div>
</div>