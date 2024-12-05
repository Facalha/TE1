<?php
// Função para conectar ao banco de dados SQLite
function conectarBanco() {
    try {
        $db = new PDO('sqlite:biblialivre.db');  // Caminho para o banco de dados SQLite
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (Exception $e) {
        echo "Erro ao conectar ao banco de dados: " . $e->getMessage() . "\n";
        exit;
    }
}

// Função para criar as tabelas no banco de dados
function criarTabelas($db) {
    $sql = "
    CREATE TABLE IF NOT EXISTS testamentos (
        id INTEGER PRIMARY KEY,
        periodo TEXT,
        nome TEXT,
        abrev TEXT
    );

    CREATE TABLE IF NOT EXISTS capitulos (
        id INTEGER PRIMARY KEY,
        testamento_id INTEGER,
        numero INTEGER,
        texto TEXT,
        FOREIGN KEY (testamento_id) REFERENCES testamentos(id)
    );

    CREATE INDEX IF NOT EXISTS idx_testamento_id ON capitulos(testamento_id);
    ";
    
    $db->exec($sql);
}

// Função para verificar se o testamento já existe
function testamentoExiste($db, $id) {
    $stmt = $db->prepare("SELECT COUNT(*) FROM testamentos WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count > 0;
}

// Função para inserir os testamentos e capítulos no banco de dados
function inserirTestamentos($db, $dados) {
    foreach ($dados as $testamento) {
        // Verificar se o testamento já existe no banco de dados
        if (testamentoExiste($db, $testamento['id'])) {
            echo "Testamento '{$testamento['nome']}' já existe no banco de dados. Pulando inserção.\n";
            continue; // Pular este testamento se já existir
        }

        // Inserir o testamento
        $stmt = $db->prepare("INSERT INTO testamentos (id, periodo, nome, abrev) VALUES (:id, :periodo, :nome, :abrev)");
        $stmt->bindParam(':id', $testamento['id']);
        $stmt->bindParam(':periodo', $testamento['periodo']);
        $stmt->bindParam(':nome', $testamento['nome']);
        $stmt->bindParam(':abrev', $testamento['abrev']);
        $stmt->execute();
        
        // Verificar se a chave 'capitulos' existe e é um array
        if (isset($testamento['capitulos']) && is_array($testamento['capitulos'])) {
            // Inserir os capítulos
            $testamento_id = $db->lastInsertId(); // Pega o ID do testamento inserido
            foreach ($testamento['capitulos'] as $numero => $texto) {
                $stmt = $db->prepare("INSERT INTO capitulos (testamento_id, numero, texto) VALUES (:testamento_id, :numero, :texto)");
                $stmt->bindParam(':testamento_id', $testamento_id);
                $numeroCapitulo = $numero + 1; // Capítulos começam do número 1
                $stmt->bindParam(':numero', $numeroCapitulo);
                $stmt->bindParam(':texto', $texto);
                $stmt->execute();
            }
        } else {
            echo "Aviso: Não há capítulos para o testamento '{$testamento['nome']}'\n";
        }
    }
}

// Função principal
function processarBiblia() {
    // Conectar ao banco de dados SQLite
    $db = conectarBanco();

    // Criar as tabelas, se não existirem
    criarTabelas($db);

    // Ler o arquivo biblia.json
    $jsonData = file_get_contents('biblialivre.json');  // Caminho para o seu arquivo JSON
    if ($jsonData === false) {
        echo "Erro ao ler o arquivo JSON.\n";
        exit;
    }

    // Decodificar o conteúdo do JSON
    $dados = json_decode($jsonData, true);
    if ($dados === null) {
        echo "Erro ao decodificar o JSON.\n";
        exit;
    }

    // Inserir os dados no banco de dados
    inserirTestamentos($db, $dados);

    echo "Dados inseridos com sucesso!\n";
}

// Chamar a função principal para processar a Bíblia
processarBiblia();
?>
