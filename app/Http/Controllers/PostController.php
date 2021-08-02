<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Post;
use App\Comment;
use App\Like;
use App\Category;
use App\PostType;

class PostController extends Controller
{
    public function show($id)
    {
        $post = Post::findOrFail($id);

        $comments = Comment::where('post_id', $id)->orderBy('created_at', 'desc')->get();

        $likesCount = $post->loadCount('likes')->likes_count;

        // Checking if the post is already liked by the Auth user or not.
        $like = new Like();
        $user_id = Auth::user()->id;
        $post_id = $id;
        if ($like->likeExists($user_id, $post_id)) {
            $isLiked = true;
        } else {
            $isLiked = false;
        }

        // Getting categories for the post
        $category = new Category();
        $categories = $category->getCategoriesForPost($post);

        return view('posts.show', compact('post', 'comments', 'likesCount', 'isLiked', 'categories'));
    }

    public function list()
    {
        $posts = Post::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);
        return view('posts.list', compact('posts'));
    }

    public function create()
    {
        $post_types = PostType::orderBy('slug', 'asc')->get();
        $categories = Category::orderBy('slug', 'asc')->get();

        return view('posts.create', compact('post_types', 'categories'));
    }

    public function store()
    {
        $input = request()->validate([
            'post_type_id' => ['required'],
            'title' => ['required', 'max:255'],
            'content' => ['required', 'max:2000'],
            'post_image' => ['file', 'image', 'max:1024'],
            'categories' => ['required'],
        ]);

        $post = new Post();
        $post->user_id = Auth::user()->id;
        $post->post_type_id = $input['post_type_id'];
        $post->title = $input['title'];
        $post->content = $input['content'];

        if (request('post_image')) {
            $post['post_image'] = request('post_image')->store('images');
        }

        $post->save();

        // category_idとpost_idをpivot tableに入れるのは$post->save()の後（postができてないのにpivot tableにidを入れることは不可能）
        $post->categories()->sync($input['categories'], false);


        session()->flash('post-created-message', 'Post was created :' . $post['title']);

        return redirect()->route('home');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);

        // Checking which categories the post already has
        $categories = Category::all();

        $category = new Category();
        $current_categories_id = array_column($category->getCategoriesForPost($post)->toArray(), "id");

        return view('posts.edit', compact('post', 'categories', 'current_categories_id'));
    }

    public function update(Request $request, $id)
    {

        $input = request()->validate([
            'title' => ['required', 'max:255'],
            'content' => ['required', 'max:2000'],
            'post_image' => ['file', 'image', 'max:1024'],
            'categories' => ['required'],
        ]);

        $post = Post::findOrFail($id);

        $post->title = $input['title'];
        $post->content = $input['content'];

        if (request('post_image')) {
            $post->post_image = $request->file('post_image')->store('images');
        }

        $post->update();

        $post->categories()->sync($input['categories']);

        session()->flash('post-updated-message', 'The post was updated successfully.');

        return back();
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        session()->flash('post-deleted-message', 'Post was deleted successfully.: ' . $post->title);

        return back();
    }

    public function like($id)
    {
        $user_id = Auth::user()->id;
        $post_id = $id;

        $like = new Like();

        if ($like->likeExists($user_id, $post_id)) {
            // Already liked => Remove Like
            $like = Like::where('user_id', '=', $user_id)->where('post_id', '=', $post_id)->delete();
        } else {
            // Not yet like => Add Like
            $like->user_id = Auth::user()->id;
            $like->post_id = $id;
            $like->save();
        }

        return back();
    }

    // Display the posts only for the selected category on home page
    public function categoryPost($id)
    {
        $category_selected = Category::findOrFail($id);
        $posts = $category_selected->posts()->paginate(5);

        $categories = Category::orderBy('slug', 'asc')->get();

        // Count articles and questions for the specific category
        $post_types = PostType::all();

        foreach ($categories as $category) {
            $count_for_each_post_type = [];

            foreach ($post_types as $post_type) {
                $num_of_posts = $category->posts->where('post_type_id', '=', $post_type->id)->count();
                array_push($count_for_each_post_type, ['name' => $post_type->name, 'num_of_posts' => $num_of_posts]);
            }

            $category->count_for_each_post_type = $count_for_each_post_type;
        }

        $news_list = session()->get('news_list');

        return view('home', compact('categories', 'category_selected', 'posts', 'news_list'));
    }
}
