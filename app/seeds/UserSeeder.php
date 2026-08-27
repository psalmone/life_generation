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
     *
     * @return void
     */
    public function run()
    {
        // Check if admin user already exists
        $admin = $this->_lava->db->table('users')->where('email', 'admin@example.com')->get();

        if (!$admin) {
            $this->_lava->db->table('users')->insert([
                'username'   => 'admin',
                'email'      => 'admin@example.com',
                'password'   => password_hash('password123', PASSWORD_DEFAULT),
                'role'       => 'admin',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            echo "  Created admin user (email: admin@example.com, password: password123)\n";
        }

        // Check if regular demo user already exists
        $user = $this->_lava->db->table('users')->where('email', 'user@example.com')->get();

        if (!$user) {
            $this->_lava->db->table('users')->insert([
                'username'   => 'johndoe',
                'email'      => 'user@example.com',
                'password'   => password_hash('password123', PASSWORD_DEFAULT),
                'role'       => 'user',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            echo "  Created demo user (email: user@example.com, password: password123)\n";
        }
    }
}
