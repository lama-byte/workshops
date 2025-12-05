document.addEventListener("DOMContentLoaded", function (){
      //
      let email = document.getElementById("email");
      let pass = document.getElementById("password");
      let loginBtn = document.getElementById("loginBtn");

    document.getElementById("loginForm").addEventListener ("submit", function (e){
        //email format validation 
     const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email.value)){
          alert("please enter a valid email address");
          e.preventDefault();
        }
         //check password length
        else if (pass.value.length < 8){
          pass.focus;
          alert("password should be at least 8 characters!");
          e.preventDefault();
        }
        else {
        //after doing php should check if it's working --------------------
        loginBtn.textContent = "Logging in..";
        loginBtn.disabled = true;
        }

      });

    });
