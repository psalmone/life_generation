<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Dgroup_model extends Model {

    protected $table = 'dgroups';
    protected $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all D-Groups
     *
     * @return array
     */
    public function get_all()
    {
        return $this->db->table($this->table)->get_all();
    }

    /**
     * Get a single D-Group by ID
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
     * Get member count for a specific D-Group
     *
     * @param int $dgroup_id
     * @return int
     */
    public function count_members($dgroup_id)
    {
        $result = $this->db->table('members')
            ->where('dgroup_id', $dgroup_id)
            ->get_all();
        return is_array($result) ? count($result) : 0;
    }

    /**
     * Get all D-Groups enriched with member counts
     *
     * @return array
     */
    public function get_all_with_counts()
    {
        $groups = $this->get_all();
        if (empty($groups)) return [];

        foreach ($groups as &$group) {
            $group['member_count'] = $this->count_members($group['id']);
        }
        unset($group);

        return $groups;
    }

    /**
     * Enroll a member into a D-Group (replaces any previous enrollment)
     *
     * @param int $user_id
     * @param int $dgroup_id
     * @return bool
     */
    public function join($user_id, $dgroup_id)
    {
        return $this->db->table('members')
            ->where('user_id', $user_id)
            ->update(['dgroup_id' => (int)$dgroup_id, 'updated_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Remove a member from their D-Group
     *
     * @param int $user_id
     * @return bool
     */
    public function leave($user_id)
    {
        return $this->db->table('members')
            ->where('user_id', $user_id)
            ->update(['dgroup_id' => null, 'updated_at' => date('Y-m-d H:i:s')]);
    }
}
