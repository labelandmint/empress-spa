// Reference: https://www.w3schools.com/howto/howto_js_form_steps.asp

var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function showTab(n) {
    // This function will display the specified tab of the form...
    var x = document.getElementsByClassName("es-tab");
    x[n].style.display = "block";
    //... and fix the Previous/Next buttons:
    if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }
    if (n == (x.length - 1)) {
        // document.getElementById("nextBtn").innerHTML = "Submit";
        document.getElementById("nextBtn").style.display = "none";
        document.getElementById("submitBtn").style.display = "inline";
    } else {
        // document.getElementById("nextBtn").innerHTML = "Next";
        document.getElementById("nextBtn").style.display = "inline";
        document.getElementById("submitBtn").style.display = "none";
    }
    //... and run a function that will display the correct step indicator:
    fixStepIndicator(n)
}

function nextPrev(n) {
    // This function will figure out which tab to display
    var x = document.getElementsByClassName("es-tab");
    // Hide the current tab:
    x[currentTab].style.display = "none";
    // Increase or decrease the current tab by 1:
    currentTab = currentTab + n;
    // if you have reached the end of the form...

    if (currentTab >= x.length) {
        // ... the form gets submitted:
        document.getElementsByClassName("es-multi-step-form").submit();
        return false;
    }
    // Otherwise, display the correct tab:
    showTab(currentTab);
}

function fixStepIndicator(n) {
    // This function removes the "active" class of all steps...
    var i, x = document.getElementsByClassName("es-step");
    for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
    }

    //... and adds the "active" class on the current step:
    for (j = 0; j <= n; j++) {
        x[j].className += " active";
    }
}
