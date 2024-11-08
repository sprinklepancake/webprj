document.addEventListener('DOMContentLoaded', function() {
    //loads the header
    fetch('../components/header.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('header').innerHTML = data;
        });

    //loads the footer
    fetch('../components/footer.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('footer').innerHTML = data;
        });

    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Login functionality will be implemented in the backend.'); //still didnt implement the backend stuff
        });
    }

    //ripple effect i was workign on, might use later 
/*
    //ripple effect on login background(excluding form)
    const loginBackground = document.querySelector('.login-background');
    if (loginBackground) {
        loginBackground.addEventListener('mousemove', function(e) {
            const formRect = loginForm.getBoundingClientRect();

            // Check if mouse is outside the form area
            if (
                e.clientX < formRect.left ||
                e.clientX > formRect.right ||
                e.clientY < formRect.top ||
                e.clientY > formRect.bottom
            ) {
                let ripple = document.querySelector('.ripple');

                // Create the ripple element if it doesn't exist
                if (!ripple) {
                    ripple = document.createElement('div');
                    ripple.classList.add('ripple');
                    loginBackground.appendChild(ripple);
                }

                // Position the ripple based on mouse coordinates
                ripple.style.left = `${e.clientX - loginBackground.offsetLeft - ripple.offsetWidth / 2}px`;
                ripple.style.top = `${e.clientY - loginBackground.offsetTop - ripple.offsetHeight / 2}px`;

                // Activate the ripple
                ripple.classList.add('active');

                // Remove the active class after animation
                setTimeout(() => {
                    ripple.classList.remove('active');
                }, 600);
            }
        });
    }
*/
});
