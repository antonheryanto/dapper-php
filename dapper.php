<?php

class Dapper {
    /**
     *  @var PDO 
     *  new PDO("mysql:host=localhost;dbname=database;", "root", "", [
     *      PDO::ATTR_PERSISTENT => true,
     *      PDO::ATTR_CASE => PDO::CASE_NATURAL,
     *      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
     *      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
     *  ]);
     */
    public $pdo = null;

    /**
     * @param PDO pdo
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;   
    }

    /**
     * @param string sql
     * @param array params
     * @param object fetchOptions
     */
    public function query($sql, $params = NULL, $fetchOptions = NULL) {
      $c = $this->queryReader($sql, $params);
      $v = $c->fetchAll($fetchOptions);
      $c->closeCursor();
      return $v;
    }

    /**
     * execute sql statement
     * @param string sql
     * @param array params
     * @return int row number
     */
    public function execute($sql, $params = NULL) {
        try {
            $q = $this->queryReader($sql, $params);
            $r = $q->rowCount();
            $q->closeCursor();
            return $r;
        } catch (PDOException $ex) {
            $this->debug($ex->getMessage());
        }
        return 0;
    }

    /**
     * execute query return scalar value
     * @param string sql
     * @param array params
     */
    public function executeScalar($sql, $params = NULL) {
        $c = $this->queryReader($sql, $params);
        $v = $c->fetchColumn();
        $c->closeCursor();
        return $v;
    }

    /**
     * raw query, if used multiple time please close cursos upon reuse
     * @param string $sql
     * @param array $params
     * @return PDOStatement
     */
    public function queryReader($sql, $params = NULL) {
        try {
            $q = $this->pdo->prepare($sql);
            $q->execute($params);
            return $q;
        } catch (PDOException $ex) {
            $this->debug($ex->getMessage());
        }
        return NULL;
    }

    protected function debug($msg) {
        echo "<pre>Error!: $msg\n";
        $bt = debug_backtrace();
        foreach ($bt as $line) {
            $args = var_export($line['args'], true);
            echo "{$line['function']}($args) at {$line['file']}:{$line['line']}\n";
        }
        echo "</pre>";
        die();
    }
}
