<?php
require_once 'app/core/database.php';
require_once 'app/models/cliente.php';

class cliente_controller
{
    private $cliente_model;

    public function __construct()
    {
        $this->cliente_model = new cliente_model();
    }

    // Lista todos os clientes
    public function listar_cliente()
    {
        $clientes = $this->cliente_model->listar_cliente();
        require 'app/views/cliente/gerenciar_cliente.php';
    }

    // Exibe o formulário de cadastro
    public function cadastrar_cliente()
    {
        require 'app/views/cliente/cadastrar.php';
    }

    // Processa o cadastro
    public function salvar_cliente()
    {
        $dados = [
            'nome_cliente'     => $_POST['nome_cliente'],
            'email_cliente'    => $_POST['email_cliente'],
            'telefone_cliente' => $_POST['telefone_cliente'],
            'cnpj_fornecedor'  => $_POST['cnpj_fornecedor']
        ];

        $this->cliente_model->cadastrar_cliente($dados);
        header('Location: /cliente');
    }

    // Busca cliente por ID
    public function buscar_cliente_por_id($id_cliente)
    {
        $cliente = $this->cliente_model->buscar_cliente_por_id($id_cliente);
        require 'app/views/cliente/editar_cliente.php';
    }

    // Busca cliente por nome
    public function buscar_cliente_por_nome($nome_cliente)
    {
        $clientes = $this->cliente_model->buscar_cliente_por_nome($nome_cliente);
        require 'app/views/cliente/gerenciar_cliente.php';
    }

    // Atualiza cliente
    public function atualizar_cliente($id_cliente)
    {
        $dados = [
            'nome_cliente'     => $_POST['nome_cliente'],
            'email_cliente'    => $_POST['email_cliente'],
            'telefone_cliente' => $_POST['telefone_cliente'],
            'cnpj_fornecedor'  => $_POST['cnpj_fornecedor']
        ];

        $this->cliente_model->atualizar_cliente($id_cliente, $dados);
        header('Location: /cliente');
    }

    // Exclui cliente
    public function deletar_cliente($id_cliente)
    {
        $this->cliente_model->deletar_cliente($id_cliente);
        header('Location: /cliente');
    }
}
