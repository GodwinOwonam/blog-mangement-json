<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBlogRequest;
use Illuminate\Http\Request;
use App\Http\Services\BlogService;

class BlogsController extends Controller
{
    private $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    public function index(Request $req) {
        return view('blog.index', $this->blogService->index($req));
    }

    public function admin_index(Request $req)
    {
        return view('blog.index', $this->blogService->admin_index($req));
    }

    public function show_create_blog()
    {
        if (!(request()->session()->get('is_admin') &&
            request()->session()->get('is_admin') === true
        )) {
            return view('blog.index',[
                'success' => false,
                'message' => 'Unauthorized! Only admin can create blog'
            ]);
        }

        return view('blog.create_blog');
    }

    public function create_blog(CreateBlogRequest $req)
    {
        return view('blog.create_blog', $this->blogService->create_blog($req));
    }
}
