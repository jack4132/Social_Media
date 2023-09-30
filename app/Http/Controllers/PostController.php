<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;


class PostController extends Controller
{
	//
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
		$users = auth()->user()->following()->pluck('profiles.user_id');
		$posts = Post::whereIn('user_id', $users)->with('user')->latest()->paginate(2); // Post::whereIn('user_id', $users)->orderBy('created_at', 'DESC')->get();
		// dd($posts);
		return view('posts.index', compact('posts'));
	}
	public function create()
	{
		return view('posts/create'); // posts.create
	}
	public function store()
	{
		$data = request()->validate([
			'caption' => 'required',
			'image' => ['required', 'image']
		]);
		// $post = new \App\Models\Post();
		// $post->caption = $data['caption'];
		// $post->image = $data['image'];
		// $post->save();
		// \app\Models\Post::create($data);
		$imagePath = request('image')->store('uploads', 'public');

		$image = Image::make(public_path("storage/{$imagePath}"))->fit(1200, 1200);
		$image->save();
		auth()->user()->posts()->create([
			'caption' => $data['caption'],
			'image' => $imagePath
		]);
		return redirect('/profile/' . auth()->user()->id);
		// dd(request()->all());
		// return view('posts/create'); // posts.create
	}
	public function show(\App\Models\Post $post)
	{
		// dd($post);
		return view('posts.show', compact('post'));
	}
}