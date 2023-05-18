<?php include "template.php"; /** @var $conn */
$page = $_SERVER['PHP_SELF'];

?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Cyber City - Flag Maker</title>
    </head>
    <body>
    <h1 class='text-primary'>Flag Maker</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <div class="col-md-12">
            <p>Enter the flag below to make it</p>
            <p>Flag<input type="text" name="flag" class="form-control" required="required"></p>
            <p>Points Given<input type="text" name="pointsValue" class="form-control" required="required"></p>
        </div>
        <input type="submit" name="formSubmit" value="Make">
    </form >
    </body>
    </html>

<?php


//if (isset($_POST['login'])) {
$accessLevel == 2;
if ($_SESSION["access_level"] == $accessLevel) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $flag = sanitise_data($_POST['flag']);
        $hashed_flag = password_hash($flag, PASSWORD_DEFAULT);
        $points = sanitise_data($_POST['pointsValue']);
        $isDupe = false;
        $flagList = $conn->query("SELECT HashedFlag FROM Flags");
        while ($flagData = $flagList->fetch()) {
            if (password_verify($flag, $flagData[0])) {
                echo "Flag Already exist";
                $isDupe = true;
            }
        }
        if (!$isDupe) {
            $sql = "INSERT INTO Flags (HashedFlag, PointsValue) VALUES (:newFlag, :newPoints)";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':newFlag', $hashed_flag);
            $stmt->bindValue(':newPoints', $points);
            $stmt->execute();
            echo "Flag Made";
        }
    }
} else {
    header("Location:index.php");
    $_SESSION["flash_message"] = "<div class='bg-success'>Not authorised to access this page</div>";
}



?>
