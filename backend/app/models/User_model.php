<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class User_model extends Model {

    protected $table = 'users';
    protected $primary_key = 'id';

    /**
     * Only the users table credentials / auth fields are fillable here.
     * Member profile fields (name, age, address, etc.) live in the members table.
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'is_active',
        'created_at',
        'updated_at',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Find a user row by email, username, or member_id (via JOIN to members).
     * Returns the raw users row — no member profile fields.
     *
     * @param string $identifier
     * @return array|null
     */
    public function find_by_identifier($identifier)
    {
        // Try email first
        $user = $this->db->table($this->table)
            ->where('email', $identifier)
            ->get();

        if (!empty($user)) {
            return $user;
        }

        // Try username
        $user = $this->db->table($this->table)
            ->where('username', $identifier)
            ->get();

        if (!empty($user)) {
            return $user;
        }

        // Fallback: look up by member_id via members table
        $member = $this->db->table('members')
            ->where('member_id', $identifier)
            ->get();

        if (!empty($member) && !empty($member['user_id'])) {
            return $this->get_by_id($member['user_id']);
        }

        return null;
    }

    /**
     * Check if an email already exists in users (for uniqueness validation).
     *
     * @param string   $email
     * @param int|null $exclude_id  Exclude this user id (for update checks)
     * @return bool
     */
    public function email_exists($email, $exclude_id = null)
    {
        $res = $this->db->table($this->table)->where('email', $email)->get();
        if (empty($res)) return false;
        // If the matched row belongs to the excluded user, no conflict
        if ($exclude_id && (int)($res['id'] ?? 0) === (int)$exclude_id) return false;
        return true;
    }

    /**
     * Check if a username already exists in users.
     *
     * @param string   $username
     * @param int|null $exclude_id
     * @return bool
     */
    public function username_exists($username, $exclude_id = null)
    {
        $res = $this->db->table($this->table)->where('username', $username)->get();
        if (empty($res)) return false;
        if ($exclude_id && (int)($res['id'] ?? 0) === (int)$exclude_id) return false;
        return true;
    }

    /**
     * Create a new user account (credentials only).
     * Member profile should be created separately via Member_model::create_member().
     *
     * @param array $data  Must include: username, email, password. Optionally: role, is_active.
     * @return int|bool    Inserted user ID or false
     */
    public function create_user($data)
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if (!isset($data['role'])) {
            $data['role'] = 'user';
        }

        if (!isset($data['is_active'])) {
            $data['is_active'] = 1;
        }

        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $this->db->table($this->table)->insert($data);
    }

    /**
     * Get a user row by primary key ID.
     *
     * @param int $id
     * @return array|null
     */
    public function get_by_id($id)
    {
        return $this->db->table($this->table)
            ->where('id', $id)
            ->get();
    }

    /**
     * Update user account fields (e.g. email sync, role, is_active).
     *
     * @param int   $id
     * @param array $data
     * @return bool
     */
    public function update_user($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->table($this->table)
            ->where('id', $id)
            ->update($data);
    }

    /**
     * Get the last inserted user ID from the users table.
     *
     * @return int|null
     */
    public function last_insert_id()
    {
        return $this->db->last_id();
    }
}
