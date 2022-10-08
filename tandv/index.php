<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
      <div class="row mt-5">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <h3 class="card-title text-center">T-And-V Encryption</h3>
              <?php
                $encryptedText = "";
                $hold1 = "";
                if (isset($_POST['encrypt'])) {
                  $text = $_POST['text'];
                  if (strlen($text) < 8) {
                    echo "
                      <script>
                        alert('Text must include more characters than 8');
                      </script>
                    ";
                  } else {
                    include 'hiding.php';
                    $hide = new Hiding($text);
                    $hold1 = $text;
                    $encryptedText = $hide->hide();
                  }
                }
              ?>
              <form method="post">
                <div class="input-group mb-3 mt-5">
                  <input name="text" id="value1" type="text" class="form-control" placeholder="Enter text" autocomplete="off" value='<?= $hold1; ?>'>
                  <button name="encrypt" class="btn btn-primary">Encrypt</button>
                </div>
                <div class="input-group mb-3">
                  <input type="text" class="form-control" placeholder="Encrypted text" id="copyEncrypt" value="<?= htmlspecialchars($encryptedText, ENT_QUOTES | ENT_HTML5); ?>" readonly>
                  <button class="btn btn-primary" type="button" onclick="copy1()">Copy</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <h3 class="card-title text-center">T-And-V Decryption</h3>
              <?php
                $decryptedText = "";
                $hold2 = "";
                if (isset($_POST['decrypt'])) {
                  $text = $_POST['textDecrypt'];
                  if (strlen($text) < 8) {
                    echo "
                      <script>
                        alert('Text must include more characters than 8');
                      </script>
                    ";
                  } else {
                    include 'showing.php';
                    $show = new Showing($text);
                    $hold2 = $text;
                    $decryptedText = $show->show();
                  }
                }
              ?>
              <form class="mt-5" method="post">
                <div class="input-group mb-3 mt-5">
                  <input name="textDecrypt" type="text" class="form-control" placeholder="Enter encrypted text" autocomplete="off" value="<?= htmlspecialchars($hold2, ENT_QUOTES | ENT_HTML5); ?>">
                  <button name="decrypt" class="btn btn-primary">Decrypt</button>
                </div>
                <div class="input-group mb-3">
                  <input type="text" class="form-control" placeholder="Decrypted text" id="copyDecrypt" value='<?= $decryptedText; ?>' readonly>
                  <button class="btn btn-primary" type="button" onclick="copy2()">Copy</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.1.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
      function copy1() {
        var copyText = document.getElementById("copyEncrypt");
        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices
        navigator.clipboard.writeText(copyText.value);
      }
      function copy2() {
        var copyText = document.getElementById("copyDecrypt");
        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices
        navigator.clipboard.writeText(copyText.value);
      }
    </script>
  </body>
</html>