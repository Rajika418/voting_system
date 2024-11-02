<?php
require '../../db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $userId = $_GET['user_id'];
    $electionId = $_GET['election_id'];

    // Check if the user has already voted in this election
    $stmt = $pdo->prepare("SELECT COUNT(*) as vote_count FROM votes WHERE user_id = :user_id AND election_id = :election_id");
    $stmt->execute(['user_id' => $userId, 'election_id' => $electionId]);
    $result = $stmt->fetch();

    if ($result['vote_count'] > 0) {
        echo json_encode(['status' => 'voted']);
    } else {
        echo json_encode(['status' => 'not_voted']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
