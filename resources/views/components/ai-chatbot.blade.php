{{-- AI Chatbot Component --}}
<div id="ai-chatbot-container">
    {{-- Floating Chat Button --}}
    <button id="chatbot-toggle" class="chatbot-toggle" onclick="toggleChatbot()">
        <i class="fas fa-robot"></i>
    </button>

    {{-- Chat Window --}}
    <div id="chatbot-window" class="chatbot-window">
        <div class="chatbot-header">
            <div class="d-flex align-items-center">
                <div class="chatbot-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="ms-3">
                    <h5 class="mb-0 chatbot-title">AI Assistant</h5>
                    <small class="chatbot-subtitle">Ask me anything!</small>
                </div>
            </div>
            <button class="btn-close-chat" onclick="toggleChatbot()" title="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="chatbot-messages" id="chatbot-messages">
            <div class="message bot-message">
                <div class="message-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="message-content">
                    <p>Hello! I can help you with your employee information:</p>
                    <ul class="help-list">
                        <li>💰 <strong>Your Payslip</strong> - Check salary details</li>
                        <li>📅 <strong>Your Schedule</strong> - View shift times</li>
                        <li>⏰ <strong>Attendance</strong> - Your daily records</li>
                        <li>🏖️ <strong>Leave Balance</strong> - Check available days</li>
                        <li>⏳ <strong>Overtime</strong> - Your OT hours</li>
                        <li>🎉 <strong>Holidays</strong> - Upcoming events</li>
                    </ul>
                    <p>What would you like to know?</p>
                </div>
            </div>
        </div>

        <div class="chatbot-quick-actions">
            <button class="quick-action-btn" onclick="sendQuickMessage('My attendance')">
                <i class="fas fa-clock"></i> Attendance
            </button>
            <button class="quick-action-btn" onclick="sendQuickMessage('What is my shift?')">
                <i class="fas fa-calendar-day"></i> Shift
            </button>
            <button class="quick-action-btn" onclick="sendQuickMessage('Show my payslip')">
                <i class="fas fa-file-invoice-dollar"></i> Payslip
            </button>
            <button class="quick-action-btn" onclick="sendQuickMessage('Next holiday?')">
                <i class="fas fa-umbrella-beach"></i> Holiday
            </button>
            <button class="quick-action-btn" onclick="sendQuickMessage('Leave balance?')">
                <i class="fas fa-calendar-check"></i> Leaves
            </button>
            <button class="quick-action-btn" onclick="sendQuickMessage('My overtime')">
                <i class="fas fa-business-time"></i> Overtime
            </button>
            <button class="quick-action-btn" onclick="sendQuickMessage('My profile info')">
                <i class="fas fa-user"></i> Profile
            </button>
        </div>

        <div class="chatbot-input">
            <input type="text" id="chatbot-input-field" placeholder="Type your message..." onkeypress="handleKeyPress(event)">
            <button onclick="sendMessage()" class="btn-send" title="Send message">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
        <!-- File preview and voice input removed -->
    </div>
</div>

<style>
    #ai-chatbot-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }

    .chatbot-toggle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4338ca 0%, #7c3aed 100%);
        border: 2px solid white;
        color: white;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 6px 24px rgba(67, 56, 202, 0.5);
        transition: all 0.3s ease;
        position: relative;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            box-shadow: 0 6px 24px rgba(67, 56, 202, 0.5);
        }
        50% {
            box-shadow: 0 8px 32px rgba(124, 58, 237, 0.7);
        }
    }

    .chatbot-toggle:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 32px rgba(124, 58, 237, 0.8);
        animation: none;
    }

    .chat-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #ff4757;
        color: white;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 10px;
        font-weight: bold;
    }

    .chatbot-window {
        position: fixed;
        bottom: 85px;
        right: 20px;
        width: 360px;
        height: 520px;
        max-height: calc(100vh - 100px);
        border-radius: 16px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(99, 102, 241, 0.1);
        display: none;
        flex-direction: column;
        overflow: hidden;
        animation: slideUp 0.3s ease;
        border: 1px solid rgba(99, 102, 241, 0.2);
    }

    /* Light mode chatbot window */
    body.light .chatbot-window {
        background: white;
    }

    /* Dark mode chatbot window */
    body.dark .chatbot-window {
        background: #2d2d2d;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .chatbot-window.active {
        display: flex;
    }

    .chatbot-header {
        background: linear-gradient(135deg, #4338ca 0%, #7c3aed 100%);
        color: white;
        padding: 16px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        box-shadow: 0 4px 12px rgba(67, 56, 202, 0.3);
        position: sticky;
        top: 0;
        z-index: 100;
        flex-shrink: 0;
        min-height: 85px;
    }

    .chatbot-title {
        font-size: 15px;
        font-weight: 750;
        margin: 0;
        line-height: 1.3;
        color: white;
    }

    .chatbot-subtitle {
        font-size: 11px;
        opacity: 0.9;
        color: rgba(255, 255, 255, 0.95);
        display: block;
        margin-top: 2px;
    }

    .chatbot-avatar {
        width: 36px;
        height: 36px;
        background: rgba(255, 255, 255, 0.25);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        flex-shrink: 0;
    }

    .btn-close-chat {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        font-size: 16px;
        cursor: pointer;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .btn-close-chat:hover {
        background: rgba(255, 255, 255, 0.35);
        transform: scale(1.05);
    }

    .chatbot-messages {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 16px;
        min-height: 0;
    }

    /* Light mode messages background */
    body.light .chatbot-messages {
        background: #f8f9fa;
    }

    /* Dark mode messages background */
    body.dark .chatbot-messages {
        background: #1a1a1a;
    }

    .message {
        display: flex;
        margin-bottom: 15px;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .message-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .bot-message .message-avatar {
        background: linear-gradient(135deg, #4338ca 0%, #7c3aed 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(67, 56, 202, 0.4);
    }

    .user-message {
        flex-direction: row-reverse;
    }

    .user-message .message-avatar {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.4);
    }

    .message-content {
        max-width: 70%;
        padding: 12px 16px;
        border-radius: 15px;
        margin: 0 10px;
    }

    /* Light mode bot message */
    body.light .bot-message .message-content {
        background: white;
        color: #212529;
        border-bottom-left-radius: 5px;
    }

    /* Dark mode bot message */
    body.dark .bot-message .message-content {
        background: #343a40;
        color: #ffffff;
        border-bottom-left-radius: 5px;
    }

    .user-message .message-content {
        background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%);
        color: white;
        border-bottom-right-radius: 5px;
        box-shadow: 0 2px 8px rgba(67, 56, 202, 0.3);
    }

    .chat-link {
        color: #4338ca;
        text-decoration: underline;
        font-weight: 600;
        cursor: pointer;
    }

    .chat-link:hover {
        color: #3730a3;
    }

    body.dark .chat-link {
        color: #a5b4fc;
    }

    .message-content .btn {
        margin-top: 10px;
        display: inline-block;
        text-align: center;
    }

    .help-list {
        margin: 10px 0;
        padding-left: 20px;
        font-size: 14px;
    }

    .help-list li {
        margin: 5px 0;
    }

    .chatbot-quick-actions {
        padding: 10px 12px;
        border-top: 1px solid #e9ecef;
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
        flex-shrink: 0;
        justify-content: center;
    }

    /* Light mode quick actions */
    body.light .chatbot-quick-actions {
        background: white;
        border-top-color: #e9ecef;
    }

    /* Dark mode quick actions */
    body.dark .chatbot-quick-actions {
        background: #2d2d2d;
        border-top-color: #444;
    }

    .quick-action-btn {
        flex: 0 1 auto;
        min-width: 75px;
        max-width: 95px;
        padding: 8px 10px;
        border: 2px solid #6366f1;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        white-space: nowrap;
        text-align: center;
    }
    
    .quick-action-btn i {
        display: block;
        font-size: 16px;
        margin-bottom: 3px;
    }

    /* Light mode quick action buttons */
    body.light .quick-action-btn {
        background: white;
        color: #4338ca;
    }

    body.light .quick-action-btn:hover {
        background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(67, 56, 202, 0.4);
    }

    /* Dark mode quick action buttons */
    body.dark .quick-action-btn {
        background: #343a40;
        color: #818cf8;
        border-color: #6366f1;
    }

    body.dark .quick-action-btn:hover {
        background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.6);
    }

    .chatbot-input {
        padding: 12px 16px;
        border-top: 1px solid #e9ecef;
        display: flex;
        gap: 8px;
        align-items: center;
        flex-shrink: 0;
        background: inherit;
    }

    /* Light mode input */
    body.light .chatbot-input {
        background: white;
        border-top-color: #e9ecef;
    }

    /* Dark mode input */
    body.dark .chatbot-input {
        background: #2d2d2d;
        border-top-color: #444;
    }

    #chatbot-input-field {
        flex: 1;
        border: 1px solid #e9ecef;
        border-radius: 25px;
        padding: 10px 16px;
        outline: none;
        font-size: 13px;
        min-width: 0;
    }

    /* Light mode input field */
    body.light #chatbot-input-field {
        background: white;
        color: #212529;
        border-color: #e9ecef;
    }

    /* Dark mode input field */
    body.dark #chatbot-input-field {
        background: #343a40;
        color: #ffffff;
        border-color: #555;
    }

    #chatbot-input-field:focus {
        border-color: #667eea;
    }

    .btn-send {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4338ca 0%, #7c3aed 100%);
        border: none;
        color: white;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 3px 10px rgba(67, 56, 202, 0.3);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }

    .btn-send:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 14px rgba(124, 58, 237, 0.5);
    }

    .btn-attach {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: transparent;
        border: 2px solid #6366f1;
        color: #6366f1;
        cursor: pointer;
        transition: all 0.3s;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }

    /* Light mode attach button */
    body.light .btn-attach {
        color: #4338ca;
        border-color: #4338ca;
    }

    body.light .btn-attach:hover {
        background: #4338ca;
        color: white;
        transform: scale(1.05);
    }

    /* Dark mode attach button */
    body.dark .btn-attach {
        color: #818cf8;
        border-color: #6366f1;
    }

    body.dark .btn-attach:hover {
        background: #6366f1;
        color: white;
        transform: scale(1.05);
    }

    .btn-voice {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: transparent;
        border: 2px solid #10b981;
        color: #10b981;
        cursor: pointer;
        transition: all 0.3s;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }

    /* Light mode voice button */
    body.light .btn-voice {
        color: #10b981;
        border-color: #10b981;
    }

    body.light .btn-voice:hover {
        background: #10b981;
        color: white;
        transform: scale(1.05);
    }

    body.light .btn-voice.recording {
        background: #ef4444;
        border-color: #ef4444;
        color: white;
        animation: recordingPulse 1s infinite;
    }

    /* Dark mode voice button */
    body.dark .btn-voice {
        color: #34d399;
        border-color: #10b981;
    }

    body.dark .btn-voice:hover {
        background: #10b981;
        color: white;
        transform: scale(1.05);
    }

    body.dark .btn-voice.recording {
        background: #ef4444;
        border-color: #ef4444;
        color: white;
        animation: recordingPulse 1s infinite;
    }

    @keyframes recordingPulse {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
        }
        50% {
            box-shadow: 0 0 0 8px rgba(239, 68, 68, 0);
        }
    }

    .file-preview {
        padding: 10px 20px;
        border-top: 1px solid #e9ecef;
    }

    /* Light mode file preview */
    body.light .file-preview {
        background: #f8f9fa;
        border-top-color: #e9ecef;
    }

    /* Dark mode file preview */
    body.dark .file-preview {
        background: #1a1a1a;
        border-top-color: #444;
    }

    .file-preview-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 13px;
    }

    /* Light mode file preview content */
    body.light .file-preview-content {
        background: white;
        color: #212529;
        border: 1px solid #dee2e6;
    }

    /* Dark mode file preview content */
    body.dark .file-preview-content {
        background: #343a40;
        color: #ffffff;
        border: 1px solid #555;
    }

    .file-name {
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        margin-right: 10px;
    }

    .btn-remove-file {
        background: transparent;
        border: none;
        color: #dc3545;
        cursor: pointer;
        font-size: 14px;
        padding: 4px 8px;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .btn-remove-file:hover {
        background: #dc3545;
        color: white;
    }

    .typing-indicator {
        display: flex;
        gap: 5px;
        padding: 10px;
    }

    .typing-dot {
        width: 8px;
        height: 8px;
        background: #667eea;
        border-radius: 50%;
        animation: typing 1.4s infinite;
    }

    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }

    @keyframes typing {
        0%, 60%, 100% { transform: translateY(0); }
        30% { transform: translateY(-10px); }
    }

    /* Responsive Design for Different Devices */
    
    /* Large Desktop (1920px+) */
    @media (min-width: 1920px) {
        .chatbot-window {
            width: 380px;
            height: 540px;
        }
        .chatbot-toggle {
            width: 60px;
            height: 60px;
            font-size: 24px;
        }
    }

    /* Desktop/Laptop (1024px - 1919px) - Default styles already set */
    @media (min-width: 1024px) and (max-width: 1919px) {
        .chatbot-window {
            width: 360px;
            height: 520px;
        }
    }

    /* Tablet Portrait (768px - 1023px) */
    @media (min-width: 768px) and (max-width: 1023px) {
        .chatbot-window {
            width: 340px;
            height: 500px;
            bottom: 85px;
        }
        
        .chatbot-toggle {
            width: 56px;
            height: 56px;
            font-size: 22px;
        }
        
        .quick-action-btn {
            font-size: 10px;
            padding: 7px 8px;
            min-width: 70px;
            max-width: 85px;
        }

        .btn-send, .btn-voice, .btn-attach {
            width: 36px;
            height: 36px;
            font-size: 14px;
        }
    }

    /* Mobile (max-width: 767px) */
    @media (max-width: 767px) {
        #ai-chatbot-container {
            bottom: 12px;
            right: 12px;
        }

        .chatbot-toggle {
            width: 54px;
            height: 54px;
            font-size: 21px;
            border-width: 2px;
        }

        .chatbot-window {
            width: calc(100vw - 24px);
            height: calc(100dvh - 110px);
            max-height: calc(100dvh - 110px);
            right: 12px;
            bottom: calc(75px + env(safe-area-inset-bottom));
            border-radius: 14px;
            max-width: 380px;
        }

        .chatbot-header {
            padding: 12px 14px;
            padding-top: calc(12px + env(safe-area-inset-top));
            min-height: 60px;
        }

        .chatbot-title {
            font-size: 14px;
        }

        .chatbot-subtitle {
            font-size: 10px;
        }

        .chatbot-avatar {
            width: 32px;
            height: 32px;
            font-size: 16px;
        }

        .btn-close-chat {
            width: 28px;
            height: 28px;
            font-size: 13px;
        }
        
        .chatbot-quick-actions {
            padding: 8px 10px;
            gap: 4px;
        }

        .quick-action-btn {
            min-width: 65px;
            max-width: 75px;
            font-size: 9px;
            padding: 6px 7px;
        }
        
        .quick-action-btn i {
            font-size: 14px;
        }

        .chatbot-input {
            padding: 10px 12px;
            gap: 6px;
        }

        #chatbot-input-field {
            font-size: 12px;
            padding: 8px 12px;
        }

        .btn-send, .btn-voice, .btn-attach {
            width: 36px;
            height: 36px;
            font-size: 14px;
        }

        .message-content {
            max-width: 80%;
            font-size: 14px;
        }
    }

    /* Extra Small Mobile (max-width: 375px) - iPhone SE, etc */
    @media (max-width: 375px) {
        .chatbot-window {
            width: calc(100vw - 8px);
            right: 4px;
            height: calc(100vh - 85px);
        }

        .chatbot-header {
            padding: 12px;
        }
        
        .chatbot-quick-actions {
            padding: 7px 8px;
            gap: 3px;
        }

        .quick-action-btn {
            min-width: 60px;
            max-width: 70px;
            font-size: 8.5px;
            padding: 5px 5px;
        }
        
        .quick-action-btn i {
            font-size: 13px;
            margin-bottom: 2px;
        }

        .message-content {
            font-size: 13px;
        }
    }
</style>

<script>
    let selectedFile = null;
    let chatLoaded = false;

    async function toggleChatbot() {
        const window = document.getElementById('chatbot-window');
        window.classList.toggle('active');
        
        // Load history when opened for the first time
        if (window.classList.contains('active') && !chatLoaded) {
            await loadChatHistory();
        }
    }

    async function loadChatHistory() {
        try {
            console.log('Loading chat history...');
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.log('No CSRF token found, skipping history load');
                chatLoaded = true;
                return;
            }

            const response = await fetch('/api/chatbot/history', {
                headers: {
                    'X-CSRF-TOKEN': csrfToken.content,
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });
            
            console.log('History API response status:', response.status);

            if (!response.ok) {
                console.log('No chat history available or user not authenticated');
                chatLoaded = true;
                return;
            }

            const messages = await response.json();
            console.log('Retrieved messages:', messages);
            
            if (Array.isArray(messages) && messages.length > 0) {
                const container = document.getElementById('chatbot-messages');
                container.innerHTML = ''; // Clear initial greeting if history exists
                
                messages.forEach(msg => {
                    if (msg && msg.message && msg.sender_type) {
                        console.log('Adding historical message:', msg.sender_type, msg.message.substring(0, 50) + '...');
                        addMessage(msg.message, msg.sender_type, false); // false to not scroll every time
                    }
                });
                
                container.scrollTop = container.scrollHeight;
                console.log('Chat history loaded successfully');
            } else {
                console.log('No messages found in history');
            }
            chatLoaded = true;
        } catch (error) {
            console.error('Error loading chat history:', error);
            console.log('History load failed, continuing with empty chat');
            chatLoaded = true;
        }
    }

    function handleKeyPress(event) {
        if (event.key === 'Enter') {
            sendMessage();
        }
    }

    function sendQuickMessage(message) {
        const input = document.getElementById('chatbot-input-field');
        input.value = message;
        sendMessage();
    }

    function handleFileSelect(event) {
        const file = event.target.files[0];
        if (file) {
            selectedFile = file;
            const filePreview = document.getElementById('file-preview');
            const fileName = document.getElementById('file-name');
            
            // Format file name with size
            const fileSize = (file.size / 1024).toFixed(2);
            fileName.textContent = `📎 ${file.name} (${fileSize} KB)`;
            
            filePreview.style.display = 'block';
        }
    }

    function removeFile() {
        selectedFile = null;
        document.getElementById('chatbot-file-input').value = '';
        document.getElementById('file-preview').style.display = 'none';
    }

    async function sendMessage(customMessage = null) {
        const input = document.getElementById('chatbot-input-field');
        const message = customMessage || input.value.trim();
        
        if (!message && !selectedFile) return;

        console.log('Sending message:', message);

        // Check CSRF token before sending
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            addMessage('Error: CSRF token not found. Please refresh the page.', 'bot');
            return;
        }

        // Prepare message text
        let messageText = message;
        if (selectedFile) {
            messageText += ` [Attached: ${selectedFile.name}]`;
        }

        // Add user message
        addMessage(messageText, 'user');
        if (!customMessage) input.value = '';
        
        // Clear file if attached
        if (selectedFile) {
            removeFile();
        }

        // Show typing indicator
        showTypingIndicator();

        // Send to backend
        try {
            console.log('Making API call to /api/chatbot');
            const response = await fetch('/api/chatbot', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.content,
                    'Accept': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify({ message: message })
            });

            console.log('API response status:', response.status);

            if (!response.ok) {
                const errorText = await response.text();
                console.error('API Error Response:', errorText);
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const data = await response.json();
            console.log('API response data:', data);
            
            // Remove typing indicator
            removeTypingIndicator();

            // Add bot response
            if (data.response) {
                addMessage(data.response, 'bot');
                // Mark that chatbot has been used for future sessions
                sessionStorage.setItem('chatbot_was_used', 'true');
            } else {
                addMessage('Sorry, I received an empty response. Please try again.', 'bot');
            }
        } catch (error) {
            console.error('Chat error:', error);
            removeTypingIndicator();
            let errorMessage = 'Sorry, I encountered an error. ';
            if (error.message) {
                errorMessage += `Error: ${error.message}`;
            }
            if (error.status) {
                errorMessage += ` (Status: ${error.status})`;
            }
            addMessage(errorMessage, 'bot');
        }
    }

    function addMessage(text, type, shouldScroll = true) {
        const messagesContainer = document.getElementById('chatbot-messages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type}-message`;
        
        const avatar = document.createElement('div');
        avatar.className = 'message-avatar';
        
            // User messages show their profile photo
            @if(auth()->check() && auth()->user()->profile_photo_url)
                avatar.innerHTML = '<img src="{{ auth()->user()->profile_photo_url }}" alt="You" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">';
            @else
                avatar.innerHTML = '<i class="fas fa-user"></i>';
            @endif
        } else {
            // Bot messages show robot icon
        if (type === 'user') {
            // User messages show their profile photo
            @if(auth()->check() && auth()->user()->profile_photo_url)
                avatar.innerHTML = '<img src="{{ auth()->user()->profile_photo_url }}" alt="You" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">';
            @else
                avatar.innerHTML = '<img src="{{ asset('img/avatar.png') }}" alt="Default" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">';
            @endif
        } else {
            // Bot messages show robot icon
            avatar.innerHTML = '<i class="fas fa-robot"></i>';
        }
        
        const content = document.createElement('div');
        content.className = 'message-content';
        content.innerHTML = `<p>${text}</p>`;
        
        messageDiv.appendChild(avatar);
        messageDiv.appendChild(content);
        messagesContainer.appendChild(messageDiv);
        
        // Scroll to bottom
        if (shouldScroll) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    }

    function showTypingIndicator() {
        const messagesContainer = document.getElementById('chatbot-messages');
        const typingDiv = document.createElement('div');
        typingDiv.className = 'message bot-message';
        typingDiv.id = 'typing-indicator';
        typingDiv.innerHTML = `
            <div class="message-avatar"><i class="fas fa-robot"></i></div>
            <div class="message-content">
                <div class="typing-indicator">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>
        `;
        messagesContainer.appendChild(typingDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function removeTypingIndicator() {
        const indicator = document.getElementById('typing-indicator');
        if (indicator) indicator.remove();
    }

    // Voice Input Functionality using Web Speech API
    let recognition = null;
    let isRecording = false;
    let recognitionInitialized = false;
    let fullTranscript = '';

    function initVoiceRecognition() {
        try {
            // Check for Speech Recognition API support
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            
            if (!SpeechRecognition) {
                console.log('❌ Speech recognition not supported in this browser');
                return false;
            }
            
            recognition = new SpeechRecognition();
            console.log('✅ Using SpeechRecognition API');
            console.log('🌐 Browser:', navigator.userAgent);

            recognition.continuous = false; // Stop automatically after speech is detected
            recognition.interimResults = true;
            recognition.lang = 'en-US';
            recognition.maxAlternatives = 1;
            
            // Disable network requirement (works offline)
            try {
                if ('grammars' in recognition) {
                    recognition.grammars = null;
                }
            } catch (e) {
                console.log('⚠️ Could not set grammars:', e);
            }

            recognition.onstart = function() {
                console.log('🎤 Voice recognition STARTED');
                isRecording = true;
                fullTranscript = '';
                const btn = document.getElementById('btn-voice');
                if (btn) {
                    btn.classList.add('recording');
                    console.log('✅ Recording button turned red');
                }
            };

            recognition.onresult = function(event) {
                console.log('📝 Voice recognition RESULT received');
                let interimTranscript = '';
                let finalTranscript = '';

                // Process all results from the beginning
                for (let i = event.resultIndex; i < event.results.length; i++) {
                    const transcript = event.results[i][0].transcript;
                    if (event.results[i].isFinal) {
                        finalTranscript += transcript + ' ';
                    } else {
                        interimTranscript += transcript;
                    }
                }

                const input = document.getElementById('chatbot-input-field');
                if (input) {
                    if (finalTranscript) {
                        fullTranscript += finalTranscript;
                    }
                    input.value = (fullTranscript + interimTranscript).trim();
                }
            };

            recognition.onerror = function(event) {
                console.error('❌ Speech recognition ERROR:', event.error);
                isRecording = false;
                const btn = document.getElementById('btn-voice');
                if (btn) btn.classList.remove('recording');
                
                if (event.error === 'not-allowed' || event.error === 'service-not-allowed') {
                    alert('🎤 Microphone permission denied! Please enable microphone access in your browser settings.');
                } else if (event.error === 'no-speech') {
                    console.log('⚠️ No speech detected - please try again');
                } else if (event.error === 'aborted') {
                    console.log('⚠️ Speech recognition aborted');
                } else if (event.error === 'network') {
                    console.log('⚠️ Network error - continuing with offline mode');
                    // Don't show alert, just log it
                } else {
                    console.log('⚠️ Other error:', event.error);
                }
            };

            recognition.onend = function() {
                console.log('🛑 Voice recognition ENDED');
                
                if (isRecording) {
                    // Auto-restart if user is still recording
                    try {
                        recognition.start();
                        console.log('🔄 Restarting recognition...');
                    } catch (e) {
                        console.log('Could not restart:', e);
                    }
                } else {
                    const btn = document.getElementById('btn-voice');
                    if (btn) btn.classList.remove('recording');
                }

            };

            recognitionInitialized = true;
            console.log('✅ Voice recognition initialized successfully');
            return true;
        } catch (error) {
            console.error('❌ Error initializing voice recognition:', error);
            return false;
        }
    }

    function toggleVoiceInput() {
        console.log('🔘 Voice button clicked');
        console.log('📱 Device:', /iPhone|iPad|iPod|Android/i.test(navigator.userAgent) ? 'Mobile' : 'Desktop');
        
        // Initialize on first click if not already done
        if (!recognitionInitialized) {
            console.log('🔄 Initializing voice recognition...');
            const initialized = initVoiceRecognition();
            if (!initialized) {
                const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
                const browserName = /Safari/i.test(navigator.userAgent) ? 'Safari' : 
                                   /Chrome/i.test(navigator.userAgent) ? 'Chrome' :
                                   /Edge/i.test(navigator.userAgent) ? 'Edge' : 'Unknown';
                alert(`❌ Voice input is not supported in ${browserName} on your ${isMobile ? 'mobile device' : 'browser'}.\n\nPlease try:\n• Chrome (Recommended)\n• Edge\n• Safari`);
                return;
            }
        }

        if (!recognition) {
            alert('❌ Voice input is not available. Please try refreshing the page.');
            return;
        }

        if (isRecording) {
            console.log('⏹️ Stopping voice recognition');
            isRecording = false;
            const btn = document.getElementById('btn-voice');
            if (btn) btn.classList.remove('recording');
            try {
                recognition.stop();
            } catch (error) {
                console.error('Error stopping recognition:', error);
            }
        } else {
            console.log('▶️ Starting voice recognition');
            fullTranscript = '';
            
            // Clear input field before starting
            const input = document.getElementById('chatbot-input-field');
            if (input) {
                input.value = '';
            }
            
            try {
                recognition.start();
                console.log('✅ Recognition start() called');
            } catch (error) {
                console.error('❌ Error starting recognition:', error);
                
                // If already started, try stopping first then starting
                if (error.message && (error.message.includes('already started') || error.name === 'InvalidStateError')) {
                    console.log('⚠️ Recognition already running, stopping and restarting...');
                    try {
                        recognition.stop();
                    } catch (e) {
                        console.error('Error stopping:', e);
                    }
                    
                    setTimeout(() => {
                        try {
                            fullTranscript = '';
                            recognition.start();
                            console.log('✅ Recognition restarted');
                        } catch (e) {
                            console.error('❌ Retry failed:', e);
                            const btn = document.getElementById('btn-voice');
                            if (btn) btn.classList.remove('recording');
                        }
                    }, 300);
                } else {
                    const btn = document.getElementById('btn-voice');
                    if (btn) btn.classList.remove('recording');
                    alert('❌ Could not start voice recognition. Please try again.');
                }
            }
        }
    }

    // Log when the page is loaded to verify script is working
    console.log('🚀 AI Chatbot script loaded successfully');
    console.log('🌐 Browser:', navigator.userAgent);
    console.log('📱 Device:', /iPhone|iPad|iPod|Android/i.test(navigator.userAgent) ? 'Mobile' : 'Desktop');
    console.log('🎤 webkitSpeechRecognition:', 'webkitSpeechRecognition' in window);
    console.log('🎤 SpeechRecognition:', 'SpeechRecognition' in window);
    console.log('✅ Speech Recognition API available:', !!(window.SpeechRecognition || window.webkitSpeechRecognition));

    // Auto-load chat history when page loads (after a short delay to ensure DOM is ready)
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, checking CSRF token...');
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('CSRF token not found! This will cause API calls to fail.');
        } else {
            console.log('CSRF token found:', csrfToken.content.substring(0, 10) + '...');
        }

        setTimeout(async function() {
            if (sessionStorage.getItem('chatbot_was_used') === 'true') {
                console.log('Previous chat session detected, loading history...');
                await loadChatHistory();
            } else {
                console.log('No previous chat session found.');
            }
        }, 500);
    });
</script>
