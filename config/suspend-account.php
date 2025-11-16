<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit();}
$type = $_POST['type'];
$action = $_POST['action'];
if ($type == 'utilisateur') {
    $user_id = $_POST['user_id'];
    $suspended = ($action == 'suspend') ? 1 : 0;
    $sql = "UPDATE utilisateur SET suspended = ? WHERE utilisateur_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $suspended, $user_id);    
    $user_type = "utilisateur";} else {
    $emp_id = $_POST['emp_id'];
    $suspended = ($action == 'suspend') ? 1 : 0;
    $sql = "UPDATE employe SET suspended = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $suspended, $emp_id);   
    $user_type = "employé";}
if ($stmt->execute()) {
    $status = ($action == 'suspend') ? "suspendu" : "réactivé";
    $_SESSION['success_message'] = "✅ $user_type $status avec succès!";} else {
    $_SESSION['error_message'] = "❌ Erreur lors de l'opération";}
header("Location: ../admin-dashboard.php");
exit();
?>