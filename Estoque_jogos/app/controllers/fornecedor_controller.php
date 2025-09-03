<?php
require_once '/../config/database.php';
require_once '/../models/cliente.php';

session_start();

$pdo = database();
$model = new fornecedor_model($pdo);

class Fornecedor_controller
{

    public function listar_fornecedor()
    {
        $fornecedores = $this->fornecedor_model->listar_fornecedor();
        require 'app/views/fornecedor/gerenciar_fornecedor.php';
    }
    public function cadastrar_fornecedor()
    {
        require 'app/views/fornecedor/cadastrar.php';
    }
    public function salvar_fornecedor()
    {
        $dados = [
            'nome_fornecedor' => $_POST['nome_fornecedor'],
            'email_fornecedor' => $_POST['email_fornecedor'],
            'telefone_fornecedor' => $_POST['telefone_fornecedor'],
            'cnpj_fornecedor' => $_POST['cnpj_fornecedor']
        ];
        $this->fornecedor_model->cadastrar_fornecedor($dados);
        header('Location: /fornecedor');
    }
    public function buscar_fornecedor_por_id($id_fornecedor)
    {
        $fornecedor = $this->fornecedor_model->buscar_fornecedor_por_id($id_fornecedor);
        require 'app/views/fornecedor/editar_fornecedor.php';
    }
    public function buscar_fornecedor_por_nome($nome_fornecedor)
    {
        $fornecedores = $this->fornecedor_model->buscar_fornecedor_por_nome($nome_fornecedor);
        require 'app/views/fornecedor/gerenciar_fornecedor.php';
    }
    public function atualizar_fornecedor($id_fornecedor)
    {
        $dados = [
            'nome_fornecedor' => $_POST['nome_fornecedor'],
            'email_fornecedor' => $_POST['email_fornecedor'],
            'telefone_fornecedor' => $_POST['telefone_fornecedor'],
            'cnpj_fornecedor' => $_POST['cnpj_fornecedor']
        ];
        $this->fornecedor_model->atualizar_fornecedor($id_fornecedor, $dados);
        header('Location: /fornecedor');
    }
    public function deletar_fornecedor($id_fornecedor)
    {
        $this->fornecedor_model->deletar_fornecedor($id_fornecedor);
        header('Location: /fornecedor');
    }
}
?>