<?php

// Composerでインストールしたライブラリを一括読み込み
require_once __DIR__ . '/vendor/autoload.php';
//DBクラスを使うためにindex.phpを読み込む
require_once('index.php');
require_once('MyValidator.php');

//$message　nameのパラーメーターをメッセージとする
$message = htmlspecialchars($_GET["accountNo"]);

$v = new MyValidator();
$v->regexCheck($message,'message','/[0-9]{1,13}/');
$v();

$ids = getUserIds();

foreach($ids as $id){
  // echo gettype($id). '<br>';
//  echo 'id = ' . $id . '<br>';
  if($id == (int)$message) echo '1';
}

if($ids === PDO::PARAM_NULL){
  error_log('There is no id');
}


// ユーザーIDをデータベースから取得
function getUserIds() {

  $dbh = dbConnection::getConnection();
  $sql = 'select id from '. TABLE_NAME_IDS;
  #$sql = 'select pgp_sym_decrypt(userid,\''. getenv('DB_ENCRYPT_PASS') .'\') from '. TABLE_NAME_IDS;
  $sth = $dbh->prepare($sql);
  $sth->execute();

  // レコードが存在しなければNULL
  if (!($ids=$sth->fetchAll(PDO::FETCH_COLUMN, 0))) {
    return PDO::PARAM_NULL;
  }

  return $ids;
}
