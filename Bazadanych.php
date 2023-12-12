<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: logowanie.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete'])) {
        $id_to_delete = $_POST['entry_id'];
        
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "database";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Błąd połączenia z bazą danych: " . $conn->connect_error);
        }

        $sql_delete = "DELETE FROM wycieczki WHERE id = $id_to_delete";

        if ($conn->query($sql_delete) === TRUE) {
            echo "Rekord został usunięty pomyślnie.";
        } else {
            echo "Błąd podczas usuwania rekordu: " . $conn->error;
        }
        $conn->close();

    }

    if (isset($_POST['Edytuj'])) {
        $id_to_edit = $_POST['entry_id'];
        header("Location: EdytowanieWycieczki.php?id=$id_to_edit");
        exit();
    }

    if (isset($_POST['deleteAll'])) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "database";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Błąd połączenia z bazą danych: " . $conn->connect_error);
        }

        $sql_delete_all = "DELETE FROM wycieczki";

        if ($conn->query($sql_delete_all) === TRUE) {
            echo "Wszystkie rekordy zostały usunięte pomyślnie.";
        } else {
            echo "Błąd podczas usuwania rekordów: " . $conn->error;
        }
        $conn->close();
    }
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
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Zdjęcia</th>
          <th>Data Wyjazdu</th>
          <th>Miejsce</th>
          <th>Cena</th>
          <th>Dostępna</th>
          <th>Akcje</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $servername = "localhost";
          $username = "root";
          $password = "";
          $dbname = "database";

          $conn = new mysqli($servername, $username, $password, $dbname);

          if ($conn->connect_error) {
              die("Błąd połączenia z bazą danych: " . $conn->connect_error);
          }

          $sql = "SELECT * FROM wycieczki";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . $row['id'] . "</td>";
                  
                  $zdjecie_id = $row['zdjecia_id'];
                  $sql_zdjecie = "SELECT * FROM zdjecia WHERE id = $zdjecie_id";
                  $result_zdjecie = $conn->query($sql_zdjecie);

                  if ($result_zdjecie->num_rows > 0) {
                      $zdjecie = $result_zdjecie->fetch_assoc();
                      $url = $zdjecie['nazwaPliku']; 

                      $rozszerzenie = pathinfo($url, PATHINFO_EXTENSION);
                      if (in_array($rozszerzenie, ['png', 'jpeg'])) {
                          echo "<td><img src='" . $url . "' alt='Zdjęcie' style='max-width: 100px; max-height: 100px;'></td>";
                      } else {
                          echo "<td>Niepoprawne rozszerzenie</td>";
                      }
                  } else {
                      echo "<td>Brak zdjęcia</td>";
                  }
                  
                  // Kontynuacja reszty danych w wierszu tabeli
                  echo "<td>" . $row['dataWyjazdu'] . "</td>";
                  echo "<td>" . $row['cel'] . "</td>";
                  echo "<td>" . $row['cena'] . "</td>";
                  echo "<td>" . $row['dostepna'] . "</td>";
                  echo "<td>
                          <form method='post'>
                              <input type='hidden' name='entry_id' value='" . $row['id'] . "'>
                              <button type='submit' class='btn btn-danger' name='delete'>Usuń</button>
                              <button type='submit' class='btn btn-warning' name='Edytuj'>Edytuj</button>
                          </form>
                        </td>";
                  echo "</tr>";
              }
          } else {
              echo "<tr><td colspan='7'>Brak wpisów w bazie danych</td></tr>";
          }
          $conn->close();
        ?>
      </tbody>
    </table>
    <form method="post">
      <div class="row">
        <div class="col-md-4 mb-3">
          <a href="DodawanieWycieczki.php" class="btn btn-primary">Dodaj nową wycieczkę</a>
        </div>
        <div class="col-md-4 mb-3">
          <button type="submit" class="btn btn-danger" name="deleteAll" onclick="return confirm('Czy na pewno chcesz usunąć wszystkie rekordy z bazy danych?')">Usuń dane z bazy</button>
        </div>
        <div class="col-md-4 mb-3">
          <a href="Bazadanych.php" class="btn btn-warning">Edytuj dane</a>
        </div>
      </div>
    </form>
  </div>
  </div>
</body>
</html>

