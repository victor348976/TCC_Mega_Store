<?php
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Layout Estilo Lunya</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, Helvetica, sans-serif;
}

.hero{
    width:100%;
    height:100vh;
    background:url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1600')
    center/cover no-repeat;
    display:flex;
    justify-content:center;
    align-items:center;
    text-align:center;
    color:white;
    position:relative;
}

.hero::before{
    content:'';
    position:absolute;
    inset:0;
    background:rgba(0,0,0,0.35);
}

.hero-content{
    position:relative;
    max-width:800px;
    padding:20px;
}

.hero-content h1{
    font-size:4rem;
    margin-bottom:20px;
}

.hero-content p{
    font-size:1.2rem;
    line-height:1.6;
}

.about-section{
    display:flex;
    min-height:700px;
}

.about-section.reverse{
    flex-direction:row-reverse;
}

.about-image{
    width:50%;
}

.about-image img{
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
}

.about-text{
    width:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:80px;
}

.about-content{
    max-width:500px;
}

.about-content h2{
    font-size:2.5rem;
    margin-bottom:25px;
}

.about-content p{
    line-height:1.8;
    color:#444;
    font-size:1.1rem;
}

@media(max-width:768px){

    .hero-content h1{
        font-size:2.5rem;
    }

    .about-section,
    .about-section.reverse{
        flex-direction:column;
    }

    .about-image,
    .about-text{
        width:100%;
    }

    .about-image{
        height:400px;
    }

    .about-text{
        padding:40px 25px;
    }
}
</style>

</head>
<body>

<!-- HERO -->

<section class="hero">
    <div class="hero-content">
        <h1>Nossa História</h1>

        <p>
            "Seja o seu próprio tipo de beleza." – Willy Cartier
        </p>
    </div>
</section>

<!-- BLOCO 1 -->

<section class="about-section">

    <div class="about-image">
        <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?w=1200">
    </div>

    <div class="about-text">
        <div class="about-content">
            <h2>O Legado e a Fundação</h2>

            <p>
                       A história da Mega Store não começa apenas com um CNPJ, mas com um legado familiar de mais de duas décadas no varejo. Em 2007, sob o olhar atento e a experiência da genitora — que já trilhava um caminho de sucesso em outra cidade — a loja foi inaugurada.              O primeiro ano foi um período de mentoria e transição, onde a estrutura foi montada para ser o alicerce da própria jornada empreendedora, contando com o apoio indispensável de quem já conhecia os segredos do mercado.


            </p>
        </div>
    </div>

</section>

<!-- BLOCO 2 -->

<section class="about-section reverse">

    <div class="about-image">
        <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=1400&h=900&q=80">
    </div>

    <div class="about-text">
        <div class="about-content">
            <h2>Os Primeiros Passos e a Independência</h2>

            <p>
                       O ano de 2008 foi quando, a atual gestora, assumiu oficialmente a responsabilidade total pela empresa, que passou a levar o seu nome e a exigir um olhar estratégico. Durante quatro anos, foi cultivado as raízes da empresa no primeiro ponto comercial, um espaço preparado com carinho que serviu de escola para entender os desejos dos clientes e os desafios da gestão diária.
            </p>
        </div>
    </div>

</section>

<!-- BLOCO 3 -->

<section class="about-section">

    <div class="about-image">
        <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1200">
    </div>

    <div class="about-text">
        <div class="about-content">
            <h2>Crescimento e a Identidade "Mega Modas"</h2>

            <p>
                     Com o amadurecimento da empresa, a expansão tornou-se inevitável. A loja mudou-se para um novo endereço, onde operou por cinco anos sob a bandeira Mega Modas. Foi nesta fase que a marca consolidou sua presença no mercado e construiu uma história própria, conquistando um espaço de relevância e fidelizando um público que acompanhava de perto a evolução e a dedicação depositadas em cada detalhe do atendimento.
            </p>
        </div>
    </div>

</section>

<!-- BLOCO 4 -->

<section class="about-section reverse">

    <div class="about-image">
        <img src="https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?auto=format&fit=crop&w=1400&h=900&q=80">
    </div>

    <div class="about-text">
        <div class="about-content">
            <h2>A Consolidação e o Nascimento da Mega Store</h2>

            <p>
                       O ápice dessa trajetória ocorreu em 2017, com a realização do sonho da sede própria. Ao adquirir o terreno e construir um espaço planejado do zero, a empresa deixou de ser apenas um comércio para se tornar um destino. Esse novo capítulo exigiu uma identidade que refletisse tamanha transformação: a Mega Modas evoluiu para Mega Store. Hoje, a loja é a materialização de um percurso que une o respeito às origens e a força de uma gestão que soube construir seu próprio patrimônio e identidade.

            </p>
        </div>
    </div>

</section>

</body>
</html>