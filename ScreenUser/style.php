<?php
header("Content-type: text/css");
?>

@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto+Slab:wght@100..900&display=swap');

*{
    margin: 0;
    padding: 0;
}

body {
    background-color: var(--cinzaEscuro);
    font-family: "Montserrat", sans-serif;
    background-color: #161616;
}

header {
    width: 100%;
    height: 75px;
    background-color: #1d1d1d;
    display: flex;
    justify-content: center;
    align-items: center;
    border-bottom: 2px solid #42FF00;
}

header img {
    height: 48px;
    margin-top: 2px;
}

nav.menu-lateral{
    width: 72px;
    height: 100%;
    background-color: #1d1d1d;
    padding: 40px 0 40px 1%;
    box-shadow: 2px 0 0 #42FF00;
    position: fixed;
    top: 0;
    left: 0;
    overflow: hidden;
    transition: 1.0s;
}
nav.menu-lateral:hover{
    width: 300px;
}

.btn-expandir{
    width: 100%;
    padding-left: 10px;
}
.btn-expandir > i{
    color: #fff;
    cursor: pointer;
    font-size: 24px;
}

ul{
    height: 100%;
    list-style-type: none;
}

ul li.item-menu{
    transition: .2s;
}

ul li.item-menu:hover{
    background-color: #42FF00;
    border-radius: 10px;
}

ul li.item-menu a{
    color: #fff;
    text-decoration: none;
    font-size: 20px;
    padding: 20px 4%;
    display: flex;
    margin-bottom: 20px;
    line-height: 20px;
}

ul li.item-menu a .txt-link{
    margin-left: 40px;
    font-size: 20px;
    display: flex;
    transition: .5s;
    align-items: center;
}
nav.menu-lateral.expandir .txt-link{
    margin-left: 40px;
    opacity: 1;
}

ul li.item-menu a .icon i{
    font-size: 30px;
    margin-left: 10px;
}

/*seção*/

section .primeira_sessao{
    display: flex;
    flex-direction: column;
    align-items: center;
}
section .profile-page * {
    margin: 0;
    padding: 0;
    border: 0;
    font: inherit;
    vertical-align: baseline;
    box-sizing: border-box;
}

section .profile-page{
    width: 70%;
}

section .dados-perfil{
    display: grid;
    gap: 16px;
    margin: 20px auto;
    padding: 16px;
    background-color: #ebebeb;
    border-radius: 8px;
    
}
section .dados-perfil p.grid {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #f8f7f8;
    border-radius: 5px;
    padding: 10px;
}

section .dados-perfil input {
    width: 500px;
    height: 25px;
    padding: 5px;
    background-color: #f8f7f8;
    font-size: 16px;
    border: none;
    margin-bottom: 6px;
    margin-top: 6px;
    cursor: pointer;
}

section .dados-perfil input:focus{
    border: none;
    outline: none;
}
    
.block__item, .block__section, .grid{
        cursor: pointer;
        display: grid;
        grid-template-columns: 1fr 20px;
        padding: 16px;
        color: #000000;
        user-select: none;
        background-color: #f8f7f8;
        border-radius: 10px;
    }

    section .dados-perfil button {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
    }
    
    section .dados-perfil button i {
        font-size: 20px;
        color: #000; 
    }

section .grid {
    overflow: hidden;
    border-bottom: 3px solid #ebebeb;
}


.block__item:last-child {
        border-bottom: none;
}
/* Estilo geral para o diálogo */
dialog#dialog, #dialogNome, #dialogCPF, #dialogNascimento, #dialogTelefone, #dialog-email{
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 450px;
    height: 320px;
    background-color: #fff;
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
}
/* Fundo escurecido ao abrir o diálogo */
body::backdrop {
    background-color: rgba(0, 0, 0, 0.5);
}
/* Footer */
footer{
    background-color: #111;
}
.footerContainer{
    margin-top: 100px;
    width: 90%;
    padding: 30px 10px 10px ;
}
.socialIcons{
    display: flex;
    justify-content: center;
}
.socialIcons a{
    text-decoration: none;
    padding:  10px;
    background-color: white;
    margin: 10px;
    border-radius: 50%;
}
.socialIcons a i{
    font-size: 2em;
    color: black;
    opacity: 0,9;
}


.socialIcons a:hover{
    background-color: #111;
    transition: 0.5s;
}
.socialIcons a:hover i{
    color: white;
    transition: 0.5s;
}

.footerBottom{
    background-color: #000;
    padding: 20px;
    text-align: center;
}
.footerBottom p{
    color: white;
}