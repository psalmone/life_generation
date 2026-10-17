<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Event_model extends Model {

    protected $table = 'events';
    protected $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all active events ordered by event_date ascending
     */
    public function get_upcoming_events()
    {
        $sql = "
            SELECT e.*, 
                   COUNT(CASE WHEN ea.rsvp_status = 'attending' THEN 1 END) AS rsvp_count,
                   COUNT(CASE WHEN ea.checked_in = 1 THEN 1 END) AS checked_in_count
            FROM events e
            LEFT JOIN event_attendances ea ON ea.event_id = e.id
            WHERE e.is_active = 1
            GROUP BY e.id
            ORDER BY e.event_date ASC
        ";
        $stmt = $this->db->raw($sql);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    /**
     * Get all events with enriched attendance stats (for admin)
     */
    public function get_all_events_admin()
    {
        $sql = "
            SELECT e.*, 
                   COUNT(CASE WHEN ea.rsvp_status = 'attending' THEN 1 END) AS rsvp_count,
                   COUNT(CASE WHEN ea.checked_in = 1 THEN 1 END) AS checked_in_count
            FROM events e
            LEFT JOIN event_attendances ea ON ea.event_id = e.id
            GROUP BY e.id
            ORDER BY e.event_date DESC
        ";
        $stmt = $this->db->raw($sql);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    /**
     * Get single event by ID
     */
    public function get_by_id($id)
    {
        return $this->db->table($this->table)->where('id', $id)->get();
    }

    /**
     * Create event (Admin)
     */
    public function create_event($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['is_active']  = 1;
        return $this->db->table($this->table)->insert($data);
    }

    /**
     * Update event details (Admin)
     */
    public function update_event($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->table($this->table)->where('id', $id)->update($data);
    }

    /**
     * Delete / deactivate event
     */
    public function delete_event($id)
    {
        $this->db->table('event_attendances')->where('event_id', $id)->delete();
        return $this->db->table($this->table)->where('id', $id)->delete();
    }

    /**
     * Get user RSVP status for all events
     */
    public function get_user_rsvps($user_id)
    {
        $sql = "SELECT * FROM event_attendances WHERE user_id = ?";
        $stmt = $this->db->raw($sql, [(int)$user_id]);
        $rows = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
        $map = [];
        foreach ($rows as $r) {
            $map[$r['event_id']] = $r;
        }
        return $map;
    }

    /**
     * RSVP to an event (attending / not attending)
     */
    public function rsvp($event_id, $user_id, $member_id, $member_name, $status = 'attending')
    {
        $existing = $this->db->table('event_attendances')
            ->where('event_id', $event_id)
            ->where('user_id', $user_id)
            ->get();

        if ($existing) {
            return $this->db->table('event_attendances')
                ->where('id', $existing['id'])
                ->update(['rsvp_status' => $status]);
        } else {
            return $this->db->table('event_attendances')->insert([
                'event_id'      => (int)$event_id,
                'user_id'       => (int)$user_id,
                'member_id'     => $member_id,
                'member_name'   => $member_name,
                'rsvp_status'   => $status,
                'checked_in'    => 0,
                'created_at'    => date('Y-m-d H:i:s'),
            ]);
        }
    }

    /**
     * Cancel RSVP
     */
    public function cancel_rsvp($event_id, $user_id)
    {
        $sql = "DELETE FROM event_attendances WHERE event_id = ? AND user_id = ?";
        return $this->db->raw($sql, [(int)$event_id, (int)$user_id]);
    }

    /**
     * Check in attendee by member_id (e.g. Scanned via QR code or manual check-in)
     */
    public function check_in_by_member_id($event_id, $member_id_str)
    {
        $member_id_str = trim($member_id_str);
        $member = $this->db->table('members')->where('member_id', $member_id_str)->get();
        if (!$member) {
            return ['success' => false, 'message' => 'Invalid Member ID. Member not found.'];
        }

        $existing = $this->db->table('event_attendances')
            ->where('event_id', $event_id)
            ->where('user_id', $member['user_id'])
            ->get();

        if ($existing) {
            if (!empty($existing['checked_in'])) {
                return ['success' => true, 'already_checked_in' => true, 'member' => $member, 'message' => 'Member already checked in at ' . date('h:i A', strtotime($existing['checked_in_at']))];
            }
            $this->db->table('event_attendances')
                ->where('id', $existing['id'])
                ->update([
                    'checked_in'    => 1,
                    'checked_in_at' => date('Y-m-d H:i:s'),
                    'rsvp_status'   => 'attending'
                ]);
        } else {
            $this->db->table('event_attendances')->insert([
                'event_id'      => (int)$event_id,
                'user_id'       => (int)$member['user_id'],
                'member_id'     => $member['member_id'],
                'member_name'   => $member['full_name'],
                'rsvp_status'   => 'attending',
                'checked_in'    => 1,
                'checked_in_at' => date('Y-m-d H:i:s'),
                'created_at'    => date('Y-m-d H:i:s'),
            ]);
        }

        return ['success' => true, 'already_checked_in' => false, 'member' => $member, 'message' => 'Successfully checked in ' . htmlspecialchars($member['full_name']) . '!'];
    }

    /**
     * Get attendance list for an event
     */
    public function get_event_attendees($event_id)
    {
        $sql = "
            SELECT ea.*, m.avatar, m.email
            FROM event_attendances ea
            LEFT JOIN members m ON m.user_id = ea.user_id
            WHERE ea.event_id = ?
            ORDER BY ea.checked_in DESC, ea.created_at DESC
        ";
        $stmt = $this->db->raw($sql, [(int)$event_id]);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }
}
