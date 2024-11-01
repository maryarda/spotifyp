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

// Consultar dados da tabela musica
$sql = "SELECT nome, artista, img, audio_end FROM musica";
$result = $conn->query($sql);

if ($result === false) {
    die("Erro na consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Músicas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .music-item {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px 0;
            display: flex;
            align-items: center;
        }
        .music-item img {
            max-width: 100px;
            margin-right: 20px;
        }
    </style>
</head>
<body>
    <h1>Lista de Músicas</h1>

    <?php
    if ($result->num_rows > 0) {
        // Saída de dados de cada linha
        while ($row = $result->fetch_assoc()) {
            echo '<div class="music-item">';

            // Verifique se a imagem não está vazia
            if (!empty($row['img'])) {
                echo '<img src="data:image/jpeg;base64,' . base64_encode($row['img']) . '" alt="Imagem da Música">';
            } else {
                echo '<img src="placeholder.jpg" alt="Imagem da Música" style="max-width: 100px;">'; // Placeholder se a imagem estiver vazia
            }

            echo '<div>';
            echo '<h2>' . htmlspecialchars($row['nome']) . '</h2>';
            echo '<p>Artista: ' . htmlspecialchars($row['artista']) . '</p>';

            // Verifique se o áudio não está vazio
            if (!empty($row['audio_end'])) {
                echo '<audio controls>';
                echo '<source src="data:audio/mpeg;base64,' . base64_encode($row['audio_end']) . '" type="audio/mpeg">';
                echo 'Seu navegador não suporta o elemento de áudio.';
                echo '</audio>';
            } else {
                echo '<p>Áudio não disponível.</p>'; // Mensagem caso o áudio esteja vazio
            }

            echo '</div>';
            echo '</div>';
        }
    } else {
        echo "<p>Nenhuma música encontrada.</p>";
    }

    $conn->close();
    ?>
</body>
</html>
