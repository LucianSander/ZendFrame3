<?php

namespace Pessoa\Model;

use Zend\Db\TableGateway\TableGatewayInterface;
use RuntimeException;

class PessoaTable {

    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }


    public function getAll(){
        return $this->tableGateway->select();
    }

    public function getPessoa($id){
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        //validação
        if (!$row) {
            throw new RuntimeException(sprintf('Não foi encontrado o id %d',$id));
        }
        return $row;
    }
    //função para salavra uma pessoa
    public function salvarPessoa(Pessoa $pessoa){
        $data = [
            //'id' => $pessoa->getId(),
            'nome' => $pessoa->getNome(),
            'sobrenome' => $pessoa->getSobrenome(),
            'email' => $pessoa->getEmail(),
            'situacao' => $pessoa->getSituacao(),
        ];

        $id = (int) $pessoa->getId();
        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }
        $this->tableGateway->update($data,['id' => $id]);
    }
    //função para deletar uma pessoa
    public function deletarPessoa($id){
        $this->tableGateway->delete(['id'=>(int)$id]);
    }

}