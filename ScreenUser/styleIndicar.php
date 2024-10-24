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
    width: 75px;
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
    align-items: center;
}

ul li.item-menu a .icon i{
    font-size: 30px;
}

.menu-icon {
    display: none; /* Esconde o ícone no desktop */
    font-size: 30px;
    cursor: pointer;
    position: absolute;
    top: 20px;
    left: 20px;
    color: #fff;
}

/*seção*/

section .primeira_sessao{
    display: flex;
    flex-direction: column;
    align-items: center;
}

section .titulo{
    display: flex;
    margin: 20px;
    color: rgb(9, 255, 0);
    font-size: 25px;
    margin-top: 30px;
}

section .form-indicacao{
    display: flex;
    background-color: #ebebeb;
    padding: 30px;
    width: 50%;
    height: 100%;
    border-radius: 20px;
    margin-top: 30px;
}
section .form-indicacao input {
    display: flex;
    flex-direction: column;
    width: 600px;
    height: 30px;
    padding: 5px;
    border: 1px solid #00000088;
    border-radius: 6px;
    background-color: #f8f7f8;
    font-size: 16px;
    color: #333;
    margin-bottom: 6px;
    margin-top: 6px;
}

section .form-indicacao .dados{
    font-size: 18px;
}

button{
    display: flex;
    margin-top: 10px;
    margin-bottom: 10px;
    align-items: center;
    background-color: #38d100;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    height: 30px;
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

@media (max-width: 950px) {
    /* Esconde o menu lateral por padrão no mobile */
    nav.menu-lateral {
        width: 0;
        box-shadow: none;
        transition: 0.3s;
    }

    /* Exibe o ícone de hambúrguer no mobile */
    .menu-icon {
        display: block;
    }

    /* Menu lateral expandido ao clicar no ícone de hambúrguer */
    nav.menu-lateral.expandir {
        width: 250px;
        box-shadow: 2px 0 0 #42FF00;
    }
    section .form-indicacao{
    width: 80%;
    }
    section .form-indicacao input, select {
        width: 300px;
    }
}