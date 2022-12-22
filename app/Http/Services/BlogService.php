<?php

namespace App\Http\Services;

use App\Http\Repositories\BlogRepository;

class BlogService
{
    protected $blogRepository;

    public function __construct()
    {
        $this->blogRepository = new BlogRepository();
    }

    public function index($req)
    {
        return $this->blogRepository->index($req);
    }

    public function admin_index($req)
    {
        return $this->blogRepository->index($req);
    }

    public function create_blog($data)
    {
        if(!(
            request()->session()->get('is_admin') &&
            request()->session()->get('is_admin') === true
        )) {
            return [
                'success' => false,
                'message' => 'Unauthorized! Only admin can create blog'
            ];
        }
        return $this->blogRepository->create_blog($data);
    }
}
