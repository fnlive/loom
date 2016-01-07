<?php
 /**
 * Class for handling user login and authentication..
 *
 * @author Fredrik Nilsson, fn@live.se
 * @copyright Fredrik Nilsson 2010 - 2014
 * @link https://github.com/fnlive/loom
 */
class CUser
{

  /**
   * Properties
   */
  private static $authenticated = false;
  private $acronym = null;
  private $name = null;
  // TODO: make static ? Use as singleton?

  /**
   * Constructor
   *
   */
  function __construct()
  {
    if (isset($_SESSION['user']->acronym)) {
      self::$authenticated = true;
      $this->acronym = $_SESSION['user']->acronym;
      $this->name = $_SESSION['user']->name;
    }
  }

private function ResetDb()
{
    // Sql query to Initialize user db to default values.
    // For now, use to copy to SQL workbench
    $sqlQuery = <<<EOD
    DROP TABLE IF EXISTS USER;

    CREATE TABLE USER
    (
      id INT AUTO_INCREMENT PRIMARY KEY,
      acronym CHAR(12) UNIQUE NOT NULL,
      name VARCHAR(80),
      password CHAR(32),
      salt INT NOT NULL
    ) ENGINE INNODB CHARACTER SET utf8;

    INSERT INTO USER (acronym, name, salt) VALUES
      ('doe', 'John/Jane Doe', unix_timestamp()),
      ('admin', 'Administrator', unix_timestamp())
    ;

    UPDATE USER SET password = md5(concat('doe', salt)) WHERE acronym = 'doe';
    UPDATE USER SET password = md5(concat('admin', salt)) WHERE acronym = 'admin';

    SELECT * FROM USER;
EOD;

}
  /**
   * Login and authenticaate user
   *
   */
  public function Login($user, $password, $db)
  {
    // Check if user and password is okey
    $sql = "SELECT acronym, name FROM USER WHERE acronym = ? AND password = md5(concat(?, salt))";
    // $sql = "SELECT * FROM USER";
    $res = $db->ExecuteSelectQueryAndFetchAll($sql, array($user, $password));
    // If user in database and password matches, set user as authenticated by adding acronym and name to session variable.
    if (isset($res[0])) {
      $_SESSION['user'] = $res[0];
      self::$authenticated = true;
      $this->acronym = $res[0]->acronym;
      $this->name = $res[0]->name;
    }
    header('Location: login.php');
  }

  /**
   * Output html form for login
   * Display if user is logged in or not,
   *
   */
  public function LoginForm()
  {
      $out = "";
      // Check if user is authenticated.
      if($this->IsAuthenticated()) {
          $out .= "<p>Du är inloggad som: {$this->acronym} ({$this->name})</p>";
      }  else {
          $out = <<<EOD
  <form method=post>
    <fieldset>
    <legend>Logga in</legend>
    <p>Logga in med doe:doe eller admin:admin</p>
    <p><label>Användare <input type='text' name='user' value='' placeholder='Ange användarnamn'/></label></p>
    <p><label>Lösenord <input type='password' name='passwd' value='' placeholder='Ange ditt lösenord'/></label></p>
    <p><input type='submit' name='submit' value='login'/></p>
    </fieldset>
  </form>
EOD;
        $out .= "<p>Du är INTE inloggad.</p>";
    }
    return $out;
  }

  /**
   * Logout user and return html with status message.
   *
   */
  public function LogoutAndOutputHTML()
  {
    if (self::$authenticated) {
      $output = "<p>{$this->name}, du är nu utloggad.</p>";
    }
    else {
      $output = "<p>Du är INTE inloggad.</p>";
    }
    $this->Logout();
    return $output;
  }

  /**
   * Login user. Call from controller page for self-submitting login-page.
   *
   */
  static public function ProcessLogin($db)
  {
      // If user pressed login button, try authenticate user.
      if (isset($_POST['submit']) && "login"==$_POST['submit']) {
          $user = new CUser();
          $user->Login($_POST['user'], $_POST['passwd'], $db);
      }
  }

  /**
   * Logout user
   *
   */
  public function Logout()
  {
    if (isset($_SESSION['user']->acronym)) {
      $_SESSION['user']->name = null;
      $_SESSION['user']->acronym = null;
    }
    self::$authenticated = false;
    $this->acronym = null;
    $this->name = null;
  }

  /**
   * Check if user is authenticated
   *
   */
  public static function IsAuthenticated()
  {
    // Check if user is authenticated.
    // Return true if authenticated.
    return self::$authenticated;
  }

  /**
   * Get functions
   *
   */
  public function GetAcronym()
  {
    return $this->acronym;
  }
  public function GetName()
  {
    return $this->name;
  }

}
