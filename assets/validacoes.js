document.addEventListener("DOMContentLoaded", () => {
  validarFormCadastrarCliente();
});

function validarFormCadastrarCliente() {
  const form = document.querySelector("#formCadastrarCliente");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    const erros = [];

    const nome = form.querySelector("#nome_cliente").value.trim();
    const email = form.querySelector("#email_cliente").value.trim();
    const telefone = form.querySelector("#telefone_cliente").value.trim();
    const cnpj = form.querySelector("#cnpj_cliente").value.trim();

    // Nome: mínimo 3 caracteres
    if (nome.length < 3) {
      erros.push("O nome deve ter pelo menos 3 caracteres.");
    }

    // Email: formato válido
    if (!validarEmail(email)) {
      erros.push("Digite um e-mail válido.");
    }

    // Telefone: apenas números e tamanho mínimo
    if (!/^\d{10,11}$/.test(telefone)) {
      erros.push(
        "O telefone deve conter apenas números e ter 10 ou 11 dígitos."
      );
    }

    // CNPJ: formato válido
    if (!validarCNPJ(cnpj)) {
      erros.push("Digite um CNPJ válido.");
    }

    if (erros.length > 0) {
      e.preventDefault();
      mostrarErros(form, erros);
    }
  });
}

function mostrarErros(form, erros) {
  const container = form.querySelector(".erros-validacao");
  if (container) {
    container.innerHTML = erros
      .map((msg) => `<p class="erro">${msg}</p>`)
      .join("");
  } else {
    alert(erros.join("\n"));
  }
}

function validarEmail(email) {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return regex.test(email);
}

function validarCNPJ(cnpj) {
  cnpj = cnpj.replace(/[^\d]+/g, "");

  if (cnpj.length !== 14) return false;
  if (/^(\d)\1+$/.test(cnpj)) return false;

  let tamanho = cnpj.length - 2;
  let numeros = cnpj.substring(0, tamanho);
  let digitos = cnpj.substring(tamanho);
  let soma = 0;
  let pos = tamanho - 7;

  for (let i = tamanho; i >= 1; i--) {
    soma += numeros.charAt(tamanho - i) * pos--;
    if (pos < 2) pos = 9;
  }
  let resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
  if (resultado != digitos.charAt(0)) return false;

  tamanho++;
  numeros = cnpj.substring(0, tamanho);
  soma = 0;
  pos = tamanho - 7;
  for (let i = tamanho; i >= 1; i--) {
    soma += numeros.charAt(tamanho - i) * pos--;
    if (pos < 2) pos = 9;
  }
  resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
  if (resultado != digitos.charAt(1)) return false;

  return true;
}
document.addEventListener("DOMContentLoaded", () => {
  aplicarMascaras();
  validarFormCadastrarCliente();
});

function aplicarMascaras() {
  const telInput = document.querySelector("#telefone_cliente");
  const cnpjInput = document.querySelector("#cnpj_cliente");

  if (telInput) {
    telInput.addEventListener("input", () => {
      let v = telInput.value.replace(/\D/g, "");
      if (v.length > 11) v = v.slice(0, 11);
      if (v.length <= 10) {
        telInput.value = v.replace(/(\d{2})(\d{4})(\d{0,4})/, "($1) $2-$3");
      } else {
        telInput.value = v.replace(/(\d{2})(\d{5})(\d{0,4})/, "($1) $2-$3");
      }
    });
  }

  if (cnpjInput) {
    cnpjInput.addEventListener("input", () => {
      let v = cnpjInput.value.replace(/\D/g, "");
      if (v.length > 14) v = v.slice(0, 14);
      cnpjInput.value = v.replace(
        /^(\d{2})(\d{3})(\d{3})(\d{4})(\d{0,2}).*/,
        "$1.$2.$3/$4-$5"
      );
    });
  }
}
