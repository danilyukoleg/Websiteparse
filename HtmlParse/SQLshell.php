<?php

namespace HtmlParse;

class SQLshell
{
    private $db;
    private $insertId;
    public function __construct(string $host, string $userName, string $password, string $database)
    {
        $this->db = mysqli_connect($host, $userName, $password, $database);
    }

    public function getInsertId(): int
    {
        return $this->insertId;
    }

    public function preparedExpression(string $sql, array $data = [], bool $flag = false): mixed
    {
        $stmt = mysqli_prepare($this->db, $sql);

        if ($stmt === false) {
            $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($this->db);
            die($errorMsg);
        }

        if ($data) {
            $types = '';
            $stmt_data = [];

            foreach ($data as $key => $value) {
                $type = 's';

                if (is_int($value)) {
                    $type = 'i';
                }
                else if (is_double($value)) {
                    $type = 'd';
                }

                if ($type) {
                    $types .= $type;
                    $stmt_data[] = $value;
                }
            }

            $values = array_merge([$stmt, $types], $stmt_data);
            mysqli_stmt_bind_param(...$values);

            if (mysqli_errno($this->db) > 0) {
                $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($this->db);
                die($errorMsg);
            }
        }
        mysqli_stmt_execute($stmt);
        $this->insertId = mysqli_insert_id($this->db);

        if ($flag) {
          return  $this->getAssoc($stmt);
        }

        return $stmt;
    }


    private function getAssoc($data): array|null
    {
        $res = mysqli_stmt_get_result($data);
        $rows = mysqli_num_rows($res);

        if ($rows === 1) {
           return mysqli_fetch_assoc($res);
        } else {
           return mysqli_fetch_all($res, MYSQLI_ASSOC);
     }
   }

}