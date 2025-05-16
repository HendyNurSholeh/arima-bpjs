<?php

namespace App\Controllers;

use App\Models\AkunModel;
use CodeIgniter\HTTP\RedirectResponse;

class LoginController extends BaseController
{
    public function login(): string
    {
        return view('login');
    }
    
    public function logout(): RedirectResponse
    {
        // Perform logout logic here (e.g., destroy session)
        session()->destroy();
        
        // Redirect to login page
        return redirect()->to('/login');
    }

    public function postLogin()
    {
        $session = session();
        $accountModel = new AkunModel();

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        // Cari user berdasarkan username
        $account = $accountModel->getAccountByUsername($username);

        // Jika user ditemukan dan password cocok
        if ($account && password_verify($password, $account['password'])) {
            $session->set('is_login', true);
            $session->set('id_akun', $account['id_akun']);
            $session->set('username', $account['username']);
            $session->set('email', $account['email']);
            $session->set('nama', $account['nama']);
            if($account['level']=="admin"){
                $session->set('level', "admin");
                return redirect()->to('/admin/dashboard');
            }

        } else {
            // Jika login gagal
            $session->setFlashdata('error', 'Failed! Username atau password salah!');
            return redirect()->to("/login");
        }
    }
    
}