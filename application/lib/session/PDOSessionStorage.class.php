<?php

class PDOSessionStorage extends mvcSessionStorage
{
  public  $maxTime;
  private $db;

  /**
   * Initializes the session storage, sets the callbacks for session handling.
   */
  public function init()
  {
    $this->db = $this->getPDOConnection();
    
    $this->maxTime['access'] = time();
    $this->maxTime['gc'] = get_cfg_var('session.gc_maxlifetime');

    session_set_save_handler(
      array($this, 'open'),
      array($this, 'close'),
      array($this, 'read'),
      array($this, 'write'),
      array($this, 'destroy'),
      array($this, 'clean')
    );

    register_shutdown_function('session_write_close');
  }

  /**
   * Gets the PDO connection, configured in session.yml.
   *
   * @return PDO
   */
  protected function getPDOConnection()
  {
    $dbType = mvcConfigStorage::get('session_db_type');
    $dbName = mvcConfigStorage::get('session_db_name');
    $hostname = mvcConfigStorage::get('session_db_host');
    $port = mvcConfigStorage::get('session_db_port');
    $username = mvcConfigStorage::get('session_db_user');
    $password = mvcConfigStorage::get('session_db_pass');

    return new PDO("$dbType:host=$hostname;port=$port;dbname=$dbName", $username, $password);
  }

  /**
   * This is the method responsible for opening the session.
   *
   * @return true
   */
  public function open()
  {
    return true;
  }

  /**
   * This is the method responsible for closing the session.
   *
   * @return true
   */
  public function close()
  {
    $this->clean($this->maxTime['gc']);

    return true;
  }

  /**
   * This is the method responsible for reading from the session.
   *
   * @return mixed
   */
  public function read($id)
  {
    $getData = $this->db->prepare("SELECT `data`
      FROM `session`
      WHERE `id` = ?");

    $getData->bindParam(1, $id);
    $getData->execute();

    $allData = $getData->fetch(PDO::FETCH_ASSOC);
    $totalData = count($allData);
    $hasData = (bool) $totalData >= 1;

    return $hasData ? $allData['data'] : '';
  }

  /**
   * This is the method responsible for saving data to the session.
   *
   * @return mixed
   */
  public function write($id, $data)
  {
    $getData = $this->db->prepare("REPLACE INTO
      `session`
      VALUES (?, ?, ?)");

    $getData->bindParam(1, $id);
    $getData->bindParam(2, $this->maxTime['access']);
    $getData->bindParam(3, $data);

    return $getData->execute();
  }

  /**
   * This is the method responsible for destroying the session.
   *
   * @return mixed
   */
  public function destroy($id)
  {
    $getData = $this->db->prepare("DELETE FROM
      `session`
      WHERE `id` = ?");

    $getData->bindParam(1, $id);

    return $getData->execute();
  }

  /**
   * This is the method responsible for cleaning the session.
   *
   * @return mixed
   */
  public function clean($max)
  {
    $old = ($this->maxTime['access'] - $max);

    $getData = $this->db->prepare("DELETE FROM
      `session`
      WHERE `access` < ?");

    $getData->bindParam(1, $old);

    return $getData->execute();
  }
}