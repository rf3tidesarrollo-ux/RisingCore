const bttn1 = document.querySelector('#C1');
const bttn2 = document.querySelector('#C2');
const bttn3 = document.querySelector('#C3');
const cmb1 = document.querySelector('#F1');
const cmb2 = document.querySelector('#F2');
const cmb3 = document.querySelector('#F3');
var P1 = false;
var P2 = false;
var P3 = false;

bttn1.addEventListener('click', () => {
    cmb1.classList.toggle("Close");
    cmb1.classList.toggle("Open");
    
    if (P1==false) {
        $('#Arrow1').removeClass('fa-circle-down').addClass('fa-circle-up');
        P1=true;
    }else{
        $('#Arrow1').removeClass('fa-circle-up').addClass('fa-circle-down');
        P1=false;
    }
});

bttn2.addEventListener('click', () => {
    cmb2.classList.toggle("Close");
    cmb2.classList.toggle("Open");
    
    if (P2==false) {
        $('#Arrow2').removeClass('fa-circle-down').addClass('fa-circle-up');
        P2=true;
    }else{
        $('#Arrow2').removeClass('fa-circle-up').addClass('fa-circle-down');
        P2=false;
    }
});

bttn3.addEventListener('click', () => {
    cmb3.classList.toggle("Close");
    cmb3.classList.toggle("Open");
    
    if (P3==false) {
        $('#Arrow3').removeClass('fa-circle-down').addClass('fa-circle-up');
        P3=true;
    }else{
        $('#Arrow3').removeClass('fa-circle-up').addClass('fa-circle-down');
        P3=false;
    }
});