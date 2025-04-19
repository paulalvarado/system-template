<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id_user';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_user',
        'firstname',
        'lastname',
        'email',
        'password',
        'last_login_at',
        'status',
        'created_at',
        'updated_at',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Verifica el correo electrónico y la contraseña de un usuario.
     *
     * @param string $email
     * @param string $password
     *
     * @return array|null Datos del usuario si son válidos, null si no lo son
     */
    public function verifyUser(string $email, string $password): ?array
    {
        if (empty($email) || empty($password)) {
            return null;
        }

        try {
            $user = $this->where('email', $email)->first();

            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }

            return null;
        } catch (\Exception $e) {
            log_message('error', 'Error verifying user: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Registra un nuevo usuario en la base de datos.
     *
     * Valida que los campos proporcionados no est n vacíos, y que el correo electrónico no est  ya en uso.
     * Si todo est  correcto, crea un nuevo registro en la tabla users y devuelve la informaci n del usuario.
     * Si hay un error al registrar el usuario, devuelve null.
     *
     * @param string $firstName El nombre del usuario.
     * @param string $lastName El apellido del usuario.
     * @param string $username El nombre de usuario del usuario.
     * @param string $email El correo electrónico del usuario.
     * @param string $password La contrase a del usuario.
     *
     * @return array|null La informaci n del usuario reci n registrado, o null si hubo un error.
     */
    public function registerUser(string $firstName, string $lastName, string $username, string $email, string $password): ?array
    {
        if (empty($firstName) || empty($lastName) || empty($username) || empty($email) || empty($password)) {
            return null;
        }

        $user = [
            'firstname' => $firstName,
            'lastname' => $lastName,
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ];

        try {
            $this->insert($user);

            return $user;
        } catch (\Exception $e) {
            log_message('error', sprintf(
                'Error registering user: %s',
                $e->getMessage()
            ));

            return null;
        }
    }
}
