<footer class="footer mt-auto py-3 bg-light">
    <div class="container">
        <span class="text-muted">&copy;Twitter Clone 2022</span>
    </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
</script>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
    crossorigin="anonymous"></script>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" id="loginModalTitle">Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" id="loginAlert"></div>
                <form>
                    <input type="hidden" name="loginActive" id="loginActive" value="1">
                    <fieldset class="from-group">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Email
                                address</label>
                            <input type="email" class="form-control" id="email" name="email"
                                aria-describedby="emailHelp" placeholder="Enter email address">
                            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                        </div>
                    </fieldset>

                    <fieldset class="form-group">
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input name="password" type="password" class="form-control" id="password"
                                placeholder="Enter password">
                        </div>
                    </fieldset>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Remember me</label>
                    </div>
                    <div class="modal-footer">
                        <a href="#" id="toggleLogin">Sign Up</a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="loginSignupButton">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// alert("yoo!");

$("#toggleLogin").click(function() {
    if ($("#loginActive").val() == "1") {

        $("#loginActive").val("0");
        $("#loginModalTitle").html("Sign Up");
        $("#loginSignupButton").html("Sign Up");
        $("#toggleLogin").html("Login");


    } else {

        $("#loginActive").val("1");
        $("#loginModalTitle").html("Login");
        $("#loginSignupButton").html("Login");
        $("#toggleLogin").html("Sign up");
    }
})

$("#loginSignupButton").click(function() {
    $.ajax({
        type: "POST",
        url: "actions.php?action=loginSignup",
        data: "email=" + $("#email").val() + "&password=" + $("#password").val() + "&loginActive=" + $(
            "#loginActive").val(),
        success: function(result) {
            if (result == 1) {

                window.location.assign("http://localhost/twitterclone/");

            } else {
                $("#loginAlert").html(result).show();
            }
        }
    })
})

$(".toggleFollow").click(function() {
    var id = $(this).attr("data-userId");
    $.ajax({
        type: "POST",
        url: "actions.php?action=toggleFollow",
        data: "userId=" + $(this).attr("data-userId"),
        success: function(result) {
            if (result == "1") {
                $("a[data-userId='" + id + "']").html("Follow");
            } else if (result == '2') {
                $("a[data-userId='" + id + "']").html("Unfollow");
            }
        }
    })
})

$("#postTweetButton").click(function() {
    $.ajax({
        type: "POST",
        url: "actions.php?action=postTweet",
        data: "tweetContent=" + $("#tweetContent").val(),
        success: function(result) {

            if (result == "1") {

                $("#tweetSuccess").show();
                $("#tweetFail").hide();
                window.location.assign("http://localhost/twitterclone/");


            } else if (result != "") {
                $("#tweetFail").html(result).show()
                $("#tweetSuccess").hide();

            }
        }
    })
})
</script>

</body>

</html