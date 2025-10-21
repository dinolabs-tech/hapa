<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>EDUHIVE</title>
  <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
  <link rel="icon" href="assets/img/kaiadmin/d-icon.ico" type="image/x-icon" />

  <!-- Fonts and icons -->
  <script src="assets/js/plugin/webfont/webfont.min.js"></script>
  <script>
    WebFont.load({
      google: { families: ["Public Sans:300,400,500,600,700"] },
      custom: {
        families: [
          "Font Awesome 5 Solid",
          "Font Awesome 5 Regular",
          "Font Awesome 5 Brands",
          "simple-line-icons",
        ],
        urls: ["assets/css/fonts.min.css"],
      },
      active: function () {
        sessionStorage.fonts = true;
      },
    });
  </script>

  <!-- CSS Files -->
  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/plugins.min.css" />
  <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />

  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link rel="stylesheet" href="assets/css/demo.css" />
  <link rel="stylesheet" href="components/students_style.css" />


  <style>
    /* Chatbot popup styles */
    .chatbot-popup {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 300px;
      height: 400px;
      background-color: #f9f9f9;
      /* Light gray background */
      border: 1px solid #ddd;
      /* Light gray border */
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      /* Softer shadow */
      display: none;
      /* Hidden by default */
      flex-direction: column;
      border-radius: 10px;
      /* Rounded edges */
      overflow: hidden;
      /* Hide overflow for rounded corners */
    }

    .chatbot-header {
      background-color: #343a40;
      /* Dark gray header */
      color: #fff;
      padding: 10px;
      text-align: center;
      cursor: pointer;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .chatbot-header .close-button {
      cursor: pointer;
      font-size: 1.2em;
    }

    .chatbot-body {
      padding: 10px;
      overflow-y: scroll;
      flex-grow: 1;
    }

    .chatbot-input {
      padding: 10px;
      border-top: 1px solid #ddd;
      /* Light gray border */
      background-color: #eee;
      /* Light background */
      display: flex;
      align-items: center;
    }

    .chatbot-input input[type="text"] {
      width: 70%;
      /* Adjust width for button */
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
      /* Rounded input */
      margin-right: 5px;
    }

    .chatbot-input button {
      width: 100%;
      /* Take remaining space */
      padding: 8px 12px;
      border: none;
      background-color: #007bff;
      color: #fff;
      border-radius: 5px;
      cursor: pointer;
    }

    .chatbot-icon {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 60px;
      /* Slightly larger icon */
      height: 60px;
      background-color: transparent;
      color: #007bff;
      border-radius: 50%;
      text-align: center;
      line-height: 60px;
      cursor: pointer;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
      font-size: 1.5em;
      /* Larger icon text */
    }

    .chatbot-icon:hover {
      background-color: rgba(0, 123, 255, 0.2);
      /* Light blue with transparency */
      transform: scale(1.2);
      /* Slightly larger on hover */
      transition: background-color 0.3s, transform 0.3s;
      /* Smooth transition */
    }


    .adminchatbot-input {
      padding: 10px;
      border-top: 1px solid #ddd;
      background-color: #eee;
      display: flex;
      align-items: center;
      gap: 5px;
      /* Optional spacing between input and button */
    }

    .adminchatbot-input input[type="text"] {
      width: 70%;
      /* Takes 70% of the space */
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .adminchatbot-input button {
      width: 30%;
      /* Button width reduced */
      padding: 8px 12px;
      border: none;
      background-color: #007bff;
      color: #fff;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>

</head>