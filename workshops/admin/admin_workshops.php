<?php
require_once __DIR__ . '/../../protected/config/database.php';
session_start();
/*if   (
    !isset($_SESSION['user']) ||
    ($_SESSION['user']['role'] ?? null) !== 'admin'
) {
    // change the path if your login file is somewhere else
    header('Location: ../public/login.php');
    exit;
}  يتفعل بعدين بعد ما نخلص      */

$pdo = Database::getConnection();
$errors = [];
$success = null;

// Handle form submissions 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action      = $_POST['action'] ?? '';
    $id          = isset($_POST['id']) ? (int)$_POST['id'] : null;
    $title       = trim($_POST['title'] ?? '');
    $category    = trim($_POST['category'] ?? '');
    $price       = trim($_POST['price'] ?? '');
    $capacity    = (int)($_POST['capacity'] ?? 0);
    $seatsAvail  = (int)($_POST['seats_available'] ?? 0);
    $startDate   = trim($_POST['start_date'] ?? '');
    $startTime   = trim($_POST['start_time'] ?? '');
    $location    = trim($_POST['location'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // --------- Basic validation ----------
    if ($title === '') {
        $errors[] = 'Title is required.';
    }
    if ($startDate === '') {
        $errors[] = 'Start date is required.';
    }
    if ($price === '' || !is_numeric($price)) {
        $errors[] = 'Valid price is required.';
    }
    if ($capacity < 0) {
        $errors[] = 'Capacity cannot be negative.';
    }
    if ($seatsAvail < 0) {
        $errors[] = 'Available seats cannot be negative.';
    }

    // --------- Image upload handling ----------
    $imageName = null;
    $currentImage = null;

    // For update, load existing image so we keep it if no new file
    if ($action === 'update' && $id) {
        $stmt = $pdo->prepare("SELECT image FROM workshops WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $currentImage = $row['image'];
        }
    }

    if (empty($errors)) {
        // If a file was uploaded
        if (!empty($_FILES['image_file']['name'])) {
            $uploadDir = __DIR__ . '/../../public/uploads/';

            // Create uploads folder if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $originalName = basename($_FILES['image_file']['name']);
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($ext, $allowedExt)) {
                $errors[] = 'Only JPG, JPEG, PNG, and WEBP images are allowed.';
            } else {
                // Generate safe, unique filename
                $safeName = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $originalName);
                $imageName = time() . '_' . $safeName;
                $targetFile = $uploadDir . $imageName;

                if (!move_uploaded_file($_FILES['image_file']['tmp_name'], $targetFile)) {
                    $errors[] = 'Failed to upload image file.';
                }
            }
        } else {
            // No new file uploaded
            if ($action === 'update') {
                // Keep old image
                $imageName = $currentImage;
            } else {
                // Create without image
                $imageName = null;
            }
        }
    }

    // --------- Database insert / update ----------
    if (empty($errors)) {
        if ($action === 'create') {
            $stmt = $pdo->prepare("
                INSERT INTO workshops 
                    (title, category, price, capacity, seats_available, start_date, start_time, location, description, image) 
                VALUES 
                    (:title, :category, :price, :capacity, :seats_available, :start_date, :start_time, :location, :description, :image)
            ");
            $stmt->execute([
                ':title'           => $title,
                ':category'        => $category,
                ':price'           => $price,
                ':capacity'        => $capacity,
                ':seats_available' => $seatsAvail,
                ':start_date'      => $startDate,
                ':start_time'      => $startTime ?: null,
                ':location'        => $location,
                ':description'     => $description,
                ':image'           => $imageName,
            ]);
            $success = 'Workshop created successfully.';
        } elseif ($action === 'update' && $id) {
            $stmt = $pdo->prepare("
                UPDATE workshops
                SET title = :title,
                    category = :category,
                    price = :price,
                    capacity = :capacity,
                    seats_available = :seats_available,
                    start_date = :start_date,
                    start_time = :start_time,
                    location = :location,
                    description = :description,
                    image = :image
                WHERE id = :id
            ");
            $stmt->execute([
                ':title'           => $title,
                ':category'        => $category,
                ':price'           => $price,
                ':capacity'        => $capacity,
                ':seats_available' => $seatsAvail,
                ':start_date'      => $startDate,
                ':start_time'      => $startTime ?: null,
                ':location'        => $location,
                ':description'     => $description,
                ':image'           => $imageName,
                ':id'              => $id,
            ]);
            $success = 'Workshop updated successfully.';
        }
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];
    if ($deleteId > 0) {
        $stmt = $pdo->prepare("DELETE FROM workshops WHERE id = :id");
        $stmt->execute([':id' => $deleteId]);
        $success = 'Workshop deleted successfully.';
    }
}

// Handle delete purchased workshop
if (isset($_GET['delete_item'])) {
    $itemId = (int)$_GET['delete_item'];
    if ($itemId > 0) {
        $stmt = $pdo->prepare("DELETE FROM order_items WHERE id = :id");
        $stmt->execute([':id' => $itemId]);
        $success = 'Purchased workshop (order item) deleted successfully.';
    }
}

// If editing, load that workshop data
$editWorkshop = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    if ($editId > 0) {
        $stmt = $pdo->prepare("SELECT * FROM workshops WHERE id = :id");
        $stmt->execute([':id' => $editId]);
        $editWorkshop = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// Get all workshops to list
$stmt = $pdo->query("SELECT * FROM workshops ORDER BY start_date ASC, start_time ASC");
$workshops = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get all purchased workshops with user information
$stmt = $pdo->query("
    SELECT 
        oi.id,
        oi.order_id,
        oi.user_id,
        oi.price,
        oi.quantity,
        (oi.price * oi.quantity) AS subtotal,
        w.title AS workshop_title,
        w.start_date,
        w.start_time,
        u.full_name AS user_full_name,
        u.email AS user_email
    FROM order_items oi
    JOIN workshops w ON oi.workshop_id = w.id
    JOIN users u ON oi.user_id = u.id
    ORDER BY oi.id DESC
");
$orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin – Manage Workshops</title>
  <link rel="stylesheet" href="../assets/css/base.css">
  <link rel="stylesheet" href="../assets/css/index.css">
  <?php require_once __DIR__ . '/../shared/styleFonts.php'; ?>
</head>
<body>

<?php require_once __DIR__ . "/../shared/headerAdmin.php" ?>

<main id="admin-main">
    <section id="admin-content">
        <h1>Admin Dashboard</h1>

        <?php if ($success): ?>
            <div class="message success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="message error">
                <?php foreach ($errors as $e): ?>
                    <div><?= htmlspecialchars($e) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php
            $isEdit = $editWorkshop !== null;
            $formTitle = $isEdit ? 'Edit Workshop' : 'Create New Workshop';
            $action = $isEdit ? 'update' : 'create';
        ?>

        <h2><?= htmlspecialchars($formTitle) ?></h2>

        <form method="post" class="admin-form" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?= htmlspecialchars($action) ?>">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= (int)$editWorkshop['id'] ?>">
            <?php endif; ?>

            <div class="row">
                <label>
                    Title
                    <input type="text" name="title" required
                           value="<?= htmlspecialchars($editWorkshop['title'] ?? '') ?>">
                </label>
                <label>
                    Category
                    <input type="text" name="category"
                           value="<?= htmlspecialchars($editWorkshop['category'] ?? '') ?>">
                </label>
            </div>

            <div class="row">
                <label>
                    Price
                    <input type="number" step="0.01" name="price" required
                           value="<?= htmlspecialchars($editWorkshop['price'] ?? '') ?>">
                </label>
                <label>
                    Capacity
                    <input type="number" name="capacity" min="0" required
                           value="<?= htmlspecialchars($editWorkshop['capacity'] ?? '0') ?>">
                </label>
                <label>
                    Seats Available
                    <input type="number" name="seats_available" min="0" required
                           value="<?= htmlspecialchars($editWorkshop['seats_available'] ?? '0') ?>">
                </label>
            </div>

            <div class="row">
                <label>
                    Start Date
                    <input type="date" name="start_date" required
                           value="<?= htmlspecialchars($editWorkshop['start_date'] ?? '') ?>">
                </label>
                <label>
                    Start Time
                    <input type="time" name="start_time"
                           value="<?= htmlspecialchars($editWorkshop['start_time'] ?? '') ?>">
                </label>
                <label>
                    Location
                    <input type="text" name="location"
                           value="<?= htmlspecialchars($editWorkshop['location'] ?? '') ?>">
                </label>
            </div>

            <div class="row">
                <label>
                    Upload Image
                    <input type="file" name="image_file" accept="image/*">
                </label>

                <?php if (!empty($editWorkshop['image'])): ?>
                    <div style="margin-top: 0.5em;">
                        <span>Current Image:</span><br>
                        <img src="../public/uploads/<?= htmlspecialchars($editWorkshop['image']) ?>"
                             alt="Workshop image"
                             style="max-width: 150px; max-height: 150px; border-radius: 8px;">
                    </div>
                <?php endif; ?>
            </div>

            <div class="row">
                <label>
                    Description
                    <textarea name="description"><?= htmlspecialchars($editWorkshop['description'] ?? '') ?></textarea>
                </label>
            </div>

            <button type="submit"><?= $isEdit ? 'Update Workshop' : 'Create Workshop' ?></button>
            <?php if ($isEdit): ?>
                <a href="admin_workshops.php" class="button">Cancel Edit</a>
            <?php endif; ?>
        </form>

        <h2>All Workshops</h2>
        <table class="admin-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Date / Time</th>
                <th>Category</th>
                <th>Price</th>
                <th>Capacity</th>
                <th>Available</th>
                <th>Location</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!$workshops): ?>
                <tr><td colspan="9">No workshops found.</td></tr>
            <?php else: ?>
                <?php foreach ($workshops as $w): ?>
                    <tr>
                        <td><?= (int)$w['id'] ?></td>
                        <td><?= htmlspecialchars($w['title']) ?></td>
                        <td>
                            <?= htmlspecialchars($w['start_date']) ?>
                            <?php if (!empty($w['start_time'])): ?>
                                <?= ' ' . htmlspecialchars(substr($w['start_time'], 0, 5)) ?>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($w['category']) ?></td>
                        <td><?= htmlspecialchars($w['price']) ?></td>
                        <td><?= htmlspecialchars($w['capacity']) ?></td>
                        <td><?= htmlspecialchars($w['seats_available']) ?></td>
                        <td><?= htmlspecialchars($w['location']) ?></td>
                        <td>
                            <a class="button"
                               href="admin_workshops.php?edit=<?= (int)$w['id'] ?>">Edit</a>
                            <a class="button delete"
                               href="admin_workshops.php?delete=<?= (int)$w['id'] ?>"
                               onclick="return confirm('Delete this workshop?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>

        <h2>Purchased Workshops (Order Items)</h2>
        <table class="admin-table">
            <thead>
            <tr>
                <th>Item ID</th>
                <th>Order ID</th>
                <th>User ID</th>
                <th>User Name</th>
                <th>User Email</th>
                <th>Workshop</th>
                <th>Date / Time</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($orderItems)): ?>
                <tr>
                    <td colspan="11">No purchased workshops found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($orderItems as $item): ?>
                    <tr>
                        <td><?= (int)$item['id'] ?></td>
                        <td><?= (int)$item['order_id'] ?></td>
                        <td><?= (int)$item['user_id'] ?></td>
                        <td><?= htmlspecialchars($item['user_full_name']) ?></td>
                        <td><?= htmlspecialchars($item['user_email']) ?></td>
                        <td><?= htmlspecialchars($item['workshop_title']) ?></td>
                        <td>
                            <?= htmlspecialchars($item['start_date']) ?>
                            <?php if (!empty($item['start_time'])): ?>
                                <?= ' ' . htmlspecialchars(substr($item['start_time'], 0, 5)) ?>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($item['price']) ?></td>
                        <td><?= htmlspecialchars($item['quantity']) ?></td>
                        <td><?= htmlspecialchars($item['subtotal']) ?></td>
                        <td>
                            <a class="button delete"
                               href="admin_workshops.php?delete_item=<?= (int)$item['id'] ?>"
                               onclick="return confirm('Delete this order item?');">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>

    </section>
</main>
<footer>
<?php  require_once __DIR__ . '/../shared/footer.php'; ?>
</footer>
</body>
</html>

