<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Prayer_wall extends Controller {

    public function __construct()
    {
        parent::__construct();
        $this->call->model('User_model');
        $this->call->model('Member_model');
        $this->call->model('Prayer_request_model');

        // Must be logged in
        if (!$this->session->has_userdata('user_id')) {
            $this->session->set_flashdata('error', 'Please sign in to access the Prayer Wall.');
            redirect('login');
            exit;
        }
    }

    // =========================================================================
    //  PRAYER WALL PAGE
    // =========================================================================

    public function index()
    {
        $userId   = $this->session->userdata('user_id');
        $user     = $this->User_model->get_by_id($userId);
        $is_admin = ($user['role'] ?? 'user') === 'admin';

        $requests = $this->Prayer_request_model->get_wall($userId, $is_admin);
        $member   = $this->Member_model->get_by_user_id($userId);

        $data = [
            'title'    => 'Prayer Wall - Life Generation',
            'user'     => $user,
            'member'   => $member,
            'requests' => $requests,
            'is_admin' => $is_admin,
            'success'  => $this->session->flashdata('success') ?? null,
            'error'    => $this->session->flashdata('error') ?? null,
        ];

        $this->call->view('prayer_wall', $data);
    }

    // =========================================================================
    //  SUBMIT PRAYER REQUEST
    // =========================================================================

    public function submit()
    {
        if ($this->io->method() !== 'post') {
            redirect('prayer-wall');
            return;
        }

        $userId     = $this->session->userdata('user_id');
        $member     = $this->Member_model->get_by_user_id($userId);
        $memberName = $member['full_name'] ?? $this->session->userdata('username') ?? 'Anonymous';

        $title      = trim($this->io->post('title') ?? '');
        $body       = trim($this->io->post('body') ?? '');
        $visibility = $this->io->post('visibility') === 'private' ? 'private' : 'public';

        if (empty($title) || empty($body)) {
            $this->session->set_flashdata('error', 'Title and request body are required.');
            redirect('prayer-wall');
            return;
        }

        if (strlen($title) > 200) {
            $this->session->set_flashdata('error', 'Title must be 200 characters or fewer.');
            redirect('prayer-wall');
            return;
        }

        $this->Prayer_request_model->submit([
            'user_id'     => $userId,
            'member_name' => $memberName,
            'title'       => $title,
            'body'        => $body,
            'visibility'  => $visibility,
            'status'      => 'active',
        ]);

        $this->session->set_flashdata('success', 'Your prayer request has been submitted. The community is praying with you.');
        redirect('prayer-wall');
    }

    // =========================================================================
    //  MARK AS ANSWERED
    // =========================================================================

    public function mark_answered()
    {
        if ($this->io->method() !== 'post') {
            redirect('prayer-wall');
            return;
        }

        $userId   = $this->session->userdata('user_id');
        $user     = $this->User_model->get_by_id($userId);
        $is_admin = ($user['role'] ?? 'user') === 'admin';
        $id       = (int)($this->io->post('request_id') ?? 0);

        if ($id > 0) {
            $this->Prayer_request_model->update_status($id, 'answered', $userId, $is_admin);
            $this->session->set_flashdata('success', 'Praise God! This prayer has been marked as answered.');
        }

        redirect('prayer-wall');
    }

    // =========================================================================
    //  DELETE PRAYER REQUEST
    // =========================================================================

    public function delete()
    {
        if ($this->io->method() !== 'post') {
            redirect('prayer-wall');
            return;
        }

        $userId   = $this->session->userdata('user_id');
        $user     = $this->User_model->get_by_id($userId);
        $is_admin = ($user['role'] ?? 'user') === 'admin';
        $id       = (int)($this->io->post('request_id') ?? 0);

        if ($id > 0) {
            $this->Prayer_request_model->delete_request($id, $userId, $is_admin);
            $this->session->set_flashdata('success', 'Prayer request removed.');
        }

        redirect('prayer-wall');
    }
}
