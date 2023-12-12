<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: logowanie.php');
    exit();
}

    if (isset($_POST['dodajWycieczke'])) {
        $zdjecia_id = $_POST['zdjecia_id'];
        $dataWyjazdu = $_POST['dataWyjazdu'];
        $cel = $_POST['cel'];
        $cena = $_POST['cena'];
        $dostepna = $_POST['dostepna'];

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "database";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Błąd połączenia z bazą danych: " . $conn->connect_error);
        }

        $sql = "INSERT INTO wycieczki (zdjecia_id, dataWyjazdu, cel, cena, dostepna) 
                VALUES ('$zdjecia_id', '$dataWyjazdu', '$cel', '$cena', '$dostepna')";

        if ($conn->query($sql) === TRUE) {
            echo "Nowa wycieczka została dodana pomyślnie.";
        } else {
            echo "Błąd podczas dodawania wycieczki: " . $conn->error;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['edit'])) {
                $id_to_edit = $_POST['entry_id'];
                header("Location: EdytowanieWycieczki.php?id=$id_to_edit");
                exit();
            }
        }

        $conn->close();
    }
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Administratora</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h1 class="mb-4">Panel Administratora</h1>
    <form method="post">
      <div class="mb-3">
        <label for="zdjecia_id" class="form-label">ID Zdjęcia</label>
        <input type="text" class="form-control" id="zdjecia_id" name="zdjecia_id">
      </div>
      <div class="mb-3">
        <label for="dataWyjazdu" class="form-label">Data Wyjazdu</label>
        <input type="date" class="form-control" id="dataWyjazdu" name="dataWyjazdu">
      </div>
      <div class="mb-3">
        <label for="cel" class="form-label">Miejsce</label>
        <input type="text" class="form-control" id="cel" name="cel">
      </div>
      <div class="mb-3">
        <label for="cena" class="form-label">Cena</label>
        <input type="number" class="form-control" id="cena" name="cena">
      </div>
      <div class="mb-3">
        <label for="dostepna" class="form-label">Dostępna</label>
        <select class="form-select" id="dostepna" name="dostepna">
          <option value="Tak">Tak</option>
          <option value="Nie">Nie</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary" name="dodajWycieczke">Dodaj nową wycieczkę</button><a style="\margin-left:10px;" href="Bazadanych.php" class="btn btn-danger">Powrót</a>
    </form>
  </div>
</body>
</html>