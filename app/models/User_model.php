<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class User_model extends Model {

    protected $table = 'users';
    protected $primary_key = 'id';
    protected $fillable = ['username', 'email', 'password', 'role', 'is_active'];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Find user by email or username
     *
     * @param string $identifier
     * @return array|null
     */
    public function find_by_identifier($identifier)
    {
        return $this->db->table($this->table)
            ->group_start()
                ->where('email', $identifier)
                ->or_where('username', $identifier)
            ->group_end()
            ->get();
    }

    /**
     * Check if email exists
     *
     * @param string $email
     * @return bool
     */
    public function email_exists($email)
    {
        $res = $this->db->table($this->table)
            ->where('email', $email)
            ->get();
        return !empty($res);
    }

    /**
     * Check if username exists
     *
     * @param string $username
     * @return bool
     */
    public function username_exists($username)
    {
        $res = $this->db->table($this->table)
            ->where('username', $username)
            ->get();
        return !empty($res);
    }

    /**
     * Create a new user with hashed password
     *
     * @param array $data
     * @return int|bool
     */
    public function register_user($data)
    {
        // Hash the password securely using standard bcrypt / PASSWORD_DEFAULT
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if (!isset($data['role'])) {
            $data['role'] = 'user';
        }

        if (!isset($data['is_active'])) {
            $data['is_active'] = 1;
        }

        return $this->db->table($this->table)->insert($data);
    }

    /**
     * Get user by ID
     *
     * @param int $id
     * @return array|null
     */
    public function get_user_by_id($id)
    {
        return $this->db->table($this->table)
            ->where('id', $id)
            ->get();
    }
}
