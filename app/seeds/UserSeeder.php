<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UserSeeder {

    private $_lava;

    public function __construct()
    {
        $this->_lava = lava_instance();
        $this->_lava->call->database();
    }

    /**
     * Run the database seeds.
     * Creates demo accounts in BOTH the users table AND the members table.
     *
     * @return void
     */
    public function run()
    {
        $db = $this->_lava->db;

        // ── Admin Account ──────────────────────────────────────────────────
        $admin_user = $db->table('users')->where('email', 'admin@example.com')->get();

        if (!$admin_user) {
            $admin_user_id = $db->table('users')->insert([
                'username'   => 'admin',
                'email'      => 'admin@example.com',
                'password'   => password_hash('password123', PASSWORD_DEFAULT),
                'role'       => 'admin',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            if (!$admin_user_id) {
                $admin_user_id = $db->last_id();
            }

            // Check members table for this user before inserting
            $admin_member = $db->table('members')->where('user_id', $admin_user_id)->get();
            if (!$admin_member) {
                $db->table('members')->insert([
                    'user_id'        => $admin_user_id,
                    'member_id'      => 'LG-2026-ADMIN',
                    'full_name'      => 'Chief Master Administrator',
                    'age'            => 32,
                    'address'        => '100 Life Generation HQ, Innovation Blvd, Metro Manila',
                    'email'          => 'admin@example.com',
                    'membership_tier'=> 'VIP Member',
                    'is_active'      => 1,
                    'joined_at'      => date('Y-m-d H:i:s'),
                ]);
            }

            echo "  ✓ Created admin user (email: admin@example.com, password: password123)\n";
        }

        // ── Demo Member Account ─────────────────────────────────────────────
        $demo_user = $db->table('users')->where('email', 'user@example.com')->get();

        if (!$demo_user) {
            $demo_user_id = $db->table('users')->insert([
                'username'   => 'danielle',
                'email'      => 'user@example.com',
                'password'   => password_hash('password123', PASSWORD_DEFAULT),
                'role'       => 'user',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            if (!$demo_user_id) {
                $demo_user_id = $db->last_id();
            }

            $demo_member = $db->table('members')->where('user_id', $demo_user_id)->get();
            if (!$demo_member) {
                $db->table('members')->insert([
                    'user_id'        => $demo_user_id,
                    'member_id'      => 'LG-2026-DEMO1',
                    'full_name'      => 'Danielle Vitto',
                    'age'            => 24,
                    'address'        => '742 Evergreen Terrace, Sector 4, Calabarzon',
                    'email'          => 'user@example.com',
                    'membership_tier'=> 'Gold Member',
                    'is_active'      => 1,
                    'joined_at'      => date('Y-m-d H:i:s'),
                ]);
            }

            echo "  ✓ Created demo member (email: user@example.com, password: password123)\n";
        }
    }
}
