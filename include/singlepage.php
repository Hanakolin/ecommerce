<?php
// filepath: d:\xampp\htdocs\PHP\e-commerce\include\singlepage.php
include '../include/config.php';

// Get the card ID from the query parameter
$id = $_GET['id'] ?? 0;

// Fetch the card details from the database
$sql = "SELECT * FROM card WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$card = $result->fetch_assoc();

if (!$card) {
    echo "Card not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($card['title']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-10">
        <div class="flex flex-col md:flex-row -mx-4">
            <div class="md:flex-1 px-4">
                <div class="h-[460px] rounded-lg bg-gray-300 mb-4">
                    <img class="w-full h-full object-cover" src="<?php echo htmlspecialchars($card['thumbnail']); ?>" alt="Product Image">
                </div>
            </div>
            <div class="md:flex-1 px-4">
                <h2 class="text-2xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($card['title']); ?></h2>
                <p class="text-gray-600 text-sm mb-4"><?php echo htmlspecialchars($card['subtitle']); ?></p>
                <div>
                    <span class="font-bold text-gray-700">Product Description:</span>
                    <p class="text-gray-600 text-sm mt-2"><?php echo htmlspecialchars($card['discription']); ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>