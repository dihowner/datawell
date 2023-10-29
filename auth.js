const authEndpoint = "https://intelregion.peaktopup.com/auth/";
const dashboardPage = "https://intelregion.peaktopup.com/user/dashboard"



$(".loginMember").on("click", async function() {
    let userdetail = $(".userdetail").val();
    let password = $(".password").val();
    
    button = $(this);
    
    if(userdetail == "" || userdetail == undefined || password == "" || password == undefined) {
        swal.fire('Error', 'Please fill all field', 'error');
    }
    else {
        try {
            
            const csrfTokenResponse = await fetch(authEndpoint+"csrf-token");
            csrfTokenData = await csrfTokenResponse.json();
            csrfToken = csrfTokenData.csrf_token;
            
            console.log(csrfToken);
            
            let loginData = {};
            loginData["user_detail"] = userdetail
            loginData["password"] = password
            loginData["_token"] = csrfToken
            
            $.ajax({
                url: authEndpoint+"login",
                type: "POST",
                data: JSON.stringify(loginData),
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    // 'X-CSRF-TOKEN': csrfToken,
                },
                beforeSend: function() {
                    // button.html("Processing...").attr("disabled", true)
                    button.html("Processing...")
                },
                success: function(loginResponse) {
                    window.location = dashboardPage;
                },
                error: function(xhr, status, error) {
                    swal.fire(error, xhr.responseJSON.message, 'error');
                }
            })
            
            console.log(JSON.stringify(loginData))
        } catch (error) {
            swal.fire('Error', 'Error fetching authorization token, kindly refresh this page', 'error');
        }
    }
});
