<?php
class NoSQLDatabase {
    private $dataDir = 'data/';
    public function __construct() {
        if (!is_dir($this->dataDir)) {
            mkdir($this->dataDir, 0755, true);
        }}
    public function saveUserPreferences($user_id, $preferences) {
        $file = $this->dataDir . 'user_preferences.json';
        $data = [];
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
        }  
        $data[$user_id] = [
            'user_id' => $user_id,
            'preferences' => $preferences,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $result = file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
        
        if ($result) {
            error_log("NoSQL: Préférences sauvegardées pour l'utilisateur $user_id");
        } 
        return $result; }
    public function getUserPreferences($user_id) {
        $file = $this->dataDir . 'user_preferences.json';
        
        if (!file_exists($file)) {
            return null;
        }    
        $data = json_decode(file_get_contents($file), true);
        return $data[$user_id]['preferences'] ?? null;}
    public function saveRideAnalytics($ride_data) {
        $file = $this->dataDir . 'ride_analytics.json';
        $data = [];
        
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
        }   
        $analytics_id = uniqid('analytic_');
        $data[$analytics_id] = [
            'analytic_id' => $analytics_id,
            'data' => $ride_data,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        return file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));}
    public function getAllRideAnalytics() {
        $file = $this->dataDir . 'ride_analytics.json';
        if (!file_exists($file)) {
            return [];
        }  
        return json_decode(file_get_contents($file), true);
    }}
$nosql = new NoSQLDatabase();
?>