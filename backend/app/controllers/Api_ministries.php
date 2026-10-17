<?php
defined("PREVENT_DIRECT_ACCESS") OR exit("No direct script access allowed");

class Api_ministries extends Controller {
    private $api;
    public function __construct() {
        parent::__construct();
        $this->call->library("Api");
        $this->api = $this->Api;
        $this->call->model("Ministry_model");
        $this->call->model("Member_model");
    }
    public function index() {
        $this->api->require_method("GET");
        $payload = $this->api->require_jwt();
        $userId  = (int)$payload["sub"];
        $this->api->respond(["ministries" => $this->Ministry_model->get_active_with_status($userId)]);
    }
    public function apply() {
        $this->api->require_method("POST");
        $payload     = $this->api->require_jwt();
        $userId      = (int)$payload["sub"];
        $body        = $this->api->body();
        $ministry_id = (int)($body["ministry_id"] ?? 0);
        $ministry    = $this->Ministry_model->get_by_id($ministry_id);
        if (!$ministry || empty($ministry["is_active"])) $this->api->respond_error("Ministry not found.", 404);
        if ($this->Ministry_model->has_applied($userId, $ministry_id)) $this->api->respond_error("Already applied.", 409);
        $member     = $this->Member_model->get_by_user_id($userId);
        $memberName = $member["full_name"] ?? "Member";
        $this->Ministry_model->apply(["user_id"=>$userId,"ministry_id"=>$ministry_id,"member_name"=>$memberName,"message"=>trim($body["message"]??"")]);
        $this->api->respond(["message" => "Applied to " . $ministry["name"] . "!"], 201);
    }
    public function cancel() {
        $this->api->require_method("POST");
        $payload     = $this->api->require_jwt();
        $userId      = (int)$payload["sub"];
        $body        = $this->api->body();
        $ministry_id = (int)($body["ministry_id"] ?? 0);
        $this->Ministry_model->cancel_application($userId, $ministry_id);
        $this->api->respond(["message" => "Application withdrawn."]);
    }
    public function update_application() {
        $this->api->require_method("POST");
        $payload = $this->api->require_jwt();
        if (($payload["role"]??"user") !== "admin") $this->api->respond_error("Unauthorized.", 403);
        $body   = $this->api->body();
        $app_id = (int)($body["app_id"] ?? 0);
        $status = $body["status"] ?? "";
        if (!in_array($status, ["approved","rejected","pending"])) $this->api->respond_error("Invalid status.", 422);
        $this->Ministry_model->update_application_status($app_id, $status);
        $this->api->respond(["message" => "Application " . $status . "."]);
    }
    public function delete_application() {
        $this->api->require_method("POST");
        $payload = $this->api->require_jwt();
        if (($payload["role"]??"user") !== "admin") $this->api->respond_error("Unauthorized.", 403);
        $body   = $this->api->body();
        $app_id = (int)($body["app_id"] ?? 0);
        $this->Ministry_model->delete_application($app_id);
        $this->api->respond(["message" => "Application deleted."]);
    }
    public function create() {
        $this->api->require_method("POST");
        $payload = $this->api->require_jwt();
        if (($payload["role"]??"") !== "admin") $this->api->respond_error("Unauthorized.", 403);
        $body = $this->api->body();
        $name = trim($body["name"]??"");
        $icon = trim($body["icon"]??"???");
        $description = trim($body["description"]??"");
        if (empty($name)) $this->api->respond_error("Ministry name is required.", 422);
        $image = null;
        if (isset($_FILES["image"])&&$_FILES["image"]["error"]===UPLOAD_ERR_OK) {
            $file=$_FILES["image"]; $ext=strtolower(pathinfo($file["name"],PATHINFO_EXTENSION));
            if(in_array($ext,["jpg","jpeg","png","webp","gif"])&&@getimagesize($file["tmp_name"])){
                $dir=ROOT_DIR."public".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR."ministries".DIRECTORY_SEPARATOR;
                if(!is_dir($dir))mkdir($dir,0755,true);
                $fn="ministry_".time().".".(string)$ext;
                if(move_uploaded_file($file["tmp_name"],$dir.$fn))$image="uploads/ministries/".(string)$fn;
            }
        }
        $this->Ministry_model->create_ministry(["name"=>$name,"icon"=>$icon,"description"=>$description,"image"=>$image,"is_active"=>1]);
        $this->api->respond(["message"=>"Ministry created."],201);
    }
    public function update() {
        $this->api->require_method("POST");
        $payload = $this->api->require_jwt();
        if (($payload["role"]??"") !== "admin") $this->api->respond_error("Unauthorized.", 403);
        $body        = $this->api->body();
        $ministry_id = (int)($body["ministry_id"]??0);
        $ministry    = $this->Ministry_model->get_by_id($ministry_id);
        if (!$ministry) $this->api->respond_error("Ministry not found.", 404);
        $upd = ["name"=>trim($body["name"]??""),"icon"=>trim($body["icon"]??"???"),"description"=>trim($body["description"]??"")];
        if (isset($_FILES["image"])&&$_FILES["image"]["error"]===UPLOAD_ERR_OK) {
            $file=$_FILES["image"]; $ext=strtolower(pathinfo($file["name"],PATHINFO_EXTENSION));
            if(in_array($ext,["jpg","jpeg","png","webp","gif"])&&@getimagesize($file["tmp_name"])){
                $dir=ROOT_DIR."public".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR."ministries".DIRECTORY_SEPARATOR;
                if(!is_dir($dir))mkdir($dir,0755,true);
                $fn="ministry_".time().".".(string)$ext;
                if(move_uploaded_file($file["tmp_name"],$dir.$fn))$upd["image"]="uploads/ministries/".(string)$fn;
            }
        }
        $this->Ministry_model->update_ministry($ministry_id,$upd);
        $this->api->respond(["message"=>"Ministry updated."]);
    }
    public function delete() {
        $this->api->require_method("POST");
        $payload = $this->api->require_jwt();
        if (($payload["role"]??"") !== "admin") $this->api->respond_error("Unauthorized.", 403);
        $body = $this->api->body();
        $ministry_id = (int)($body["ministry_id"]??0);
        $min = $this->Ministry_model->get_by_id($ministry_id);
        if($min&&!empty($min["image"])){$old=ROOT_DIR."public".DIRECTORY_SEPARATOR.ltrim(str_replace("/",DIRECTORY_SEPARATOR,$min["image"]),DIRECTORY_SEPARATOR);if(file_exists($old))@unlink($old);}
        $this->Ministry_model->delete_ministry($ministry_id);
        $this->api->respond(["message"=>"Ministry deleted."]);
    }
}
