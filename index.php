<?php
session_start();
$filename = 'url_mappings.txt';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $id = $_POST['id'];
    $password = $_POST['password'];
    if ($id == 'Redtag' && $password == 'Sanket@123') {
        $_SESSION['loggedin'] = true;
        header('Location: index.php');
        exit;
    } else {
        $loginError = 'Invalid ID or password';
    }
}

if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    include 'login_form.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['destinationURL'])) {
    $destinationURL = $_POST['destinationURL'];
    $shortCode = substr(md5(uniqid()), 0, 5); // Generate a random short code
    $shortURL = "http://".$_SERVER['HTTP_HOST']."/".$shortCode;

    // Save the mapping in a text file
    $file = fopen($filename, 'a');
    fwrite($file, "$shortCode,$destinationURL\n");
    fclose($file);

    $newLink = $shortURL;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteCode'])) {
    $deleteCode = $_POST['deleteCode'];
    $fileContents = file($filename, FILE_IGNORE_NEW_LINES);
    $newContents = [];

    foreach ($fileContents as $line) {
        list($storedCode, $storedURL) = explode(',', $line);
        if ($storedCode !== $deleteCode) {
            $newContents[] = $line;
        }
    }

    file_put_contents($filename, implode("\n", $newContents));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple URL Redirector</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 50px;
        }
        input, button {
            margin: 5px;
        }
        table {
            margin-top: 20px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .copybtn {
            background: aquamarine;
            border: 1px solid;
        }
    </style>
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Copied to clipboard');
            }).catch(err => {
                alert('Failed to copy: ', err);
            });
        }
    </script>
</head>
<body>
    <h1>URL Redirector</h1>
    <form method="POST" action="index.php">
        <input type="text" name="destinationURL" placeholder="Enter destination URL" required>
        <button type="submit">Create Redirect Link</button>
    </form>

    <p id="linkOutput">
        <?php if (isset($newLink)) {
            echo "<a href=\"$newLink\" target=\"_blank\">$newLink</a>";
            echo " <button class=\"copybtn\" onclick=\"copyToClipboard('$newLink')\">Copy</button>";
        } ?>
    </p>

    <h2>Saved URLs</h2>
    <table>
        <thead>
            <tr>
                <th>Short URL</th>
                <th>Destination URL</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (file_exists($filename)) {
                $fileContents = file($filename, FILE_IGNORE_NEW_LINES);

                foreach ($fileContents as $line) {
                    list($shortCode, $destinationURL) = explode(',', $line);
                    $shortURL = "http://".$_SERVER['HTTP_HOST']."/".$shortCode;

                    echo "<tr>
                            <td><a href=\"$shortURL\" target=\"_blank\">$shortURL</a> <button class=\"copybtn\" onclick=\"copyToClipboard('$shortURL')\">Copy</button></td>
                            <td>$destinationURL</td>
                            <td>
                                <form method=\"POST\" action=\"index.php\" style=\"display:inline;\">
                                    <input type=\"hidden\" name=\"deleteCode\" value=\"$shortCode\">
                                    <button type=\"submit\" class=\"delete-icon\">
                                        <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"#fa0000\" viewBox=\"0 0 256 256\">
                                            <path d=\"M216,48H180V36A28,28,0,0,0,152,8H104A28,28,0,0,0,76,36V48H40a12,12,0,0,0,0,24h4V208a20,20,0,0,0,20,20H192a20,20,0,0,0,20-20V72h4a12,12,0,0,0,0-24ZM100,36a4,4,0,0,1,4-4h48a4,4,0,0,1,4,4V48H100Zm88,168H68V72H188ZM116,104v64a12,12,0,0,1-24,0V104a12,12,0,0,1,24,0Zm48,0v64a12,12,0,0,1-24,0V104a12,12,0,0,1,24,0Z\"></path>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>";
                }
            }
            ?>
        </tbody>
    </table>
</body>
</html>
