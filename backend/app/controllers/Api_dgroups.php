<?php
defined("PREVENT_DIRECT_ACCESS") OR exit("No direct script access allowed");

class Api_dgroups extends Controller {
    private $api;
    public function __construct() {
        parent::__construct();
        $this->call->library("Api");
        $this->api = $this->Api;
        $this->call->model("Dgroup_model");
    }
    public function index() {
        $this->api->require_method("GET");
        $this->api->require_jwt();
        $this->api->respond(["dgroups" => $this->Dgroup_model->get_all_with_counts()]);
    }
    public function join() {
        $this->api->require_method("POST");
        $payload   = $this->api->require_jwt();
        $userId    = (int)$payload["sub"];
        $body      = $this->api->body();
        $dgroup_id = (int)($body["dgroup_id"] ?? 0);
        $group     = $this->Dgroup_model->get_by_id($dgroup_id);
        if (!$group) $this->api->respond_error("D-Group not found.", 404);
        $this->Dgroup_model->join($userId, $dgroup_id);
        $this->api->respond(["message" => "Joined " . $group["name"] . "!"]);
    }
    public function leave() {
        $this->api->require_method("POST");
        $payload = $this->api->require_jwt();
        $userId  = (int)$payload["sub"];
        $this->Dgroup_model->leave($userId);
        $this->api->respond(["message" => "Left your D-Group."]);
    }
    public function create() {
        $this->api->require_method("POST");
        $payload = $this->api->require_jwt();
        if (($payload["role"] ?? "") !== "admin") $this->api->respond_error("Unauthorized.", 403);
        $body        = $this->api->body();
        $name        = trim($body["name"] ?? "");
        $leader_name = trim($body["leader_name"] ?? "");
        $schedule    = trim($body["schedule"] ?? "");
        $location    = trim($body["location"] ?? "");
        $description = trim($body["description"] ?? "");
        if (empty($name)||empty($leader_name)||empty($schedule)||empty($location))
            $this->api->respond_error("Name, leader, schedule, and location are required.", 422);
        $image = null;
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
            $file = $_FILES["image"];
            $ext  = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
            if (in_array($ext,["jpg","jpeg","png","webp","gif"]) && @getimagesize($file["tmp_name"])) {
                $dir = ROOT_DIR."public".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR."dgroups".DIRECTORY_SEPARATOR;
                if (!is_dir($dir)) mkdir($dir,0755,true);
                $fn = "dgroup_".time().".".(string)$ext;
                if (move_uploaded_file($file["tmp_name"],$dir.$fn)) $image = "uploads/dgroups/".(string)$fn;
            }
        }
        $this->Dgroup_model->create_dgroup(["name"=>$name,"leader_name"=>$leader_name,"schedule"=>$schedule,"location"=>$location,"description"=>$description,"image"=>$image]);
        $this->api->respond(["message" => "D-Group created."], 201);
    }
    public function update() {
        $this->api->require_method("POST");
        $payload = $this->api->require_jwt();
        if (($payload["role"] ?? "") !== "admin") $this->api->respond_error("Unauthorized.", 403);
        $body      = $this->api->body();
        $dgroup_id = (int)($body["dgroup_id"] ?? 0);
        $group     = $this->Dgroup_model->get_by_id($dgroup_id);
        if (!$group) $this->api->respond_error("D-Group not found.", 404);
        $upd = ["name"=>trim($body["name"]??""),"leader_name"=>trim($body["leader_name"]??""),"schedule"=>trim($body["schedule"]??""),"location"=>trim($body["location"]??""),"description"=>trim($body["description"]??"")];
        if (!empty($body["remove_image"])) { if(!empty($group["image"])){$old=ROOT_DIR."public".DIRECTORY_SEPARATOR.ltrim(str_replace("/",DIRECTORY_SEPARATOR,$group["image"]),DIRECTORY_SEPARATOR);if(file_exists($old))@unlink($old);} $upd["image"]=null; }
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
            $file=$_FILES["image"]; $ext=strtolower(pathinfo($file["name"],PATHINFO_EXTENSION));
            if(in_array($ext,["jpg","jpeg","png","webp","gif"])&&@getimagesize($file["tmp_name"])){
                $dir=ROOT_DIR."public".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR."dgroups".DIRECTORY_SEPARATOR;
                if(!is_dir($dir))mkdir($dir,0755,true);
                $fn="dgroup_".time().".".(string)$ext;
                if(move_uploaded_file($file["tmp_name"],$dir.$fn))$upd["image"]="uploads/dgroups/".(string)$fn;
            }
        }
        $this->Dgroup_model->update_dgroup($dgroup_id,$upd);
        $this->api->respond(["message"=>"D-Group updated."]);
    }
    public function delete() {
        $this->api->require_method("POST");
        $payload = $this->api->require_jwt();
        if (($payload["role"] ?? "") !== "admin") $this->api->respond_error("Unauthorized.", 403);
        $body = $this->api->body();
        $dgroup_id = (int)($body["dgroup_id"] ?? 0);
        $group = $this->Dgroup_model->get_by_id($dgroup_id);
        if($group&&!empty($group["image"])){$old=ROOT_DIR."public".DIRECTORY_SEPARATOR.ltrim(str_replace("/",DIRECTORY_SEPARATOR,$group["image"]),DIRECTORY_SEPARATOR);if(file_exists($old))@unlink($old);}
        $this->Dgroup_model->delete_dgroup($dgroup_id);
        $this->api->respond(["message"=>"D-Group deleted."]);
    }
}
