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
    
    /**
     * Autentica un usuario y devuelve su información en caso de éxito.
     *
     * Valida que los campos proporcionados no están vacíos, y que el
     * username y la contraseña coinciden con un registro en la tabla
     * users. Si todo está correcto, devuelve la información del usuario
     * autenticado, o null si hubo un error.
     *
     * @return array|null La información del usuario autenticado, o null
     *                    si hubo un error.
     */
    public function login()
    {
        // Validamos los campos requeridos
        $rules = [
            'username' => 'required|max_length[255]',
            'password' => 'required|max_length[255]',
            'password_confirm' => 'required|max_length[255]|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'status' => ResponseInterface::HTTP_BAD_REQUEST,
                'errors' => $this->validator->getErrors(),
            ], ResponseInterface::HTTP_BAD_REQUEST);
        }

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        $remember_me = $this->request->getVar('remember_me');

        try {
            $user = (new UsersModel())->verifyUser($username, $password);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => ResponseInterface::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'An error occurred while verifying the user'
            ], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($user) {
            if ($user['status'] == 0) {
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
                'status' => $user['status'],
            ];

            // Si remember_me está presente y es verdadero, extendemos la expiración
            if ($remember_me) {
                try {
                    $cookieValue = bin2hex(random_bytes(32)); // Generar token seguro
                } catch (\Exception $e) {
                    return $this->respond([
                        'status' => ResponseInterface::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => 'An error occurred while generating a secure token'
                    ], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
                }

                $cookieName = 'remember_me';
                $expire = time() + (60 * 60 * 24 * 30); // 30 días

                try {
                    // Guardar el token
                    (new UserMetaModel())->saveRememberToken($user['id_user'], $cookieValue);
                } catch (\Exception $e) {
                    return $this->respond([
                        'status' => ResponseInterface::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => 'An error occurred while saving the remember token'
                    ], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
                }

                setcookie($cookieName, $cookieValue, $expire, '/', '', false, true); // httpOnly
            }

            session()->set('auth', $payload);

            // Actualizamos la fecha de inicio de sesión
            (new UsersModel())->update($user['id_user'], [
                'last_login_at' => date('Y-m-d H:i:s')
            ]);

            // Creamos una notificación de inicio de sesión con flashdata
            session()->setFlashdata('notification', [
                'type' => 'success',
                'message' => 'You have successfully logged in'
            ]);

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

    /**
     * Destruye la sesión actual y devuelve un mensaje de confirmación.
     *
     * @return ResponseInterface
     */
    public function logout()
    {
        session()->destroy();
        return $this->respond([
            'status' => ResponseInterface::HTTP_OK,
            'message' => 'Logout successful'
        ], ResponseInterface::HTTP_OK);
    }

    /**
     * Registra un nuevo usuario en la base de datos.
     *
     * Valida que los campos proporcionados no están vacíos, y que el correo electrónico no está ya en uso.
     * Si todo está correcto, crea un nuevo registro en la tabla users y devuelve la información del usuario.
     * Si hay un error al registrar el usuario, devuelve null.
     *
     * @return array|null La información del usuario reción registrado, o null si hubo un error.
     */
    public function register()
    {
        // Validamos los campos requeridos
        $rules = [
            'firstname' => 'required|max_length[255]',
            'lastname' => 'required|max_length[255]',
            'username' => 'required|max_length[255]',
            'email' => 'required|valid_email|max_length[255]',
            'password' => 'required|max_length[255]',
            'password_confirmation' => 'required|max_length[255]|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'status' => ResponseInterface::HTTP_BAD_REQUEST,
                'errors' => $this->validator->getErrors(),
            ], ResponseInterface::HTTP_BAD_REQUEST);
        }

        $firstname = $this->request->getVar('firstname');
        $lastname = $this->request->getVar('lastname');
        $username = $this->request->getVar('username');
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        try {
            $user = (new UsersModel())->registerUser($firstname, $lastname, $username, $email, $password);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => ResponseInterface::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'An error occurred while registering the user'
            ], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($user) {
            // Creamos una notificación de inicio de sesión con flashdata
            session()->setFlashdata('notification', [
                'type' => 'success',
                'message' => 'You have successfully registered'
            ]);
            
            return $this->respond([
                'status' => ResponseInterface::HTTP_CREATED,
                'message' => 'User registered successfully'
            ], ResponseInterface::HTTP_CREATED);
        } else {
            return $this->respond([
                'status' => ResponseInterface::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'An error occurred while registering the user'
            ], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
