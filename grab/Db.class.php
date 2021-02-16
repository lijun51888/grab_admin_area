<?php
/**
 * 数据库操作
 */
class Db
{
  public $dbh;
  private $db_type = 'mysql';
  private $db_host = 'localhost';
  private $db_port = 3306;
  private $db_user = 'root';
  private $db_pass = 'root';
  private $db_name = 'test';
  private $db_charset = 'utf8';

  function __construct($option = [])
  {
    if (isset($option['db_type']))
      $this->db_type = $option['db_type'];
    if (isset($option['db_host']))
      $this->db_host = $option['db_host'];
    if (isset($option['db_port']))
      $this->db_port = $option['db_port'];
    if (isset($option['db_user']))
      $this->db_user = $option['db_user'];
    if (isset($option['db_pass']))
      $this->db_pass = $option['db_pass'];
    if (isset($option['db_name']))
      $this->db_name = $option['db_name'];
    if (isset($option['db_charset']))
      $this->db_charset = $option['db_charset'];
    try {
      $this->dbh = new PDO("{$this->db_type}:host={$this->db_host};dbname={$this->db_name};port={$this->db_port};charset={$this->db_charset}", $this->db_user, $this->db_pass);
    } catch (PDOException $e) {
      exit('error: '. $e->getMessage());
    }
  }

  /**
  * 执行一条查询语句
  * @param sql string sql语句
  * @return array 返回一个二维数据
  */
  public function query($sql) {
    $query = $this->dbh->query($sql);
    $query->setFetchMode(PDO::FETCH_ASSOC);
    $result = $query->fetchAll();
    // $this->dbh = null;
    return $result;
  }

  /**
  * 执行一条增删改sql语句
  * @param sql string sql语句
  * @return int 受影响的行数，如果执行发生错误，返回false
  */
  public function execSql($sql) {
    $count = $this->dbh->exec($sql);
    // $this->dbh = null;
    return $count;
  }

  public function queryOne($sql) {
    $query = $this->dbh->query($sql);
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result;
  }

  public function queryCol($sql) {
    $query = $this->dbh->query($sql);
    $result = $query->fetchColumn();
    return $result;
  }

  /**
  * 开启事务
  * @return boolean true为开启
  */
  public function strartTran() {
    return $this->dbh->beginTransaction();
  }

  /**
  * 提交事务
  * @return boolean
  */
  public function commitTran() {
    return $this->dbh->commit();
  }

  /**
  * 回滚事务
  * @return boolean
  */
  public function rollBackTran() {
    return $this->dbh->rollBack();
  }

  public function action($actions)
	{
		if (is_callable($actions))
		{
			$this->dbh->beginTransaction();

			$result = $actions($this);
      echo '<br>aa';
      var_dump($result);
			if ($result === false)
			{
        echo 'asdwewew';
				var_dump($this->dbh->rollBack());
			}
			else
			{
        echo '<br> ksksks';
				$this->dbh->commit();
			}
		}
		else
		{
			return false;
		}
	}

}

/*$option = ['db_name' => 'test'];
$dbh = new Db($option);
var_dump($dbh);

$res = $dbh->query('select * from tbl_user');
echo '<pre>';
print_r($res);
echo '</pre>';

$res = $dbh->execSql('delete from tbl_user where id=4');
echo $res;*/

/*$option = ['db_host' => '192.168.9.35', 'db_name' => 'db_address', 'db_user' => 'root', 'db_pass' => 'root'];
$dbh = new Db($option);
var_dump($dbh);

$res = $dbh->query('show tables');
echo '<pre>';
print_r($res);
echo '</pre>';*/

// $dbh = new Db();
/*$is_open = $dbh->dbh->beginTransaction();
var_dump($is_open);
$res1 = $dbh->execSql('insert into tbl_users(login_name, name, password, remark) values ("admin", "admin", md5("admin"))');
$res2 = $dbh->execSql('update tbl_user set remark="666" where id=1');
// $sql1 = $dbh->dbh->prepare('insert into tbl_users(login_name, name, password, remark) values ("admin", "admin", md5("admin"))');
// $sql2 = $dbh->dbh->prepare('update tbl_user set remark="22" where id=1');
// $res1 = $sql1->execute();
// $res2 = $sql2->execute();
if ($res1 === false || $res2 === false) {
  echo 'jinlaile';
  $dbh->dbh->rollBack();
}else {
  echo 'commit';
  $dbh->dbh->commit();
}*/

/*$dbh->strartTran();
$res1 = $dbh->execSql('insert into tbl_users(login_name, name, password, remark) values ("admin", "admin", md5("admin"))');
$res2 = $dbh->execSql('update tbl_user set remark="123" where id=1');
if ($res1 === false || $res2 === false) {
  echo 'bb:', $res2;
  $dbh->rollBackTran();
}else {
  $dbh->commitTran();
}*/

/*$dbh->action(function($db){
  $res1 = $db->execSql('insert into tbl_users(login_name, name, password, remark) values ("admin", "admin", md5("admin"))');
  $res2 = $db->execSql('update tbl_user set remark="123" where id=1');
  var_dump($res1);
  if ($res1 === false || $res2 === false) {
    echo 'bb:', $res2;
    return false;
  }
});*/

/*$db_type = 'mysql';
$db_host = 'localhost';
$db_port = 3306;
$db_user = 'root';
$db_pass = 'root';
$db_name = 'test';
$db_charset = 'utf8';
$pdo = new PDO("$db_type:host=$db_host;port=$db_port;dbname=$db_name;charset=$db_charset", $db_user, $db_pass);
  $pdo->beginTransaction();
  // $res1 = $pdo->exec('insert into tbl_tests(name, info) values ("admin", "admin")');
  // $res2 = $pdo->exec('update tbl_test set info="456" where id=3');
  $res1 = $pdo->exec('insert into tbl_users(login_name, name, password, remark) values ("admin", "admin", md5("admin"))');
  $res2 = $pdo->exec('update tbl_user set remark="123" where id=1');
  var_dump($res1);
  var_dump($res2);
  if ($res1 === false || $res2 === false) {
    var_dump($pdo->rollBack());
    echo 'jinlaimei?';
  }else {
    var_dump($pdo->commit());
  }*/
