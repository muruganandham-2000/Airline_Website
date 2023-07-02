<?php
class db_connection {
  private $host = "localhost";
  private $username = "root";
  private $password = "Muruga@2000";
  private $database = "pydatabase";
  private $conn;

  public function __construct() {
    $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->database);

    if (!$this->conn) {
      die("Connection failed: " . mysqli_connect_error());
    }
  }

  public function query($sql) {
    return mysqli_query($this->conn, $sql);
  }

  public function close() {
    mysqli_close($this->conn);
  }
}
?>