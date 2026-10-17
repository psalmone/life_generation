<?php
defined("PREVENT_DIRECT_ACCESS") OR exit("No direct script access allowed");

class Api_events extends Controller {

    private $api;

    public function __construct()
    {
        parent::__construct();
        $this->call->library("Api");
        $this->api = $this->Api;
        $this->call->model("Event_model");
        $this->call->model("Member_model");
    }

    // GET /api/events
    public function index()
    {
        $this->api->require_method("GET");
        $this->api->require_jwt();
        $events = $this->Event_model->get_upcoming_events();
        $this->api->respond(["events" => $events]);
    }

    // POST /api/events/rsvp
    public function rsvp()
    {
        $this->api->require_method("POST");
        $payload  = $this->api->require_jwt();
        $userId   = (int)$payload["sub"];
        $body     = $this->api->body();
        $event_id = (int)($body["event_id"] ?? 0);
        $event    = $this->Event_model->get_by_id($event_id);
        if (!$event || empty($event["is_active"])) $this->api->respond_error("Event not found.", 404);
        $member        = $this->Member_model->get_by_user_id($userId);
        $member_id_str = $member["member_id"] ?? "LG-MEMBER";
        $member_name   = $member["full_name"] ?? "Member";
        $this->Event_model->rsvp($event_id, $userId, $member_id_str, $member_name, "attending");
        $this->api->respond(["message" => "RSVP confirmed for " . $event["title"] . "!"]);
    }

    // POST /api/events/cancel-rsvp
    public function cancel_rsvp()
    {
        $this->api->require_method("POST");
        $payload  = $this->api->require_jwt();
        $userId   = (int)$payload["sub"];
        $body     = $this->api->body();
        $event_id = (int)($body["event_id"] ?? 0);
        $this->Event_model->cancel_rsvp($event_id, $userId);
        $this->api->respond(["message" => "RSVP cancelled."]);
    }

    // Admin: POST /api/events/create
    public function create()
    {
        $this->api->require_method("POST");
        $payload = $this->api->require_jwt();
        if (($payload["role"] ?? "") !== "admin") $this->api->respond_error("Unauthorized.", 403);
        $body        = $this->api->body();
        $title       = trim($body["title"] ?? "");
        $location    = trim($body["location"] ?? "");
        $event_date  = trim($body["event_date"] ?? "");
        $event_time  = trim($body["event_time"] ?? "");
        $description = trim($body["description"] ?? "");
        $capacity    = !empty($body["capacity"]) ? (int)$body["capacity"] : null;
        if (empty($title) || empty($location) || empty($event_date) || empty($event_time))
            $this->api->respond_error("Title, location, date, and time are required.", 422);
        $banner_image = null;
        if (isset($_FILES["banner_image"]) && $_FILES["banner_image"]["error"] === UPLOAD_ERR_OK) {
            $file = $_FILES["banner_image"];
            $ext  = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
            if (in_array($ext, ["jpg","jpeg","png","webp","gif"]) && @getimagesize($file["tmp_name"])) {
                $uploadDir = ROOT_DIR . "public" . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "events" . DIRECTORY_SEPARATOR;
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                $fileName = "event_" . time() . "." . $ext;
                if (move_uploaded_file($file["tmp_name"], $uploadDir . $fileName))
                    $banner_image = "uploads/events/" . $fileName;
            }
        }
        $this->Event_model->create_event(["title"=>$title,"description"=>$description,"banner_image"=>$banner_image,"location"=>$location,"event_date"=>$event_date,"event_time"=>$event_time,"capacity"=>$capacity]);
        $this->api->respond(["message" => "Event created."], 201);
    }

    // Admin: POST /api/events/update
    public function update()
    {
        $this->api->require_method("POST");
        $payload = $this->api->require_jwt();
        if (($payload["role"] ?? "") !== "admin") $this->api->respond_error("Unauthorized.", 403);
        $body     = $this->api->body();
        $event_id = (int)($body["event_id"] ?? 0);
        $event    = $this->Event_model->get_by_id($event_id);
        if (!$event) $this->api->respond_error("Event not found.", 404);
        $update = [
            "title"      => trim($body["title"] ?? ""),
            "location"   => trim($body["location"] ?? ""),
            "event_date" => trim($body["event_date"] ?? ""),
            "event_time" => trim($body["event_time"] ?? ""),
            "description"=> trim($body["description"] ?? ""),
            "capacity"   => !empty($body["capacity"]) ? (int)$body["capacity"] : null,
        ];
        if (isset($_FILES["banner_image"]) && $_FILES["banner_image"]["error"] === UPLOAD_ERR_OK) {
            $file = $_FILES["banner_image"];
            $ext  = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
            if (in_array($ext, ["jpg","jpeg","png","webp","gif"]) && @getimagesize($file["tmp_name"])) {
                $uploadDir = ROOT_DIR . "public" . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "events" . DIRECTORY_SEPARATOR;
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                $fileName = "event_" . time() . "." . $ext;
                if (move_uploaded_file($file["tmp_name"], $uploadDir . $fileName)) {
                    if (!empty($event["banner_image"])) { $old = ROOT_DIR."public".DIRECTORY_SEPARATOR.ltrim(str_replace("/",DIRECTORY_SEPARATOR,$event["banner_image"]),DIRECTORY_SEPARATOR); if(file_exists($old))@unlink($old); }
                    $update["banner_image"] = "uploads/events/" . $fileName;
                }
            }
        }
        $this->Event_model->update_event($event_id, $update);
        $this->api->respond(["message" => "Event updated."]);
    }

    // Admin: POST /api/events/delete
    public function delete()
    {
        $this->api->require_method("POST");
        $payload  = $this->api->require_jwt();
        if (($payload["role"] ?? "") !== "admin") $this->api->respond_error("Unauthorized.", 403);
        $body     = $this->api->body();
        $event_id = (int)($body["event_id"] ?? 0);
        $ev       = $this->Event_model->get_by_id($event_id);
        if ($ev && !empty($ev["banner_image"])) { $old = ROOT_DIR."public".DIRECTORY_SEPARATOR.ltrim(str_replace("/",DIRECTORY_SEPARATOR,$ev["banner_image"]),DIRECTORY_SEPARATOR); if(file_exists($old))@unlink($old); }
        $this->Event_model->delete_event($event_id);
        $this->api->respond(["message" => "Event deleted."]);
    }

    // Admin: POST /api/events/check-in
    public function check_in()
    {
        $this->api->require_method("POST");
        $payload = $this->api->require_jwt();
        if (($payload["role"] ?? "") !== "admin") $this->api->respond_error("Unauthorized.", 403);
        $body          = $this->api->body();
        $event_id      = (int)($body["event_id"] ?? 0);
        $member_id_str = trim($body["member_id"] ?? "");
        if ($event_id < 1 || empty($member_id_str)) $this->api->respond_error("Event ID and Member ID required.", 422);
        $res = $this->Event_model->check_in_by_member_id($event_id, $member_id_str);
        if ($res["success"]) $this->api->respond(["message" => $res["message"], "member" => $res["member"]]);
        else $this->api->respond_error($res["message"], 404);
    }
}
