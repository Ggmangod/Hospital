// script.js

document.addEventListener('DOMContentLoaded', function() {
    function openTab(tabName) {
        var i;
        var x = document.getElementsByClassName("tab");
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        document.getElementById(tabName).style.display = "block";
    }

    function sendData(url, formData, successCallback, errorCallback) {
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Network response was not ok.');
        })
        .then(data => {
            if (data.success) {
                successCallback();
            } else {
                errorCallback(data.message);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error.message);
            errorCallback('Произошла ошибка при отправке данных: ' + error.message); // Передаем текст ошибки обратно для вывода
        });
    }

    const registerForm = document.getElementById('registerForm');
    const successMessage = document.querySelector('.success-message');
    const errorMessage = document.querySelector('.error-message');

    registerForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(registerForm);

        sendData('p_.php', formData, 
            function() {
                successMessage.style.display = 'block';
                setTimeout(function() {
                    successMessage.style.display = 'none';
                    openTab('login');
                }, 3000);
            },
            function(message) {
                errorMessage.textContent = message;
                errorMessage.style.display = 'block';
            }
        );
    });

    const loginForm = document.getElementById('loginForm');

    loginForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(loginForm);

        sendData('p_.php', formData, 
            function() {
                window.location.href = 'hospital_S.html';
            },
            function(message) {
                errorMessage.textContent = message;
                errorMessage.style.display = 'block';
            }
        );
    });
});
