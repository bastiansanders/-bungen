<!DOCTYPE html>
<html lang="de" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Login Page</title>
    <link rel="stylesheet" href="css/main.css">
  </head>
  <body>
    <?php
    if(isset($_POST["submit"])){
      require("mysql.php");
      $stmt = $mysql->prepare("SELECT * FROM accounts WHERE USERNAME = :user"); //Username überprüfen
      $stmt->bindParam(":user", $_POST["username"]);
      $stmt->execute();
      $count = $stmt->rowCount();
      if($count == 1){
        //Username ist frei
        $row = $stmt->fetch();
        if(password_verify($_POST["pw"], $row["PASSWORD"])){
          function updateLogin($username){
            require("mysql.php");
            $stmt = $mysql->prepare("UPDATE accounts SET LASTLOGIN = :value WHERE USERNAME = :user");
            $stmt->bindParam(":user", $username, PDO::PARAM_STR);
            $now = time();
            $stmt->bindParam(":value", $now, PDO::PARAM_STR);
            $stmt->execute();
          }
          if ($row["SERVERRANK"] != -1) {
            session_start();
            $_SESSION["username"] = $row["USERNAME"];
            header("Location: geheim.php");
          } else {
            echo "Du wurdest gebannt.";
          }
        } else {
          echo "Etwas ist schiefgelaufen. Überprüfe dein Nutzername und Passwort";
        }
      } else {
        echo "Etwas ist schiefgelaufen. Überprüfe dein Nutzername und Passwort";
      }
    }
     ?>
     <div class="" id="frm">
       <h1>Anmelden</h1>
       <form action="index.php" method="post">
         <input type="text" name="username" placeholder="Nutzername" id="input" required><br>
         <input type="password" name="pw" placeholder="Passwort" id="input" required><br>
         <button type="submit" name="submit" id="btn">Einloggen</button>
       </form>
       <br>
       <div class="txt">
         <p>Willst du dir ein Konto erstellen? </p><a href="register.php"><p>Registrieren</p></a>
         <a href="passwordreset.php"><p>Hast du dein Passwort vergessen?</p></a>
       </div>
    </div>
  </body>
</html>
