document.getElementById('edit-cep').addEventListener('input', function() {
    const cep = this.value.replace(/\D/g, ''); // Remove qualquer caractere que não seja número

    if (cep.length === 8) {
        const url = `https://viacep.com.br/ws/${cep}/json/`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (!('erro' in data)) {
                    // Atualiza os campos de endereço com os dados retornados
                    document.getElementById('edit-bairro').value = data.bairro;
                    document.getElementById('edit-uf').value = data.uf;
                } else {
                    alert('CEP não encontrado.');
                }
            })
            .catch(error => {
                console.error('Erro ao buscar o CEP:', error);
                alert('Erro ao buscar o CEP.');
            });
    }
});
