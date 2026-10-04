<?php
namespace App\Controllers;
use App\Models\TaskModel;
use App\Models\UserModel;
class Pages extends BaseController
{
    public function index(): string
    {
        return view('pages/home', ['title' => 'Welcome', 'activePage' => 'home',
            'tasks' => (new TaskModel())->active()->where('task_date', date('Y-m-d'))->orderBy('id', 'DESC')->findAll()]);
    }
    public function about(): string
    {
        return view('pages/about', ['title' => 'About', 'activePage' => 'about']);
    }
    public function profile(): string
    {
        return view('pages/profile', ['title' => 'Profile', 'activePage' => 'profile',
            'user' => (new UserModel())->select('full_name, username, email')->orderBy('id')->first()]);
    }
}
