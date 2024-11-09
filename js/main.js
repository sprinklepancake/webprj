document.addEventListener('DOMContentLoaded', function() {
    //loads the header   //hasan
    fetch('../components/header.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('header').innerHTML = data;
        });

    //loads the footer  //hasan
    fetch('../components/footer.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('footer').innerHTML = data;
        });

    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Login functionality will be implemented in the backend.'); //still didnt implement the backend stuff    //hasan
        });
    }

    //ripple effect i was workign on, might use later    //hasan
/*
    //ripple effect on login background(excluding form)
    const loginBackground = document.querySelector('.login-background');
    if (loginBackground) {
        loginBackground.addEventListener('mousemove', function(e) {
            const formRect = loginForm.getBoundingClientRect();

            if (
                e.clientX < formRect.left ||
                e.clientX > formRect.right ||
                e.clientY < formRect.top ||
                e.clientY > formRect.bottom
            ) {
                let ripple = document.querySelector('.ripple');

                if (!ripple) {
                    ripple = document.createElement('div');
                    ripple.classList.add('ripple');
                    loginBackground.appendChild(ripple);
                }

                ripple.style.left = `${e.clientX - loginBackground.offsetLeft - ripple.offsetWidth / 2}px`;
                ripple.style.top = `${e.clientY - loginBackground.offsetTop - ripple.offsetHeight / 2}px`;

                ripple.classList.add('active');

                setTimeout(() => {
                    ripple.classList.remove('active');
                }, 600);
            }
        });
    }
*/
});
