document.addEventListener("DOMContentLoaded", () => {
  aplicarMascaras();
});

function aplicarMascaras() {
  // Telefones
  const telInputs = document.querySelectorAll("#telefone_cliente, #contato_fornecedor, #telefone_funcionario");
  telInputs.forEach((input) => {
    input.addEventListener("input", () => {
      let v = input.value.replace(/\D/g, "");
      if (v.length > 11) v = v.slice(0, 11);
      if (v.length <= 10) {
        input.value = v.replace(/(\d{2})(\d{4})(\d{0,4})/, "($1) $2-$3");
      } else {
        input.value = v.replace(/(\d{2})(\d{5})(\d{0,4})/, "($1) $2-$3");
      }
    });
  });

  // CNPJ
  const cnpjInputs = document.querySelectorAll("#cnpj_cliente, #cnpj_fornecedor");
  cnpjInputs.forEach((input) => {
    input.addEventListener("input", () => {
      let v = input.value.replace(/\D/g, "");
      if (v.length > 14) v = v.slice(0, 14);
      input.value = v.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{0,2}).*/, "$1.$2.$3/$4-$5");
    });
  });

  // CPF
  const cpfInput = document.querySelector("#cpf_funcionario");
  if (cpfInput) {
    cpfInput.addEventListener("input", () => {
      let v = cpfInput.value.replace(/\D/g, "");
      if (v.length > 11) v = v.slice(0, 11);
      cpfInput.value = v.replace(/(\d{3})(\d{3})(\d{3})(\d{0,2})/, "$1.$2.$3-$4");
    });
  }

  // Salário e Preço (formato monetário simples)
  const moneyInputs = document.querySelectorAll("#salario_funcionario, #preco_produto");
  moneyInputs.forEach((input) => {
    input.addEventListener("input", () => {
      let v = input.value.replace(/\D/g, "");
      v = (parseFloat(v) / 100).toFixed(2);
      input.value = "R$ " + v.replace(".", ",");
    });
  });

  // Nomes e textos (bloqueia números)
  const nomeInputs = document.querySelectorAll(
    "#nome_cliente, #nome_fornecedor, [name='nome_funcionario'], [name='nome_usuario'], #nome_produto, #descricao_produto, #plataforma_produto, #tipo_produto"
  );
  nomeInputs.forEach((input) => {
    input.addEventListener("input", () => {
      input.value = input.value.replace(/\d/g, "");
    });
  });
}

document.querySelectorAll('.combo-filtravel').forEach(select => {
  const input = document.createElement('input');
  input.setAttribute('type', 'text');
  input.setAttribute('placeholder', 'Filtrar...');
  input.classList.add('filtro-combo');

  select.parentNode.insertBefore(input, select);

  input.addEventListener('input', () => {
    const filtro = input.value.toLowerCase();
    Array.from(select.options).forEach(option => {
      const texto = option.text.toLowerCase();
      option.style.display = texto.includes(filtro) || option.value === "" ? 'block' : 'none';
    });
  });
});

