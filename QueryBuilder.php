<?php
    abstract class DBWrapper {
        protected $pdo;
        protected $query;
        protected $table;
        protected $conditions = [];
        protected $fields = '*';
        protected $limit;
        protected $orderBy;

        public function __construct($config) {
            $this->connect($config);
        }
        
        abstract protected function connect($config);

        public function table($table) {
            $this->table = $table;
            return $this;
        }

        public function insert($data) {
            $this->fields = implode(", ", array_keys($data));
            $placeholder = implode(", ", array_fill(0, count($data), "?"));
            $this->values = array_values($data);
            $this->query = "INSERT INTO {$this->table} ({$this->fields}) VALUES ({$placeholders})";
            return $this;
        }

        public function select($fields = "*") {
            $this->fields = is_array($fields) ? implode(", ", $fields) : $fields;
            $this->query = "SELECT {$this->fields} FROM {$this->table}";
            return $this;
        }

        public function where($field, $operator, $value) {
            $this->conditions[] = "{$field} {$operator} ?";
            $this->values[] = $value;
            return $this;
        }

        public function update($data) {
            $this->values = array_values($data);
            $set = implode(", ", array_map(fn($k) => "{$k} = ?", array_keys($data)));
            $this->query = "UPDATE {$this->table} SET {$set}";
            return $this;
        }

        public function delete() {
            $this->query = "DELETE FROM {$this->table}";
            return $this;
        }

        public function limit($limit) {
            $this->limit = $limit;
            return $this;
        }
    
        public function orderBy($field, $direction = 'ASC') {
            $this->orderBy = "{$field} {$direction}";
            return $this;
        }
        
        protected function buildQuery() {
            if ($this->conditions) {
                $this->query .= " WHERE " . implode(" AND ", $this->conditions);
            }
            if ($this->orderBy) {
                $this->query .= " ORDER BY " . $this->orderBy;
            }
            if ($this->limit) {
                $this->query .= " LIMIT " . $this->limit;
            }
        }
    
        public function execute() {
            $this->buildQuery();
            $stmt = $this->pdo->prepare($this->query);
            $stmt->execute($this->values);
            return $stmt;
        }
    }
?>
