<?php
header("Content-type: text/css");
?>

@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

* {
    padding: 0;
    margin: 0;
    box-sizing: border-box;
    font-family: 'Montserrat', sans-serif;
}

body {
    width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #001100;
}

.container {
    width: 80%;
    height: 80vh;
    display: flex;
    box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.212);
}

.form-image {
    width: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 2rem;
}

.form-image img {
    width: 34rem;
}

.form {
    width: 50%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: #44ff00f8;
    box-shadow: 0px 10px 40px #3ad302f8;
    border-radius: 20px;
    padding: 3rem;
}

.form-header {
    margin-bottom: 0.6rem;
    display: flex;
    justify-content: space-between;
}

.login-button {
    display: flex;
    align-items: center;
}

.login-button button {
    border: none;
    background-color: #fff;
    padding: 0.6rem 3rem;
    border-radius: 5px;
    cursor: pointer;
}

.login-button button:hover {
    background-color: #ffffffbb;
}

.login-button button a {
    text-decoration: none;
    font-weight: 700;
    font-size: 15px;
    color: #000000;
}

.form-header h1::after {
    content: '';
    display: block;
    width: 5rem;
    height: 0.3rem;
    background-color: #fff;
    margin: 0 auto;
    position: absolute;
    border-radius: 10px;
}

.input-group {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    padding: 1rem 0;
}

.input-group .input-box label {
    font-size: 15px;
}

.input-box {
    display: flex;
    flex-direction: column;
    margin-bottom: 1rem;
}

.input-box input {
    margin: 0.6rem 0;
    padding: 0.9rem 2rem;
    border: none;
    border-radius: 10px;
    box-shadow: 1px 1px 6px #0000001c;
    font-size: 0.8rem;
}

.input-box input:hover {
    background-color: #ffffffbb;
}

.input-box input:focus-visible {
    outline: 1px solid #000000;
}

.input-box label,
.genero-title h6 {
    font-size: 0.75rem;
    font-weight: 600;
    color: #000000c0;
}

.input-box input::placeholder {
    color: #000000be;
}

.genero-group {
    display: flex;
    justify-content: space-between;
    margin-top: 0.62rem;
    padding: 0 .5rem;
}

.genero-input {
    display: flex;
    align-items: center;
}

.genero-inputs .genero-title h6 {
    font-size: 15px;
}

.genero-input label {
    font-size: 0.95rem;
    font-weight: 600;
    color: #000000c0;
}

.continue-button button {
    width: 100%;
    margin-top: 0.6rem;
    border: none;
    background-color: #fff;
    padding: 0.62rem;
    border-radius: 5px;
    cursor: pointer;
}

.continue-button button:hover {
    background-color: #ffffffbb;
}

.continue-button button a {
    text-decoration: none;
    font-size: 0.93rem;
    font-weight: 700;
    font-size: 18px;
    color: #000000;
}

@media screen and (max-width: 1330px) {
    .form-image {
        display: none;
    }
    .container {
        width: 50%;
    }
    .form {
        width: 100%;
    }
}

@media screen and (max-width: 1064px) {
    .container {
        width: 90%;
        height: auto;
    }
    .input-group {
        flex-direction: column;
        z-index: 5;
        padding-right: 5rem;
        max-height: 10rem;
        overflow-y: scroll;
        flex-wrap: nowrap;
    }
    .gender-inputs {
        margin-top: 2rem;
    }
    .gender-group {
        flex-direction: column;
    }
    .gender-title h6 {
        margin: 0;
    }
    .gender-input {
        margin-top: 0.5rem;
    }
}