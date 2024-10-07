function configurarSelecaoLinha() {
    document.addEventListener('DOMContentLoaded', () => {
        const selectIcons = document.querySelectorAll('.bi-app');

        selectIcons.forEach(icon => {
            icon.addEventListener('click', selecionarLinha);
        });
    });
}

function selecionarLinha() {
    const row = this.closest('tr');
    row.classList.toggle('selected');

    if (row.classList.contains('selected')) {
        this.classList.remove('bi-app');
        this.classList.add('bi-check2-square');
        row.style.backgroundColor = '#e0f7fa'; // Altera a cor de fundo para a linha selecionada

        // Pegar informações das células da linha selecionada
        const cells = row.querySelectorAll('td');
        const id = cells[1].textContent;
        const nome = cells[2].textContent;
        const quantidade = cells[3].textContent;
        const preco = cells[4].textContent;

        // Armazenar as informações ou fazer o que for necessário com elas
        console.log("ID:", id);
        console.log("Nome:", nome);
        console.log("Quantidade:", quantidade);
        console.log("Preço:", preco);
    } else {
        this.classList.remove('bi-check2-square');
        this.classList.add('bi-app');
        row.style.backgroundColor = ''; // Remove a cor de fundo
    }
}

// Chamando a função para configurar a seleção de linhas quando a página é carregada
configurarSelecaoLinha();
