<?php

namespace App\Http\Controllers;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class ProfilesController extends Controller
{
    public function index(User $user)
    {
        //if user is authenticated, grab the authenticated following that contain the id else false
        $follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;
        $postCount = Cache::remember(
            'count.posts.' . $user->id,
            now()->addSeconds(10),
            function () use ($user) {
                return $user->posts->count();
            });

        $followersCount = Cache::remember(
            'count.followers.' . $user->id,
            now()->addSeconds(10),
            function () use ($user) {
                return $user->profile->followers->count();
            });

        $followingCount = Cache::remember(
            'count.following.' . $user->id,
            now()->addSeconds(10),
            function () use ($user) {
                return $user->following->count();
            });
            return view('profiles.index', compact('user', 'follows', 'postCount', 'followersCount', 'followingCount'));
    }

    public function edit(User $user)
    {
        $this->authorize('update',$user->profile);//policy 

        return view('profiles.edit', compact('user'));

    }

    public function update(User $user)
    {
        $data = request()->validate([
            'title' => 'required',
            'description' => 'required',
            'url' => 'url',
            'image' => '',
        ]);

        if (request('image')) {
            $imagePath = request('image')->store('profile', 'public');

            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
            $image->save();

            $imageArray = ['image' => $imagePath];
            //set image array that contain the image path but only happens if image is in request
            //else set image arraay to empty array below code
        }

        //auth means grabbing only the authenticated user/extra layer of protection
        auth()->user()->profile->update(array_merge(
            $data,
            $imageArray ?? []
            //if image array not set , default is empty array// don't overwrite
        ));

        return redirect("/profile/{$user->id}");
    }

    public function search(User $user){

      
    
        $search_text=$_GET['query'];
        $user=User::where('username','LIKE','%'.$search_text.'%')->get();
        return view('profiles.search', compact('user'));
    }

   


}
