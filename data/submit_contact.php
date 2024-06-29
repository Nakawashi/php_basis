<!DOCTYPE html>
<html>


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de Recettes - Page d'accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>


<body class="d-flex flex-column min-vh-100">
    <div class="container">
        <?php require_once(__DIR__ . '/header.php'); ?>
        <?php
            $postData = $_POST;

            if (
                !isset($postData['email'])
                || !filter_var($postData['email'], FILTER_VALIDATE_EMAIL)
                || empty($postData['message'])
                || trim($postData['message']) === ''
            ) {
                echo('Il faut un email et un message valides pour soumettre le formulaire.');
                return;
            }
            
            $isFileLoaded = false;
            if (isset($_FILES['screenshot']) && $_FILES['screenshot']['error'] === 0) {
    
                // Testons, si le fichier est trop volumineux
                if ($_FILES['screenshot']['size'] > 1000000) {
                    echo "L'envoi n'a pas pu être effectué, erreur ou image trop volumineuse";
                    return;
                }
                $fileInfo = pathinfo($_FILES['screenshot']['name']); // récupère un dictionnaire de données
                $extension = $fileInfo['extension']; // dans ce dictionnaire nous voulons la valeur de la clé extension
                $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png', 'txt'];
                if (!in_array($extension, $allowedExtensions)) {
                    echo "L'envoi n'a pas pu être effectué, l'extension {$extension} n'est pas autorisée";
                    return;
                }
                $path = __DIR__ . '/uploads/';
                if (!is_dir($path)) {
                    echo "L'envoi n'a pas pu être effectué, le dossier uploads est manquant";
                    return;
                }
                move_uploaded_file($_FILES['screenshot']['tmp_name'], $path . basename($_FILES['screenshot']['name']));
                $isFileLoaded = true;
                echo "******\n";
                print_r($_FILES);
                echo "******\n";

            }
        ?>
        
        <h1>Message bien reçu !</h1>
                
        <div class="card">
            
            <div class="card-body">
                <h5 class="card-title">Rappel de vos informations</h5>
                <p class="card-text"><b>Email</b> : <<?php echo htmlspecialchars($_POST['email']); ?></p>
                <p class="card-text"><b>Message</b> : <?php echo strip_tags($_POST['message']); ?></p>
                <?php if ($isFileLoaded) : ?>
                    <div class="alert alert-success" role="alert">
                        L'envoi a bien été effectué !
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php require_once(__DIR__ . '/footer.php'); ?>
    </div>
</body>


</html>
