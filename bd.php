<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "spotify_pirata";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $conn->real_escape_string(trim($_POST['nome']));
    $artista = $conn->real_escape_string(trim($_POST['artista']));

    // Verificar se os arquivos foram enviados
    if (isset($_FILES['img']) && isset($_FILES['audio'])) {
        // Verificar se ocorreu algum erro no upload
        if ($_FILES['img']['error'] === UPLOAD_ERR_OK && $_FILES['audio']['error'] === UPLOAD_ERR_OK) {
            // Verificar se os arquivos têm o tipo correto
            $imgFileType = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
            $audioFileType = strtolower(pathinfo($_FILES['audio']['name'], PATHINFO_EXTENSION));

            $allowedImageTypes = ['jpg', 'jpeg', 'png', 'gif'];
            $allowedAudioTypes = ['mp3', 'wav', 'ogg'];

            if (in_array($imgFileType, $allowedImageTypes) && in_array($audioFileType, $allowedAudioTypes)) {
                // Carregar os arquivos
                $img = file_get_contents($_FILES['img']['tmp_name']);
                $audio = file_get_contents($_FILES['audio']['tmp_name']);

                // Inserir dados na tabela musica
                $sql = "INSERT INTO musica (nome, artista, img, audio_end) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);

                // Bind com os tipos corretos
                $stmt->bind_param("ssbs", $nome, $artista, $img, $audio); // 'b' para blob (imagem e áudio)

                // Enviar os dados longos
                $stmt->send_long_data(2, $img); // Para a imagem
                $stmt->send_long_data(3, $audio); // Para o áudio

                if ($stmt->execute()) {
                    echo "Novo registro criado com sucesso!";
                } else {
                    echo "Erro: " . $stmt->error; // Exibir erro específico
                }

                $stmt->close();
            } else {
                echo "Erro: Tipo de arquivo inválido.";
            }
        } else {
            // Exibir erros de upload específicos
            if ($_FILES['img']['error'] !== UPLOAD_ERR_OK) {
                echo "Erro ao enviar imagem: " . $_FILES['img']['error'] . "<br>";
            }
            if ($_FILES['audio']['error'] !== UPLOAD_ERR_OK) {
                echo "Erro ao enviar áudio: " . $_FILES['audio']['error'] . "<br>";
            }
        }
    } else {
        echo "Erro: Arquivos não foram enviados.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de Música</title>
</head>
<body>
    <h1>Upload de Música</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="nome">Nome da Música:</label>
        <input type="text" id="nome" name="nome" required><br><br>

        <label for="artista">Artista:</label>
        <input type="text" id="artista" name="artista" required><br><br>

        <label for="img">Imagem da Música:</label>
        <input type="file" id="img" name="img" accept="image/*" required><br><br>

        <label for="audio">Arquivo de Áudio:</label>
        <input type="file" id="audio" name="audio" accept="audio/*" required><br><br>

        <input type="submit" value="Enviar">
    </form>
</body>
</html>
