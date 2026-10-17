<?php
defined("PREVENT_DIRECT_ACCESS") OR exit("No direct script access allowed");

class Api_public extends Controller {
    private $api;
    public function __construct() {
        parent::__construct();
        $this->call->library("Api");
        $this->api = $this->Api;
        $this->call->model("Event_model");
        $this->call->model("Dgroup_model");
        $this->call->model("Ministry_model");
        $this->call->model("Prayer_request_model");
        $this->call->model("Member_model");
    }
    // GET /api/public/home
    public function home() {
        $this->api->require_method("GET");
        $this->api->rate_limit("public_home", 60, 60);
        $upcoming_events = $this->Event_model->get_upcoming_events();
        $dgroups         = $this->Dgroup_model->get_all_with_counts();
        $ministries      = $this->Ministry_model->get_active();
        $prayer_requests = $this->Prayer_request_model->get_wall(0, false);
        $this->api->respond([
            "total_members"   => $this->Member_model->count_members(),
            "active_members"  => $this->Member_model->count_active_members(),
            "upcoming_events" => array_slice($upcoming_events, 0, 3),
            "dgroups"         => array_slice($dgroups, 0, 3),
            "ministries"      => array_slice($ministries, 0, 4),
            "prayer_requests" => array_slice($prayer_requests, 0, 4),
        ]);
    }
}
