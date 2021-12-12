<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Controllers\BaseController;

class User extends BaseController
{
    public function edit()
    {
        return view('vw_edit');
    }
    public function userUpdate()
    {
        // $users = new UserModel();


        $is_unique = (session()->get('username') !== $this->request->getVar('username')) ? '|is_unique[users.username]' : '';

        if (!$this->validate([
            'username' => [
                'rules' => 'required|min_length[4]|max_length[20]' . $is_unique,
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 20 Karakter',
                    'is_unique' => 'Username sudah digunakan sebelumnya'
                ]
            ],
            // 'new_password' => [
            //     'rules' => 'required|min_length[4]|max_length[50]',
            //     'errors' => [
            //         'required' => '{field} Harus diisi',
            //         'min_length' => '{field} Minimal 4 Karakter',
            //         'max_length' => '{field} Maksimal 50 Karakter',
            //     ]
            // ],
            'name' => [
                'rules' => 'required|min_length[4]|max_length[100]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 100 Karakter',
                ]
            ],
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }

        $users = new UserModel();
        $dataUser = $users->where(
            'username',
            session()->get('username'),
        )->first();
        var_dump($dataUser);
        // var_dump($this->request->getPost('username'));
        $users->update($dataUser->id, [
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('new_password'), PASSWORD_BCRYPT),
            'name' => $this->request->getPost('name')
        ]);

        return redirect()->to('/');
    }
}
