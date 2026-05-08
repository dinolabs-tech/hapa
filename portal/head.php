<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>EDUHIVE</title>
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

  <meta name="description" content="EduHive Learning Management System" />
  <meta name="description" content="EduHive LMS" />
  <link rel="icon" href="assets/img/kaiadmin/d-icon.ico" type="image/x-icon" />
  <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
  <!-- Fonts and icons -->
  <script src="assets/js/plugin/webfont/webfont.min.js"></script>
  <script>
    WebFont.load({
      google: {
        families: ["Public Sans:300,400,500,600,700"]
      },
      custom: {
        families: [
          "Font Awesome 5 Solid",
          "Font Awesome 5 Regular",
          "Font Awesome 5 Brands",
          "simple-line-icons",
        ],
        urls: ["assets/css/fonts.min.css"],
      },
      active: function() {
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

  <!-- Chart.js Library -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->


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

    .alumni-modal {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.6);
}

.alumni-modal-content {
  background: #fff;
  width: 60%;
  margin: 8% auto;
  padding: 20px;
  border-radius: 10px;
  position: relative;
  max-height: 80vh;
  overflow-y: auto;
}

.close-modal {
  position: absolute;
  right: 15px;
  top: 10px;
  font-size: 22px;
  cursor: pointer;
}
  </style>


<!-- Dinopilot chatbot script -->
<style>

        *{
            box-sizing:border-box;
            margin: 0;
            padding: 0;
        }

        body{
            background: #f0f2f5;
            font-family: Arial, sans-serif;
            min-height: 200vh;
        }

        /* Chat Bubble Button */
        #chatBubble {
            position: fixed;
            bottom: 45px;
            right: 25px;
            width: 65px;
            height: 65px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 50%;
            box-shadow: 0 6px 25px rgba(37, 99, 235, 0.4);
            cursor: pointer;
            z-index: 999999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border: none;
        }

        #chatBubble:hover {
            transform: scale(1.08);
            box-shadow: 0 8px 30px rgba(37, 99, 235, 0.55);
        }

        #chatBubble svg {
            width: 30px;
            height: 30px;
            fill: white;
        }

        /* Chat Widget Window */
        #chatWidget {
            position: fixed;
            bottom: 100px;
            right: 25px;
            width: 400px;
            max-width: calc(100vw - 30px);
            height: 600px;
            max-height: calc(100vh - 120px);
            background: #111827;
            border-radius: 16px;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.35);
            z-index: 999998;
            display: none;
            flex-direction: column;
            overflow: hidden;
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #chatWidget.active {
            display: flex;
            transform: translateY(0);
            opacity: 1;
        }

        .widget-header {
            background: #1e293b;
            padding: 16px 20px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #334155;
            font-size: 17px;
            font-weight: 600;
        }

        .widget-header .close-btn {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s;
            font-size: 14px;
        }

        .widget-header .close-btn:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        #chat{
            flex:1;
            padding:20px;
            overflow-y:auto;
            background: #0f172a;
        }

        .message{
            padding:15px;
            border-radius:12px;
            margin-bottom:15px;
            line-height:1.7;
            max-width:85%;
            font-size: 14px;
        }

        .user{
            background:#2563eb;
            margin-left:auto;
            color: white;
        }

        .ai{
            background:#1e293b;
            border:1px solid #334155;
            color: white;
        }

        .input-area{
            padding:15px;
            border-top:1px solid #334155;
            background:#111827;
        }

        textarea{
            width:100%;
            height:70px;
            border:none;
            outline:none;
            border-radius:10px;
            padding:15px;
            resize:none;
            font-size:14px;
            margin-bottom:10px;
            background:#1e293b;
            color:white;
        }

        button{
            width:100%;
            padding:12px;
            border:none;
            border-radius:10px;
            background:#22c55e;
            color:white;
            font-size:15px;
            cursor:pointer;
            transition:0.2s;
        }

        button:hover{
            opacity:0.9;
        }

        .typing{
            opacity:0.7;
            font-style:italic;
        }

        /* Pulse animation */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.4;
            }
        }

        /* Responsive */
        @media (max-width: 480px) {
            #chatWidget {
                right: 15px;
                left: 15px;
                width: auto;
                bottom: 90px;
            }

            #chatBubble {
                right: 15px;
                bottom: 15px;
            }
        }

    </style>
</head>