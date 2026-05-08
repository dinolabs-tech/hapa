<?php if ($_SESSION['role'] !== 'Student' && $_SESSION['role'] !== 'Parent' && $_SESSION['role'] !== 'Alumni') { ?>
  <!-- Chat Bubble Button -->
  <button id="chatBubble" onclick="toggleChat()">
    <i class="fa-solid fa-robot"></i>
  </button>
<?php  } ?>


<!-- Chat Widget Window -->
<div id="chatWidget">
  <div class="widget-header">
    <span>DinoPilot</span>
    <button class="close-btn" onclick="toggleChat()">✕</button>
  </div>

  <div id="chat">
    <!-- Welcome message -->
    <div class="message ai">
      👋 Hello and welcome!

      I'm Mira, your virtual assistant and I'm here to help answer your questions about Eduhive.

      Feel free to ask me anything 😊
    </div>
  </div>

  <div class="input-area">
    <textarea
      id="prompt"
      placeholder="Type your message here..."></textarea>

    <button onclick="sendMessage()">
      Send Message
    </button>
  </div>
</div>

<script>
  let chatOpen = false;

  function toggleChat() {
    const widget = document.getElementById('chatWidget');
    const bubble = document.getElementById('chatBubble');

    if (chatOpen) {
      widget.classList.remove('active');
      bubble.style.transform = 'scale(1) rotate(0deg)';
      chatOpen = false;
    } else {
      widget.classList.add('active');
      bubble.style.transform = 'scale(0.9) rotate(90deg)';
      chatOpen = true;

      setTimeout(() => {
        document.getElementById('chat').scrollTop = document.getElementById('chat').scrollHeight;
      }, 100);
    }
  }

  async function sendMessage() {

    const promptInput = document.getElementById("prompt");
    const chat = document.getElementById("chat");

    const prompt = promptInput.value.trim();

    if (!prompt) {
      return;
    }

    // Add user message
    chat.innerHTML += `
            <div class="message user">
                ${prompt}
            </div>
        `;

    const typingId = "typing-" + Date.now();

    // Typing indicator
    chat.innerHTML += `
            <div
                class="message ai typing"
                id="${typingId}"
            >
                Assistant is typing...
            </div>
        `;

    // Scroll down
    chat.scrollTop = chat.scrollHeight;

    // Clear textarea
    promptInput.value = "";

    try {

      const response = await fetch("dinopilot/chat.php", {

        method: "POST",

        headers: {
          "Content-Type": "application/json"
        },

        body: JSON.stringify({
          prompt: prompt
        })
      });

      const data = await response.json();

      // Remove typing
      document.getElementById(typingId).remove();

      // Check for human agent escalation
      if (data.escalate === true) {
        // Activate live chat mode
        document.querySelector('.widget-header').innerHTML = `🔴 LIVE CHAT - Customer Care Agent <button class="close-btn" onclick="toggleChat()">✕</button>`;
        document.querySelector('.widget-header').style.background = '#dc2626';

        // Show transfer message
        chat.innerHTML += `
                    <div class="message ai" style="background: #fef3c7; color: #92400e; border: 1px solid #fcd34d;">
                        ⚡ ${data.message}
                        <br><br>
                        ✅ Connection established
                        <br>
                        ⏱️ Estimated wait time: ${data.wait_time}
                        <br><br>
                        <em>You are now connected with our customer support team. An agent will join this chat shortly.</em>
                    </div>
                `;

        // Change interface to live agent mode
        document.querySelector('button:not(.close-btn)').innerHTML = "Message Agent";
        document.querySelector('button:not(.close-btn)').style.background = '#dc2626';

        // Add agent online indicator
        chat.innerHTML += `
                    <div class="message ai typing">
                        <span style="display: inline-block; width: 8px; height: 8px; background: #22c55e; border-radius: 50%; margin-right: 8px; animation: pulse 1.5s infinite;"></span>
                        Agent is connecting to chat...
                    </div>
                `;

        // Scroll to bottom
        chat.scrollTop = chat.scrollHeight;
        return;
      }

      const aiMessage =
        data?.choices?.[0]?.message?.content ||
        data?.error ||
        "Sorry, I could not generate a response.";

      // Add AI response
      chat.innerHTML += `
                <div class="message ai">
                    ${formatResponse(aiMessage)}
                </div>
            `;

    } catch (error) {

      console.error(error);

      document.getElementById(typingId).remove();

      chat.innerHTML += `
                <div class="message ai">
                    Sorry, something went wrong while connecting to the assistant.
                </div>
            `;
    }

    // Auto scroll
    chat.scrollTop = chat.scrollHeight;
  }


  // Better formatting
  function formatResponse(text) {

    return text
      .replace(/\n/g, "<br>")
      .replace(/\*\*(.*?)\*\*/g, "<strong>$1</strong>");
  }


  // Send on Enter
  document.getElementById("prompt")
    .addEventListener("keydown", function(e) {

      if (e.key === "Enter" && !e.shiftKey) {

        e.preventDefault();

        sendMessage();
      }
    });
</script>


<footer class="footer">
  <div class="container-fluid d-flex justify-content-between">

    <div class="copyright">
      Copyright &copy; <?= date('Y'); ?> <strong><span>Dinolabs Tech Services</span></strong>. All Rights Reserved
    </div>
    <div>
      Designed by <a href="https://www.dinolabstech.com">Dinolabs Tech Services</a>
    </div>
  </div>
</footer>