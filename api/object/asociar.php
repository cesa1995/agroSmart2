<?php
class asociar{
    public $id;
    public $idfp;
    public $ide;
    public $tipo;
    public $estado;
    public $idfinca;
    public $idparcela;

    private $conn;
    private $fincasP_table = "fincaparcela";
    private $parcelasE_table = "parcelaelemento";

    function __construct($db){
        $this->conn=$db;
    }

    function changeStateElement(){
        $query="UPDATE ".$this->parcelasE_table." SET estado=:estado WHERE id=:id";
        $stmt=$this->conn->prepare($query);
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->estado=htmlspecialchars(strip_tags($this->estado));

        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()){
            return true;
        }

        return false;
    }

    function deleteusuario(){
        $query="DELETE FROM " .$this->parcelasE_table. " WHERE tipoelemento=1 AND ((id=:id) OR (idelemento=:idelemento) OR (idfincaparcela=:idparcela))";
        $stmt=$this->conn->prepare($query);
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->idparcela=htmlspecialchars(strip_tags($this->idparcela));
        $this->ide=htmlspecialchars(strip_tags($this->ide));
        $stmt->bindParam(":id",$this->id);
        $stmt->bindParam(":idelemento",$this->ide);
        $stmt->bindParam(":idparcela", $this->idparcela);
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    function deleteequipo(){
        $query="DELETE FROM " .$this->parcelasE_table. " WHERE tipoelemento=0 AND ((id=:id) OR (idelemento=:idelemento) OR (idfincaparcela=:idparcela))";
        $stmt=$this->conn->prepare($query);
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->idparcela=htmlspecialchars(strip_tags($this->idparcela));
        $this->ide=htmlspecialchars(strip_tags($this->ide));
        $stmt->bindParam(":id",$this->id);
        $stmt->bindParam(":idelemento",$this->ide);
        $stmt->bindParam(":idparcela", $this->idparcela);
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    function deleteparcela(){
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->idfinca=htmlspecialchars(strip_tags($this->idfinca));
        $this->idparcela=htmlspecialchars(strip_tags($this->idparcela));
        $idP=$this->idparcela;
        if(empty($this->id)){
            $query="SELECT * FROM ".$this->fincaP_table." WHERE idparcela=:idparcela OR idfinca=:idfinca";
            $stmt=$this->conn->prepare($query);
            $stmt->bindParam(":idparcela", $this->idparcela);
            $stmt->bindParam(":idfinca", $this->idfinca);
            $stmt->execute();
            while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                $this->idparcela=$row["id"];
                if(!$this->deleteequipo() && !$this->deleteusuario()){
                    return false;
                }
            }
        }
        $query="DELETE FROM ".$this->fincasP_table." WHERE id=:id OR idparcela=:idparcela OR idfinca=:idfinca";
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":idparcela", $idP);
        $stmt->bindParam(":idfinca", $this->idfinca);
        if($stmt->execute()){
            $this->idparcela=$this->id;
            $this->deleteequipo();
            $this->deleteusuario();
            return true;
        }
        return false;
    }

    function readusuarios(){
        $query="SELECT parcelaelemento.id, fincas.nombre as finca, parcelas.nombre as parcela, usuarios.nombre as usuario, usuarios.nivel as nivel, usuarios.email as email, parcelaelemento.estado FROM "
            . $this->parcelasE_table . "
            INNER JOIN usuarios ON
                usuarios.id=parcelaelemento.idelemento AND parcelaelemento.idfincaparcela=:id AND tipoelemento=1
            INNER JOIN fincaparcela ON
                fincaparcela.id=parcelaelemento.idfincaparcela
            INNER JOIN fincas ON
                fincas.id=fincaparcela.idfinca
            INNER JOIN parcelas WHERE
                parcelas.id=fincaparcela.idparcela";
        $stmt=$this->conn->prepare($query);
        $this->fincaid=htmlspecialchars(strip_tags($this->fincaid));
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt;
    }

    function readequipos(){
        $query="SELECT parcelaelemento.id, fincas.nombre as finca, parcelas.nombre as parcela, equipos.nombre as equipo, equipos.devicetype as tipo, equipos.descripcion, parcelaelemento.estado FROM "
        .$this->parcelasE_table."
        INNER JOIN equipos ON
            equipos.id=parcelaelemento.idelemento AND parcelaelemento.idfincaparcela=:id AND tipoelemento=0
        INNER JOIN fincaparcela ON
            fincaparcela.id=parcelaelemento.idfincaparcela
        INNER JOIN fincas ON
            fincas.id=fincaparcela.idfinca
        INNER JOIN parcelas WHERE
            parcelas.id=fincaparcela.idparcela";
        $stmt=$this->conn->prepare($query);
        $this->id=htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt;
    }

    function readparcelas(){
        $query="SELECT fincaparcela.id, parcelas.nombre,fincas.nombre as finca, parcelas.tipo, fincaparcela.nombre as nick FROM "
        .$this->fincasP_table."
        INNER JOIN parcelas ON
            parcelas.id=fincaparcela.idparcela AND fincaparcela.idfinca=:idfinca
        INNER JOIN fincas WHERE
            fincas.id=fincaparcela.idfinca";
        $stmt=$this->conn->prepare($query);
        $this->idfinca=htmlspecialchars(strip_tags($this->idfinca));
        $stmt->bindParam(":idfinca", $this->idfinca);
        $stmt->execute();
        return $stmt;
    }

    function validUsuario(){
        $query="SELECT id, usuarios.nivel FROM ". $this->parcelasE_table. "
            WHERE
                idfincaparcela=:idfp
            AND
                idelemento=:ide
            AND
                tipoelemento=:tipo";
        $stmt=$this->conn->prepare($query);
        $this->idfp=htmlspecialchars(strip_tags($this->idfp));
        $this->ide=htmlspecialchars(strip_tags($this->ide));
        $this->tipo=htmlspecialchars(strip_tags($this->tipo));

        $stmt->bindParam(":idfp", $this->idfp);
        $stmt->bindParam(":ide", $this->ide);
        $stmt->bindParam(":tipo", $this->tipo);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if(!empty($row)){
            return false;
        }
        return true;
    }

    function addParcela(){
        $query="INSERT INTO ".$this->fincasP_table." SET
            idfinca=:idfinca,
            idparcela=:idparcela";
        $stmt=$this->conn->prepare($query);
        $this->idfinca=htmlspecialchars(strip_tags($this->idfinca));
        $this->idparcela=htmlspecialchars(strip_tags($this->idparcela));

        $stmt->bindParam(":idfinca", $this->idfinca);
        $stmt->bindParam(":idparcela", $this->idparcela);

        if($stmt->execute()){
            return true;
        }

        return false;
    }

    function addElemento(){
        $query="INSERT INTO ". $this->parcelasE_table . " SET
            idfincaparcela=:idfincaparcela,
            idelemento=:idelemento,
            tipoelemento=:tipoelemento,
            estado=:estado";
        $stmt=$this->conn->prepare($query);
        $this->idfp=htmlspecialchars(strip_tags($this->idfp));
        $this->ide=htmlspecialchars(strip_tags($this->ide));
        $this->tipo=htmlspecialchars(strip_tags($this->tipo));
        $this->estado=htmlspecialchars(strip_tags($this->estado));

        $stmt->bindParam(":idfincaparcela", $this->idfp);
        $stmt->bindParam(":idelemento", $this->ide);
        $stmt->bindParam(":tipoelemento", $this->tipo);
        $stmt->bindParam(":estado", $this->estado);

        if($stmt->execute()){
            return true;
        }
        return false;
    }
}

?>