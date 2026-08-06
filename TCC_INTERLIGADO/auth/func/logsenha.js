
let logsenha = document.querySelector('div[name="logsenha"]');
let inputlog=document.querySelector('input[name="logsenha"]');
let iconlog =document.querySelector('img[name="logsenha"]');
iconlog.addEventListener
('click',function() {
         logsenha.classList.toggle('visible'); 
        if(logsenha.classList.contains('visible')) 
        { iconlog.src = 'olho.png';
            inputlog.type = 'text';
        } 
        else {
            iconlog.src = 'olhorisc.png';
            inputlog.type= 'password'; 
        }
            }
);
