<?php
include "config_scanealo.php";
include "utils_scanealo.php";

$dbConn =  connect($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (isset($_GET['adeNumero']))
    {
      //Mostrar un post
      $sql = $dbConn->prepare("SELECT * from invAjusteDet where adeNumero=:adeNumero");
      $sql->bindValue(':adeNumero', $_GET['adeNumero']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
      exit();
	  }
 else {
      //Mostrar lista de post
      $sql = $dbConn->prepare("SELECT * FROM invAjusteDet`");
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      header("HTTP/1.1 200 OK");
      echo json_encode( $sql->fetchAll()  );
      exit();
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $input = $_POST;
    $sql = "INSERT INTO invAjusteDet
          (adeproducto, adeExistencia, adeCantReal, adePrecio,ajuNumero)
          VALUES
          (:adeproducto, :adeExistencia, :adeCantReal, :adePrecio,:ajuNumero)";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();

    $postCodigo = $dbConn->lastInsertId();
    if($postCodigo)
    {
      $input['adeNumero'] = $postCodigo;
      header("HTTP/1.1 200 OK");
      echo json_encode($input);
      exit();
	 }
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
	$codigo = $_GET['adeNumero'];
  $statement = $dbConn->prepare("DELETE FROM  invAjusteDet where adeNumero=:adeNumero");
  $statement->bindValue(':adeNumero', $codigo);
  $statement->execute();
	header("HTTP/1.1 200 OK");
	exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
    $postCodigo = $input['adeNumero'];
    $fields = getParams($input);

    $sql = "
          UPDATE invAjusteDet
          SET $fields
          WHERE adeNumero='$postCodigo'
           ";

    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);

    $statement->execute();
    header("HTTP/1.1 200 OK");
    exit();
}

//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");

?>
