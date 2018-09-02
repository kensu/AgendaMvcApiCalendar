<?php
require_once 'models/TelefoneModel.php';
  

class ContatoModel extends PersistModelAbstract
{
    private $in_id;
    private $st_nome;
    private $st_email;
      
    function __construct()
    {
        parent::__construct();
        $this->createTableContato();
    }
      
      
      
    public function setId( $in_id )
    {
        $this->in_id = $in_id;
        return $this;
    }
      
    public function getId()
    {
        return $this->in_id;
    }
      
    public function setNome( $st_nome )
    {
        $this->st_nome = $st_nome;
        return $this;
    }
      
    public function getNome()
    {
        return $this->st_nome;
    }
      
    public function setEmail( $st_email )
    {
        $this->st_email = $st_email;
        return $this;
    }
      
    public function getEmail()
    {
        return $this->st_email;
    }
      
    public function _list( $st_nome = null )
    {
        if(!is_null($st_nome))
            $st_query = "SELECT * FROM tbl_contato WHERE con_st_nome LIKE '%$st_nome%';";
        else
            $st_query = 'SELECT * FROM tbl_contato;';   
          
        $v_contatos = array();
        try
        {
            $o_data = $this->o_db->query($st_query);
            while($o_ret = $o_data->fetchObject())
            {
                $o_contato = new ContatoModel();
                $o_contato->setId($o_ret->con_in_id);
                $o_contato->setNome($o_ret->con_st_nome);
                $o_contato->setEmail($o_ret->con_st_email);
                array_push($v_contatos, $o_contato);
            }
        }
        catch(PDOException $e)
        {}              
        return $v_contatos;
    }
      
    public function loadById( $in_id )
    {
        $v_contatos = array();
        $st_query = "SELECT * FROM tbl_contato WHERE con_in_id = $in_id;";
        $o_data = $this->o_db->query($st_query);
        $o_ret = $o_data->fetchObject();
        $this->setId($o_ret->con_in_id);
        $this->setNome($o_ret->con_st_nome);
        $this->setEmail($o_ret->con_st_email);        
        return $this;
    }
      
    public function save()
    {
        if(is_null($this->in_id))
            $st_query = "INSERT INTO tbl_contato
                        (
                            con_st_nome,
                            con_st_email
                        )
                        VALUES
                        (
                            '$this->st_nome',
                            '$this->st_email'
                        );";
        else
            $st_query = "UPDATE
                            tbl_contato
                        SET
                            con_st_nome = '$this->st_nome',
                            con_st_email = '$this->st_email'
                        WHERE
                            con_in_id = $this->in_id";
        try
        {
              
            if($this->o_db->exec($st_query) > 0)
                if(is_null($this->in_id))
                {
                    if($this->o_db->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite')
                    {
                        $o_ret = $this->o_db->query('SELECT last_insert_rowid() AS con_in_id')->fetchObject();
                        return $o_ret->con_in_id;
                    }
                    else
                        return $this->o_db->lastInsertId();
                }
                else
                    return $this->in_id;
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return false;               
    }
  
    public function delete()
    {
        if(!is_null($this->in_id))
        {
            $st_query = "DELETE FROM
                            tbl_contato
                        WHERE con_in_id = $this->in_id";
            if($this->o_db->exec($st_query) > 0)
                return true;
        }
        return false;
    }
      
    private function createTableContato()
    {
        if($this->o_db->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite')
            $st_auto_increment = '';
        else
            $st_auto_increment = 'AUTO_INCREMENT';
         
        $st_query = "CREATE TABLE IF NOT EXISTS tbl_contato
                    (
                        con_in_id INTEGER NOT NULL $st_auto_increment,
                        con_st_nome CHAR(200),
                        con_st_email CHAR(100),
                        PRIMARY KEY(con_in_id)
                    )";
 
        try
       {
            $this->o_db->exec($st_query);
        }
        catch(PDOException $e)
        {
            throw $e;
        }   
    }
}
?>
