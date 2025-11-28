<?php
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh; // Database Handler
    private $stmt;
    private $error;

    public function __construct() {
        // Configurar DSN (Data Source Name)
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8';
        
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        // Intentar conectar
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo "Error de conexión: " . $this->error;
            die();
        }
    }

    // Preparar la consulta
    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    // Vincular valores
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    // Ejecutar la consulta
    public function execute() {
        return $this->stmt->execute();
    }

    // Obtener múltiples registros
    public function registers() {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Obtener un solo registro
    public function register() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }
    
    // Obtener cantidad de filas
    public function rowCount() {
        return $this->stmt->rowCount();
    }
}
?>