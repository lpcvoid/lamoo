<?php
/**
 * Created by PhpStorm.
 * User: Swoosh
 * Date: 27.04.16
 * Time: 09:22
 */

class lmoLovooScraper
{

    public $db;
    private $_con;

    private $_uids  = [];

    function __construct(lmoLovooConnector $con, $sqlite_file)
    {

        $this->db = new PDO("sqlite:$sqlite_file");
        $this->db->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);

        $this->_con = $con;

    }

    function Scrape()
    {

        //100 pages
        for ($i = 0; $i < 500; $i++) {
            $data = $this->_con->GetUsers(18, 25, "false", $i + 1);

            foreach ($data->response->result as $key => $value) {
                if (!array_key_exists($value->id,$this->_uids)){

                    $det = $this->_con->GetUserDetails($value->id);
                    $this->AddUserToDatabase($value,$det->response->result->me, $det->response->result->flirt);
                    $this->_uids[$value->id]++;
                }


            }

        }


    }

    private function AddUserToDatabase($user,$details,$flirt)
    {
        $stmt = $this->db->prepare('INSERT INTO "main"."chicks" ("id","name","age",
"last_online","goal","vip","loc_current","loc_home","new","mobile","verified",
        "size_cm","hair_color","hair_len","eye_color","body_jewel","orgin","children",
        "job","relationship_status","looking_for","first_date","turns_off",
        "free_time","saturday","music","whazzup","freetext") VALUES  (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)'); //,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        var_dump($stmt);
        print_r($user);
        $stmt->bindParam(1, $user->id);
        $stmt->bindParam(2, $user->name);
        $stmt->bindParam(3, $user->age);
        $stmt->bindParam(4, $user->lastOnlineTime);
        $stmt->bindParam(5, json_encode($user->flirtInterests));
        $stmt->bindParam(6, $user->isVip);
        $stmt->bindParam(7, $user->locations->current->city);
        $stmt->bindParam(8, $user->locations->home->city);
        $stmt->bindParam(9, $user->isNew);
        $stmt->bindParam(10, $user->isMobile);
        $stmt->bindParam(11, $user->isVerified);


        $stmt->bindParam(12, str_replace("cm","",$details->size->label));
        $stmt->bindParam(13, $details->hair->label);
        $stmt->bindParam(14, $details->hairlength->label);
        $stmt->bindParam(15, $details->eye->label);
        $stmt->bindParam(16, $details->yewel->label);
        $stmt->bindParam(17, $details->root->label);
        $stmt->bindParam(18, $details->child->label);
        $stmt->bindParam(19, $details->job->label);



        $stmt->bindParam(20, $flirt->status->label);
        $stmt->bindParam(21, $flirt->search->label);
        $stmt->bindParam(22, $flirt->first->label);
        $stmt->bindParam(23, $flirt->turnsup->label);
        $stmt->bindParam(24, $flirt->freetime->label);
        $stmt->bindParam(25, $flirt->sat->label);
        $stmt->bindParam(26, $flirt->music->label);
        $stmt->bindParam(27, $user->whazzup);
        $stmt->bindParam(28, $user->freetext);






        $stmt->execute();
    }

} 