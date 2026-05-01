<?php
require_once 'db.php';

if (!$conn) {
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Database Not Available</title><link rel="stylesheet" href="style.css"></head><body><main class="panel" style="width:min(760px,92%);margin:2rem auto;"><h2>MySQL Server Not Running</h2><p>' . htmlspecialchars($dbError ?: 'Unable to connect to the database server.') . '</p><p>Start MySQL from XAMPP/WAMP, then refresh this page.</p><p><a class="cta" href="index.html">Back to form</a></p></main></body></html>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	header('Location: index.html');
	exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$role = trim($_POST['role'] ?? '');
$bio = trim($_POST['bio'] ?? '');
$gender = trim($_POST['gender'] ?? '');
$interests = $_POST['interests'] ?? [];
$newsletter = isset($_POST['newsletter']) ? 1 : 0;

if ($name === '' || $email === '' || $password === '' || $role === '' || $gender === '') {
	die('Required fields are missing. <a href="index.html">Go back</a>');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	die('Invalid email format. <a href="index.html">Go back</a>');
}

if (strlen($password) < 8) {
	die('Password must be at least 8 characters. <a href="index.html">Go back</a>');
}

$roleLabel = '';
switch ($role) {
	case 'frontend':
		$roleLabel = 'Frontend Developer';
		break;
	case 'backend':
		$roleLabel = 'Backend Developer';
		break;
	case 'fullstack':
		$roleLabel = 'Full Stack Developer';
		break;
	default:
		$roleLabel = 'Other';
		break;
}

$interestList = is_array($interests) ? implode(',', $interests) : '';
$passwordLength = strlen($password);

mysqli_begin_transaction($conn);

try {
	$insertUser = mysqli_prepare($conn, 'INSERT INTO users (name, email) VALUES (?, ?) ON DUPLICATE KEY UPDATE name = VALUES(name), id = LAST_INSERT_ID(id)');
	mysqli_stmt_bind_param($insertUser, 'ss', $name, $email);
	mysqli_stmt_execute($insertUser);
	$userId = mysqli_insert_id($conn);
	mysqli_stmt_close($insertUser);

	$insertProfile = mysqli_prepare($conn, 'INSERT INTO profiles (user_id, password_length, role, gender, bio, interests, newsletter) VALUES (?, ?, ?, ?, ?, ?, ?)');
	mysqli_stmt_bind_param($insertProfile, 'iissssi', $userId, $passwordLength, $roleLabel, $gender, $bio, $interestList, $newsletter);
	mysqli_stmt_execute($insertProfile);
	mysqli_stmt_close($insertProfile);

	mysqli_commit($conn);
} catch (Throwable $e) {
	mysqli_rollback($conn);
	die('Failed to save data: ' . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Submission Successful</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<main class="panel" style="width:min(760px,92%);margin:2rem auto;">
		<h2>Data Saved Successfully</h2>
		<p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
		<p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
		<p><strong>Role:</strong> <?php echo htmlspecialchars($roleLabel); ?></p>
		<p><strong>Request Type:</strong> POST</p>
		<p>
			<a class="cta" href="index.html">Back to form</a>
			<a class="cta" href="view.php">View submissions</a>
		</p>
	</main>
</body>
</html>