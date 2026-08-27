<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Auth extends Controller {

    public function __construct()
    {
        parent::__construct();
        $this->call->model('User_model');
    }

    /**
     * Show Login Page or process form
     */
    public function login()
    {
        // If already logged in, redirect to dashboard
        if ($this->session->has_userdata('user_id')) {
            redirect('dashboard');
            return;
        }

        $data = [
            'title' => 'Sign In - Life Generation',
            'error' => $this->session->flashdata('error') ?? null,
            'success' => $this->session->flashdata('success') ?? null,
            'username_val' => $this->session->flashdata('old_username') ?? ''
        ];

        $this->call->view('auth/login', $data);
    }

    /**
     * Authenticate user credentials
     */
    public function login_submit()
    {
        if ($this->io->is_post()) {
            $identifier = trim($this->io->post('identifier') ?? '');
            $password   = $this->io->post('password') ?? '';

            if (empty($identifier) || empty($password)) {
                $this->session->set_flashdata('error', 'Please enter your username/email and password.');
                $this->session->set_flashdata('old_username', $identifier);
                redirect('login');
                return;
            }

            // Look up user in database
            $user = $this->User_model->find_by_identifier($identifier);

            if ($user && isset($user['password'])) {
                // Verify password against stored bcrypt hash
                if (password_verify($password, $user['password'])) {
                    if (isset($user['is_active']) && (int)$user['is_active'] === 0) {
                        $this->session->set_flashdata('error', 'Your account has been deactivated. Please contact support.');
                        redirect('login');
                        return;
                    }

                    // Check if password hash needs rehash
                    if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
                        $newHash = password_hash($password, PASSWORD_DEFAULT);
                        $this->db->table('users')->where('id', $user['id'])->update(['password' => $newHash]);
                    }

                    // Prevent session fixation
                    $this->session->regenerate_on_login();

                    // Store user data in session
                    $this->session->set_userdata([
                        'user_id'   => $user['id'],
                        'username'  => $user['username'],
                        'email'     => $user['email'],
                        'role'      => $user['role'] ?? 'user',
                        'logged_in' => TRUE
                    ]);

                    $this->session->set_flashdata('success', 'Welcome back, ' . htmlspecialchars($user['username']) . '!');
                    redirect('dashboard');
                    return;
                }
            }

            // Authentication failed
            $this->session->set_flashdata('error', 'Invalid username/email or password.');
            $this->session->set_flashdata('old_username', $identifier);
            redirect('login');
            return;
        }

        redirect('login');
    }

    /**
     * Show Registration Page
     */
    public function register()
    {
        if ($this->session->has_userdata('user_id')) {
            redirect('dashboard');
            return;
        }

        $data = [
            'title' => 'Create an Account - Life Generation',
            'error' => $this->session->flashdata('error') ?? null,
            'success' => $this->session->flashdata('success') ?? null,
            'old' => $this->session->flashdata('old_data') ?? []
        ];

        $this->call->view('auth/register', $data);
    }

    /**
     * Handle user registration
     */
    public function register_submit()
    {
        if ($this->io->is_post()) {
            $username         = trim($this->io->post('username') ?? '');
            $email            = trim($this->io->post('email') ?? '');
            $password         = $this->io->post('password') ?? '';
            $password_confirm = $this->io->post('password_confirm') ?? '';

            $old_data = [
                'username' => $username,
                'email'    => $email
            ];

            // Validation checks
            if (empty($username) || empty($email) || empty($password) || empty($password_confirm)) {
                $this->session->set_flashdata('error', 'All fields are required.');
                $this->session->set_flashdata('old_data', $old_data);
                redirect('register');
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->session->set_flashdata('error', 'Please enter a valid email address.');
                $this->session->set_flashdata('old_data', $old_data);
                redirect('register');
                return;
            }

            if (strlen($password) < 6) {
                $this->session->set_flashdata('error', 'Password must be at least 6 characters.');
                $this->session->set_flashdata('old_data', $old_data);
                redirect('register');
                return;
            }

            if ($password !== $password_confirm) {
                $this->session->set_flashdata('error', 'Passwords do not match.');
                $this->session->set_flashdata('old_data', $old_data);
                redirect('register');
                return;
            }

            // Check if username or email is already taken
            if ($this->User_model->username_exists($username)) {
                $this->session->set_flashdata('error', 'That username is already registered. Please choose another.');
                $this->session->set_flashdata('old_data', $old_data);
                redirect('register');
                return;
            }

            if ($this->User_model->email_exists($email)) {
                $this->session->set_flashdata('error', 'That email address is already in use.');
                $this->session->set_flashdata('old_data', $old_data);
                redirect('register');
                return;
            }

            // Insert new user (hashed with bcrypt inside User_model)
            $created = $this->User_model->register_user([
                'username' => $username,
                'email'    => $email,
                'password' => $password,
                'role'     => 'user',
                'is_active'=> 1
            ]);

            if ($created) {
                $this->session->set_flashdata('success', 'Account created successfully! You can now log in.');
                redirect('login');
                return;
            } else {
                $this->session->set_flashdata('error', 'Failed to register account. Please try again.');
                $this->session->set_flashdata('old_data', $old_data);
                redirect('register');
                return;
            }
        }

        redirect('register');
    }

    /**
     * Authenticated Dashboard
     */
    public function dashboard()
    {
        if (!$this->session->has_userdata('user_id')) {
            $this->session->set_flashdata('error', 'Please log in to access the dashboard.');
            redirect('login');
            return;
        }

        $userId = $this->session->userdata('user_id');
        $user = $this->User_model->get_user_by_id($userId);

        $data = [
            'title' => 'Dashboard - Life Generation',
            'user'  => $user ?: [
                'username' => $this->session->userdata('username'),
                'email'    => $this->session->userdata('email'),
                'role'     => $this->session->userdata('role'),
                'created_at' => date('Y-m-d H:i:s')
            ],
            'success' => $this->session->flashdata('success') ?? null
        ];

        $this->call->view('auth/dashboard', $data);
    }

    /**
     * User Logout
     */
    public function logout()
    {
        $this->session->sess_destroy();
        session_start();
        $this->session->set_flashdata('success', 'You have been safely logged out.');
        redirect('login');
    }
}
