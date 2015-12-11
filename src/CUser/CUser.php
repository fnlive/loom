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
  private $authenticated = false;
  private $acronym = null;
  private $name = null;

  /**
   * Constructor
   *
   */
  function __construct()
  {
    if (isset($_SESSION['user']->acronym)) {
      $this->authenticated = true;
      $this->acronym = $_SESSION['user']->acronym;
      $this->name = $_SESSION['user']->name;
    }
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
      $authenticated = true;
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

  // Check if user is authenticated.
  if($this->authenticated) {
    $out .= "<p>Du är inloggad som: {$this->acronym} ({$this->name})</p>";
  }
  else {
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
    if ($this->authenticated) {
      $output = "<p>{$this->name}, du är nu utloggad.</p>";
    }
    else {
      $output = "<p>Du är INTE inloggad.</p>";
    }
    $this->Logout();
    return $output;
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
    $this->authenticated = false;
    $this->acronym = null;
    $this->name = null;
  }

  /**
   * Check if user is authenticated
   *
   */
  public function IsAuthenticated()
  {
    // Check if user is authenticated.
    // Return true if authenticated.
    return $this->authenticated;
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
