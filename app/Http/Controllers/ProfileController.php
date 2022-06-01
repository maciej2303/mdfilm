<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\ProfileEditRequest;
use App\Models\User;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as Image;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('dashboard.profile.edit')->with('user', auth()->user());
    }

    public function update(ProfileEditRequest $request, User $user)
    {

        $user->update($request->except('email'));
        if ($request->file('avatar')) {
            Storage::delete(str_replace('storage/', 'public/', $user->avatar));
            $avatar_path = $this->resize($request->file('avatar'), 600, 600);
            $user->avatar = str_replace('public/', 'storage/', $avatar_path);
            $user->save();
        }
        return redirect()->route('home');
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
