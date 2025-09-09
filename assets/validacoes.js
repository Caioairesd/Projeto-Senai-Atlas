// Aguarda o carregamento completo do DOM antes de executar as funções
document.addEventListener("DOMContentLoaded", () => {
  aplicarMascaras(); // Aplica todas as máscaras de formatação
  aplicarFiltroSelect(); // Aplica filtros em elementos select
});

// Função principal que aplica todas as máscaras
function aplicarMascaras() {
  aplicarMascaraTelefone(); // Formata campos de telefone
  aplicarMascaraCNPJ(); // Formata campos de CNPJ
  aplicarMascaraCPF(); // Formata campos de CPF
  aplicarMascaraMonetaria(); // Formata campos monetários
  bloquearNumerosEmCamposDeTexto(); // Bloqueia números em campos de texto
}

// Função para formatar campos de telefone
function aplicarMascaraTelefone() {
  // Seleciona todos os campos de telefone pelos seus IDs
  const telInputs = document.querySelectorAll("#telefone_cliente, #contato_fornecedor, #telefone_funcionario");
  
  telInputs.forEach((input) => {
    // Adiciona evento de input para formatar em tempo real
    input.addEventListener("input", () => {
      // Remove todos os caracteres não numéricos e limita a 11 dígitos
      let v = input.value.replace(/\D/g, "").slice(0, 11);
      
      // Aplica a formatação apropriada baseada no tamanho do número
      input.value = v.length <= 10
        ? v.replace(/(\d{2})(\d{4})(\d{0,4})/, "($1) $2-$3") // Formato para 10 dígitos
        : v.replace(/(\d{2})(\d{5})(\d{0,4})/, "($1) $2-$3"); // Formato para 11 dígitos
    });
  });
}

// Função para formatar campos de CNPJ
function aplicarMascaraCNPJ() {
  // Seleciona todos os campos de CNPJ pelos seus IDs
  const cnpjInputs = document.querySelectorAll("#cnpj_cliente, #cnpj_fornecedor");
  
  cnpjInputs.forEach((input) => {
    // Adiciona evento de input para formatar em tempo real
    input.addEventListener("input", () => {
      // Remove todos os caracteres não numéricos e limita a 14 dígitos
      let v = input.value.replace(/\D/g, "").slice(0, 14);
      
      // Aplica a formatação do CNPJ (XX.XXX.XXX/XXXX-XX)
      input.value = v.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{0,2}).*/, "$1.$2.$3/$4-$5");
    });
  });
}

// Função para formatar campos de CPF
function aplicarMascaraCPF() {
  // Seleciona o campo de CPF pelo seu ID
  const cpfInput = document.querySelector("#cpf_funcionario");
  
  if (cpfInput) {
    // Adiciona evento de input para formatar em tempo real
    cpfInput.addEventListener("input", () => {
      // Remove todos os caracteres não numéricos e limita a 11 dígitos
      let v = cpfInput.value.replace(/\D/g, "").slice(0, 11);
      
      // Aplica a formatação do CPF (XXX.XXX.XXX-XX)
      cpfInput.value = v.replace(/(\d{3})(\d{3})(\d{3})(\d{0,2})/, "$1.$2.$3-$4");
    });
  }
}

// Função para formatar campos monetários
function aplicarMascaraMonetaria() {
  // Seleciona todos os campos monetários pelos seus IDs
  const moneyInputs = document.querySelectorAll("#salario_funcionario, #preco_produto");
  
  moneyInputs.forEach((input) => {
    // Adiciona evento de input para formatar em tempo real
    input.addEventListener("input", () => {
      // Remove todos os caracteres não numéricos
      let v = input.value.replace(/\D/g, "");
      
      // Se o valor estiver vazio, limpa o campo e retorna
      if (!v) {
        input.value = "";
        return;
      }
      
      // Converte para formato decimal (divide por 100) e formata com 2 casas decimais
      v = (parseFloat(v) / 100).toFixed(2);
      
      // Adiciona o prefixo "R$" e substitui ponto por vírgula
      input.value = "R$ " + v.replace(".", ",");
    });
  });
}

// Função para bloquear números em campos de texto
function bloquearNumerosEmCamposDeTexto() {
  // Seleciona todos os campos de texto que devem bloquear números
  const nomeInputs = document.querySelectorAll(
    "#nome_cliente, #nome_fornecedor, [name='nome_funcionario'], [name='nome_usuario'], #nome_produto, #descricao_produto, #plataforma_produto, #tipo_produto"
  );
  
  nomeInputs.forEach((input) => {
    // Adiciona evento de input para filtrar caracteres
    input.addEventListener("input", () => {
      // Permite apenas letras (incluindo acentuadas) e espaços
      input.value = input.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, "");
    });
  });
}

// Função para adicionar filtro em elementos select
function aplicarFiltroSelect() {
  // Seleciona todos os elementos select com a classe 'combo-filtravel'
  document.querySelectorAll('.combo-filtravel').forEach(select => {
    // Cria um campo de input para filtro
    const input = document.createElement('input');
    input.setAttribute('type', 'text');
    input.setAttribute('placeholder', 'Filtrar...');
    input.classList.add('filtro-combo');
    
    // Insere o campo de input antes do elemento select
    select.parentNode.insertBefore(input, select);
    
    // Adiciona evento de input para filtrar as opções
    input.addEventListener('input', () => {
      // Obtém o texto do filtro em minúsculas
      const filtro = input.value.toLowerCase();
      
      // Itera sobre todas as opções do select
      Array.from(select.options).forEach(option => {
        // Obtém o texto da opção em minúsculas
        const texto = option.text.toLowerCase();
        
        // Mostra a opção se corresponder ao filtro ou se for uma opção vazia
        option.style.display = texto.includes(filtro) || option.value === "" ? 'block' : 'none';
      });
    });
  });
}