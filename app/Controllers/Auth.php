<?php
namespace App\Controllers;
use App\Models\UserModel;
class Auth extends BaseController
{
    public function login()
    {
        return session('isLoggedIn') === true ? redirect()->to(site_url('tasks')) : $this->form();
    }
    public function authenticate()
    {
        $data = $this->request->getPost(['username', 'password']);
        if (! $this->validateData($data, ['username' => 'required|max_length[50]', 'password' => 'required|max_length[256]'])) {
            return $this->form('', 'Enter a valid username and password.');
        }
        $user = (new UserModel())->where('username', trim($data['username']))->first();
        if (! $user || ! password_verify($data['password'], $user['password'])) {
            return $this->form($data['username'], 'The username or password is incorrect.');
        }
        session()->regenerate(true);
        session()->set(['isLoggedIn' => true, 'user_id' => (int) $user['id'], 'username' => $user['username']]);
        return redirect()->to(site_url('tasks'));
    }
    public function logout()
    {
        session()->remove(['isLoggedIn', 'user_id', 'username']);
        session()->destroy();
        return redirect()->to(site_url('login'));
    }
    private function form(string $username = '', string $error = ''): string
    {
        return view('auth/login', compact('username', 'error') + ['title' => 'Sign in', 'activePage' => 'login']);
    }
}

