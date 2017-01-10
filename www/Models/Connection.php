<?php class Connection{
	protected $Conn;
	
	function __construct(){
		try{
			$this->Conn = new PDO("mysql:host=localhost;dbname={$GLOBALS['Secrets']['DBName']}", "{$GLOBALS['Secrets']['DBUsername']}", "{$GLOBALS['Secrets']['DBPassword']}");
			$this->Conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e){
			echo "Connection Failed: ". $e->getMessage();
		}
	}
	protected function Qurry($Qurry, $Input){
		$stmt = $this->Conn->prepare($Qurry);
		$stmt->execute([$Input]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
}?>