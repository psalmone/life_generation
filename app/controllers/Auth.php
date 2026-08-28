<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Auth extends Controller {

    public function __construct()
    {
        parent::__construct();
        $this->call->model('User_model');
        $this->call->model('Member_model');
        $this->call->model('Dgroup_model');
        $this->call->model('Ministry_model');
        $this->call->model('Event_model');
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
     *  2. members — profile (name, age, address, email)
     *
     * Required fields: full_name, email, age, address, password, password_confirm
     * Username is auto-generated from the email prefix if not supplied.
     */
    public function register_submit()
    {
        if ($this->io->method() === 'post') {
            $full_name       = trim($this->io->post('full_name') ?? '');
            $email           = trim($this->io->post('email') ?? '');
            $address         = trim($this->io->post('address') ?? '');
            $age_raw         = trim($this->io->post('age') ?? '');
            $password        = $this->io->post('password') ?? '';
            $password_confirm= $this->io->post('password_confirm') ?? '';

            // Auto-generate username from email prefix
            $email_prefix = explode('@', $email)[0] ?? '';
            $username     = preg_replace('/[^a-zA-Z0-9_]/', '', strtolower($email_prefix));
            if (empty($username)) {
                $username = 'member' . rand(1000, 9999);
            }

            $old_data = [
                'full_name' => $full_name,
                'email'     => $email,
                'address'   => $address,
                'age'       => $age_raw,
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

        // Load D-Group data
        $current_dgroup = null;
        $all_dgroups    = [];
        if (!empty($member['dgroup_id'])) {
            $current_dgroup = $this->Dgroup_model->get_by_id($member['dgroup_id']);
            if ($current_dgroup) {
                $current_dgroup['member_count'] = $this->Dgroup_model->count_members($current_dgroup['id']);
            }
        } else {
            $all_dgroups = $this->Dgroup_model->get_all_with_counts();
        }

        // Load Ministry data for the current user
        $current_ministry = null;
        if (!empty($member['ministry_id'])) {
            $current_ministry = $this->Ministry_model->get_by_id($member['ministry_id']);
        }
        $ministries        = $this->Ministry_model->get_active_with_status($userId);
        $user_apps         = $this->Ministry_model->get_user_applications($userId);
        $all_ministry_apps = [];
        if (isset($user['role']) && $user['role'] === 'admin') {
            $all_ministry_apps = $this->Ministry_model->get_all_applications();
        }

        // Load Events & Attendance data
        $upcoming_events = $this->Event_model->get_upcoming_events();
        $user_rsvps      = $this->Event_model->get_user_rsvps($userId);
        $all_events_admin= [];
        if (isset($user['role']) && $user['role'] === 'admin') {
            $all_events_admin = $this->Event_model->get_all_events_admin();
        }

        $data = [
            'title'             => 'Membership Dashboard - Life Generation',
            'user'              => $user,
            'member'            => $member,
            'all_members'       => $all_members,
            'total_members'     => $total_members,
            'active_members'    => $active_members,
            'current_dgroup'    => $current_dgroup,
            'all_dgroups'       => $all_dgroups,
            'current_ministry'  => $current_ministry,
            'ministries'        => $ministries,
            'user_apps'         => $user_apps,
            'all_ministry_apps' => $all_ministry_apps,
            'upcoming_events'   => $upcoming_events,
            'user_rsvps'        => $user_rsvps,
            'all_events_admin'  => $all_events_admin,
            'success'           => $this->session->flashdata('success') ?? null,
            'error'             => $this->session->flashdata('error') ?? null,
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

    /**
     * Handle Avatar Photo Upload via Drag & Drop or File Select
     */
    public function upload_avatar()
    {
        if (!$this->session->has_userdata('user_id')) {
            redirect('login');
            return;
        }

        if ($this->io->method() === 'post') {
            $userId = $this->session->userdata('user_id');

            if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
                $errorCode = $_FILES['avatar']['error'] ?? 'No file';
                $this->session->set_flashdata('error', 'Please select a valid image file to upload (Error: ' . $errorCode . ').');
                redirect('dashboard');
                return;
            }

            $file    = $_FILES['avatar'];
            $maxSize = 5 * 1024 * 1024; // 5MB
            $exts    = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

            // File size validation
            if ($file['size'] > $maxSize) {
                $this->session->set_flashdata('error', 'Image size must not exceed 5MB.');
                redirect('dashboard');
                return;
            }

            // Extension validation
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $exts)) {
                $this->session->set_flashdata('error', 'Invalid file extension (.' . htmlspecialchars($ext) . '). Only PNG, JPG, JPEG, WEBP, and GIF are supported.');
                redirect('dashboard');
                return;
            }

            // Image verification using getimagesize
            $imageInfo = @getimagesize($file['tmp_name']);
            if (!$imageInfo) {
                $this->session->set_flashdata('error', 'The uploaded file is not a recognized or valid image.');
                redirect('dashboard');
                return;
            }

            // Destination directory
            $uploadDir = ROOT_DIR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'avatars' . DIRECTORY_SEPARATOR;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Unique filename
            $newFileName = 'avatar_' . $userId . '_' . time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
            $destination = $uploadDir . $newFileName;

            // Fetch current avatar to delete old file if it exists
            $member = $this->Member_model->get_by_user_id($userId);
            if (!empty($member['avatar'])) {
                $oldPath = ROOT_DIR . 'public' . DIRECTORY_SEPARATOR . ltrim(str_replace('/', DIRECTORY_SEPARATOR, $member['avatar']), DIRECTORY_SEPARATOR);
                if (file_exists($oldPath) && is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $avatarRelativeUrl = 'uploads/avatars/' . $newFileName;
                $this->Member_model->update_member($userId, [
                    'avatar' => $avatarRelativeUrl,
                ]);

                $this->session->set_flashdata('success', 'Profile photo updated successfully!');
            } else {
                $this->session->set_flashdata('error', 'Failed to save image. Please check server permissions.');
            }
        }

        redirect('dashboard');
    }

    /**
     * Remove / Reset Member Profile Photo to Default
     */
    public function remove_avatar()
    {
        if (!$this->session->has_userdata('user_id')) {
            redirect('login');
            return;
        }

        if ($this->io->method() === 'post') {
            $userId = $this->session->userdata('user_id');
            $member = $this->Member_model->get_by_user_id($userId);

            if ($member && !empty($member['avatar'])) {
                $oldPath = ROOT_DIR . 'public' . DIRECTORY_SEPARATOR . ltrim(str_replace('/', DIRECTORY_SEPARATOR, $member['avatar']), DIRECTORY_SEPARATOR);
                if (file_exists($oldPath) && is_file($oldPath)) {
                    @unlink($oldPath);
                }
                $this->Member_model->update_member($userId, [
                    'avatar' => null,
                ]);
                $this->session->set_flashdata('success', 'Profile photo removed successfully. Default initial restored.');
            }
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

    /**
     * Admin action: Promote or Demote member role (user, moderator, admin).
     */
    public function update_member_role()
    {
        if (!$this->session->has_userdata('user_id')) {
            redirect('login');
            return;
        }

        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Unauthorized.');
            redirect('dashboard');
            return;
        }

        if ($this->io->method() === 'post') {
            $target_user_id = (int)($this->io->post('user_id') ?? 0);
            $new_role       = trim($this->io->post('role') ?? '');
            $allowed_roles  = ['user', 'moderator', 'admin'];

            if (!in_array($new_role, $allowed_roles)) {
                $this->session->set_flashdata('error', 'Invalid role selected.');
                redirect('dashboard');
                return;
            }

            // Prevent admin from demoting themselves
            if ($target_user_id === (int)$this->session->userdata('user_id') && $new_role !== 'admin') {
                $this->session->set_flashdata('error', 'You cannot demote your own administrator account.');
                redirect('dashboard');
                return;
            }

            $user = $this->User_model->get_by_id($target_user_id);
            if ($user) {
                $this->User_model->update_user($target_user_id, ['role' => $new_role]);
                $this->session->set_flashdata('success', 'User <strong>' . htmlspecialchars($user['username']) . '</strong> role updated to <strong>' . strtoupper($new_role) . '</strong>.');
            } else {
                $this->session->set_flashdata('error', 'User not found.');
            }
        }

        redirect('dashboard');
    }

    /**
     * Admin action: Export Member Directory to CSV / Excel spreadsheet.
     */
    public function export_members_csv()
    {
        if (!$this->session->has_userdata('user_id')) {
            redirect('login');
            return;
        }

        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Unauthorized.');
            redirect('dashboard');
            return;
        }

        $all_members = $this->Member_model->get_all_members();

        $filename = 'lifegen_members_roster_' . date('Y-m-d_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        // UTF-8 BOM for Excel compatibility
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Header Row
        fputcsv($output, [
            'Member ID',
            'Full Name',
            'Username',
            'Email Address',
            'Age',
            'Address',
            'Role',
            'Ministry Team',
            'Discipleship Group',
            'Status',
            'Joined Date'
        ]);

        // Data Rows
        foreach ($all_members as $m) {
            fputcsv($output, [
                $m['member_id'] ?? '—',
                $m['full_name'] ?? '—',
                $m['username'] ?? '—',
                $m['email'] ?? '—',
                !empty($m['age']) ? $m['age'] : '—',
                $m['address'] ?? '—',
                strtoupper($m['role'] ?? 'user'),
                $m['ministry_name'] ?? 'None',
                $m['dgroup_name'] ?? 'None',
                !empty($m['is_active']) ? 'Active' : 'Inactive',
                !empty($m['joined_at']) ? date('Y-m-d H:i', strtotime($m['joined_at'])) : '—',
            ]);
        }

        fclose($output);
        exit;
    }

    // =========================================================================
    //  MINISTRY VOLUNTEER SIGN-UP
    // =========================================================================

    /**
     * Apply to a ministry.
     */
    public function apply_ministry()
    {
        if (!$this->session->has_userdata('user_id')) {
            redirect('login');
            return;
        }

        if ($this->io->method() === 'post') {
            $userId      = $this->session->userdata('user_id');
            $ministry_id = (int)($this->io->post('ministry_id') ?? 0);
            $message_val = $this->io->post('message');
            $message     = $message_val !== null ? trim($message_val) : '';

            if ($ministry_id < 1) {
                $this->session->set_flashdata('error', 'Invalid ministry selection.');
                redirect('dashboard');
                return;
            }

            $ministry = $this->Ministry_model->get_by_id($ministry_id);
            if (!$ministry || empty($ministry['is_active'])) {
                $this->session->set_flashdata('error', 'Ministry not found or inactive.');
                redirect('dashboard');
                return;
            }

            if ($this->Ministry_model->has_applied($userId, $ministry_id)) {
                $this->session->set_flashdata('error', 'You have already applied to this ministry.');
                redirect('dashboard');
                return;
            }

            $member     = $this->Member_model->get_by_user_id($userId);
            $memberName = $member['full_name'] ?? $this->session->userdata('username') ?? 'Member';

            $this->Ministry_model->apply([
                'user_id'     => $userId,
                'ministry_id' => $ministry_id,
                'member_name' => $memberName,
                'message'     => $message,
            ]);

            $this->session->set_flashdata('success', 'Your application to <strong>' . htmlspecialchars($ministry['name']) . '</strong> has been submitted!');
        }

        redirect('dashboard');
    }

    /**
     * Cancel a pending ministry application.
     */
    public function cancel_ministry()
    {
        if (!$this->session->has_userdata('user_id')) {
            redirect('login');
            return;
        }

        if ($this->io->method() === 'post') {
            $userId      = $this->session->userdata('user_id');
            $ministry_id = (int)($this->io->post('ministry_id') ?? 0);

            if ($ministry_id > 0) {
                $this->Ministry_model->cancel_application($userId, $ministry_id);
                $this->session->set_flashdata('success', 'Your application has been withdrawn.');
            }
        }

        redirect('dashboard');
    }

    /**
     * Admin: approve or reject a ministry application.
     */
    public function update_ministry_application()
    {
        if (!$this->session->has_userdata('user_id')) {
            redirect('login');
            return;
        }

        $role = $this->session->userdata('role');
        if ($role !== 'admin') {
            $this->session->set_flashdata('error', 'Unauthorized.');
            redirect('dashboard');
            return;
        }

        if ($this->io->method() === 'post') {
            $app_id = (int)($this->io->post('app_id') ?? 0);
            $status = $this->io->post('status');

            if ($app_id > 0 && in_array($status, ['approved', 'rejected', 'pending'])) {
                $this->Ministry_model->update_application_status($app_id, $status);
                $this->session->set_flashdata('success', 'Application status updated to <strong>' . ucfirst($status) . '</strong>.');
            }
        }

        redirect('dashboard');
    }

    // =========================================================================
    //  D-GROUP ENROLLMENT
    // =========================================================================

    /**
     * Join a D-Group.
     */
    public function join_dgroup()
    {
        if (!$this->session->has_userdata('user_id')) {
            redirect('login');
            return;
        }

        if ($this->io->method() === 'post') {
            $dgroup_id = (int)($this->io->post('dgroup_id') ?? 0);

            if ($dgroup_id < 1) {
                $this->session->set_flashdata('error', 'Invalid D-Group selection.');
                redirect('dashboard');
                return;
            }

            $group = $this->Dgroup_model->get_by_id($dgroup_id);
            if (!$group) {
                $this->session->set_flashdata('error', 'D-Group not found.');
                redirect('dashboard');
                return;
            }

            $userId = $this->session->userdata('user_id');
            $this->Dgroup_model->join($userId, $dgroup_id);
            $this->session->set_flashdata('success', 'You have joined <strong>' . htmlspecialchars($group['name']) . '</strong>. Welcome to your D-Group!');
        }

        redirect('dashboard');
    }

    /**
     * Leave current D-Group.
     */
    public function leave_dgroup()
    {
        if (!$this->session->has_userdata('user_id')) {
            redirect('login');
            return;
        }

        if ($this->io->method() === 'post') {
            $userId = $this->session->userdata('user_id');
            $this->Dgroup_model->leave($userId);
            $this->session->set_flashdata('success', 'You have left your D-Group. You can join a new one anytime.');
        }

        redirect('dashboard');
    }

    // =========================================================================
    //  EVENT MANAGEMENT & ATTENDANCE TRACKING
    // =========================================================================

    /**
     * Member: RSVP for an upcoming event.
     */
    public function rsvp_event()
    {
        if (!$this->session->has_userdata('user_id')) {
            redirect('login');
            return;
        }

        if ($this->io->method() === 'post') {
            $userId   = $this->session->userdata('user_id');
            $event_id = (int)($this->io->post('event_id') ?? 0);
            $event    = $this->Event_model->get_by_id($event_id);

            if (!$event || empty($event['is_active'])) {
                $this->session->set_flashdata('error', 'Event not found or inactive.');
                redirect('dashboard');
                return;
            }

            $member = $this->Member_model->get_by_user_id($userId);
            $member_id_str = $member['member_id'] ?? 'LG-MEMBER';
            $member_name   = $member['full_name'] ?? $this->session->userdata('username') ?? 'Member';

            $this->Event_model->rsvp($event_id, $userId, $member_id_str, $member_name, 'attending');
            $this->session->set_flashdata('success', 'RSVP confirmed for <strong>' . htmlspecialchars($event['title']) . '</strong>! See you there.');
        }

        redirect('dashboard');
    }

    /**
     * Member: Cancel RSVP.
     */
    public function cancel_rsvp_event()
    {
        if (!$this->session->has_userdata('user_id')) {
            redirect('login');
            return;
        }

        if ($this->io->method() === 'post') {
            $userId   = $this->session->userdata('user_id');
            $event_id = (int)($this->io->post('event_id') ?? 0);
            $this->Event_model->cancel_rsvp($event_id, $userId);
            $this->session->set_flashdata('success', 'Your RSVP has been cancelled.');
        }

        redirect('dashboard');
    }

    /**
     * Admin: Create a new event.
     */
    public function create_event()
    {
        if (!$this->session->has_userdata('user_id')) {
            redirect('login');
            return;
        }

        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Unauthorized.');
            redirect('dashboard');
            return;
        }

        if ($this->io->method() === 'post') {
            $title        = trim($this->io->post('title') ?? '');
            $location     = trim($this->io->post('location') ?? '');
            $event_date   = trim($this->io->post('event_date') ?? '');
            $event_time   = trim($this->io->post('event_time') ?? '');
            $description  = trim($this->io->post('description') ?? '');
            $capacity     = !empty($this->io->post('capacity')) ? (int)$this->io->post('capacity') : null;
            $banner_image = null;

            if (empty($title) || empty($location) || empty($event_date) || empty($event_time)) {
                $this->session->set_flashdata('error', 'Title, location, date, and time are required.');
                redirect('dashboard');
                return;
            }

            // Handle optional event banner / invitation image upload
            if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['banner_image'];
                $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $exts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

                if (in_array($ext, $exts) && @getimagesize($file['tmp_name'])) {
                    $uploadDir = ROOT_DIR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'events' . DIRECTORY_SEPARATOR;
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $fileName = 'event_' . time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
                    if (move_uploaded_file($file['tmp_name'], $uploadDir . $fileName)) {
                        $banner_image = 'uploads/events/' . $fileName;
                    }
                }
            }

            $this->Event_model->create_event([
                'title'        => $title,
                'description'  => $description,
                'banner_image' => $banner_image,
                'location'     => $location,
                'event_date'   => $event_date,
                'event_time'   => $event_time,
                'capacity'     => $capacity,
            ]);

            $this->session->set_flashdata('success', 'New event <strong>' . htmlspecialchars($title) . '</strong> created with visual invitation banner!');
        }

        redirect('dashboard');
    }

    /**
     * Admin: Update an existing event.
     */
    public function update_event()
    {
        if (!$this->session->has_userdata('user_id')) {
            redirect('login');
            return;
        }

        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Unauthorized.');
            redirect('dashboard');
            return;
        }

        if ($this->io->method() === 'post') {
            $event_id    = (int)($this->io->post('event_id') ?? 0);
            $title       = trim($this->io->post('title') ?? '');
            $location    = trim($this->io->post('location') ?? '');
            $event_date  = trim($this->io->post('event_date') ?? '');
            $event_time  = trim($this->io->post('event_time') ?? '');
            $description = trim($this->io->post('description') ?? '');
            $capacity    = !empty($this->io->post('capacity')) ? (int)$this->io->post('capacity') : null;

            $event = $this->Event_model->get_by_id($event_id);
            if (!$event) {
                $this->session->set_flashdata('error', 'Event not found.');
                redirect('dashboard');
                return;
            }

            $update_data = [
                'title'       => $title,
                'location'    => $location,
                'event_date'  => $event_date,
                'event_time'  => $event_time,
                'capacity'    => $capacity,
                'description' => $description,
            ];

            // Handle optional new flyer image replacement
            if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['banner_image'];
                $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $exts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

                if (in_array($ext, $exts) && @getimagesize($file['tmp_name'])) {
                    $uploadDir = ROOT_DIR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'events' . DIRECTORY_SEPARATOR;
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $fileName = 'event_' . time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
                    if (move_uploaded_file($file['tmp_name'], $uploadDir . $fileName)) {
                        // Delete old flyer file if exists
                        if (!empty($event['banner_image'])) {
                            $oldPath = ROOT_DIR . 'public' . DIRECTORY_SEPARATOR . ltrim(str_replace('/', DIRECTORY_SEPARATOR, $event['banner_image']), DIRECTORY_SEPARATOR);
                            if (file_exists($oldPath) && is_file($oldPath)) {
                                @unlink($oldPath);
                            }
                        }
                        $update_data['banner_image'] = 'uploads/events/' . $fileName;
                    }
                }
            }

            $this->Event_model->update_event($event_id, $update_data);
            $this->session->set_flashdata('success', 'Event <strong>' . htmlspecialchars($title) . '</strong> updated successfully!');
        }

        redirect('dashboard');
    }

    /**
     * Admin: Delete an event.
     */
    public function delete_event()
    {
        if (!$this->session->has_userdata('user_id')) {
            redirect('login');
            return;
        }

        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Unauthorized.');
            redirect('dashboard');
            return;
        }

        if ($this->io->method() === 'post') {
            $event_id = (int)($this->io->post('event_id') ?? 0);
            if ($event_id > 0) {
                $ev = $this->Event_model->get_by_id($event_id);
                if ($ev && !empty($ev['banner_image'])) {
                    $oldPath = ROOT_DIR . 'public' . DIRECTORY_SEPARATOR . ltrim(str_replace('/', DIRECTORY_SEPARATOR, $ev['banner_image']), DIRECTORY_SEPARATOR);
                    if (file_exists($oldPath) && is_file($oldPath)) {
                        @unlink($oldPath);
                    }
                }
                $this->Event_model->delete_event($event_id);
                $this->session->set_flashdata('success', 'Event removed successfully.');
            }
        }

        redirect('dashboard');
    }

    /**
     * Admin: Check-in an attendee by Member ID (QR Scan or Manual ID entry).
     */
    public function check_in_attendee()
    {
        if (!$this->session->has_userdata('user_id')) {
            redirect('login');
            return;
        }

        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Unauthorized.');
            redirect('dashboard');
            return;
        }

        if ($this->io->method() === 'post') {
            $event_id      = (int)($this->io->post('event_id') ?? 0);
            $member_id_str = trim($this->io->post('member_id') ?? '');

            if ($event_id < 1 || empty($member_id_str)) {
                $this->session->set_flashdata('error', 'Event and Member ID are required for check-in.');
                redirect('dashboard');
                return;
            }

            $res = $this->Event_model->check_in_by_member_id($event_id, $member_id_str);
            if ($res['success']) {
                $this->session->set_flashdata('success', $res['message']);
            } else {
                $this->session->set_flashdata('error', $res['message']);
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
