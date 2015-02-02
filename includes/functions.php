<?php
include_once 'config.php';
 
function sec_session_start() {
    $session_name = 'sec_session_id';   // vergib einen Sessionnamen
    $secure = SECURE;
    // Damit wird verhindert, dass JavaScript auf die session id zugreifen kann.
    $httponly = true;
    // Zwingt die Sessions nur Cookies zu benutzen.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    // Holt Cookie-Parameter.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"], 
        $cookieParams["domain"], 
        $secure,
        $httponly);
    // Setzt den Session-Name zu oben angegebenem.
    session_name($session_name);
    session_start();            // Startet die PHP-Sitzung 
    session_regenerate_id();    // Erneuert die Session, löscht die alte. 
}

function login($email, $password, $mysqli) {
    // Das Benutzen vorbereiteter Statements verhindert SQL-Injektion.
    if ($stmt = $mysqli->prepare("SELECT id, username, password, salt 
        FROM members
       WHERE email = ?
        LIMIT 1")) {
        $stmt->bind_param('s', $email);  // Bind "$email" to parameter.
        $stmt->execute();    // Führe die vorbereitete Anfrage aus.
        $stmt->store_result();
 
        // hole Variablen von result.
        $stmt->bind_result($user_id, $username, $db_password, $salt);
        $stmt->fetch();
 
        // hash das Passwort mit dem eindeutigen salt.
        $password = hash('sha512', $password . $salt);
        if ($stmt->num_rows == 1) {
            // Wenn es den Benutzer gibt, dann wird überprüft ob das Konto
            // blockiert ist durch zu viele Login-Versuche 
 
            if (checkbrute($user_id, $mysqli) == true) {
                // Konto ist blockiert 
                // Schicke E-Mail an Benutzer, dass Konto blockiert ist
                return false;
            } else {
                // Überprüfe, ob das Passwort in der Datenbank mit dem vom
                // Benutzer angegebenen übereinstimmt.
                if ($db_password == $password) {
                    // Passwort ist korrekt!
                    // Hole den user-agent string des Benutzers.
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
                    // XSS-Schutz, denn eventuell wir der Wert gedruckt
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION['user_id'] = $user_id;
                    // XSS-Schutz, denn eventuell wir der Wert gedruckt
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", 
                                                                "", 
                                                                $username);
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512', 
                              $password . $user_browser);
                    // Login erfolgreich.
                    return true;
                } else {
                    // Passwort ist nicht korrekt
                    // Der Versuch wird in der Datenbank gespeichert
                    $now = time();
                    $mysqli->query("INSERT INTO login_attempts(user_id, time)
                                    VALUES ('$user_id', '$now')");
                    return false;
                }
            }
        } else {
            //Es gibt keinen Benutzer.
            return false;
        }
    }
}