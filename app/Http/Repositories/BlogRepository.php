<?php

namespace App\Http\Repositories;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;

class BlogRepository
{
    private $blogData;
    private $categoriesData;

    public function __construct()
    {
        $this->blogData =
            json_decode(file_get_contents(storage_path() . "/data/blogs.json"), true);
        $this->categoriesData =
            json_decode(file_get_contents(storage_path() . "/data/categories.json"), true);
    }

    public function index($req)
    {
        $is_admin = !(
                        request()->session()->get('is_admin') &&
                        request()->session()->get('is_admin') === true
                    ) ? false : true;

        $filteredBlogs = null;

        if(!empty($req['category']))
        {
            $filteredBlogs = $this->filter_by_category($filteredBlogs, $req['category']);
        }

        if (!empty($req['author'])) {
            $filteredBlogs = $this->filter_by_author($filteredBlogs, $req['author']);
        }

        if(!empty($req['search']))
        {
            $filteredBlogs = $this->search_by_title($filteredBlogs, $req['search']);
        }

        return array_merge(['blogs' => $filteredBlogs ?? $this->get_blog_data()], [
            'is_admin' => ($is_admin && $is_admin === true) ? true: false,
            'categories' => array_keys($this->get_categories_data()??[]),
        ]);
    }

    public function create_blog($data)
    {
        try {
            $oldBlogData = $this->get_blog_data();

            if($oldBlogData && array_key_exists(strtolower($data->title), $oldBlogData))
            {
                return [
                    'success' => false,
                    'message' => 'Blog already exists'
                ];
            }

            $blogs_path = storage_path() . '/data/blogs.json';
            $categories_path = storage_path() . '/data/categories.json';

            $categories = $this->extract_categories($data->categories);
            // dd($categories);
            $thumbnailUrl = $this->storeThumbnail($data->thumbnail);

            $inputData = [
                'title' => $data['title'],
                'description' => $data['description'],
                'author_name' => $data['author_name'],
                'author_email' => $data['author_email'],
                'thumbnail' => $thumbnailUrl,
                'categories' => $categories,
                'publish_date' => Carbon::now('UTC'),
            ];

            $blogData = array_merge($oldBlogData ?? [], [
                strtolower($data->title) => $inputData
            ]);

            file_put_contents($blogs_path, json_encode($blogData));

            file_put_contents($categories_path, json_encode($this->update_categories_data($categories, strtolower($data->title))));

            return [
                'success' => true,
                'message' => "Blog created successfully"
            ];
        } catch (\Exception $e) {

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // private functions go here

    private function get_blog_data()
    {
        return $this->blogData;
    }

    private function get_categories_data()
    {
        return $this->categoriesData;
    }

    private function storeThumbnail(UploadedFile $file)
    {
        $randomName = "BIM-" . time() . strtolower(uniqid());
        $fileName = $randomName . '.' . $file->extension();

        $fileUrl = public_path('photos/blogs/' . $fileName);
        $file->move(public_path() . '/photos/blogs/', $fileName);

        if (file_exists($fileUrl)) {
            return url("/photos/blogs/" . $fileName);
        }
    }

    private function extract_categories(string $categories)
    {
        return $this->clean_up_categories(explode(',', strtolower($categories)));
    }

    private function clean_up_categories($categories)
    {
        return array_map(function ($var) {
            return trim($var);
        }, $categories);
    }

    private function update_categories_data($categories, $blogTitle)
    {
        $oldCategoriesData = $this->get_categories_data();

        foreach($categories as $category)
        {
            if($oldCategoriesData && array_key_exists(strtolower($category), $oldCategoriesData))
            {
                array_push($oldCategoriesData[strtolower($category)], strtolower($blogTitle));
            } else {
                $oldCategoriesData = array_merge($oldCategoriesData ?? [], [
                    strtolower($category) => [
                        $blogTitle
                    ]
                ]);
            }
        }

        return $oldCategoriesData;
    }

    private function filter_by_category($prevBlogs, $category)
    {
        $blogCategories = $this->get_categories_data();

        if(!$blogCategories || count($blogCategories) < 1 || !($this->get_blog_data() && count($this->get_blog_data()) > 0)) {
            return [];
        }

        if(array_key_exists($category, $blogCategories))
        {
            $filtered =  array_filter($prevBlogs ?? $this->get_blog_data(), function ($val) use ($category) {
                return in_array($category, $val['categories']);
            });

            return $filtered;
        } else {
            return [];
        }
    }

    private function filter_by_author($prevBlogs, $author)
    {
        $blogs = $prevBlogs ?? $this->get_blog_data();

        if (!$blogs || count($blogs) < 1) {
            return [];
        }

        $filtered =  array_filter($blogs, function ($val) use ($author) {
            return stripos($val['author_name'], $author) !== false;
        });

        return $filtered;
    }

    private function search_by_title($prevBlogs, $search)
    {
        $blogs = $prevBlogs ?? $this->get_blog_data();

        if (!$blogs || count($blogs) < 1 ) {
            return [];
        }

        $filtered =  array_filter($blogs, function ($val) use ($search) {
            return stripos($val['title'], $search) !== false;
        });

        return $filtered;
    }

}
