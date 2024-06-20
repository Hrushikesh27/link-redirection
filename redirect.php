// <?php
// $filename = 'url_mappings.txt';

// if (isset($_GET['code'])) {
//     $shortCode = $_GET['code'];
//     $fileContents = file($filename, FILE_IGNORE_NEW_LINES);
//     $found = false;

//     foreach ($fileContents as $line) {
//         list($storedCode, $storedURL) = explode(',', $line);
//         if ($storedCode == $shortCode) {
//             $found = true;
//             header("Location: $storedURL");
//             exit;
//         }
//     }

//     if (!$found) {
//         echo "<p>Invalid or expired URL</p>";
//     }
// }
// ?>

<?php
$filename = 'url_mappings.txt';

if (isset($_GET['code'])) {
    $shortCode = $_GET['code'];
    $fileContents = file($filename, FILE_IGNORE_NEW_LINES);
    $found = false;

    foreach ($fileContents as $line) {
        list($storedCode, $storedURL) = explode(',', $line);
        if ($storedCode == $shortCode) {
            $found = true;
            $referrer = 'baseswiki.org';
            $destinationURL = $storedURL . (strpos($storedURL, '?') === false ? '?' : '&') . 'ref=' . urlencode($referrer);
            header("Location: $destinationURL");
            exit;
        }
    }

    if (!$found) {
        echo "<p>Invalid or expired URL</p>";
    }
}
?>
