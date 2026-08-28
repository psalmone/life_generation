<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Member_model extends Model {

    protected $table = 'members';
    protected $primary_key = 'id';
    protected $fillable = [
        'user_id',
        'member_id',
        'full_name',
        'age',
        'address',
        'email',
        'membership_tier',
        'is_active',
        'joined_at',
        'updated_at',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Generate a unique Life Generation Member ID
     * Format: LG-YYYY-XXXXX (e.g. LG-2026-A3F9C)
     *
     * @return string
     */
    public function generate_member_id()
    {
        do {
            $year      = date('Y');
            $rand      = strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
            $member_id = "LG-{$year}-{$rand}";
            $exists    = $this->db->table($this->table)->where('member_id', $member_id)->get();
        } while (!empty($exists));

        return $member_id;
    }

    /**
     * Create a new member profile linked to a user account
     *
     * @param array $data
     * @return int|bool  Inserted row ID or false
     */
    public function create_member($data)
    {
        if (empty($data['member_id'])) {
            $data['member_id'] = $this->generate_member_id();
        }

        if (!isset($data['membership_tier'])) {
            $data['membership_tier'] = 'Standard Member';
        }

        if (!isset($data['is_active'])) {
            $data['is_active'] = 1;
        }

        if (!isset($data['joined_at'])) {
            $data['joined_at'] = date('Y-m-d H:i:s');
        }

        return $this->db->table($this->table)->insert($data);
    }

    /**
     * Get a member profile by the linked user_id
     *
     * @param int $user_id
     * @return array|null
     */
    public function get_by_user_id($user_id)
    {
        return $this->db->table($this->table)
            ->where('user_id', $user_id)
            ->get();
    }

    /**
     * Get a member profile by the member_id string (e.g. LG-2026-A3F9C)
     *
     * @param string $member_id
     * @return array|null
     */
    public function get_by_member_id($member_id)
    {
        return $this->db->table($this->table)
            ->where('member_id', $member_id)
            ->get();
    }

    /**
     * Update member profile fields
     *
     * @param int   $user_id  The owning user's ID
     * @param array $data     Fields to update
     * @return bool
     */
    public function update_member($user_id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->table($this->table)
            ->where('user_id', $user_id)
            ->update($data);
    }

    /**
     * Toggle active status by member primary key (id)
     *
     * @param int $member_row_id  members.id (not user_id)
     * @param int $status         0 or 1
     * @return bool
     */
    public function toggle_active($member_row_id, $status)
    {
        return $this->db->table($this->table)
            ->where('id', $member_row_id)
            ->update(['is_active' => (int)$status, 'updated_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Get all members, joined with their user role and login details
     * Returns combined rows: member fields + user role/username/is_active(user level)
     *
     * @return array
     */
    public function get_all_members()
    {
        $sql = "
            SELECT
                m.id              AS member_row_id,
                m.user_id,
                m.member_id,
                m.full_name,
                m.age,
                m.address,
                m.email,
                m.membership_tier,
                m.is_active,
                m.joined_at,
                m.updated_at,
                u.username,
                u.role,
                u.is_active       AS user_is_active
            FROM members m
            LEFT JOIN users u ON u.id = m.user_id
            ORDER BY m.joined_at DESC
        ";

        $stmt = $this->db->raw($sql);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    /**
     * Count total members
     *
     * @return int
     */
    public function count_members()
    {
        $res = $this->db->table($this->table)->get_all();
        return is_array($res) ? count($res) : 0;
    }

    /**
     * Count active members
     *
     * @return int
     */
    public function count_active_members()
    {
        $res = $this->db->table($this->table)->where('is_active', 1)->get_all();
        return is_array($res) ? count($res) : 0;
    }

    /**
     * Check if an email already exists in the members table (excluding a user)
     *
     * @param string   $email
     * @param int|null $exclude_user_id
     * @return bool
     */
    public function email_exists($email, $exclude_user_id = null)
    {
        $builder = $this->db->table($this->table)->where('email', $email);
        if ($exclude_user_id) {
            $builder->where('user_id !=', $exclude_user_id);
        }
        $res = $builder->get();
        return !empty($res);
    }

    /**
     * Find member row by email, member_id, or username (via JOIN)
     * Used during login to get the member context after authenticating user
     *
     * @param int $user_id
     * @return array|null
     */
    public function get_full_profile($user_id)
    {
        $sql = "
            SELECT
                m.id              AS member_row_id,
                m.user_id,
                m.member_id,
                m.full_name,
                m.age,
                m.address,
                m.email           AS member_email,
                m.membership_tier,
                m.is_active       AS member_is_active,
                m.joined_at,
                u.username,
                u.email           AS login_email,
                u.role,
                u.is_active       AS user_is_active
            FROM users u
            LEFT JOIN members m ON m.user_id = u.id
            WHERE u.id = ?
            LIMIT 1
        ";

        $stmt = $this->db->raw($sql, [$user_id]);
        $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
        return $row ?: null;
    }
}
