<?php
class datos{
    public $idusuario;
    public $idfinca;
    public $idequipo;
    public $idfincaparcela;
    public $idparcelaelemento;
    public $tipo;

    private $tabla_datos="datos";
    private $tabla_fincaP="fincaparcela";
    private $tabla_parcelaE="parcelaelemento";
    private $conn;

    public function __construct($db){
        $this->conn=$db;
    }

    function getFincas(){
        $query="SELECT 	fincas.id, fincas.nombre, fincas.direccion, fincas.telefono FROM fincaparcela
		INNER JOIN fincas ON
       	fincas.id=fincaparcela.idfinca
        INNER JOIN parcelaelemento WHERE
        fincaparcela.id=parcelaelemento.idfincaparcela
        AND parcelaelemento.idelemento=:idusuario
        AND parcelaelemento.tipoelemento=1 GROUP BY fincaparcela.idfinca";

        $stmt=$this->conn->prepare($query);
        $this->idusuario=htmlspecialchars(strip_tags($this->idusuario));
        $stmt->bindParam(":idusuario", $this->idusuario);
        $stmt->execute();
        return $stmt;
    }

    function getParcelas(){
        $query="SELECT fincaparcela.id, parcelas.nombre, parcelas.tipo,fincas.nombre as finca, fincaparcela.nombre as nick FROM fincaparcela
        INNER JOIN parcelaelemento ON
        fincaparcela.id=parcelaelemento.idfincaparcela
       	AND fincaparcela.idfinca=:idfinca
        AND parcelaelemento.idelemento=:idusuario
        AND parcelaelemento.tipoelemento=1
        INNER JOIN parcelas ON
        parcelas.id=fincaparcela.idparcela
        INNER JOIN fincas ON
        fincas.id=fincaparcela.idfinca";
        $stmt=$this->conn->prepare($query);
        $this->idusuario=htmlspecialchars(strip_tags($this->idusuario));
        $this->idfinca=htmlspecialchars(strip_tags($this->idfinca));
        $stmt->bindParam(":idfinca", $this->idfinca);
        $stmt->bindParam(":idusuario", $this->idusuario);
        $stmt->execute();
        return $stmt;
    }

    function getEquipos(){
        $query="SELECT parcelaelemento.id,fincas.nombre as finca, parcelas.nombre as parcela, equipos.nombre as equipo, equipos.devicetype, equipos.descripcion, parcelaelemento.estado
        FROM parcelaelemento
        INNER JOIN equipos ON
        parcelaelemento.idelemento=equipos.id
        AND parcelaelemento.idfincaparcela=:idfincaparcela
        AND parcelaelemento.tipoelemento=0
        INNER JOIN fincaparcela ON
        fincaparcela.id=parcelaelemento.idfincaparcela
        INNER JOIN fincas ON
        fincas.id=fincaparcela.idfinca
        INNER JOIN parcelas ON
        parcelas.id=fincaparcela.idparcela";
        $stmt=$this->conn->prepare($query);
        $this->idfincaparcela=htmlspecialchars(strip_tags($this->idfincaparcela));
        $stmt->bindParam(":idfincaparcela", $this->idfincaparcela);
        $stmt->execute();
        return $stmt;
    }

    function getVariables(){
        $query="SELECT idelemento, tipo FROM datos WHERE idelemento=:idequipo  GROUP BY tipo";
        $stmt=$this->conn->prepare($query);
        $this->idequipo=htmlspecialchars(strip_tags($this->idequipo));
        $stmt->bindParam(":idequipo", $this->idequipo);
        $stmt->execute();
        return $stmt;
    }

    function getData(){
        $query="SELECT dato, horafecha FROM datos WHERE idelemento=:idequipo AND tipo=:tipo";
        $stmt=$this->conn->prepare($query);
        $this->idequipo=htmlspecialchars(strip_tags($this->idequipo));
        $this->tipo=htmlspecialchars(strip_tags($this->tipo));
        $stmt->bindParam(":idequipo", $this->idequipo);
        $stmt->bindParam(":tipo", $this->tipo);
        $stmt->execute();
        return $stmt;
    }


}
?>