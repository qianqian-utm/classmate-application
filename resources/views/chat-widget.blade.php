<div id="chat-widget" class="chat-widget">
    <button id="chat-toggle" class="chat-toggle">
        <i class="fas fa-comments"></i>
    </button>
    
    <div id="chat-popup" class="chat-popup">
        <div class="chat-header">
            <h5 class="m-0">AI Assistant</h5>
            <button class="close-btn"><i class="fas fa-times"></i></button>
        </div>
        <div class="chat-body">
            <div id="messages"></div>
        </div>
        <div class="chat-footer">
            <textarea id="user-input" placeholder="Type your message..." rows="1"></textarea>
            <button onclick="sendMessage()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<style>
.chat-widget {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
}

.chat-toggle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #007bff;
    color: white;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.chat-popup {
    display: none;
    position: absolute;
    bottom: 80px;
    right: 0;
    width: 350px;
    height: 500px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.2);
}

.chat-header {
    padding: 15px;
    background: #007bff;
    color: white;
    border-radius: 10px 10px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.close-btn {
    background: none;
    border: none;
    color: white;
}

.chat-body {
    height: 380px;
    overflow-y: auto;
    padding: 15px;
}

.chat-footer {
    padding: 10px;
    border-top: 1px solid #dee2e6;
    display: flex;
    gap: 10px;
}

.chat-footer textarea {
    flex-grow: 1;
    border: 1px solid #dee2e6;
    border-radius: 20px;
    padding: 8px 15px;
    resize: none;
}

.chat-footer button {
    background: #007bff;
    color: white;
    border: none;
    border-radius: 50%;
    width: 35px;
    height: 35px;
}

.message-container {
    display: flex;
    gap: 8px;
    margin: 16px 0;
    align-items: flex-start;
}

.user-message-container {
    flex-direction: row-reverse;
}

.avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.user-avatar {
    background-color: #007bff;
    color: white;
}

.bot-avatar {
    background-color: #6c757d;
    color: white;
}

.message {
    max-width: 70%;
    padding: 12px 16px;
    border-radius: 15px;
    position: relative;
}

.user-message {
    background-color: #007bff;
    color: white;
    border-top-right-radius: 4px;
}

.bot-message {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-top-left-radius: 4px;
}
</style>

<script>
document.getElementById('chat-toggle').addEventListener('click', function() {
    const popup = document.getElementById('chat-popup');
    popup.style.display = popup.style.display === 'none' ? 'block' : 'none';
});

document.querySelector('.close-btn').addEventListener('click', function() {
    document.getElementById('chat-popup').style.display = 'none';
});

async function sendMessage() {
    const input = document.getElementById('user-input');
    const messages = document.getElementById('messages');
    const userMessage = input.value.trim();

    if (!userMessage) return;

    // User message
    messages.innerHTML += `
        <div class="message-container user-message-container">
            <div class="avatar user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="message user-message">${userMessage}</div>
        </div>
    `;
    messages.scrollTop = messages.scrollHeight;

    try {
        const response = await axios.post('/chatbot/prompt', { question: userMessage });
        const botResponse = response.data.response;
        
        // Bot message
        messages.innerHTML += `
            <div class="message-container">
                <div class="avatar bot-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="message bot-message">${botResponse}</div>
            </div>
        `;
    } catch (error) {
        messages.innerHTML += `
            <div class="message-container">
                <div class="avatar bot-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="message bot-message alert-danger">Error: Unable to fetch response.</div>
            </div>
        `;
    }

    input.value = '';
    messages.scrollTop = messages.scrollHeight;
}

document.getElementById('user-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});
</script>