 <?php

class OrderProcessor {

    public function process() {
        $a = 1;
        $b = 2;
        $c = 3;
        $d = 4;
        $e = 5;
        $f = 6;

        return $a;
    }
}


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
