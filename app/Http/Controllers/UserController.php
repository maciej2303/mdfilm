<?php

namespace App\Http\Controllers;

use App\Enums\UserLevel;
use App\Enums\UserStatus;
use App\Helpers\CollectionHelper;
use App\Http\Requests\Users\UserCreateRequest;
use App\Http\Requests\Users\UserEditRequest;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as Image;
use Session;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->setFilter) {
            $request_filters = $request->except(['searchTerm', 'page']) ? $request->except(['searchTerm', 'page']) : null;
            if ($request_filters) {
                $this->setSessionFilters($request_filters);
            }
        }
        $session_filters = $this->getSessionFilters();

        $searchTerm = $request->searchTerm;
        $users = User::query();
        $users = $this->parametersQuery($users, $session_filters);
        if ($searchTerm != null) {
            $users = $this->searchTermQuery($users, $searchTerm);
        }
        $users = $users->orderBy('email')->get();
        $users = CollectionHelper::paginate($users, 25);
        $allUsers = User::orderBy('name')->get();
        $emails = $allUsers->pluck('email');
        $userNames = $allUsers->pluck('name');
        $clients = Client::orderBy('name')->get();
        $levels = [];
        foreach (UserLevel::getValues() as $level) {
            $levels[] = ['value' => $level, 'description' => UserLevel::getDescription($level)];
        }

        if ($request->ajax()) {
            return response()->json(view('dashboard.admin.users.results', compact('users'))->render());
        }

        return view('dashboard.admin.users.index')
            ->with('session_filters', $session_filters)
            ->with('users', $users)
            ->with('emails', $emails)
            ->with('userNames', $userNames)
            ->with('levels', $levels)
            ->with('clients', $clients)
            ->with('searchTerm', $searchTerm);
    }
    private function setSessionFilters($request_filters) 
    {
         session()->put('users_filters', $request_filters);
    }
    private function getSessionFilters()
    {
        return session()->get('users_filters');
    }
    private function searchTermQuery($users, $searchTerm)
    {
        $users = $users->where(function ($query) use ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%')
                ->orWhere('email', 'like', '%' . $searchTerm . '%')
                ->orWhere('level', 'like', '%' . $searchTerm)
                ->orWhereRaw("DATE_FORMAT(last_login_at, '%d.%m.%Y %H:%i') like '" . '%' . $searchTerm . "%'")
                ->orWhere('status', 'like', '%' . $searchTerm . '%')
                ->orWhereHas('client', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                });
        });
        return $users;
    }
    private function parametersQuery($users, $parameters)
    {
        $users = $users->when(isset($parameters['emailFilter']), function ($query) use ($parameters) {
            $query->where(function ($q) use ($parameters) {
                foreach ($parameters['emailFilter'] as $parameter) {
                    $q->orWhere('email', 'like', "%{$parameter}%");
                }
            });
        })->when(isset($parameters['userNameFilter']), function ($query) use ($parameters) {
            $query->where(function ($q) use ($parameters) {
                foreach ($parameters['userNameFilter'] as $parameter) {
                    $q->orWhere('name', 'like', "%{$parameter}%");
                }
            });
        })->when(isset($parameters['levelFilter']), function ($query) use ($parameters) {
            $query->where(function ($q) use ($parameters) {
                foreach ($parameters['levelFilter'] as $parameter) {
                    $q->orWhere('level', 'like', "%{$parameter}%");
                }
            });
        })->when(isset($parameters['clientFilter']), function ($query) use ($parameters) {
            $query->whereHas('client', function ($quer) use ($parameters) {
                $quer->where(function ($q) use ($parameters) {
                    foreach ($parameters['clientFilter'] as $parameter) {
                        $q->orWhere('name', 'like', "%{$parameter}%");
                    }
                });
            });
        })->when(isset($parameters['statusFilter']), function ($query) use ($parameters) {
            $query->where(function ($q) use ($parameters) {
                foreach ($parameters['statusFilter'] as $parameter) {
                    $q->orWhere('status', 'like', $parameter);
                }
            });
        });
        return $users;
    }

    public function create()
    {
        $levels = UserLevel::getValues();
        $statuses = UserStatus::getValues();
        $clients = Client::all();
        return view('dashboard.admin.users.form')
            ->with('levels', $levels)
            ->with('statuses', $statuses)
            ->with('clients', $clients);
    }

    public function store(UserCreateRequest $request)
    {
        $avatar_path = null;
        if ($request->file('avatar')) {
            $avatar_path = $this->resize($request->file('avatar'), 600, 600);
        }

        User::create(array_merge($request->all(), ['who_add' => auth()->id(), 'avatar' => str_replace('public/', 'storage/', $avatar_path)]));
        return redirect()->route('users.index');
    }

    public function edit(User $user)
    {
        $levels = UserLevel::getValues();
        $statuses = UserStatus::getValues();
        $clients = Client::all();
        return view('dashboard.admin.users.form')
            ->with('levels', $levels)
            ->with('user', $user)
            ->with('statuses', $statuses)
            ->with('clients', $clients);
    }

    public function update(UserEditRequest $request, User $user)
    {
        $user->update($request->input());
        if ($request->file('avatar')) {
            Storage::delete(str_replace('storage/', 'public/', $user->avatar));
            $avatar_path = $this->resize($request->file('avatar'), 600, 600);
            $user->avatar = str_replace('public/', 'storage/', $avatar_path);
            $user->save();
        }
        return redirect()->route('users.index');
    }

    public function destroy(Request $request)
    {
        try {
            $user = User::find($request->id);
            Storage::delete(str_replace('storage/', 'public/', $user->avatar));
            $user->delete(str_replace('storage/', 'public/', $user->avatar));
            return response()->json(200);
        } catch (Exception $e) {
        }
    }

    private function resize($image, $size_x, $size_y)
    {
        $filename = Str::random(100) . '.' . $image->extension();
        $image_resize = Image::make($image->getRealPath());
        $width = $image_resize->width();
        $height = $image_resize->height();
        if ($width > $height) {
            $image_resize->resize(null, $size_y, function ($constraint) {
                $constraint->aspectRatio();
            });
        } else {
            $image_resize->resize($size_x, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        $image_resize->crop($size_x, $size_y);

        $path = 'public/images/avatars/' . $filename;
        Storage::put($path, (string) $image_resize->encode());
        return $path;
    }
}
