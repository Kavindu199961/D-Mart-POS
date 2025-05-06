@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">AI ChatBot (ChatGPT)</h2>
    <div class="card">
        <div class="card-body">
            <div id="chatBox" class="mb-3" style="max-height: 400px; overflow-y: auto; border: 1px solid #ccc; padding: 15px; border-radius: 5px;">
                <!-- Messages will appear here -->
            </div>
            <form id="chatForm">
                <div class="input-group">
                    <input type="text" id="message" class="form-control" placeholder="Type your message..." required>
                    <button type="submit" class="btn btn-primary">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('chatForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const message = document.getElementById('message').value;
    const chatBox = document.getElementById('chatBox');

    // Show user message
    chatBox.innerHTML += `<div><strong>You:</strong> ${message}</div>`;
    document.getElementById('message').value = '';

    const response = await fetch("{{ route('admin.chat.send') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ message: message })
    });

    const data = await response.json();

    // Show bot reply
    chatBox.innerHTML += `<div><strong>Bot:</strong> ${data.reply}</div>`;
    chatBox.scrollTop = chatBox.scrollHeight;
});
</script>
@endsection
