<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Project Ayu | Your Home Health Assistant</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />
    <style>
      @font-face {
        font-family: "font";
        src: url("../AYU/resources/font.ttf") format("truetype");
        font-weight: normal;
        font-style: normal;
      }

      * {
        font-family: "font", sans-serif;
      }

      body {
        background: linear-gradient(135deg, #3a0d23, #000000);
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
      }

      .gradient-box {
        width: 98%;
        max-width: 98%;
        min-height: 95vh;
        border-radius: 20px;
        background: rgba(0, 0, 0, 0.6);
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.6);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        padding: 40px;
        position: relative;
        overflow: hidden;
      }

      .logo {
        position: absolute;
        top: 10px;
        left: 10px;
        width: 70px;
        height: auto;
      }

      .center-top-logo {
        position: absolute;
        top: 120px;
        left: 50%;
        transform: translateX(-50%);
        width: 120px;
        height: auto;
      }

      .chat-container {
        flex-grow: 1;
        overflow-y: auto;
        width: 100%;
        padding: 10px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        max-height: calc(100% - 80px); /* Keep space for input-group */
      }

      .message {
        padding: 10px 15px;
        border-radius: 10px;
        margin: 5px 0;
        max-width: 80%;
        position: relative;
      }

      .sender {
        background-image: linear-gradient(to right, #e11f59, #1c0a11);
        border-radius: 20px;
        color: white;
        align-self: flex-end;
      }

      .receiver {
        background: #1c0a11;
        color: white;
        align-self: flex-start;
      }

      .receiver-logo {
        position: absolute;
        left: -40px;
        top: 50%;
        transform: translateY(-50%);
        width: 30px;
        height: auto;
      }

      .input-group-container {
        width: 100%;
        padding: 10px;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        z-index: 999;
        position: absolute;
        bottom: 0;
      }

      .txtfil {
        border-radius: 50px;
        background-image: linear-gradient(to right, #eb7598, #2e2528);
        border: none;
      }

      .btn-color {
        background-image: linear-gradient(to right, #e11f59, #1c0a11);
        color: white;
      }

      .welcome-screen {
        text-align: center;
        color: white;
        font-size: 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        margin-top: -10px;
      }
    </style>
  </head>
  <body>
    <div class="gradient-box">
      <img
        src="../AYU/resources/AI_AURO_WHITE.png"
        alt="Logo"
        class="logo"
      />
      <img
        src="../AYU/resources/ayu-logo.png"
        alt="Center Top Logo"
        class="center-top-logo"
        id="centerTopLogo"
      />
      <div class="chat-container" id="chatContainer">
        <div class="welcome-screen" id="welcomeScreen">
          <p>I am Your Home Health Assistant. How Can I Assist You?</p>
        </div>
      </div>
      <div class="input-group-container">
        <div class="input-group">
          <input
            type="text"
            class="form-control txtfil"
            id="userInput"
            placeholder="How are you feeling right now?"
            onkeypress="handleKeyPress(event)"
          />
          <button class="btn btn-color" onclick="sendMessage()">
            <i class="fa-solid fa-arrow-right"></i>
            Send
          </button>
          <button class="btn btn-color">
            <i class="fa-solid fa-microphone"></i>
          </button>
        </div>
      </div>
    </div>

    <script>
      function sendMessage() {
        let inputField = document.getElementById("userInput");
        let chatContainer = document.getElementById("chatContainer");
        let welcomeScreen = document.getElementById("welcomeScreen");
        let centerTopLogo = document.getElementById("centerTopLogo");
        let messageText = inputField.value.trim();

        if (messageText !== "") {
          if (welcomeScreen) welcomeScreen.remove();
          if (centerTopLogo) centerTopLogo.remove();

          // User message
          let senderMessage = document.createElement("div");
          senderMessage.classList.add("message", "sender");
          senderMessage.innerText = messageText;
          chatContainer.appendChild(senderMessage);
          chatContainer.scrollTop = chatContainer.scrollHeight;
          inputField.value = "";

          // Backend request
          fetch(
            "http://localhost/AYU/chatGPT.php?query=" +
              encodeURIComponent(messageText)
          )
            .then((res) =>
              res.ok ? res.json() : Promise.reject("Error fetching response")
            )
            .then((data) => {
              let botMessage = document.createElement("div");
              botMessage.classList.add(
                "message",
                "receiver",
                "position-relative"
              );
              botMessage.innerHTML = `
                    <img src="../AYU/resources/ayu-logo.png" alt="Ayu Logo" class="receiver-logo">
                    ${data.response}
                `;
              chatContainer.appendChild(botMessage);
              chatContainer.scrollTop = chatContainer.scrollHeight;
            })
            .catch((error) => {
              let errorMessage = document.createElement("div");
              errorMessage.classList.add("message", "receiver");
              errorMessage.innerText =
                "An error occurred while connecting to the server.";
              chatContainer.appendChild(errorMessage);
            });
        }
      }
      function handleKeyPress(event) {
        if (event.key === "Enter") {
          sendMessage();
        }
      }
    </script>
  </body>
</html>
