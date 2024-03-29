const themeButton = document.getElementById("theme-button");
const body = document.querySelector("body");
const eptDayBackground = "/online_ticket_purchase/assets/images/trainPerspective.jpg";
const eptNightBackground = "/online_ticket_purchase/assets/images/trainPerspective.jpg";
const darkTheme = "dark-theme";
const iconTheme = "uil-sun";



// Previously selected topic (if user selected)
const selectedTheme = localStorage.getItem("selected-theme");
const selectedIcon = localStorage.getItem("selected-icon");

// We obtain the current theme that the interface has by validating the dark-theme class
const getCurrentTheme = () =>
    document.body.classList.contains(darkTheme) ? "dark" : "light";
const getCurrentIcon = () =>
    themeButton.classList.contains(iconTheme) ? "uil-moon" : "uil-sun";

// We validate if the user previously chose a topic
if (selectedTheme) {

    // If the validation is fulfilled, we ask what the issue was to know if we activated or deactivated the dark
    document.body.classList[selectedTheme === "dark" ? "add" : "remove"](
        darkTheme



    );
    
    themeButton.classList[selectedIcon === "uil-moon" ? "add" : "remove"](
        iconTheme

    );
}

// Activate / deactivate the theme manually with the button
themeButton.addEventListener("click", () => {
   
    // Add or remove the dark / icon theme
    document.body.classList.toggle(darkTheme);
    themeButton.classList.toggle(iconTheme);

    if (body.style.backgroundImage === eptDayBackground) {
        body.style.backgroundImage = eptNightBackground;
    } else {
        body.style.backgroundImage = eptDayBackground;
    }
  
     
      
    


});

   