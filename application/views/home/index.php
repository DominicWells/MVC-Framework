<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body>
    <h1>Welcome</h1>
    <p>Hello <?= htmlspecialchars($name) ?></p>
    <ul>
        <?php foreach ($colours as $colour) {
            echo "<li>" . htmlspecialchars($colour) . "</li>";
}
?>
    </ul>
</body>
</html>