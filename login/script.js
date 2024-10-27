//Function will toggle between sign-in and sign-up pages
const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

registerBtn.addEventListener('click', () =>{
    container.classList.add("active");
});

loginBtn.addEventListener('click', () =>{
    container.classList.remove("active");
});


//Function will redirect to respective dashboards
$('#loginForm').submit(function(e) {
    e.preventDefault();
    var formData = $(this).serialize();
    $.ajax({

        url: 'login.php',
        type: 'POST',
        data: formData, 
        success: function(response) {
            if (response.success) {
                switch (response.role) {
                    case 'admin':
                        window.location.href = '../admin/index.php';
                        break;

                        default: console.error('Invalid role:', response.role);
                }
            } else {
                alert('Invalid Login Information');
            }
        },
        error: function(xhr,status,error) {
            console.error('Error',error);
        }
    });
});