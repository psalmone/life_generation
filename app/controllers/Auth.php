<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Auth extends Controller {

    public function __construct()
    {
        parent::__construct();
        $this->call->model('User_model');
        $this->call->model('Member_model');
    }

    // =========================================================================
    //  COMMUNITY HOMEPAGE
    // =========================================================================

    /**
     * Show the Christian Community Homepage.
     */
    public function home()
    {
        $is_logged_in = $this->session->has_userdata('user_id');
        $user_data = null;

        if ($is_logged_in) {
            $user_id = $this->session->userdata('user_id');
            $user_data = [
                'username'  => $this->session->userdata('username'),
                'full_name' => $this->session->userdata('full_name'),
                'role'      => $this->session->userdata('role'),
                'member_id' => $this->session->userdata('member_id'),
            ];
        }

        $data = [
            'title'          => 'Life Generation - Christian Community & Fellowship',
            'is_logged_in'   => $is_logged_in,
            'user'           => $user_data,
            'total_members'  => $this->Member_model->count_members(),
            'active_members' => $this->Member_model->count_active_members(),
        ];

        $this->call->view('home', $data);
    }

    // =========================================================================
    //  LOGIN
    // =========================================================================

    /**
     * Show the Login page.
     */
    public function login()
    {
        if ($this->session->has_userdata('user_id')) {
            redirect('dashboard');
            return;
        }

        $data = [
            'title'        => 'Sign In - Life Generation',
            'error'        => $this->session->flashdata('error') ?? null,
            'success'      => $this->session->flashdata('success') ?? null,
            'username_val' => $this->session->flashdata('old_username') ?? ''
        ];

        $this->call->view('auth/login', $data);
    }

    /**
     * Authenticate credentials.
     * Supports login by Email, Username, or Member ID.
     */
    public function login_submit()
    {
        if ($this->io->method() === 'post') {
            $identifier = trim($this->io->post('identifier') ?? '');
            $password   = $this->io->post('password') ?? '';

            if (empty($identifier) || empty($password)) {
                $this->session->set_flashdata('error', 'Please enter your Member ID, Username, or Email along with your password.');
                $this->session->set_flashdata('old_username', $identifier);
                redirect('login');
                return;
            }

            // Find user account row
            $user = $this->User_model->find_by_identifier($identifier);

            if ($user && isset($user['password'])) {
                if (password_verify($password, $user['password'])) {

                    // Check user-level account status
                    if (isset($user['is_active']) && (int)$user['is_active'] === 0) {
                        $this->session->set_flashdata('error', 'Your account is currently inactive. Please contact support.');
                        redirect('login');
                        return;
                    }

                    // Rehash if needed
                    if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
                        $newHash = password_hash($password, PASSWORD_DEFAULT);
                        $this->User_model->update_user($user['id'], ['password' => $newHash]);
                    }

                    // Load the member profile linked to this user
                    $member = $this->Member_model->get_by_user_id($user['id']);

                    // Check member-level active status
                    if ($member && (int)($member['is_active'] ?? 1) === 0) {
                        $this->session->set_flashdata('error', 'Your membership is currently inactive. Please contact support.');
                        redirect('login');
                        return;
                    }

                    // Regenerate session for security
                    $this->session->regenerate_on_login();

                    // Store combined session data
                    $this->session->set_userdata([
                        'user_id'         => $user['id'],
                        'username'        => $user['username'],
                        'email'           => $user['email'],
                        'role'            => $user['role'] ?? 'user',
                        'logged_in'       => TRUE,
                        // Member-level session data
                        'member_row_id'   => $member['id'] ?? null,
                        'member_id'       => $member['member_id'] ?? '',
                        'full_name'       => $member['full_name'] ?? $user['username'],
                        'membership_tier' => $member['membership_tier'] ?? 'Standard Member',
                    ]);

                    $greetingName = !empty($member['full_name']) ? $member['full_name'] : $user['username'];
                    $this->session->set_flashdata('success', 'Welcome back, ' . htmlspecialchars($greetingName) . '!');
                    redirect('dashboard');
                    return;
                }
            }

            $this->session->set_flashdata('error', 'Invalid credentials. Please verify your details and try again.');
            $this->session->set_flashdata('old_username', $identifier);
            redirect('login');
            return;
        }

        redirect('login');
    }

    // =========================================================================
    //  REGISTRATION
    // =========================================================================

    /**
     * Show the Membership Registration page.
     */
    public function register()
    {
        if ($this->session->has_userdata('user_id')) {
            redirect('dashboard');
            return;
        }

        $data = [
            'title'   => 'Membership Registration - Life Generation',
            'error'   => $this->session->flashdata('error') ?? null,
            'success' => $this->session->flashdata('success') ?? null,
            'old'     => $this->session->flashdata('old_data') ?? []
        ];

        $this->call->view('auth/register', $data);
    }

    /**
     * Handle membership registration.
     *
     * Creates TWO rows:
     *  1. users  — login credentials + role
     *  2. members — profile (name, age, address, email, membership_tier)
     *
     * Required fields: full_name, email, age, address, password, password_confirm
     * Optional fields: membership_tier (defaults to Standard Member)
     * Username is auto-generated from the email prefix if not supplied.
     */
    public function register_submit()
    {
        if ($this->io->method() === 'post') {
            $full_name       = trim($this->io->post('full_name') ?? '');
            $email           = trim($this->io->post('email') ?? '');
            $address         = trim($this->io->post('address') ?? '');
            $age_raw         = trim($this->io->post('age') ?? '');
            $membership_tier = trim($this->io->post('membership_tier') ?? 'Standard Member');
            $password        = $this->io->post('password') ?? '';
            $password_confirm= $this->io->post('password_confirm') ?? '';

            // Auto-generate username from email prefix
            $email_prefix = explode('@', $email)[0] ?? '';
            $username     = preg_replace('/[^a-zA-Z0-9_]/', '', strtolower($email_prefix));
            if (empty($username)) {
                $username = 'member' . rand(1000, 9999);
            }

            $old_data = [
                'full_name'       => $full_name,
                'email'           => $email,
                'address'         => $address,
                'age'             => $age_raw,
                'membership_tier' => $membership_tier,
            ];

            // ---- Validation ----
            if (empty($full_name)) {
                $this->_reg_error('Full Name is required.', $old_data);
                return;
            }

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->_reg_error('A valid email address is required.', $old_data);
                return;
            }

            if (empty($address)) {
                $this->_reg_error('Address is required for membership registration.', $old_data);
                return;
            }

            $age = filter_var($age_raw, FILTER_VALIDATE_INT);
            if ($age === false || $age < 1 || $age > 125) {
                $this->_reg_error('Please enter a valid age between 1 and 125.', $old_data);
                return;
            }

            if (strlen($password) < 6) {
                $this->_reg_error('Password must be at least 6 characters.', $old_data);
                return;
            }

            if ($password !== $password_confirm) {
                $this->_reg_error('Passwords do not match.', $old_data);
                return;
            }

            // Check email uniqueness in both tables
            if ($this->User_model->email_exists($email)) {
                $this->_reg_error('That email address is already registered.', $old_data);
                return;
            }

            if ($this->Member_model->email_exists($email)) {
                $this->_reg_error('That email address is already linked to a membership.', $old_data);
                return;
            }

            // Ensure username uniqueness
            if ($this->User_model->username_exists($username)) {
                $username = $username . rand(10, 9999);
            }

            // Sanitize membership tier
            $valid_tiers = ['Standard Member', 'Silver Member', 'Gold Member', 'VIP Member'];
            if (!in_array($membership_tier, $valid_tiers)) {
                $membership_tier = 'Standard Member';
            }

            // ---- Create user account (credentials only) ----
            $user_created = $this->User_model->create_user([
                'username'   => $username,
                'email'      => $email,
                'password'   => $password,
                'role'       => 'user',
                'is_active'  => 1,
            ]);

            if (!$user_created) {
                $this->_reg_error('Registration failed (user account). Please try again.', $old_data);
                return;
            }

            $new_user_id = is_numeric($user_created) && (int)$user_created > 0 ? (int)$user_created : $this->db->last_id();

            // ---- Create member profile ----
            $member_id = $this->Member_model->generate_member_id();

            $member_created = $this->Member_model->create_member([
                'user_id'        => $new_user_id,
                'member_id'      => $member_id,
                'full_name'      => $full_name,
                'age'            => (int)$age,
                'address'        => $address,
                'email'          => $email,
                'membership_tier'=> $membership_tier,
                'is_active'      => 1,
            ]);

            if ($member_created) {
                $this->session->set_flashdata('success', "Membership created! Your Member ID is <strong>{$member_id}</strong>. You can now log in.");
                $this->session->set_flashdata('old_username', $email);
                redirect('login');
                return;
            } else {
                // Roll back user creation if member profile failed
                $this->db->table('users')->where('id', $new_user_id)->delete();
                $this->_reg_error('Registration failed (member profile). Please try again.', $old_data);
                return;
            }
        }

        redirect('register');
    }

    // =========================================================================
    //  DASHBOARD
    // =========================================================================

    /**
     * Member / Admin Dashboard.
     */
    public function dashboard()
    {
        if (!$this->session->has_userdata('user_id')) {
            $this->session->set_flashdata('error', 'Please sign in to access your membership dashboard.');
            redirect('login');
            return;
        }

        $userId = $this->session->userdata('user_id');

        // Load user account row
        $user = $this->User_model->get_by_id($userId);
        if (!$user) {
            $this->session->sess_destroy();
            redirect('login');
            return;
        }

        // Load member profile row
        $member = $this->Member_model->get_by_user_id($userId);

        // If member profile is missing (edge case: admin accounts), create a stub
        if (empty($member)) {
            $member_id = $this->Member_model->generate_member_id();
            $this->Member_model->create_member([
                'user_id'        => $userId,
                'member_id'      => $member_id,
                'full_name'      => $user['username'],
                'age'            => null,
                'address'        => null,
                'email'          => $user['email'],
                'membership_tier'=> 'Standard Member',
                'is_active'      => 1,
            ]);
            $member = $this->Member_model->get_by_user_id($userId);
        }

        // Admin stats
        $all_members   = [];
        $total_members = 0;
        $active_members= 0;

        if (isset($user['role']) && $user['role'] === 'admin') {
            $all_members   = $this->Member_model->get_all_members();
            $total_members = count($all_members);
            foreach ($all_members as $m) {
                if (!empty($m['is_active'])) {
                    $active_members++;
                }
            }
        }

        $data = [
            'title'          => 'Membership Dashboard - Life Generation',
            'user'           => $user,
            'member'         => $member,
            'all_members'    => $all_members,
            'total_members'  => $total_members,
            'active_members' => $active_members,
            'success'        => $this->session->flashdata('success') ?? null,
            'error'          => $this->session->flashdata('error') ?? null,
        ];

        $this->call->view('auth/dashboard', $data);
    }

    // =========================================================================
    //  PROFILE UPDATE
    // =========================================================================

    /**
     * Update logged-in member's profile details.
     * Updates the members table (name, age, address) and syncs email to users table.
     */
    public function update_profile()
    {
        if (!$this->session->has_userdata('user_id')) {
            redirect('login');
            return;
        }

        if ($this->io->method() === 'post') {
            $userId    = $this->session->userdata('user_id');
            $full_name = trim($this->io->post('full_name') ?? '');
            $email     = trim($this->io->post('email') ?? '');
            $address   = trim($this->io->post('address') ?? '');
            $age_raw   = trim($this->io->post('age') ?? '');

            if (empty($full_name) || empty($email) || empty($address)) {
                $this->session->set_flashdata('error', 'Full Name, Email, and Address cannot be empty.');
                redirect('dashboard');
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->session->set_flashdata('error', 'Please enter a valid email address.');
                redirect('dashboard');
                return;
            }

            $age = filter_var($age_raw, FILTER_VALIDATE_INT);
            if ($age === false || $age < 1 || $age > 125) {
                $this->session->set_flashdata('error', 'Please enter a valid age (1-125).');
                redirect('dashboard');
                return;
            }

            // Check email uniqueness
            if ($this->User_model->email_exists($email, $userId)) {
                $this->session->set_flashdata('error', 'That email address is already in use by another account.');
                redirect('dashboard');
                return;
            }

            if ($this->Member_model->email_exists($email, $userId)) {
                $this->session->set_flashdata('error', 'That email address is already linked to another membership.');
                redirect('dashboard');
                return;
            }

            // Update member profile
            $m_updated = $this->Member_model->update_member($userId, [
                'full_name' => $full_name,
                'age'       => (int)$age,
                'address'   => $address,
                'email'     => $email,
            ]);

            // Sync email to users table as well
            $this->User_model->update_user($userId, ['email' => $email]);

            if ($m_updated) {
                $this->session->set_userdata([
                    'full_name' => $full_name,
                    'email'     => $email,
                ]);
                $this->session->set_flashdata('success', 'Your membership profile has been updated successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to update profile. Please try again.');
            }

            redirect('dashboard');
            return;
        }

        redirect('dashboard');
    }

    // =========================================================================
    //  ADMIN: TOGGLE MEMBER STATUS
    // =========================================================================

    /**
     * Admin action: Toggle a member's active status.
     * Targets the members.is_active column (not users.is_active).
     */
    public function toggle_member_status()
    {
        if (!$this->session->has_userdata('user_id')) {
            redirect('login');
            return;
        }

        $current_role = $this->session->userdata('role');
        if ($current_role !== 'admin') {
            $this->session->set_flashdata('error', 'Unauthorized access.');
            redirect('dashboard');
            return;
        }

        if ($this->io->method() === 'post') {
            $member_row_id = (int)$this->io->post('member_row_id');

            // Load member by primary key (members.id)
            $target_member = $this->db->table('members')
                ->where('id', $member_row_id)
                ->get();

            if ($target_member) {
                $new_status  = empty($target_member['is_active']) ? 1 : 0;
                $status_label = $new_status ? 'activated' : 'deactivated';

                $this->Member_model->toggle_active($member_row_id, $new_status);
                $name = htmlspecialchars($target_member['full_name'] ?? 'Member');
                $this->session->set_flashdata('success', "{$name} has been {$status_label}.");
            } else {
                $this->session->set_flashdata('error', 'Member not found.');
            }
        }

        redirect('dashboard');
    }

    // =========================================================================
    //  LOGOUT
    // =========================================================================

    public function logout()
    {
        $this->session->sess_destroy();
        if (session_status() !== PHP_SESSION_ACTIVE && !headers_sent()) {
            session_start();
        }
        $this->session->set_flashdata('success', 'You have been safely signed out.');
        redirect('login');
    }

    // =========================================================================
    //  PRIVATE HELPERS
    // =========================================================================

    /**
     * Flash registration error and redirect back.
     */
    private function _reg_error($message, $old_data)
    {
        $this->session->set_flashdata('error', $message);
        $this->session->set_flashdata('old_data', $old_data);
        redirect('register');
    }
}
