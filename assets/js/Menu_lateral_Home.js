var menuItem = document.querySelectorAll('.item-menu');

function selectLink(){
    menuItem.forEach((item)=>
        item.classList.remove('active')
    )
    this.classList.add('active')
}

menuItem.forEach((item)=>
    item.addEventListener('click', selectLink)
)

var btnExp = document.querySelector('#btn-exp')
var menuIcon = document.querySelector('.menu-lateral')
var body = document.body; // Seleciona o body

btnExp.addEventListener('click', function(){
    menuIcon.classList.toggle('expandir')
    body.classList.toggle('menu-expandido'); // Adiciona ou remove a classe do body
})