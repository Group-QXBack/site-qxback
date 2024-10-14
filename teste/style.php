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
    display: grid;
    gap: 16px;
    margin: 20px auto;
    padding: 16px;
    background-color: #ebebeb;
    border-radius: 8px;
    width: 900px;
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
        margin-bottom: 15px;
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

    .select2-container--default .select2-selection--single {
        background-color: #f8f7f8;
        border: 1px solid #42FF00;
        border-radius: 4px;
        height: 38px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #333;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
