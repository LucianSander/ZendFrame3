<?php

namespace Pessoa\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Pessoa\Form\PessoaForm;

class PessoaController extends AbstractActionController{

    private $table;

    // injeção de dependencia 
    public function __construct($table){

        $this->table = $table; //new PessoaTable();

    }

    //Ao final do metodo é utilizado o sufixo Action para mostrar que
    //se refere a uma ação que sera tomada

    public function indexAction(){
        //Ao retornar uma view model o zend entende que o que sera renderizado
        //sera o prefixo que acompanha o Action no nome do metodo nesse caso "index"
        return new ViewModel(['pessoas' => $this->table->getAll()]);
    }

    public function adicionarAction(){
        //Criação de formulário
        $form = new PessoaForm();
        $form->get('submit')->setValue('Adicionar');
        //Aqui consigo pegar as informações da requisição
        $request = $this->getRequest();
        //Aqui é para testar se o request é um post se não será enviando para realização de adição da informação
        if (!$request->isPost()){
            return new ViewModel(['form'=>$form]);
        }
        //criação de elemento pessoa
        $pessoa = new \Pessoa\Model\Pessoa();
        //setar dados que o usuario ja enviou/trazer os dados para preencher o formulário
        $form->setData($request->getPost());
        //validação dos dados
        if (!$form->isValid()){
            return new ViewModel(['form'=>$form]);
        }
        //retorna a array, so vai carregar se passa pelo validador
        $pessoa->exchangeArray($form->getData());
        //salvar no banco depois de ter pego os dados
        $this->table->salvarPessoa($pessoa);
        //retornar para uma rota pessoa, caindo na listagem de pessoas
        return $this->redirect()->toRoute('pessoa');
    }


    public function salvarAction(){
        return new viewModel();
    }


    public function editarAction(){
        $id = (int) $this->params()->fromRoute('id',0);
        if (0 === $id) {
            return $this->redirect()->toRoute('pessoa', ['action' => 'adiconar']);
        }
        try {
            $pessoa = $this->table->getPessoa($id);
        } catch (Exception $exc) {
            return $this->redirect()->toRoute('pessoa', ['action' => 'index']);
        }
        $form = new PessoaForm();
        $form->bind($pessoa);
        $form->get('submit')->setAttribute('value', 'Salvar');
        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];
        if (!$request->isPost()) {
            return $viewData;
        }
        $form->setData($request->getPost());
        if (!$form->isValid()){
            return new $ViewData;
        }

        //$pessoa->exchangeArray($form->getData());
        $this->table->salvarPessoa($form->getData());
        return $this->redirect()->toRoute('pessoa');
    }

    public function removerAction(){
        $id = (int) $this->params()->fromRoute('id',0);
        if (0 === $id) {
            return $this->redirect()->toRoute('pessoa');
        }
        //seleção de sim ou não
        $request = $this->getRequest();
        if($request->isPost()){
            $del = $request->getPost('del','Não');
            if($del == 'Sim') {
                $id = (int) $request->getPost('id');
                $this->table->deletarPessoa($id);
            }
            return $this->redirect()->toRoute('pessoa');
        }
    //confirmação se quero remover ou não
        return ['id'=>$id,'pessoa' => $this->table->getPessoa($id)];
    }

    public function confirmacaoAction(){
        return new viewModel();
    }
    /**
     * pessoa             -> index
     * pessoa/adicionar   -> adicionarAction
     * pessoa/salvar      -> salvarAction
     * pessoa/editar      -> editarAction
     * pessoa/confirmacao -> confirmacaoAction
     * pessoa/remover     -> removerAction
     */
}