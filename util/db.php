<?php
class Database {
	private $conn;
	private $host;
	private $port;
	private $socket;
	private $user;
	private $password;
	private $dbname;

	public function openDB()
	{
		$this->host="";
		$this->port=0;
		$this->socket="";
		$this->user="";
		$this->password="";
		$this->dbname="";

		$pdo_conn = null;
		$conn = new mysqli($this->host, $this->user, $this->password, $this->dbname, $this->port, $this->socket)
		or die ('Could not connect to the database server' . mysqli_connect_error());
		try
		{
			$pdo_conn = new PDO('mysql:host=localhost;dbname=' . $this->dbname, $this->user, $this->password);
			$pdo_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e)
		{
			echo 'ERROR: ' . $e->getMessage();
		}
		return $pdo_conn;
	}

	public function closeDB()
	{
		if ($this->conn != null)
			$this->conn->close();
	}
}
?>
