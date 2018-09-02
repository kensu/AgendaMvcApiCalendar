<?php
require_once 'models/ContatoModel.php'; 
  

class ContatoController
{
    public function listarContatoAction()
    {
        $o_Contato = new ContatoModel();
          
        //Listando os contatos cadastrados
        $v_contatos = $o_Contato->_list();
          
        $o_view = new View('views/listarContatos.phtml');
          
        $o_view->setParams(array('v_contatos' => $v_contatos));
          
        //Imprimindo código HTML
        $o_view->showContents();
    }
      
    public function manterContatoAction()
    {
        $o_contato = new ContatoModel();
          
        if( isset($_REQUEST['in_con']) )
            if( DataValidator::isNumeric($_REQUEST['in_con']) )
                $o_contato->loadById($_REQUEST['in_con']);
              
        if(count($_POST) > 0)
        {
            $o_contato->setNome(DataFilter::cleanString($_POST['st_nome']));
            $o_contato->setEmail(DataFilter::cleanString($_POST['st_email']));
              
            //salvando en lista de contatos
            if($o_contato->save() > 0)
                Application::redirect('?controle=Contato&acao=listarContato');
        }
              
        $o_view = new View('views/manterContato.phtml');
        $o_view->setParams(array('o_contato' => $o_contato));
        $o_view->showContents();
    }
      
    
    
    public function apagarContatoAction()
    {
        if( DataValidator::isNumeric($_GET['in_con']) )
        {
            //borrar contato
            $o_contato = new ContatoModel();
            $o_contato->loadById($_GET['in_con']);
            $o_contato->delete();
              
            //borrar telefono
            $o_telefone = new TelefoneModel();
            $v_telefone = $o_telefone->_list($_GET['in_con']);
            foreach($v_telefone AS $o_telefone)
                $o_telefone->delete();
            Application::redirect('?controle=Contato&acao=listarContato');
        }   
    }
}
?>
