<?php
require_once 'db.php';

/**
 * Variables from db.php
 * @var mysqli|false $conn Database connection resource or false if connection failed
 * @var string $storageFile Path to local storage JSON file
 */

function portfolio_submit_get_role_label($role)
{
	switch ($role) {
		case 'frontend':
			return 'Frontend Developer';
		case 'backend':
			return 'Backend Developer';
		case 'fullstack':
			return 'Full Stack Developer';
		default:
			return 'Other';
	}
}

function portfolio_submit_handle_input()
{
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

	return [
		'name' => $name,
		'email' => $email,
		'password' => $password,
		'role' => $role,
		'roleLabel' => portfolio_submit_get_role_label($role),
		'bio' => $bio,
		'gender' => $gender,
		'interests' => is_array($interests) ? implode(',', $interests) : '',
		'newsletter' => $newsletter,
		'passwordLength' => strlen($password),
	];
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	header('Location: index.html');
	exit;
}

global $conn, $storageFile;

$input = portfolio_submit_handle_input();
$savedMode = null;

if (empty($conn) || $conn === false) {
	portfolio_storage_upsert_record($storageFile, [
		'name' => $input['name'],
		'email' => $input['email'],
		'password_length' => $input['passwordLength'],
		'role' => $input['roleLabel'],
		'gender' => $input['gender'],
		'bio' => $input['bio'],
		'interests' => $input['interests'],
		'newsletter' => $input['newsletter'],
		'created_at' => date('Y-m-d H:i:s'),
	]);
	$savedMode = 'Local storage fallback';
} else {
	// Type assertion for static analysis
	assert($conn instanceof mysqli);
	
	try {
		mysqli_begin_transaction($conn);

		$insertUser = mysqli_prepare($conn, 'INSERT INTO users (name, email) VALUES (?, ?) ON DUPLICATE KEY UPDATE name = VALUES(name), id = LAST_INSERT_ID(id)');
		if (!$insertUser) {
			throw new Exception('Prepare failed: ' . mysqli_error($conn));
		}
		mysqli_stmt_bind_param($insertUser, 'ss', $input['name'], $input['email']);
		mysqli_stmt_execute($insertUser);
		$userId = mysqli_insert_id($conn);
		mysqli_stmt_close($insertUser);

		$insertProfile = mysqli_prepare($conn, 'INSERT INTO profiles (user_id, password_length, role, gender, bio, interests, newsletter) VALUES (?, ?, ?, ?, ?, ?, ?)');
		if (!$insertProfile) {
			throw new Exception('Prepare failed: ' . mysqli_error($conn));
		}
		mysqli_stmt_bind_param($insertProfile, 'iissssi', $userId, $input['passwordLength'], $input['roleLabel'], $input['gender'], $input['bio'], $input['interests'], $input['newsletter']);
		mysqli_stmt_execute($insertProfile);
		mysqli_stmt_close($insertProfile);

		mysqli_commit($conn);
	} catch (Throwable $e) {
		if (!empty($conn) && $conn !== false) {
			@mysqli_rollback($conn);
		}
		die('Failed to save data: ' . htmlspecialchars($e->getMessage()));
	}
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
		<p><strong>Name:</strong> <?php echo htmlspecialchars($input['name']); ?></p>
		<p><strong>Email:</strong> <?php echo htmlspecialchars($input['email']); ?></p>
		<p><strong>Role:</strong> <?php echo htmlspecialchars($input['roleLabel']); ?></p>
		<p><strong>Request Type:</strong> POST</p>
		<?php if ($savedMode !== null): ?>
		<p><strong>Storage:</strong> <?php echo htmlspecialchars($savedMode); ?></p>
		<?php endif; ?>
		<p>
			<a class="cta" href="index.html">Back to form</a>
			<a class="cta" href="view.php">View submissions</a>
		</p>
	</main>
</body>
</html>
