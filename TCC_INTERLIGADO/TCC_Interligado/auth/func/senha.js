

let senha = document.querySelector('div[name="senha"]');
let inputsenha=document.querySelector('input[name="senha"]');
let iconsenha =document.querySelector('img[name="senha"]');
iconsenha.addEventListener
('click',function() {
         senha.classList.toggle('visible'); 
        if(senha.classList.contains('visible')) 
        { iconsenha.src = 'olho.png';
            inputsenha.type = 'text';
        } 
        else {
            iconsenha.src = 'olhorisc.png';
            inputsenha.type= 'password'; 
        }
            }
);

let repsenha = document.querySelector('div[name="repsenha"]');
let inputrepsenha=document.querySelector('input[name="repsenha"]');
let iconrepsenha =document.querySelector('img[name="repsenha"]');
iconrepsenha.addEventListener
('click',function() {
         repsenha.classList.toggle('visible'); 
        if(repsenha.classList.contains('visible')) 
        { iconrepsenha.src = 'olho.png';
            inputrepsenha.type = 'text';
        } 
        else {
            iconrepsenha.src = 'olhorisc.png';
            inputrepsenha.type='password'; 
        }
            }
);

