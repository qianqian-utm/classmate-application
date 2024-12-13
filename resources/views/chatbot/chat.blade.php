@extends('layouts.app')

@section('content')

<div id="chatbot">
        <h1>AI Chatbot</h1>
        <div id="chatbox">
            <div id="messages"></div>
            <textarea id="user-input" placeholder="Type your question..."></textarea>
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>
    <script>
        async function sendMessage() {
            const input = document.getElementById('user-input');
            const messages = document.getElementById('messages');
            const userMessage = input.value;

            // Display user message
            messages.innerHTML += `<div class="user-message">${userMessage}</div>`;

            try {
                const response = await axios.post('/chatbot/prompt', { question: userMessage });
                const botResponse = response.data.response;

                // Display chatbot response
                messages.innerHTML += `<div class="bot-message">${botResponse}</div>`;
            } catch (error) {
                messages.innerHTML += `<div class="bot-message">Error: Unable to fetch response.</div>`;
            }

            input.value = '';
        }
    </script>

@endsection