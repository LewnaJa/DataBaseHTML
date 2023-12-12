<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: logowanie.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['save'])) {
        $id_to_edit = $_POST['entry_id'];
        $new_image_id = $_POST['image_id'];
        $new_departure_date = $_POST['departure_date'];
        $new_destination = $_POST['destination'];
        $new_price = $_POST['price'];
        $new_available = $_POST['available'];

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "database";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Błąd połączenia z bazą danych: " . $conn->connect_error);
        }

        $sql_update = "UPDATE wycieczki SET zdjecia_id='$new_image_id', dataWyjazdu='$new_departure_date', cel='$new_destination', cena='$new_price', dostepna='$new_available' WHERE id='$id_to_edit'";

        if ($conn->query($sql_update) === TRUE) {
            echo "Rekord został zaktualizowany pomyślnie.";
        } else {
            echo "Błąd podczas aktualizacji rekordu: " . $conn->error;
        }
        $conn->close();
    }
}

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_to_edit = $_GET['id'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "database";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }

    $sql_fetch = "SELECT * FROM wycieczki WHERE id = $id_to_edit";
    $result_fetch = $conn->query($sql_fetch);

    if ($result_fetch->num_rows > 0) {
        $row = $result_fetch->fetch_assoc();
        ?>
        <!DOCTYPE html>
        <html lang="pl">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edycja rekordu</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            <div class="container mt-5">
                <h1 class="mb-4">Edycja rekordu</h1>
                <form method="post">
                    <input type='hidden' name='entry_id' value='<?php echo $row['id']; ?>'>
                    <label>ID Zdjęcia:</label>
                    <input type='text' name='image_id' value='<?php echo $row['zdjecia_id']; ?>' class="form-control mb-3"><br>
                    <label>Data Wyjazdu:</label>
                    <input type='text' name='departure_date' value='<?php echo $row['dataWyjazdu']; ?>' class="form-control mb-3"><br>
                    <label>Miejsce:</label>
                    <input type='text' name='destination' value='<?php echo $row['cel']; ?>' class="form-control mb-3"><br>
                    <label>Cena:</label>
                    <input type='text' name='price' value='<?php echo $row['cena']; ?>' class="form-control mb-3"><br>
                    <label>Dostępna:</label>
                    <input type='text' name='available' value='<?php echo $row['dostepna']; ?>' class="form-control mb-3"><br>
                    <button type='submit' class='btn btn-primary' name='save'>Zapisz zmiany</button><a style="margin-left:10px; margin-bottom:15px;" href="Bazadanych.php" class="btn btn-danger mt-3">Powrót</a>
                </form>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "Brak danych do edycji";
    }
    $conn->close();
} else {
    echo "Nieprawidłowe ID rekordu do edycji";
}
?>