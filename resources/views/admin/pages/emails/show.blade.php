<style>

</style>
</style>
<div class="email-header">
    <h2 class="email-subject">{{ $email['subject'] }}</h2>
    <p><strong>From:</strong> {{ $email['from'] }}</p>
    <p><strong>To:</strong> {{ $email['to'] }}</p>
    @if ($email['cc'])
    <p><strong>CC:</strong> {{ $email['cc'] }}</p>
    @endif
    @if ($email['bcc'])
    <p><strong>BCC:</strong> {{ $email['bcc'] }}</p>
    @endif
    <p><strong>Date:</strong> {{ $email['date'] }}</p>
</div>
<div class="email-body">
    {!! $email['body'] !!}
</div>
<div class="email-footer">
    <p><strong>Snippet:</strong> {{ $email['snippet'] }}</p>
</div>