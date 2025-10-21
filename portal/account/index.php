<?php 
session_start(); 
if (!isset($_SESSION['user_id'])) {
  header('Location: ../login.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
  <meta name="description" content="Account Management Page">
  <title>Account</title>
  <link rel="icon" href="favicon.ico" type="image/x-icon">

  <?php include('./header.php'); ?>
  
  <style>
    body {
      background: #80808045;
    }

    .modal-dialog.large {
      width: 80% !important;
      max-width: unset;
    }

    .modal-dialog.mid-large {
      width: 97% !important;
      max-width: unset;
    }

    #viewer_modal .btn-close {
      position: absolute;
      z-index: 999999;
      background: unset;
      color: white;
      border: unset;
      font-size: 27px;
      top: 0;
    }

    #viewer_modal .modal-dialog {
      width: 80%;
      max-width: unset;
      height: calc(90%);
      max-height: unset;
    }

    #viewer_modal .modal-content {
      background: black;
      border: unset;
      height: calc(100%);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    #viewer_modal img,
    #viewer_modal video {
      max-height: 100%;
      max-width: 100%;
    }

    @media (max-width: 768px) {
      body {
        width: 100%;
      }
    }
  </style>
</head>

<body>
  <?php include 'topbar.php'; ?>
  <?php include 'navbar.php'; ?>

  <div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-body text-white"></div>
  </div>

  <main id="view-panel">
    <?php $page = isset($_GET['page']) ? $_GET['page'] : 'home'; ?>
    <?php include $page . '.php'; ?>
  </main>

  <div id="preloader"></div>
  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

  <!-- Confirm Modal -->
  <div class="modal fade" id="confirm_modal" role="dialog">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmation</h5>
        </div>
        <div class="modal-body">
          <div id="delete_content"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="confirm">Continue</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Universal Modal -->
  <div class="modal fade" id="uni_modal" role="dialog">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
        </div>
        <div class="modal-body"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="submit" onclick="$('#uni_modal form').submit()">Save</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Viewer Modal -->
  <div class="modal fade" id="viewer_modal" role="dialog">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
        <img src="" alt="">
      </div>
    </div>
  </div>

  <script>
    window.start_load = function () {
      $('body').prepend('<div id="preloader2"></div>');
    }

    window.end_load = function () {
      $('#preloader2').fadeOut('fast', function () {
        $(this).remove();
      });
    }

    window.viewer_modal = function ($src = '') {
      start_load();
      var ext = $src.split('.').pop().toLowerCase();
      var view = (ext === 'mp4') 
        ? $("<video src='" + $src + "' controls autoplay></video>") 
        : $("<img src='" + $src + "' />");

      $('#viewer_modal .modal-content video, #viewer_modal .modal-content img').remove();
      $('#viewer_modal .modal-content').append(view);
      $('#viewer_modal').modal({
        show: true,
        backdrop: 'static',
        keyboard: false,
        focus: true
      });
      end_load();
    }

    window.uni_modal = function ($title = '', $url = '', $size = "") {
      start_load();
      $.ajax({
        url: $url,
        error: err => {
          console.log(err);
          alert("An error occurred");
        },
        success: function (resp) {
          if (resp) {
            $('#uni_modal .modal-title').html($title);
            $('#uni_modal .modal-body').html(resp);
            if ($size !== '') {
              $('#uni_modal .modal-dialog').addClass($size);
            } else {
              $('#uni_modal .modal-dialog').attr("class", "modal-dialog modal-md");
            }
            $('#uni_modal').modal({
              show: true,
              backdrop: 'static',
              keyboard: false,
              focus: true
            });
            end_load();
          }
        }
      });
    }

    window._conf = function ($msg = '', $func = '', $params = []) {
      $('#confirm_modal #confirm').attr('onclick', $func + "(" + $params.join(',') + ")");
      $('#confirm_modal .modal-body').html($msg);
      $('#confirm_modal').modal('show');
    }

    window.alert_toast = function ($msg = 'TEST', $bg = 'success') {
      $('#alert_toast').removeClass('bg-success bg-danger bg-info bg-warning');
      $('#alert_toast').addClass('bg-' + $bg);
      $('#alert_toast .toast-body').html($msg);
      $('#alert_toast').toast({ delay: 3000 }).toast('show');
    }

    $(document).ready(function () {
      $('#preloader').fadeOut('fast', function () {
        $(this).remove();
      });

      $('.datetimepicker').datetimepicker({
        format: 'Y/m/d H:i',
        startDate: '+3d'
      });

      $('.select2').select2({
        placeholder: "Please select here",
        width: "100%"
      });
    });
  </script>
</body>
</html>
