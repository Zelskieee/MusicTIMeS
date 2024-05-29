function confirmLogout() {
    var confirmLogout = confirm("Are you sure you want to logout?");
    if (confirmLogout) {
        // Destroy PHP session
        fetch('controller/logout.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'logout=true',
        })
        .then(response => {
            if (response.ok) {
                // Redirect to logout page
                window.location.href = "../index.php";
            } else {
                console.error('Failed to logout');
            }
        })
        .catch(error => {
            console.error('Error occurred while trying to logout:', error);
        });
    }
}

function setActive(event, index, path) {
    event.preventDefault();

    const links = document.querySelectorAll('nav a');
    links.forEach(function(link) { link.classList.remove('active'); });
    links[index].classList.add('active');

    console.log(path)
    window.location.href = path;
}

function toggleLanguage() {
    var checkbox = document.getElementById('language_mode');
    var language = document.getElementsByName('lang')[0].value;
    let _language = ""
    if (checkbox.checked && language != 'my') {
        _language = "my"
    } else {
        _language = 'en'
    }
    const formData = new FormData();
    formData.append('lang', _language);

    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        window.location.reload();
        return response.text();
    })
}

function handleCart(){
    var cartSidebar = document.querySelector('.cart-sidebar');
    if (cartSidebar.classList.contains('open')) {
        // If cart sidebar is open, close it
        cartSidebar.classList.remove('open');
    } else {
        // If cart sidebar is closed, open it
        cartSidebar.classList.add('open');
    }
};