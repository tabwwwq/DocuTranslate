<?php
require_once '../config.php';
requireAuth();

$db = getDB();
$stmt = $db->prepare('DELETE FROM gmail_accounts WHERE user_id = ?');
$stmt->execute([$_SESSION['user_id']]);

jsonResponse(['success' => true]);
