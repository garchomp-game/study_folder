<?php
define('DB_DATABASE', 'dotinstall_db');
define('DB_USERNAME', 'dbuser');
define('DB_PASSWORD', 'hogehoge');
define('PDO_DSN', 'mysql:dbhost=localhost;dbname=' . DB_DATABASE);
CLASS User{
  // public $id;
  // public $name;
  // public $score; 省略可能
  public function show(){
    echo "$this->name($this->score)";
  }
}
try{
  $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


  $stmt = $db->query("delete from users");
  $stmt = $db->query("select * from users");
  $users = $stmt->fetchAll(PDO::FETCH_CLASS, 'User');
  foreach ($users as  $value) {
    # code...
    $value->show();
  }


  // // $stmt = $db->prepare('select score from users where name like ?');
  // // $stmt->execute(["%t%"]);
  //
  // $stmt = $db->prepare('select score from users order by score desc limit ?');
  // $stmt->bindValue(1,1,PDO::PARAM_INT);
  // // $stmt->execute([1]);
  // // $stmt = $db->prepare('select score from users where score > ?');
  // // $stmt->execute([60]);

  $stmt = $db->query('select * from users');
  $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach ($users as $value) {
    # code...
    // var_dump($value);
  }
  // echo $stmt->rowCount() . "\n\nrecords found.\n";
  //
  // $stmt = $db->prepare("insert into users (name, score) values (:name, :score)");
  // $stmt->execute([':name'=>'taguchi',':score'=>80]);

    $stmt = $db->prepare("insert into users (name, score) values (?, ?)");

    $name = 'garchomp';
    $stmt->bindValue(1, $name, PDO::PARAM_STR);
    $stmt->bindParam(2,$score, PDO::PARAM_INT);
    $score = 87;
    $stmt->execute();
    $score = 68;
    $stmt->execute();

    $name = 'otoka';
    $stmt->bindValue(1, $name, PDO::PARAM_STR);
    $score = 23;
    $stmt->bindValue(2,$score, PDO::PARAM_INT);
    $stmt->execute();
    $score = 44;
    $stmt->bindValue(2,$score, PDO::PARAM_INT);
    $stmt->execute();

  echo "inserted: ". $db->lastInsertId();

  $db->exec("insert into users (name,score) values ('taguchi', 55)");
  echo 'user added!';
  //update
  $stmt = $db->prepare("update users set score = :score where name = :name");
  $stmt->execute([
    ':score' => 100,':name' => 'garchomp'
  ]);
  echo 'row update: '. $stmt->rowCount();

  //delete
  $stmt = $db->prepare("delete from users where name = :name");
  $stmt->execute([
    ':name' => 'taguchi'
  ]);
  echo 'row deleted: '. $stmt->rowCount();


  $db->beginTransaction();
  $db->exec("update users set score = score - 10 where name = 'otoka'");
  $db->exec("update users set score = score + 10 where name = 'garchomp'");
  $db->commit();

} catch (PDOException $e) {
  $db->rollback();
  echo $e->getMessage();
  exit;
}
