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

/* Menu Lateral*/

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
    align-items: center;
}

ul li.item-menu a .icon i{
    font-size: 30px;
}

/*Tabela*/

h1 {
    text-align: center;
    margin: 10px 0;
    color: #000;
}

h2 {
    text-align: center;
    margin: 20px 0;
    color: #42FF00;
}

p {
    text-align: center;
    margin: 10px 0;
    color: #00000; 
}

form {
    background-color: #ebebeb;
    border-radius: 8px;
    padding: 20px;
    width: 70%;
    margin: 20px auto;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
}

form .resgatar{
    display: flex;
    align-items: center;
    flex-direction: column;
}

label {
    display: block;
    margin-bottom: 10px;
    color: #00000;
}

input[type="text"] {
    width: 95%;
    padding: 10px;
    background-color: #f8f7f8;
    border: 1px solid #42FF00;
    border-radius: 4px;
    font-size: 16px;
    color: #333;
}

button {
    background-color: #42FF00;
    color: #161616;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    margin-top: 10px;
    transition: background-color 0.3s;
    width: 100%;
}

.dados-usuario{
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    background-color: #FFF;
    border-radius: 6px;
    border: 1px solid ##777777;
    padding: 30px 20px 20px;
    text-align: left;
    vertical-align: top;
    min-height: 160px;
}

.dados-usuario .conta-bancaria, .endereco-usuario{
    width: 50%;
}

.dados-usuario p{
    color: #707070;
    text-align: left;
}
.dados-usuario .btn-resgate{
    background-color: #FFF;
    font-size: 13px;
    line-height: 1.5em;
    letter-spacing: 0px;
    font-weight: normal;
    color: #42FF00;
    display: inline-block;
    font-weight: 700;
    float: right;
}
.dados-usuario .endereco-usuario{
    margin-left: 20px;
}

table {
    width: 80%;
    border-collapse: collapse;
    margin: 20px auto;
    background-color: #2b2b2b;
    border-radius: 8px;
    overflow: hidden;
}

th, td {
    padding: 8px 12px;
    text-align: left;
    border-bottom: 2px solid #2cab00;
    font-size: 14px;
    background-color:   #dddddd;
}

th {
    background-color: #dddddd;
    color: #00000;
}

tr:nth-child(even) {
    background-color: #fff;
}

tr:hover {
    background-color: #3a3a3a;
}

button{
    background-color: #42FF00;
    color: #161616;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    margin-top: 10px;
    transition: background-color 0.3s;
    width: 100%;
    text-decoration: none;
}

#telaSemResgate {
    text-align: center; 
    padding: 20px;
    background-color: var(--cinzaEscuro);
    border-radius: 8px;
    margin: 5px;

}

.mascote {
    max-width: 80%; 
    max-height: 300px; 
    height: auto; 
    margin-bottom: 0.25px; 
}

.paragrafo {
    color: #fff; 
    margin: 10px 0;/
}
