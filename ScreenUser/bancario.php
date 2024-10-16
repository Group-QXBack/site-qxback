    <?php
    header("Content-type: text/css");
    ?>

    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap');

    * {
        margin: 0;
        padding: 0;
    }
    header {
        width: 100%;
        height: 75px;
        background-color: #3a3a3a;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    header img {
        height: 48px;
        margin-top: 2px;
    }

    body {
        background-color: #161616;
        font-family: "Montserrat", sans-serif;
    }

    .content {
    display: flex;
    justify-content: center;
    align-items: center;
    height: calc(100vh - 75px);
    margin-top: 20px;   
    padding-top: 20px; 
}


form {
    background-color: #2b2b2b;
    border-radius: 8px;
    padding: 20px;
    width: 600px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
    margin-top: 20px; 
}


    fieldset {
        border: none;
    }

    legend {
        font-size: 24px;
        color: #42FF00;
        margin-bottom: 20px;
    }

    .row {
        margin-bottom: 5px;
    }
    

    label {
        color: #fff;
        display: block;
        margin-bottom: 5px;
    }

    input[type="text"],
    input[type="number"],
    select {
        width: 100%;
        padding: 10px;
        background-color: #f8f7f8;
        border: 1px solid #42FF00;
        border-radius: 4px;
        font-size: 16px;
        color: #333;
    }

    input[type="text"]:focus,
    input[type="number"]:focus,
    select:focus {
        border-color: #fff;
        outline: none;
    }

    button.btn-form {
        background-color: #42FF00;
        color: #161616;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .btn-form a {
        text-decoration: none;
        color: inherit;
    }

    button.btn-form:hover {
        background-color: #36d400;
    }

    .success-message {
    background-color: #ccffcc;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 15px; /* Espaçamento inferior */
    margin-top: 20px; /* Espaçamento superior */
    color: #005700;
}

.error-messages {
    background-color: #ffcccc; /* Cor de fundo para mensagens de erro */
    padding: 10px; /* Preenchimento */
    border-radius: 5px; /* Bordas arredondadas */
    margin-bottom: 15px; /* Espaçamento inferior */
}

.error-message {
    color: #d00; /* Cor do texto das mensagens de erro */
}


    .select2-container--default .select2-selection--single {
        background-color: #f8f7f8;
        border: 1px solid #42FF00;
        border-radius: 4px;
        height: 100%;
        width: 619px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #333;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%;
    }
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
        align-items: center;
    }

    ul li.item-menu a .icon i{
        font-size: 30px;
    }


    section .dados-perfil input:focus{
        border: none;
        outline: none;
    }
        

    .block__item:last-child {
            border-bottom: none;
    }
