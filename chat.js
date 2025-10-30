const chatWidget = document.getElementById("chatWidget");
const chatToggle = document.getElementById("chatToggle");
const closeChat = document.getElementById("closeChat");
const chatBody = document.getElementById("chatBody");
const chatInput = document.getElementById("chatInput");
const sendBtn = document.getElementById("sendBtn");

// Toggle bật/tắt
chatToggle.onclick = () => {
  chatWidget.style.display =
    chatWidget.style.display === "block" ? "none" : "block";
};
closeChat.onclick = () => (chatWidget.style.display = "none");

// Hiển thị tin nhắn
function appendMsg(role, text) {
  const msg = document.createElement("div");
  msg.className = "msg " + role;
  const bubble = document.createElement("div");
  bubble.className = "bubble " + role;
  bubble.textContent = text;
  msg.appendChild(bubble);
  chatBody.appendChild(msg);
  chatBody.scrollTop = chatBody.scrollHeight;
}

// Gửi tin nhắn
async function sendMessage() {
  const text = chatInput.value.trim();
  if (!text) return;

  appendMsg("user", text);
  chatInput.value = "";
  appendMsg("bot", "Đang trả lời... 🤖");

  try {
    const res = await fetch("chat.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ message: text }),
    });
    const data = await res.json();
    chatBody.lastChild.remove();
    appendMsg("bot", data.reply);
  } catch (e) {
    chatBody.lastChild.remove();
    appendMsg("bot", "⚠️ Có lỗi khi kết nối máy chủ.");
  }
}

sendBtn.onclick = sendMessage;
chatInput.addEventListener("keypress", (e) => {
  if (e.key === "Enter") sendMessage();
});
