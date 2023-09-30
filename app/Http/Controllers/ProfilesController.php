<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Intervention\Image\Facades\Image;



class ProfilesController extends Controller
{
	//
	// public function __construct()
	// {
	// 	$this->middleware('auth');
	// }
	public function index($user)
	{
		// dd($user);
		// 
		$user = User::findOrFail($user);
		$follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;
		// dd($follows);
		$postCount = Cache::remember('count.post.' . $user->id, now()->addSeconds(30), function () use ($user) {
			return $user->posts->count();
		}); //
		$followersCount = Cache::remember('count.post.' . $user->id, now()->addSeconds(30), function () use ($user) {
			return $user->profile->followers->count();
		});
		$followingCount = Cache::remember('count.post.' . $user->id, now()->addSeconds(30), function () use ($user) {
			return $user->following->count();
		});

		return view('profiles.index', [
			'user' => $user,
			'follows' => $follows,
			'postCount' => $postCount,
			'followersCount' => $followersCount,
			'followingCount' => $followingCount

		]);
	}
	public function edit(User $user)
	{
		$this->authorize('update', $user->profile);
		return view('profiles.edit', compact('user'));

	}
	public function update(User $user)
	{
		$this->authorize('update', $user->profile);
		$data = request()->validate([
			'title' => 'required',
			'description' => 'required',
			'url' => 'url',
			'image' => ''
		]);
		// dd($data);

		if (request('image')) {
			$imagePath = request('image')->store('profile', 'public');

			$image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
			$image->save();
			$imageArray = ['image' => $imagePath];
		}
		// dd(
		// 	array_merge(
		// 		$data,
		// 		$imageArray ?? []
		// 	)
		// );
		auth()->user()->profile->update(
			array_merge(
				$data,
				$imageArray ?? []
			)
		);

		return redirect("/profile/{$user->id}");
	}
}