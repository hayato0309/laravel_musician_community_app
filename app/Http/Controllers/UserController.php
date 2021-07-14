<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\User;
use App\Post;
use App\Like;
use App\Follow;



class UserController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);
        $posts = Post::where('user_id', '=', $user->id)->orderBy('created_at', 'desc')->simplePaginate(5);

        // Getting all posts of the user
        foreach ($posts as $post) {
            $post['likesCount'] = $post->loadCount('likes')->likes_count;

            $like = new Like();
            $user_id = Auth::user()->id;
            $post_id = $post->id;
            if ($like->likeExists($user_id, $post_id)) {
                $post['isLiked'] = true;
            } else {
                $post['isLiked'] = false;
            }
        }

        // Checking if Auth user is already following the user
        $follow = new Follow();
        $following_id = Auth::user()->id;
        $follower_id = $id;
        if ($follow->followingExists($following_id, $follower_id)) {
            $isFollowing = true;
        } else {
            $isFollowing = false;
        }

        // Get following users
        $followings = $user->followings()->get();

        foreach ($followings as $following) {
            $following['followingInModal'] = $follow->followingExists($following_id, $following['id']);
        }

        // Get follower users
        $followers = $user->followers()->get();

        foreach ($followers as $follower) {
            $follower['followerInModal'] = $follow->followingExists($following_id, $follower['id']);
        }

        return view('users.show', compact('user', 'posts', 'isFollowing', 'followings', 'followers'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {

        $input = request()->validate([
            'name' => ['required', 'string', 'max:30', 'alpha_dash'],
            'avatar' => ['file', 'image', 'max:1024'],
            'email' => ['required', 'email', 'max:50'],
            'greeting' => ['required', 'string', 'max:130'],
            'interests' => ['required', 'string', 'max:170'],
        ]);

        $user = User::findOrFail($id);

        if (request('avatar')) {
            $input['avatar'] = $request->file('avatar')->store('images');
        }

        $user->update($input);

        $request->session()->flash('user-profile-updated-message', 'Your profile was updated successfully.');

        return back();
    }

    public function editPassword()
    {
        $auth = Auth::user();
        return view('users.edit_password')->with('auth', $auth);
    }

    public function updatePassword(Request $request)
    {
        $auth = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'string', 'min:8'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $validator->validate();

        // Check if the input password and current password match
        if (!(Hash::check($request->current_password, $auth->password))) {
            $validator->errors()->add('current_password', "This password deosn't macth the current password.");
            return back()->withInput()->withErrors($validator);
        }

        $auth->password = bcrypt($request->new_password);
        $auth->save();

        $request->session()->flash('updated-password', 'The password was updated successfully.');

        return redirect()->route('user.edit', $auth->id);
    }

    public function follow($id)
    {
        $following_id = Auth::user()->id;
        $follower_id = $id;

        $follow = new Follow();

        if ($follow->followingExists($following_id, $follower_id)) {
            // Already following => Remove following
            $follow = Follow::where('following_id', '=', $following_id)->where('follower_id', '=', $follower_id)->delete();
        } else {
            // No following yet => Record new following
            $follow->following_id = $following_id;
            $follow->follower_id = $follower_id;

            $follow->save();
        }

        return back();
    }
}
