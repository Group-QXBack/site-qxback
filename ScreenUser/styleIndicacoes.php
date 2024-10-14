<?php
header("Content-type: text/css");
?>

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
    width: 73px;
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
.container {
    margin-left: 100px;
    max-width: 1200px;
    padding: 0 20px;
}

h1 {
    display: flex;
    justify-content: center;
    margin-bottom: 30px;
    margin-top: 40px;
    font-size: 2.5em;
    color: #42FF00;
}

hr {
    border: 0;
    height: 1px;
    background-color: rgb(54, 54, 54);
    margin: 20px auto;
    width: 80%;
}

table {
    width: 100%;
    margin: 20px 0;
    border-collapse: collapse;
    background-color: rgba(0, 0, 0, 0.3);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #42FF00;
    background-color: #ebebeb;
}

    th {
        background-color: rgba(0, 0, 0, 0.5);
        color: #fff;
    }

    tr:nth-child(even) {
        background-color: rgba(255, 255, 255, 0.1);
    }

    tr:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }

    .status-em-andamento {
        color: #ffcc00; 
    }

    .status-confirmada {
        color: #28a745; 
    }

    .status-rejeitada {
        color: #dc3545;
    }

    .valor-pendente {
        color: #f39c12; 
    }

    .actions-column a, .btn-acao {
        text-decoration: none;
        color: #fff;
        padding: 10px;
        background-color: rgba(0, 0, 0, 0.3);
        border-radius: 5px;
        transition: background-color 0.3s;
        display: inline-block;
        margin: 0 5px;
        font-size: 14px;
        text-align: center;
    }

    .btn-acao:hover {
        background-color: rgb(75, 198, 133);
    }

    .no-data-message {
        text-align: center;
        color: #aaa;
        padding: 20px;
        font-size: 16px;
    }

    .bottom-buttons {
        margin-top: 20px;
        text-align: center;
    }

    .bottom-buttons a {
        padding: 10px 20px;
        background-color: rgba(0, 0, 0, 0.3);
        color: #fff;
        border-radius: 5px;
        transition: background-color 0.3s;
        font-size: 16px;
        text-decoration: none;
        display: inline-block;
    }

    .bottom-buttons a:hover {
        background-color: rgb(75, 198, 133);
    }

    footer {
        padding: 20px;
        background-color: rgba(0, 0, 0, 0.3);
    }

    .expand-btn {
        cursor: pointer;
        font-size: 18px;
        color: #007bff;
        border: none;
        background: none;
    }

    .details-row {
        display: none;
        background-color: rgba(0, 0, 0, 0.5);
        color: #fff;
    }

    .details-row td {
        padding: 10px;
        border: none;
    }

    .details-row ul {
        list-style-type: none;
        padding: 0;
    }

    .details-row li {
        margin-bottom: 5px;
    }