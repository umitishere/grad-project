<!-- Footer -->
<footer class="text-center text-lg-start bg-light text-muted margin-top-30">
  <!-- Section: Social media -->
  <section
    class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom"
  >
    <!-- Left -->
    <div class="me-5 d-none d-lg-block">
      <span>Icons:</span>
    </div>
    <!-- Left -->

    <!-- Right -->
    <div>
      <a href="" class="me-4 text-reset">
        <i class="fab fa-facebook-f"></i>
      </a>
      <a href="" class="me-4 text-reset">
        <i class="fab fa-twitter"></i>
      </a>
      <a href="" class="me-4 text-reset">
        <i class="fab fa-google"></i>
      </a>
      <a href="" class="me-4 text-reset">
        <i class="fab fa-instagram"></i>
      </a>
    </div>
    <!-- Right -->
  </section>
  <!-- Section: Social media -->

  <!-- Section: Links  -->
  <section class="">
    <div class="container text-center text-md-start mt-5">
      <!-- Grid row -->
      <div class="row mt-3">
        <!-- Grid column -->
        <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
          <!-- Content -->
          <h6 class="text-uppercase fw-bold mb-4">
            <i class="fas fa-gem me-3"></i>Grad Project
          </h6>
          <p>Site hakkında bir açıklama yazısı.</p>
        </div>
        <!-- Grid column -->

        <!-- Grid column -->
        <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
          <!-- Links -->
          <h6 class="text-uppercase fw-bold mb-4">
            Bazı Linkler
          </h6>
          <p>
            <a href="#!" class="text-reset">Link 1</a>
          </p>
          <p>
            <a href="#!" class="text-reset">Link 2</a>
          </p>
          <p>
            <a href="#!" class="text-reset">Link 3</a>
          </p>
          <p>
            <a href="#!" class="text-reset">Link 4</a>
          </p>
        </div>
        <!-- Grid column -->

        <!-- Grid column -->
        <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
          <!-- Links -->
          <h6 class="text-uppercase fw-bold mb-4">
            Bazı Başlıklar
          </h6>
          <p>
            <a href="#!" class="text-reset">Birinci Yazının Başlığı Budur</a>
          </p>
          <p>
            <a href="#!" class="text-reset">İkinci Yazının Başlığı Budur</a>
          </p>
          <p>
            <a href="#!" class="text-reset">Üçüncü Yazının Başlığı Budur</a>
          </p>
          <p>
            <a href="#!" class="text-reset">Dördüncü Yazının Başlığı Budur</a>
          </p>
        </div>
        <!-- Grid column -->

        <!-- Grid column -->
        <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
          <!-- Links -->
          <h6 class="text-uppercase fw-bold mb-4">
            İletişim
          </h6>
          <p><i class="fas fa-home me-3"></i> İstanbul</p>
          <p>
            <i class="fas fa-envelope me-3"></i>
            info@siteadi.com
          </p>
          <p><i class="fas fa-phone me-3"></i> + 90 555 555 55 55</p>
        </div>
        <!-- Grid column -->
      </div>
      <!-- Grid row -->
    </div>
  </section>
  <!-- Section: Links  -->

  <!-- Copyright -->
  <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
    TELİF HAKKI © 2021 - <span class="text-reset fw-bold">Site Adı</span>
  </div>
  <!-- Copyright -->
</footer>
<!-- Footer -->

<script>

$(document).ready(function() {

    $("#searchUser").on('shown.bs.modal', function(){
        $("#search").focus();
    });


    //On pressing a key on "Search box" in "search.php" file. This function will be called.
    $("#search").keyup(function() {

        //Assigning search box value to javascript variable named as "name".
        var name = $('#search').val();

        //Validating, if "name" is empty.
        if (name == "") {
            //Assigning empty value to "display" div in "search.php" file.
            $("#display").html("");
        } else {

            //AJAX is called.
            $.ajax({

                //AJAX type is "Post".
                type: "POST",

                //Data will be sent to "ajax.php".
                url: "ajax.php",

                //Data, that will be sent to "ajax.php".
                data: {

                    //Assigning value of "name" into "search" variable.
                    search: name
                },

                // If result found, this funtion will be called.
                success: function(html) {

                    //Assigning result to "display" div in "search.php" file.
                    $("#display").html(html).show();
                }
           });
       }
   });
});

</script>
</body>
</html>
