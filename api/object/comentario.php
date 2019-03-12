<?php

class comentario{

    private $conn;

    public $comentario;
    public $usuario;
    public $idparcela;
    public $time;

    public function __construct($db){
        $this->conn=$db;
    }

    public function create(){
        $query="INSERT INTO comentarios SET idparcela=:idparcela, comentario=:comentario, usuario=:usuario, fecha=:fecha";
        $stmt=$this->conn->prepare($query);
        $this->comentario=htmlspecialchars(strip_tags($this->comentario));
        $this->idparcela=htmlspecialchars(strip_tags($this->idparcela));
        $this->usuario=htmlspecialchars(strip_tags($this->usuario));
        $this->time=htmlspecialchars(strip_tags($this->time));
        $stmt->bindParam(":idparcela", $this->idparcela);
        $stmt->bindParam(":comentario", $this->comentario);
        $stmt->bindParam(":usuario", $this->usuario);
        $stmt->bindParam(":fecha", $this->time);
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function read_pag($numDatos, $pag){
        $total_row=$this->count();
        $posicion=$pag*$numDatos;
        $posicion=$total_row-$posicion;
        if($posicion<0){
            $numDatos=$posicion+$numDatos;
            $this->prueva=$numDatos;
            $posicion=0;
        }
        $query="SELECT * FROM comentarios WHERE idparcela=? LIMIT ?, ?";
        $stmt=$this->conn->prepare($query);
        $this->idparcela=htmlspecialchars(strip_tags($this->idparcela));
        $stmt->bindParam(1, $this->idparcela);
        $stmt->bindParam(2, $posicion, PDO::PARAM_INT);
        $stmt->bindParam(3, $numDatos, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function delete(){
        $query="DELETE FROM comentarios WHERE idparcela=?";
        $stmt=$this->conn->prepare($query);
        $this->idparcela=htmlspecialchars(strip_tags($this->idparcela));
        $stmt->bindParam(1, $this->idparcela);
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function count(){
        $query="SELECT COUNT(*) as total_row FROM comentarios WHERE idparcela=:idparcela";
        $stmt=$this->conn->prepare($query);
        $this->idparcela=htmlspecialchars(strip_tags($this->idparcela));
        $stmt->bindParam("idparcela", $this->idparcela);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_row'];
    }

}

?>