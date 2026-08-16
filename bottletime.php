<?php
$db = new PDO('sqlite:/var/www/pisowifi.db');
$input = json_decode(file_get_contents('php://input'), true);
$bottle_count = $input['count'] ?? 1;
$user_ip = $_SERVER['REMOTE_ADDR'];

if ($bottle_count > 0) {
    // Exchange rate: 1 plastic bottle = 15 minutes (900 seconds) of internet time
    $seconds_to_add = $bottle_count * 15 * 60;

    $stmt = $db->prepare("SELECT expiration FROM sessions WHERE ip_address = ?");
    $stmt->execute([$user_ip]);
    $session = $stmt->fetch();

    if ($session) {
        $current_expiry = max(time(), $session['expiration']);
        $new_expiry = $current_expiry + $seconds_to_add;
        $update = $db->prepare("UPDATE sessions SET expiration = ? WHERE ip_address = ?");
        $update->execute([$new_expiry, $user_ip]);
    } else {
        $new_expiry = time() + $seconds_to_add;
        $insert = $db->prepare("INSERT INTO sessions (ip_address, expiration) VALUES (?, ?)");
        $insert->execute([$user_ip, $new_expiry]);
    }

    // Whitelist the client's IP via iptables
    exec("sudo iptables -I FORWARD -s " . escapeshellarg($user_ip) . " -j ACCEPT");
    echo json_encode(["status" => "success", "new_expiry" => date("Y-m-d H:i:s", $new_expiry)]);
}
?>
