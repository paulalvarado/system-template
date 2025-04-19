<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use App\Models\UsersModel;
use App\Models\UserMetaModel;

class AuthController extends BaseController
{
    use ResponseTrait;
    
    public function login()
    {
        // Validamos los campos requeridos
        $rules = [
            'username' => 'required|max_length[255]',
            'password' => 'required|max_length[255]',
            'password_confirm' => 'required|max_length[255]|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)->setJSON([
                'status' => ResponseInterface::HTTP_BAD_REQUEST,
                'errors' => $this->validator->getErrors(),
            ], ResponseInterface::HTTP_BAD_REQUEST);
        }

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        $remember_me = $this->request->getVar('remember_me');

        $user = (new UsersModel())->verifyUser($username, $password);

        if ($user) {
            if ($user['is_active'] == 0) {
                return $this->respond([
                    'status' => ResponseInterface::HTTP_UNAUTHORIZED,
                    'message' => 'The user is not active'
                ], ResponseInterface::HTTP_UNAUTHORIZED);
            }

            $payload = [
                'id_user' => $user['id_user'],
                'firstname' => $user['firstname'],
                'lastname' => $user['lastname'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role'],
                'is_active' => $user['is_active'],
            ];

            // Si remember_me está presente y es verdadero, extendemos la expiración
            if ($remember_me) {
                $cookieValue = bin2hex(random_bytes(32)); // Generar token seguro
                $cookieName = 'remember_me';
                $expire = time() + (60 * 60 * 24 * 30); // 30 días

                // Guardar el token
                (new UserMetaModel())->saveRememberToken($user['id_user'], $cookieValue);

                setcookie($cookieName, $cookieValue, $expire, '/', '', false, true); // httpOnly
            }

            session()->set('auth', $payload);

            return $this->respond([
                'status' => ResponseInterface::HTTP_OK,
                'data' => $payload
            ], ResponseInterface::HTTP_OK);
        } else {
            return $this->respond([
                'status' => ResponseInterface::HTTP_UNAUTHORIZED,
                'message' => 'Invalid username or password'
            ], ResponseInterface::HTTP_UNAUTHORIZED);
        }
    }
}
