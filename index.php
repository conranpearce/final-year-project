<?php
    include_once 'header.php';
?>
    <section class="index-intro">

        <?php 
            if (isset($_SESSION["useruid"])) {
                echo "<p>Hello there " . $_SESSION["useruid"] . "</p>";
            } 
        ?>

        <h1>This is an introduction</h1>

        <?php
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.carbonintensity.org.uk/intensity/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            // echo $response;

            $decoded = json_decode($response, true);
            echo "<h2>" . $decoded['data'][0]['intensity']['index'] . "</h2>";
        ?>

        <!-- Testing calling javascript -->
        <script>
            function callAPI() {
                var requestOptions = {
                method: 'GET',
                redirect: 'follow'
                };

                fetch("https://api.carbonintensity.org.uk/intensity/", requestOptions)
                    .then(response => response.json())
                    .then((result) => {
                        console.log(result);
                        console.log(result['data'][0]['intensity']['index']);
                        return result;
                    })
                    .catch(error => console.log('error', error));
            }
        </script>

        <!-- Calling javascript function above-->
        <script type="text/javascript"> 
            callAPI();
        </script>

    </section>

<?php
    include_once 'footer.php';
?>