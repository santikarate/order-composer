<?php 

class Orders
{
    private $host;
    private $dbname;
    private $user;
    private $password;

    public function __construct()
    {
        $this->host = 'localhost';
        $this->dbname = 'icb0006_uf4_pr01';
        $this->user = 'root';
        $this->password = '';
    }
    private function connect()
    {
        mysqli_report(MYSQLI_REPORT_STRICT);
        try {
            $conn = new mysqli($this->host, $this->user, $this->password, $this->dbname);
            return $conn;
        } catch (mysqli_sql_exception $e) {
            throw $e;
        }
    }
    public function getOrders()
    {
        if (isset($_GET['domain'])) {
            $domain = $_GET['domain'];
            if ($domain == "yahoo" || $domain == "hotmail" || $domain == "gmail") {
                $result = $this->connect()->query('SELECT * FROM orders Where company like "%' . $domain . '%" ;');
                $array['register'] = array();
                if ($result->num_rows != 0) {
                    while ($row = $result->fetch_assoc()) {
                        $register = array(
                            'id_order' => $row['id_order'],
                            'date' => $row['date'],
                            'company' => $row['company'],
                            'qty' => $row['qty'],
                        );
                        array_push($array['register'], $register);
                    }
                }
                return json_encode($array, JSON_FORCE_OBJECT);
            }
            return json_encode(['Missatge' => 'No hi ha dades'], JSON_FORCE_OBJECT);
        } elseif (isset($_GET['date'])) {
            $date = $_GET['date'];
            $format = 'd-m-Y';
            $d = DateTime::createFromFormat($format, $date);
            if ($d && $d->format($format) === $date) {
                $result = $this->connect()->query('SELECT * FROM orders Where date > ' . $date);
                $array['register'] = array();
                if ($result->num_rows != 0) {
                    while ($row = $result->fetch_assoc()) {
                        $register = array(
                            'id_order' => $row['id_order'],
                            'date' => $row['date'],
                            'company' => $row['company'],
                            'qty' => $row['qty'],
                        );
                        array_push($array['register'], $register);
                    }
                }
                return json_encode($array, JSON_FORCE_OBJECT);
            } else {
                return json_encode(['Missatge' => 'No hi ha dades'], JSON_FORCE_OBJECT);
            }
        } else {
            $result = $this->connect()->query('SELECT * FROM orders');
            $array['register'] = array();
            if ($result->num_rows != 0) {
                while ($row = $result->fetch_assoc()) {
                    $register = array(
                        'id_order' => $row['id_order'],
                        'date' => $row['date'],
                        'company' => $row['company'],
                        'qty' => $row['qty'],
                    );
                    array_push($array['register'], $register);
                }
            }
            return json_encode($array, JSON_FORCE_OBJECT);
        }
    }
}
