<?php
defined("PREVENT_DIRECT_ACCESS") OR exit("No direct script access allowed");

class Api_prayer extends Controller {
    private $api;
    public function __construct() {
        parent::__construct();
        $this->call->library("Api");
        $this->api = $this->Api;
        $this->call->model("Prayer_request_model");
        $this->call->model("Member_model");
        $this->call->model("User_model");
    }
    public function index() {
        $this->api->require_method("GET");
        $payload  = $this->api->require_jwt();
        $userId   = (int)$payload["sub"];
        $user     = $this->User_model->get_by_id($userId);
        $is_admin = ($user["role"] ?? "user") === "admin";
        $requests = $this->Prayer_request_model->get_wall($userId, $is_admin);
        $this->api->respond(["requests" => $requests]);
    }
    public function submit() {
        $this->api->require_method("POST");
        $payload    = $this->api->require_jwt();
        $userId     = (int)$payload["sub"];
        $body       = $this->api->body();
        $title      = trim($body["title"] ?? "");
        $bodyText   = trim($body["body"] ?? "");
        $visibility = ($body["visibility"] ?? "public") === "private" ? "private" : "public";
        if (empty($title) || empty($bodyText)) $this->api->respond_error("Title and body are required.", 422);
        if (strlen($title) > 200) $this->api->respond_error("Title max 200 characters.", 422);
        $member     = $this->Member_model->get_by_user_id($userId);
        $memberName = $member["full_name"] ?? "Anonymous";
        $this->Prayer_request_model->submit(["user_id"=>$userId,"member_name"=>$memberName,"title"=>$title,"body"=>$bodyText,"visibility"=>$visibility,"status"=>"active"]);
        $this->api->respond(["message" => "Prayer request submitted."], 201);
    }
    public function mark_answered() {
        $this->api->require_method("POST");
        $payload  = $this->api->require_jwt();
        $userId   = (int)$payload["sub"];
        $user     = $this->User_model->get_by_id($userId);
        $is_admin = ($user["role"] ?? "user") === "admin";
        $body     = $this->api->body();
        $id       = (int)($body["request_id"] ?? 0);
        if ($id > 0) $this->Prayer_request_model->update_status($id, "answered", $userId, $is_admin);
        $this->api->respond(["message" => "Marked as answered."]);
    }
    public function delete() {
        $this->api->require_method("POST");
        $payload  = $this->api->require_jwt();
        $userId   = (int)$payload["sub"];
        $user     = $this->User_model->get_by_id($userId);
        $is_admin = ($user["role"] ?? "user") === "admin";
        $body     = $this->api->body();
        $id       = (int)($body["request_id"] ?? 0);
        if ($id > 0) $this->Prayer_request_model->delete_request($id, $userId, $is_admin);
        $this->api->respond(["message" => "Prayer request deleted."]);
    }
}
