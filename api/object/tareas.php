<?php
class tareas{
    private $conn;

    public $id;
    public $idparcela;
    public $tarea;
    public $inicio;
    public $fin;
    public $estado;
    public $now;

    public function __construct($db){
        $this->conn = $db;
    }

    public function create(){
        $query="INSERT INTO tareas SET tarea=:tarea, idparcela=:idparcela, inicio=:inicio, fin=:fin, estado=:estado";
        $stmt=$this->conn->prepare($query);
        $this->tarea=htmlspecialchars(strip_tags($this->tarea));
        $this->inicio=htmlspecialchars(strip_tags($this->inicio));
        $this->fin=htmlspecialchars(strip_tags($this->fin));
        $this->estado=htmlspecialchars(strip_tags($this->estado));
        $this->idparcela=htmlspecialchars(strip_tags($this->idparcela));
        $stmt->bindParam(":tarea", $this->tarea);
        $stmt->bindParam(":idparcela", $this->idparcela);
        $stmt->bindParam(":inicio", $this->inicio);
        $stmt->bindParam(":fin", $this->fin);
        $stmt->bindParam(":estado", $this->estado);
        if($stmt->execute()){
            $this->id=$this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    public function read(){
        $query="UPDATE tareas SET estado=0 WHERE estado=1 AND idparcela=:idparcela AND fin<=:tem;
        UPDATE tareas SET estado=1 WHERE estado=0 AND idparcela=:idparcela AND fin>=:tem;";
        $stmt=$this->conn->prepare($query);
        $this->idparcela=htmlspecialchars(strip_tags($this->idparcela));
        $this->now=htmlspecialchars(strip_tags($this->now));
        $this->estado=htmlspecialchars(strip_tags($this->estado));
        $stmt->bindParam(":idparcela", $this->idparcela);
        $stmt->bindParam(":tem", $this->now);
        $stmt->execute();

        $query="SELECT * FROM tareas WHERE estado=:estado AND idparcela=:idparcela";
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam(":idparcela", $this->idparcela);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->execute();
        return $stmt;
    }

    public function delete(){
        $query="DELETE FROM tareas WHERE id=:id";
        $stmt=$this->conn->prepare($query);
        $this->id=htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function update(){
        $query="UPDATE tareas SET
            tarea=:tarea,
            inicio=:inicio,
            fin=:fin
        WHERE
            id=:id";
        $stmt=$this->conn->prepare($query);
        $this->tarea=htmlspecialchars(strip_tags($this->tarea));
        $this->inicio=htmlspecialchars(strip_tags($this->inicio));
        $this->fin=htmlspecialchars(strip_tags($this->fin));
        $this->id=htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":tarea", $this->tarea);
        $stmt->bindParam(":inicio", $this->inicio);
        $stmt->bindParam(":fin", $this->fin);
        $stmt->bindParam(":id", $this->id);
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function updateState(){
        $query="UPDATE tareas SET estado=:estado WHERE id=:id";
        $stmt=$this->conn->prepare($query);
        $this->estado=htmlspecialchars(strip_tags($this->estado));
        $this->id=htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":id", $this->id);
        if($stmt->execute()){
	    $query="SELECT * FROM tareas WHERE id=:id";
	    $stmt=$this->conn->prepare($query);
	    $stmt->bindParam(":id",$this->id);
	    if($stmt->execute()){
		$row=$stmt->fetch(PDO::FETCH_ASSOC);
		$this->tarea=$row["tarea"];
		$this->inicio=$row["inicio"];
		$this->fin=$row["fin"];
                return true;
	    }
        }
        return false;
    }
}
?>
