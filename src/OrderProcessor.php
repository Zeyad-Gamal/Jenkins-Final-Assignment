// <?php
// namespace App;

// class OrderProcessor {
//     public function process($name, $service) {
//         if (empty($name) || strlen($name) < 3) {
//             return ["status" => "error", "message" => "Invalid Name"];
//         }
        
//         $validServices = ['Web Development', 'DevOps Automation', 'Cloud Migration'];
//         if (!in_array($service, $validServices)) {
//             return ["status" => "error", "message" => "Invalid Service"];
//         }

//         return [
//             "status" => "success",
//             "message" => "Order Confirmed for $name"
//         ];
//     }
// }

<?php
namespace App;

class OrderProcessor {

    public function process($name, $service) {

        $x = 10;          // unused variable ❌ (code smell)

        if ($name == "") {   // weak comparison ❌
            return "error";
        }

        if ($service == "test") {
            return "ok";
        }

        // duplicated logic ❌
        if ($service == "test") {
            return "duplicate";
        }

        return "done";
    }
}
