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
        $following_user_id = Auth::user()->id;
        $followed_user_id = $id;
        if ($follow->followingExists($following_user_id, $followed_user_id)) {
            $isFollowing = true;
        } else {
            $isFollowing = false;
        }

        return view('users.show', compact('user', 'posts', 'isFollowing'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {

        $input = request()->validate([
            'name' => ['required', 'string', 'max:225', 'alpha_dash'],
            'avatar' => ['file', 'image', 'max:1024'],
            'email' => ['required', 'email', 'max:225'],
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
        $following_user_id = Auth::user()->id;
        $followed_user_id = $id;

        $follow = new Follow();

        if ($follow->followingExists($following_user_id, $followed_user_id)) {
            // Already following => Remove following
            $follow = Follow::where('following_id', '=', $following_user_id)->where('followed_id', '=', $followed_user_id)->delete();
        } else {
            // No following yet => Record new following
            $follow->following_id = $following_user_id;
            $follow->followed_id = $followed_user_id;

            $follow->save();
        }

        return back();
    }
}
