<?php

namespace App\Models;

use CodeIgniter\Model;

class UserMetaModel extends Model
{
    protected $table            = 'usermeta';
    protected $primaryKey       = 'id_usermeta';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_usermeta',
        'id_user',
        'meta_key',
        'meta_value',
        'created_at',
        'updated_at'
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
     * Guarda un token de recordatorio en la tabla usermeta.
     * 
     * Si la clave meta_key 'remember_token' ya existe para el usuario dado, actualiza el valor meta_value.
     * De lo contrario, crea un nuevo registro con el token dado.
     * 
     * @param int $userId El ID de usuario a asociar con el token
     * @param string $token El token a guardar
     * @return bool  Éxito
     */
    public function saveRememberToken($userId, $token)
    {
        if (!is_int($userId)) throw new \InvalidArgumentException('User ID should be an integer');
        if (!is_string($token)) throw new \InvalidArgumentException('Token should be a string');

        $userMeta = $this->where('id_user', $userId)->first();

        if (is_null($userMeta)) {
            // No previous record found
            $this->insert([
                'id_user' => $userId,
                'meta_key' => 'remember_token',
                'meta_value' => $token,
            ]);
        } else {
            // Update the existing record
            $this->update($userMeta['id_usermeta'], ['meta_value' => $token]);
        }

        return true;
    }

    /**
     * Recupera un usuario por su token de recordatorio.
     *
     * Busca en la tabla usermeta un registro con una clave meta_key de 'remember_token'
     * y un valor meta_value que coincida con el token proporcionado. Si se encuentra un
     * registro coincidente, recupera la informaci n asociada del usuario desde el
     * modelo UsersModel.
     *
     * @param string $token El token de recordatorio a buscar.
     * @return array|null La información del usuario si se encuentra, o null si no se
     *                    encuentra un usuario coincidente.
     * @throws \InvalidArgumentException Si el token proporcionado no es una cadena.
     */
    public function getUserByRememberToken(string $token): ?array
    {
        if (!is_string($token)) throw new \InvalidArgumentException('Token should be a string');

        $meta = $this->where('meta_key', 'remember_token')->where('meta_value', $token)->first();

        if (is_null($meta)) return null;

        $user = new UsersModel();
        $userData = $user->find($meta['id_user']);

        if (is_null($userData)) return null;

        return $userData;
    }
}
