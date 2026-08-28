<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Prayer_request_model extends Model {

    protected $table = 'prayer_requests';
    protected $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Submit a new prayer request.
     */
    public function submit($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->table($this->table)->insert($data);
    }

    /**
     * Get all public requests + the current user's private requests.
     * Ordered newest first.
     *
     * @param int $current_user_id
     * @return array
     */
    public function get_wall($current_user_id, $is_admin = false)
    {
        if ($is_admin) {
            $sql = "
                SELECT * FROM {$this->table}
                WHERE status != 'archived'
                ORDER BY created_at DESC
            ";
        } else {
            $sql = "
                SELECT * FROM {$this->table}
                WHERE status != 'archived'
                  AND (visibility = 'public' OR user_id = ?)
                ORDER BY created_at DESC
            ";
        }
        $stmt = $this->db->raw($sql, $is_admin ? [] : [(int)$current_user_id]);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    /**
     * Get a single request by ID (with ownership check for non-admins).
     */
    public function get_by_id($id)
    {
        return $this->db->table($this->table)->where('id', $id)->get();
    }

    /**
     * Update status of a prayer request.
     */
    public function update_status($id, $status, $user_id = null, $is_admin = false)
    {
        $builder = $this->db->table($this->table)->where('id', $id);
        if (!$is_admin && $user_id) {
            // Only owner can modify their own
            $sql = "UPDATE {$this->table} SET status = ?, updated_at = ? WHERE id = ? AND user_id = ?";
            return $this->db->raw($sql, [$status, date('Y-m-d H:i:s'), (int)$id, (int)$user_id]);
        }
        return $builder->update(['status' => $status, 'updated_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Delete a prayer request (owner or admin).
     */
    public function delete_request($id, $user_id = null, $is_admin = false)
    {
        if ($is_admin) {
            return $this->db->table($this->table)->where('id', $id)->delete();
        }
        $sql = "DELETE FROM {$this->table} WHERE id = ? AND user_id = ?";
        return $this->db->raw($sql, [(int)$id, (int)$user_id]);
    }
}
