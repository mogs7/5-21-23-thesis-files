const allSideMenu = document.querySelectorAll(' .top-nav-admin .nav.nav-tabs.nav-justified li');

allSideMenu.forEach(item => {
    const li = item.parentElement;

    item.addEventListener('click', function () {
        allSideMenu.forEach(i => {
            i.parentElement.classList.remove('active');
        })
        li.classList.add('active');
    })

    // Check if the current URL matches the link's href attribute
    if (window.location.href.includes(item.getAttribute('href'))) {
        li.classList.add('active');
    }
});




// TOGGLE SIDEBAR
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
	sidebar.classList.toggle('hide');
})







const searchButton = document.querySelector('#content nav form .form-input button');
const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
const searchForm = document.querySelector('#content nav form');

searchButton.addEventListener('click', function (e) {
	if(window.innerWidth < 576) {
		e.preventDefault();
		searchForm.classList.toggle('show');
		if(searchForm.classList.contains('show')) {
			searchButtonIcon.classList.replace('bx-search', 'bx-x');
		} else {
			searchButtonIcon.classList.replace('bx-x', 'bx-search');
		}
	}
})





if(window.innerWidth < 768) {
	sidebar.classList.add('hide');
} else if(window.innerWidth > 576) {
	searchButtonIcon.classList.replace('bx-x', 'bx-search');
	searchForm.classList.remove('show');
}


window.addEventListener('resize', function () {
	if(this.innerWidth > 576) {
		searchButtonIcon.classList.replace('bx-x', 'bx-search');
		searchForm.classList.remove('show');
	}
})



const switchMode = document.getElementById('switch-mode');

switchMode.addEventListener('change', function () {
	if(this.checked) {
		document.body.classList.add('dark');
	} else {
		document.body.classList.remove('dark');
	}
})

const popupBtn = document.getElementById("filter-icon");
const popup = document.getElementById("popup");

popupBtn.addEventListener("mousedown", function() {
  popup.style.display = popup.style.display === "none" ? "block" : "none";
});


/*//$(document).ready(function() {
    // function to fetch data from server and update the table
    //function updateTable() {
        //$.ajax({
            url: "includes/registered.enforcers.inc.php",
            dataType: "html",
            success: function(data) {
                $("#table-body").empty().html(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error: " + textStatus + ": " + errorThrown);
            }
        });
    }
    // call the updateTable function when the page loads and when the filter is submitted

    updateTable();
    $("table-body").submit(function(event) {
        event.preventDefault();
        updateTable();
    });
});*/

