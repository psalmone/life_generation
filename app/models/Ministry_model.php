<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Ministry_model extends Model {

    protected $table = 'ministries';
    protected $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    /** Get all active ministries */
    public function get_active()
    {
        return $this->db->table($this->table)->where('is_active', 1)->get_all();
    }

    /** Get all ministries for admin management */
    public function get_all_admin()
    {
        $sql = "
            SELECT m.*, 
                   COUNT(DISTINCT mem.id) AS member_count,
                   COUNT(DISTINCT CASE WHEN ma.status = 'pending' THEN ma.id END) AS pending_apps_count
            FROM ministries m
            LEFT JOIN members mem ON mem.ministry_id = m.id
            LEFT JOIN ministry_applications ma ON ma.ministry_id = m.id
            GROUP BY m.id
            ORDER BY m.id ASC
        ";
        $stmt = $this->db->raw($sql);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    /** Get single ministry by ID */
    public function get_by_id($id)
    {
        return $this->db->table($this->table)->where('id', $id)->get();
    }

    /** Create a new ministry (Admin) */
    public function create_ministry($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->table($this->table)->insert($data);
    }

    /** Update ministry details (Admin) */
    public function update_ministry($id, $data)
    {
        return $this->db->table($this->table)->where('id', $id)->update($data);
    }

    /** Delete a ministry (Admin) */
    public function delete_ministry($id)
    {
        return $this->db->table($this->table)->where('id', $id)->delete();
    }

    /**
     * Check if a user has already applied to a given ministry.
     */
    public function has_applied($user_id, $ministry_id)
    {
        $res = $this->db->table('ministry_applications')
            ->where('user_id', $user_id)
            ->where('ministry_id', $ministry_id)
            ->get();
        return !empty($res);
    }

    /**
     * Submit a volunteer application.
     */
    public function apply($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['status']     = 'pending';
        return $this->db->table('ministry_applications')->insert($data);
    }

    /**
     * Cancel (delete) own application.
     */
    public function cancel_application($user_id, $ministry_id)
    {
        $sql = "DELETE FROM ministry_applications WHERE user_id = ? AND ministry_id = ? AND status = 'pending'";
        return $this->db->raw($sql, [(int)$user_id, (int)$ministry_id]);
    }

    /**
     * Get single application by ID
     */
    public function get_application_by_id($app_id)
    {
        return $this->db->table('ministry_applications')->where('id', $app_id)->get();
    }

    /**
     * Get all applications for a user (with ministry name).
     */
    public function get_user_applications($user_id)
    {
        $sql = "
            SELECT ma.*, m.name AS ministry_name, m.icon
            FROM ministry_applications ma
            JOIN ministries m ON m.id = ma.ministry_id
            WHERE ma.user_id = ?
            ORDER BY ma.created_at DESC
        ";
        $stmt = $this->db->raw($sql, [(int)$user_id]);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    /**
     * Get all applications (admin view) with member and ministry info.
     */
    public function get_all_applications()
    {
        $sql = "
            SELECT ma.*, m.name AS ministry_name, m.icon
            FROM ministry_applications ma
            JOIN ministries m ON m.id = ma.ministry_id
            ORDER BY ma.created_at DESC
        ";
        $stmt = $this->db->raw($sql);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    /**
     * Admin: update application status.
     * When status is approved, assigns ministry_id to the member profile!
     */
    public function update_application_status($app_id, $status)
    {
        $app = $this->get_application_by_id($app_id);
        if ($app) {
            if ($status === 'approved') {
                $this->db->table('members')
                    ->where('user_id', $app['user_id'])
                    ->update([
                        'ministry_id' => (int)$app['ministry_id'],
                        'updated_at'  => date('Y-m-d H:i:s')
                    ]);
            } elseif ($status === 'rejected') {
                // If member was currently assigned this ministry, reset it
                $member = $this->db->table('members')->where('user_id', $app['user_id'])->get();
                if ($member && (int)($member['ministry_id'] ?? 0) === (int)$app['ministry_id']) {
                    $this->db->table('members')
                        ->where('user_id', $app['user_id'])
                        ->update([
                            'ministry_id' => null,
                            'updated_at'  => date('Y-m-d H:i:s')
                        ]);
                }
            }
        }

        return $this->db->table('ministry_applications')
            ->where('id', $app_id)
            ->update(['status' => $status, 'updated_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Get all active ministries enriched with user's application status.
     */
    public function get_active_with_status($user_id)
    {
        $ministries = $this->get_active();
        if (empty($ministries)) return [];

        foreach ($ministries as &$m) {
            $app = $this->db->table('ministry_applications')
                ->where('user_id', $user_id)
                ->where('ministry_id', $m['id'])
                ->get();
            $m['application_status'] = $app['status'] ?? null;
            $m['has_applied']        = !empty($app);
        }
        unset($m);

        return $ministries;
    }
}
