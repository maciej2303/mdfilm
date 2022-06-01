<?php

namespace App\Http\Controllers;

use App\Enums\ClientEmailTypes;
use App\Enums\ClientStatus;
use App\Enums\UserStatus;
use App\Http\Requests\Clients\ClientCreateRequest;
use App\Http\Requests\Clients\ClientEditRequest;
use App\Models\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as Image;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(String $searchTerm = null)
    {
        $clients = Client::withCount('projects')
            ->withCount('users')
            ->where('name', 'like', '%' . $searchTerm . '%')
            ->orWhere('nip', 'like', '%' . $searchTerm . '%')
            ->orWhere(function ($query) use ($searchTerm) {
                $query->has('users', '=', $searchTerm);
            })
            ->orWhere(function ($query) use ($searchTerm) {
                $query->has('projects', '=', $searchTerm);
            })
            ->orWhere('status', 'like', '%' . $searchTerm . '%')
            ->orderBy('name')
            ->paginate(25);
        return view('dashboard.admin.clients.index')
            ->with('clients', $clients)
            ->with('searchTerm', $searchTerm);
    }

    public function show(Client $client)
    {
        $statuses = ClientStatus::getValues();
        return view('dashboard.admin.clients.form')
            ->with('client', $client)
            ->with('statuses', $statuses);
    }

    public function create()
    {
        $statuses = ClientStatus::getValues();
        return view('dashboard.admin.clients.form')
            ->with('statuses', $statuses);
    }

    public function store(ClientCreateRequest $request)
    {
        $logo_path = null;
        if ($request->file('logo')) {
            $logo_path = $this->resize($request->file('logo'), null, 80);
        }

        $client = Client::create(array_merge($request->all(), ['who_add' => auth()->id(), 'logo' => str_replace('public/', 'storage/', $logo_path)]));
        foreach ($request->billing_emails as $billing_email) {
            $client->emails()->create(['email' => $billing_email, 'type' => ClientEmailTypes::BILLING]);
        }
        foreach ($request->contact_emails as $contact_email) {
            $client->emails()->create(['email' => $contact_email, 'type' => ClientEmailTypes::CONTACT]);
        }

        return redirect()->route('clients.index');
    }

    public function edit(Client $client)
    {
        $statuses = ClientStatus::getValues();
        return view('dashboard.admin.clients.form')
            ->with('client', $client)
            ->with('statuses', $statuses);
    }

    public function update(ClientEditRequest $request, Client $client)
    {
        $client->update($request->input());
        if ($request->file('logo')) {
            Storage::delete(str_replace('storage/', 'public/', $client->logo));
            $logo_path = $this->resize($request->file('logo'), null, 80);
            $client->logo = str_replace('public/', 'storage/', $logo_path);
            $client->save();
        }
        $client->billingEmails()->delete();
        $client->contactEmails()->delete();
        foreach ($request->billing_emails as $billing_email) {
            $client->emails()->create(['email' => $billing_email, 'type' => ClientEmailTypes::BILLING]);
        }
        foreach ($request->contact_emails as $contact_email) {
            $client->emails()->create(['email' => $contact_email, 'type' => ClientEmailTypes::CONTACT]);
        }
        return redirect()->route('clients.index');
    }

    public function destroy(Request $request)
    {
        try {
            $client = Client::find($request->id);
            $client->users->each(function ($user, $key) {
                $user->status = UserStatus::INACTIVE;
                $user->save();
            });
            $client->delete();
            return response()->json(200);
        } catch (Exception $e) {
        }
    }

    private function resize($image, $size_x, $size_y)
    {
        $filename = Str::random(100) . '.' . $image->extension();
        $image_resize = Image::make($image->getRealPath());
        if ($image_resize->height() > 100)
            $image_resize->resize(null, $size_y, function ($constraint) {
                $constraint->aspectRatio();
            });


        $path = 'public/images/logos/' . $filename;
        Storage::put($path, (string) $image_resize->encode());
        return $path;
    }
}
